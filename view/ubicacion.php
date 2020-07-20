<?php include("../controller/mainscript.php");?><!DOCTYPE html>
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
    <link rel="stylesheet" href="css/ubicacion.css">
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
            <div class="leyenda"><a href="./dashboard">Principal</a><span> / </span><a href="./archivos">Archivos</a><span> / Ubicación</span></div>
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
            <section class="col-12 col-lg">
              <div class="card" id="cont-ub">
                <h3 class="flaticon-cosechadora">Ubicación</h3><br>
                <ul class="nav nav-tabs" id="tabUb" roles="tablist">
                  <li class="nav-item"><a class="nav-link active" id="tabList" href="#list" data-toggle="tab">Resumen</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData1" href="#new" data-toggle="tab">Estados</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData2" href="#new2" data-toggle="tab">Municipio</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData3" href="#new3" data-toggle="tab">Parroquia</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData4" href="#new4" data-toggle="tab">Sector</a></li>
                </ul>
                <div class="tab-content" id="contenidoTabUb">
                  <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <h4>Ubicaciones registradas</h4><br>
                    <div class="busqueda row">
                      <div class="col p-0">
                        <form class="forms form-inline" id="form-buscar" method="post" autocomplete="off">
                          <div class="col-12 p-0 position-relative" id="box-input-search">
                            <input class="btn_search" id="search_main" type="search" name="tBuscar" placeholder="Buscar">
                            <input class="btn_search flaticon-busqueda position-absolute" id="bsearch" type="submit" name="bSearch" value="Buscar">
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-bordered">
                        <thead>
                          <tr>
                            <th>Estado</th>
                            <th>Municipio</th>
                            <th>Parroquia</th>
                            <th>Sector </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><a class="item-estado" href="#">Portuguesa</a></td>
                            <td><a class="item-municipio" href="#">Guanare</a></td>
                            <td class="text-collapsed"><a class="item-parroquia" href="#">San Juan de Guanaguanare</a></td>
                            <td class="text-collapsed"><a class="item-sector" href="#">Mesa de Cavaca</a></td>
                          </tr>
                          <tr>
                            <td><a class="item-estado" href="#">Lara</a></td>
                            <td><a class="item-municipio" href="#">Iribarren</a></td>
                            <td class="text-collapsed"><a class="item-parroquia" href="#">Santa Rosa</a></td>
                            <td class="text-collapsed"><a class="item-sector" href="#">...</a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="new" role="tabpanel">
                    <h4>Datos del Estado</h4>
                    <form class="form-module form-inline" id="fEstado" action="#">
                      <input id="coEstado" type="text" name="codestado" readonly>
                      <input id="dsestado" type="text" name="dsestado" placeholder="Descripción">
                      <div class="w-100"></div>
                      <input class="button-primary btn-reg" id="btnRegEstado" type="button" value="Guardar">
                    </form>
                  </div>
                  <div class="tab-pane fade" id="new2" role="tabpanel">
                    <h4>Datos del Municipio</h4>
                    <form class="form-module form-inline" id="fMunicipio" action="#">
                      <select class="custom-select" id="estado">
                        <option value="" selected>Seleccione un Estado</option>
                      </select>
                      <div class="w-100"></div>
                      <input id="coMunicipio" type="text" name="comunicipio" placeholder="" readonly>
                      <input id="dsMunicipio" type="text" name="dsmunicipio" placeholder="Descripción">
                      <div class="w-100"></div>
                      <input class="button-primary btn-reg" id="btnRegMunicipio" type="button" value="Guardar">
                    </form>
                  </div>
                  <div class="tab-pane fade" id="new3" role="tabpanel">
                    <h4>Datos de la Parroquia</h4>
                    <form class="form-module form-inline" id="fParroquia" action="#">
                      <select class="custom-select" id="sEstado">
                        <option value="" selected>Seleccione un Estado</option>
                      </select>
                      <select class="custom-select" id="sMunicipio">
                        <option value="" selected>Seleccione un Municipio</option>
                      </select>
                      <div class="w-100"></div>
                      <input id="coParroquia" type="text" name="coparroquia" placeholder="" readonly>
                      <input id="dsParroquia" type="text" name="dsparroquia" placeholder="Descripción">
                      <div class="w-100"></div>
                      <input class="button-primary btn-reg" id="btnRegParroquia" type="button" value="Guardar">
                    </form>
                  </div>
                  <div class="tab-pane fade" id="new4" role="tabpanel">
                    <h4>Datos del Sector</h4>
                    <form class="form-module form-inline" id="fSector" action="#">
                      <select class="custom-select" id="sEstado">
                        <option value="" selected>Seleccione un Estado</option>
                      </select>
                      <select class="custom-select" id="sMunicipio">
                        <option value="" selected>Seleccione un Municipio</option>
                      </select>
                      <select class="custom-select" id="sParroquia">
                        <option value="" selected>Seleccione una Parroquia</option>
                      </select>
                      <div class="w-100"></div>
                      <input id="coSector" type="text" name="cosector" placeholder="" readonly>
                      <input id="dsSector" type="text" name="dssector" placeholder="Descripción">
                      <div class="w-100"></div>
                      <input class="button-primary btn-reg" id="btnRegSector" type="button" value="Guardar">
                    </form>
                  </div>
                </div>
              </div>
            </section>
            <aside class="col-12 col-lg-auto">
              <div class="card">
                <p>Información Adicional							</p>
              </div>
            </aside>
          </div>
        </main>
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
  <script src="js/rubros.js"></script>
  <script>
    var pmain = new Principal;
    pmain.main_process();
    
    var ubicacion = new ubicacion();
    ubicacion.item_selected();
  </script>
</html>