<?php
session_start();
require_once 'conexion.php';

// 1. SEGURIDAD: Si no hay sesión, al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nombre_usuario = $_SESSION['nombre'];
$rol_actual = $_SESSION['rol'];

// ======================================================================
// 2. NUEVO: DETECTAR SI HAY UNA CONVOCATORIA ABIERTA
// Busca en la base de datos la última convocatoria que tenga estatus 'Abierta'
// ======================================================================
$queryConvocatoria = "SELECT id, titulo, descripcion FROM convocatorias WHERE estatus = 'Abierta' ORDER BY id DESC LIMIT 1";
$resConvocatoria = mysqli_query($conexion, $queryConvocatoria);
$convocatoriaAbierta = ($resConvocatoria && mysqli_num_rows($resConvocatoria) > 0) ? mysqli_fetch_assoc($resConvocatoria) : null;


// 3. OBTENER ESTADÍSTICAS DEL USUARIO
$queryStats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estatus = 'Enviado' THEN 1 ELSE 0 END) as enviados,
    SUM(CASE WHEN estatus = 'Aprobado' THEN 1 ELSE 0 END) as aprobados
    FROM proyectos WHERE usuario_id = '$user_id'";

$resStats = mysqli_query($conexion, $queryStats);
$stats = mysqli_fetch_assoc($resStats);

// 4. OBTENER LISTADO DE PROYECTOS DEL USUARIO
$queryList = "SELECT id, folio, titulo, area, estatus, fecha_creacion 
              FROM proyectos 
              WHERE usuario_id = '$user_id' 
              ORDER BY id DESC";
$resList = mysqli_query($conexion, $queryList);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UACM - Mi Panel</title>
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

    <header class="bg-white border-b border-slate-200 px-8 py-4 flex flex-col sm:flex-row items-center justify-between sticky top-0 z-50 shadow-sm gap-4">
        <div class="flex items-center gap-4">
            <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Panel de Investigador</p>
            </div>
        </div>
        <div class="flex gap-4">
            <?php if ($rol_actual === 'admin' || $rol_actual === 'evaluador'): ?>
                <a href="admin-dashboard.php" class="flex items-center gap-2 px-5 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition-all font-bold text-sm">
                    <span class="material-symbols-outlined text-sm">admin_panel_settings</span> Panel de Administración
                </a>
            <?php endif; ?>
            <a href="logout.php" class="flex items-center gap-2 px-5 py-2 border-2 border-slate-200 text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-all font-bold text-sm">
                <span class="material-symbols-outlined text-sm">logout</span> Salir
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        
        <?php if ($convocatoriaAbierta): ?>
        <div class="mb-8 bg-gradient-to-r from-primary to-[#9C2007] rounded-3xl p-8 text-white shadow-xl shadow-primary/20 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="px-3 py-1 bg-white/20 text-white text-[10px] font-black uppercase tracking-widest rounded-full border border-white/30 backdrop-blur-sm">Convocatoria Activa</span>
                <h1 class="text-3xl font-black mt-3 mb-2"><?php echo htmlspecialchars($convocatoriaAbierta['titulo']); ?></h1>
                <p class="text-white/80 text-sm max-w-2xl"><?php echo htmlspecialchars($convocatoriaAbierta['descripcion'] ?? 'La convocatoria se encuentra actualmente abierta para recepción de proyectos.'); ?></p>
            </div>
            <div class="shrink-0">
                <a href="registro.php?conv_id=<?php echo $convocatoriaAbierta['id']; ?>" class="px-8 py-4 bg-white text-primary rounded-xl font-black hover:bg-gray-50 transition-all flex items-center gap-2 shadow-lg">
                    <span class="material-symbols-outlined">edit_document</span> Registrar Proyecto
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="size-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center border border-slate-100">
                    <span class="material-symbols-outlined text-2xl">folder_open</span>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Proyectos</p>
                    <h3 class="text-3xl font-black text-slate-800"><?php echo $stats['total'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="size-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center border border-blue-100">
                    <span class="material-symbols-outlined text-2xl">send</span>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Enviados (En Revisión)</p>
                    <h3 class="text-3xl font-black text-slate-800"><?php echo $stats['enviados'] ?? 0; ?></h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="size-14 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center border border-green-100">
                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Aprobados</p>
                    <h3 class="text-3xl font-black text-slate-800"><?php echo $stats['aprobados'] ?? 0; ?></h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            
            <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-black text-slate-800 text-lg">Historial de Proyectos</h3>
                    <p class="text-xs text-slate-500 mt-1">Consulta el estatus de tus propuestas enviadas</p>
                </div>
                
                <?php if ($convocatoriaAbierta): ?>
                    <a href="registro.php?conv_id=<?php echo $convocatoriaAbierta['id']; ?>" class="bg-primary text-white px-6 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 hover:bg-black transition-all shadow-md">
                        <span class="material-symbols-outlined text-sm">add_circle</span> Iniciar Nuevo Proyecto
                    </a>
                <?php else: ?>
                    <span class="bg-gray-100 text-gray-400 px-6 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 cursor-not-allowed border border-gray-200">
                        <span class="material-symbols-outlined text-sm">lock</span> Convocatoria Cerrada
                    </span>
                <?php endif; ?>
                </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Datos del Proyecto</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Estatus</th>
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php while($fila = mysqli_fetch_assoc($resList)): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-primary font-black text-[10px] mb-1 block">FOLIO: <?php echo htmlspecialchars($fila['folio'] ?? 'N/A'); ?></span>
                                <h4 class="font-bold text-slate-800 text-sm mb-1"><?php echo htmlspecialchars($fila['titulo']); ?></h4>
                                <p class="text-[10px] text-slate-500 uppercase tracking-widest"><?php echo htmlspecialchars($fila['area']); ?> · Registrado el <?php echo date("d/m/Y", strtotime($fila['fecha_creacion'])); ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <?php 
                                    $est = $fila['estatus'];
                                    $badgeClass = "bg-gray-100 text-gray-600 border-gray-200";
                                    if ($est == 'Aprobado') $badgeClass = "bg-green-50 text-green-600 border-green-200";
                                    if ($est == 'No Aprobado') $badgeClass = "bg-red-50 text-red-600 border-red-200";
                                    if ($est == 'Con Cambios') $badgeClass = "bg-amber-50 text-amber-600 border-amber-200";
                                    if ($est == 'Enviado' || $est == 'En Evaluación') $badgeClass = "bg-blue-50 text-blue-600 border-blue-200";
                                ?>
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($est); ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="generar_pdf.php?id=<?php echo $fila['id']; ?>" target="_blank"
                                       class="flex items-center gap-1 text-red-600 hover:bg-red-50 px-3 py-2 rounded-xl transition-all font-bold text-[10px] uppercase">
                                        <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                                        Folio
                                    </a>
                                    <a href="ver-detalle.php?id=<?php echo $fila['id']; ?>" 
                                       class="flex items-center gap-1 text-gray-400 hover:text-black px-3 py-2 rounded-xl transition-all font-bold text-[10px] uppercase">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                        Detalles
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                        <?php if (mysqli_num_rows($resList) == 0): ?>
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center">
                                <div class="inline-flex items-center justify-center size-16 bg-slate-50 text-slate-300 rounded-full mb-4">
                                    <span class="material-symbols-outlined text-3xl">inbox</span>
                                </div>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Aún no has registrado ningún proyecto.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>