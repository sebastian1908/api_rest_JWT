<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require_once '../Conexion/conexion.php';
require_once '../models/metodos.php';

class updateProducto{
    private function updateDataProducto(){
        $conexion = new Conexion();
        $db = $conexion->conexionBd();
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser PUT',
            ]);
        exit;
        }

        $productos = new Productos($db);
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->id) && !empty($data->nombreProducto) && 
        !empty($data->precio) && !empty($data->cantidad)){ 
	
	    $productos->id = $data->id; 
	    $productos->nombreProducto = $data->nombreProducto;
        $productos->precio = $data->precio;
        $productos->cantidad = $data->cantidad;
	
            if($productos->updateProduct()){     
                http_response_code(200);   
                echo json_encode(array("message" => "Producto actualizado con exito."));
            }else{    
                http_response_code(405);     
                echo json_encode(array("message" => "Error al actualizar el producto."));
            }
            
        } else {
            http_response_code(500);    
            echo json_encode(array("message" => "Error, algo salio fallo."));
        }
    }

    public function obtenerDataProducto(){
        return $this->updateDataProducto();
    }
}


$datos = new updateProducto();
$datos->obtenerDataProducto();























