<?php
// 1. Iniciar sesión para acceder a las variables globales
session_start();

// 2. Protección: Si no está logueado, fuera
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 3. Verificar si los datos llegaron por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Limpiamos y recibimos los datos del formulario
    $titulo   = trim($_POST['titulo']);
    $area     = $_POST['area'];
    $duracion = $_POST['duracion'];
    $resumen  = trim($_POST['resumen']);

    // 4. Guardar los datos en una variable de sesión específica del proyecto
    // Esto evita que se pierdan si el usuario refresca la página
    $_SESSION['proyecto_paso1'] = [
        'titulo'   => $titulo,
        'area'     => $area,
        'duracion' => $duracion,
        'resumen'  => $resumen
    ];

    // 5. Redirigir al siguiente paso
    header("Location: registro-paso2.php");
    exit;
} else {
    // Si alguien intenta entrar a este archivo directamente sin enviar el formulario
    header("Location: registro.php");
    exit;
}