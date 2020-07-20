<?php 

	class Productores{

		public $busqueda;
		public $filtro;
		public $resp;
		public $nivel;
		public $data;

		public function __construct($d=[]){
			$this->busqueda = "%".$d["busqueda"]."%";
			$this->filtro =$_SESSION["filtro"];
			$this->nivel = $_SESSION["nivel"];
			$this->d = $d;

		}

		public function buscar(){


			switch ($_SESSION["nivel"]) {
				case 'MUNICIPAL':
					$prefijo = 'AND entidad.';
					break;
				case 'ENTIDAD':
					$prefijo = 'AND entidad.';
					break;
				case 'PRODUCTOR':
					$prefijo = 'AND productor.';
					break;
				default:
					$prefijo='';
					break;
			}

			$this->filtro = $prefijo . $this->filtro;

			$fglobal = new global_functions();

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();

			$sql = "SELECT
				productor.ced_rif as rif,
				productor.razonsocial as rsocial,
				productor.dirfiscal as direccion,
				productor.telefonos as telefono,
				productor.representante as representante,
				productor.correoe as correo,
				productor.pagina as pagina,
				productor.estatus as estatus
			FROM
				productor, productor_entidad, entidad
			WHERE
				productor.ced_rif = productor_entidad.ced_rif
				AND productor_entidad.rifentidad = entidad.rifentidad
				AND (productor.razonsocial LIKE :busqueda OR productor_entidad.ced_rif LIKE :busqueda ) " . $this->filtro . " GROUP BY productor.ced_rif";
			
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;

			try {

				$statement->execute(
					array(
						":busqueda"=>$this->busqueda
					)
				);

				$arrayProd=[];

				if ($statement->rowCount() > 0) {

					while ($f = $statement->fetch()) {
						array_push($arrayProd,array(
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

					$r = $fglobal->arrayMsj(true,"",$arrayProd);

				}else{
					#no hay registros
					$r = $fglobal->arrayMsj(false,"No se encontró ningun registro");
				}

			} catch (PDOException $e) {
				$r = "Problemas de conexión: ". $e->getMessage();
			}

			return $r;

		}

		public function guardar(){

			$c = $this->d;
			$update = $this->d["update"];

			$sql = "SELECT * FROM productor WHERE ced_rif = :ndoc";
			$param = array(":ndoc"=>strtoupper($this->d['ndoc']));
			$consultas = new Querys();
			$resultSQL = querys::QUERYBD($sql,$param);
			$state=$resultSQL["state"];
			if (!$state) return Methods::arrayMsj(false,$resultSQL["error"]);
			$stmt = $resultSQL["stmt"];
			if($stmt->rowCount()>0){
				if ($update==1) {
						$sql = "UPDATE productor SET razonsocial=:rsocial,
	dirfiscal=:dfiscal,
	representante=:rlegal,
	telefonos=:tlf,
	correoe=:correo,
	pagina=:paginas,
	estatus=:estatus WHERE ced_rif=:ndoc";
						$param = array(
							"rsocial"=>$this->d["rsocial"],
							"dfiscal"=>$this->d["dfiscal"],
							"rlegal"=>$this->d["rlegal"],
							"tlf"=>$this->d["tlf"],
							"correo"=>$this->d["correo"],
							"paginas"=>$this->d["pagina"],
							"estatus"=>$this->d["estatus"],
							"ndoc"=>$this->d["ndoc"]
						);
						$consultas = new Querys;
						$resultSQL = querys::QUERYBD($sql,$param);
						$state=$resultSQL["state"];
						if(!$state) return Methods::arrayMsj(false,"No se pueden guardar los datos del productor ".$resultSQL["error"]);

				}else{
					return Methods::arrayMsj(false,"No se pueden actualizar los datos del productor"); 
				}
			}else{
					$sql = "INSERT INTO productor VALUES(:ndoc,:rsocial,:dfiscal,:rlegal,:tlf,:correo,:paginas,:estatus)";
					$param = array(
						"ndoc"=>$this->d["ndoc"],
						"rsocial"=>$this->d["rsocial"],
						"dfiscal"=>$this->d["dfiscal"],
						"rlegal"=>$this->d["rlegal"],
						"tlf"=>$this->d["tlf"],
						"correo"=>$this->d["correo"],
						"paginas"=>$this->d["pagina"],
						"estatus"=>$this->d["estatus"]
						
					);
					//$consultas = new Querys;
					$resultSQL = querys::QUERYBD($sql,$param);
					$state=$resultSQL["state"];
					if(!$state) return Methods::arrayMsj(false,"No se pueden guardar los datos del productor ".$resultSQL["error"]);

					//ascociar productor con entidad
					$sql = "INSERT INTO productor_entidad VALUES(:ndocent,'',:ndoc,:rsocial,'')";
					$param = array(
						"ndocent"=>$this->d["rif_end"],
						"ndoc"=>$this->d["ndoc"],
						"rsocial"=>$this->d["rsocial"]									
					);
					//$consultas = new Querys;
					$resultSQL = querys::QUERYBD($sql,$param);
					$state=$resultSQL["state"];
					if(!$state) return Methods::arrayMsj(false,"No se pudo completar el registro del productor. Detalles del error: ".$resultSQL["error"]);
					
			}

			return Methods::arrayMsj(true,"",[]);
		}

	}
	
?>