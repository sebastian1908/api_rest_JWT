<?php
class Productos{   
    
    private $itemsTable = "productos";   
    public $id;
    public $nombre_producto;
    public $precio;
    public $price;
    public $category_id;   
    private $conn;
	
    public function __construct($db){
        $this->conn = $db;
    }
		
	
	public function getProduct(){
        if($this->id) {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->itemsTable." WHERE id = ?");
			$stmt->bind_param("i", $this->id);					
		} else {
			$stmt = $this->conn->prepare("SELECT * FROM ".$this->itemsTable);		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	function createProduct(){
		
		$stmt = $this->conn->prepare("
			INSERT INTO ".$this->itemsTable."(nombre_producto, precio, cantidad)
			VALUES(?,?,?)");
		
		$this->nombreProducto = htmlspecialchars(strip_tags($this->nombreProducto));
		$this->precio = htmlspecialchars(strip_tags($this->precio));
		$this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
		
		$stmt->bind_param("sii", $this->nombreProducto, $this->precio, $this->cantidad);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
		
	function updateProduct(){
	 
		$stmt = $this->conn->prepare("
			UPDATE ".$this->itemsTable." 
			SET nombre_producto= ?, precio = ?, cantidad = ? WHERE id = ?");
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->nombreProducto = htmlspecialchars(strip_tags($this->nombreProducto));
		$this->precio = htmlspecialchars(strip_tags($this->precio));
		$this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
	 
		$stmt->bind_param("siii", $this->nombreProducto, $this->precio, $this->cantidad, $this->id);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	function deleteProduct(){
		
		$stmt = $this->conn->prepare("
			DELETE FROM ".$this->itemsTable." 
			WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
}