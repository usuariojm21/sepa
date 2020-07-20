
<?php
	session_start();
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
    <link rel="stylesheet" href="css/style.css?v=1">
    <script></script>
  </head>
  <body class="h-100">
    <div class="container h-100">
      <div class="row m-0 h-100">
        <div class="col p-0 pt-md-5 align-self-center align-self-md-start">
          <div class="row justify-content-center align-items-center align-items-start-md pt-1 pt-sm-5 pt-md-0" id="home">
            <section class="col-12 col-md-6 align-self-center" id="header">
              <!--.logo: h1 Səpa-->
              <div class="logo"><a href="./"><img src="./img/logotipo-light.svg" alt=""></a></div>
              <div class="title">Sistema <span>Estadistico </span>de <span>Producción Agricola</span></div>
              <!--button#entrar Entrar-->
            </section>
            <section class="col-12 col-md-6 align-self-start mt-3 align-self-md-center d-flex justify-content-center justify-content-md-end" id="login">
              <div class="form-content">
                <h3>Entrar a la plataforma administrativa</h3>
                <div class="alert alert-success" id="alertForm" role="alert"><span class="msj-alert"></span></div>
                <form id="flogin" action="" name="login" method="post" autocomplete="off">
                  <input class="iptUsuario" id="usuario" type="text" name="usuario" placeholder="Usuario">
                  <input class="iptUsuario" id="clave" type="password" name="clave" placeholder="Contraseña">
                  <input class="b-style-color3" id="btnlogin" type="submit" name="bentrar" value="Entrar">
                  <!--p.btnreg Si aun no tiene una cuenta a(href="./sing-up") Registrarse
                  -->
                </form>
                <div class="sepforzado" style="clear:both;"></div>
                <!--p#errorlogin.alert.alert-danger Usuario o contraceña incorrecto-->
              </div>
            </section>
            <section class="piedepagina col-12">
              <!--a#logogobierno(href="#"): img(src="img/logotipo-gobierno-light.svg", alt="Gobierno Bolivariano de Portuguesa")-->
            </section>
          </div>
          <div class="footer row">
            <footer></footer>
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
  <script>
    var main = new Main();
    //dashboard.sesion();
    let form = document.querySelector("#flogin");
    let usuario = document.querySelector("#usuario");
    let clave = document.querySelector("#clave");
    let bEntrar = document.querySelector("#btnlogin");
    
    let login = new Login(
    	usuario,
    	clave,
    	bEntrar,
    	form
    )
    
    usuario.focus();
    
    form.addEventListener("submit",function(e){
    	e.preventDefault();
    
    	login.guardar();
    
    })
  </script>
</html>