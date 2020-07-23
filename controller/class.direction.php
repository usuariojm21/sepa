<?php 

	class Estados{

		/*public $codigoEstado;
		public $codigoMunicipio;
		public $codigoParroquia;*/
		public $d;
		function __construct($d=[]){
			$this->d = $d;
			/*$this->codEstado = $d["cestado"];
			$this->codMunicipio = $d["cmunicipio"];
			$this->codParroquia = $d["cparroquia"];*/
		}

		public function getEstados(){
			$paramSQL = array(
				"campos" => ["codestado","nomestado"],
				"values" => [],
				"tabla" => "estados",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
			$arrayData=[];

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					array_push($arrayData,array(
						"codigo"=> $f["codestado"],
						"nombre"=> $f["nomestado"]
					));
				}
				return Methods::returnArray(true,"",$arrayData);

			}else{
				return Methods::returnArray(false,"No se encontró ningun registro");
			}
		}
		public function newAndupdateEstados(){

		}
		public function deleteEstados(){

		}
	}

	class Municipios extends Estados{
		public function getMunicipios(){
			$codigo=$this->d["cestado"];

			$paramSQL = array(
				"campos" => [],
				"values" => [],
				"tabla" => 'municipios',
				"filtro" => array(
					"state" => true,
					"condicion" => ['codestado',$codigo],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
	
			$arrayData=[];

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					array_push($arrayData,array(
						"codigo"=> $f["codmunicipio"],
						"municipio"=> $f["nommunicipio"]
					));
				}


				return Methods::returnArray(true,"",$arrayData);

			}else{
				return Methods::returnArray(false,"No se encontró ningun registro");
			}
		}
		public function newAndupdateMunicipios(){

		}
		public function deleteMunicipios(){

		}
	}

	class Parroquias extends Municipios{
		public function getParroquias(){
			$codigo=$this->d["cmunicipio"];

			$paramSQL = array(
				"campos" => [],
				"values" => [],
				"tabla" => 'parroquias',
				"filtro" => array(
					"state" => true,
					"condicion" => ['codmunicipio',$codigo],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
			$arrayData=[];

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					array_push($arrayData,array(
						"codigo"=> $f["codparroquia"],
						"parroquia"=> $f["nomparroquia"]
					));
				}


				return Methods::returnArray(true,"",$arrayData);

			}else{
				return Methods::returnArray(false,"No se encontró ningun registro");
			}
		}
		public function newAndupdateParroquias(){

		}
		public function deleteParroquias(){

		}		
	}

	class Sectores extends Parroquias{
		public function getSectores(){
			$codigo=$this->d["cparroquia"];

			$paramSQL = array(
				"campos" => [],
				"values" => [],
				"tabla" => 'sectores',
				"filtro" => array(
					"state" => true,
					"condicion" => ['codparroquia',$codigo],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);
			$arrayData=[];

			if ($resultSQL->rowCount()>0) {

				while ($f = $resultSQL->fetch()) {
					array_push($arrayData,array(
						"codigo"=> $f["codsector"],
						"sector"=> $f["nomsector"]
					));
				}


				return Methods::returnArray(true,"",$arrayData);

			}else{
				return Methods::returnArray(false,"No se encontró ningun registro");
			}
		}
		public function newAndupdateSectores(){
			$cestado = $this->d["codestado"];
			$cparroquia = $this->d["cparroquia"];
			$codigosector = $this->d["codigosector"];
			$sector = strtoupper($this->d["sector"]);

			$sql = "SELECT * FROM sectores WHERE codparroquia=:cparroquia AND codsector=:codsector";
			$param = array(":cparroquia"=>$cparroquia, ":codsector"=>$codigosector);

			$rQuery = Querys::QUERYBD($sql,$param);
			$state=$rQuery["state"];
			if (!$state) return Methods::returnArray(false,$rQuery["error"],[]);
			$stmt = $rQuery["stmt"];

			if($stmt->rowCount()<1){
				#si no se encuentra
				$rQuery='';
				$sql='';
				$param='';
				$state='';

				//verificar total de sectores pertenecientes al codigo de parroquia y generar el autonumerico
				$sql = "SELECT * FROM sectores WHERE codsector LIKE '$cparroquia%'";
				$param=[];
				// $param = array(":codparroquia"=>$cparroquia);

				$rQuery = Querys::QUERYBD($sql,$param);
				$state=$rQuery["state"];
				if (!$state) return Methods::returnArray(false,$rQuery["error"],[]);
				$stmt = $rQuery["stmt"];

				if($stmt->rowCount()<1){
					$codigosector = $cparroquia.'01';
				}else{
					$r=$stmt->rowCount()+1;
					if($r<10) $r='0'.$r;
					$codigosector = $cparroquia.$r;
				}

				
				$sql = "INSERT INTO sectores (codsector,nomsector,grupo,codparroquia,codestado) VALUES(:codigo,:sector,'SIN GRUPO',:cparroquia,:codestado)";
				$param = array(":codigo"=>$codigosector, ":sector"=>$sector,":cparroquia"=>$cparroquia,":codestado"=>$cestado);

				$rQuery = Querys::QUERYBD($sql,$param);
				$state=$rQuery["state"];
				if (!$state) return Methods::returnArray(false,$rQuery["error"],[]);
			}

			return Methods::returnArray(true,"",[]);
		}
		public function deleteSectores(){

		}
	}

	class Direccion extends Sectores{

	}

?>