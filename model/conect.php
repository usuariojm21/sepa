<?php 

	class conexion{
		public function get_conexion(){			
			try{
				$con= new PDO("mysql:host=localhost;dbname=sepa;","root","");
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $con;

			}catch(PDOException $e){
				echo "Conexión fallida: ". $e->getMessage();
			}
		}
	}

?>
