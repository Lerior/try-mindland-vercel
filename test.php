<?php
require_once 'conexion.php';
$c = conexion();
if ($c) {
    echo "Conexión a la base de datos exitosa.";
}else {
    echo "NOHAYNADA";
}
?>