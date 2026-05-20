import os
from dotenv import load_dotenv
from flask import Flask, jsonify
from flask_cors import CORS
import mysql.connector
from mysql.connector import pooling
import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression
from sklearn.ensemble import RandomForestRegressor

# --------------------------------
# Cargar variables de entorno
# --------------------------------

load_dotenv()

DB_CONFIG = {
    "host":     os.getenv("DB_HOST", "db"),
    "user":     os.getenv("DB_USER", "root"),
    "password": os.getenv("DB_PASS", "root"),
    "database": os.getenv("DB_NAME", "modular"),
    "port":     int(os.getenv("DB_PORT", 3306)),
}

# --------------------------------
# Pool de conexiones (mejor para Docker)
# --------------------------------

pool = pooling.MySQLConnectionPool(
    pool_name="modular_pool",
    pool_size=5,
    **DB_CONFIG
)

def get_conn():
    return pool.get_connection()

# --------------------------------
# Crear app Flask
# --------------------------------

app = Flask(__name__)
CORS(app)

# --------------------------------
# Verificar relación terapeuta-paciente
# --------------------------------

def verificar_relacion(id_terapeuta, id_paciente):
    conn = get_conn()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT 1 FROM relaciones
        WHERE id_terapeuta = %s AND id_paciente = %s AND is_active = TRUE
    """, (id_terapeuta, id_paciente))
    resultado = cursor.fetchone()
    cursor.close()
    conn.close()
    return resultado is not None

# --------------------------------
# Obtener lista de conductas del paciente
# --------------------------------

def obtener_conductas(id_paciente):
    conn = get_conn()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("""
        SELECT DISTINCT
            c.id_plantilla,
            cp.titulo,
            cp.usa_intensidad,
            cp.usa_duracion
        FROM conductas c
        JOIN conductas_plantilla cp ON c.id_plantilla = cp.id_plantilla
        WHERE c.id_paciente = %s AND cp.is_active = TRUE
        ORDER BY cp.titulo
    """, (id_paciente,))
    resultado = cursor.fetchall()
    cursor.close()
    conn.close()
    return resultado

# --------------------------------
# Obtener datos históricos de UNA conducta
# --------------------------------

def obtener_datos(id_paciente, id_plantilla):
    conn = get_conn()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT fecha, intensidad, duracion, descripcion
        FROM conductas
        WHERE id_paciente = %s AND id_plantilla = %s
        ORDER BY fecha
    """, (id_paciente, id_plantilla))
    rows = cursor.fetchall()
    cursor.close()
    conn.close()

    if not rows:
        return None

    df = pd.DataFrame(rows, columns=["fecha", "intensidad", "duracion", "descripcion"])
    df["fecha"] = pd.to_datetime(df["fecha"])
    df["dia"] = (df["fecha"] - df["fecha"].min()).dt.days
    return df

# --------------------------------
# Predicción con regresión lineal
# --------------------------------

def predecir_regresion(df, dias=7):
    df = df.dropna(subset=["intensidad"])
    X = df[["dia"]]
    y = df["intensidad"]
    modelo = LinearRegression()
    modelo.fit(X, y)
    ultimo_dia = df["dia"].max()
    predicciones = []
    for i in range(1, dias + 1):
        pred = modelo.predict([[ultimo_dia + i]])[0]
        predicciones.append(round(max(1, min(10, pred)), 2))
    return predicciones

# --------------------------------
# Predicción con Random Forest
# --------------------------------

def predecir_random_forest(df, dias=7):
    df = df.fillna(0)
    X = df[["dia", "duracion"]]
    y = df["intensidad"]
    modelo = RandomForestRegressor(n_estimators=100, random_state=42)
    modelo.fit(X, y)
    ultimo_dia = df["dia"].max()
    duracion_prom = df["duracion"].mean()
    predicciones = []
    for i in range(1, dias + 1):
        pred = modelo.predict([[ultimo_dia + i, duracion_prom]])[0]
        predicciones.append(round(max(1, min(10, pred)), 2))
    return predicciones

# --------------------------------
# Calcular riesgo
# --------------------------------

def calcular_riesgo(predicciones):
    promedio = np.mean(predicciones)
    riesgo = round(promedio / 10, 2)
    if riesgo < 0.4:
        nivel = "bajo"
    elif riesgo < 0.7:
        nivel = "medio"
    else:
        nivel = "alto"
    return riesgo, nivel

