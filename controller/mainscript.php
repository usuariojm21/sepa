<?php
	session_start();
 	require_once("../controller/obtener_ciclos.php");
 	require_once("../controller/class.datausers.php");
	
	$ciclos = new Ciclo;
	if ($_GET) {
		$cod_ciclo = $_GET["c"];
		$total_ciclos = $ciclos->obtener_ciclo($cod_ciclo);
	}else{
		$total_ciclos = $ciclos->obtener_ciclo();
	}
	
	$usuarios = new Usuarios();
	$show = $usuarios->verificarNivelesUsuarios(); //variable para las vistas por nivel de usuarios
	$session_data = $usuarios->session();
	if($show>0) $entidad = $usuarios->verifyEntidad();
	$nproductores = $usuarios->verifyDataProductor();
	$undproduccion = $usuarios->verifyUNDproduccion();
	$resumenHAtotales = $usuarios->resumenTotalHectareas();


	//if ($show==0) $active = $usuarios->verifyUNDproduccion();

	//$Productores = $_SESSION["dataproductor"];
	//$undproduccion = $_SESSION["dataUNDproduccion"];

	//echo json_encode($resumenHAtotales,JSON_FORCE_OBJECT);

?>