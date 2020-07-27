<?php
	include("../controller/mainscript.php");
	require_once("../controller/class.productores.php");
	require_once("../controller/class.entidad.php");
require_once("../controller/class.direction.php");
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	//obtener listado de productores
$productores = new Productores();
	$list_productores = $productores->buscar();
	$JSONproductores = json_encode($list_productores,JSON_FORCE_OBJECT);

	//obtener listado de entidades
	$JSONentidad = json_encode("");
	if($_SESSION['nivel']=='MUNICIPAL' || $_SESSION['nivel']=='ADMINISTRADOR'){
		$entidadfinanciera = new Entidad();
		$listentidad = $entidadfinanciera->buscar();
		$JSONentidad = json_encode($listentidad,JSON_FORCE_OBJECT);
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
    <link rel="stylesheet" href="css/intencion.css?v=1">
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
            <div class="leyenda"><a href="./dashboard">Principal</a><span> / </span><a href="./operaciones">Operaciones</a><span> / intención de Siembra</span></div>
            <ul>
              <li><a class="flaticon-salpicadero" id="mnuprincipal" href="./dashboard">Principal</a></li>
              <!--li: a.flaticon-escritura(href="#") Ciclo de cosecha-->
              <li><a class="flaticon-escritura" id="intencionsiembra" href="#">Intención de Siembra</a></li><?php if ($show > 0) echo '<li><a class="flaticon-tractor" href="javascript:void(0)">Financiamiento</a></li>'; ?>
              <li><a class="flaticon-logout" id="logout" href="../controller/logout.session.php">Cerrar Sesión</a></li>
            </ul>
          </nav>
        </div>
        <main class="contenido col position-relative" id="main">
          <div class="row m-0">
            <section class="col-12" id="migasdepan">
              <div class="leyenda"><a href="./dashboard">Principal</a><span> > </span><a href="./operaciones">Operaciones</a><span>
                   > <b>intención de Siembra</b></span></div>
            </section>
            <section class="col-12 col-lg">
              <div class="card" id="cont-intencion">
                <div class="row m-0 position-relative">
                  <div class="col p-0" id="listIntencionBox">
                    <div class="row m-0">
                      <div class="col p-0">
                        <h3 class="flaticon-agricultor">Intención de Siembra</h3>
                      </div>
                      <div class="col-12 p-0 mt-2">
                        <form id="form-listado-intencion" name="formbuscar" action="" method="post" autocomplete="off">
                          <div class="row m-0">
                            <div class="col-12 col-sm p-1">
                              <select class="custom-select" id="lestado" name="estado">
                                <option value="">Estado</option><?php 
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
                            <div class="col-12 col-sm p-1">
                              <select class="custom-select" id="lmunicipio" name="municipio">
                                <option value="">Municipio</option>
                              </select>
                            </div>
                            <div class="col-12 col-sm p-1">
                              <select class="custom-select" id="lparroquia" name="parroquia">
                                <option value="">Parroquia		</option>
                              </select>
                            </div>
                            <div class="col-12 col-sm p-1 autocomplete">
                              <select class="custom-select" id="lsector" name="sector">
                                <option value="">Sector</option>
                              </select>
                            </div>
                            <div class="w-100"></div>
                            <div class="col-12 col-sm p-1">
                              <select class="custom-select" id="lentidad" name="entidad">
                                <option value="">Entidad</option>
                              </select>
                            </div><?php
                            	if($_SESSION["nivel"]=='PRODUCTOR'){ ?>
                            <input id="lproductor" type="hidden" name="productor" data-twovalue=""><?php }else{ ?>
                            <div class="col-12 col-sm p-1">
                              <div class="autocomplete">
                                <input id="lproductor" type="text" placeholder="Productor" name="productor" data-twovalue="">
                              </div>
                            </div><?php } ?>
                            
                            <div class="col-12 col-sm p-1">
                              <select class="custom-select" id="lrubro" name="rubro">
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
                            <div class="col-auto p-1 align-self-center">
                              <input class="b-style-color2" id="buscar" type="submit" value="Buscar">
                            </div>
                          </div>
                        </form>
                      </div>
                      <!--.col-12.p-0.mt-2
                      .row.m-0
                      	.col-auto.p-1
                      		input#newIntention.b-style-color3(type='button' value='Nueva intención')
                      -->
                      <div class="col-12 p-0 mt-2">
                        <div id="listadoIntencion">
                          <div class="table-responsive">
                            <table class="table table-sm" id="table">
                              <thead>
                                <tr>
                                  <th>Entidad</th>
                                  <th>Productor</th>
                                  <th>Rubro</th>
                                  <th class="align-right">H. Intención</th>
                                </tr>
                              </thead>
                              <tbody></tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </main>
        <div class="button-primary-new"><a class="flaticon-anadir" id="buttonNew" href="javascript:void(0)"></a></div>
        <div class="row m-0 position-fixed h-100" id="boxNew">
          <div class="align-self-end" id="boxButtons">
            <!--.item-button
            label Intención de siembra
            a.flaticon-escritura(href="./intencion")
            -->
            <div class="item-button">
              <label>Financiamiento</label><a class="flaticon-tractor" href="./unidad_produccion"></a>
            </div>
            <div class="item-button">
              <label>Nueva intención</label><a class="flaticon-escritura" href="javascript:void(0)" onclick="newIntencionForm()"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 p-0 p-sm-3" id="newIntencionBox">
      <div class="row m-0" id="headerNewIntencion">
        <div class="col-auto p-1 align-self-center position-sm-absolute" id="boxButtonAtras">
          <button class="button-primary" id="atras">Atras</button>
        </div>
        <div class="col col-sm-auto p-1 align-self-center">
          <h5>Nueva Intención</h5>
        </div>
      </div>
      <form class="forms form-module" id="fintencion" action="#" method="post" autocomplete="off">
        <div class="row m-0 pt-3">
          <div class="group-input col-12 col-sm autocomplete p-1">
            <label class="custom-label" for="entidad">Entidad *</label>
            <input id="entidad" type="text" name="entidad" data-twovalue="%">
          </div>
          <div class="group-input col-12 col-sm autocomplete p-1">
            <label class="custom-label" for="productor">Productor *</label>
            <input id="productor" type="text" name="productor" data-twovalue="%">
          </div>
          <div class="group-select col-12 col-sm p-1">
            <label class="custom-label" for="undproduccion">Unidad de producción *</label>
            <select class="custom-select" id="undproduccion" name="undproduccion">
              <option value="">Seleccione una opción</option>
            </select>
          </div>
          <div class="w-100"></div>
          <div class="group-select col-12 col-sm p-1">
            <label class="custom-label" for="rubros">Rubros *</label>
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
          <div class="group-input col-12 col-sm-3 p-1">
            <label class="custom-label" for="haintencion">Hectareas *</label>
            <input class="number" id="haintencion" type="text" name="haintencion">
          </div>
          <div class="group-select col-12 col-sm p-1">
            <label class="custom-label" for="ndoctecnico">Tecnico de campo</label>
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
          <div class="w-100"></div>
          <div class="group-input col-auto p-1" id="btnFinalizarBox">
            <input class="b-style-color3" id="btnFinalizar" type="submit" value="Agregar">
          </div>
        </div>
      </form>
    </div>
    <!--fin de formulario nueva intencion			-->
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
  <script src="js/class.intencion.js?v=4"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    main.boxNew();
    
    let n = `<?php echo $_SESSION["nivel"]; ?>`;
    
    
    //id de elementos
    let ciclo = jsonCiclos.ciclo_actual;
    
    var lentidad = js("#form-listado-intencion #lentidad");
    var lproductor = js("#form-listado-intencion #lproductor");
    var lestado = js("#form-listado-intencion #lestado");
    var lmunicipio = js("#form-listado-intencion #lmunicipio");
    var lparroquia = js("#form-listado-intencion #lparroquia");
    var lsector = js("#form-listado-intencion #lsector");
    var lrubro = js("#form-listado-intencion #lrubro");
    
    var entidad = js("#fintencion #entidad");
    var productor = js("#fintencion #productor");
    var undproduccion = js("#fintencion #undproduccion");
    var rubros = js("#fintencion #rubros");
    var haintencion = js("#fintencion #haintencion");
    var ndoctecnico = js("#fintencion #ndoctecnico");
    
    var docentidad = '';
    
    IMask(haintencion,{mask:EXPnumberDecimal});
    
    let intencion = new IntencionSiembra(
    ciclo,
    lrubro,
    lestado,
    lmunicipio,
    lparroquia,
    lsector,
    lentidad,
    lproductor,
    entidad,
    productor,
    undproduccion,
    rubros,
    haintencion,
    ndoctecnico
    );
    
    intencion.validNivel();
    
    intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,docentidad,function(){
    	intencion.getProductor(lentidad.value,lproductor);
    });
    
    intencion.getIntencion();
    
    //llenar select de municipio y parroquia//////////////////////////////
    lestado.addEventListener("change",function(){
    	main.getMunicipio(this.value,lmunicipio,'');
    	main.getParroquia(999999,lparroquia,'');
    	main.getSelectSector(999999,lsector,'');
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad);
    });
    lmunicipio.addEventListener("change",function(){
    	main.getParroquia(this.value,lparroquia,'');
    	main.getSelectSector(999999,lsector,'');
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	//intencion.getProductor(lentidad.value,lproductor);
    });
    lparroquia.addEventListener("change",function(){
    	//console.log(lparroquia)
    	//lsector.value = '';
    	//lsector.dataset.twovalue = '';
    	main.getSelectSector(this.value,lsector,'');
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	//intencion.getProductor(lentidad.value,lproductor);
    });
    lsector.addEventListener("change",function(){
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	//intencion.getProductor(lentidad.value,lproductor);
    });
    /*lsector.addEventListener("blur",function(){
    	intencion.getLentidad(lestado.value,lmunicipio.value,lparroquia.value,this.dataset.twovalue,lentidad,'');
    });*/
    lentidad.addEventListener("change",function(){
    	//console.log("sss")
    	intencion.getProductor(lentidad.value,lproductor);
    });
    
    //buscar intencion de siembra/////////////////////////////////////////
    js("#form-listado-intencion").addEventListener("submit",function(e){
    	e.preventDefault();
    	intencion.getIntencion();
    });
    
    //SCRIPT PARA NUEVA SECCION DE NUEVA INTENCION///////////////////
    
    //mostrar ventada de nueva intencion
    /*js("#listIntencionBox #newIntention").addEventListener("click",function(){
    	js("#newIntencionBox").style.top = "0";
    });*/
    function newIntencionForm(){
    	main.hideBoxNew();
    	js("#newIntencionBox").style.top = "0";
    }
    
    js("#newIntencionBox button#atras").addEventListener("click",function(){
    	js("#newIntencionBox").style.top = "110%";
    	js('#buttonNew').removeAttribute('style');
    });
    
    //cargar listado de entidades////////////////////////////////////////
    var entidades = JSON.parse(`<?php echo $JSONentidad; ?>`);
    if(typeof entidades == "object"){
    	var arrEntidades=[];
    	for(let i in entidades.data){
    		arrEntidades.push([
    			entidades.data[i].rif,
    			entidades.data[i].razonsocial			
    		]);
    	}
    	main.inputsearch(entidad,arrEntidades);
    }else{
    	entidad.parentElement.setAttribute("style","display:none;")
    	//entidad.setAttribute("disabled","disabled");
    	entidad.dataset.twovalue=ssdata.entidad;
    	intencion.getProductor(entidad.dataset.twovalue,productor);
    }
    
    //obtener productores
    entidad.addEventListener("blur",function(){
    	let value = '';
    	if(ssdata.nivel==='PRODUCTOR') value=ssdata.cirif;
    	intencion.getProductor(this.dataset.twovalue,productor,value);
    });
    
    //obtener unidades de produccion
    productor.addEventListener("blur",function(){
    	let value = '';
    	if(ssdata.nivel==='PRODUCTOR') value=ssdata.cirif;
    	intencion.getUNDproduccion(this.dataset.twovalue,undproduccion);
    });
    
    js("#fintencion").addEventListener("submit",function(e){
    	e.preventDefault();
    	intencion.newIntencion();
    });
    
    //- docintencion.value = '<?php echo $DOCintencion;?>';
    
    //- let f = new Date();
    //- let d = f.getDate();
    //- let m = f.getMonth()+1;
    //- let y = f.getFullYear();
    //- fechaintencion.value = `${y}-${m}-${d}`;
    //- let fechaactual = document.querySelector("#fechaactual");
    //- fechaactual.innerText = `Fecha: ${d}/${m}/${y}`;
    
    //- let intencion = new IntencionModule(
    //- ciclo,
    //- formbuscar,
    //- formintencion,
    //- docproductor,
    //- undproduccion,
    //- codfichapredial,
    //- tenencia,
    //- destenencia,
    //- rubros,
    //- haintencion,
    //- tecnico,
    //- );
    
    //- window.addEventListener("load",function(e){
    //- 	intencion.getMunicipios();
    //- 	intencion.getRubros();
    //- 	intencion.getIntencion();
    
    //- 	if(n !== 'PRODUCTOR'){
    //- 		intencion.getEntidad();
    //- 		intencion.getProductor();
    //- 	}
    //- })
    //- formbuscar.addEventListener("submit",function(e){
    //- 	e.preventDefault();
    //- 	intencion.getIntencion();
    //- });
    
    //- //botton mostrar detalle de tabla
    //- $(document).on("click","#options #mas",function(){
    //- 	let button = $(this);
    //- 	tr = button.parents("tr");
    //- 	let docint = tr.children("td").eq(1).text();
    	
    //- 	$(`.detalletabla[data-intencion=${docint}]`).slideToggle("fast",function(){
    //- 		button.toggleClass("show");
    //- 	});
    //- 	//if(button.text()=='-') {button.text("+"); return};
    //- 	//button.text("-");
    //- });
    //- //fin de boton mostrar
    
    //- //boton agregar mas registros a intencion existente
    //- $(document).on("click", "#options #add",function(){
    //- 		let button = $(this);
    //- 		let docint = button.data("intencion");
    
    //- 		$("#new").animate({
    //- 			left:0
    //- 		});
    
    //- 		docintencion.value = docint;
    //- 		let spanDocintencion = document.querySelector("#nrointencion");
    //- 		spanDocintencion.innerText = "Intención Nº: " + docintencion.value
    		
    //- 		let doc = ssdata.cirif;
    
    //- 		intencion.getNewProductor(doc);
    		
    //- 		intencion.getNewRubros();
    //- 		intencion.getTecnico();
    //- 		let ent = ssdata.entidad || '%';
    //- 		if(n == "ADMINISTRADOR") intencion.getNewEntidad(ent);
    
    //- });
    
    //- //nuevo registro
    //- let newInt = document.querySelector("#nuevaintencion");
    
    //- newInt.addEventListener("click",function(){
    //- 	$("#new").animate({
    //- 		left:0
    //- 	});
    
    //- 	let spanDocintencion = document.querySelector("#nrointencion");
    //- 	spanDocintencion.innerText = "Intención Nº: " + docintencion.value
    //- 	let doc = ssdata.cirif;
    
    //- 	intencion.getNewProductor(doc);
    	
    //- 	intencion.getNewRubros();
    //- 	intencion.getTecnico();
    //- 	let ent = ssdata.entidad || '%';
    //- 	if(n == "ADMINISTRADOR") intencion.getNewEntidad(ent);
    //- });
    
    //- let cancelNewInt = document.querySelector("#closeNewIntencion");
    //- cancelNewInt.addEventListener("click",function(){
    //- 	$("#new").animate({
    //- 		left:'100%'
    //- 	});
    
    //- 	docintencion.value = '<?php echo $DOCintencion;?>';
    
    //- })
    
    //- newentidad.addEventListener("change",function(){
    //- 	let desentidad = document.querySelector("#desentidad-desc");
    
    //- 	let i = this.selectedIndex;
    //- 	desentidad.value = this.options[i].innerText;
    	
    //- });
    
    //- undproduccion.addEventListener("change",function(){
    //- 	let i = this.selectedIndex;
    
    
    //- 	propietario.innerText = '';
    //- 	if(this.value=='OTHER'){
    //- 		intencion.codfichapredial.value="";
    //- 		intencion.codfichapredial.attributes["data-nombreund"].value = "";
    //- 		intencion.tenencia.value="";
    
    //- 		$("#otraundproduccion").fadeIn("fast");
    //- 	}else{
    //- 		$("#otraundproduccion").fadeOut("fast");
    //- 		intencion.codfichapredial.value=this.value;
    //- 		intencion.codfichapredial.attributes["data-nombreund"].value = this.options[i].innerText;
    //- 		intencion.tenencia.value=this.options[i].dataset.tenencia;
    //- 	}
    //- });
    
    //- docproductor.addEventListener("blur",function(){
    //- 	intencion.getUNDproduccion(this.value);
    //- });
    
    //- codfichapredial.addEventListener("blur",function(){
    //- 	let f = new FormData();
    //- 	let url = '../controller/class.undproduccion.php';
    //- 	f.append("busqueda",this.value);
    //- 	f.append("method",1);
    
    //- 	let axios = pmain.axios('',url,f);
    //- 	axios.then(function(response){
    //- 		let resp = response.data;
    //- 		let estado = resp.estado;
    //- 		let descripcion = resp.descripcion;
    //- 		let datos = resp.data;
    
    //- 		let propietario = document.querySelector("#propietario");
    //- 		propietario.innerText = "";
    //- 		codfichapredial.dataset.nombreund = '';
    //- 		if(estado){
    //- 			codfichapredial.dataset.nombreund = datos[0].razonsocial;
    //- 			propietario.innerText = datos[0].razonsocial;
    //- 		}
    
    //- 	})
    //- })
    
    //- IMask(haintencion,{mask:EXPnumberDecimal});
    
    //- let bAdd = document.querySelector("#addList");
    //- bAdd.addEventListener("click",function(){
    //- 	intencion.addIntencion();
    //- });
    
    //- //let datosDetalle;
    //- let documento = $(document);
    //- var tr='';
    
    //- documento.on("click","#delete",function(){
    //- 	//$("#deleteIntencion")
    //- 	tr = $(this).parents("tr");
    //- 	$(".modal-title").text("Eliminar Intención de Siembra");
    //- 	$(".modal-body p").text("Si eliminas la intención de siembra no podras revertir los cambios ¿Estas seguro de continuar?");
    //- })
    
    //- let deleteIntencion = $("#aceptar");
    
    //- deleteIntencion.on("click",function(){
    	
    //- 	let docintencion = tr.children('td').eq(0).text();
    //- 	let docproductor = tr.children('td').eq(3).text();
    //- 	let docrubro = tr.children('td').eq(4).text();
    //- 	let docfichapredial = tr.children('td').eq(5).text();
    
    //- 	intencion.deleteIntencion(
    //- 		docintencion,
    //- 		docproductor,
    //- 		docrubro,
    //- 		docfichapredial
    //- 	);
    //- });
    
    //- documento.on("click","#removeIntencion",function(){
    //- 	let padre = $(this).parents('.mTr');
    //- 	let index = padre.index();
    //- 	padre.remove()
    //- })
    
    
    //- let bGuardar = document.querySelector("#bguardar");
    //- bGuardar.addEventListener("click",function(){
    //- 	intencion.guardarIntencion();
    //- })
  </script>
</html>