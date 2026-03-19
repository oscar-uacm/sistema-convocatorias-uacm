<?php
session_start();
require_once 'conexion.php'; // Conecta a la BD usando tu PDO

// 1. Verificar que el usuario esté logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Verificar que existan todos los datos en sesión
if (!isset($_SESSION['proyecto_paso1'], $_SESSION['proyecto_paso2'], $_SESSION['proyecto_paso3'], $_SESSION['proyecto_paso4'])) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h2>Faltan datos en tu registro</h2>
            <p>Por favor, <a href='registro-paso1.php' style='color:#9c2007; font-weight:bold;'>inicia el registro nuevamente</a> para evitar errores.</p>
         </div>");
}

// 3. Rescatar todas las variables
$p1 = $_SESSION['proyecto_paso1'];
$p2 = $_SESSION['proyecto_paso2'];
$p3 = $_SESSION['proyecto_paso3'];
$p4 = $_SESSION['proyecto_paso4'];

// 4. Generar Folio Automático y Datos por Defecto
$folio = "PROY-" . date("Y") . "-" . str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);
$estatus = "Enviado";
$fecha_creacion = date("Y-m-d H:i:s");

// 5. Insertar en la Base de Datos
try {
// --- NUEVO: RESCATAR LA CONVOCATORIA DE LA SESIÓN ---
    $convocatoria_id = isset($_SESSION['conv_id']) ? intval($_SESSION['conv_id']) : null;
    // ----------------------------------------------------

    $sql = "INSERT INTO proyectos (
                usuario_id, folio, estatus, fecha_creacion,
                titulo, area, duracion, resumen,
                colaborador_nombre, colaborador_id, colaborador_adscripcion, alumno_nombre, alumno_matricula,
                monto, justificacion, fecha_inicio, fecha_fin,
                ruta_formato1, ruta_formato2, ruta_formato3,
                convocatoria_id  /* <-- NUEVA COLUMNA AQUÍ */
            ) VALUES (
                ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?,
                ? /* <-- NUEVO SIGNO DE INTERROGACIÓN AQUÍ */
            )";

    $stmt = $pdo->prepare($sql);
    
    // Ejecutamos pasando las variables en orden (¡Agregamos $convocatoria_id al final!)
    $stmt->execute([
        $user_id, $folio, $estatus, $fecha_creacion,
        $p1['titulo'], $p1['area'], $p1['duracion'], $p1['resumen'],
        $p2['colaborador_nombre'], $p2['colaborador_id'], $p2['colaborador_adscripcion'], $p2['alumno_nombre'], $p2['alumno_matricula'],
        $p3['monto'], $p3['justificacion'], $p3['fecha_inicio'], $p3['fecha_fin'],
        $p4['ruta_formato1'], $p4['ruta_formato2'], $p4['ruta_formato3'],
        $convocatoria_id /* <-- LA VARIABLE AL FINAL */
    ]);

    // ... (todo el código anterior de PDO insert se queda igual) ...
    
    // 6. ¡NUEVO! Guardar el folio para la pantalla de éxito
    $_SESSION['ultimo_folio'] = $folio;

    // 7. Limpiar las sesiones de los pasos
    unset($_SESSION['proyecto_paso1']);
    unset($_SESSION['proyecto_paso2']);
    unset($_SESSION['proyecto_paso3']);
    unset($_SESSION['proyecto_paso4']);

    // 8. ¡NUEVO! Redirigir a TU archivo de éxito en lugar del dashboard
    header("Location: registro-exito.php");
    exit;

} catch (PDOException $e) {
    die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
            <h2 style='color:#9c2007;'>Error al guardar en la Base de Datos</h2>
            <p>Ocurrió un problema: " . $e->getMessage() . "</p>
         </div>");

}