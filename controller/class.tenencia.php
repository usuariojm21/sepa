<?php 

	//error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start();

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	class Tenencia{
		public $codtenencia;
		public $destenencia;
		public $estatus;
		public $d;

		function __construct($d){
			$this->d = $d;
		}

		function buscar(){
			$sql=''
			$statement = global_functions::tenencia($sql);
		}

	}

	if (isset($_POST)) {
		$tenencia = new Tenencia($_POST);
		$method = $_POST["method"];
		if ($method==1) {
			$resp= $tenencia->buscar();
		}
	}else{
		$resp=global_functions::arrayMsj(false,"No se recibieron los datos por POST");
	}

	header('Content-Type: application/json');
	echo json_encode($resp,JSON_FORCE_OBJECT);

?>