<?php

	session_start();

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	class Login{
		public $usuario;
		public $clave;

		function __construct($d){
			$this->usuario = $d["usuario"];
			$this->clave = base64_encode($d["clave"]);
		}
		public function entrar(){

			$sql = "SELECT * FROM usuarios WHERE usuario=:user AND clave=:pass";

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$parametros = [
				":user" => $this->usuario,
				":pass" => $this->clave
			];
			$modelo=null;
			try {

				$statement->execute($parametros);

				if ($statement->rowCount() > 0) {

					$r = $statement->fetch();
					
					$_SESSION["sepa_login"]=true;
					$_SESSION["nivel"]=$r["nivel"];
					$_SESSION["ente"]=$r["rifentidad"];
					$_SESSION["cirif"]=$r["ced_rif"];
					$_SESSION["usuario"]=$r["usuario"];
					//$_SESSION["clave"]=$r["clave"];
					$_SESSION["filtro"]=$r["filtro"];

					return global_functions::returnArray(true,"¡Bienvenido! ".$r["usuario"]);

					//si se encontro
				}else{
					//no se encontro
					return global_functions::returnArray(false,"Usuario o contraseña no encontrado");
				}

			} catch (PDOException $e) {
				return global_functions::returnArray(false,"Problemas con la consulta: ". $e->getMessage());
			}

		}

	}

	if ($_POST) {
		$login = new Login($_POST);
		$resp = $login->entrar();
	}else{
		$resp = global_functions::returnArray(false,"ERROR: No se recibieron los datos por POST. Por favor contacte con Soporte Tecnico");
	}

	header('Content-Type: application/json');
	echo json_encode($resp, JSON_FORCE_OBJECT);

?>