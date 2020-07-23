<?php 

	require_once("../model/class.conexion.php");
	require_once("../model/class.consultas.php");
	require_once("../model/functions.php");

	$fglobal = new global_functions();

	if ($_POST) {
		
		$data = $_POST;
		$c = $data["data"];
		$update = $data["update"];

		$paramSQL = array(
			"campos" => ["ced_rif","razonsocial","dirfiscal","representante","telefonos","correoe","pagina","estatus"],
			"values" => [$c["rif"],$c["razonSocial"],$c["direccion"],$c["representante"],$c["tlf"],$c["email"],$c["url"],$c["estatus"]],
			"tabla" => "productor",
			"filtro" => array(
				"state" => true,
				"condicion" => ["ced_rif",$c["rif"]],
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

					$resp = $fglobal->returnArray(true,"El productor ha sido modificado.");
				}else{
					$resp = $fglobal->returnArray(true,$updateSQL);
				}
			}else{
				$resp = $fglobal->returnArray(false,"Este productor ya se encuentra registrado");
			}
		}else{
			#insertar
			$insertSQL = $consultas->insert($paramSQL);
			if ($insertSQL===true) {
				# registro realizado exitosamente

				$paramSQL2 = array(
					"campos" => [
						"rifentidad",
						"ced_rif",
						"razonsocialproductor"
					],
					"values" => [
						$c["rif_end"],
						$c["rif"],
						$c["razonSocial"]
					],
					"tabla" => "productor_entidad"
				);

				$insertSQL2 = $consultas->insert($paramSQL2);
				if ($insertSQL2 === true) {
					$resp = $fglobal->returnArray(true,"El productor ha sido registrado exitosamente.");
				}else{
					$resp = $fglobal->returnArray(false,$insertSQL2);
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