<?php

	class Consultas{
		public function insert($campos){
			
			$parametros = [];
			$param=null;
			$valor_param=null;
			$camposBD=null;
			$values=null;
			$add=[];
			$stringSQL=null;

			$tabla = $campos["tabla"];

			for ($i=0; $i < count($campos["campos"]); $i++) { 
				
				//Construir string de campos y values para la sql Insert
				if ($i < count($campos["campos"]) && $i > 0) {
					
					$camposBD = $camposBD . ", ";
					$values = $values . ", ";
				}

				$camposBD = $camposBD . $campos["campos"][$i];
				$values = $values . ":".$campos["campos"][$i];

				//construyendo arreglo para asignacion de parametros
				$param = ":".$campos["campos"][$i];
				$valor_param = $campos["values"][$i];

				$add = array( $param => $valor_param);
				$parametros = array_merge($parametros,$add);

			}

			//construyendo SQL Insert
			$stringSQL = "Insert Into ". $campos["tabla"] . " (".$camposBD.") Values(".$values.")";

			//return $stringSQL."<br><br>";
			//echo json_encode($parametros)."<br><br>";

			$modelo = new conexion();
			$conexion =  $modelo->get_conexion();
			$sql = $stringSQL;
			$statement = $conexion->prepare($sql);
			
			try {
				$statement->execute($parametros);
				$statement->closeCursor();
				$statement = null;
				$modelo=null;

				return true;

			} catch (PDOException $e) {
				return "Problemas al ingresar un nuevo registro: ". $e->getMessage();
			}

			
		}

		public function update($campos){

			$parametros = [];
			$param=null;
			$valor_param=null;
			$camposBD=null;
			$values=null;
			$add=[];
			$stringSQL=null;
			$filtro=$campos["filtro"];

			$tabla = $campos["tabla"];
			$condicion = $filtro["condicion"][0];
			$paramCondicion = ":param1";//. $campos["condicion"][0];
			$valorCondicion = $filtro["condicion"][1];

			for ($i=0; $i < count($campos["campos"]); $i++) { 
				
				//Construir string de campos y values para la sql Update
				if ($i < count($campos["campos"]) && $i > 0) {
					
					$camposBD = $camposBD . ", ";
					//$values = $values . ", ";
				}

				$camposBD = $camposBD . $campos["campos"][$i] . "= :". $campos["campos"][$i];
				//$values = $values . ":".$campos["campos"][$i];

				//construyendo arreglo para asignacion de parametros
				$param = ":".$campos["campos"][$i];
				$valor_param = $campos["values"][$i];

				$add = array( $param => $valor_param);
				$parametros = array_merge( $parametros, $add);

			}

			$parametros = array_merge($parametros, array($paramCondicion => $valorCondicion));

			//construyendo SQL Update
			$stringSQL = "Update ". $tabla . " Set ".$camposBD." Where ".$condicion."=".$paramCondicion;

			//echo $stringSQL."<br><br>";
			//echo json_encode($parametros)."<br><br>";

			$modelo = new conexion();
			$conexion =  $modelo->get_conexion();
			$sql = $stringSQL;
			$statement = $conexion->prepare($sql);
			try {
				$statement->execute($parametros);
				return true;

			} catch (PDOException $e) {
				return "Problemas al actualizar el registro: ". $e->getMessage();
			}

			$modelo=null;
		}


		public function select($parametrosSQL){

			//return json_encode($parametrosSQL);
			$parametros = [];
			//$camposBD=$parametrosSQL["campos"];
			$tabla=$parametrosSQL["tabla"];
			$filtro = $parametrosSQL["filtro"];
			$stateFiltro = $filtro["state"];
			$stringSQL=null;
			$stringFiltroSQL=null;
			$result=null;

			if ($stateFiltro) {
				$campoCondicion = $filtro["condicion"][0];
				$paramCondicion = ":param1";
				$valorCondicion = $filtro["condicion"][1];
				$operadorCondicion = $filtro["operador"];
				$param_adicionales = $filtro["param_adicionales"];

				//crear array de parametros que se enviarán a la consulta sql
				$array = array($paramCondicion => $valorCondicion);
				$parametros = array_merge($parametros,$array);
				
				$stringFiltroSQL = " Where ". $campoCondicion . $operadorCondicion . $paramCondicion . " ". $param_adicionales;
			}else{
				$stringFiltroSQL = "";
			}

			$stringSQL = "Select * From ".$tabla . $stringFiltroSQL;

			$rows = "";
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$sql = $stringSQL;
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;
			try {
				$statement->execute($parametros);
				return $statement;

			} catch (PDOException $e) {
				return "Problemas de conexión: ". $e->getMessage();
			}

			$modelo=null;

			/*$parametros = [
				":rifentidad"=>$valorCondicion
			];*/
			/*$statement->execute();
			while ($r=$statement->fetch()) {
				$rows[] = $result;
			}

			return json_encode($rows);
			$modelo=null;*/
		}

		public function QUERYBD($sql,$param){

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;
			try {
				$statement->execute($param);
				return array(
					"state"=>true,
					"stmt"=>$statement
				);


			} catch (PDOException $e) {
				return array(
					"state"=>false,
					"error"=> $e->getMessage()
				);			
			}
		}

		public function procedures($erif,$prif,$prazons){
			$sql = "CALL cargar_productor_entidad(?,?)";

			$PDOmodel = new conexion();
			$conn = $PDOmodel->get_conexion();
			$statement = $conn->prepare($sql);
			$statement->bindParam(1, $erif, PDO::PARAM_STR, 400);
			$statement->bindParam(2, $prif, PDO::PARAM_STR, 400);
			//$statement->bindParam(3, $prazons, PDO::PARAM_STR, 400);
			try {
				$statement->execute();
				return true;
			} catch (Exception $e) {
				return "Problema Fatal: ". $e->getMessage();
			}
		}


	}



?>

