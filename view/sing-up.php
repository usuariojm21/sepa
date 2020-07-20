
<?php

	//error_reporting(E_ERROR | E_WARNING | E_PARSE);
	session_start();
	require_once("../model/class.conexion.php");
	require_once("../model/class.querys.php");
	require_once("../controller/methods.php");
	//require_once("../controller/class.direction.php");
	if (isset($_SESSION["sepa_login"]) && $_SESSION["sepa_login"]===true) {
		header("location: ./dashboard");
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
    <link rel="stylesheet" href="css/users.css">
    <script></script>
  </head>
  <body class="h-100">
    <div class="container h-100">
      <div class="row justify-content-center mt-10">
        <div class="col-auto"><a class="logotipo" href="./">
            <picture><img src="./img/logotipo-color.svg" alt=""></picture></a></div>
      </div>
      <div class="row justify-content-center">
        <section class="col-12 col-md-8 align-self-center" id="singup">
          <h2>Crea una nueva cuenta</h2>
          <form class="form-module" id="fusers" autocomplete="off">
            <div class="row m-0">
              <div class="col-12 p-1 position-relative">
                <!--#loadingi.flaticon-cargar
                -->
                <input class="form-control v-rif" id="cirif" type="text" name="cirif" placeholder="CI/Rif (Ej. V87654321)">
              </div>
              <div class="col-12 p-1">
                <input class="form-control" id="rsocial" type="text" name="rsocial" placeholder="Razón Social" maxlength="100">
              </div>
              <div class="col-12 p-1">
                <input class="form-control" id="correo" type="email" name="correo" placeholder="Correo Electrónico" maxlength="">
              </div>
              <div class="col-12 p-1">
                <input class="form-control" id="representante" type="text" name="nombrepresentante" placeholder="Representante Legal">
              </div>
              <div class="col-12 p-1">
                <input class="form-control large-control" id="dfiscal" type="text" name="dfiscal" placeholder="Dirección Fiscal" maxlength="255">
              </div>
              <div class="col-12 p-1">
                <input class="form-control" id="nuser" type="text" name="usuario" placeholder="Usuario">
              </div>
              <div class="col-6 p-1">
                <input class="form-control" id="pass" type="password" name="clave" placeholder="Contraseña">
              </div>
              <div class="col-6 p-1">
                <input class="form-control" id="repeatpass" type="password" placeholder="Confirmar contraseña">
              </div>
              <div class="col-12 p-1">
                <input class="button-primary" id="regusers" type="button" value="Crear Cuenta">
              </div>
            </div>
          </form>
        </section>
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
  <!--script(src="js/imask.js")-->
  <script src="js/class.users.js"></script>
  <script>
    var main = new Main();
    
    //let entAutoFinan = 'A000000001';
    
    let forms = document.querySelector('#fusers');
    let cirif = document.getElementById("cirif");
    let representante = document.getElementById("representante");
    let nuser = document.getElementById("nuser");
    let pass = document.getElementById("pass");
    let repeatpass = document.getElementById("repeatpass");
    let rsocial = document.getElementById("rsocial");
    let correo = document.getElementById("correo");
    let dfiscal = document.getElementById("dfiscal");
    let regusers = document.getElementById("regusers");
    
    
    let blockElement = [];
    
    IMask(cirif,{mask:EXPrifMask});
    
    let users = new Users(
    	//entAutoFinan,
    	cirif,
    	representante,
    	nuser,
    	pass,
    	repeatpass,
    	rsocial,
    	correo,
    	dfiscal
    );
    
    //events
    /*window.addEventListener("load",function(){
    	pmain.obtenerEstados(estados);
    });*/
    
    
    cirif.addEventListener("keypress",function(e){
    	let keycode = (event.keyCode ? event.keyCode : event.which);
    });
    
    cirif.addEventListener("blur",function(){
    	users.buscar_productor(this);
    })
    
    pass.addEventListener("keyup",function(){
    	repeatpass.placeholder=this.value
    	repeatpass.placeholder = repeatpass.placeholder || "Confirmar Contraseña" 
    })
    
    regusers.addEventListener("click",function(){
    	users.validar();
    });
    
    /*var element = cirif;
    var maskOptions = {
      mask: '[VG]'
    };
    var mask = IMask(cirif, {
    	mask: /^([VEJPG]{1})([0-9]{7,9})$/
    });*/
  </script>
</html>