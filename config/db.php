<?php
// config/db.php

date_default_timezone_set('America/Bogota');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sipaf_db";
$port = 3307;

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: establecer codificación
$conn->set_charset("utf8");
?>

