<?php 
	
	session_start();

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	class Rubros{
		public $grupo = '';
		public $dsgrupo = '';
		public $subgrupo = '';
		public $dssubgrupo = '';
		public $rubro = '';
		public $dsrubro = '';
		public $busqueda = '';
		public $d;


		public function guardargrupo($d){
			
			$this->dsgrupo = $d['dsgrupo'];
			
			$fglobal = new global_functions();

			$paramSQL = array(
				"campos" => [
					'desgrupo'
				],
				"values" => [
					$this->dsgrupo
				],
				"tabla" => "grupo",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "",
					"param_adicionales" => ''
				)
			);

			$consultas = new Consultas();
			$resultSQL = $consultas->insert($paramSQL);

			if($resultSQL===true){
				$r = $fglobal->arrayMsj(true,"Grupo guardado exitosamente. Agrega ahora los subgrupos correspondientes");
			}else{
				$r = $fglobal->arrayMsj(false,$resultSQL);
			}

			return $r;

		}

		public function guardarsubgrupo($d){
			$this->grupo = $d['codgrupo'];
			//$this->subgrupo = $d['codsubgrupo'];
			$this->dssubgrupo = $d['dssubgrupo'];
			
						
			$fglobal = new global_functions();

			$paramSQL = array(
				"campos" => [
					'codgrupo',
					'dessubgrupo'
				],
				"values" => [
					$this->grupo,
					$this->dssubgrupo
				],
				"tabla" => "subgrupo",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "",
					"param_adicionales" => ''
				)
			);

			$consultas = new Consultas();
			$resultSQL = $consultas->insert($paramSQL);

			if($resultSQL===true){
				$r = $fglobal->arrayMsj(true,"Sub-Grupo guardado exitosamente. Agrega ahora los rubros correspondientes");
			}else{
				$r = $fglobal->arrayMsj(false,$resultSQL);
			}

			return $r;

		}

		public function guardarrubro($d){
			$this->grupo = $d['codgrupo'];
			$this->subgrupo = $d['codsubgrupo'];
			$this->dsrubro = $d['dsrubro'];			
						
			$fglobal = new global_functions();

			$paramSQL = array(
				"campos" => [
					'codgrupo',
					'codsubgrupo',
					'desrubro'
				],
				"values" => [
					$this->grupo,
					$this->subgrupo,
					$this->dsrubro
				],
				"tabla" => "rubros",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "",
					"param_adicionales" => ''
				)
			);

			$consultas = new Consultas();
			$resultSQL = $consultas->insert($paramSQL);

			if($resultSQL===true){
				$r = $fglobal->arrayMsj(true,"Rubro guardado exitosamente.");
			}else{
				$r = $fglobal->arrayMsj(false,$resultSQL);
			}

			return $r;

		}

		public function obtenerdatos($d){
			$campos = $d['campos'];
			$tabla = $d['tabla'];
			$condicion = $d['condicion'];
			
			$sql = "SELECT $campos FROM $tabla WHERE $condicion";

			$fglobal = new global_functions();
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			try {

				$statement->execute();

				$arrayProd=[];

				if ($statement->rowCount() > 0) {

					while ($f = $statement->fetch()) {
						$arrayProd[]=array(
							"codigo"=> $f["cd"],
							"descripcion"=> $f["ds"]
						);
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

		public function buscar($d){
			$this->busqueda = "%".$d["busqueda"]."%";
			
			$sql = "SELECT
				grupo.codgrupo as grupo,
				grupo.desgrupo as dsgrupo,
				subgrupo.codsubgrupo as subgrupo,
				subgrupo.dessubgrupo as dssubgrupo,
				rubros.codrubro as rubro,
				rubros.desrubro as dsrubro
				FROM
				rubros, subgrupo, grupo
				WHERE
				grupo.codgrupo = subgrupo.codgrupo AND
				subgrupo.codsubgrupo = rubros.codsubgrupo AND
				rubros.desrubro LIKE :busqueda ORDER BY rubros.desrubro ASC";

			$fglobal = new global_functions();
			$modelo = new conexion();
			$conexion = $modelo->get_conexion();
			$statement = $conexion->prepare($sql);
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			try {

				$statement->execute(array(":busqueda"=>$this->busqueda));

				$arrayProd=[];

				if ($statement->rowCount() > 0) {

					while ($f = $statement->fetch()) {
						$arrayProd[]=array(
							"grupo"=> $f['grupo'],
							"dsgrupo"=> $f['dsgrupo'],
							"subgrupo"=> $f['subgrupo'],
							"dssubgrupo" => $f['dssubgrupo'],
							"rubro" => $f['rubro'],
							"dsrubro" => $f['dsrubro']
						);
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

		public function eliminar($d){

			$this->d = $d;

			$this->rubro = $d["rubro"];

			$deleteSQL = "DELETE FROM rubros WHERE codrubro=:codigo";

			$modelo= new conexion();
			$conexion=$modelo->get_conexion();
			$statement=$conexion->prepare($deleteSQL);
			$modelo=null;

			try {
				$statement->execute(array(":codigo"=>$this->rubro));

				return global_functions::arrayMsj(true,"");

			} catch (PDOException $e) {
				return global_functions::arrayMsj(false,"¡ERROR FATAL! ".$e->getMessage());
			}

		}

	}

	if ($_POST) {

		$rubros = new Rubros($_POST);

		$method = $_POST["method"];

		if ($method==1) {
			$resp = $rubros->guardargrupo($_POST);
		}else if($method==2){
			$resp = $rubros->guardarsubgrupo($_POST);
		}else if ($method==3) {
			$resp = $rubros->guardarrubro($_POST);
		}else if ($method==4) {
			$resp = $rubros->obtenerdatos($_POST);
		}else if ($method==5) {
			$resp = $rubros->buscar($_POST);
		}else if($method==6){
			$resp = $rubros->eliminar($_POST);
		}

	}else{
		$resp = global_functions::arrayMsj(false,"No se recibieron los datos por POST");
	}

	header('Content-Type: application/json');
	echo json_encode($resp,JSON_FORCE_OBJECT);


?>