<?php

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	class Municipios{

		public function obtener_municipios($codigo,$globalf){

			$codigo=$codigo;

			$paramSQL = array(
				"campos" => [],
				"values" => [],
				"tabla" => 'municipios',
				"filtro" => array(
					"state" => true,
					"condicion" => ['codestado',$codigo],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new consultas();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$arrayData=[];

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					$arrayData[]=array(
						"codigo"=> $f["codmunicipio"],
						"municipio"=> $f["nommunicipio"]
					);
				}


				$resultado = $globalf->arrayMsj(true,"",$arrayData);

			}else{
				$resultado = $globalf->arrayMsj(false,"No se encontró ningun registro");
			}

			return $resultado;

		}

	}

	if ($_POST) {
		$municipio = new Municipios;

		$globalf = new global_functions();

		$resp = $municipio->obtener_municipios($_POST["codigo"],$globalf);
		
	}else{
		$resp = $globalf->arrayMsj(false,"No se recibieron los datos por POST");
	}

	header('Content-Type: application/json');
	echo json_encode($resp, JSON_FORCE_OBJECT);
	 

?>