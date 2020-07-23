<?php

	class Methods{

		public function returnArray($state=true,$description="",$data=[]){
			
			return array(
				"estado"=>$state,
				"descripcion"=>$description,
				"data"=>$data
			);
		}

		public function leerConfig($url){
			$json = file_get_contents($url);
			return json_decode($json, true);
		}

		public function escribirConfig($fileConfig,$url){
			$json_string = json_encode($fileConfig);
			file_put_contents($url, $json_string);
		}

		/*public function autoincremento($c){
			if (strlen($c) == 1)  return "00000" . $c;
			if (strlen($c) == 2)  return "0000" . $c;
			if (strlen($c) == 3)  return "000" . $c;
			if (strlen($c) == 4)  return "00" . $c;
			if (strlen($c) == 5)  return "0" . $c;
			if (strlen($c) > 6)  return $c;
		}*/
		public function JSONautoincrementable($url,$variable){
			$json = file_get_contents($url);
			$json =  json_decode($json, true);
			$correlativo = $json["autoincremento"][$variable] + 1;
			$json["autoincremento"][$variable] = $correlativo;
			$json_string = json_encode($json);
			file_put_contents($url, $json_string);
			return $correlativo;

		}
		/*public function BDautoincrementable($rifproveedor,$correlativo){
			$sql = "SELECT $correlativo as correlativo FROM proveedor where rifproveedor = :ndoc";
			$param = array(
				":ndoc"=>$rifproveedor
			);
			$consultas = new Querys();
			$resultSQL = $consultas->QUERYBD($sql,$param);
			$state=$resultSQL["state"];
			if (!$state) return Methods::returnArray(false,$resultSQL["error"]);
			$stmt = $resultSQL["stmt"];

			$r = $stmt->fetch();
			return $r["correlativo"]+1;
		}*/
		
		public function getMilisegundos($datetime=""){
			$fecha = new DateTime($datetime);
			return $fecha->getTimestamp();
		}

		public function convertirsegundos($segundos,$respuesta) {
			$horas = floor($segundos / 3600);
			$minutos = floor(($segundos - ($horas * 3600)) / 60);
			$segundos = $segundos - ($horas * 3600) - ($minutos * 60);

			$formato_hora =  $horas . ':' . $minutos . ":" . $segundos;

			if ($respuesta=='hh') return $horas;
			if ($respuesta=='mm') return $minutos;
			if ($respuesta=='ss') return $segundos;
			if ($respuesta=='hms') return $formato_hora;
		}
		public function sendmail($arr){

			$nombre=$arr["nombre"];
			$email=$arr["fromemail"];
			$asunto=$arr["asunto"];
			$mensaje=$arr["mensaje"];
			$headers="MINE-Version: 1.0\r\n";
			$headers  .="Content-type: text/html; charset=UTF-8\r\n";
			$headers .= "X-Priority: 3\n";
			$headers .= "X-MSMail-Priority: Normal\n";
			$headers .= "X-Mailer: php\n";
			$headers .= "From: \"".$nombre."\" <".$email.">\r\n";
			$headers.="Reply-To: ".$email."\r\n";
			try {
				mail($arr["email"], $asunto, $mensaje, $headers);
				return true;
			} catch (Exception $e) {
				return "ERROR: ".$e->getMessage();
			}
		}
		public function historial($ntabla,$descop,$usuario){
			$sql="INSERT INTO historialoperaciones VALUES('',:ntabla,:descop,now(),:usuario)";
			$param=array(
				":ntabla"=>$ntabla,
				":descop"=>$descop,
				":usuario"=>$usuario
			);
			$consultas = new Consultas();
			$resultSQL = $consultas->QUERYBD($sql,$param);
			$state=$resultSQL["state"];
			if (!$state) return Methods::returnArray(false,$resultSQL["error"]);
		}
	}

?>
