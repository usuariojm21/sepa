
<?php
	session_start();

	if (isset($_SESSION["sepa_login"]) && $_SESSION["sepa_login"]===true) {
		header("location: ./dashboard");
	}

	if(!isset($_SESSION["accountcreated"]) && $_SESSION["accountcreated"]===true){
		header("location:./");
	}

?>

<!DOCTYPE html>
<html lang="es"></html>
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
  <meta charset="UTF-8">
  <title>¡Cuenta creada!</title>
  <style>
    body,html{
    	height:100% !important;
    }
    .cont-success{
    	text-align: center;
    }
    h1{
    	color:#6641b8;
    	font-size:60px;
    	font-weight:800;
    }
    p{
    	color:#aaa;
    }
    a{
    	background:#2fc797;
    	border-radius: 50px;
    	color:#fff;
    	display:inline-block;
    	font-weight:500;
    	padding: 10px 20px;
    	text-decoration:none !important;
    	vertical-align:top;
    	width:auto;
    }
    a:hover{
    	color:#fff;
    }
    
  </style>
</head>
<body>
  <div class="container h-100">
    <div class="row justify-content-center align-items-center h-100">
      <div class="col align-self-center">
        <div class="cont-success">
          <h1>¡Felicidades!</h1>
          <h3>Tu cuenta ha sido creada exitosamente.</h3>
          <p>En breves momentos recibiras un correo para activar tu cuenta.</p><a href="./">Ir al inicio</a>
        </div>
      </div>
    </div>
  </div>
</body>