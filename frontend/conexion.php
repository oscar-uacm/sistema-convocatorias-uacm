<?php
$host = "db"; 
$user = "user_admin";
$pass = "user_password";
$db   = "sistema_proyectos";

// 1. Conexión MySQLi (Para Dashboard y Login)
$conexion = mysqli_connect($host, $user, $pass, $db);
if (!$conexion) {
    die("Error de conexión MySQLi: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");

// 2. Conexión PDO (Para finalizar-registro.php)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión PDO: " . $e->getMessage());
}
?>