<?php

ini_set('display_errors', 0);
error_reporting(E_ALL);

require __DIR__ . '/conn.php';

$con = conecta();

$username = $_POST['username'] ?? '';
$correo   = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

if($username == '' || $correo == '' || $password == ''){
    echo "ERROR: Datos incompletos";
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$rol = "terapeuta";

try {

    $sql = "INSERT INTO usuarios 
    (nombre, username, email, password, rol) 
    VALUES('', '$username', '$correo', '$passwordHash', '$rol')";

    $res = $con->query($sql);

    session_start();
    $_SESSION['user_id'] = $con->insert_id;
    $_SESSION['username'] = $username;
    $_SESSION['rol'] = $rol;

    echo 1;

} catch (mysqli_sql_exception $e) {

    // ERROR DUPLICADO
    if ($e->getCode() == 1062) {

        if (strpos($e->getMessage(), 'username') !== false) {
            echo "El nombre de usuario ya está en uso";
        } elseif (strpos($e->getMessage(), 'email') !== false) {
            echo "El correo ya está registrado";
        } else {
            echo "Registro duplicado";
        }

    } else {
        echo "Error al registrar médico";
    }

    // LOG INTERNO
    error_log("ERROR insertarMedico: " . $e->getMessage());
}