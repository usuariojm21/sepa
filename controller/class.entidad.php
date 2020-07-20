<?php 

	class Entidad{

		public $busqueda;
		public $docentidad;
		public $rzsocial;
		public $telefono;
		public $correo;
		public $pagina;
		public $estado;
		public $municipio;
		public $parroquia;
		public $sector;
		public $direccion;
		public $representante;
		public $tlfrepresentante;
		public $estatus;
		public $filtro;
		public $d;

		function __construct($d=[]){
			$this->busqueda = "%".$d["busqueda"]."%";
			$this->docentidad=$d["ci-rif"];
			$this->rzsocial=$d["razons"];
			$this->telefono=$d["tlf"];
			$this->correo=$d["correo"];
			$this->pagina=$d["pagina"];
			$this->estado=$d["estado"];
			$this->municipio=$d["municipio"];
			$this->parroquia=$d["parroquia"];
			$this->sector=$d["sector"];
			$this->direccion=$d["dfiscal"];
			$this->representante=$d["rlegal"];
			$this->tlfrepresentante=$d["tlfrlegal"];
			$this->estatus=$d["estatus"];
			$this->filtro = $_SESSION["filtro"];
			$this->d = $d;
			//if ($this->nivel == "PRODUCTOR") $this->filtro = "AND productor_entidad.".$this->filtro;
		}

		public function buscar(){

			/*switch ($_SESSION["nivel"]) {
				case 'MUNICIPAL':
					$prefijo = 'AND ';
					break;
				case 'ENTIDAD':
					$prefijo = 'AND ';
					break;
				default:
					$prefijo='';
					break;
			}*/


			$filtro="";
			if ($_SESSION["nivel"]!=="ADMINISTRADOR") {$filtro = " AND entidad.". $this->filtro;}

			$fglobal = new Methods();

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();

			$sql = "SELECT
				entidad.rifentidad as rifentidad,
				razonsocial,
				dirfiscal,
				telefonos,
				correoe,
				representante,
				telfrepresentante,
				paginaweb,
				estado,
				municipio,
				parroquia,
				sector,
				estatus,
				usuario,
				clave
			FROM
				entidad,
				usuarios 
			WHERE
				usuarios.rifentidad = entidad.rifentidad
				AND ( entidad.rifentidad LIKE :busqueda OR razonsocial LIKE :busqueda )". $filtro;

			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;

			try {

				$statement->execute(
					array(
						":busqueda"=>$this->busqueda
					)
				);

				$rowArray=[];

				if ($statement->rowCount() > 0) {

					while ($f = $statement->fetch()) {
						$rowArray[]=array(
							"rif"=> $f["rifentidad"],
							"razonsocial"=> $f["razonsocial"],
							"direccion"=> $f["dirfiscal"],
							"telefono"=> $f["telefonos"],
							"correoe"=> $f["correoe"],
							"representante"=> $f["representante"],
							"telfrepresentante"=> $f["telfrepresentante"],
							"paginaweb"=> $f["paginaweb"],
							"estado"=> $f["estado"],
							"municipio"=> $f["municipio"],
							"parroquia"=> $f["parroquia"],
							"sector"=> $f["sector"],
							"estatus"=> $f["estatus"],
							"usuario"=> $f["usuario"],
							"clave"=> $f["clave"]
						);
					}

					return Methods::arrayMsj(true,"",$rowArray);

				}else{
					#no hay registros
					return Methods::arrayMsj(false,"No se encontró ningun registro");
				}

			} catch (PDOException $e) {
				return "Problemas de conexión: ". $e->getMessage();
			}	

		}
		public function guardar(){

			$c = $this->d;
			$update = $this->d["update"];

			//actualizar o registrar sectores
			$direccion = new Direccion(array("cparroquia"=>$c["parroquia"],"sector"=>$c["sector"]));
			$savesectores = $direccion->newAndupdateSectores();
			//return $savesectores;
			if($savesectores["estado"]===false) return $savesectores;

			$paramSQL = array(
				"campos" => [
					"rifentidad",
					"razonsocial",
					"dirfiscal",
					"telefonos",
					"correoe",
					"representante",
					"telfrepresentante",
					"paginaweb",
					"estado",
					"municipio",
					"parroquia",
					"sector",
					"estatus"
				],
				"values" => [
					strtoupper($c["ci-rif"]),
					$c["razons"],
					$c["dfiscal"],
					$c["tlf"],
					$c["correo"],
					$c["rlegal"],
					$c["tlfrlegal"],
					$c["pagina"],
					$c["estado"],
					$c["municipio"],
					$c["parroquia"],
					$c["codigosector"],
					$c["estatus"]
				],
				"tabla" => "entidad",
				"filtro" => array(
					"state" => true,
					"condicion" => ["rifentidad",$c["ci-rif"]],
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
					#actualizar
					$updateSQL = $consultas->update($paramSQL);
					
					if ($updateSQL===true) {

						return $this->newuser();//Methods::arrayMsj(true,"El ente financiero ha sido modificado exitosamente.");
					}else{
						return Methods::arrayMsj(true,$updateSQL);
					}
				}else{
					return Methods::arrayMsj(false,"Este ente financiero ya se encuentra registrada");
				}
			}else{
				#insertar
				$paramSQL["filtro"]["state"] = false;
				$insertSQL = $consultas->insert($paramSQL);
				if ($insertSQL===true) {
					# registro realizado exitosamente
					return $this->newuser();//Methods::arrayMsj(true,"El ente financiero ha sido registrado exitosamente.");
				}else{
					# error en el registro
					return Methods::arrayMsj(false,$insertSQL);
				}
			}
		}

		public function newuser(){
			$usuario = $this->d["nomusuario"];
			$clave = base64_encode($this->d["claveusuario"]);
			$entidad=$this->d["ci-rif"];
			$filtro = "rifentidad='$entidad'";

			$sql="SELECT * from usuarios WHERE rifentidad=:entidad";
			$param=array(
				":entidad"=>$entidad
			);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state=$rQuery["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$rQuery["error"]);

			$stmt = $rQuery["stmt"];

			if ($stmt->rowCount()>0) {
				$sql2="UPDATE usuarios SET usuario=:user, clave=:pass, filtro=:filtro WHERE rifentidad=:entidad";
			}else{
				$sql2="INSERT INTO usuarios VALUES(:entidad,'%',:user,:pass,'ENTIDAD',:filtro)";
			}

			//parametros para registras o actualizar el usuario
			$param2 = array(
				":entidad"=>$entidad,
				":user"=>$usuario,
				":pass"=>$clave,
				":filtro"=>$filtro
			);

			$rQuery2 = Querys::QUERYBD($sql2,$param2);
			$state = $rQuery2["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$rQuery2["error"]);

			return Methods::arrayMsj(true,"Los datos de esta entidad han sido registrados exitosamente.");

		}

	}

	/*if ($_POST) {
		$method = $_POST["method"];
		$entes = new Entidad($_POST);

		if ($method==1) {
			$resp = $entes->buscar();
		}else if($method==2){
			$resp = $entes->guardar();
		}

	}else{
		$resp = Methods::arrayMsj(false,"No se recibieron los datos por POST");
	}

	header('Content-Type: application/json');
	echo json_encode($resp,JSON_FORCE_OBJECT);*/

?>