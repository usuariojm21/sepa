<?php 

	class Users{
		public $d;

		function __construct($d=[]){
			$this->d = $d;
		}
		public function verifyUsersProductor(){ //verificar si el usuario ya se encuentra registrado en a tabla productores
			$nombre =$this->d["cedularif"];

			$paramSQL = array(
				"campos" => [],
				"values" => [],
				"tabla" => "productor",
				"filtro" => array(
					"state" => true,
					"condicion" => ["ced_rif",$nombre],
					"operador" => "=",
					"param_adicionales" => ''
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);

			if($resultSQL->rowCount() > 0){

				$f = $resultSQL->fetch();

				return Methods::returnArray(true,'',array(
					"razonsocial" => $f["razonsocial"],
					"dirfiscal" => $f["dirfiscal"],
					"representante" => $f["representante"],
					"correoe" => $f["correoe"]					
				));

			}else{
				return Methods::returnArray(false,'No se encuentra registrado');
			}
		}

		public function newUser(){

			//solo variables para tabla usuarios
			$entidad = 'A000000001';
			$nuser =$this->d["usuario"];
			$pass = base64_encode($this->d["clave"]);
			$niveluser = "PRODUCTOR";
			$estatus = 1;

			//variables para construir el filtro de niveles de usuario
			//$entidad =$this->d["entidad"];
			$cirif = strtoupper($this->d["cirif"]);
			//$municipio =$this->d["municipio"];

			//if ($niveluser=="PRODUCTOR") {
			$SQLfiltro = "ced_rif='$cirif'";
			//}

			$paramSQL = array(
				"campos" => [
					"rifentidad",
					"ced_rif",
					"usuario",
					"clave",
					"nivel",
					"filtro"
				],
				"values" => [
					$entidad,
					$cirif,
					$nuser,
					$pass,
					$niveluser,
					$SQLfiltro
				],
				"tabla" => "usuarios",
				"filtro" => array(
					"state" => true,
					"condicion" => ["usuario",$nuser],
					"operador" => "=",
					"param_adicionales" => ''
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->select($paramSQL);

			if($resultSQL->rowCount() > 0){
				return Methods::returnArray(false,"Este usuario ya se encuentra registrado.");
			}else{
				#Registrar nuevo usuario

				$paramSQL["filtro"]["state"] = false;
				$resultSQLinsert = $consultas->insert($paramSQL);
				if ($resultSQLinsert===true) {
					//Registrar nuevo productor o actualizar información de productor existente
					$productor = $this->newProductor();
					
					if ($productor["estado"]===true) {
						$_SESSION["accountcreated"]=true;
						return Methods::returnArray(true,"Tu cuenta se ha creado exitosamente.");
					}else{
						return Methods::returnArray(false,$productor["descripcion"]);
					}

				}else{
					return Methods::returnArray(false,$resultSQLinsert);
				}
			}
		}

		public function newProductor(){

			//solo variables para tabla productor
			$cirif = strtoupper($this->d["cirif"]);
			$representante = $this->d["nombrepresentante"];
			$rsocial = $this->d["rsocial"];
			$correo = $this->d["correo"];
			$dfiscal = $this->d["dfiscal"];
			$estatus = 1;

			$paramSQL = array(
				"campos" => [
					"ced_rif",
					"razonsocial",
					"dirfiscal",
					"representante",
					"correoe",
					"estatus"
				],
				"values" => [
					$cirif,
					$rsocial,
					$dfiscal,
					$representante,
					$correo,
					$estatus
					
				],
				"tabla" => "productor",
				"filtro" => array(
					"state" => true,
					"condicion" => ["ced_rif",$cirif],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQLselect = $consultas->select($paramSQL);

			if($resultSQLselect->rowCount() > 0){
				$resultSQL = $consultas->update($paramSQL);
			}else{
				$resultSQL = $consultas->insert($paramSQL);
			}

			if ($resultSQL===true) {
				//informacion de productor ingresada o actualizada
				return $this->newProductorEntidad();
			}else{
				//error en la consulta
				return Methods::returnArray(false,$resultSQL);
			}

		}

		public function newProductorEntidad(){

			//solo variables para tabla productor-entidad
			$entidad='A000000001';
			$cirif = strtoupper($this->d["cirif"]);
			$nuser = $this->d["usuario"];
			$rsocial = $this->d["rsocial"];

			$paramSQL = array(
				"campos" => [
					"rifentidad",
					"ced_rif",
					"razonsocialproductor",
					"usuario"
				],
				"values" => [
					$entidad,
					$cirif,
					$rsocial,
					$nuser				
				],
				"tabla" => "productor_entidad",
				"filtro" => array(
					"state" => false,
					"condicion" => [],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);

			$consultas = new Querys();
			$resultSQL = $consultas->insert($paramSQL);

			if ($resultSQL===true) {
				//informacion de productor ingresada o actualizada
				return Methods::returnArray(true,'');
			}else{
				//error en la consulta
				return Methods::returnArray(false,$resultSQL);
			}

		}

	}

?>