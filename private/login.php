<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'../../Conexion/conexion.php';
require __DIR__.'../../models/JwtHandler.php';

class Login{
    
    private function IniciarSesion(){
        function msg($success,$status,$message,$extra = []){
            return array_merge([
                'success' => $success,
                'status' => $status,
                'message' => $message
            ],$extra);
        }

        $conexion = new Conexion();
        $conn = $conexion->ConexionConBaseDatos();

        $data = json_decode(file_get_contents("php://input"));
        $returnData = [];

        if($_SERVER["REQUEST_METHOD"] != "POST"):
            $returnData = msg(0, http_response_code(404),'Pagina no encontrada!');
        
            elseif(!isset($data->email) || !isset($data->password)|| empty(trim($data->email))|| empty(trim($data->password))):
        
            $fields = ['fields' => ['email','password']];
            $returnData = msg(0,http_response_code(422),'Todos los campos son obligatorios!',$fields);
        
                else:
            $email = trim($data->email);
            $password = trim($data->password);
                
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
                $returnData = msg(0,http_response_code(422),'Correo ingresado es invalido!');
            
            elseif(strlen($password) < 8):
                $returnData = msg(0,http_response_code(422),'la contraseña debe ser mayor a 8 caracteres!');
                        
            else:
                try{

                    $fetch_user_by_email = "SELECT * FROM `users` WHERE `email`=:email";
                    $query_stmt = $conn->prepare($fetch_user_by_email);
                    $query_stmt->bindValue(':email', $email,PDO::PARAM_STR);
                    $query_stmt->execute();
                                
                    if($query_stmt->rowCount()):
                        $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                        $check_password = password_verify($password, $row['password']);
 
                        if($check_password):
                        
                            $jwt = new JwtHandler();
                            $token = $jwt->jwtEncodeData(
                            'http://localhost/proyecto_api/',
                            'usuario autenticado'
                        );

                            $returnData = [
                                'success' => 1,
                                'message' => 'Sesion iniciada correctamente.',
                                'token' => $token
                            ];
                                                
                        else:
                            $returnData = msg(0,http_response_code(422),'Contraseña invalida!');
                        endif;
                                        
                    else:
                        $returnData = msg(0,http_response_code(422),'Correo invalido!');
                    endif;
                }
                catch(PDOException $e){
                    $returnData = msg(0,http_response_code(500),$e->getMessage());
                }
            
            endif;
        endif;
        

        return json_encode($returnData);
    }

    function obtenerIniciarSesion(){
        return $this->IniciarSesion();
    }
}

$login = new Login();
$datos = $login->obtenerIniciarSesion();
echo $datos;
