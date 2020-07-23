<?php

	require_once("../model/class.conexion.php");
	require_once("../model/class.querys.php");
	require_once("../model/functions.php");

	class Parroquias{

		public function obtener_parroquias($codigo,$fGlobal){

			$codigo=$codigo;

			$paramSQL = array(
				"campos" => [],
				"values" => [],
				"tabla" => 'parroquias',
				"filtro" => array(
					"state" => true,
					"condicion" => ['codmunicipio',$codigo],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$arrayData=[];

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					array_push($arrayData,array(
						"codigo"=> $f["codparroquia"],
						"parroquia"=> $f["nomparroquia"]
					));
				}


				$resultado = $fGlobal->returnArray(true,"",$arrayData);

			}else{
				$resultado = $fGlobal->returnArray(false,"No se encontró ningun registro");
			}

			return $resultado;

		}

	}

	if ($_POST) {
		$parroquia = new Parroquias;

		$fGlobal = new global_functions();

		$resp = $parroquia->obtener_parroquias($_POST["codigo"],$fGlobal);
		
	}else{
		$resp = $fGlobal->returnArray(false,"No se recibieron los datos por POST");
	}

	header('Content-Type: application/json');
	echo json_encode($resp, JSON_FORCE_OBJECT);
	 

?>