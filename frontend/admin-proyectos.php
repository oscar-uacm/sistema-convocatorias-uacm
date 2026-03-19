<?php
session_start();
require_once 'conexion.php';

// 1. SEGURIDAD: Solo Admin o Evaluador
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'evaluador')) {
    header("Location: login.php");
    exit;
}

$user_id = intval($_SESSION['user_id']); 
$rol = $_SESSION['rol'];
$nombre_usuario = $_SESSION['nombre'];

// 2. LÓGICA DE CONSULTA (AHORA INCLUYE LA CONVOCATORIA)
if ($rol === 'admin') {
    $sql = "SELECT p.id, p.folio, p.titulo, p.area, p.estatus, p.fecha_creacion, u.nombre as investigador, c.titulo as nombre_convocatoria 
            FROM proyectos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            LEFT JOIN convocatorias c ON p.convocatoria_id = c.id
            ORDER BY p.id DESC";
} else {
    $sql = "SELECT p.id, p.folio, p.titulo, p.area, p.estatus, p.fecha_creacion, u.nombre as investigador, c.titulo as nombre_convocatoria 
            FROM proyectos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            LEFT JOIN convocatorias c ON p.convocatoria_id = c.id
            WHERE p.evaluador_id = $user_id 
            ORDER BY p.id DESC";
}

$res = mysqli_query($conexion, $sql);
$num_proyectos = mysqli_num_rows($res);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UACM - Panel de Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: { colors: { "primary": "#701705", "background-light": "#f8f6f5" }, fontFamily: { "display": ["Lexend", "sans-serif"] } },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display text-slate-800 pb-20">
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="size-10 bg-slate-800 text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">folder_open</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Expedientes de Proyectos</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">
                    <?php echo ($rol === 'admin') ? 'Gestión General' : 'Mis Asignaciones'; ?>
                </p>
            </div>
        </div>
        <div class="flex gap-4">
            <?php $ruta_volver = ($rol === 'admin') ? 'admin-dashboard.php' : 'dashboard.php'; ?>
            <a href="<?php echo $ruta_volver; ?>" class="flex items-center gap-2 px-5 py-2 border-2 border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-all font-bold text-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Volver al Inicio
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">list_alt</span> 
                    <?php echo $num_proyectos; ?> Proyectos Encontrados
                </h3>
            </div>
            
            <table class="w-full text-left">
                <thead class="bg-white border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Proyecto y Autor</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Área</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Estatus</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php while($p = mysqli_fetch_assoc($res)): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-primary font-black text-[9px] mb-1 block">FOLIO: <?php echo htmlspecialchars($p['folio']); ?></span>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight mb-1"><?php echo htmlspecialchars($p['titulo']); ?></h3>
                            
                            <p class="text-[10px] font-bold text-blue-600 uppercase flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">campaign</span>
                                <?php echo htmlspecialchars($p['nombre_convocatoria'] ?? 'Registro Libre'); ?>
                            </p>
                            <?php if($rol === 'admin'): ?>
                                <p class="text-[10px] text-gray-400 mt-2 italic">Autor: <?php echo htmlspecialchars($p['investigador']); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 text-[10px] font-bold text-gray-600 uppercase"><?php echo htmlspecialchars($p['area'] ?? 'No definida'); ?></td>
                        <td class="px-8 py-6">
                            <?php 
                                $est = $p['estatus'];
                                $badgeClass = "bg-gray-100 text-gray-600";
                                if ($est == 'Aprobado') $badgeClass = "bg-green-100 text-green-700";
                                if ($est == 'No Aprobado') $badgeClass = "bg-red-100 text-red-700";
                                if ($est == 'Con Cambios') $badgeClass = "bg-amber-100 text-amber-700";
                                if ($est == 'En Evaluación') $badgeClass = "bg-blue-100 text-blue-700";
                            ?>
                            <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest border border-current/10 <?php echo $badgeClass; ?>">
                                <?php echo htmlspecialchars($est); ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="admin-evaluacion.php?id=<?php echo $p['id']; ?>" class="inline-flex items-center gap-2 bg-slate-900 text-white text-[10px] font-black px-6 py-3 rounded-xl hover:bg-primary transition-all uppercase tracking-widest">
                                <span class="material-symbols-outlined text-sm">rate_review</span> Dictaminar
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>