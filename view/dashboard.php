<?php

	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include("../controller/mainscript.php");
	require_once("../controller/class.intencion.php");
	require_once("../controller/class.productores.php");
	require_once("../controller/class.entidad.php");
require_once("../controller/class.direction.php");
	$nUNDproduccion = count($undproduccion["data"]);
	if($_SESSION["nivel"]=='PRODUCTOR'){
		if($nUNDproduccion<1) {
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
    <link rel="stylesheet" href="css/dashboard.css?v=100">
    <script>
      let jsonCiclos = JSON.parse(`<?php echo $total_ciclos; ?>`);
      var ssdata = JSON.parse(`<?php echo $session_data; ?>`);
      
    </script>
  </head>
  <body class="h-100 loading">
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
              <a href="../controller/logout.session.php">
                <div class="menu-items">
                  <span>Cerrar Sesión</span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </header>
      <div class="main-content row h-100">
        <div class="barra_lateral col-12 col-md-auto">
          <nav>
            <div class="leyenda">Principal / </div>
            <ul>
              <li><a class="flaticon-salpicadero" id="mnuprincipal" href="./dashboard">Principal</a></li>
              <li><a class="flaticon-escritura" href="./archivos">Archivos</a></li>
              <li><a class="flaticon-tractor" href="./operaciones">Operaciones</a></li>
              <!--lia.flaticon-portafolios-con-lapiz(href="./seguimiento" + formato_archivos) Seguimiento
              -->
              <!--lia.flaticon-numero-de-seguimiento(href="./preguiado" + formato_archivos) Pre-guiado
              -->
              <!--lia.flaticon-mantenimiento(href="./mantenimiento" + formato_archivos) Mantenimiento
              -->
              <!--lia.flaticon-boton-de-ayuda-bocadillo-con-signo-de-interrogacion(href="./ayuda" + formato_archivos) Ayuda
              -->
              <li><a class="flaticon-logout" id="logout" href="../controller/logout.session.php">Cerrar Sesión</a></li>
            </ul>
          </nav>
        </div>
        <main class="contenido col">
          <div class="row m-0">
            <section class="col-12" id="migasdepan">
              <div class="leyenda"><span> <b>Principal</b></span></div>
            </section>
            <section class="col-12">
              <div class="header-resumen row justify-content-start">
                <?php if($show>0){
                	if($show>1){ ?>
                <div class="card-resumen col-6 col-sm" id="card-entidad">
                  <div class="card"><i class="flaticon-pagar"></i>
                    <h4><?php echo number_format(count($entidad["data"]),0,"","."); ?></h4><span class="text-collapsed">Entes Financieros</span>
                  </div>
                </div><?php } ?>
                <div class="card-resumen col-6 col-sm" id="card-productores">
                  <div class="card"><i class="flaticon-agricultor"></i>
                    <h4><?php echo number_format(count($list_productores["data"]),0,"","."); ?></h4><span class="text-collapsed">Productores</span>
                  </div>
                </div><?php } ?>
                <div class="card-resumen col-6 col-sm" id="card-undprod">
                  <div class="card"><i class="flaticon-siembra"></i>
                    <h4><?php echo number_format(count($undproduccion["data"]),0,"","."); ?></h4><span class="text-collapsed">Unidades de Producción</span>
                  </div>
                </div>
                <div class="card-resumen col-6 col-sm" id="card-haproductivas">
                  <div class="card"><i class="flaticon-terreno"></i><?php
                    	$totalha = 0;
                    	for($i=0; $i < count($undproduccion["data"]); $i++){ 
                    		$totalha = $totalha + $undproduccion["data"][$i]["haproductivas"];
                    	}
                    ?>
                    <h4><?php echo number_format($totalha,2,",","."); ?></h4><span class="text-collapsed">Hect. Productivas</span>
                  </div>
                </div>
                <div class="card-resumen col-6 col-sm" id="card-haintencion">
                  <div class="card"><i class="flaticon-terreno"></i><?php
                    	$totalhaI = $resumenHAtotales[0]["haintencion"];
                    ?>
                    <h4><?php echo number_format($totalhaI,2,",","."); ?></h4><span class="text-collapsed">Hect. de Intención</span>
                  </div>
                </div>
                <div class="card-resumen col-6 col-sm" id="card-hafinanciadas">
                  <div class="card"><i class="flaticon-terreno"></i><?php
                    	$totalhaF = $resumenHAtotales[0]["hafinanciadas"];
                    ?>
                    <h4><?php echo number_format($totalhaF,2,",","."); ?></h4><span class="text-collapsed">Hect. Financiadas</span>
                  </div>
                </div>
                <div class="card-resumen col-6 col-sm" id="card-hasembradas">
                  <div class="card"><i class="flaticon-terreno"></i><?php
                    	$totalhaS = $resumenHAtotales[0]["hasembradas"];
                    ?>
                    <h4><?php echo number_format($totalhaS,2,",","."); ?></h4><span class="text-collapsed">Hect. Sembradas</span>
                  </div>
                </div>
                <div class="card-resumen col-6 col-sm" id="card-hacosechadas">
                  <div class="card"><i class="flaticon-terreno"></i><?php
                    	$totalhaC = $resumenHAtotales[0]["hacosechadas"];
                    ?>
                    <h4><?php echo number_format($totalhaC,2,",","."); ?></h4><span class="text-collapsed">Hect. Cosechadas</span>
                  </div>
                </div>
                <div class="card-resumen col-6 col-sm" id="card-haperdidas">
                  <div class="card"><i class="flaticon-terreno"></i><?php
                    	$totalhaP = $resumenHAtotales[0]["haperdidas"];
                    ?>
                    <h4><?php echo number_format($totalhaP,2,",","."); ?></h4><span class="text-collapsed">Hect. Perdidas</span>
                  </div>
                </div>
              </div>
            </section>
            <section class="col-12 col-xl" id="resumen-intencion">
              <div class="card">
                <div class="row justify-content-start m-0">
                  <div class="col-12 p-0">
                    <h3>Resumen de Intención de siembra</h3>
                  </div>
                  <div class="col-12 p-0 mt-2">
                    <form id="form-resumen" name="formbuscar" action="" method="post" autocomplete="off">
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
                          <!--input#lsector(type="text" name='sector' placeholder='Sector' data-twovalue='')-->
                        </div>
                        <div class="w-100"></div>
                        <div class="col-12 col-sm p-1">
                          <select class="custom-select" id="lentidad" name="entidad">
                            <option value="">Entidad</option>
                          </select>
                          <!--.autocompleteinput#lentidad(type="text" placeholder='Entidad' name='entidad' data-twovalue='')
                          -->
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
                  <div class="col-12 p-0">
                    <div id="listadoIntencion">
                      <div class="table-responsive">
                        <table class="table table-sm" id="table">
                          <thead>
                            <tr>
                              <th>Rubros</th>
                              <th class="align-right">H. Intención</th>
                              <th class="align-right">H. Financiadas</th>
                              <th class="align-right">H. Sembradas</th>
                              <th class="align-right">H. Cosechadas</th>
                              <th class="align-right">H. Perdidas</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
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
  <script src="js/class.intencion.js?v=100"></script>
  <script>
    var main = new Main();
    var dashboard = new Dashboard();
    dashboard.getCicle(jsonCiclos);
    
    var ciclo = jsonCiclos.ciclo_actual;
    
    var lentidad = js("#lentidad");
    var lproductor = js("#lproductor");
    var lestado = js("#lestado");
    var lmunicipio = js("#lmunicipio");
    var lparroquia = js("#lparroquia");
    var lsector = js("#lsector");
    var lrubro = js("#lrubro");
    
    let intencion = new IntencionModule(
    ciclo,
    lrubro,
    lestado,
    lmunicipio,
    lparroquia,
    lsector,
    lentidad,
    lproductor
    );
    
    intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    intencion.getProductor(lentidad.value,lproductor);
    //intencion.getIntencion();
    
    //llenar select de municipio y parroquia//////////////////////////////
    lestado.addEventListener("change",function(){
    	main.getMunicipio(this.value,lmunicipio,'');
    	main.getParroquia(999999,lparroquia,'');
    	main.getSelectSector(999999,lsector,'');
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	intencion.getProductor(lentidad.value,lproductor);
    });
    lmunicipio.addEventListener("change",function(){
    	main.getParroquia(this.value,lparroquia,'');
    	main.getSelectSector(999999,lsector,'');
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	intencion.getProductor(lentidad.value,lproductor);
    });
    lparroquia.addEventListener("change",function(){
    	main.getSelectSector(this.value,lsector,'');
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	intencion.getProductor(lentidad.value,lproductor);
    });
    lsector.addEventListener("change",function(){
    	intencion.getLentidad(lestado.value, lmunicipio.value, lparroquia.value, lsector.value,lentidad,'','');
    	intencion.getProductor(lentidad.value,lproductor);
    });
    lentidad.addEventListener("change",function(){
    	intencion.getProductor(lentidad.value,lproductor);
    });
    
    var dashboard = new Dashboard(lentidad, lproductor, lestado, lmunicipio, lparroquia, lsector, lrubro,ciclo);
    dashboard.getIntencion();
    
    js("#form-resumen").addEventListener("submit",function(e){
    	e.preventDefault();
    	
    	dashboard.getIntencion();
    
    });
    
    js("body").classList.remove("loading")
  </script>
</html>