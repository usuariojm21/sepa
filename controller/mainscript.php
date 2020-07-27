<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start();
	require_once("../controller/obtener_ciclos.php");
	require_once("../controller/class.ciclos.php");
	require_once("../controller/class.datausers.php");
	require_once("../controller/class.intencion.php");
	require_once("../controller/class.productores.php");
	require_once("../controller/class.undproduccion.php");
	require_once("../controller/class.entidad.php");
	require_once("../controller/class.tecnico.php");
	require_once("../controller/class.direction.php");
	require_once("../controller/class.paquetetec.php");
		
	$ciclos = new Ciclo;
	if ($_GET) {
		$cod_ciclo = $_GET["c"];
		$totalCiclos = $ciclos->obtener_ciclo($cod_ciclo);
	}else{
		$totalCiclos = $ciclos->obtener_ciclo();
	}
	
	$users = new Users();
	//variable para las vistas por nivel de usuarios	
	$session_data = $users->session();
	$show = $users->verificarNivelesUsuarios();
	$dataProductores = $users->verifyDataproductor();
	$dataUNDproduccion = $users->verifyDataUNDproduccion();
	$dataEntidad = $users->verifyEntidad();
	$resumenHAtotales = $users->resumenTotalHectareas();

	//if($show>0)

	//if ($show==0) $active = Users::verifyUNDproduccion();

	//$Productores = $_SESSION["dataproductor"];
	//$undproduccion = $_SESSION["dataUNDproduccion"];

	//echo json_encode($resumenHAtotales,JSON_FORCE_OBJECT);

?>