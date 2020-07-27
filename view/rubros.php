<?php 
	include("../controller/mainscript.php");

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

?><!DOCTYPE html>
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
    <link rel="stylesheet" href="css/rubros.css">
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
            <div class="leyenda"><a href="./dashboard">Principal</a><span> / </span><a href="./archivos">Archivos</a><span> / Rubros</span></div>
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
          <div class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Eliminar Rubro</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <p>Si eliminas el rubro no podras deshacer los cambios ¿Estas seguro de continuar?</p>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-danger" id="aceptar">Eliminar</button>
                  <button class="btn btn-secondary" id="cancel-modal" type="button" data-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </div>
          </div>
          <div class="row m-0">
            <section class="col-12" id="migasdepan">
              <div class="leyenda"><a href="./dashboard">Principal</a><span> > </span><a href="./archivos">Archivos</a><span>
                   > <b>Rubros</b></span></div>
            </section>
            <section class="col-12 col-lg">
              <div class="card" id="cont-rub">
                <h3 class="flaticon-cosechadora">Rubros</h3><br>
                <ul class="nav nav-tabs" id="tabRub" roles="tablist">
                  <li class="nav-item"><a class="nav-link active" id="tabListRub" href="#list" data-toggle="tab">Resumen</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData1" href="#new" data-toggle="tab">Grupos</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData2" href="#new2" data-toggle="tab">Sub-Grupos</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabData3" href="#new3" data-toggle="tab">Rubros</a></li>
                </ul>
                <div class="tab-content" id="contenidoTabRub">
                  <div class="tab-pane fade show active" id="list" role="tabpanel"><br>
                    <h4>Rubros registrados</h4><br>
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
                            <th>#</th>
                            <th>Grupo</th>
                            <th>Sub-Grupo</th>
                            <th>Rubro</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                        <h4 class="noData"></h4>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="new" role="tabpanel">
                    <!--h4 Datos del Grupo--><br>
                    <form class="forms form-rubros form-module" id="fGrupo" action="#" name="form-grupos" autocomplete="off">
                      <div class="row">
                        <div class="col-12">
                          <label class="custom-label">Descripción</label>
                          <input id="dsGrupo" type="text" name="dsgrupo" placeholder="Descripción">
                          <input type="hidden" name="method" value="1">
                        </div>
                        <div class="col-auto p-0 mt-2">
                          <input class="b-style-color3" id="btnRegGrupo" type="submit" value="Guardar">
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade" id="new2" role="tabpanel">
                    <!--h4 Datos del Sub-Grupo--><br>
                    <form class="forms form-module" id="fSubgrupo" action="#" name="form-subgrupos" autocomplete="off">
                      <div class="row">
                        <div class="col-12 col-sm-4">
                          <label class="custom-label">Grupo</label>
                          <select class="grupo custom-select" id="grupo" name="codgrupo">
                            <option value="" selected>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12 col-sm-8">
                          <label class="custom-label">Descripción de Subgrupo</label>
                          <input id="dsSubgrupo" type="text" name="dssubgrupo" placeholder="Descripción del Sub-Grupo">
                          <input type="hidden" name="method" value="2">
                        </div>
                        <div class="col-auto p-0 mt-2">
                          <input class="b-style-color3" id="btnRegSubgrupo" type="submit" value="Guardar">
                        </div>
                        <div class="col-auto pl-1 mt-2">
                          <input class="b-style-color1" id="btnClean1" type="button" value="Cancelar">
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="tab-pane fade" id="new3" role="tabpanel">
                    <!--h4 Datos del Rubro--><br>
                    <form class="forms form-rubros form-module" id="fRubro" action="#" name="form-rubros" autocomplete="off">
                      <div class="row">
                        <div class="col-12 col-sm-6">
                          <label class="custom-label">Grupo</label>
                          <select class="grupo custom-select" id="grupo" name="codgrupo">
                            <option value="" selected>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12 col-sm-6">
                          <label class="custom-label">Subgrupo</label>
                          <select class="custom-select" id="subgrupo" name="codsubgrupo">
                            <option value="" selected>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12">
                          <label class="custom-label">Descripción de Rubro</label>
                          <input id="dsRubro" type="text" name="dsrubro" placeholder="Descripción">
                          <input type="hidden" name="method" value="3">
                        </div>
                        <div class="col-auto p-0 mt-2">
                          <input class="b-style-color3" id="btnRegRubro" type="submit" value="Guardar">
                        </div>
                        <div class="col-auto pl-1 mt-2">
                          <input class="b-style-color1" id="btnClean2" type="button" value="Cancelar">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </section>
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
  <script src="js/class.rubros.js"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    
    let formBusqueda = document.querySelector("#form-buscar");
    
    let forms = document.getElementsByClassName("forms");
    let formGrupo = document.getElementById("fGrupo");
    let formSubGrupo = document.getElementById("fSubgrupo");
    let formRubro = document.getElementById("fRubro");
    
    let dsgrupo = forms['fGrupo']['dsGrupo'];
    let bgGrupo = forms['fGrupo']['btnRegGrupo'];
    
    let combrogrupo1 = forms['fSubgrupo']['grupo'];
    let dssubgrupo = forms['fSubgrupo']['dsSubgrupo'];
    let bgSubGrupo = forms['fSubgrupo']['btnRegSubgrupo'];
    
    let combrogrupo2 = forms['fRubro']['grupo'];
    let combosbgrupo = forms['fRubro']['subgrupo'];
    let dsrubro = forms['fRubro']['dsRubro'];
    let bgRubro = forms['fRubro']['btnRegRubro'];
    
    let rubro = new Rubro(
    	forms,
    	dsgrupo,
    	combrogrupo1,
    	dssubgrupo,
    	combrogrupo2,
    	combosbgrupo,
    	dsrubro
    );
    
    /*let rubros = new Rubros(forms,dsgrupo,
    combrogrupo1,
    dssubgrupo,
    combrogrupo2,
    combosbgrupo,
    dsrubro);
    
    let grupos = new Grupos(forms,dsgrupo,
    combrogrupo1,
    dssubgrupo,
    combrogrupo2,
    combosbgrupo,
    dsrubro);
    
    let subgrupos = new SubGrupos(forms,dsgrupo,
    combrogrupo1,
    dssubgrupo,
    combrogrupo2,
    combosbgrupo,
    dsrubro);
    
    let rubro = new Rubro(forms,dsgrupo,
    combrogrupo1,
    dssubgrupo,
    combrogrupo2,
    combosbgrupo,
    dsrubro);*/
    
    window.addEventListener('load',function(){
    	rubro.datos();
    	rubro.buscar();
    	rubro.obtenerGrupos();
    });
    
    var tr='';
    $(document).on("click","#deleteItem",function(e){
    	tr = $(this).parents("tr");
    });
    
    /*let bModalAceptar = document.querySelector("#aceptar");
    bModalAceptar.addEventListener("click",function(){
    
    	let codigoRubro = tr.children("td").eq(5).text();
    	rubro.eliminarRubro(codigoRubro);
    });*/
    
    let frmBuscar = document.querySelector("#form-buscar");
    frmBuscar.addEventListener("submit",function(e){
    	e.preventDefault();
    	rubro.buscar();
    });
    
    //eventos select grupos
    combrogrupo2.addEventListener("change",function(){
    	rubro.obtenerSubGrupos(this.value);
    });
    
    //eventos guardar
    forms["fGrupo"].addEventListener("submit",function(e){
    	e.preventDefault();
    	rubro.guardarDatosGrupos();
    });
    forms["fSubgrupo"].addEventListener("submit",function(e){
    	e.preventDefault();
    	rubro.guardarDatosSubGrupo();
    });
    forms["fRubro"].addEventListener("submit",function(e){
    	e.preventDefault();
    	rubro.guardarDatosRubro();
    });
    
    let bCleanSubgrupo = document.querySelector("#btnClean1");
    bCleanSubgrupo.addEventListener("click",function(){
    	combrogrupo1.value = "";
    	dssubgrupo.value = "";
    	combrogrupo1.focus();
    });
    
    let bCleanRubro = document.querySelector("#btnClean2");
    bCleanRubro.addEventListener("click",function(){
    	combrogrupo2.value = "";
    	combosbgrupo.value = "";
    	dsrubro.value = "";
    	combrogrupo2.focus();
    });
    
    /*this.items_tabla = function(){
    	$(".item-grupo").on("click",function(){
    		document.getElementById("tabData1").click();
    	});
    
    	$(".item-subgrupo").on("click",function(){
    		document.getElementById("tabData2").click();
    	});
    
    	$(".item-rubro").on("click",function(){
    		document.getElementById("tabData3").click();
    	});
    }*/
  </script>
</html>