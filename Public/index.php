<?php

require_once '../include/header.php';
require_once '../Conexion/conexion.php';
require_once '../models/metodos.php';

$conexion = new Conexion();
$db = $conexion->conexionBd();
$productos = new Productos($db);
$resultado = $productos->getProduct();

?>
<div class="container fixed">    
<div class="title text-center">
        <div class="p-3 mb-2 bg-primary text-white display-6">
           PRODUCTOS
        </div>
    </div>

<div class="table-responsive">
    <table class="table">
        <thead class="bg-primary text-center text-white">
            <tr>
                <th>#</th>
                <th>NOMBRE PRODUCTO</th>
                <th>PRECIO</th>
                <th>CANTIDAD</th>
				<th>Accion</th>
            </tr>    
        </thead>
        <?php
        $sql = "SELECT * FROM productos";
        foreach ($db->query($sql) as $row) {
            ?>
            <tr class="text-center">
                <th><?= $row['id']; ?></th>
                <th><?= $row['nombre_producto']; ?></th>
                <th><?= $row['precio']; ?></th>
                <th><?= $row['cantidad']; ?></th>				
            </tr>
<?php 
    }
?> 
</table>
</div>
</div>