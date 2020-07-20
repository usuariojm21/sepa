<?php

	session_start();

	require_once("../model/class.conexion.php");
	require_once("../model/functions.php");

	if ($_POST) {
		
		$usuario=$_POST["usuario"];
		$clave=base64_encode($_POST["clave"]);

		$globalf = new global_functions();
		$rLogin = $globalf->login($usuario, $clave);
		$result= null;
		$resp = null;

		if ($rLogin->rowCount()>0) {
			# si se hallan registros
			$resp = $rLogin->fetch();
			
			$_SESSION["sepa_login"]=true;
			$_SESSION["nivel"]=$resp["nivel"];
			$_SESSION["ente"]=$resp["rifentidad"];
			$_SESSION["cirif"]=$resp["ced_rif"];
			$_SESSION["usuario"]=$resp["usuario"];
			$_SESSION["clave"]=$resp["clave"];
			$_SESSION["filtro"]=$resp["filtro"];

			$result=array(
				"estado"=>true,
				"descripcion"=>""
			);
			
		}else{
			$result=array(
				"estado"=>false,
				"descripcion"=>"Usuario o contraceña incorrectos"
			);
		}

	}else{
		$result=array(
			"estado"=>false,
			"descripcion"=>"Error en el envío de datos"
		);
	}

	header('Content-Type: application/json');
	echo json_encode($result, JSON_FORCE_OBJECT);

?>