<?php
session_start();
require_once 'conexion.php';

// 1. Seguridad básica: Debe estar logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$rol = $_SESSION['rol'];
$proyecto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($proyecto_id === 0) {
    die("ID de proyecto inválido.");
}

// 2. Consulta a la base de datos con PDO y Seguridad de Acceso
try {
    // Si es investigador, SOLO puede ver sus propios proyectos.
    // Si es admin, comite o evaluador, podría ver cualquier proyecto.
    if ($rol === 'investigador') {
        $sql = "SELECT * FROM proyectos WHERE id = ? AND usuario_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$proyecto_id, $user_id]);
    } else {
        $sql = "SELECT * FROM proyectos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$proyecto_id]);
    }

    $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$proyecto) {
        die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h2 style='color:#9c2007;'>Acceso Denegado</h2>
                <p>El proyecto no existe o no tienes permisos para visualizarlo.</p>
                <a href='dashboard.php' style='color:blue;'>Volver al inicio</a>
             </div>");
    }

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

// Función auxiliar para determinar el color del estatus
function colorEstatus($estatus) {
    switch ($estatus) {
        case 'Aprobado': return 'bg-green-100 text-green-700 border-green-200';
        case 'Con Cambios': return 'bg-amber-100 text-amber-700 border-amber-200';
        case 'No Aprobado': return 'bg-red-100 text-red-700 border-red-200';
        case 'En Evaluación': return 'bg-blue-100 text-blue-700 border-blue-200';
        default: return 'bg-gray-100 text-gray-700 border-gray-200'; // "Enviado"
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UACM - Detalles del Proyecto</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#701705", "background-light": "#f8f6f5" },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display text-slate-800 pb-20">
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">description</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Expediente del Proyecto</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary">Consulta de Detalles</p>
            </div>
        </div>
        <div class="flex gap-4">
            <?php 
                $ruta_volver = 'dashboard.php';
                if ($rol === 'admin') $ruta_volver = 'admin-proyectos.php';
                if ($rol === 'comite') $ruta_volver = 'comite-dashboard.php';
                if ($rol === 'evaluador') $ruta_volver = 'admin-proyectos.php';
            ?>
            <a href="<?php echo $ruta_volver; ?>" class="flex items-center gap-2 px-5 py-2 border-2 border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-all font-bold text-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Volver
            </a>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 rounded border text-[10px] font-black uppercase tracking-widest <?php echo colorEstatus($proyecto['estatus']); ?>">
                        ESTATUS: <?php echo htmlspecialchars($proyecto['estatus']); ?>
                    </span>
                    <span class="text-xs font-bold text-slate-400">Folio: <?php echo htmlspecialchars($proyecto['folio']); ?></span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 leading-tight"><?php echo htmlspecialchars($proyecto['titulo']); ?></h1>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Fecha de Envío</p>
                <p class="text-sm font-bold text-slate-700 flex items-center gap-1 justify-end">
                    <span class="material-symbols-outlined text-sm">calendar_month</span> 
                    <?php echo date('d / m / Y', strtotime($proyecto['fecha_creacion'])); ?>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">feed</span> Información General
                    </h3>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Área Académica</p>
                            <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($proyecto['area']); ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Duración</p>
                            <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($proyecto['duracion']); ?> </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Resumen Ejecutivo</p>
                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <?php echo nl2br(htmlspecialchars($proyecto['resumen'])); ?>
                        </p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600">payments</span> Finanzas y Cronograma
                    </h3>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50/50 p-4 rounded-xl border border-green-100">
                            <p class="text-[10px] font-bold text-green-600 uppercase mb-1">Monto Solicitado</p>
                            <p class="text-2xl font-black text-green-700">$<?php echo number_format($proyecto['monto'], 2); ?></p>
                        </div>
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                            <p class="text-[10px] font-bold text-blue-600 uppercase mb-1">Periodo de Ejecución</p>
                            <p class="text-sm font-bold text-blue-800 mt-2">
                                <?php echo date('d/m/Y', strtotime($proyecto['fecha_inicio'])); ?> <span class="text-blue-400 font-normal mx-1">al</span> <?php echo date('d/m/Y', strtotime($proyecto['fecha_fin'])); ?>
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">Justificación del Presupuesto</p>
                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <?php echo nl2br(htmlspecialchars($proyecto['justificacion'])); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                
                <div class="bg-slate-900 p-8 rounded-3xl shadow-lg border border-slate-800">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-white">folder_zip</span> Documentos
                    </h3>
                    <div class="space-y-4">
                        <?php if(!empty($proyecto['ruta_formato1'])): ?>
                            <a href="<?php echo htmlspecialchars($proyecto['ruta_formato1']); ?>" download class="flex items-center gap-3 bg-slate-800 p-4 rounded-xl border border-slate-700 hover:border-primary transition-colors group">
                                <span class="material-symbols-outlined text-blue-400 group-hover:text-primary">description</span>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-white">Formato I</p>
                                    <p class="text-[10px] text-slate-400">Descargar archivo</p>
                                </div>
                                <span class="material-symbols-outlined text-slate-500 group-hover:text-white">download</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if(!empty($proyecto['ruta_formato2'])): ?>
                            <a href="<?php echo htmlspecialchars($proyecto['ruta_formato2']); ?>" download class="flex items-center gap-3 bg-slate-800 p-4 rounded-xl border border-slate-700 hover:border-primary transition-colors group">
                                <span class="material-symbols-outlined text-blue-400 group-hover:text-primary">assignment</span>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-white">Formato II</p>
                                    <p class="text-[10px] text-slate-400">Descargar archivo</p>
                                </div>
                                <span class="material-symbols-outlined text-slate-500 group-hover:text-white">download</span>
                            </a>
                        <?php endif; ?>

                        <?php if(!empty($proyecto['ruta_formato3'])): ?>
                            <a href="<?php echo htmlspecialchars($proyecto['ruta_formato3']); ?>" download class="flex items-center gap-3 bg-slate-800 p-4 rounded-xl border border-slate-700 hover:border-primary transition-colors group">
                                <span class="material-symbols-outlined text-green-400 group-hover:text-primary">table_view</span>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-white">Formato III</p>
                                    <p class="text-[10px] text-slate-400">Descargar archivo</p>
                                </div>
                                <span class="material-symbols-outlined text-slate-500 group-hover:text-white">download</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">groups</span> Equipo de Trabajo
                    </h3>
                    
                    <?php if(!empty($proyecto['colaborador_nombre'])): ?>
                        <div class="mb-5 pb-5 border-b border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Colaborador Principal</p>
                            <p class="font-bold text-slate-800 text-sm mb-1"><?php echo htmlspecialchars($proyecto['colaborador_nombre']); ?></p>
                            <p class="text-xs text-slate-500">ID: <?php echo htmlspecialchars($proyecto['colaborador_id']); ?></p>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">account_balance</span> <?php echo htmlspecialchars($proyecto['colaborador_adscripcion']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if(!empty($proyecto['alumno_nombre'])): ?>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Alumno / Tesista</p>
                            <p class="font-bold text-slate-800 text-sm mb-1"><?php echo htmlspecialchars($proyecto['alumno_nombre']); ?></p>
                            <p class="text-xs text-slate-500">Matrícula: <?php echo htmlspecialchars($proyecto['alumno_matricula']); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(empty($proyecto['colaborador_nombre']) && empty($proyecto['alumno_nombre'])): ?>
                        <p class="text-sm text-slate-500 italic">No se registraron colaboradores para este proyecto.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>
</body>
</html>