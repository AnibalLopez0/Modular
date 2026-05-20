<?php
define("HOST", "localhost");
define("BD", "terapia_db");
define("USER_BD", "root");
define("PASS_BD", "");

function conecta()	
	{
		$con = new mysqli(HOST, USER_BD, PASS_BD, BD, 3307);
		// checar errores
		if ($con->connect_error) {
			die("Error de conexión: " . $con->connect_error);
		}

		// corregir acentos
		$con->set_charset("utf8");
		return $con;
	}
?>
