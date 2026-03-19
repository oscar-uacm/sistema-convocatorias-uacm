<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['rol'] === 'admin') {
    $id = intval($_POST['id_proyecto']);
    $estatus = mysqli_real_escape_string($conexion, $_POST['estatus']);
    $obs = mysqli_real_escape_string($conexion, $_POST['comentarios']);

    $sql = "UPDATE proyectos SET 
                estatus = '$estatus', 
                observaciones_admin = '$obs' 
            WHERE id = $id";

    if (mysqli_query($conexion, $sql)) {
        header("Location: admin-proyectos.php?msj=exito");
    } else {
        echo "Error en la base de datos: " . mysqli_error($conexion);
    }
} else {
    header("Location: login.php");
}
?>