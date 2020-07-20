<?php 

	class PaqueteTec{
		
		public $d;
		public $codcostop;
		public $filtro;
		public $nivel;

		function __construct($d=[]){
			$this->d = $d;
			$this->filtro = $_SESSION["filtro"];
			$this->nivel = $_SESSION["nivel"];
		}

		public function getEntidad(){
			if ($this->nivel==="ADMINISTRADOR" || $this->nivel==="MUNICIPAL") $filtro ='';
			if ($this->nivel==="ENTIDAD") $filtro = "WHERE ". $this->filtro;
			if ($this->nivel==="PRODUCTOR") return Methods::arrayMsj();

			$sql="SELECT * from entidad ".$filtro;
			$param=[];

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);
			$stmt = $rQuery["stmt"];
			if($stmt->rowCount()>0){
				$ARRentidad=[];

				while ($f = $stmt->fetch()) {
					array_push($ARRentidad, array(
						"ndoc"=>$f["rifentidad"],
						"razons"=>$f["razonsocial"]
					));
				}

				return Methods::arrayMsj(true,"",$ARRentidad);
			}else{
				return Methods::arrayMsj(false,"No se encontraron registros",$ARRentidad);
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
			if(!$state) return Methods::arrayMsj(false,"Error en la consulta. ".$query["error"]);

			$stmt = $query["stmt"];
			if($stmt->rowCount()>0){
				$arrProductor=[];

				while ($f = $stmt->fetch()) {

					array_push($arrProductor,array(
						"rif"=>$f['rif'],
						"razonsocial"=>$f['razonsocial']
					));
				}

				return Methods::arrayMsj(true,"",$arrProductor);
			}else{
				return Methods::arrayMsj(true,"");
			}

		}

		public function getPaqueteTec(){
				$sql="SELECT
					costoproduccion.codcostop AS codcostop,
					costoproduccion.rifentidad AS rifentidad,
					entidad.razonsocial AS rsocialentidad,
					costoproduccion.ced_rif AS docproductor,
					costoproduccion.ciclo AS ciclo,
					ciclos.desciclo AS desciclo,
					costoproduccion.codrubro AS rubro,
					rubros.desrubro AS desrubro,
					iddetalle,
					clasificacion,
					descripcion,
					unidadmedida,
					cantidad,
					costounitariomercado,
					costototalmercado,
					costounitarioencisa,
					costototalencisa 
				FROM
					costoproduccion,
					detalle_costoproduccion,
					entidad,
					ciclos,
					rubros 
				WHERE
					entidad.rifentidad = costoproduccion.rifentidad  
					AND ciclos.ciclo = costoproduccion.ciclo 
					AND rubros.codrubro = costoproduccion.codrubro
					AND detalle_costoproduccion.codcostop = costoproduccion.codcostop
					AND costoproduccion.ciclo LIKE :ciclo
					AND costoproduccion.rifentidad LIKE :entidad
					AND costoproduccion.codrubro LIKE :rubro";
				$param = array(
					":ciclo" => $this->d["ciclo"],
					":entidad" => $this->d["entidad"],
					":rubro" => $this->d["rubro"]
				);

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);
				$stmt = $rQuery["stmt"];
				if($stmt->rowCount()>0){
					$ARRpaquete=[];

					while ($f = $stmt->fetch()) {

						/*array_push($ARRpaquete,array(
							"codigo"=>$f['codigo'],
							"rifentidad"=>$f['rifentidad'],
							"ced_rif"=>$f['ced_rif'],
							"ciclo"=>$f['ciclo'],
							"descripcion"=>$f['descripcion'],
							"clasificacion"=>$f['clasificacion'],
							"unidadmedida"=>$f['unidadmedida'],
							"cantidad"=>$f['cantidad'],
							"costounitariomercado"=>$f['costounitariomercado'],
							"costototalmercado"=>$f['costototalmercado'],
							"costounitarioencisa"=>$f["costounitarioencisa"],
							"costototalencisa"=>$f["costototalencisa"]
						));*/

						array_push($ARRpaquete, array(
							"iddetalle"=>$f["iddetalle"],
							"codcostop"=>$f["codcostop"],
							"rifentidad"=>$f["rifentidad"],
							"rsocialentidad"=>$f["rsocialentidad"],
							"docproductor"=>$f["docproductor"],
							//"rsocialproductor"=>$f["rsocialproductor"],
							"ciclo"=>$f["ciclo"],
							"desciclo"=>$f["desciclo"],
							"rubro"=>$f["rubro"],
							"desrubro"=>$f["desrubro"],
							"clasificacion"=>$f["clasificacion"],
							"descripcion"=>$f["descripcion"],
							"unidadmedida"=>$f["unidadmedida"],
							"cantidad"=>$f["cantidad"],
							"costoumercado"=>$f["costounitariomercado"],
							"costotmercado"=>$f["costototalmercado"],
							"costouencisa"=>$f["costounitarioencisa"],
							"costotencisa"=>$f["costototalencisa"]
						));

					}

					return Methods::arrayMsj(true,"",$ARRpaquete);
				}else{
					return Methods::arrayMsj(false,"No hay datos registrados.");
				}
		}

		public function newPaquete(){
			//return $this->d;
			$update = $this->d["update"];

			$ndoc = $this->d["entidad"];
			$codigocostop = 'CP'.$this->d["ciclo"].$ndoc;
			$this->codcostop = $codigocostop;
			
			/**************BUSCAR REGISTRO EN LA TABLA COSTOPRODUCCION************/
			$sql="SELECT * from costoproduccion WHERE codcostop=:codigocosto";
			$param = array(":codigocosto"=>$this->codcostop);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);
			
			$stmt = $rQuery["stmt"];
			if ($stmt->rowCount()>0){
				
				//$this->codcostop = $getPaqueteTec["data"][0]["codigo"];
				return $this->detallepaquete();
				/*if ($update==1) {
					//update
					
				}else{
					//no update
					return Methods::arrayMsj(false,"Ya existe un registro perteneciente a este rubro");
				}*/
			}else{

				//if($this->nivel==="PRODUCTOR") $ndoc = $this->d["productor"];

				$sql="INSERT INTO costoproduccion VALUES(:codcostop, :rifentidad, '', :ciclo, :rubro)";
				$param=array(
					":codcostop"=>$codigocostop,
					":rifentidad"=>$this->d["entidad"],
					//":ced_rif"=>$this->d["productor"],
					":ciclo"=>$this->d["ciclo"],
					":rubro"=>$this->d["rubros"]
				);

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);

				//registrar paquete tecnologico
				return $this->detallepaquete();
			}
		}

		public function detallepaquete($update=false){

			//Estos parametros sirven tanto para update como para insert

			//obtener montos totales
			$costototalmercado = $this->d["cantidad"] * $this->d["costoum"];
			$costototalencisa = $this->d["cantidad"] * $this->d["costoue"];

			$parametros=array(
				":codigocosto"=>$this->codcostop,
				":descrip"=>$this->d["desc"],
				":clasificacion"=>$this->d["clasificacion"],
				":undmedida"=>$this->d["undmedida"],
				":cantidad"=>$this->d["cantidad"],
				":costoum"=>$this->d["costoum"],
				":costotm"=>$costototalmercado,
				":costoue"=>$this->d["costoue"],
				":costote"=>$costototalencisa
			);

			if ($update) {
				# update
				/*$sql="UPDATE detalle_costoproduccion SET descripcion=:descrip, clasificacion=:clasificacion, unidadmedida=:undmedida, cantidad=:cantidad, costounitariomercado=:costoum, costototalmercado=:costotm, costounitarioencisa=:costoue, costototalencisa=:costote WHERE codcostop=:codigocosto";
				$param=$parametros; //parametros declarados arriba

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);

				return Methods::arrayMsj(true,"Tu paquete tecnológico se ha modificado exitosamente.");*/

			}else{
				#insert
				$sql="INSERT INTO detalle_costoproduccion VALUES('', :codigocosto, :descrip, :clasificacion, :undmedida, :cantidad, :costoum, :costotm, :costoue, :costote)";
				$param=$parametros; //parametros declarados arriba

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);

				return Methods::arrayMsj(true,"Tu paquete tecnológico se ha registrado exitosamente.");

			}
		}

		public function deletePaqueteTec(){
			$codigocostop = 'CP'.$this->d["ciclo"].$this->d["entidad"];
			$this->codcostop = $codigocostop;
			$this->d["dataTable"] = json_decode($this->d["dataTable"],true);
			//return $this->d["dataTable"];

			$sql="DELETE FROM detalle_costoproduccion WHERE iddetalle=:id AND codcostop=:codigo";
			$param=array(
				":id"=>$this->d["dataTable"]["iddetalle"],
				":codigo"=>$this->codcostop
			);

			$rQuery= Querys::QUERYBD($sql,$param);
			$state=$rQuery["state"];
			if (!$state) return Methods::arrayMsj(false,$rQuery["error"]);

			return Methods::arrayMsj(true,"El registro ha sido eliminado exitosamente.");
		}

	}

?>