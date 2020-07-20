<?php 

require_once("../model/class.conexion.php");
require_once("../model/class.consultas.php");

$rif = $_POST["rif"];
$usuario = $_POST["usuario"];
$clave = $_POST["clave"];
$nivel = $_POST["nivel"];

$campos = array(
	"campos" => ["rifentidad","usuario","clave","nivel"],
	"values" => [$rif,$usuario,$clave,$nivel],
	"tabla" => "usuarios",
	"condicion" => ["rifentidad",$rif]
	//"param_adionales" => "Order By usuario Desc"
);

$consultas = new consultas();
$mensaje = $consultas->actualizar($campos);

echo $mensaje;

?>