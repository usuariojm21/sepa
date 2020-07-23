<?php 

	class Tecnicos{

		public $ci;
		public $nombre;
		public $tlf;
		public $correo;
		public $estado;
		public $municipio;
		public $parroquia;
		public $sector;
		public $direccion;
		public $state;
		public $busqueda;
		public $filtro;
		public $entidad;
		public $d;

		public function __construct($d){
			$this->ndoc = strtoupper($d['ci-rif']);
			$this->nombre = strtoupper($d['nombre']);
			$this->tlf = strtoupper($d['tlf']);
			$this->correo = strtoupper($d['correo']);
			$this->estado = strtoupper($d['estado']);
			$this->municipio = strtoupper($d['municipio']);
			$this->parroquia = strtoupper($d['parroquia']);
			$this->sector = strtoupper($d['codigosector']);
			$this->direccion = strtoupper($d['direccionfiscal']);
			$this->state = $d['estatus'];
			$this->busqueda = "%".$d["busqueda"]."%";
			$this->entidad = strtoupper($_SESSION['ente']);

			$this->d = $d;

			$this->filtro = $_SESSION["filtro"];

		}

		public function buscar(){

			$filtro = "";

			if ($_SESSION["nivel"]!=="ADMINISTRADOR") {
				if($_SESSION["nivel"]==='PRODUCTOR') $this->filtro = "rifentidad='A000000001'";
				$filtro = "AND entidad.".$this->filtro;
			}

			$modelo = new conexion();
			$conexion = $modelo->get_conexion();

			$sql = "SELECT
	tecnico.cedtecnico AS cedula,
	tecnico.nomtecnico AS nombre,
	tecnico.dirfiscal AS direccion,
	tecnico.telefonos AS telefonos,
	tecnico.correoe AS correoe,
	tecnico.estado AS estado,
	tecnico.municipio AS municipio,
	tecnico.parroquia AS parroquia,
	tecnico.sector AS sector,
	tecnico.estatus AS estatus,
	tecnico_entidad.rifentidad
FROM
	tecnico,
	tecnico_entidad, entidad
WHERE
	tecnico.cedtecnico = tecnico_entidad.cedtecnico
	AND tecnico_entidad.rifentidad = entidad.rifentidad
	AND ( tecnico.cedtecnico LIKE :busqueda OR tecnico.nomtecnico LIKE :busqueda ) ". $filtro;
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
						$arrayProd[]=array(
							"rifentidad"=>$f["rifentidad"],
							"cedula"=> $f["cedula"],
							"nombre"=> $f["nombre"],
							"direccion"=> $f["direccion"],
							"telefono"=> $f["telefonos"],
							"correoe"=>$f['correoe'],
							"estado"=>$f['estado'],
							"municipio"=>$f['municipio'],
							"parroquia"=>$f['parroquia'],
							"sector"=>$f['sector'],
							"estatus"=>$f['estatus']
						);
					}

				}

				return Methods::returnArray(true,"",$arrayProd);

			} catch (PDOException $e) {
				return "Problemas de conexión: ". $e->getMessage();
			}

		}

		public function getDataTecnico(){
			$sql="SELECT * FROM tecnico WHERE cedtecnico=:tecnico";
			$param=array(
				":tecnico"=>$this->ndoc
			);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery['state'];
			if(!$state) return Methods::returnArray(false,$rQuery['error']);

			$stmt = $rQuery["stmt"];
			$ARRtecnico=[];

			if($stmt->rowCount()>0){

				$this->busqueda = $this->ndoc;
				$searchTecnicoEntidad = $this->buscar();
				$data = $searchTecnicoEntidad["data"];
				$ndocEntidad = '';
				if(count($data)>0) $ndocEntidad = $data[0]['rifentidad'];

				while ($f=$stmt->fetch()) {
					array_push($ARRtecnico, array(
						"rifentidad"=>$ndocEntidad,
						"cedula"=> $f["cedtecnico"],
						"nombre"=> $f["nomtecnico"],
						"direccion"=> $f["dirfiscal"],
						"telefono"=> $f["telefonos"],
						"correoe"=>$f['correoe'],
						"estado"=>$f['estado'],
						"municipio"=>$f['municipio'],
						"parroquia"=>$f['parroquia'],
						"sector"=>$f['sector'],
						"estatus"=>$f['estatus']
					));
				}
			}

			return Methods::returnArray(true,'',$ARRtecnico);
		}

		public function guardar(){

			$update = $this->d["update"];
			$c = $this->d;

			//actualizar o registrar sectores
			$direccion = new Direccion(array(
				"codestado"=>$c["estado"],
				"cparroquia"=>$c["parroquia"],
				"codigosector"=>$c["codigosector"],
				"sector"=>$c["sector"]
			));
			$savesectores = $direccion->newAndupdateSectores();

			if($savesectores["estado"]===false) return $savesectores;

			$paramSQL = array(
				"campos" => [
					'cedtecnico',
					'nomtecnico',
					'dirfiscal',
					'telefonos',
					'correoe',
					'estado',
					'municipio',
					'parroquia',
					'sector',
					'estatus'
				],
				"values" => [
					$this->ndoc,
					$this->nombre,
					$this->direccion,
					$this->tlf,
					$this->correo,
					$this->estado,
					$this->municipio,
					$this->parroquia,
					$this->sector,
					$this->state
				],
				"tabla" => "tecnico",
				"filtro" => array(
					"state" => true,
					"condicion" => ["cedtecnico",$this->ndoc],
					"operador" => "=",
					"param_adicionales" => ''
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);

			if($resultSQL->rowCount() > 0){

				if ($update==0 || $update=="0") return Methods::returnArray(false,"El tecnico ya se encuentra registrado.");

				//actualizar
				$resultSQL = $consultas->update($paramSQL);

				if ($resultSQL===true) {
					//return Methods::returnArray(true,'El Tecnico ha sido actualizado exitosamente.');
					return $this->vincular();
				}else{
					return Methods::returnArray(false,$resultSQL);
				}
				
			}else{
				//no se encuentra, registrar
				$paramSQL['fitro']['state'] = false;
				$resultSQL = $consultas->insert($paramSQL);

				if ($resultSQL===true) {
					# listo, actualizado

					return $this->vincular();

				}else{
					#problemas con la actualizacion
					return Methods::returnArray(false,$resultSQL);
				}

			}

		}

		public function vincular(){

			//MODIFICAR ESTE METODO: BUSCAR REGISTRO POR CEDULA DEL TECNICO Y RIF DE ENTIDAD. SINO LO ENCUENTRA REGISTRARLO.
			$sql="SELECT * FROM tecnico_entidad WHERE rifentidad=:entidad AND cedtecnico=:tecnico";
			$param=array(
				":entidad"=>$this->entidad,
				":tecnico"=>$this->ndoc
			);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery['state'];
			if(!$state) return Methods::returnArray(false,$rQuery['error']);

			$stmt = $rQuery["stmt"];
			if ($stmt->rowCount()>0) return Methods::returnArray(true);

			$paramSQL = array( 
				"campos" => [
					'rifentidad',
					'cedtecnico',
					'nomtecnico'
				],
				"values" => [
					$this->entidad,
					$this->ndoc,
					$this->nombre
				],
				"tabla" => "tecnico_entidad",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "=",
					"param_adicionales" => ''
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->insert($paramSQL);

			if($resultSQL===true){
				//listo
				return Methods::returnArray(true);				
			}else{
				//problemas con el registro
				return Methods::returnArray(false,$resultSQL);
			}

		}

	}

?>