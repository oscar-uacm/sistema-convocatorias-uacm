<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['proyecto_paso3'] = [
        'monto'        => $_POST['monto'],
        'justificacion' => trim($_POST['justificacion']),
        'fecha_inicio' => $_POST['fecha_inicio'],
        'fecha_fin'    => $_POST['fecha_fin']
    ];

    // Redirigimos al paso final (Revisión y envío a base de datos)
    header("Location: registro-paso4.php");
    exit;
} else {
    header("Location: registro-paso3.php");
    exit;
}