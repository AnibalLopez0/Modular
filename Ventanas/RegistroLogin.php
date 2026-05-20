<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<link rel="stylesheet" href="../CSS/estilosRegistroLogin.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

</head>

<body>

<div class="container">

    <!-- LOGIN -->
    <div class="form-box login">

        <form action="../Funciones/login.php" method="POST">

            <h1>Iniciar sesión</h1>

            <div class="input-box">
                <input type="email" name="correo" placeholder="Correo" required>
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Contraseña" required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <!-- MENSAJES DE ERROR -->
            <?php if (isset($_GET['error'])): ?>
                <div style="color:red; text-align:center; margin-bottom:10px;">
                    <?php
                        if ($_GET['error'] == 'user') {
                            echo "Usuario no encontrado";
                        } elseif ($_GET['error'] == 'pass') {
                            echo "Contraseña incorrecta";
                        }
                    ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn">Iniciar sesión</button>

        </form>

    </div>


    <!-- REGISTRO MEDICOS -->
    <div class="form-box register">

        <form id="alta-form" action="insertarMedico.php" method="POST">

            <h1>Registrar médico</h1>

            <div class="input-box">
                <input type="text" id="username" name="username" placeholder="Nombre de usuario" required>
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="input-box">
                <input type="email" id="correo" name="correo" placeholder="Correo" required>
                <i class="fa-regular fa-envelope"></i>
            </div>

            <div id="ce"></div>

            <div class="input-box">
                <input type="password" id="pass" name="password" placeholder="Contraseña" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <button type="submit" class="btn">Registrar</button>

            <div id="campos"></div>

        </form>

    </div>
                

    <!-- PANEL LATERAL -->
    <div class="toggle-box">

        <div class="toggle-panel toggle-left">
            <h1 class="mensaje-bienvenida">Hola, bienvenido a Psycotracker</h1>
            <p>
                Si eres médico y aún no tienes cuenta puedes registrarte
            </p>
            <button class="btn register-btn">Registrarse</button>
        </div>

        <div class="toggle-panel toggle-right">
            <h1>Bienvenido!</h1>
            <p>¿Ya estás registrado?</p>
            <button class="btn login-btn">Iniciar sesión</button>
        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../Scripts/animacionesRegistroLogin.js"></script>
<script src="../Scripts/registro.js"></script>

</body>
</html>
