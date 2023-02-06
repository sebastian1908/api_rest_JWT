<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '../../Conexion/conexion.php';

class Registro{

    private function RegistraUsuario(){
        $conexion = new Conexion();
        $datos = $conexion->ConexionConBaseDatos();

        function msg($success, $status, $message, $extra = [])
        {
            return array_merge([
                'success' => $success,
                'status' => $status,
                'message' => $message
            ], $extra);
        }

        $data = json_decode(file_get_contents("php://input"));
        $returnData = [];

        if ($_SERVER["REQUEST_METHOD"] != "POST") :

            $returnData = msg(0, http_response_code(404), 'Pagina no encontrada');

        elseif (!isset($data->nombre) || !isset($data->email) || !isset($data->password) || empty(trim($data->nombre)) || empty(trim($data->email)) || empty(trim($data->password))) :

            $fields = ['fields' => ['nombre', 'email', 'password']];
            $returnData = msg(0, http_response_code(422), 'Todos los campos son obligatorios!', $fields);

        else :
            $nombre = trim($data->nombre);
            $email = trim($data->email);
            $password = trim($data->password);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
                $returnData = msg(0, http_response_code(422), 'Correo Invalido!');

            elseif (strlen($password) < 8) :
                $returnData = msg(0, http_response_code(422), 'la contraseña debe tener una longitud mayor a 3 caracteres!');

            elseif (strlen($nombre) < 3) :
                $returnData = msg(0, http_response_code(422), 'El nombre debe tener una longitud mayor a 3 caracteres!');

            else :
                try {
                    $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
                    $check_email_stmt = $datos->prepare($check_email);
                    $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                    $check_email_stmt->execute();

                    if ($check_email_stmt->rowCount()) :
                        $returnData = msg(0, http_response_code(422), 'El correo ya esta en uso!');

                    else :
                        $insert_query = "INSERT INTO `users`(`nombre`,`email`,`password`) VALUES (:nombre,:email,:password)";
                        $insert_stmt = $datos->prepare($insert_query);

                        $insert_stmt->bindValue(':nombre', htmlspecialchars(strip_tags($nombre)), PDO::PARAM_STR);
                        $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                        $insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

                        $insert_stmt->execute();
                        $returnData = msg(1, http_response_code(200), 'Registro exitosos.');
                    endif;
                } catch (PDOException $e) {
                    $returnData = msg(0, http_response_code(500), $e->getMessage());
                }
            endif;
        endif;
        return json_encode($returnData);
    }


    function obtenerRegistrarUsuario(){
        return $this->registraUsuario();
    }
}

$registro = new Registro();
$datos = $registro->obtenerRegistrarUsuario();
echo $datos;
