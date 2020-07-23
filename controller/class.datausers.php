<?php 

	require_once("../model/class.conexion.php");
	require_once("../model/class.querys.php");
	require_once("../controller/methods.php");

	class Usuarios{

		public $login='';
		public $user='';
		public $ente='';
		public $ndoc='';
		public $filtro='';
		public $nivel='';
		public $datasession='';
		
		function __construct(){

			$this->login = $_SESSION["sepa_login"];
			$this->user=$_SESSION["usuario"];
			$this->ente=$_SESSION["ente"];
			$this->ndoc=$_SESSION["cirif"];
			$this->filtro=$_SESSION["filtro"];
			$this->nivel=$_SESSION["nivel"];

		}

		public function session(){
			if (!isset($this->login)) {
				header("location:./");
			}else{
				$session = array(
					"user"=>$this->user,
					"entidad"=>$this->ente,
					"nivel"=>$this->nivel,
					"filtro"=>$this->filtro,
					"cirif"=>$this->ndoc
				);

				return json_encode($session,JSON_FORCE_OBJECT);

			}
		}

		public function verificarNivelesUsuarios(){

			$show=0;
			if($_SESSION["nivel"]=='ADMINISTRADOR' || $_SESSION["nivel"]=='REGIONAL') return 2;
			if($_SESSION["nivel"]=='ENTIDAD' || $_SESSION["nivel"]=='MUNICIPAL') return 1;
			if($_SESSION["nivel"]=='PRODUCTOR') return 0;

		}

		public function resumenTotalHectareas(){

			$filtro = $_SESSION["filtro"];
			$nivel = $_SESSION["nivel"];
			if ($nivel==='ADMINISTRADOR') $filtro = '';

			$sql="SELECT SUM(ha_intencion) as haintencion, SUM(ha_financiadas) as hafinanciadas, SUM(ha_sembradas) as hasembradas, SUM(ha_cosechadas) as hacosechadas, SUM(ha_perdidas) as haperdidas FROM detalle_intencion  WHERE $filtro";

			$param=[];

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$rQuery["error"]);

			$stmt=$rQuery["stmt"];
			if($stmt->rowCount()>0){
				$arrResumen=[];
				while ($f=$stmt->fetch()) {
					array_push($arrResumen, array(
						"haintencion"=> $f["haintencion"],
						"hafinanciadas"=> $f["hafinanciadas"],
						"hasembradas"=> $f["hasembradas"],
						"hacosechadas"=> $f["hacosechadas"],
						"haperdidas"=> $f["haperdidas"]
					));
				}
			}

			return $arrResumen;

		}

		public function verifyDataProductor(){

			$filtro = "AND productor_entidad.". $this->filtro;


			$sql="SELECT
			productor.ced_rif as rif,
			productor.razonsocial as rsocial,
			productor.dirfiscal as direccion,
			productor.representante as representante,
			productor.telefonos as telefonos,
			productor.correoe as correo,
			productor.pagina as pagina,
			productor.estatus as estatus
			FROM productor_entidad, productor WHERE productor_entidad.ced_rif = productor.ced_rif ".$filtro;

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			try {
				$statement->execute();
				$dataproductor=[];

				if ($statement->rowCount() > 0) {
					
					while ($r = $statement->fetch()) {
						 array_push($dataproductor,array(
							"docproductor"=>$r["rif"],
							"rsocial"=>$r["rsocial"],
							"direccion"=>$r["direccion"],
							"representante"=>$r["representante"],
							"telefono"=>$r["telefonos"],
							"correo"=>$r["correo"],
							"pagina"=>$r["pagina"],
							"estatus"=>$r["estatus"]
						));
					}

					$_SESSION["dataproductor"]=Methods::returnArray(true,"",$dataproductor);
					return $_SESSION["dataproductor"];

				}else{

				}

				

			} catch (PDOException $e) {
				//echo $e->getMessage();
			}

		}

		public function verifyEntidad(){

			$filtro = "WHERE ". $this->filtro;
			if ($_SESSION["nivel"]==="ADMINISTRADOR") $filtro='';

			$sql="SELECT * FROM entidad ".$filtro;

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			try {
				$statement->execute();
				$dataEntidad=[];

				if ($statement->rowCount() > 0) {
					
					while ($r = $statement->fetch()) {
						 array_push($dataEntidad,array(
							"ndoc"=>$r["rifentidad"],
							"rsocial"=>$r["razonsocial"],
							"direccion"=>$r["dirfiscal"],
							"representante"=>$r["representante"],
							"telefono"=>$r["telefonos"],
							"correo"=>$r["correoe"],
							"pagina"=>$r["paginaweb"],
							"estatus"=>$r["estatus"]
						));
					}

					$r=Methods::returnArray(true,"",$dataEntidad);
				}else{
					$r=Methods::returnArray(false,"No hay Entidades registradas");
				}

				$_SESSION["dataEntidad"] = $r;
				return $_SESSION["dataEntidad"];	

			} catch (PDOException $e) {
				$r=Methods::returnArray(false,"¡ERROR FATAL! ".$e->getMessage());
			}

		}

		public function verifyUNDproduccion(){

			if ($_SESSION["nivel"]==='PRODUCTOR') {
				$this->filtro = 'undprod_productor.'.$this->filtro;
			}
			$filtro = "AND ".$this->filtro;
			if ($_SESSION["nivel"]==="ADMINISTRADOR") $filtro='';

			$sql ="SELECT
				undprod_productor.codundprod AS codigo,
				unidadproduccion.codfichapredial,
				urldocumentoficha,
				undprod_productor.nomundprod AS nombreund,
				dirundprod,
				estado,
				municipio,
				parroquia,
				sector,
				hatotal,
				haproductivas,
				coorprinlat,
				coorprinlog,
				codtenencia 
			FROM
				productor,
				productor_entidad,
				undprod_productor,
				unidadproduccion 
			WHERE
				productor_entidad.ced_rif = productor.ced_rif 
				AND unidadproduccion.codundprod = undprod_productor.codundprod 
				AND productor.ced_rif = undprod_productor.ced_rif ".$filtro;

			$modelo= new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			try {
				
				$statement->execute();

				$undproduccion=[];
				$_SESSION["haproductivas"]=0;
				if ($statement->rowCount() > 0) {			
					
					while ($f = $statement->fetch()) {
						
						array_push($undproduccion, array(
							"codundprod"=>$f["codigo"],
							"codfichapredial"=>$f["codfichapredial"],
							"filefichapredial"=>$f["urldocumentoficha"],
							"direccion"=>$f["dirundprod"],
							"nomundprod"=>$f["nombreund"],
							"estado"=>$f["estado"],
							"municipio"=>$f["municipio"],
							"parroquia"=>$f["parroquia"],
							"sector"=>$f["sector"],
							"hatotal"=>$f["hatotal"],
							"haproductivas"=>$f["haproductivas"],
							"coorprinlat"=>$f["coorprinlat"],
							"coorprinlog"=>$f["coorprinlog"],
							"codtenencia"=>$f["codtenencia"]
						));

						$_SESSION["haproductivas"] = $_SESSION["haproductivas"] + $f["haproductivas"];

					}

					return Methods::returnArray(true,"",$undproduccion);

				}else{
					return Methods::returnArray(false,"No hay Unidades de producción registradas");
				}

				//$_SESSION["dataUNDproduccion"] = $r;
				//return $_SESSION["dataUNDproduccion"];

			} catch (PDOException $e) {
				return Methods::returnArray(false,"¡ERROR FATAL! ".$e->getMessage());
			}

		}

		/*public function verifyUNDproduccion(){

			$filtro = "AND undprod_productor.". $this->filtro;

			$sql="SELECT undprod_productor.codundprod as codigo, undprod_productor.nomundprod as nombreund, estado, municipio, parroquia FROM undprod_productor, unidadproduccion WHERE unidadproduccion.codundprod = undprod_productor.codundprod ".$filtro;

			$modelo= new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			try {
				
				$statement->execute();

				if ($statement->rowCount() > 0) {
					
					$_SESSION["dataUNDproduccion"]=[];
					while ($f = $statement->fetch()) {
						
						array_push($_SESSION["dataUNDproduccion"], array(
							"codundprod"=>$f["codigo"],
							"nomundprod"=>$f["nombreund"],
							"estado"=>$f["estado"],
							"municipio"=>$f["municipio"],
							"parroquia"=>$f["parroquia"],
						));

					}

					return true;

				}else{
					return false;
				}

			} catch (PDOException $e) {
				echo $e->getMessage();
			}
	
		}*/

	}


?>