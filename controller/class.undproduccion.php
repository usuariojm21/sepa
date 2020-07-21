<?php 

	//session_start();

	/*require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");*/

	class UNDproduccion{
		public $busqueda;
		public $filtro;
		public $resp;
		public $nivel;

		public function __construct($d){
			$this->busqueda = "%".$d["busqueda"]."%";
			$this->filtro = $_SESSION["filtro"];
			$this->nivel = $_SESSION["nivel"];
			$this->d = $d;

			switch ($_SESSION["nivel"]) {
				case 'MUNICIPAL':
					$prefijo = 'AND unidadproduccion.';
					break;
				case 'ENTIDAD':
					$prefijo = 'AND productor_entidad.';
					break;
				case 'PRODUCTOR':
					$prefijo = 'AND productor.';
					break;

					$prefijo='';
					break;
			}

			$this->filtro = $prefijo . $this->filtro;

		}

		public function buscar(){

			//$modelo = new conexion();
			//$conexion = $modelo->get_conexion();

			$filtro = $this->filtro;

			$sql = "SELECT
					unidadproduccion.codundprod AS codigound,
					unidadproduccion.codfichapredial AS codficha,
					unidadproduccion.urldocumentoficha AS urldocumentoficha,
					unidadproduccion.nomundprod AS nombreund,
					unidadproduccion.dirundprod AS direccion,
					unidadproduccion.estado,
					unidadproduccion.municipio,
					unidadproduccion.parroquia,
					unidadproduccion.sector,
					unidadproduccion.hatotal,
					unidadproduccion.haproductivas,
					unidadproduccion.hadisponibles,
					unidadproduccion.coorprinlat,
					unidadproduccion.coorprinlog,
					unidadproduccion.estatus 
				FROM
					unidadproduccion,
					undprod_productor,
					productor,
					productor_entidad 
				WHERE
					unidadproduccion.codundprod = undprod_productor.codundprod 
					AND undprod_productor.ced_rif = productor.ced_rif 
					AND productor.ced_rif = productor_entidad.ced_rif 
					AND unidadproduccion.nomundprod LIKE :busqueda $filtro	GROUP BY
					unidadproduccion.codundprod";
			$param = array(":busqueda"=>$this->busqueda);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery['state'];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$rQuery["error"]);

			$stmt = $rQuery["stmt"];
			if ($stmt->rowCount()>0) {
				$arrayUNDprod=[];
				while ($f = $stmt->fetch()) {
					/*OBTENER LISTADO DE PRODUCTORES ASOCIADOS A LA UNIDAD DE PRODUCCION*/
					$ARRproductores=[];
					$sql2="SELECT
						productor.ced_rif,
						productor.razonsocial,
						tenencia.codtenencia,
						tenencia.destenencia,
						undprod_productor.hadisponibles
					FROM
						undprod_productor,
						productor,
						tenencia 
					WHERE
						productor.ced_rif = undprod_productor.ced_rif
						AND tenencia.codtenencia = undprod_productor.codtenencia
						AND undprod_productor.codundprod = :codigo";
					$param2 = array(":codigo" => $f['codigound']);

					$rQuery2 = Querys::QUERYBD($sql2,$param2);
					$state2 = $rQuery2["state"];
					
					if (!$state2) {
						return Methods::arrayMsj(false,"Error en la consulta numero 2. ".$rQuery2["error"]);
						break;
					}else{

						$stmt2 = $rQuery2["stmt"];
						if($stmt2->rowCount()>0){
							while ($x = $stmt2->fetch()) {
								array_push($ARRproductores, array(
									"cedrif"=>$x["ced_rif"],
									"razons"=>$x["razonsocial"],
									"codtenencia"=>$x["codtenencia"],
									"destenencia"=>$x["destenencia"],
									"hadisponibles"=>$x["hadisponibles"]
								));
							}
						}

					}

					/*LLENAR ARREGLO FINAL*/
					array_push($arrayUNDprod,array(
						"codigo"=> $f["codigound"],
						"codficha"=>$f["codficha"],
						"urldocumentoficha"=>$f["urldocumentoficha"],
						"nombreund"=> $f["nombreund"],
						"direccion"=> $f["direccion"],
						//"crif"=> $f["rif"],
						//"razonsocial"=> $f["rsocial"],							
						"estado"=> $f["estado"],
						"municipio"=> $f["municipio"],
						"parroquia"=> $f["parroquia"],
						"sector"=> $f["sector"],
						"hatotal"=> $f["hatotal"],
						"haproductivas"=> $f["haproductivas"],
						"coorprinlat"=> $f["coorprinlat"],
						"coorprinlog"=> $f["coorprinlog"],
						"estatus"=> $f["estatus"],
						"dataproductores"=>$ARRproductores
					));
				}

				return Methods::arrayMsj(true,"",$arrayUNDprod);
			}else{
				return Methods::arrayMsj(true,"No se encontró ningun registro");
			}

		}

		public function getproductor($ndoc = '%'){

			$c = $this->d;

			$sql = "SELECT 
			productor.ced_rif as rif,
			productor.razonsocial as rsocial,
			productor.dirfiscal as direccion,
			productor.telefonos as telefono,
			productor.representante as representante,
			productor.correoe as correo,
			productor.pagina as pagina,
			productor.estatus as estatus
FROM productor, productor_entidad WHERE
productor_entidad.ced_rif = productor.ced_rif AND productor.ced_rif LIKE :ndoc " . $this->filtro;

			
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;

			try {

				//$statement->execute();
				$statement->execute(array(":ndoc"=>$ndoc));

				$arrayUNDprod=[];

				if ($statement->rowCount() > 0) {

					while ($f = $statement->fetch()) {
						array_push($arrayUNDprod, array(
							"cRif"=> $f["rif"],
							"razonsocial"=> $f["rsocial"],
							"direccion"=> $f["direccion"],
							"telefonos"=> $f["telefono"],
							"representante"=> $f["representante"],
							"correo"=> $f["correo"],
							"pagina"=> $f["pagina"],
							"estatus"=> $f["estatus"]
						));
					}

					return Methods::arrayMsj(true,"",$arrayUNDprod);

				}else{
					#no hay registros
					return Methods::arrayMsj(false,"No hay productores registrados o el productor seleccionado no coincide con nuestros registros.");
				}

			} catch (PDOException $e) {
				return Methods::arrayMsj(false,"Problemas de conexión: ". $e->getMessage());
			}		

		}

		public function guardar(){

			$this->d['lproductores'] = json_decode($this->d['lproductores'],true);
			$update = $this->d["update"];
			$c = $this->d;
			
			if ($c["codfichapredial"]!=='') {
				$fileUpload = $this->cargar_ficha_predial();
				if($fileUpload[0]===false) return Methods::arrayMsj(false,$fileUpload[1]);
			}

			if ($c["undproduccion"]=='' || $c["undproduccion"]=='POR ASIGNAR' || $c["undproduccion"]=='undefined') {
				$codundproduccion=Methods::JSONautoincrementable("../controller/config.json","unidadproduccion");
				$c["undproduccion"] = "UNDP".str_pad($codundproduccion, 7,"0", STR_PAD_LEFT);
				$this->d["undproduccion"] = $c["undproduccion"];
			}

			//enviar parametros para construir sentencias SQL
			$paramSQL = array(
				"campos" => [
					"codundprod",
					"nomundprod",
					"dirundprod",
					"estado",
					"municipio",
					"parroquia",
					"sector",
					"hatotal",
					"haproductivas",
					"coorprinlat",
					"coorprinlog",
					"estatus",
					"codfichapredial",
					"urldocumentoficha"
				],
				"values" => [
					$c["undproduccion"],
					$c["nombre"],
					$c["direccion"],
					$c["estado"],
					$c["municipio"],
					$c["parroquia"],
					$c["codigosector"],
					$c["haTotal"],
					$c["haProductivas"],
					$c["latitud"],
					$c["longitud"],
					$c["estatus"],
					$c["codfichapredial"],
					$fileUpload[1]
				],
				"tabla" => "unidadproduccion",
				"filtro" => array(
					"state" => true,
					"condicion" => ["codundprod",$c["undproduccion"]],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);
			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$add=[];

			if($resultSQL->rowCount() > 0){
				if ($update==1 || $update=="1") {
					
					//actualizar o registrar sectores
					$direccion = new Direccion(array(
						"codestado"=>$c["estado"],
						"cparroquia"=>$c["parroquia"],
						"codigosector"=>$c["codigosector"],
						"sector"=>$c["sector"]
					));
					$savesectores = $direccion->newAndupdateSectores();
					if($savesectores["estado"]===false) return $savesectores;

					#actualizar
					$updateSQL = $consultas->update($paramSQL);
					
					if ($updateSQL===true) {
						return $this->detalle();
						/*return Methods::arrayMsj(true,"La unidad de producción ha sido modificada.",array(
							"undproduccion"=>$c["undproduccion"]
						));*/
					}else{
						return Methods::arrayMsj(false,$updateSQL);
					}
				}else{
					return Methods::arrayMsj(false,"Esta unidad de producción ya se encuentra registrada");
				}
			}else{
				
				//actualizar o registrar sectores
				$direccion = new Direccion(array(
					"codestado"=>$c["estado"],
					"cparroquia"=>$c["parroquia"],
					"codigosector"=>$c["codigosector"],
					"sector"=>$c["sector"]
				));
				$savesectores = $direccion->newAndupdateSectores();

				//return $savesectores;
				if($savesectores["estado"]===false) return $savesectores;

				//insertar
				$insertSQL = $consultas->insert($paramSQL);
				if ($insertSQL===true) {
					return $this->detalle();
				}else{
					return Methods::arrayMsj(false,$insertSQL);
				}
			}
		}

		public function detalle(){

			$c = $this->d;
			$lproductores = $this->d["lproductores"];

			//VALIDAR SI LOS PRODUCTORES INGRESADOS SE ENCUENTRAN EN LA BASE DE DATOS
			$validproductor = $this->VALIDproductor();
			if($validproductor["estado"]===false) return $validproductor;

			//VALIDAR QUE LAS HECTAREAS DISPONIBLES NO EXCEDAN LAS PRODUCTIVAS
			$validHectareas = $this->VALIDtotalHectareas();
			if($validHectareas["estado"]===false) return $validHectareas;

			//ELIMINAR REGISTROS ANTERIORES DE LA TABLA DETALLE
			$sql="DELETE FROM undprod_productor WHERE codundprod=:codundprod";
			$param=array("codundprod"=>$c["undproduccion"]);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$rQuery["error"]);

			$return = '';
			foreach ($lproductores as $key => $value) {

				$sql = "INSERT INTO undprod_productor (ced_rif,codtenencia,codundprod,hadisponibles,codfichapredial) VALUES(:codprod, :tenencia, :undproduccion, :hadisponibles, :codigoficha)";
				$param=array(
					":codprod"=>$value["docproductor"],
					":tenencia"=>$value["codtenencia"],
					":undproduccion"=>$c["undproduccion"],
					":hadisponibles"=>$value["hadisponibles"],
					":codigoficha"=>$c["codfichapredial"]
				);
				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if(!$state) {
					$return = Methods::arrayMsj(false,"Error en la consulta. ".$rQuery["error"]);
					break;
				};

				$return = Methods::arrayMsj(true,"Los datos han sido registrados exitosamente.");
			}

			return $return;
		}

		public function VALIDproductor(){
			$lproductores = $this->d["lproductores"];
			return $lproductores;

			$return='';
			foreach ($lproductores as $key => $value) {
				$validproductor = $this->getproductor($value["docproductor"]);
				$return = $validproductor;
				if($validproductor["estado"]===false) break;
			}

			return $return;
		}

		public function VALIDtotalHectareas(){
			$lproductores = $this->d["lproductores"];

			$ha=0;
			foreach ($lproductores as $key => $value) {
				$ha = $ha + $value["hadisponibles"];
			}

			if ($ha > $this->d["haProductivas"]) return Methods::arrayMsj(false,"El total de hecteras disponibles para los productores seleccionados excede a las productivas.");
			return Methods::arrayMsj(true);			
		}


			/*$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$sql = "SELECT * FROM undprod_productor WHERE ced_rif=:codprod AND codundprod=:codundprod";
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;
			try {
				$statement->execute(array("codprod"=>$c["lproductores"]["docproductor"],"codundprod"=>$c["undproduccion"]));

				if (!$statement->rowCount() > 0) {
					$paramSQL = array(
						"campos" => [
							"ced_rif",
							"codundprod",
							//"nomundprod",
							"codtenencia",
							"codfichapredial",
							"hadisponible"
						],
						"values" => [
							$c["lproductores"]["docproductor"],
							$c["undproduccion"],
							//$c["nombre"],
							$c["lproductores"]["codtenencia"],
							$c["codfichapredial"],
							$c["lproductores"]["hadisponible"]
						],
						"tabla" => "undprod_productor"
					);

					$consultas = new Querys();
					$insertSQL2 = $consultas->insert($paramSQL);

					if ($insertSQL2===true) {
						return Methods::arrayMsj(true,"La unidad de producción ha sido registrada exitosamente.",array(
							"undproduccion"=>$codundprod
						));
					}else{
						return Methods::arrayMsj(false,$insertSQL2);
					}
				}
			} catch (PDOException $e) {
				return Methods::arrayMsj(false,"Problemas con la consulta: ". $e->getMessage());
			}*/

		//}

		/*public function verifyHectareasDisponibles($docproductor, $docundprod){
			$sql="SELECT hadisponibles from undprod_productor where ced_rif = :docproductor AND codundprod = :undproduccion";
			$param=array(
				":undproduccion"=>$docundprod,
				":docproductor"=>$docproductor
			);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);

			$stmt=$query["stmt"];
			if($stmt->rowCount()>0){
				$r=$stmt->fetch();
				$hadisponibles = $r["hadisponibles"];
				return $hadisponibles;
			}
		}*/


		public function cargar_ficha_predial(){

			$update = $this->d["update"];
			$carpeta="../view/uploads/";
			$nombre = $this->d["filefichapred"];
			
			if (isset($_FILES["fileFicha"])){
				$file=$_FILES["fileFicha"];

				$nombre=$file["name"];
				$tipo=$file["type"];
				$ruta_temp=$file["tmp_name"];
				$size=$file["size"];
				$dimensiones=getimagesize($ruta_temp);
				$width=$dimensiones[0];
				$height=$dimensiones[0];
				$carpeta="../view/uploads/";

				$ruta = $carpeta.$nombre;  //esta ruta se guardara en la base de datos
				
				if ($size>1024*1024) {
					return [false,'Error! el tamaño permitido es 1MB'];
				}else if(move_uploaded_file($ruta_temp, $ruta)){
					return [true, $ruta];          
				}else{
					return [false,'Problema al cargar el documento de ficha predial.'];
				}
			}else if ($update==1 AND $nombre!='' AND $nombre!='Cargar Archivo'){
				$ruta = $carpeta.$nombre;
				return [true, $ruta];
			}else{
				return [false,'No ha seleccionado ningún archivo.'];
			}
		}
	}

	/*if ($_POST) {
		$method = $_POST["method"];

		$undproduccion = new UNDproduccion($_POST);

		if ($method==1) {
			$resp = $undproduccion->buscar();
		}else if($method==2){
			$resp = $undproduccion->getproductor();
		}else if($method==3){
			$resp = $undproduccion->guardar();
		}

	}else{
		$resp = Methods::arrayMsj(false,"No se recibieron los datos por POST");
	}

	header('Content-Type: application/json');
	echo json_encode($resp,JSON_FORCE_OBJECT);*/

?>