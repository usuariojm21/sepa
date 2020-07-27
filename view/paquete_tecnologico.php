<?php
	include("../controller/mainscript.php");
	require_once("../controller/class.paquetetec.php");
	require_once("../controller/class.intencion.php");
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
    <link rel="stylesheet" href="css/paquetetec.css">
    <script>
      let jsonCiclos = JSON.parse(`<?php echo $totalCiclos; ?>`);
      //var listEstados = JSON.parse(`<?php //echo $obtener_estados; ?>`);
      var ssdata = JSON.parse(`<?php echo $session_data; ?>`);
      //console.log(ssdata.filtro);
      
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
            <div class="leyenda"><a href="./dashboard">Principal</a><span> / </span><a href="./archivos">Archivos</a><span> / Paquete Tecnológico</span></div>
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
                   > <b>Paquete Tecnológico</b></span></div>
            </section>
            <section class="col-12 col-lg">
              <div class="card" id="cont-productores">
                <h3 class="flaticon-agricultor">Paquete Tecnológico</h3><br>
                <ul class="nav nav-tabs d-none" id="tabProductores" roles="tablist">
                  <li class="nav-item"><a class="nav-link active" id="tabListpaquetetec" href="#list" data-toggle="tab">Paquetes registrados</a></li>
                  <li class="nav-item"><a class="nav-link" id="tabNewpaquetetec" href="#new" data-toggle="tab">Datos Principales</a></li>
                </ul>
                <div class="tab-content" id="contenidoTabproductores">
                  <div class="tab-pane fade show active" id="list" role="tabpanel">
                    <h4>Paquetes registrados</h4>
                    <hr>
                    <form id="fbuscar" action="">
                      <div class="row m-0">
                        <div class="col-12 col-sm p-1">
                          <select class="custom-select" id="entidad" name="entidad">
                            <option value="">Entidad</option><?php
                            	$paqueteTec = new PaqueteTec();
                            	$entidad = $paqueteTec->getEntidad();
                            	$estado = $entidad["estado"];
                            	$desc = $entidad["descripcion"];
                            	$reg = $entidad["data"];
                            	foreach ($reg as $key => $value) {
                            		echo "<option value='".$value['ndoc']."'>".$value['razons']."</option>";
                            	}
                            	if(count($reg)<1) echo "<option value='A000000001'>Autofinanciado</option>";
                            ?>
                          </select>
                        </div>
                        <!--.col-12.col-sm.p-1.autocomplete
                        input#lproductor(type="text" name="productor" placeholder="Productor" data-twovalue="")
                        //select#lproductor.custom-select(name='productor' v-model="lproductor")
                        	option(value="") Productor
                        -->
                        <div class="col-12 col-sm p-1">
                          <select class="custom-select" id="rubros" name="rubro">
                            <option value="">Rubros</option>
                            <?php
                            	$sql = "SELECT * FROM rubros";
                            	$param=[];
                            	$rQuery = Querys::QUERYBD($sql,$param);
                            	$state=$rQuery["state"];
                            	if($state){
                            		$stmt = $rQuery["stmt"];
                            		while($f=$stmt->fetch()){
                            			echo "<option value='".$f['codrubro']."'>".$f['desrubro']."</option>";
                            		}
                            	}
                            ?>
                          </select>
                        </div>
                        <div class="col-auto p-1">
                          <input class="b-style-color2" id="buscarPaquete" type="submit" name="buscar" value="Buscar">
                        </div>
                      </div>
                    </form>
                    <!--Paquetes productivos registrado-->
                    <div class="table-responsive">
                      <table class="tabla-paquetetec table table-striped table-hover table-bordered" id="tablaPacT">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th class="d-none" title="Clasificación">Clasif.</th>
                            <th>Descripción</th>
                            <th>Und. Medida</th>
                            <th>Cantidad</th>
                            <th title="Costo Unitario de Mercado">Cs/U Mercado</th>
                            <th title="Costo Total de Mercado">Cs/T Mercado</th>
                            <th title="Costo Unitario Encisa">Cs/U Encisa</th>
                            <th title="Costo Total Encisa">Cs/T Encisa</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                      <h4 class="noData"></h4>
                    </div>
                    <!--.row.mx-0.mt-3.justify-content-end
                    .col-auto.p-0.app
                    	button#btnNewPaquete.b-style-color1.d-none(type="button" @click='newPaqueteForm') Nuevo paquete
                    -->
                  </div>
                  <div class="tab-pane fade" id="new" role="tabpanel">
                    <div class="row mx-1">
                      <div class="col p-1">
                        <h4>Agrega los items de tu paquete tecnológico</h4>
                      </div>
                    </div>
                    <form class="form-module" id="fpaquete" action="#" method="post" autocomplete="off">
                      <div class="row">
                        <div class="col-12 col-sm p-1 autocomplete">
                          <label>Clasificación *</label>
                          <div class="autocomplete">
                            <input id="clasificacion" type="text" name="clasificacion" placeholder="Siembra, Insumos" v-model="clasificacion">
                          </div>
                        </div>
                        <div class="col-12 col-sm p-1 autocomplete">
                          <label>Descripción *</label>
                          <input id="desc" type="text" name="desc" placeholder="Arado, Semilla" v-model="desc">
                        </div>
                        <div class="col-12 col-sm p-1">
                          <label>Unidad de Medida *</label>
                          <select class="custom-select" id="undmedida" name="undmedida" v-model="undmedida">
                            <option value="">Seleccione una opción</option>
                            <option value="pase">Pase</option>
                            <option value="plantas">Plantas</option>
                            <option value="lts.">Lts.</option>
                            <option value="galon">Galon</option>
                            <option value="unidad.">Unidad</option>
                            <option value="kg.">Kg.</option>
                            <option value="tn.">Tn.</option>
                            <option value="grs.">Grs.</option>
                            <option value="m3">M3</option>
                            <option value="jornal">Jornal</option>
                            <option value="%">%</option>
                            <option value="días">Días</option>
                            <option value="riego">Riego</option>
                            <option value="cesta">Cesta</option>
                            <option value="ton.">Ton.</option>
                            <option value="saco">Saco</option>
                            <option value="viaje">Viaje</option>
                            <option value="tasa">Tasa</option>
                            <option value="alícuota">Alícuota</option>
                            <option value="vuelo">Vuelo</option>
                            <option value="cuota">Cuota</option>
                            <option value="varas">Varas</option>
                            <option value="paquete">Paquete</option>
                            <option value="tm-ha">Tm/Ha</option>
                            <option value="lata">Lata</option>
                            <option value="sobres">Sobres</option>
                            <option value="cormo">Cormo</option>
                            <option value="muestra">Muestra</option>
                          </select>
                          <!--input#undmedida(type="text", name="undmedida" placeholder='Kg, Litro, Jornal')-->
                        </div>
                        <div class="w-100"></div>
                        <div class="col-12 col-sm col-md p-1">
                          <label>Cantidad *</label>
                          <input class="number" id="cantidad" type="text" name="cantidad" v-model="cantidad" v-masknumberdc>
                        </div>
                        <div class="col-12 col-sm col-md p-1">
                          <label>Costo Unit. de Mercado *</label>
                          <input class="number" id="costoum" type="text" name="costoum" v-model="costoum" v-masknumberdc>
                        </div>
                        <!--.col-12.col-sm-4.col-md.p-1
                        label Costo Total de Mercado *
                        input#costotm.number(type="text", name="costotm" v-model="costotm" v-masknumberdc)
                        -->
                        <div class="col-12 col-sm p-1">
                          <label>Costo Unit. Encisa *</label>
                          <input class="number" id="costoue" type="text" name="costoue" v-model="costoue" v-masknumberdc>
                        </div>
                        <!--.col-12.col-sm.p-1
                        label Costo Total Encisa *
                        input#costote.number(type="text", name="costote" v-model="costote" v-masknumberdc)
                        -->
                        <div class="w-100"></div>
                        <div class="col-auto p-0 mt-2 ml-auto">
                          <input class="b-style-color1" id="btnBack" type="button" value="Volver" onclick="js('#tabListpaquetetec').click(),js('#buttonNew').removeAttribute('style')">
                        </div>
                        <div class="col-auto px-1 mt-2">
                          <input class="b-style-cancel" id="btnClean" type="button" value="Cancelar">
                        </div>
                        <div class="col-auto p-0 mt-2">
                          <input class="b-style-color3 load" id="btnRegPaquete" type="submit" value="Agregar">
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </main>
        <div class="button-primary-new">
          <button class="flaticon-anadir" id="buttonNew" type="button" disabled onclick="newFormPaquete()"></button>
        </div>
        <div class="row m-0 position-fixed h-100" id="boxNew">
          <div class="align-self-end" id="boxButtons">
            <div class="item-button">
              <label>Intención de siembra</label><a class="flaticon-escritura" href="./intencion"></a>
            </div>
            <!--.item-button
            label Financiamiento
            a.flaticon-tractor(href="./financiamiento")
            -->
            <div class="item-button">
              <label>Nuevo Paquete tecnológico</label><a class="flaticon-agricultor" href="javascript:void(0)" onclick="main.hideBoxNew(), js('#tabNewpaquetetec').click()"></a>
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
  <script src="js/class.paquetetec.js"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    main.boxNew();
    
    let arrclasif=[];
    let arrdesc=[];
    
    let ciclo = jsonCiclos.ciclo_actual;
    
    let fbuscar = js("#fbuscar");
    let entidad = js("#entidad");
    //let lproductor = js("#lproductor");
    let rubros = js("#rubros");
    let form = js("#fpaquete");
    let rubro = js("#rubros");
    let desc = js("#desc");
    let clasificacion = js("#clasificacion");
    let undmedida = js("#undmedida");
    let cantidad = js("#cantidad");
    let costoum = js("#costoum");
    //let costotm = js("#costotm");
    let costoue = js("#costoue");
    //let costote = js("#costote");
    //let bGuardar = js("#btnRegPaquete");
    let bLimpiar = js("#btnClean");
    
    let upPaqueteTec = 0;
    
    var blockElement = [rubro]; //elementos a ser bloqueados
    
    var paquetetec = new PaqueteTec(
    	entidad,
    	rubros,
    	desc,
    	clasificacion,
    	undmedida,
    	cantidad,
    	costoum,
    	costoue,
    	form
    );
    
    
    IMask(cantidad,{mask:EXPnumberDecimal});
    IMask(costoum,{mask:EXPnumberDecimal});
    //IMask(costotm,{mask:EXPnumberDecimal});
    IMask(costoue,{mask:EXPnumberDecimal});
    //IMask(costote,{mask:EXPnumberDecimal});
    
    fbuscar.addEventListener("submit",function(e){
    	e.preventDefault();
    	paquetetec.searchPaquete(this);
    });
    
    
    function newFormPaquete(){
    	let valid = main.valid(fbuscar);
    	if(!valid[0]) {
    		swal("¡Error!", valid[2], "error",{button:{
    			text: "Aceptar",
    			className:"errorSweetAlert",
    			closeModal:true
    		}}).then(function(){
    			valid[1].focus();
    			bNewPaquete.setAttribute("disabled","disabled");
    		});
    	}		
    }
    
    paquetetec.loadInputSearch();
    
    /*for(let i in clasificacion.data){
    	arrclasif.push([
    		clasificacion.data[i].clasificacion
    	]);
    }
    main.inputsearch(clasificacion,arrclasif);
    
    //cargar lista descripcion
    var arrdescripcion=[];
    for(let i in desc.data){
    	arrdescripcion.push([
    		desc.data[i].descripcion			
    	]);
    }
    main.inputsearch(desc,arrdescripcion);*/
    
    //enviar datos para registrar el paquete		
    form.addEventListener('submit',function(e){
    	e.preventDefault();
    	paquetetec.saveNewPaqueteTec();
    });
    
    bLimpiar.addEventListener("click",function(e){
    	paquetetec.cleanForm();
    })
    
    let bBack = js("#btnBack");
    bBack.addEventListener("click",function(){
    	js("#tabListpaquetetec").click();
    });
  </script>
</html>