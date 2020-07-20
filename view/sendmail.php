<?php 

	require("php/phpMailer/class.phpmailer.php");
	require("php/phpMailer/class.smtp.php");

	$subject = 'Prueba 2 de envio de correo desde portugas';
	$mensaje = "Holaaaaa, este es un correo de prueba";

	$mail = new phpMailer;
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "ssl";
	$mail->Host = "mail.portugas.net";
	$mail->Port = 465;
	$mail->Username = "pgda";
	$mail->Password = "dEQ2fdgut";
	$mail->From = "portugas@portugas.net";
	$mail->FromName = "Soporte tecnico";
	$mail->Subject = $subject;
	$mail->addAddress('josemendezlbn@gmail.com');
	$mail->isHTML(true);
	$mail->Body = $mensaje;
	$statemail = false;
	try {
		$mail->send();
		$statemail = true;
		echo "Listo, el correo ha sido enviado.";
	} catch (Exception $e) {
		$statemail = false;
		echo "<script>console.log('".$e->getMessage()."')</script>";
	}
?>