<?php 

	class ModoRapido{
		
		function __construct($d=[]){
			$this->d = $d;
			$this->nivel = $_SESSION["nivel"];
		}

		public function guardar(){
			//registrar productor
			//$newproductor = $this->newproductor();
			//if ($newproductor["estado"]===false) return $newproductor; //no se completó el registro
			
			//registrar undproduccion
			$newundproduccion = $this->newundproduccion();
			if ($newundproduccion["estado"]===false) return $newundproduccion; //no se completó el registro

			//registrar intencion de siembra
			$newintencion = $this->newintencion($newundproduccion["data"]["codundprod"]);
			/*if ($newintencion["estado"]===false)*/ return $newintencion; //no se completó el registro
		}

		public function newproductor(){
			//return $this->d;
			$c = $this->d;

			$sql = "SELECT * FROM productor WHERE ced_rif = :ndoc";
			$param = array(":ndoc"=>strtoupper($this->d['ndoc']));
			$consultas = new Querys();
			$resultSQL = querys::QUERYBD($sql,$param);
			$state=$resultSQL["state"];
			if (!$state) return Methods::arrayMsj(false,$resultSQL["error"]);
			$stmt = $resultSQL["stmt"];
			if($stmt->rowCount()>0){
				/*if ($update==1) {
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

				}else{*/
					return Methods::arrayMsj(false,"No se pueden actualizar los datos del productor"); 
				//}
			}else{
					$sql = "INSERT INTO productor VALUES(:ndoc,:rsocial,:dfiscal,:rlegal,:tlf,:correo,:paginas,:estatus)";
					$param = array(
						"ndoc"=>strtoupper($this->d["ndoc"]),
						"rsocial"=>$this->d["rsocial"],
						"dfiscal"=>$this->d["dfiscalproductor"],
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
						"ndocent"=>strtoupper($this->d["entidad"]),
						"ndoc"=>strtoupper($this->d["ndoc"]),
						"rsocial"=>$this->d["rsocial"]									
					);
					//$consultas = new Querys;
					$resultSQL = querys::QUERYBD($sql,$param);
					$state=$resultSQL["state"];
					if(!$state) return Methods::arrayMsj(false,"No se pudo completar el registro del productor. Detalles del error: ".$resultSQL["error"]);
					
			}

			return Methods::arrayMsj(true, "Datos guardados en el modulo de productores");
		}

		public function newundproduccion(){
			//return $this->d;

			$c = $this->d;

			$codundproduccion=Methods::JSONautoincrementable("../controller/config.json","unidadproduccion");
			$undproduccion = "UNDP".str_pad($codundproduccion, 7,"0", STR_PAD_LEFT);

			//validar si el productor ingresado se encuentra en la base de datos
			//$validproductor = $this->getproductor($c["ndoc"]);
			//if($validproductor["estado"]===false) return $validproductor;

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
					"hadisponibles",
					"coorprinlat",
					"coorprinlog",
					"estatus",
					//"codfichapredial",
					//"urldocumentoficha"
				],
				"values" => [
					$undproduccion,
					$c["nombre"],
					$c["direccion"],
					$c["estado"],
					$c["municipio"],
					$c["parroquia"],
					$c["codigosector"],
					$c["haTotal"],
					$c["haProductivas"],
					$c["haProductivas"],
					$c["latitud"],
					$c["longitud"],
					$c["estatus"],
					//$c["codfichapredial"],
					//$fileUpload[1]
				],
				"tabla" => "unidadproduccion",
				"filtro" => array(
					"state" => true,
					"condicion" => ["codundprod",$undproduccion],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);
			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$add=[];

			if($resultSQL->rowCount() > 0){
				/*if ($update==1 || $update=="1") {
					
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
					//$resp =  $updateSQL;
					
					if ($updateSQL===true) {
						return Methods::arrayMsj(true,"La unidad de producción ha sido modificada.",array(
							"undproduccion"=>$undproduccion
						));
					}else{
						return Methods::arrayMsj(false,$updateSQL);
					}*/
				//}else{
					return Methods::arrayMsj(false,"Esta unidad de producción ya se encuentra registrada");
				//}
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
					return $this->vinculeProductorANDundproduccion($undproduccion);
				}else{
					return Methods::arrayMsj(false,$insertSQL);
				}
			}

		}

		public function vinculeProductorANDundproduccion($codundprod){

			$c = $this->d;

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$sql = "SELECT * FROM undprod_productor WHERE ced_rif=:codprod AND codundprod=:codundprod";
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			$modelo=null;
			try {
				$statement->execute(array("codprod"=>strtoupper($c["ndoc"]),"codundprod"=>$codundprod));

				if (!$statement->rowCount() > 0) {
					$paramSQL = array(
						"campos" => [
							"ced_rif",
							"codundprod",
							"nomundprod",
							"codtenencia",
							"codfichapredial",
							"hadisponibles"
						],
						"values" => [
							strtoupper($c["ndoc"]),
							$codundprod,
							$c["nombre"],
							1,
							$c["codfichapredial"],
							$c["haProductivas"]

						],
						"tabla" => "undprod_productor"
					);

					$consultas = new Querys();
					$rQuery = $consultas->insert($paramSQL);

					if ($rQuery===true) {
						return Methods::arrayMsj(true,"La unidad de producción ha sido registrada exitosamente.",array("codundprod"=>$codundprod));
					}else{
						return Methods::arrayMsj(false,$rQuery);
					}
				}
			} catch (PDOException $e) {
				return Methods::arrayMsj(false,"Problemas con la consulta: ". $e->getMessage());
			}

		}

		public function newintencion($codundprod){
			//return $this->d;
			$totalHectarea=0;
			$detalle = json_decode($this->d['intenciones'],true);


			//////////////////VALIDACIONES PRINCIPALES
			//verificar hectareas disponibles
			$haintencion = 0;
			for ($i=0; $i < count($detalle); $i++) {
				$detalle[$i]["haintencion"] = str_replace(".", "", $detalle[$i]["haintencion"]);
				$detalle[$i]["haintencion"] = str_replace(",", ".", $detalle[$i]["haintencion"]);
				$haintencion =+ $detalle[$i]["haintencion"];
				$hectareastotales = $haintencion;
			}

			$hectareasDisponibles = $this->verifyHectareasDisponibles($this->d["ndoc"], $codundprod);
			if ($hectareasDisponibles<$haintencion) {
				return Methods::arrayMsj(false,"El numero de hectareas seleccionado es superior al total de hectareas disponibles para este productor.");
			}

			//Verificar si existe un registro con el rubro y el productor y el ciclo seleccionado.
			$verifyRubro = $this->verifyRubro();
			if ($verifyRubro) return Methods::arrayMsj(false,"Ya existe un registro con este rubro.");

			//////////////////FIN DE VALIDACIONES

			//for ($i=0; $i < count($detalle); $i++) {
				//$detalle[$i]["haintencion"] = str_replace(".", "", $detalle[$i]["haintencion"]);
				//$detalle[$i]["haintencion"] = str_replace(",", ".", $detalle[$i]["haintencion"]);
				//$detalle[$i]["haintencion"] = (real) $detalle[$i]["haintencion"];
			//}

			$nintencion = '';//$this->d["nintencion"];
			if ($nintencion ==='') {
				$nintencion=Methods::JSONautoincrementable("../controller/config.json","intencion");
				$nintencion = "INT".str_pad($nintencion, 6,"0", STR_PAD_LEFT);
				//$this->d["nintencion"] = $nintencion;
			}

			$sql="SELECT * FROM intencion WHERE ciclo=:ciclo AND rifentidad=:rifentidad";
			$param=array(
				":ciclo"=>$this->d['ciclo'],
				":rifentidad"=>$this->d['entidad']
			);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);

			$stmt = $query["stmt"];
			$consultas = new Querys();
			if($stmt->rowCount()>0){

				$r = $stmt->fetch();
				$nintencion = $r["nrointencion"];
				$hectareastotales = $hectareastotales + $r["ha_total_hectareas"];
				//return $hectareastotales;

				$sql="UPDATE intencion SET ha_total_hectareas=:hectareas WHERE ciclo=:ciclo AND rifentidad=:rifentidad";
				$param=array(":ciclo"=>$this->d['ciclo'],":rifentidad"=>$this->d['entidad'], ":hectareas"=>$hectareastotales);

				$query = Querys::QUERYBD($sql,$param);
				$state = $query["state"];
				if(!$state) return Methods::arrayMsj(false,"Error en la consulta. No se pudo actualizar la información. ".$query["error"]);

				return $this->newDetalleIntencion($nintencion,$codundprod,$hectareasDisponibles);

			}else{
				$sql="INSERT INTO intencion (nrointencion, ciclo, rifentidad, fecha_intencion, ha_total_hectareas, estado) VALUES(:ndoc,:ciclo,:docentidad,:fecha,:hectareas,:estado)";

				$param=array(":ndoc"=>$nintencion,":ciclo"=>$this->d['ciclo'],":docentidad"=>$this->d['entidad'],":fecha"=>date('Y-m-d h:m:s'), ":hectareas"=>$hectareastotales,":estado"=>1);

				$query = Querys::QUERYBD($sql,$param);
				$state = $query["state"];
				if(!$state) return Methods::arrayMsj(false,"Error en la consulta. No se pudo guardar la información. ".$query["error"]);

				return $this->newDetalleIntencion($nintencion,$codundprod,$hectareasDisponibles);
			}
		}

		public function newDetalleIntencion($nintencion,$undproduccion,$hectareasDisponibles){
			/*$filtro='';
			if ($this->nivel!=="ADMINISTRADOR") {
				$filtro = 'AND ' . $this->filtro;
			}*/


			$detalle = json_decode($this->d['intenciones'],true);

			/*$delete = $this->deleteIntencion();
			if ($delete["estado"]===false) {
				return $delete;
			}*/

			$result='';
			for ($i=0; $i < count($detalle); $i++) {

				//obtener direccion de entidad
				/*$direccion = $this->getEntidad("AND rifentidad='".$this->d['entidad']."'");
				$direccion = $direccion["data"];
				$result = $direccion;
				break;*/
				
				//verificar si ya existe una intencion con el rubro seleccionado
				$sql = "SELECT * FROM detalle_intencion WHERE ciclo=:ciclo AND rifentidad=:entidad AND ced_rif=:productor AND codrubro=:rubro"; //".$filtro;
				$param=array(
					":ciclo"=>$this->d['ciclo'],
					":entidad"=>$this->d['entidad'],
					":productor"=>strtoupper($this->d['ndoc']),
					":rubro"=>$detalle[$i]["rubro"]
				);

				$query = Querys::QUERYBD($sql,$param);
				$state = $query["state"];
				if(!$state) {
					$result = Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);
					break;
				}

				$stmt=$query["stmt"];
				if ($stmt->rowCount()<1) {

					//OBTENER DIRECCION DE LA ENTIDAD SELECCIONADA SI EL USUARIO NO ES PRODUCTOR
					/*if($this->nivel!=='PRODUCTOR'){
						$sql = "SELECT * FROM entidad WHERE rifentidad=:docentidad";
						$param=array(":docentidad"=>$this->d['entidad']);

						$query = Querys::QUERYBD($sql,$param);
						$state = $query["state"];
						if(!$state) {
							$result = Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);
							break;
						}else{
							$stmt=$query["stmt"];
							if($stmt->rowCount()>0){
								$r=$stmt->fetch();

								$detalle[$i]["estado"] = $r["estado"];
								$detalle[$i]["municipio"] = $r["municipio"];
								$detalle[$i]["parroquia"] = $r["parroquia"];
								$detalle[$i]["sector"] = $r["sector"];

							}else{
								$result = Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);
								break;
							}
						}						
					}*/

					//VERIFICAR HECTAREAS DISPONIBLES
					/*$hectareasDisponibles = $this->verifyHectareasDisponibles($this->d["ndoc"], $undproduccion);
					if ($hectareasDisponibles<$detalle[$i]["haintencion"]) {
						$result = Methods::arrayMsj(false,"El numero de hectareas seleccionado es superior al total de hectareas disponibles para este productor.");
						break;
					}*/

					//registrar nueva intencion
					$sql=	"INSERT INTO detalle_intencion (
						nrointencion,
						ciclo,
						ced_rif,
						codundprod,
						codrubro,
						rifentidad,
						cedtecnico,
						ha_intencion,
						fecha_intencion,
						estado,
						municipio,
						parroquia,
						sector,
						estatus
					)
					VALUES(
						:nintencion,
						:ciclo,
						:nproductor,
						:nundproduccion,
						:rubro,
						:entidad,
						:tecnico,
						:haintencion,
						:fecha,
						:estado,
						:municipio,
						:parroquia,
						:sector,
						:estatus
					)";
					$param = array(
						":nintencion"=>$nintencion,
						":ciclo"=>$this->d['ciclo'],
						":nproductor"=>strtoupper($this->d['ndoc']),
						":nundproduccion"=>$undproduccion,
						":rubro"=>$detalle[$i]['rubro'],
						":entidad"=>$this->d['entidad'],
						":tecnico"=>$detalle[$i]['tecnico'],
						":haintencion"=>$detalle[$i]['haintencion'],
						":fecha"=>date('Y-m-d h:m:s'),
						":estado"=>$this->d['estado'],
						":municipio"=>$this->d['municipio'],
						":parroquia"=>$this->d['parroquia'],
						":sector"=>$this->d['codigosector'],
						":estatus"=>1
					);

					$rQuery = Querys::QUERYBD($sql,$param);
					$state = $rQuery["state"];
					if(!$state) {
						$result = Methods::arrayMsj(false,"Error al intentar registrar la intención de siembra. ".$rQuery["error"]);
						break;
					}else{
						$result = $this->restarHectareas($this->d["ndoc"],$undproduccion,$detalle[$i]["haintencion"], $hectareasDisponibles);	
					}

					/*$paramSQL = array(
						"campos" => [
							"nrointencion",
							"ciclo",
							"ced_rif",
							"codundprod",
							"codrubro",
							"rifentidad",
							"ha_intencion",
							"cedtecnico",
							"estado",
							"municipio",
							"parroquia",
							"sector",
							"fecha_intencion",
							"estatus"
						],
						"values" => [
							$nintencion,
							$this->d['ciclo'],
							strtoupper($this->d["ndoc"]),
							$undproduccion,
							$detalle[$i]["rubro"],
							$this->d['entidad'],
							$detalle[$i]["haintencion"],
							$detalle[$i]["tecnico"],
							$this->d["estado"],
							$this->d["municipio"],
							$this->d["parroquia"],
							$this->d["sector"],
							date('Y-m-d h:m:s'),
							1
						],
						"tabla" => "detalle_intencion",
						"filtro" => array(
							"state" => false,
							"condicion" => [],
							"operador" => " = ",
							"param_adicionales" => ""
						)
					);

					$consultas = new Querys();
					$resultSQL = $consultas->insert($paramSQL);
					
					if ($resultSQL===false) {
						$result = Methods::arrayMsj(false,"No se pudo guardar la intención de siembra. ".$resultSQL);
						break;
					}else{

						$result = Methods::arrayMsj(true,"detalleintencion");//$this->restarHectareas($this->d["ndoc"],$undproduccion,$detalle[$i]["haintencion"], $hectareasDisponibles);

						//$result =  Methods::arrayMsj(true,'Tu intención de siembra ha sido guardada exitosamente. ');
					}*/
				}else{
					$result = Methods::arrayMsj(false,"Ya existe un registro con este rubro");
					break;
				}
			}

			return $result;
		}

		public function verifyHectareasDisponibles($docproductor, $docundprod){
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
			}else{
				return 999999;
			}
		}

		public function verifyRubro(){

			$filtro='';
			//if ($_SESSION["nivel"]!=="ADMINISTRADOR") $filtro = 'AND ' . $this->filtro;
			$detalle = json_decode($this->d['intenciones'],true);

			$sql = "SELECT * FROM detalle_intencion WHERE ciclo=:ciclo AND rifentidad=:entidad AND codrubro=:rubro";
			$param=array(":ciclo"=>$this->d['ciclo'], ":entidad"=>$this->d['entidad'], ":rubro"=>$detalle[$i]["codrubro"]);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);

			$stmt=$query["stmt"];
			if($stmt->rowCount()>0) return true; //si se encuentra el rubro

			return false; //no se encuentra el rubro
		}

		public function restarHectareas($docproductor,$docundprod,$haintencion,$hadisponibles){
			$newHaDisponibles = (real) $hadisponibles - (real) $haintencion;

			$sql="UPDATE undprod_productor SET hadisponibles=:hadisponibles WHERE ced_rif=:docproductor AND codundprod=:undproduccion";
			$param=array(
				":hadisponibles"=>$newHaDisponibles,
				":docproductor"=>$docproductor,
				":undproduccion"=>$docundprod
			);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);

			return Methods::arrayMsj(true,"Tu intención de siembra ha sido guardada exitosamente.");

		}

	}

?>