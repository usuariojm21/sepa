<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include("../controller/mainscript.php");
	require_once("../controller/methods.php");
require_once("../model/class.querys.php");
	require_once("../controller/class.productores.php");
	require_once("../controller/class.undproduccion.php");
require_once("../controller/class.direction.php");
	require_once("../controller/class.intencion.php");
$productores = new Productores();
	$list_productores = json_encode($productores->buscar(),JSON_FORCE_OBJECT);
$nUNDproduccion = count($undproduccion["data"]);

	if($_SESSION["nivel"]=='PRODUCTOR'){
		if($nUNDproduccion<1){
			header("location:./primerospasos");
		}else{
			$intencion = new IntencionSiembra(array(
					"ciclo"=>"%",
					"fecha1"=>"1900-01-01",
					"fecha2"=>"2050-12-31",
					"rubro"=>"%",
					"estado"=>"%",
					"municipio"=>"%",
					"parroquia"=>"%",
					"sector"=>"%",
					"productor"=>"%",
					"entidad"=>"%"
			));
			$verifyintencion = $intencion->buscar();
			if($verifyintencion["estado"]===true){
				$data = $verifyintencion["data"];
				if(count($data)<1){
					header("location:./primerospasos");
				}
			}
		}
	}

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
    <link rel="stylesheet" href="css/unidadc.css">
    <script>
      let jsonCiclos = JSON.parse(`<?php echo $totalCiclos; ?>`);
      var ssdata = JSON.parse(`<?php echo $session_data; ?>`);
      
    </script>
  </head>
  <body class="h-100">
    <div id="fondoDark"></div>
    <div class="container-fluid h-100">
      <header class="row">
        <div class="cont_logo col-auto d-flex justify-content-star">
          <div class="btnmenu col-auto align-self-center d-md-none" id="btn-menu"><a href="#"><i class="flaticon-boton-de-menu"></i></a></div>
          <div class="logo d-flex"><a class="align-self-center" href="./"><img src="./img/logotipo-color.svg" alt=""></a></div>
        </div>
        <div class="nav_bar col align-self-center d-flex justify-content-end position-static">
          <!--.items_mnubar.busqueda.col.align-self-center.d-flex-->
          <div class="items_mnubar ciclo-cosecha col-auto align-self-center dropdown-container"><span class="titleCicloActual"></span><a class="menu-toggle" id="cicloCosecha" href="#" title="Ciclo de Consecha"><i class="flaticon-recycle-water"></i></a>
            <div class="menu-dropdown" data-id="cicloCosecha">
              <div class="dropdown-title d-flex">
                <h6 class="header-toggle col">Ciclos de Cosecha</h6><a class="close-dropdown col-2" href="#"></a>
              </div>
              <hr>
            </div>
          </div>
          <!--.items_mnubar.notificacion.col-auto.align-self-center.dropdown-container
          a#notification.menu-toggle(href="#" title="Notificaciones"): i.flaticon-notificacion
          .menu-dropdown(data-id="notification")
          	h6.header-toggle Notificaciones
          	hr
          	a(href="#"): .menu-items
          		span Esta es una notificación
          -->
          <div class="items_mnbar user col-auto align-self-center d-flex justify-content-center align-items-center dropdown-container"><a class="menu-toggle align-selft-center" id="perfiluser" href="#"><img src="img/037-persona.svg" alt=""></a>
            <div class="menu-dropdown" data-id="perfiluser">
              <h6 class="header-toggle">Hola, <?php echo $_SESSION["usuario"]; ?></h6>
              <hr>
              <!--a(href="#"): .menu-itemsspan Configuración de Usuario
              --><a href="../controller/logout.session.php">
                <div class="menu-items"><span>Cerrar Sesión</span></div></a>
            </div>
          </div>
        </div>
      </header>
      <div class="main-content row h-100">
        <div class="barra_lateral col-12 col-md-auto">
          <nav>
            <!--.leyenda
            a(href="./dashboard" + formato_archivos) Principal
            span  / 
            a(href="./archivos" + formato_archivos) Archivos
            span  / Unidad de Producción
            -->
            <ul>
              <li><a class="flaticon-salpicadero" id="mnuprincipal" href="./dashboard">Principal</a></li><?php if ($show==2) echo '<li><a id="c-cosecha" class="flaticon-recycle-water" href="./ciclos">Ciclo de cosecha</a></li>'; ?>
              <li><a class="flaticon-agricultor" id="productores" href="./productores">Productor/Empresa</a></li>
              <li><a class="flaticon-siembra" id="u-produccion" href="./unidad_produccion">Unidad de Producción</a></li>
              <!--li: a#maquinaria.flaticon-cosechadora(href="./maquinaria") Maquinaria-->
              <!--li: a#almacenamiento.flaticon-inventario(href="./almacenamiento") Almacenamiento--><?php if ($show > 0) echo '<li><a id=e-financiero" class="flaticon-pagar" href="./entes_financieros">Entes financiero</a></li>'; ?>
              <!--|<?php if ($show > 0) echo '<li><a id=receptoria" class="flaticon-granero" href="./receptoria">Receptorías</a></li>'; ?>-->
              <li><a class="flaticon-obrero" id="t-campo" href="./tecnico_campo">Técnicos de campo</a></li><?php if ($show > 0) echo '<li><a id=rubro" class="flaticon-tractor-agricola" href="./rubros">Rubros</a></li>'; ?>
              <!--|<?php if ($show == 2) echo '<li><a id=ubicacion" class="flaticon-mapa" href="./ubicacion">Ubicación</a></li>'; ?>-->
              <li><a class="flaticon-coordenadas" id="paquetetec" href="./paquete_tecnologico">Paquete Tecnológico</a></li>
              <li><a class="flaticon-logout" id="logout" href="../controller/logout.session">Cerrar Sesión</a></li>
            </ul>
          </nav>
        </div>
        <main class="contenido col">
          <div class="row m-0">
            <section class="col-12" id="migasdepan">
              <div class="leyenda"><a href="./dashboard">Principal</a><span> > </span><a href="./archivos">Archivos</a><span>
                   > <b>Unidad de Producción</b></span></div>
            </section>
            <section class="col-12 col-lg">
              <div class="card" id="cont-up">
                <h3 class="flaticon-siembra">Unidad de Producción</h3>
                <ul class="nav nav-tabs d-none" id="tabUd" roles="tablist">
                  <li class="nav-item"><a class="nav-link active" id="tabList" href="#list" data-toggle="tab">Resumen</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabNew" href="#new" data-toggle="tab">Datos Principales</a></li>
                  <!--li.nav-item
                  a#tabData2.nav-link(href="#newad", data-toggle="tab") Datos Adicionales
                  
                  -->
                </ul>
                <div class="tab-content" id="contenidoTabUd">
                  <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <h4>Unidades de Producción registradas</h4><br>
                    <div class="table-responsive">
                      <table class="tabla-undproduccion table table-striped table-hover" id="tabla">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th class="d-none"></th>
                            <th>Nombre de Unidad</th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th>Hectareas</th>
                            <th>H. productivas</th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th>Estatus</th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          	$undproduccion = new UNDproduccion(array("busqueda"=>"%"));
                          	$data = $undproduccion->buscar();
                          	$estado = $data["estado"];
                          	$desc = $data["descripcion"];
                          	$reg = $data["data"];
                          	foreach ($reg as $key => $value) {
                          		$estatus = 'INACTIVO';
                          		$stateClassLayout = 'danger';
                          		if($value["estatus"]==1) {
                          			$estatus = 'ACTIVO';
                          			$stateClassLayout = 'success';
                          		}
                          		$dataprod = json_encode($value['dataproductores']);
                          		echo "<tr data-jsonprod='".$dataprod."'>
                          			<td><a href='javascript:void(0)' class='flaticon-lapiz-boton-de-editar edit'></a></td>
                          			<td class='d-none'>".$value["codigo"]."</td>
                          			<td>".$value["nombreund"]."</td>
                          			<td class='d-none'>".$value["direccion"]."</td>
                          			<td class='d-none'>".$value["estado"]."</td>
                          			<td class='d-none'>".$value["municipio"]."</td>
                          			<td class='d-none'>".$value["parroquia"]."</td>
                          			<td class='d-none'>".$value["sector"]."</td>
                          			<td>".$value["hatotal"]."</td>
                          			<td>".$value["haproductivas"]."</td>
                          			<td class='d-none'>".$value["coorprinlat"]."</td>
                          			<td class='d-none'>".$value["coorprinlog"]."</td>
                          			<td class='tbcellLayout ".$stateClassLayout."' data-estatus='".$value["estatus"]."'><span>".$estatus."</span></td>
                          			<td class='d-none'>".$value["codficha"]."</td>
                          			<td class='d-none'>".$value["urldocumentoficha"]."</td>
                          		</tr>";
                          	}
                          ?>													
                        </tbody>
                      </table>
                    </div>
                    <!--.row.mx-0.mt-3.justify-content-end
                    .col-auto.p-0.app
                    	button#btnNew.b-style-color1(type="button" onclick="js('#tabNew').click()") Nuevo Registro
                    -->
                  </div>
                  <div class="tab-pane fade" id="new" role="tabpanel">
                    <h4>Datos de la Unidad de producción</h4>
                    <form class="forms form-module" id="f_up" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                      <div class="row m-0">
                        <div class="col-12 p-1">
                          <p class="large-control">Ficha Predial</p>
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Codigo de la ficha</label>
                          <input class="no-valid" id="codFichaPredial" type="text" name="codfichapredial">
                        </div>
                        <div class="col-12 col-sm-8 p-1">
                          <label class="custom-label">Adjuntar documento de ficha predial</label>
                          <div class="custom-file">
                            <input class="custom-file-input no-valid" id="fileFichaPredial" type="file">
                            <label class="custom-file-label" for="fileFichaPredial">Cargar Archivo</label>
                          </div>
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label" for="nombre">Nombre de la Unidad de producción *</label>
                          <input id="nombre" type="text" name="nombre" maxlength="100">
                        </div>
                        <div class="col-12 col-sm-3 p-1">
                          <label class="custom-label" for="hatotal">Hectareas Totales *</label>
                          <input class="number" id="hatotal" type="text" name="haTotal" maxlength="8">
                        </div>
                        <div class="col-12 col-sm-3 p-1">
                          <label class="custom-label" for="haproductivas">Hectareas Productivas *</label>
                          <input class="number" id="haproductivas" type="text" name="haProductivas" maxlength="8">
                        </div>
                        <div class="w-100"></div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label" for="estado">Estado *</label>
                          <select class="custom-select" id="estado" name="estado">
                            <option value>Seleccione una opción</option><?php 
                            	$direccion = new Direccion();
                            	$data = $direccion->getEstados();
                            	$estado = $data["estado"];
                            	$desc = $data["descripcion"];
                            	$reg = $data["data"];
                            	foreach ($reg as $key => $value) {
                            		echo '<option value="'.$value['codigo'].'">'.$value["nombre"].'</option>';
                            	}?>
                          </select>
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label" for="municipio">Municipio *</label>
                          <select class="custom-select" id="municipio" name="municipio">
                            <option value>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label" for="parroquia">Parroquia *</label>
                          <select class="custom-select" id="parroquia" name="parroquia">
                            <option value>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label" for="sector">Sector *</label>
                          <div class="autocomplete">
                            <input id="sector" type="text" name="sector" data-twovalue="">
                          </div>
                        </div>
                        <div class="col-12 col-sm-8 p-1">
                          <label class="custom-label" for="dfiscal">Dirección *</label>
                          <input class="large-control" id="dfiscal" type="text" name="direccion" maxlength="255">
                        </div>
                        <div class="col-12 p-1">
                          <p class="large-control">Coordenadas Principales</p>
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label" for="latitud">Latitud</label>
                          <input class="no-valid" id="latitud" type="text" name="latitud" maxlength="50">
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label" for="longitud">Longitud</label>
                          <input class="no-valid" id="longitud" type="text" name="longitud" maxlength="50">
                        </div>
                        <div class="w-100"></div>
                        <div class="col-12 p-1">
                          <div class="custom-control custom-switch">
                            <input class="custom-control-input" id="switchUP" type="checkbox" name="estatus">
                            <label class="custom-control-label" for="switchUP">Activo/Inactivo</label>
                          </div>
                        </div>
                        <hr class="light">
                        <div class="col-12 p-1">
                          <p class="large-control">Agregar productores Productores</p>
                        </div>
                        <div class="col-12 col-sm p-1">
                          <div class="autocomplete">
                            <label class="custom-label" for="ndocproductor">Productores *													</label>
                            <input class="formProductores no-valid" id="ndocproductor" type="text" data-twovalue="">
                            <!--input#ndocproductor(type='hidden' name='productor')-->
                            <!--input#razonsocial.no-valid(type="hidden" readonly disabled) -->
                          </div>
                        </div>
                        <div class="col-12 col-sm p-1">
                          <label class="custom-label" for="tenencia">Tenencia *</label>
                          <select class="formProductores no-valid custom-select" id="tenencia">
                            <option value>Seleccione una opción</option><?php 
                            	$sql='SELECT * from tenencia';
                            	$param=[];
                            	$resultQuery = Querys::QUERYBD($sql,$param);
                            	$state = $resultQuery["state"];
                            	if ($state){
                            	$stmt = $resultQuery["stmt"];
                            		while ($f=$stmt->fetch()) {
                            			echo '<option value="'.$f['codtenencia'].'">'.$f['destenencia'].'</option>';
                            		}
                            	}
                            ?>
                          </select>
                        </div>
                        <div class="col-12 col-sm p-1">
                          <label class="custom-label" for="hadisponibles">Hectareas Disponibles *</label>
                          <input class="formProductores no-valid number" id="hadisponibles" type="text">
                        </div>
                        <div class="col-auto align-self-end p-1">
                          <input class="b-style-color2" id="addProductor" type="button" value="Agregar" onclick="VALIDaddProductores()">
                          <!--button#addProductor.b-style-color2 Agregar-->
                        </div>
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tbproductores">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>CI/Rif</th>
                                  <th>Razón Social</th>
                                  <th>Tenencia</th>
                                  <th class="text-right">Hect. disponibles</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                            <h5 class="d-none">No hay datos registrados</h5>
                          </div>
                        </div>
                        <!--.col-auto.p-0input#btnRegUP.b-style-color3(type="submit" value="Guardar")
                        -->
                        <!--.col-auto.pl-1input#btnClean.b-style-cancel(type="button" value="Cancelar")
                        -->
                        <div class="w-100"></div>
                        <div class="col-auto p-0 mt-2 ml-auto">
                          <input class="b-style-color1" id="btnBack" type="button" value="Volver" onclick="js('#tabList').click(),js('#buttonNew').removeAttribute('style')">
                        </div>
                        <div class="col-auto px-1 mt-2">
                          <input class="b-style-cancel" id="btnClean" type="button" value="Cancelar">
                        </div>
                        <div class="col-auto p-0 mt-2">
                          <input class="b-style-color3 load" id="btnReg" type="submit" value="Agregar">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </main>
        <div class="button-primary-new"><a class="flaticon-anadir" id="buttonNew" href="javascript:void(0)"></a></div>
        <div class="row m-0 position-fixed h-100" id="boxNew">
          <div class="align-self-end" id="boxButtons">
            <div class="item-button">
              <label>Intención de siembra</label><a class="flaticon-escritura" href="./intencion"></a>
            </div>
            <div class="item-button">
              <label>Productor</label><a class="flaticon-agricultor" href="./productores"></a>
            </div>
            <div class="item-button primary">
              <label>Nueva unidad de producción</label><a class="flaticon-siembra" href="javascript:void(0)" onclick="main.hideBoxNew(), js('#tabNew').click()"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script src="js/vue.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/axios.js"></script>
  <script src="js/imask.js"></script>
  <script src="js/sweetalert.min.js"></script>
  <!--script(src="js/Chart.bundle.min.js")-->
  <!--script(src="js/utils.js")-->
  <script src="js/main.js?v=100"></script>
  <script src="js/class.principal.js?v=100"></script>
  <script>
    $(document).ready(function(){
    	window.addEventListener("click",function(){
    		$(".menu-dropdown").hide();
    	})
    
    	//MOSTRAR Y OCULTAR EL MENU LATERAL EN EL MODO RESPONSIVE
    	var btnmenu = document.getElementById("btn-menu");
    	btnmenu.addEventListener("click",function(){
    		$(".barra_lateral").toggleClass('mostrarmenu');
    		$("#fondoDark").fadeToggle("slow");
    	});
    
    	$("#fondoDark").on("click",function(){
    		btnmenu.click();
    	});
    
    	window.addEventListener("resize",function(){
    		if(window.innerWidth>=768){
    			$(".barra_lateral").removeClass('mostrarmenu');
    			$("#fondoDark").fadeOut(0);
    		}
    	});
    
    	//MOSTRAR Y OCULTAR LOS MENUS DROPDOWNS
    	$(".menu-toggle").on("click",function(event){
    		event.stopPropagation();
    		var menu_dropdown = $(this).parent(".dropdown-container").children(".menu-dropdown");
    		$(".menu-dropdown").hide();
    		menu_dropdown.fadeToggle("fast");
    	});
    
    	$(".ciclo-cosecha").on("click",".menu-items",function(e){
    		let cicloc = $(this).data("ciclo");
    		localStorage.setItem('ciclo',cicloc);
    		location.reload();
    	});
    });
  </script>
  <script src="js/datatables/datatables.min.js"></script>
  <script src="js/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
  <script src="js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
  <script src="js/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
  <script src="js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
  <script src="js/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
  <script src="js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
  <script src="js/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
  <script src="js/class.undproduccion.js?v=2"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    main.boxNew();
    var productores = JSON.parse(`<?php echo $list_productores; ?>`);
    
    let n = `<?php echo $_SESSION["nivel"]; ?>`;
    
    let form = js('#f_up');
    let codigoficha = js("#codFichaPredial");
    let fileFicha = js("#fileFichaPredial");
    let ndocproductor = js("#ndocproductor");
    //let ndocproductor = js("#ndocproductor");
    let razonsocial = js("#razonsocial");
    let codigoUNDproduccion='';//'<?php //echo $DOCintencion;?>';
    let nombre = js("#nombre");
    let haTotal = js("#hatotal");
    let haProductivas = js("#haproductivas");
    let estado = js("#estado");
    let municipio = js("#municipio");
    let parroquia = js("#parroquia");
    let sector = js("#sector");
    let direccion = js("#dfiscal");
    let clatitud = js("#latitud");
    let clongitud = js("#longitud");
    let switchUP = js("#switchUP");
    let hadisponibles = js("#hadisponibles");
    let tenencia = js("#tenencia");
    let bGuardar = js("#btnRegUP");
    let bLimpiar = js("#btnClean");
    
    let updateundprod = 0;
    let valueStatus = 0;
    var blockElement = [];
    
    let undproduccion = new UNDProduccion(
    	codigoficha,
    	fileFicha,
    	ndocproductor,
    	razonsocial,
    	codigoUNDproduccion,
    	nombre,
    	haTotal,
    	haProductivas,
    	estado,
    	municipio,
    	parroquia,
    	sector,
    	direccion,
    	clatitud,
    	clongitud,
    	switchUP,
    	hadisponibles,
    	tenencia,
    	form
    		);
    
    $('.tabla-undproduccion').DataTable({
    		dom: 'Bfrtip',
    		buttons: [
    				'excel', 'pdf', 'print'
    		]
    });	 
    
    //obtener datos de la tabla y enviarlos al formulario
    $(document).on("click",".edit",function(e){
    	let tr = $(this).parents("tr");
    
    	let data = {
    		dataproductores: tr.data('jsonprod'),
    		codigo: tr.children("td").eq(1).text(),
    		nombreund: tr.children("td").eq(2).text(),
    		direccion: tr.children("td").eq(3).text(),
    		estado: tr.children("td").eq(4).text(),
    		municipio: tr.children("td").eq(5).text(),
    		parroquia: tr.children("td").eq(6).text(),
    		sector: tr.children("td").eq(7).text(),
    		hatotal: tr.children("td").eq(8).text(),
    		haproductivas: tr.children("td").eq(9).text(),
    		coorprinlat: tr.children("td").eq(10).text(),
    		coorprinlog: tr.children("td").eq(11).text(),
    		estatus: tr.children("td").eq(12).data('estatus'),
    		codficha: tr.children("td").eq(13).text(),
    		urldocumentoficha: tr.children("td").eq(14).text()
    	}
    
    	undproduccion.getRegistro(data);
    });
    
    //cargas listado de productores
    var arrProductores=[];
    for(let i in productores.data){
    	arrProductores.push([
    		productores.data[i].cRif,
    		productores.data[i].razonsocial			
    	]);
    }
    main.inputsearch(ndocproductor,arrProductores);
    
    //llenar select de municipio y parroquia
    estado.addEventListener("change",function(){
    	main.getMunicipio(this.value,municipio,'');
    	main.getParroquia(999999,parroquia,'');
    });
    municipio.addEventListener("change",function(){
    	main.getParroquia(this.value,parroquia,'');
    });
    parroquia.addEventListener("change",function(){
    	sector.value='';
    	sector.dataset.twovalue = '';
    	main.getSector(this.value,sector,'');
    });
    
    //limitar solo numeros
    IMask(codigoficha,{mask:EXPnumber});
    IMask(haTotal,{mask:EXPnumberDecimal});
    IMask(haProductivas,{mask:EXPnumberDecimal});
    IMask(clatitud,{mask:EXPnumberDecimal});
    IMask(clongitud,{mask:EXPnumberDecimal});
    IMask(hadisponibles,{mask:EXPnumberDecimal});
    
    //el monto de haProductivas no puede ser mayor que el de hatotale
    /*haProductivas.addEventListener("blur",function(e){
    	
    });*/
    
    //script para input file personalizado
    $(".custom-file-input").on("change", function() {
    	var fileName = $(this).val().split("\\").pop();
    	if(fileName==""){
    		$(this).siblings(".custom-file-label").addClass("selected").html("Cargar Archivo");
    		return;
    	}
    	$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    
    //agregar productores propietarios y arrendados
    function VALIDaddProductores(){
    	let f = jsAll(".formProductores");
    	let valid = ndocproductor.value === '' || ndocproductor.dataset.twovalue === '' || hadisponibles.value == '' || hadisponibles.value < 0 || tenencia.value === '';
    	//let valid = main.valid(f);
    	if(!valid){
    		undproduccion.addProductor(f);
    	}else{
    		swal("¡Error!", "Debe ingresar la información del productor que estará asociado a la unidad de producción.", "error",{
    			button:{
    			text: "Aceptar",
    			closeModal:true,
    			className:"errorSweetAlert"
    		}});
    	}
    }
    
    //guardar registro
    form.addEventListener('submit',function(e){
    	e.preventDefault();
    	undproduccion.validForm(function(e){
    		location.reload();
    	});
    
    });
    
    bLimpiar.addEventListener("click",function(e){
    	undproduccion.limpiar();
    });
  </script>
</html>