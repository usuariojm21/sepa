<?php 


	class IntencionSiembra{
		public $d;
		public $filtro;
		public $nivel;

		function __construct($d=[]){
			$this->d = $d;
			$this->nivel = $_SESSION["nivel"];
			$this->filtro = $_SESSION["filtro"];
		}

		public function buscar(){

			if($this->d["productor"]=='' || $this->d["productor"]=='undefined')$this->d["productor"] = '%';
			if($this->d["entidad"]=='' || $this->d["entidad"]=='undefined')$this->d["entidad"] = '%';

			//return $this->d;

			$filtro='';
			if ($_SESSION["nivel"]!=="ADMINISTRADOR") {
				$filtro = 'AND detalle_intencion.' . $this->filtro;
			}

			$sql="SELECT
				detalle_intencion.nrointencion AS docintencion,
				detalle_intencion.ced_rif AS ced_rif,
				detalle_intencion.ciclo,
				detalle_intencion.codundprod AS codundprod,
				detalle_intencion.codrubro,
				rubros.desrubro AS rubro,
				detalle_intencion.rifentidad,
				entidad.razonsocial AS entidad,
				detalle_intencion.cedtecnico AS cedtecnico,
				detalle_intencion.ha_intencion,
				detalle_intencion.ha_financiadas,
				detalle_intencion.ha_sembradas,
				detalle_intencion.ha_cosechadas,
				detalle_intencion.ha_perdidas,
				detalle_intencion.fecha_intencion,
				detalle_intencion.fecha_financiada,
				detalle_intencion.fecha_siembra,
				detalle_intencion.fecha_cosecha,
				detalle_intencion.estatus
				FROM
				detalle_intencion,
				rubros,
				entidad
				WHERE
				rubros.codrubro = detalle_intencion.codrubro
				AND entidad.rifentidad = detalle_intencion.rifentidad
				AND detalle_intencion.ciclo LIKE :ciclo
				AND (detalle_intencion.fecha_intencion BETWEEN :fecha1 AND :fecha2)
				AND detalle_intencion.codrubro LIKE :rubro
				AND detalle_intencion.estado LIKE :estado 
				AND detalle_intencion.municipio LIKE :municipio 
				AND detalle_intencion.parroquia LIKE :parroquia 
				AND detalle_intencion.sector LIKE :sector
				AND detalle_intencion.ced_rif LIKE :productor
				AND (detalle_intencion.rifentidad LIKE :entidad OR entidad.razonsocial LIKE :entidad) ". $filtro;

			$param = 	array(
				":ciclo"=>$this->d["ciclo"],
				":fecha1"=>$this->d["fecha1"],
				":fecha2"=>$this->d["fecha2"],
				":rubro"=>$this->d["rubro"],
				":estado"=>$this->d["estado"],
				":municipio"=>$this->d["municipio"],
				":parroquia"=>$this->d["parroquia"],
				":sector"=>$this->d["sector"],
				":productor"=>$this->d["productor"],
				":entidad"=>$this->d["entidad"]
			);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery['state'];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$rQuery["error"]);
			$stmt = $rQuery["stmt"];
			if($stmt->rowCount()>0){
				$arrayInt=[];
				$ndoc='';
				$i=0;

				while ($f = $stmt->fetch()) {

					//$date = new DateTime($f["fecha"]);
					//$fecha = $date->format('d/m/Y');

					array_push($arrayInt,array(
						"docintencion"=>$f['docintencion'],
						"ciclo"=>$f['ciclo'],
						"rifproductor"=>$f['ced_rif'],
						//"razonsocialproductor"=>$f['razonsocialproductor'],
						"codundprod"=>$f['codundprod'],
						//"nomundprod"=>$f['nomundprod'],
						"codrubro"=>$f['codrubro'],
						"desrubro"=>$f['rubro'],
						"rifentidad"=>$f['rifentidad'],
						"rsocialentidad"=>$f['entidad'],
						"cedtecnico"=>$f['cedtecnico'],
						//"nomtecnico"=>$f['n  omtecnico'],
						"haintencion"=>$f['ha_intencion'],
						"hafinanciadas"=>$f['ha_financiadas'],
						"hasembradas"=>$f['ha_sembradas'],
						"hacosechadas"=>$f['ha_cosechadas'],
						"haperdidas"=>$f['ha_perdidas']
						//"hatotal"=>$f['hatotal']
					));

				}

				return Methods::returnArray(true,"",$arrayInt);
			}else{
				return Methods::returnArray(true,"No se encontró ningun registro");
			}
		}

		public function newIntencion(){

			$totalHectarea=0;
			$detalle = json_decode($this->d['dataDetails'],true);


			//////////////////VALIDACIONES PRINCIPALES
			//verificar hectareas disponibles
			$hectareasDisponibles = $this->verifyHectareasDisponibles($detalle[0]["docproductor"], $detalle[0]["undproduccion"]);
			if ($hectareasDisponibles<$detalle[0]["hectareas"]) {
				return Methods::returnArray(false,"El numero de hectareas seleccionado es superior al total de hectareas disponibles para este productor.");
			}

			//Verificar si existe un registro con el rubro y el productor y el ciclo seleccionado.
			$verifyRubro = $this->verifyRubro();
			if ($verifyRubro) return Methods::returnArray(false,"Ya existe un registro con este rubro.");

			//////////////////FIN DE VALIDACIONES

			for ($i=0; $i < count($detalle); $i++) {
				$detalle[$i]["hectareas"] = str_replace(".", "", $detalle[$i]["hectareas"]);
				$detalle[$i]["hectareas"] = str_replace(",", ".", $detalle[$i]["hectareas"]);
				//$detalle[$i]["hectareas"] = (real) $detalle[$i]["hectareas"];
				$hectareastotales = $hectareastotales + $detalle[$i]["hectareas"];
			}

			$nintencion = $this->d["nintencion"];
			if ($nintencion ==='') {
				$nintencion=Methods::JSONautoincrementable("../controller/config.json","intencion");
				$nintencion = "INT".str_pad($nintencion, 6,"0", STR_PAD_LEFT);
				$this->d["nintencion"] = $nintencion;
			}

			$sql="SELECT * FROM intencion WHERE ciclo=:ciclo AND rifentidad=:rifentidad";
			$param=array(":ciclo"=>$this->d['ciclo'],":rifentidad"=>$this->d['entidad']);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

			$stmt = $query["stmt"];
			$consultas = new Querys();
			if($stmt->rowCount()>0){

				$r = $stmt->fetch();
				$this->d["nintencion"] = $r["nrointencion"];
				$hectareastotales = $hectareastotales + $r["ha_total_hectareas"];
				//return $hectareastotales;

				$sql="UPDATE intencion SET ha_total_hectareas=:hectareas WHERE ciclo=:ciclo AND rifentidad=:rifentidad";
				$param=array(":ciclo"=>$this->d['ciclo'],":rifentidad"=>$this->d['entidad'], ":hectareas"=>$hectareastotales);

				$query = Querys::QUERYBD($sql,$param);
				$state = $query["state"];
				if(!$state) return Methods::returnArray(false,"Error en la consulta. No se pudo actualizar la información. ".$query["error"]);

				return $this->newDetalleIntencion();

			}else{
				$sql="INSERT INTO intencion (nrointencion, ciclo, rifentidad, fecha_intencion, ha_total_hectareas, estado) VALUES(:ndoc,:ciclo,:docentidad,:fecha,:hectareas,:estado)";

				$param=array(":ndoc"=>$this->d['nintencion'],":ciclo"=>$this->d['ciclo'],":docentidad"=>$this->d['entidad'],":fecha"=>date('Y-m-d h:m:s'), ":hectareas"=>$hectareastotales,":estado"=>1);

				$query = Querys::QUERYBD($sql,$param);
				$state = $query["state"];
				if(!$state) return Methods::returnArray(false,"Error en la consulta. No se pudo guardar la información. ".$query["error"]);

				return $this->newDetalleIntencion();
			}
		}

		public function newDetalleIntencion(){


			$filtro='';
			if ($_SESSION["nivel"]!=="ADMINISTRADOR") {
				$filtro = 'AND ' . $this->filtro;
			}

			$detalle = json_decode($this->d['dataDetails'],true);

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
				$sql = "SELECT * FROM detalle_intencion WHERE ciclo=:ciclo AND rifentidad=:entidad AND codrubro=:rubro ".$filtro;
				$param=array(":ciclo"=>$this->d['ciclo'], ":entidad"=>$this->d['entidad'], ":rubro"=>$detalle[$i]["codrubro"]);

				$query = Querys::QUERYBD($sql,$param);
				$state = $query["state"];
				if(!$state) {
					$result = Methods::returnArray(false,"Error en la consulta. ".$query["error"]);
					break;
				}

				$stmt=$query["stmt"];
				if ($stmt->rowCount()<1) {

					//OBTENER DIRECCION DE LA ENTIDAD SELECCIONADA SI EL USUARIO NO ES PRODUCTOR
					if($_SESSION["nivel"]!=='PRODUCTOR'){
						$sql = "SELECT * FROM entidad WHERE rifentidad=:docentidad";
						$param=array(":docentidad"=>$this->d['entidad']);

						$query = Querys::QUERYBD($sql,$param);
						$state = $query["state"];
						if(!$state) {
							$result = Methods::returnArray(false,"Error en la consulta. ".$query["error"]);
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
								$result = Methods::returnArray(false,"Error en la consulta. ".$query["error"]);
								break;
							}
						}						
					}

					//VERIFICAR HECTAREAS DISPONIBLES
					$hectareasDisponibles = $this->verifyHectareasDisponibles($detalle[$i]["docproductor"], $detalle[$i]["undproduccion"]);
					if ($hectareasDisponibles<$detalle[$i]["hectareas"]) {
						$result = Methods::returnArray(false,"El numero de hectareas seleccionado es superior al total de hectareas disponibles para este productor.");
						break;
					}

					//registrar nueva intencion
					$paramSQL = array(
						"campos" => [
							"nrointencion",
							"ciclo",
							"ced_rif",
							"razonsocialproductor",
							"codundprod",
							"nomundprod",
							"codrubro",
							"desrubro",
							"rifentidad",
							"razonsocialentidad",
							"ha_intencion",
							"cedtecnico",
							"nomtecnico",
							"estado",
							"municipio",
							"parroquia",
							"sector",
							"fecha_intencion",
							"estatus"
						],
						"values" => [
							$this->d['nintencion'],
							$this->d['ciclo'],
							strtoupper($detalle[$i]["docproductor"]),
							strtoupper($detalle[$i]["productor"]),
							$detalle[$i]["undproduccion"],
							'',
							$detalle[$i]["codrubro"],
							'',
							$this->d['entidad'],
							'',
							$detalle[$i]["hectareas"],
							$detalle[$i]["doctecnico"],
							$detalle[$i]["tecnico"],
							$detalle[$i]["estado"],
							$detalle[$i]["municipio"],
							$detalle[$i]["parroquia"],
							$detalle[$i]["sector"],
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
						$result =  Methods::returnArray(false,"No se pudo guardar la intención de siembra. ".$resultSQL);
						break;
					}else{

						$result = $this->restarHectareas($detalle[$i]["docproductor"],$detalle[$i]["undproduccion"],$detalle[$i]["hectareas"], $hectareasDisponibles);

						//$result =  Methods::returnArray(true,'Tu intención de siembra ha sido guardada exitosamente. ');
					}
				}else{
					$result = Methods::returnArray(false,"Ya existe un registro con este rubro");
					break;
				}
			}

			return $result;
		}

		public function getEntidad($filtro=''){
			$c = $this->d;

			if($filtro==''){
				$filtro = "AND ".$this->filtro;
				if($this->nivel==='ADMINISTRADOR') $filtro="";
				if($this->nivel==='PRODUCTOR') $filtro = "AND rifentidad='A000000001'";	
			}

			$sql="SELECT * FROM entidad WHERE estado LIKE :estado AND municipio LIKE :municipio AND parroquia LIKE :parroquia AND sector LIKE :sector ".$filtro;
			$param=array(
				"estado"=>$c["estado"],
				"municipio"=>$c["municipio"],
				"parroquia"=>$c["parroquia"],
				"sector"=>$c["sector"]
			);


			$query= Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

			$stmt = $query["stmt"];
			if($stmt->rowCount()>0){
				$arrayEntidad=[];

				while ($f = $stmt->fetch()) {
					array_push($arrayEntidad,array(
						"rifentidad"=>$f['rifentidad'],
						"razonsocial"=>$f['razonsocial'],
						"estado"=>$f["estado"],
						"municipio"=>$f["municipio"],
						"parroquia"=>$f["parroquia"],
						"sector"=>$f["sector"]
					));

				}


				return Methods::returnArray(true,"",$arrayEntidad);
			}else{
				return Methods::returnArray(true,"");
			}

		}

		public function getProductor(){
			$c = $this->d;
			//return $c;

			$filtro='';
			if($this->nivel==='PRODUCTOR') $filtro = "AND productor.".$this->filtro;

			$sql="SELECT productor.ced_rif as rif, productor.razonsocial as razonsocial FROM productor_entidad, productor WHERE productor.ced_rif = productor_entidad.ced_rif AND productor_entidad.rifentidad LIKE :docentidad ".$filtro;
			$param=array(
				"docentidad"=>$c["docentidad"]
			);

			$query= Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

			$stmt = $query["stmt"];
			if($stmt->rowCount()>0){
				$arrProductor=[];

				while ($f = $stmt->fetch()) {

					array_push($arrProductor,array(
						"rif"=>$f['rif'],
						"razonsocial"=>$f['razonsocial']
					));
				}

				return Methods::returnArray(true,"",$arrProductor);
			}else{
				return Methods::returnArray(true,"");
			}

		}

		public function getUNDproduccion($filtro=''){
			$c = $this->d;

			if($filtro==''){
				if($this->nivel==='PRODUCTOR') $filtro = "AND ".$this->filtro;				
			}

			$sql="SELECT
				unidadproduccion.codundprod as codigo,
				unidadproduccion.nomundprod as nombre,
				estado,
				municipio,
				parroquia,
				sector
			FROM
				unidadproduccion,
				undprod_productor 
			WHERE
				unidadproduccion.codundprod = undprod_productor.codundprod 
				AND undprod_productor.ced_rif = :docproductor ".$filtro;
			$param=array(
				":docproductor"=>$c["docproductor"]
			);


			$query= Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

			$stmt = $query["stmt"];
			if($stmt->rowCount()>0){
				$arrUNDprod=[];

				while ($f = $stmt->fetch()) {

					array_push($arrUNDprod,array(
						"codigo"=>$f['codigo'],
						"nombre"=>$f['nombre'],
						"estado"=>$f["estado"],
						"municipio"=>$f["municipio"],
						"parroquia"=>$f["parroquia"],
						"sector"=>$f["sector"]
					));
				}

				return Methods::returnArray(true,"",$arrUNDprod);
			}else{
				return Methods::returnArray(true,"");
			}

		}

		public function verifyHectareasDisponibles($docproductor, $docundprod){
			$sql="SELECT hadisponibles from undprod_productor where ced_rif = :docproductor AND codundprod = :undproduccion";
			$param=array(
				":undproduccion"=>$docundprod,
				":docproductor"=>$docproductor
			);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

			$stmt=$query["stmt"];
			if($stmt->rowCount()>0){
				$r=$stmt->fetch();
				$hadisponibles = $r["hadisponibles"];
				return $hadisponibles;
			}
		}

		public function verifyRubro(){

			$filtro='';
			if ($_SESSION["nivel"]!=="ADMINISTRADOR") $filtro = 'AND ' . $this->filtro;
			$detalle = json_decode($this->d['dataDetails'],true);

			$sql = "SELECT * FROM detalle_intencion WHERE ciclo=:ciclo AND rifentidad=:entidad AND codrubro=:rubro ".$filtro;
			$param=array(":ciclo"=>$this->d['ciclo'], ":entidad"=>$this->d['entidad'], ":rubro"=>$detalle[$i]["codrubro"]);

			$query = Querys::QUERYBD($sql,$param);
			$state = $query["state"];
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

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
			if(!$state) return Methods::returnArray(false,"Error en la consulta. ".$query["error"]);

			return Methods::returnArray(true,"Tu intención de siembra ha sido guardada exitosamente.");

		}

		/*public function deleteIntencion(){

			$deleteSQL = "DELETE FROM detalle_intencion WHERE nrointencion=:ndoc";

			$modelo= new conexion();
			$conexion=$modelo->get_conexion();
			$statement=$conexion->prepare($deleteSQL);
			$modelo=null;

			try {
				$statement->execute(array(":ndoc"=>$this->d['nintencion']));

				return Methods::returnArray(true,'');

			} catch (PDOException $e) {
				return Methods::returnArray(false,"ERROR FATAL. ".$e->getMessage());
			}

		*/
	}

?>