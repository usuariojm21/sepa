<?php
	session_start(); 
	if (isset($_SESSION["sepa_login"])) {
		
		//unset($_SESSION['encon_users_sepa']);
		$_SESSION = null;
		session_destroy();
	}
	header("location: ../view/");

	
	
?>