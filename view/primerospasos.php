<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include("../controller/mainscript.php");
	$undprod = $dataUNDproduccion["data"];
	$JSONundprod = json_encode($undprod);
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
		)
	);
	$verifyintencion = $intencion->buscar();
	if($verifyintencion["estado"]===true){
		$data = $verifyintencion["data"];
		
		if(count($data)>0){
			header('location:./dashboard');
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
    <link rel="stylesheet" href="css/pasos.css">
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
        <main class="contenido col">
          <div class="row m-0">
            <section class="col-12 col-lg">
              <div class="card" id="cont-up">
                <div class="row m-0">
                  <div class="col-12 p-0">
                    <h1 class="mb-2">Primeros pasos</h1>
                  </div>
                  <div class="row m-0 position-relative" style="overflow:hidden;">
                    <div class="pasos col-12 p-0" id="paso1">
                      <h3><strong>Paso 1</strong><span>Agrega una unidad de producción</span></h3>
                      <form class="forms form-module" id="f_up" action="" method="post" autocomplete="off" enctype="multipart/form-data">
                        <div class="row">
                          <div class="group-select col-12 col-sm">
                            <label for="undproduccion">UP existente</label>
                            <select class="custom-select" id="undproduccion" name="codundproduccion">
                              <option value="POR ASIGNAR" selected>POR ASIGNAR</option><?php
                              	foreach ($undprod as $key => $value) {
                              		echo '<option value="'.$value['codundprod'].'">'.strtoupper($value["nomundprod"]).'</option>';
                              	}?>																
                            </select>
                          </div>
                          <div class="group-input col-12 col-sm">
                            <label>Codigo de la ficha predial</label>
                            <input class="no-valid" id="codFichaPredial" type="text" name="codfichapredial">
                          </div>
                          <div class="group-input col-12 col-sm-6">
                            <label>Adjuntar documento de ficha predial</label>
                            <div class="custom-file">
                              <input class="custom-file-input no-valid" id="fileFichaPredial" type="file">
                              <label class="custom-file-label" for="fileFichaPredial">Cargar Archivo</label>
                            </div>
                            <input class="no-valid" id="ndocproductor" type="hidden" name="productor" data-twovalue="" value="">
                          </div>
                          <div class="group-input col-12 col-sm-6">
                            <label for="nombre">Nombre de la Unidad de producción *</label>
                            <input id="nombre" type="text" name="nombre" maxlength="100">
                          </div>
                          <div class="group-input col-12 col-sm-6">
                            <label for="hatotal">Hectareas Totales *</label>
                            <input id="hatotal" type="text" name="haTotal" maxlength="8">
                          </div>
                          <div class="group-input col-12 col-sm-6">
                            <label for="haproductivas">Hectareas Productivas *</label>
                            <input id="haproductivas" type="text" name="haProductivas" maxlength="8">
                          </div>
                          <div class="group-select col-12 col-sm">
                            <label for="tenencia">Tenencia *</label>
                            <select class="custom-select" id="tenencia" name="tenencia">
                              <option value="">Seleccione una opción</option>
                              <option value="1">PROPIA</option>
                              <option value="2">PRESTAMO DE USO</option>
                            </select>
                            <!--input#tenencia.no-valid(type="hidden" name="tenencia" value="1")-->
                          </div>
                          <div class="w-100"></div>
                          <div class="group-select col-12 col-sm-4">
                            <label for="estado">Estado *</label>
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
                          <div class="group-select col-12 col-sm-4">
                            <label for="municipio">Municipio *</label>
                            <select class="custom-select" id="municipio" name="municipio">
                              <option value>Seleccione una opción														</option>
                            </select>
                          </div>
                          <div class="group-select col-12 col-sm-4">
                            <label for="parroquia">Parroquia *</label>
                            <select class="custom-select" id="parroquia" name="parroquia">
                              <option value>Seleccione una opción</option>
                            </select>
                          </div>
                          <div class="group-input col-12 col-sm-4 autocomplete">
                            <label for="sector">Sector *</label>
                            <input id="sector" type="text" name="sector" data-twovalue="">
                          </div>
                          <div class="group-input col-12 col-sm-8">
                            <label for="dfiscal">Dirección *</label>
                            <input class="large-control" id="dfiscal" type="text" name="direccion" maxlength="255">
                          </div>
                          <div class="form-subtitle col-12">
                            <p class="large-control">Coordenadas Principales</p>
                          </div>
                          <div class="group-input col-12 col-sm-6">
                            <label for="latitud">Latitud *</label>
                            <input id="latitud" type="text" name="latitud" maxlength="50">
                          </div>
                          <div class="group-input col-12 col-sm-6">
                            <label for="longitud">Longitud *</label>
                            <input id="longitud" type="text" name="longitud" maxlength="50">
                          </div>
                          <div class="w-100"></div>
                          <div class="group-input col-12 col-sm-auto">
                            <input class="button-primary btn-reg" id="btnRegUP" type="submit" value="Siguiente">
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="pasos col-12 p-0 position-absolute" id="paso2">
                      <h3><strong>Paso 2</strong><span>Agrega una intención de siembra</span></h3>
                      <form class="forms form-module" id="fintencion" action="#" method="post" autocomplete="off">
                        <div class="row">
                          <div class="group-select col-12 col-sm">
                            <label for="rubros">Rubros *</label>
                            <select class="custom-select" id="rubros" name="rubros">
                              <option value selected>Seleccione una opción</option><?php 
                              	$sql='SELECT * from rubros';
                              	$param=[];
                              	$resultQuery = Querys::QUERYBD($sql,$param);
                              	$state = $resultQuery["state"];
                              	if ($state){
                              	$stmt = $resultQuery["stmt"];
                              		while ($f=$stmt->fetch()) {
                              			echo '<option value="'.$f['codrubro'].'">'.$f['desrubro'].'</option>';
                              		}
                              	}
                              ?>															
                            </select>
                          </div>
                          <div class="group-input col-12 col-sm">
                            <label for="haintencion">Hectareas de la intención *</label>
                            <input id="haintencion" type="text" name="haintencion" placeholder="Hectareas">
                          </div>
                          <div class="group-select col-12 col-sm">
                            <label for="ndoctecnico">Tecnico de campo</label>
                            <select class="custom-select" id="ndoctecnico" name="doctecnico">
                              <?php
                              	$sql='SELECT * from tecnico';
                              	$param=[];
                              	$resultQuery = Querys::QUERYBD($sql,$param);
                              	$state = $resultQuery["state"];
                              	if ($state){
                              	$stmt = $resultQuery["stmt"];
                              		while ($f=$stmt->fetch()) {
                              			echo '<option value="'.$f['cedtecnico'].'">'.$f["nomtecnico"].'</option>';
                              		}
                              	}
                              ?>															
                            </select>
                          </div>
                          <input id="ciclo" type="hidden" name="ciclo">
                          <!--input#docproductor(type="hidden", name="cirif")-->
                          <!--input#undproduccion(type='hidden' name='codundproduccion')-->
                          <input id="entidad" type="hidden" name="entidad">
                          <div class="w-100"></div>
                          <div class="group-input col-auto">
                            <input class="b-secundary" id="btnBack" type="button" value="Anterior">
                          </div>
                          <div class="group-input col-auto">
                            <input class="button-primary" id="btnNext" type="submit" value="Finalizar">
                          </div>
                        </div>
                      </form>
                    </div>
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
  <script src="js/class.undproduccion.js?v=1"></script>
  <script src="js/class.intencion.js?v=1"></script>
  <script src="js/class.primerospasos.js?v=1"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    
    var session = JSON.parse(`<?php echo $session_data; ?>`);
    var JSONundprod = JSON.parse(`<?php echo $JSONundprod; ?>`);
    
    let forms = document.getElementsByClassName('forms');
    let codigoficha = js("#codFichaPredial");
    let fileFicha = js("#fileFichaPredial");
    let ndocproductor = js("#ndocproductor");
    ndocproductor.value = session.cirif;
    ndocproductor.dataset.twovalue = session.cirif;
    let razonsocial = '';
    let codigoUNDproduccion='';
    let nombre = js("#nombre");
    let haTotal = js("#hatotal");
    let haProductivas = js("#haproductivas");
    let tenencia = js("#tenencia");
    let estado = js("#estado");
    let municipio = js("#municipio");
    let parroquia = js("#parroquia");
    let sector = js("#sector");
    let direccion = js("#dfiscal");
    let clatitud = js("#latitud");
    let clongitud = js("#longitud");
    let switchUP = document.createElement("input");
    switchUP.setAttribute("type","checkbox");
    switchUP.checked = true;
    
    let bGuardar = js("#btnRegUP");
    
    let rubros = js("#rubros");
    let tecnico = js("#ndoctecnico");
    
    let haintencion = js("#haintencion");
    IMask(haintencion,{mask:EXPnumberDecimal});
    
    let	ciclo = js("#ciclo");
    ciclo.value = jsonCiclos.ciclo_actual;
    
    let	entidad = js("#entidad");
    entidad.value =  session.entidad;
    
    let bGuardar2 = js("#bguardar");
    
    let updateundprod = 0;
    let valueStatus = 0;
    var blockElement = [];
    
    for (var i = 0; i < forms.length; i ++) {
    	forms[i].addEventListener('submit',function(e){
    		e.preventDefault();
    	});
    }
    
    //instanciar clase Pasos de class.primerospasos.js
    let pasos = new Pasos(ciclo.value,session.cirif,session.entidad);
    
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
    	tenencia
    		);
    
    //seleccionar unidad de produccion exitente y obtener datos
    js("#undproduccion").addEventListener("change",function(){
    	if(this.value==='') {
    		pasos.cleanFormUndProd();				
    		return;
    	}
    
    	let value = this.value;
    	JSONundprod.forEach(function(data){
    		if(value === data["codundprod"]){
    			undproduccion.codigoUNDproduccion = data["codundprod"];
    			codigoficha.value = data["codfichapredial"];
    			fileFicha.value = data["filefichapredial"];
    			nombre.value = data["nomundprod"];
    			haTotal.value = data["hatotal"];
    			haProductivas.value = data["haproductivas"];
    			tenencia.value = data["codtenencia"];
    			estado.value = data["estado"];
    			
    			main.getMunicipio(estado.value,municipio,data["municipio"],function(value){
    				main.getParroquia(value,parroquia,data["parroquia"],function(value2){
    					main.getSector(value2,sector,data["sector"]);
    				});
    			});
    
    			direccion.value = data["direccion"];
    			clatitud.value = data["coorprinlat"];
    			clongitud.value = data["coorprinlog"];
    
    			updateundprod = 1;
    		}
    	});
    });
    
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
    
    //script para input file personalizado
    $(".custom-file-input").on("change", function() {
    	var fileName = $(this).val().split("\\").pop();
    	if(fileName==""){
    		$(this).siblings(".custom-file-label").addClass("selected").html("Cargar Archivo");
    		return;
    	}
    	$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    
    //guardar registro
    let frmUNDproduccion = document.querySelector("#f_up");
    frmUNDproduccion.addEventListener('submit',function(e){
    	e.preventDefault();
    	undproduccion.guardar(function(data){
    		//console.log(data);
    		undproduccion.codigoUNDproduccion = data.undproduccion;
    		//paso 2: nueva intencion
    		js(".main-content").scroll(0,0);
    		js("#paso1").classList.add("complete");
    		js("#paso2").classList.add("visible");
    	});
    });
    
    //volver a paso anterior
    js("#btnBack").addEventListener("click",function(){
    	js(".main-content").scroll(0,0);
    	js("#paso1").classList.remove("complete");
    	js("#paso2").classList.remove("visible");
    });
    
    //guardar nueva intencion
    js("#fintencion").addEventListener("submit",function(e){
    	e.preventDefault();
    	pasos.newIntencion();
    });
  </script>
</html>