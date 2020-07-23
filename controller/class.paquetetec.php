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
			if ($this->nivel==="PRODUCTOR") return Methods::returnArray();

			$sql="SELECT * from entidad ".$filtro;
			$param=[];

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if (!$state) return Methods::returnArray(false,$rQuery["error"]);
			$stmt = $rQuery["stmt"];
			if($stmt->rowCount()>0){
				$ARRentidad=[];

				while ($f = $stmt->fetch()) {
					array_push($ARRentidad, array(
						"ndoc"=>$f["rifentidad"],
						"razons"=>$f["razonsocial"]
					));
				}

				return Methods::returnArray(true,"",$ARRentidad);
			}else{
				return Methods::returnArray(false,"No se encontraron registros",$ARRentidad);
			}
		}

		/*public function getProductor(){
			$c = $this->d;

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

		}*/

		public function getPaqueteTec(){
				$sql="SELECT
					paquete_tecnologico.codcostop AS codcostop,
					paquete_tecnologico.rifentidad AS rifentidad,
					entidad.razonsocial AS rsocialentidad,
					paquete_tecnologico.ced_rif AS docproductor,
					paquete_tecnologico.ciclo AS ciclo,
					ciclos.desciclo AS desciclo,
					paquete_tecnologico.codrubro AS rubro,
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
					paquete_tecnologico,
					dtpaquete_tecnologico,
					entidad,
					ciclos,
					rubros 
				WHERE
					entidad.rifentidad = paquete_tecnologico.rifentidad  
					AND ciclos.ciclo = paquete_tecnologico.ciclo 
					AND rubros.codrubro = paquete_tecnologico.codrubro
					AND dtpaquete_tecnologico.codcostop = paquete_tecnologico.codcostop
					AND paquete_tecnologico.ciclo LIKE :ciclo
					AND paquete_tecnologico.rifentidad LIKE :entidad
					AND paquete_tecnologico.codrubro LIKE :rubro";
				$param = array(
					":ciclo" => $this->d["ciclo"],
					":entidad" => $this->d["entidad"],
					":rubro" => $this->d["rubro"]
				);

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::returnArray(false,$rQuery["error"]);
				$stmt = $rQuery["stmt"];
				if($stmt->rowCount()>0){
					$ARRpaquete=[];

					while ($f = $stmt->fetch()) {
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

					return Methods::returnArray(true,"",$ARRpaquete);
				}else{
					return Methods::returnArray(false,"No hay datos registrados.");
				}
		}

		public function getAutocompleteData(){
			$campo = $this->d['campo'];

			$sql="SELECT $campo as campo FROM dtpaquete_tecnologico GROUP BY $campo ORDER BY :campo ASC";
			$param = array(':campo' => $campo);
			$rQuery = Querys::QUERYBD($sql,$param);
			if (!$rQuery["state"]) return Methods::returnArray(false,$rQuery["error"]);
			
			$ARRdata=[];
			$stmt = $rQuery["stmt"];
			//return $stmt->rowCount();
			if ($stmt->rowCount()>0){
				while ($f = $stmt->fetch()) {
					array_push($ARRdata, array(
						"campo"=>$f['campo']
					));
				}
			}

			return Methods::returnArray(true,"",$ARRdata);
		}

		public function newPaquete(){
			$update = $this->d["update"];

			$entidad = $this->d["entidad"];
			$ciclo = $this->d["ciclo"];
			$rubro = $this->d["rubro"];
			$codigocostop = "CP-$ciclo-$entidad-$rubro";
			$this->codcostop = $codigocostop;
			
			/**************BUSCAR REGISTRO EN LA TABLA paquete_tecnologico************/
			$sql="SELECT * from paquete_tecnologico WHERE codcostop=:codigocosto";
			$param = array(":codigocosto"=>$this->codcostop);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if (!$state) return Methods::returnArray(false,$rQuery["error"]);
			
			$stmt = $rQuery["stmt"];
			if ($stmt->rowCount()>0){
				
				return $this->detallepaquete();

			}else{

				$sql="INSERT INTO paquete_tecnologico VALUES(:codcostop, :rifentidad, '', :ciclo, :rubro)";
				$param=array(
					":codcostop"=>$codigocostop,
					":rifentidad"=>$this->d["entidad"],
					//":ced_rif"=>$this->d["productor"],
					":ciclo"=>$this->d["ciclo"],
					":rubro"=>$this->d["rubros"]
				);

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::returnArray(false,$rQuery["error"]);

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
				":descrip"=>strtoupper($this->d["desc"]),
				":clasificacion"=>strtoupper($this->d["clasificacion"]),
				":undmedida"=>$this->d["undmedida"],
				":cantidad"=>$this->d["cantidad"],
				":costoum"=>$this->d["costoum"],
				":costotm"=>$costototalmercado,
				":costoue"=>$this->d["costoue"],
				":costote"=>$costototalencisa
			);

			if ($update) {
				# update
				/*$sql="UPDATE dtpaquete_tecnologico SET descripcion=:descrip, clasificacion=:clasificacion, unidadmedida=:undmedida, cantidad=:cantidad, costounitariomercado=:costoum, costototalmercado=:costotm, costounitarioencisa=:costoue, costototalencisa=:costote WHERE codcostop=:codigocosto";
				$param=$parametros; //parametros declarados arriba

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::returnArray(false,$rQuery["error"]);

				return Methods::returnArray(true,"Tu paquete tecnológico se ha modificado exitosamente.");*/

			}else{
				#insert
				$sql="INSERT INTO dtpaquete_tecnologico VALUES('', :codigocosto, :descrip, :clasificacion, :undmedida, :cantidad, :costoum, :costotm, :costoue, :costote)";
				$param=$parametros; //parametros declarados arriba

				$rQuery = Querys::QUERYBD($sql,$param);
				$state = $rQuery["state"];
				if (!$state) return Methods::returnArray(false,$rQuery["error"]);

				return Methods::returnArray(true,"Tu paquete tecnológico se ha registrado exitosamente.");

			}
		}

		public function deletePaqueteTec(){
			$codigocostop = 'CP'.$this->d["ciclo"].$this->d["entidad"];
			$this->codcostop = $codigocostop;
			$this->d["dataTable"] = json_decode($this->d["dataTable"],true);
			//return $this->d["dataTable"];

			$sql="DELETE FROM dtpaquete_tecnologico WHERE iddetalle=:id AND codcostop=:codigo";
			$param=array(
				":id"=>$this->d["dataTable"]["iddetalle"],
				":codigo"=>$this->codcostop
			);

			$rQuery= Querys::QUERYBD($sql,$param);
			$state=$rQuery["state"];
			if (!$state) return Methods::returnArray(false,$rQuery["error"]);

			return Methods::returnArray(true,"El registro ha sido eliminado exitosamente.");
		}

	}

?>