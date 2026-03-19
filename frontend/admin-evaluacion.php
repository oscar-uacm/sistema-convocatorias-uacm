<?php
session_start();
require_once 'conexion.php'; // Tu archivo de conexión que usa $conexion (mysqli)

// 1. Seguridad: Solo admin y evaluador pueden entrar
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'evaluador')) {
    header("Location: login.php");
    exit;
}

$id_proyecto = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id_proyecto']) ? intval($_POST['id_proyecto']) : 0);
$user_id = intval($_SESSION['user_id']);
$rol = $_SESSION['rol'];
$mensaje_exito = "";

if ($id_proyecto === 0) {
    die("ID de proyecto inválido.");
}

// 2. Seguridad: Si es evaluador, solo puede ver los proyectos que le fueron asignados
if ($rol !== 'admin') {
    $check_query = "SELECT id FROM proyectos WHERE id = $id_proyecto AND evaluador_id = $user_id";
    $check_result = mysqli_query($conexion, $check_query);
    if (mysqli_num_rows($check_result) === 0) {
        header("Location: admin-proyectos.php?error=no_autorizado");
        exit;
    }
}

// 3. Procesar el formulario de dictamen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dictaminar'])) {
    $nuevo_estatus = mysqli_real_escape_string($conexion, $_POST['estatus']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    
    $sql_update = "UPDATE proyectos SET estatus = '$nuevo_estatus', observaciones = '$observaciones' WHERE id = $id_proyecto";
    if (mysqli_query($conexion, $sql_update)) { 
        $mensaje_exito = "El dictamen ha sido guardado exitosamente."; 
    } else {
        die("Error al guardar: " . mysqli_error($conexion));
    }
}

// 4. Obtener todos los datos del proyecto para mostrarlos en pantalla
$query = "SELECT p.*, u.nombre as autor, c.titulo as nombre_convocatoria FROM proyectos p JOIN usuarios u ON p.usuario_id = u.id LEFT JOIN convocatorias c ON p.convocatoria_id = c.id WHERE p.id = $id_proyecto";
$res = mysqli_query($conexion, $query);
$proyecto = mysqli_fetch_assoc($res);

if (!$proyecto) {
    die("El proyecto no existe.");
}

