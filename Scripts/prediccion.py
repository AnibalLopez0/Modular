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

pool = pooling.MySQLConnectionPool(
    pool_name="modular_pool",
    pool_size=5,
    **DB_CONFIG
)

def get_conn():
    return pool.get_connection()

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
# Obtener datos históricos
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
    return df

# --------------------------------
# Agrupar por semana y predecir frecuencia
# --------------------------------

def predecir_frecuencia_semanal(df, semanas=1):
    # Agrupar registros por semana
    df["semana"] = df["fecha"].dt.to_period("W").apply(lambda r: r.start_time)
    semanal = df.groupby("semana").size().reset_index(name="episodios")
    semanal["idx"] = range(len(semanal))

    if len(semanal) < 2:
        # Muy pocos datos, usar promedio
        promedio = semanal["episodios"].mean()
        return round(promedio), semanal

    X = semanal[["idx"]]
    y = semanal["episodios"]

    if len(semanal) >= 6:
        modelo = RandomForestRegressor(n_estimators=100, random_state=42)
        modelo_nombre = "random_forest"
    else:
        modelo = LinearRegression()
        modelo_nombre = "regresion_lineal"

    modelo.fit(X, y)

    ultimo_idx = semanal["idx"].max()
    pred = modelo.predict([[ultimo_idx + 1]])[0]
    pred = max(0, round(pred))

    return pred, semanal, modelo_nombre

# --------------------------------
# Calcular probabilidad de ocurrencia
# --------------------------------

def calcular_probabilidad(episodios_predichos, promedio_historico):
    if promedio_historico == 0:
        return 0.0, "bajo"

    ratio = episodios_predichos / promedio_historico

    if episodios_predichos == 0:
        prob = 0.1
    elif ratio < 0.5:
        prob = 0.3
    elif ratio < 1.0:
        prob = 0.55
    elif ratio < 1.5:
        prob = 0.75
    else:
        prob = 0.90

    if prob < 0.4:
        nivel = "bajo"
    elif prob < 0.7:
        nivel = "medio"
    else:
        nivel = "alto"

    return round(prob, 2), nivel

# --------------------------------
# Endpoint dashboard
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
        id_plantilla   = conducta["id_plantilla"]
        titulo         = conducta["titulo"]
        usa_intensidad = conducta["usa_intensidad"]
        usa_duracion   = conducta["usa_duracion"]

        df = obtener_datos(id_paciente, id_plantilla)

        if df is None or len(df) < 2:
            resultado.append({
                "id_plantilla": id_plantilla,
                "titulo":       titulo,
                "error":        "Muy pocos datos para predicción"
            })
            continue

        # Predicción de frecuencia semanal
        pred_result  = predecir_frecuencia_semanal(df)
        episodios_pred = pred_result[0]
        semanal        = pred_result[1]
        modelo_usado   = pred_result[2] if len(pred_result) > 2 else "promedio"

        promedio_historico = semanal["episodios"].mean()
        probabilidad, nivel = calcular_probabilidad(episodios_pred, promedio_historico)

        # Historial semanal para la gráfica
        semanas_labels = semanal["semana"].dt.strftime("%Y-%m-%d").tolist()
        semanas_vals   = semanal["episodios"].tolist()

        # Próxima semana
        ultima_semana  = semanal["semana"].max()
        proxima_semana = (ultima_semana + pd.Timedelta(weeks=1)).strftime("%Y-%m-%d")

        # Última descripción
        ultima_desc = df["descripcion"].dropna().iloc[-1] if df["descripcion"].notna().any() else ""

        # Duración promedio para mostrar
        dur_prom = round(df["duracion"].dropna().mean(), 1) if usa_duracion and df["duracion"].notna().any() else None

        resultado.append({
            "id_plantilla":      id_plantilla,
            "titulo":            titulo,
            "descripcion":       ultima_desc,
            "usa_intensidad":    bool(usa_intensidad),
            "usa_duracion":      bool(usa_duracion),
            "modelo":            modelo_usado,
            "historial": {
                "fechas":        df["fecha"].dt.strftime("%Y-%m-%d").tolist(),
                "duracion":      df["duracion"].fillna(0).tolist() if usa_duracion else [],
                "semanas":       semanas_labels,
                "freq_semanal":  semanas_vals,
            },
            "prediccion": {
                "proxima_semana":   proxima_semana,
                "episodios":        int(episodios_pred),
                "promedio_historico": round(float(promedio_historico), 1),
            },
            "probabilidad":  probabilidad,
            "nivel":         nivel,
        })

    return jsonify({"paciente": id_paciente, "conductas": resultado})


# --------------------------------
# Health check
# --------------------------------

@app.route("/health")
def health():
    return jsonify({"status": "ok"})


# --------------------------------
# Ejecutar
# --------------------------------

if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=False, port=5000)
