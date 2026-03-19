<?php
session_start();
// 1. Incluir tu archivo de conexión a la base de datos
require_once 'conexion.php'; 

// 2. SEGURIDAD: Verificar que el usuario esté logueado y que existan datos de sesión
if (!isset($_SESSION['user_id']) || !isset($_SESSION['proyecto_paso1'])) {
    header("Location: registro.php");
    exit;
}

// 3. RECOLECTAR DATOS DE LA SESIÓN (Limpiando espacios)
$p1 = $_SESSION['proyecto_paso1'];
$p2 = $_SESSION['proyecto_paso2'] ?? [];
$p3 = $_SESSION['proyecto_paso3'] ?? [];
$usuario_id = $_SESSION['user_id'];

// 4. GENERAR FOLIO ÚNICO
// Formato: UACM-2026-XXXX (donde X son caracteres aleatorios)
$anio = date('Y');
$hash = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 5));
$folio = "UACM-{$anio}-{$hash}";

try {
    // 5. PREPARAR LA CONSULTA SQL (Usando PDO para evitar Inyección SQL)
    // Dentro de finalizar-registro.php
$sql = "INSERT INTO proyectos (
            folio, usuario_id, titulo, area, duracion, resumen, 
            colaborador_nombre, colaborador_id, colaborador_adscripcion, 
            alumno_nombre, alumno_matricula, monto, justificacion_gasto, 
            fecha_inicio, fecha_termino, estatus, fecha_creacion
        ) VALUES (
            :folio, :u_id, :titulo, :area, :duracion, :resumen, 
            :c_nom, :c_id, :c_ads, :a_nom, :a_mat, :monto, :just, 
            :f_ini, :f_fin, 'Enviado', NOW()
        )";

    $stmt = $pdo->prepare($sql);

    // 6. EJECUTAR CON LOS DATOS
    $stmt->execute([
        ':folio'   => $folio,
        ':u_id'    => $usuario_id,
        ':titulo'  => $p1['titulo'],
        ':area'    => $p1['area'],
        ':duracion'=> $p1['duracion'],
        ':resumen' => $p1['resumen'],
        ':c_nom'   => $p2['colaborador_nombre'] ?? null,
        ':c_id'    => $p2['colaborador_id'] ?? null,
        ':c_ads'   => $p2['colaborador_adscripcion'] ?? null,
        ':a_nom'   => $p2['alumno_nombre'] ?? null,
        ':a_mat'   => $p2['alumno_matricula'] ?? null,
        ':monto'   => $p3['monto'] ?? 0,
        ':just'    => $p3['justificacion'] ?? null,
        ':f_ini'   => $p3['fecha_inicio'] ?? null,
        ':f_fin'   => $p3['fecha_fin'] ?? null
    ]);

    // 7. ÉXITO: Guardar el folio en la sesión para mostrarlo en la página de éxito
    $_SESSION['ultimo_folio'] = $folio;

    // 8. LIMPIEZA: Borrar los datos temporales del formulario de la sesión
    unset($_SESSION['proyecto_paso1']);
    unset($_SESSION['proyecto_paso2']);
    unset($_SESSION['proyecto_paso3']);

    // 9. REDIRIGIR A LA PÁGINA QUE YA TIENES
    header("Location: registro-exito.php");
    exit;

} catch (PDOException $e) {
    // En caso de error técnico
    error_log("Error en registro de proyecto: " . $e->getMessage());
    die("Lo sentimos, hubo un error al procesar su solicitud. Por favor, intente más tarde.");
}