<?php 

	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start();

	require_once("../model/class.conexion.php");
	require_once("../model/class.querys.php");
	require_once("../model/functions.php");
	require_once("./methods.php");
	require_once("./consultas.php");
	require_once("./class.ciclos.php");
	require_once("./class.newuser.php");
	require_once("./class.productores.php");
	require_once("./class.undproduccion.php");
	require_once("./class.direction.php");
	require_once("./class.entidad.php");
	require_once("./class.tecnico.php");
	require_once("./class.intencion.php");
	require_once("./class.paquetetec.php");
	require_once("./class.modorapido.php");

	function clases($d=[]){
		$class = $_POST["class"];
		$method = $_POST["method"];

		if($class==='ciclos'){
			$class = new Ciclos($_POST);
	
			if ($method==1) return $class->buscar();
			if ($method==2) return $class->newCiclo();
			if ($method==3) return $class->delete(); 
		}else if($class==='newuser'){
			$class = new Users($_POST);
			if($method==1) return $class->verifyUsersProductor();
			if($method==2) return $class->newUser();
			//if($method==3) return $class->guardar();			

		}else if($class==='productores'){ //verificar la clase a la que se hace referencia

			$class = new Productores($_POST); //acceder a la clase
			//obtener el metodo a seleccionar
			if($method==1) return $class->buscar(); //ejecutar el metodo
			if($method==2) return $class->guardar(); //ejecutar el metodo

		}else if($class==='undproduccion'){
			$class = new UNDproduccion($_POST);
			if($method==1) return $class->buscar();
			//if($method==2) return $class->getproductor();
			if($method==3) return $class->guardar();
		}else if($class==='direccion'){
			$class = new Direccion($_POST);
			if($method==1) return $class->getEstados();
			if($method==2) return $class->newAndupdateEstados();
			if($method==3) return $class->deleteEstados();
			if($method==4) return $class->getMunicipios();
			if($method==5) return $class->newAndupdateMunicipios();
			if($method==6) return $class->deleteMunicipios();
			if($method==7) return $class->getParroquias();
			if($method==8) return $class->newAndupdateParroquias();
			if($method==9) return $class->deleteParroquias();
			if($method==10) return $class->getSectores();
			if($method==11) return $class->newAndupdateSectores();
			if($method==12) return $class->deleteSectores();
		}else if($class==='entidad'){
			$class = new Entidad($_POST);
			if($method==1) return $class->buscar();
			if($method==2) return $class->guardar();
		}else if($class==='tecnicodecampo'){
			$class = new Tecnicos($_POST);
			if($method==1) return $class->buscar();
			if($method==2) return $class->getDataTecnico();
			if($method==3) return $class->guardar();
			
			if($method==4) return $class->vincular();
		}else if($class==='rubros'){
			$class = new Rubros($_POST);
			if($method==1) return $class->guardargrupo();
			if($method==2) return $class->guardarsubgrupo();
			if($method==3) return $class->guardarrubro();
			if($method==4) return $class->obtenerdatos();
			if($method==5) return $class->buscar();
			if($method==6) return $class->eliminar();
		}else if($class==='paquetetec'){
			$class = new PaqueteTec($_POST);
			if($method==1) return $class->getEntidad();
			if($method==2) return $class->getProductor();
			if($method==3) return $class->getPaqueteTec();
			if($method==4) return $class->newPaquete();
			if($method==5) return $class->deletePaqueteTec();
		}else if($class==='intencion'){
			$class = new IntencionSiembra($_POST);
			if($method==1) return $class->buscar();
			if($method==2) return $class->newIntencion();
			if($method==3) return $class->getEntidad();
			if($method==4) return $class->getProductor();
			if($method==5) return $class->getUNDproduccion();
		}else if($class==='modorapido'){
			$class = new ModoRapido($_POST);
			if($method==1) return $class->guardar();
			//if($method==2) return $class->newundproduccion();
			//if($method==3) return $class->newintencion();
		}
	}

	$resp = clases($_POST);
	header('Content-Type: application/json');
	echo json_encode($resp,JSON_FORCE_OBJECT);

?>