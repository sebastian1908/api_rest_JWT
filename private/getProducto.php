<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../Conexion/conexion.php';
require_once '../models/metodos.php';

class getProducto
{


    private function getDataProducto()
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser GET',
            ]);
            exit;
        }

        $conexion = new Conexion();
        $db = $conexion->conexionBd();
        $productos = new Productos($db);
        $productos->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';
        $resultado = $productos->getProduct();

        if($resultado->num_rows > 0){    
            $product=[];
            $product["PRODUCTO"]=[]; 
            while ($prod = $resultado->fetch_assoc()) { 	
                extract($prod); 
                $data=[
                    "ID" => $prod['id'],
                    "NOMBRE PRODUCTO" => $prod['nombre_producto'],
                    "PRECIO" => $prod['precio'],
                    "CANTIDAD" => $prod['cantidad']
                ]; 
               array_push($product["PRODUCTO"], $data);
            }    
            http_response_code(200);     
            echo json_encode($product);
        }else{     
            http_response_code(404);     
            echo json_encode(["message" => "El producto no existe."]);
        } 
    }


    public function obtenerGetDataProducto()
    {
        return $this->getDataProducto();
    }

}


$datos = new getProducto();
$datos->obtenerGetDataProducto();



