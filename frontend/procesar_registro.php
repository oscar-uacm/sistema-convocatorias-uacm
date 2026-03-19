<?php
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //verificación de seguridad: si los campos están vacios
    if(empty($_POST['nombre']) || empty($_POST['correo'])) {
        die("Error: Formulario recibido pero los campos están vacíos. Revisa los atributos 'name' en tu HTML.");
    }
    // Recibimos los datos de los 'name' que pusiste en el HTML
    $nombre    = $_POST['nombre'];
    $correo    = $_POST['correo'];
    $matricula = $_POST['matricula']; // Asegúrate de haber puesto name="matricula"
    $pass      = $_POST['password'];
    $pass_c    = $_POST['password_confirm'];

    // 1. Validación básica: ¿Contraseñas iguales?
    if ($pass !== $pass_c) {
        die("Error: Las contraseñas no coinciden. <a href='javascript:history.back()'>Volver a intentar</a>");
    }

    // 2. Insertar en la base de datos
    // Nota: Agregamos la columna 'matricula' si la creaste en el paso anterior, 
    // si no, simplemente la ignoramos en el SQL.
    $sql = "INSERT INTO usuarios (nombre, correo, password, rol) 
            VALUES ('$nombre', '$correo', '$pass', 'investigador')";

    if (mysqli_query($conexion, $sql)) {
        // En lugar de redirigir, mostramos un mensaje de éxito
        echo "
        <div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>
            <h2 style='color: #9C2007;'>¡Registro Exitoso!</h2>
            <p>El usuario <b>$nombre</b> ha sido creado correctamente en la base de datos local.</p>
            <br>
            <a href='registro-usuario.html' style='text-decoration: none; color: white; background: #701705; padding: 10px 20px; border-radius: 5px;'>Volver al Registro</a>
            <a href='login.html' style='margin-left: 10px; text-decoration: none; color: #701705; border: 1px solid #701705; padding: 10px 20px; border-radius: 5px;'>Ir al Login</a>
        </div>";
    } else {
        echo "Error al registrar en MySQL: " . mysqli_error($conexion);
    }
}
?>