<?php

	
	require_once("../model/class.conexion.php");
	require_once("../model/functions.php");

	class Ciclo{
		public function obtener_ciclo($cod_ciclo = ""){

			$globalf = new global_functions();
			$rCiclos = $globalf->cargar_ciclos();
			$result=null;
			$resp=null;

			if ($rCiclos->rowCount()>0) {
				$result=array(
					"estado"=>true
				);
				
				$selected=null;
				$ARRciclos=[];
				while ($resp = $rCiclos->fetch()) {
					array_push($ARRciclos,array(
						"ciclo"=> $resp["ciclo"],
						"desciclo"=> $resp["desciclo"],
						"desdeciclo"=> $resp["desdeciclo"],
						"hastaciclo"=> $resp["hastaciclo"]
					));

				}
				
				$result = array_merge($result,array(
						"ciclo_actual"=>$ARRciclos[0]["ciclo"],
						"desc_ciclo_actual"=>$ARRciclos[0]["desciclo"],
						"data"=>$ARRciclos
					)
				);
			}else{
				$result=array(
					"estado"=>false,
					"descripcion"=>"No hay datos"
				);
			}

			//header('Content-Type: application/json');
			return json_encode($result, JSON_FORCE_OBJECT);

		}
	}

?>