<?php
session_start();
require_once 'conexion.php';

// Carga de la librería descargada manualmente
require_once 'dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['folio']) || !isset($_SESSION['user_id'])) {
    die("Acceso no autorizado.");
}

$folio = mysqli_real_escape_string($conexion, $_GET['folio']);
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del proyecto
$sql = "SELECT * FROM proyectos WHERE folio = '$folio' AND usuario_id = '$user_id'";
$res = mysqli_query($conexion, $sql);
$proy = mysqli_fetch_assoc($res);

if (!$proy) {
    die("Proyecto no encontrado o no tiene permisos para verlo.");
}

// Configuración de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Contenido del PDF (Diseño Institucional)
$html = '
<html>
<head>
    <style>
        body { font-family: sans-serif; padding: 30px; }
        .header { text-align: center; border-bottom: 2px solid #701705; margin-bottom: 30px; }
        .logo { color: #701705; font-size: 28px; font-weight: bold; }
        .folio-box { float: right; border: 1px solid #701705; padding: 10px; text-align: center; }
        .section-title { background: #f4f4f4; padding: 5px; font-weight: bold; border-left: 5px solid #701705; margin-top: 20px; }
        table { width: 100%; margin-top: 10px; border-collapse: collapse; }
        td { padding: 8px; border-bottom: 1px solid #eee; font-size: 12px; }
        .label { font-weight: bold; color: #666; width: 30%; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="folio-box">
        <span style="font-size: 10px;">FOLIO DE REGISTRO</span><br>
        <strong>' . $proy['folio'] . '</strong>
    </div>
    <div class="header">
        <div class="logo">UACM</div>
        <div style="font-size: 12px;">Colegio de Ciencias y Humanidades</div>
        <p style="font-size: 14px; font-weight: bold;">Acuse de Recibo de Proyecto de Investigación</p>
    </div>

    <div class="section-title">DATOS GENERALES</div>
    <table>
        <tr><td class="label">Título:</td><td>' . $proy['titulo'] . '</td></tr>
        <tr><td class="label">Área:</td><td>' . $proy['area'] . '</td></tr>
        <tr><td class="label">Estatus actual:</td><td>' . $proy['estatus'] . '</td></tr>
        <tr><td class="label">Fecha de registro:</td><td>' . $proy['fecha_creacion'] . '</td></tr>
    </table>

    <div class="section-title">RESUMEN DEL PROYECTO</div>
    <div style="font-size: 12px; margin-top: 10px; text-align: justify; line-height: 1.5;">
        ' . nl2br($proy['resumen']) . '
    </div>

    <div class="section-title">PRESUPUESTO SOLICITADO</div>
    <table>
        <tr><td class="label">Monto Total:</td><td>$' . number_format($proy['monto'], 2) . ' MXN</td></tr>
    </table>

    <div class="footer">
        Universidad Autónoma de la Ciudad de México - "Nada humano me es ajeno"<br>
        Este documento es un comprobante oficial de registro digital.
    </div>
</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar al navegador (Attachment false permite verlo antes de descargar)
$dompdf->stream("Acuse_" . $proy['folio'] . ".pdf", array("Attachment" => false));
?>