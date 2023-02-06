<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ .'/Conexion/conexion.php';
require __DIR__.'/models/AuthMiddleware.php';


$header = getallheaders();
$conexion = new Conexion();
$datos = $conexion->ConexionConBaseDatos();
$auth = new Auth($datos, $header);
echo json_encode($auth->validaTokenUpdateData());