# --------------------------------
# Endpoint: todas las conductas del paciente con predicciones
# --------------------------------

@app.route("/dashboard/<int:id_terapeuta>/<int:id_paciente>")
def dashboard(id_terapeuta, id_paciente):

    if not verificar_relacion(id_terapeuta, id_paciente):
        return jsonify({"error": "No autorizado"}), 403

    conductas = obtener_conductas(id_paciente)

    if not conductas:
        return jsonify({"paciente": id_paciente, "conductas": []})

    resultado = []

    for conducta in conductas:
        id_plantilla    = conducta["id_plantilla"]
        titulo          = conducta["titulo"]
        usa_intensidad  = conducta["usa_intensidad"]
        usa_duracion    = conducta["usa_duracion"]

        df = obtener_datos(id_paciente, id_plantilla)

        if df is None or len(df) < 2:
            resultado.append({
                "id_plantilla":   id_plantilla,
                "titulo":         titulo,
                "usa_intensidad": usa_intensidad,
                "usa_duracion":   usa_duracion,
                "error":          "Muy pocos datos para predicción"
            })
            continue

        # Elegir modelo según cantidad de registros
        if len(df) < 10:
            predicciones = predecir_regresion(df, dias=7)
            modelo_usado = "regresion_lineal"
        else:
            predicciones = predecir_random_forest(df, dias=7)
            modelo_usado = "random_forest"

        riesgo, nivel = calcular_riesgo(predicciones)

        fecha_max = df["fecha"].max()
        fechas_pred = [
            (fecha_max + pd.Timedelta(days=i)).strftime("%Y-%m-%d")
            for i in range(1, 8)
        ]

        # Última descripción registrada
        ultima_descripcion = df["descripcion"].dropna().iloc[-1] if df["descripcion"].notna().any() else ""

        resultado.append({
            "id_plantilla":       id_plantilla,
            "titulo":             titulo,
            "descripcion":        ultima_descripcion,
            "usa_intensidad":     bool(usa_intensidad),
            "usa_duracion":       bool(usa_duracion),
            "modelo":             modelo_usado,
            "historial": {
                "fechas":      df["fecha"].dt.strftime("%Y-%m-%d").tolist(),
                "intensidad":  df["intensidad"].fillna(0).tolist(),
                "duracion":    df["duracion"].fillna(0).tolist(),
                "frecuencia":  df.groupby("fecha").size().tolist(),
            },
            "prediccion": {
                "fechas":      fechas_pred,
                "intensidad":  predicciones,
            },
            "riesgo":       riesgo,
            "nivel_riesgo": nivel,
        })

    return jsonify({"paciente": id_paciente, "conductas": resultado})


# --------------------------------
# Endpoint: una sola conducta (opcional, para detalle)
# --------------------------------

@app.route("/prediccion/<int:id_terapeuta>/<int:id_paciente>/<int:id_plantilla>")
def prediccion_conducta(id_terapeuta, id_paciente, id_plantilla):

    if not verificar_relacion(id_terapeuta, id_paciente):
        return jsonify({"error": "No autorizado"}), 403

    df = obtener_datos(id_paciente, id_plantilla)

    if df is None or len(df) < 2:
        return jsonify({"error": "Muy pocos datos para predicción"})

    if len(df) < 10:
        predicciones = predecir_regresion(df, dias=7)
        modelo_usado = "regresion_lineal"
    else:
        predicciones = predecir_random_forest(df, dias=7)
        modelo_usado = "random_forest"

    riesgo, nivel = calcular_riesgo(predicciones)

    fecha_max = df["fecha"].max()
    fechas_pred = [
        (fecha_max + pd.Timedelta(days=i)).strftime("%Y-%m-%d")
        for i in range(1, 8)
    ]

    return jsonify({
        "modelo":    modelo_usado,
        "historial": {
            "fechas":     df["fecha"].dt.strftime("%Y-%m-%d").tolist(),
            "intensidad": df["intensidad"].fillna(0).tolist(),
            "duracion":   df["duracion"].fillna(0).tolist(),
        },
        "prediccion": {
            "fechas":     fechas_pred,
            "intensidad": predicciones,
        },
        "riesgo":       riesgo,
        "nivel_riesgo": nivel,
    })


# --------------------------------
# Health check para Docker
# --------------------------------

@app.route("/health")
def health():
    return jsonify({"status": "ok"})


# --------------------------------
# Ejecutar servidor
# --------------------------------

if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=False, port=5000)
