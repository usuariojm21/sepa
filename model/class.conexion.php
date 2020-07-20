<?php 

	class conexion{
		public function get_conexion(){			
			try{
				$con= new PDO("mysql:host=localhost;dbname=sepadm_port;charset=utf8","root","");
				//$con= new PDO("mysql:host=localhost;dbname=sepadm_portuguesa;charset=utf8","sepadm_portuguesa","@portuguesadb");
				$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $con;

			}catch(PDOException $e){
				echo "Conexión fallida: ". $e->getMessage();
			}
		}
	}

	//@portuguesadb
	//sepadm_portuguesa
	//sepadm_portuguesa

 ?>