<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require_once '../Conexion/conexion.php';
require_once '../models/metodos.php';
 

 
class DeleteProducto{
    private function deleteDataProducto(){

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser PUT',
            ]);
        exit;
        }

        $conexion = new Conexion();
        $db = $conexion->conexionBd();
        $producto = new Productos($db);
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->id)) {
            $producto->id = $data->id;
            if($producto->deleteProduct()){    
                http_response_code(200); 
                echo json_encode(array("message" => "Producto eliminado con exito."));
            } else {    
                http_response_code(503);   
                echo json_encode(array("message" => "No se pudo eliminar el producto."));
            }
        } else {
            http_response_code(400);    
            echo json_encode(array("message" => "Error, algo salio fallo."));
        }        
    }

    public function obtenerDataProducto(){
        return $this->deleteDataProducto();
    }
}


$datos = new DeleteProducto();
$datos->obtenerDataProducto();



