<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
require_once '../Conexion/conexion.php';
require_once '../models/metodos.php';

class CreateProducto
{
    private function createDataProducto()
    {
        $conexion = new Conexion();
        $db = $conexion->conexionBd();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser POST',
            ]);
            exit;
        }

        $producto = new Productos($db);
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->nombreProducto) && !empty($data->precio) &&
        !empty($data->cantidad)){    
        
            $producto->nombreProducto = $data->nombreProducto;
            $producto->precio = $data->precio;
            $producto->cantidad = $data->cantidad;
            
            if($producto->createProduct()){         
                http_response_code(200);         
                echo json_encode(array("message" => "Producto creado con exito."));
            } else{         
                http_response_code(405);        
                echo json_encode(array("message" => "Error al crear el producto."));
            }
        }else{    
            http_response_code(500);    
            echo json_encode(array("message" => "Error, algo salio fallo."));
        }
    }


    public function obtenerCreateProducto(){
        return $this->createDataProducto();
    }

}


$datos = new CreateProducto();
$datos->obtenerCreateProducto();





 

















