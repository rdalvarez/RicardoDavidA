<?php 
require_once ("clases/mascota.php");

echo "Hola";

$obj = new Mascota("JJJ", 12, "02061992", "gato");

$res = Mascota::Guardar($obj);

echo $res;

 ?>