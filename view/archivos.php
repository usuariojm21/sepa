
<?php
	session_start();
	if($_SESSION["nivel"]==="ADMINISTRADOR"){
		header("location: ./ciclos");
	}else{
		header("location: ./productores");
	};
?>
