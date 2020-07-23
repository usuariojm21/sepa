<?php

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	class Estados{

		public function obtener_estados(){
		
			$paramSQL = array(
				"campos" => ["codestado","nomestado"],
				"values" => [],
				"tabla" => "estados",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "",
					"param_adicionales" => ""
				)
			);

			$consultas = new consultas();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$arrayData=[];

			$globalf = new global_functions();

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					$arrayData[]=array(
						"codigo"=> $f["codestado"],
						"nombre"=> $f["nomestado"]
					);
				}


				$resultado = $globalf->returnArray(true,"",$arrayData);

			}else{
				$resultado = $globalf->returnArray(false,"No se encontró ningun registro");
			}

			return json_encode($resultado, JSON_FORCE_OBJECT);

		}

	}

?>