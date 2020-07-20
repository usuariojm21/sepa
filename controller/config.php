<?php 

	$config = array(
		"cicloFinanciero" => array(
			"registros" => 1
		)
	);

	$json_string = json_encode($config);
	$file = 'config.json';
	file_put_contents($file, $json_string);

	/*$datos_clientes = file_get_contents("config.json");
	$json_clientes = json_decode($datos_clientes, true);

	echo $json_clientes["cicloFinanciero"]["registros"];*/

?>