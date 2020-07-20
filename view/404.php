
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

?>

<!DOCTYPE html>
<html class="h-100" lang="es">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!--link(href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i,900,900i", rel="stylesheet")-->
    <!--link(rel="stylesheet", href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900")-->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font/flaticon.min.css">
    <title>Sepa</title>
    <link rel="stylesheet" href="css/dashboard.css">
  </head>
  <body class="h-100">
    <div class="container-fluid h-100" id="error404">
      <div class="main-content row h-100">
        <main class="contenido col">
          <div class="row m-0 h-100 justify-content-center align-items-center">
            <section class="col-12 align-self-center" id="error404">
              <h1>404</h1>
              <h5>No se encuentra la pagina :(</h5><a id="volver" href="./dashboard">Ir al inicio</a>
            </section>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>