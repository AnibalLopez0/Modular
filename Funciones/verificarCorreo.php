<?php

require "conn.php";
$con = conecta();

$correo = $_POST['correo'];

$sql = "SELECT id_usuario FROM usuarios WHERE email='$correo'";
$res = $con->query($sql);

$response = array();

if($res->num_rows > 0){

    $response['error'] = true;
    $response['message'] = "El correo ya está registrado";

}else{

    $response['error'] = false;
}


echo 1;

?>