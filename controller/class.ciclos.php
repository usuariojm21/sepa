<?php 

	class Ciclos{
		public $d;

		function __construct($d=[]){
			$this->d = $d;
		}

		public function buscar(){
			$busqueda = "%".$this->d["busqueda"]."%";

			$paramSQL = array(
				"campos" => ["ciclo","desciclo","desdeciclo","hastaciclo","estatus"],
				"values" => [],
				"tabla" => "ciclos",
				"filtro" => array(
					"state" => true,
					"condicion" => ["desciclo",$busqueda],
					"operador" => " LIKE ",
					"param_adicionales" => "ORDER BY desdeciclo DESC"
				)
			);

			$consultas = new querys();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$ARRAYciclos=[];

			if ($resultSQL->rowCount()>0) {			
				while ($f = $resultSQL->fetch()) {
					array_push($ARRAYciclos,array(
						"ciclo"=> $f["ciclo"],
						"desciclo"=> $f["desciclo"],
						"desdeciclo"=> $f["desdeciclo"],
						"hastaciclo"=> $f["hastaciclo"],
						"estatus"=>$f["estatus"]
					));
				}

				return Methods::returnArray(true,"",$ARRAYciclos);

			}else{
				return Methods::returnArray(false,"No se encontró ningun registro");
			}
		}
		public function newCiclo(){
			$c = $this->d;
			$update = $c["update"];


			$paramSQL = array(
				"campos" => ["ciclo","desciclo","desdeciclo","hastaciclo","estatus"],
				"values" => [$c["codigo"],$c["desCiclo"],$c["cicloDesde"],$c["cicloHasta"],$c["estatus"]],
				"tabla" => "ciclos",
				"filtro" => array(
					"state" => true,
					"condicion" => ["ciclo",$c["codigo"]],
					"operador" => "=",
					"param_adicionales" => ""
				)
			);
			$consultas = new querys();
			$resultSQL = $consultas->select($paramSQL);
			$resultado=[];
			$add=[];

			if($resultSQL->rowCount() > 0){
				if ($update==1 || $update=="1") {
					#actualizar
					$updateSQL = $consultas->update($paramSQL);
					
					if ($updateSQL===true) {
						return Methods::returnArray(true,"El ciclo ha sido modificado.");
					}else{
						return Methods::returnArray(true,$updateSQL);
					}
				}else{
					return Methods::returnArray(false,"Este ciclo ya se encuentra registrado");
				}
			}else{
				#insertar
				$insertSQL = $consultas->insert($paramSQL);
				if ($insertSQL===true) {
					# registro realizado exitosamente
					return Methods::returnArray(true,"El ciclo ha sido registrado exitosamente.");
				}else{
					# error en el registro
					return Methods::returnArray(true,$insertSQL);
				}
			}
		}

		public function delete(){
			$sql='DELETE FROM ciclos WHERE ciclo=:codigo';
			$param=array(":codigo"=>$this->d["codigo"]);
			$rQuery = Querys::QUERYBD($sql,$param);
			$state = $rQuery["state"];
			if (!$state===true) return Methods::returnArray(false,$rQuery["error"]);

			return Methods::returnArray(true,"");

		}
	}

?>