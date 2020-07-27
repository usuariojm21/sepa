<?php 

	require_once("../model/class.conexion.php");
	require_once("../model/class.querys.php");
	require_once("../controller/methods.php");

	class Users{

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

		public function verifyDataproductor(){
			$productor = new Productores(array("busqueda"=>''));
			return $productor->buscar();
		}
		public function verifyDataUNDproduccion(){
			$undproduccion = new UNDproduccion(array("busqueda"=>''));
			return $undproduccion->buscar();
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

	}


?>