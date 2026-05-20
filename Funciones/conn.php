<?php
define("HOST", "db");
define("BD", "modular");
define("USER_BD", "root");
define("PASS_BD", "root");

function conecta()	
	{
		$con = new mysqli(HOST, USER_BD, PASS_BD, BD, 3306);
		// checar errores
		if ($con->connect_error) {
			die("Error de conexión: " . $con->connect_error);
		}

		// corregir acentos
		$con->set_charset("utf8");
		return $con;
	}
?>
