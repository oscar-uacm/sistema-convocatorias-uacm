<?php
session_start();
include 'conexion.php';

// Seguridad: Solo administradores
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Consultas de conteo real
$resTotales = mysqli_query($conexion, "SELECT COUNT(*) as total FROM proyectos");
$total = mysqli_fetch_assoc($resTotales)['total'];

$resPendientes = mysqli_query($conexion, "SELECT COUNT(*) as total FROM proyectos WHERE estatus = 'Enviado'");
$pendientes = mysqli_fetch_assoc($resPendientes)['total'];

$resAprobados = mysqli_query($conexion, "SELECT COUNT(*) as total FROM proyectos WHERE estatus = 'Aprobado'");
$aprobados = mysqli_fetch_assoc($resAprobados)['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>UACM - Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body class="bg-[#fcfaf9] font-['Lexend'] flex">
    
    <?php include 'sidebar.php'; ?>

    <main class="flex-1 p-8">
        <header class="mb-10">
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Panel de Control Administrativo</h1>
            <p class="text-sm text-gray-500">Gestión de la convocatoria vigente 2026</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <span class="material-symbols-outlined text-primary mb-2">folder_shared</span>
                <p class="text-xs font-bold text-gray-400 uppercase">Total Recibidos</p>
                <h2 class="text-3xl font-black"><?php echo $total; ?></h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-b-4 border-b-amber-500">
                <span class="material-symbols-outlined text-amber-500 mb-2">pending_actions</span>
                <p class="text-xs font-bold text-gray-400 uppercase">Por Evaluar</p>
                <h2 class="text-3xl font-black"><?php echo $pendientes; ?></h2>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-b-4 border-b-green-500">
                <span class="material-symbols-outlined text-green-500 mb-2">task_alt</span>
                <p class="text-xs font-bold text-gray-400 uppercase">Aprobados</p>
                <h2 class="text-3xl font-black"><?php echo $aprobados; ?></h2>
            </div>
        </div>

        <div class="bg-primary/5 p-6 rounded-2xl border border-primary/10">
            <h3 class="font-bold text-primary flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined">rocket_launch</span> Acciones Rápidas
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="admin-proyectos.php" class="bg-white p-4 rounded-xl text-center shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-bold uppercase text-gray-600">Revisar Proyectos</p>
                </a>
                <a href="admin-usuarios.php" class="bg-white p-4 rounded-xl text-center shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-bold uppercase text-gray-600">Investigadores</p>
                </a>
            </div>
        </div>
    </main>
</body>
</html>