<?php
session_start();
require_once 'conexion.php';

// Seguridad: Solo el administrador puede gestionar convocatorias
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$mensaje_exito = "";

// 1. Procesar el formulario para crear una NUEVA convocatoria
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_convocatoria'])) {
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $fecha_apertura = mysqli_real_escape_string($conexion, $_POST['fecha_apertura']);
    $fecha_cierre = mysqli_real_escape_string($conexion, $_POST['fecha_cierre']);
    
    // --- LÓGICA DE SUBIDA DE PLANTILLAS ---
    $dir_plantillas = 'uploads/convocatorias/';
    if (!is_dir($dir_plantillas)) {
        mkdir($dir_plantillas, 0755, true);
    }

    function subirPlantilla($input_name, $dir) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            $extension = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
            // Generar un nombre único para evitar que se sobreescriban
            $nombre_seguro = time() . '_' . rand(100, 999) . '_' . $input_name . '.' . $extension;
            $ruta_destino = $dir . $nombre_seguro;
            
            if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $ruta_destino)) {
                return $ruta_destino;
            }
        }
        return NULL;
    }

    $ruta1 = subirPlantilla('plantilla1', $dir_plantillas);
    $ruta2 = subirPlantilla('plantilla2', $dir_plantillas);
    $ruta3 = subirPlantilla('plantilla3', $dir_plantillas);

    // Preparar las variables para SQL (Si es NULL, se inserta como NULL, sino como string)
    $p1 = $ruta1 ? "'$ruta1'" : "NULL";
    $p2 = $ruta2 ? "'$ruta2'" : "NULL";
    $p3 = $ruta3 ? "'$ruta3'" : "NULL";

    // Insertar todo en la base de datos
    $sql_insert = "INSERT INTO convocatorias (titulo, descripcion, fecha_apertura, fecha_cierre, estatus, ruta_plantilla1, ruta_plantilla2, ruta_plantilla3) 
                   VALUES ('$titulo', '$descripcion', '$fecha_apertura', '$fecha_cierre', 'Abierta', $p1, $p2, $p3)";
                   
    if (mysqli_query($conexion, $sql_insert)) {
        $mensaje_exito = "¡La convocatoria '$titulo' y sus formatos se publicaron exitosamente!";
    } else {
        $error = mysqli_error($conexion);
    }
}

// 2. Cambiar el estatus de una convocatoria (Abrir/Cerrar)
if (isset($_GET['cambiar_estatus']) && isset($_GET['id'])) {
    $id_conv = intval($_GET['id']);
    $nuevo_estatus = mysqli_real_escape_string($conexion, $_GET['cambiar_estatus']);
    mysqli_query($conexion, "UPDATE convocatorias SET estatus = '$nuevo_estatus' WHERE id = $id_conv");
    header("Location: admin-convocatorias.php");
    exit;
}

// 3. Obtener todas las convocatorias para mostrarlas en la tabla
$query_convocatorias = "SELECT * FROM convocatorias ORDER BY id DESC";
$resultado_convocatorias = mysqli_query($conexion, $query_convocatorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UACM - Gestión de Convocatorias</title>
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
            <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">campaign</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Gestor de Convocatorias</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Panel de Administrador</p>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="admin-dashboard.php" class="flex items-center gap-2 px-5 py-2 border-2 border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-all font-bold text-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Volver al Inicio
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
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border-2 border-primary/20 sticky top-24">
                <h3 class="text-sm font-black text-primary uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined">add_circle</span> Aperturar Convocatoria
                </h3>
                
                <form method="POST" action="admin-convocatorias.php" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nombre / Ciclo</label>
                        <input type="text" name="titulo" required placeholder="Ej: Convocatoria PI 2024-II" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm p-3">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Descripción Corta</label>
                        <textarea name="descripcion" rows="3" placeholder="Detalles u objetivos de esta convocatoria..." class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm p-3 resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Apertura</label>
                            <input type="date" name="fecha_apertura" required class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm p-3">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Cierre</label>
                            <input type="date" name="fecha_cierre" required class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary text-sm p-3">
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 space-y-3">
                        <label class="block text-[10px] font-black text-primary uppercase tracking-widest flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">attach_file</span> Plantillas Oficiales (Opcional)
                        </label>
                        
                        <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                            <p class="text-[10px] font-bold text-blue-800 mb-1">Formato I (Registro)</p>
                            <input type="file" name="plantilla1" accept=".pdf,.docx" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer">
                        </div>
                        
                        <div class="bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                            <p class="text-[10px] font-bold text-amber-800 mb-1">Formato II (Protocolo)</p>
                            <input type="file" name="plantilla2" accept=".pdf,.docx" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                        </div>

                        <div class="bg-green-50/50 p-3 rounded-xl border border-green-100">
                            <p class="text-[10px] font-bold text-green-800 mb-1">Formato III (Presupuesto)</p>
                            <input type="file" name="plantilla3" accept=".pdf,.xlsx" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 cursor-pointer">
                        </div>
                    </div>

                    <button type="submit" name="crear_convocatoria" class="w-full mt-4 py-3 rounded-xl bg-primary text-white font-black hover:bg-black transition-all shadow-lg shadow-primary/20 uppercase tracking-widest text-sm flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined">campaign</span> Publicar Convocatoria
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">view_list</span> Historial de Convocatorias
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400">
                                <tr>
                                    <th class="px-6 py-4">Convocatoria</th>
                                    <th class="px-6 py-4">Periodo</th>
                                    <th class="px-6 py-4 text-center">Estatus</th>
                                    <th class="px-6 py-4 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php 
                                if (mysqli_num_rows($resultado_convocatorias) > 0) {
                                    while($conv = mysqli_fetch_assoc($resultado_convocatorias)) { 
                                        $es_abierta = ($conv['estatus'] == 'Abierta');
                                ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-800"><?php echo htmlspecialchars($conv['titulo']); ?></p>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold">
                                        <span class="text-green-600"><?php echo date('d/m/y', strtotime($conv['fecha_apertura'])); ?></span> - 
                                        <span class="text-red-600"><?php echo date('d/m/y', strtotime($conv['fecha_cierre'])); ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if($es_abierta): ?>
                                            <span class="px-3 py-1 bg-green-100 text-green-700 border border-green-200 rounded text-[10px] font-black uppercase tracking-widest">Activa</span>
                                        <?php else: ?>
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 border border-gray-200 rounded text-[10px] font-black uppercase tracking-widest">Cerrada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <?php if($es_abierta): ?>
                                            <a href="admin-convocatorias.php?cambiar_estatus=Cerrada&id=<?php echo $conv['id']; ?>" class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 px-3 py-2 rounded-lg transition-colors inline-block">Cerrar</a>
                                        <?php else: ?>
                                            <a href="admin-convocatorias.php?cambiar_estatus=Abierta&id=<?php echo $conv['id']; ?>" class="text-xs font-bold text-green-600 hover:text-green-800 bg-green-50 px-3 py-2 rounded-lg transition-colors inline-block">Reabrir</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 italic">No hay convocatorias registradas aún. ¡Crea la primera!</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>