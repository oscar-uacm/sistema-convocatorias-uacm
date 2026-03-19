<?php
session_start();
require_once 'conexion.php';

// 1. SEGURIDAD: Validar permisos
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'evaluador')) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

// 2. PROCESAR EL DICTAMEN (Cuando se envía el formulario)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dictaminar'])) {
    $nuevo_estatus = mysqli_real_escape_string($conexion, $_POST['estatus']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    $proyecto_id = intval($_POST['proyecto_id']);

    $sql_update = "UPDATE proyectos SET estatus = '$nuevo_estatus', observaciones = '$observaciones' WHERE id = $proyecto_id";
    
    if (mysqli_query($conexion, $sql_update)) {
        $mensaje = "Dictamen guardado correctamente.";
        // Refrescamos los datos del proyecto para mostrar los cambios
    } else {
        $mensaje = "Error al guardar el dictamen: " . mysqli_error($conexion);
    }
}

// 3. OBTENER DATOS DEL PROYECTO
$query = "SELECT p.*, u.nombre as autor, u.correo FROM proyectos p 
          JOIN usuarios u ON p.usuario_id = u.id 
          WHERE p.id = $id";
$res = mysqli_query($conexion, $query);
$p = mysqli_fetch_assoc($res);

if (!$p) {
    header("Location: admin-proyectos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>UACM - Dictaminación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-[#fcfaf9] flex min-h-screen">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 p-8 md:p-12">
        <header class="mb-10 flex justify-between items-center">
            <a href="admin-proyectos.php" class="flex items-center gap-2 text-gray-400 hover:text-primary transition-all text-xs font-bold uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">arrow_back</span> Volver a la lista
            </a>
            <span class="text-[10px] font-black bg-gray-100 px-4 py-2 rounded-full uppercase tracking-widest text-gray-500">
                Folio: <?php echo $p['folio']; ?>
            </span>
        </header>

        <?php if ($mensaje): ?>
            <div class="mb-8 p-4 bg-green-50 border border-green-100 text-green-700 text-xs font-bold rounded-2xl flex items-center gap-2 uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">check_circle</span> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <h2 class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-4">Detalles del Proyecto</h2>
                    <h3 class="text-2xl font-black text-gray-800 mb-6 italic"><?php echo htmlspecialchars($p['titulo']); ?></h3>
                    
                    <div class="grid grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-gray-400 font-bold text-[10px] uppercase">Investigador</p>
                            <p class="text-gray-700 font-bold"><?php echo htmlspecialchars($p['autor']); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 font-bold text-[10px] uppercase">Área de Conocimiento</p>
                            <p class="text-gray-700 font-bold"><?php echo $p['area']; ?></p>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <p class="text-gray-400 font-bold text-[10px] uppercase mb-4 text-center">Documentación Enviada</p>
                        <a href="generar-pdf.php?folio=<?php echo $p['folio']; ?>" target="_blank" class="flex items-center justify-center gap-3 w-full py-4 bg-gray-50 border border-dashed border-gray-200 rounded-2xl text-gray-500 hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all font-bold text-xs uppercase tracking-widest">
                            <span class="material-symbols-outlined">picture_as_pdf</span> Ver Archivo PDF del Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 sticky top-8">
                    <h2 class="text-[10px] font-black text-gray-800 uppercase tracking-[0.2em] mb-6">Panel de Dictaminación</h2>
                    
                    <form action="" method="POST" class="space-y-6">
                        <input type="hidden" name="proyecto_id" value="<?php echo $p['id']; ?>">
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Resultado</label>
                            <select name="estatus" required class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-xs font-bold uppercase tracking-widest focus:bg-white focus:ring-2 focus:ring-primary/10 outline-none transition-all">
                                <option value="Enviado" <?php echo $p['estatus'] == 'Enviado' ? 'selected' : ''; ?>>En Revisión</option>
                                <option value="Aprobado" <?php echo $p['estatus'] == 'Aprobado' ? 'selected' : ''; ?>>Aprobar Proyecto</option>
                                <option value="Con Cambios" <?php echo $p['estatus'] == 'Con Cambios' ? 'selected' : ''; ?>>Solicitar Cambios</option>
                                <option value="No Aprobado" <?php echo $p['estatus'] == 'No Aprobado' ? 'selected' : ''; ?>>Rechazar</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Observaciones / Retroalimentación</label>
                            <textarea name="observaciones" rows="5" class="w-full px-5 py-4 bg-gray-50 border-transparent rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-primary/10 outline-none transition-all placeholder:text-gray-300" placeholder="Escribe aquí los motivos del dictamen..."><?php echo htmlspecialchars($p['observaciones'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" name="dictaminar" class="w-full py-4 bg-black text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg hover:bg-primary transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">save</span> Guardar Dictamen
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>
</body>
</html>