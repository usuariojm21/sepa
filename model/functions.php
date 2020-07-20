<?php

	class global_functions{

		public function cargar_ciclos(){
			//cargar ciclos de cosecha. El actual y el pasado
			$r=null;
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$sql= "SELECT * from ciclos order by hastaciclo desc limit 2";
			$statement = $conexion->prepare($sql);

			try {
				$statement->execute();
				return $statement;
			} catch (PDOException $e) {
				$r = "Conexión fallida: ". $e->getMessage();
			}

			$modelo=null; //cerrar conexion

		}

		public function login($user, $pass){
			$r = null;
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$sql= "SELECT * FROM usuarios WHERE usuario=:user AND clave=:pass";
			$statement = $conexion->prepare($sql);
			$parametros = [
				":user" => $user,
				":pass" => $pass
			];

			try {
				$statement->execute($parametros);
				return $statement;
			} catch (PDOException $e) {
				$r = "Conexión fallida: ". $e->getMessage();
			}
			
			$modelo=null;

		}

		public function tenencias($sql="SELECT * FROM tenencia"){
			$modelo= new conexion();
			$conexion=$modelo->get_conexion();
			$statement=$conexion->prepare($sql);
			$modelo=null;
			try {
				$statement->execute();
				return $statement;
			} catch (PDOException $e) {
				
			}
		}

		public function arrayMsj($state,$description="",$data=[]){
			
			return array(
				"estado"=>$state,
				"descripcion"=>$description,
				"data"=>$data
			);
		}

		public function leerConfig(){
			$json = file_get_contents("../controller/config.json");
			return json_decode($json, true);
		}

		public function escribirConfig($fileConfig){
			$json_string = json_encode($fileConfig);
			file_put_contents('../controller/config.json', $json_string);
		}

		public function autoincremento($c){
			if (strlen($c) == 1)  return "00000" . $c;
      if (strlen($c) == 2)  return "0000" . $c;
      if (strlen($c) == 3)  return "000" . $c;
      if (strlen($c) == 4)  return "00" . $c;
			if (strlen($c) == 5)  return "0" . $c;
			if (strlen($c) > 6)  return $c;
		}

	}
?>
