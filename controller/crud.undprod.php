<?php 

	session_start();

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	$fglobal = new global_functions();

	function construircodigo(){

		$arrayConfig = global_functions::leerConfig();
		$correlativo = $arrayConfig["autoincremento"]["unidadproduccion"] + 1;

		$arrayConfig["autoincremento"]["unidadproduccion"] = $correlativo;
		global_functions::escribirConfig($arrayConfig);
		
		return global_functions::autoincremento($correlativo);      
	}


	if ($_POST) {
		
		$c = $_POST;
		$update = $c["update"];

		$codigo = "UND".construircodigo();
		$_SESSION["codundproduccion"]=$codigo;

		if ($c["undproduccion"]=='') $c["undproduccion"] = $codigo;

		$paramSQL = array(
			"campos" => [
				"codundprod","nomundprod","dirundprod","estado","municipio","parroquia","sector","hatotal","haproductivas","coorprinlat","coorprinlog","estatus"
			],
			"values" => [$c["undproduccion"],$c["nombre"],$c["direccion"],$c["estado"],$c["municipio"],$c["parroquia"],$c["sector"],$c["haTotal"],$c["haProductivas"],$c["latitud"],$c["longitud"],$c["estatus"]],
			"tabla" => "unidadproduccion",
			"filtro" => array(
				"state" => true,
				"condicion" => ["codundprod",$c["undproduccion"]],
				"operador" => "=",
				"param_adicionales" => ""
			)
		);
		$consultas = new Consultas();
		$resultSQL = $consultas->select($paramSQL);
		$resultado=[];
		$add=[];

		if($resultSQL->rowCount() > 0){
			if ($update==1 || $update=="1") {
				#actualizar
				$updateSQL = $consultas->update($paramSQL);
				$resp =  $updateSQL;
				
				if ($updateSQL===true) {
					$resp = $fglobal->returnArray(true,"La unidad de producción ha sido modificada.");
				}else{
					$resp = $fglobal->returnArray(false,$updateSQL);
				}
			}else{
				$resp = $fglobal->returnArray(false,"Esta unidad de producción ya se encuentra registrada");
			}
		}else{
			#insertar
			$insertSQL = $consultas->insert($paramSQL);
			if ($insertSQL===true) {
				# registro realizado exitosamente

				//$resp = $fglobal->returnArray(true,"La unidad de producción ha sido registrada exitosamente.");

				$modelo = new conexion();
				$conexion = $modelo->get_conexion();
				$sql = "SELECT * FROM  undprod_productor WHERE ced_rif=:codprod AND codundprod=:cundprod";
				$statement = $conexion->prepare($sql);
				$statement->setFetchMode(PDO::FETCH_ASSOC);
				$modelo=null;
				try {
					$statement->execute(array("codprod"=>$c["productor"],"cundprod"=>$c["undproduccion"]));

					if (!$statement->rowCount() > 0) {

								$paramSQL2 = array(
									"campos" => [
										"ced_rif",
										"codundprod",
										"nomundprod"
									],
									"values" => [
										$c["productor"],
										$c["undproduccion"],
										$c["nombre"]
									],
									"tabla" => "undprod_productor"
								);

						$insertSQL2 = $consultas->insert($paramSQL2);

						if ($insertSQL2===true) {
							$resp = $fglobal->returnArray(true,"La unidad de producción ha sido registrada exitosamente.");
						}else{
							$resp = $fglobal->returnArray(false,$insertSQL2);
						}
					}


				} catch (PDOException $e) {
					$resp = $fglobal->returnArray(false,"Problemas con la consulta: ". $e->getMessage());
				}
				
			}else{
				# error en el registro
				$resp = $fglobal->returnArray(false,$insertSQL);
			}
		}
	}else{
		$resp = $fglobal->returnArray(false,"ERROR: No se recibieron los datos por POST. Por favor contacte con Soporte Tecnico");
	}

	header('Content-Type: application/json');
	echo json_encode($resp,JSON_FORCE_OBJECT);


?>