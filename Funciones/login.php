<?php
session_start();
require_once "../Funciones/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $con = conecta();

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {

            $_SESSION['id'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['username'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirección por tipo de usuario
            if ($usuario['rol'] == 'terapeuta') {
                header("Location: ../Ventanas/Central.php");
            } else {
                header("Location: ../Ventanas/Central.php");
            }
            exit();

        } else {
            header("Location: ../Ventanas/RegistroLogin.php?error=pass");
            exit();
        }

    } else {
        header("Location: ../Ventanas/RegistroLogin.php?error=user");
        exit();
    }

    $stmt->close();
    $con->close();
}
?>