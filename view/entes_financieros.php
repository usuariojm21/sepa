<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include("../controller/mainscript.php");

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
    <link rel="stylesheet" href="css/efinanciero.css">
    <script>
      let jsonCiclos = JSON.parse(`<?php echo $totalCiclos; ?>`);
      var ssdata = JSON.parse(`<?php echo $session_data; ?>`);
      //console.log(ssdata);
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
            <div class="leyenda"><a href="./dashboard">Principal</a><span> / </span><a href="./archivos">Archivos</a><span> / Entes Financieros</span></div>
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
                   > <b>Entes Financieros</b></span></div>
            </section>
            <section class="col-12 col-lg">
              <div class="card" id="cont-ente">
                <h3 class="flaticon-pagar">Entes Financieros</h3>
                <ul class="nav nav-tabs d-none" id="tabEnte" roles="tablist">
                  <li class="nav-item"><a class="nav-link active" id="tabList" href="#list" data-toggle="tab">Resumen</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabNew" href="#new" data-toggle="tab">Datos Principales								</a></li>
                </ul>
                <div class="tab-content" id="contenidoTabEnte">
                  <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <h4>Entes Registrados</h4>
                    <div class="table-responsive">
                      <table class="tabla-entidad table table-striped table-hover" id="tabla">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>C.I./Rif</th>
                            <th>Razón Social</th>
                            <th>Dirección</th>
                            <th>Telefono</th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                            <th class="d-none"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          	$entidadf = new Entidad(array("busqueda"=>"%"));
                          	$data = $entidadf->buscar();
                          	$estado = $data["estado"];
                          	$desc = $data["descripcion"];
                          	$reg = $data["data"];
                          	foreach ($reg as $key => $value) {
                          		if($value["rif"]!=='A000000001'){
                          			echo "<tr>
                          				<td><a href='javascript:void(0)' class='flaticon-lapiz-boton-de-editar edit'></a></td>
                          				<td>".$value["rif"]."</td>
                          				<td>".$value["razonsocial"]."</td>
                          				<td class='text-collapsed'>".$value["direccion"]."</td>
                          				<td class='text-collapsed'>".$value["telefono"]."</td>	
                          				<td class='d-none'>".$value["correoe"]."</td>
                          				<td class='d-none'>".$value["representante"]."</td>
                          				<td class='d-none'>".$value["telfrepresentante"]."</td>
                          				<td class='d-none'>".$value["paginaweb"]."</td>
                          				<td class='d-none'>".$value["estado"]."</td>
                          				<td class='d-none'>".$value["municipio"]."</td>
                          				<td class='d-none'>".$value["parroquia"]."</td>
                          				<td class='d-none'>".$value["sector"]."</td>
                          				<td class='d-none'>".$value["estatus"]."</td>
                          				<td class='d-none'>".$value["usuario"]."</td>
                          			</tr>";
                          		}
                          	}
                          ?>																		
                        </tbody>
                      </table>
                      <!--h4.noData-->
                    </div>
                  </div>
                  <div class="tab-pane fade" id="new" role="tabpanel">
                    <h4>Nuevo ente financiero</h4>
                    <form class="form-module" id="fEnte" action="#" method="post" autocomplete="off">
                      <div class="row m-0">
                        <div class="col-12 col-sm-3 p-1">
                          <label class="custom-label">CI/Rif *</label>
                          <input id="crif" type="text" name="ci-rif" maxlength="10" placeholder="Ej. J321459658">
                        </div>
                        <div class="col-12 col-sm-5 p-1">
                          <label class="custom-label">Razón Social *</label>
                          <input id="razons" type="text" name="razons" maxlength="100">
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Telefono *</label>
                          <input id="tlf" type="tel" name="tlf" maxlength="100">
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label">Correo Electrónico *</label>
                          <input id="correo" type="email" name="correo" maxlength="50" placeholder="juan@gmail.com">
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label">Dirección de Pagina Web</label>
                          <input class="no-valid" id="pagina" type="text" name="pagina" maxlength="255" placeholder="www.ejemplo.org">
                        </div>
                        <div class="w-100"></div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Estado *</label>
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
                          <label class="custom-label">Municipio *</label>
                          <select class="custom-select" id="municipio" name="municipio">
                            <option value>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Parroquia</label>
                          <select class="custom-select no-valid" id="parroquia" name="parroquia">
                            <option value>Seleccione una opción</option>
                          </select>
                        </div>
                        <div class="col-12 col-sm-4 autocomplete p-1">
                          <label class="custom-label">Sector</label>
                          <input class="no-valid" id="sector" type="text" name="sector" data-twovalue="">
                        </div>
                        <div class="col-12 col-sm-8 p-1">
                          <label class="custom-label">Dirección</label>
                          <input class="large-control no-valid" id="dfiscal" type="text" name="dfiscal" maxlength="200">
                        </div>
                        <div class="col-12 p-1">
                          <p class="large-control">Representante Legal</p>
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label">Nombre y Apellido</label>
                          <input id="representante" type="text" name="rlegal" maxlength="100">
                        </div>
                        <div class="col-12 col-sm-6 p-1">
                          <label class="custom-label">Telefono</label>
                          <input id="tlfrepresentante" type="text" name="tlfrlegal" maxlength="50">
                        </div>
                        <div class="col-12 p-1">
                          <p class="large-control">Datos de usuario</p>
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Usuario *</label>
                          <input id="nomusuario" type="text" name="nomusuario">
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Contraseña *</label>
                          <input id="claveusuario" type="password" name="claveusuario">
                        </div>
                        <div class="col-12 col-sm-4 p-1">
                          <label class="custom-label">Confirmar contraseña *</label>
                          <input id="confirmarclave" type="password" name="confirmarclave">
                        </div>
                        <div class="col-12">
                          <div class="custom-control custom-switch">
                            <input class="custom-control-input" id="switchEnte" type="checkbox" name="estatus">
                            <label class="custom-control-label" for="switchEnte">Activo/Inactivo</label>
                          </div>
                        </div>
                        <div class="col-auto p-0 mt-2 ml-auto">
                          <input class="b-style-color1" id="btnBack" type="button" value="Volver" onclick="js('#tabList').click(),js('#buttonNew').removeAttribute('style')">
                        </div>
                        <div class="col-auto px-1 mt-2">
                          <input class="b-style-cancel" id="btnClean" type="button" value="Cancelar">
                        </div>
                        <div class="col-auto p-0 mt-2">
                          <input class="b-style-color3 load" id="btnReg" type="submit" value="Guardar">
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
            <div class="item-button">
              <label>Técnico de campo</label><a class="flaticon-obrero" href="./tecnico_campo"></a>
            </div>
            <div class="item-button primary">
              <label>Nuevo Ente financiero</label><a class="flaticon-pagar" href="javascript:void(0)" onclick="main.hideBoxNew(), js('#tabNew').click()"></a>
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
  <script src="js/class.entes.js?v=2"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    main.boxNew();
    
    let forms = js('#fEnte');
    let crif = js("#crif");
    let razons = js("#razons");
    let tlf = js("#tlf");
    let correo = js("#correo");
    let pagina = js("#pagina");
    let estado = js("#estado");
    let municipio = js("#municipio");
    let parroquia = js("#parroquia");
    let sector = js("#sector");
    let dfiscal = js("#dfiscal");
    let representante = js("#representante");
    let tlfrepresentante = js("#tlfrepresentante");
    let nomusuario = js("#nomusuario");
    let claveusuario = js("#claveusuario");
    let confirmarclave = js("#confirmarclave");
    let switchState = js("#switchEnte");
    let bGuardar = js("#btnReg");
    let bLimpiar = js("#btnClean");
    
    let updateEnte = 0;
    let valueStatus = 0;
    var blockElement = [crif,nomusuario];
    
    IMask(crif,{mask:EXPrifMask});
    IMask(tlf,{mask:'0000-0000000'});
    IMask(tlfrepresentante,{mask:'0000-0000000'});
    
    var entes = new Entes(
    	crif,
    	razons,
    	tlf,
    	correo,
    	pagina,
    	estado,
    	municipio,
    	parroquia,
    	sector,
    	dfiscal,
    	representante,
    	tlfrepresentante,
    	nomusuario,
    	claveusuario,
    	confirmarclave,
    	switchState
    );
    
    $('.tabla-entidad').DataTable({
    		dom: 'Bfrtip',
    		buttons: [
    				'excel', 'pdf', 'print'
    		]
    });	
    
    //obtener datos de la tabla y enviarlos al formulario
    $(document).on("click",".edit",function(e){
    	let tr = $(this).parents("tr");
    
    	let data = {
    		rif: tr.children("td").eq(1).text(),
    		razonsocial: tr.children("td").eq(2).text(),
    		direccion: tr.children("td").eq(3).text(),
    		telefono: tr.children("td").eq(4).text(),
    		correoe: tr.children("td").eq(5).text(),
    		representante: tr.children("td").eq(6).text(),
    		telfrepresentante: tr.children("td").eq(7).text(),
    		paginaweb: tr.children("td").eq(8).text(),
    		estado: tr.children("td").eq(9).text(),
    		municipio: tr.children("td").eq(10).text(),
    		parroquia: tr.children("td").eq(11).text(),
    		sector: tr.children("td").eq(12).text(),
    		estatus: tr.children("td").eq(13).text(),
    		usuario: tr.children("td").eq(14).text()
    	}
    
    	entes.getRegistro(data);
    })
    
    //llenar select de municipio y parroquia y sector
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
    
    //guardar
    forms.addEventListener("submit",function(){
    	event.preventDefault();
    	entes.guardar();
    });
    
    bLimpiar.addEventListener("click",function(e){
    	entes.limpiar();
    })
  </script>
</html>