<?php
require __DIR__ . '/JwtHandler.php';

class Auth extends JwtHandler
{
    protected $db;
    protected $headers;
    protected $token;

    public function __construct($db, $headers)
    {
        parent::__construct();
        $this->db = $db;
        $this->headers = $headers;
    }

    // metodo para obtener el producto
    public function validaToken()
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);

            if (isset($data['data']) && $producto = $this->getProducto($data)){
                return [
                    "success" => 1,
                    "Producto" => $producto
                ];
            }

            else {
                return [
                    "success" => 0,
                    "data" => $data,
                ];
            }

        } else {
            http_response_code(405);
            return [
                "success" => 0,
                "message" => "Token ingresado es invalido"
            ];
        }
    }

    public function validaTokenCreateData()
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);

            if (isset($data['data']) && $producto = $this->insertDataProducto($data['data']))
                return [
                    "success" => 1,
                    "Producto" => $producto
                ];
            else {
                return [
                    "success" => 0,
                    "message" => $data['message'],
                ];
            }

        } else {
            http_response_code(405);
            return [
                "success" => 0,
                "message" => "Token ingresado es invalido"
            ];
        }
    }

    public function validaTokenUpdateData()
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);

            if (isset($data['data']) && $producto = $this->updateDataProducto($data['data']))
                return [
                    "success" => 1,
                    "Producto" => $producto
                ];
            else {
                return [
                    "success" => 0,
                    "message" => $data['message'],
                ];
            }

        } else {
            http_response_code(405);
            return [
                "success" => 0,
                "message" => "Token ingresado es invalido"
            ];
        }
    }

    public function validaTokenDeleteData()
    {

        if (array_key_exists('Authorization', $this->headers) && preg_match('/Bearer\s(\S+)/', $this->headers['Authorization'], $matches)) {

            $data = $this->jwtDecodeData($matches[1]);

            if (isset($data['data']) && $producto = $this->deleteDataProducto($data['data']))
                return [
                    "success" => 1,
                    "Producto" => $producto
                ];
            else {
                return [
                    "success" => 0,
                    "message" => $data['message'],
                ];
            }

        } else {
            http_response_code(405);
            return [
                "success" => 0,
                "message" => "Token ingresado es invalido"
            ];
        }
    }

    
    protected function getProducto()
    {

        require_once __DIR__ . '../../Conexion/conexion.php';
        $database = new Conexion();
        $conn = $database->ConexionConBaseDatos();

        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser GET',
            ]);
            exit;
        }

        $id = null;

        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
                'options' => [
                    'default' => 'id',
                    'min_range' => 1
                ]
            ]);
        }
                        
        try {

            $sql = is_numeric($id) ? "SELECT * FROM `productos` WHERE id='$id'" : "SELECT * FROM `productos`";

            $stmt = $conn->prepare($sql);

            $stmt->execute();

            if ($stmt->rowCount() > 0) :

                $data = null;
                if (is_numeric($id)) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                echo json_encode([
                    'success' => 1,
                    'data' => $data,
                ]);

            else :
                http_response_code(405);
                echo json_encode([
                    'success' => 0,
                    'message' => 'No se encontro resultado!',
                ]);
            endif;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => 0,
                'message' => $e->getMessage()
            ]);
            exit;
        }

    }

    protected function insertDataProducto()
    {
        
        require_once __DIR__ . '../../Conexion/conexion.php';
        $database = new Conexion();
        $conn = $database->ConexionConBaseDatos();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser POST',
            ]);
            exit;
        }
        
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->nombreProducto) || !isset($data->precio) || !isset($data->cantidad)) {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Todos los campos son obligatorios',
            ]);
            exit;
        }

        elseif (empty(trim($data->nombreProducto))) {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'El campo nombre del producto esta vacio'
            ]);
            exit;
        }
        
        elseif (empty(trim($data->precio))) {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'El campo precio esta vacio'
            ]);
            exit;
        }

        elseif (empty(trim($data->cantidad))) {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'El campo precio esta vacio'
            ]);
            exit;
        }

        try {
        
            $nombreProducto = htmlspecialchars(trim($data->nombreProducto));
            $precio = htmlspecialchars(trim($data->precio));
            $cantidad = htmlspecialchars(trim($data->cantidad));
        
            $query = "INSERT INTO productos (nombre_producto,precio,cantidad) VALUES(:nombre_producto,:precio,:cantidad)";
        
            $stmt = $conn->prepare($query);
        
            $stmt->bindValue(':nombre_producto', $nombreProducto, PDO::PARAM_STR);
            $stmt->bindValue(':precio', $precio, PDO::PARAM_INT);
            $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        
            if ($stmt->execute()) {
        
                http_response_code(200);
                echo json_encode([
                    'success' => 1,
                    'message' => 'Producto Agregado correctamente.'
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => 0,
                'message' => 'Error al agregar producto.'
            ]);
            exit;
        
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => 0,
                'message' => $e->getMessage()
            ]);
            exit;
        }  
    }


    protected function updateDataProducto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser PUT',
            ]);
            exit;
        }

        require_once __DIR__ . '../../Conexion/conexion.php';
        $database = new Conexion();
        $conn = $database->ConexionConBaseDatos();
        
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id)) {
            echo json_encode(['success' => 0, 'message' => 'Ingrese un ID valido.']);
            exit;
        }

        try {

            $fetch_post = "SELECT * FROM productos WHERE id=:id";
            $fetch_stmt = $conn->prepare($fetch_post);
            $fetch_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
            $fetch_stmt->execute();
        
            if ($fetch_stmt->rowCount() > 0) :
        
                $row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
                $nombreProducto = isset($data->nombreProducto) ? $data->nombreProducto : $row['nombre_producto'];
                $precio = isset($data->precio) ? $data->precio : $row['precio'];
                $cantidad = isset($data->cantidad) ? $data->cantidad : $row['cantidad'];
        
                $update_query = "UPDATE productos SET nombre_producto = :nombre_producto, precio = :precio, cantidad = :cantidad 
                WHERE id = :id";
        
                $update_stmt = $conn->prepare($update_query);
        
                $update_stmt->bindValue(':nombre_producto', htmlspecialchars(strip_tags($nombreProducto)), PDO::PARAM_STR);
                $update_stmt->bindValue(':precio', htmlspecialchars(strip_tags($precio)), PDO::PARAM_STR);
                $update_stmt->bindValue(':cantidad', htmlspecialchars(strip_tags($cantidad)), PDO::PARAM_STR);
                $update_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
        
        
                if ($update_stmt->execute()) {
        
                    echo json_encode([
                        'success' => 1,
                        'message' => 'Producto actualizado con exito'
                    ]);
                    exit;
                }
        
                echo json_encode([
                    'success' => 0,
                    'message' => 'Error, algo fallo al actualizar el producto.'
                ]);
                exit;
        
            else :
                echo json_encode(['success' => 0, 'message' => 'ID invalido, no existe.']);
                exit;
            endif;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => 0,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }


    protected function deleteDataProducto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode([
                'success' => 0,
                'message' => 'Método de solicitud no válido. El método HTTP debe ser DELETE',
            ]);
            exit;
        }

        require_once __DIR__ . '../../Conexion/conexion.php';
        $database = new Conexion();
        $conn = $database->ConexionConBaseDatos();


        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id)) {
            echo json_encode(['success' => 0, 'message' => 'Ingrese un ID valido.']);
            exit;
        }

        try {

            $fetch_post = "SELECT * FROM productos WHERE id=:id";
            $fetch_stmt = $conn->prepare($fetch_post);
            $fetch_stmt->bindValue(':id', $data->id, PDO::PARAM_INT);
            $fetch_stmt->execute();

            if ($fetch_stmt->rowCount() > 0) :

                $delete_post = "DELETE FROM productos WHERE id=:id";
                $delete_post_stmt = $conn->prepare($delete_post);
                $delete_post_stmt->bindValue(':id', $data->id,PDO::PARAM_INT);

                if ($delete_post_stmt->execute()) {

                    echo json_encode([
                        'success' => 1,
                        'message' => 'Producto eliminado exitosamente.'
                    ]);
                    exit;
                }

                echo json_encode([
                    'success' => 0,
                    'message' => 'Error, algo fallo al eliminar el producto.'
                ]);
                exit;

            else :
                echo json_encode(['success' => 0, 'message' => 'ID invalido, no existe.']);
                exit;
            endif;

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => 0,
                'message' => $e->getMessage()
            ]);
            exit;
        }

    }
}


