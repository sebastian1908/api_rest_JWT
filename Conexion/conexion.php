<?php

class Conexion{
	
	private $servidor  = "localhost";
    private $usuario  = "root";
    private $password   = "";
    private $bd  = "prueba"; 
    
    public function ConexionConBaseDatos(){
        
        try{
            $conn = new PDO('mysql:host='.$this->servidor.';dbname='.$this->bd,$this->usuario,$this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Error de conexion ".$e->getMessage(); 
            exit;
        }
          
    }
}