// Función auxiliar para colores del estatus
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
    <title>UACM - Panel de Evaluación</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
            <div class="size-10 bg-slate-800 text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">gavel</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Módulo de Evaluación</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Dictamen Académico</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="admin-proyectos.php" class="flex items-center gap-2 px-5 py-2 border-2 border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-all font-bold text-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Volver al Listado
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        
        <?php if ($mensaje_exito): ?>
        <div class="mb-8 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined">check_circle</span>
            <p class="font-bold text-sm"><?php echo $mensaje_exito; ?></p>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded border text-[10px] font-black uppercase tracking-widest <?php echo colorEstatus($proyecto['estatus']); ?>">
                            <?php echo htmlspecialchars($proyecto['estatus']); ?>
                        </span>
                        <span class="text-xs font-bold text-slate-400">Folio: <?php echo htmlspecialchars($proyecto['folio']); ?></span>
                        <span class="px-3 py-1 rounded border text-[10px] font-black uppercase tracking-widest bg-purple-100 text-purple-700 border-purple-200 ml-2">
                            <?php echo htmlspecialchars($proyecto['nombre_convocatoria'] ?? 'Registro Libre'); ?>
                        </span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 leading-tight mb-2"><?php echo htmlspecialchars($proyecto['titulo']); ?></h1>
                    <p class="text-sm font-bold text-primary mb-6">Investigador: <?php echo htmlspecialchars($proyecto['autor']); ?></p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Área Académica</p>
                            <p class="font-semibold text-sm"><?php echo htmlspecialchars($proyecto['area'] ?? 'No definida'); ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Duración</p>
                            <p class="font-semibold text-sm"><?php echo htmlspecialchars($proyecto['duracion'] ?? '0'); ?> </p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-green-600 uppercase">Monto Solicitado</p>
                            <p class="font-black text-green-700 text-lg">$<?php echo number_format($proyecto['monto'] ?? 0, 2); ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">Resumen Ejecutivo</h3>
                    <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
                        <?php echo nl2br(htmlspecialchars($proyecto['resumen'] ?? 'Sin resumen capturado.')); ?>
                    </p>
                    
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4">Justificación de Presupuesto</h3>
                    <p class="text-sm text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <?php echo nl2br(htmlspecialchars($proyecto['justificacion'] ?? 'Sin justificación capturada.')); ?>
                    </p>
                </div>
            </div>

            <div class="space-y-6 sticky top-24">
                
                <div class="bg-slate-900 p-6 rounded-3xl shadow-lg border border-slate-800">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-white">download</span> Archivos Adjuntos
                    </h3>
                    <div class="space-y-3">
                        <?php 
                        $archivos = [
                            ['Formato I - Registro', $proyecto['ruta_formato1'] ?? '', 'description', 'text-blue-400'],
                            ['Formato II - Protocolo', $proyecto['ruta_formato2'] ?? '', 'assignment', 'text-blue-400'],
                            ['Formato III - Presupuesto', $proyecto['ruta_formato3'] ?? '', 'table_view', 'text-green-400']
                        ];
                        $hay_archivos = false;
                        foreach($archivos as $archivo):
                            if(!empty($archivo[1])):
                                $hay_archivos = true;
                        ?>
                            <a href="<?php echo htmlspecialchars($archivo[1]); ?>" download class="flex items-center gap-3 bg-slate-800 p-3 rounded-xl border border-slate-700 hover:border-primary transition-colors group">
                                <span class="material-symbols-outlined <?php echo $archivo[3]; ?> group-hover:text-primary"><?php echo $archivo[2]; ?></span>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-white"><?php echo $archivo[0]; ?></p>
                                </div>
                                <span class="material-symbols-outlined text-slate-500 group-hover:text-white text-sm">download</span>
                            </a>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hay_archivos) {
                            echo '<p class="text-xs text-slate-500 italic">El investigador no adjuntó archivos o no están disponibles.</p>';
                        }
                        ?>
                    </div>
                </div>

                <form method="POST" action="admin-evaluacion.php" class="bg-white p-6 rounded-3xl shadow-sm border-2 border-primary/20">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">edit_document</span> Dictamen
                    </h3>
                    
                    <input type="hidden" name="id_proyecto" value="<?php echo $id_proyecto; ?>">

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Resolución</label>
                        <select name="estatus" required class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm font-bold p-3">
                            <option value="En Evaluación" <?php echo ($proyecto['estatus'] == 'En Evaluación') ? 'selected' : ''; ?>>En Evaluación (Pendiente)</option>
                            <option value="Aprobado" <?php echo ($proyecto['estatus'] == 'Aprobado') ? 'selected' : ''; ?>>Aprobado</option>
                            <option value="Con Cambios" <?php echo ($proyecto['estatus'] == 'Con Cambios') ? 'selected' : ''; ?>>Aprobado Con Cambios</option>
                            <option value="No Aprobado" <?php echo ($proyecto['estatus'] == 'No Aprobado') ? 'selected' : ''; ?>>No Aprobado</option>
                            <option value="Enviado" <?php echo ($proyecto['estatus'] == 'Enviado') ? 'selected' : ''; ?>>Enviado (Inicial)</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Observaciones / Retroalimentación</label>
                        <textarea name="observaciones" rows="5" placeholder="Escriba los comentarios que verá el investigador..." class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm p-3 resize-none"><?php echo htmlspecialchars($proyecto['observaciones'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" name="dictaminar" class="w-full py-3 rounded-xl bg-primary text-white font-black hover:bg-black transition-all shadow-lg shadow-primary/20 uppercase tracking-widest text-sm flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined">save</span> Guardar Dictamen
                    </button>
                </form>

            </div>
        </div>
    </main>
</body>
</html>