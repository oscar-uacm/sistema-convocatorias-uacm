<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Guardamos los datos del equipo en la sesión
    $_SESSION['proyecto_paso2'] = [
        'colaborador_nombre'      => trim($_POST['colaborador_nombre']),
        'colaborador_id'          => trim($_POST['colaborador_id']),
        'colaborador_adscripcion' => trim($_POST['colaborador_adscripcion']),
        'alumno_nombre'           => trim($_POST['alumno_nombre']),
        'alumno_matricula'        => trim($_POST['alumno_matricula'])
    ];

    // Redirigimos al paso 3 (Cronograma o Presupuesto)
    header("Location: registro-paso3.php");
    exit;
} else {
    header("Location: registro-paso2.php");
    exit;
}