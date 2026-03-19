<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'evaluador')) {
    header("Location: login.php");
    exit;
}

$id_proyecto = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = intval($_SESSION['user_id']);
$rol = $_SESSION['rol'];
$mensaje = "";

if ($rol !== 'admin') {
    $check = mysqli_query($conexion, "SELECT id FROM proyectos WHERE id = $id_proyecto AND evaluador_id = $user_id");
    if (mysqli_num_rows($check) === 0) {
        header("Location: admin-proyectos.php?error=no_autorizado");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dictaminar'])) {
    $nuevo_estatus = mysqli_real_escape_string($conexion, $_POST['estatus']);
    $observaciones = mysqli_real_escape_string($conexion, $_POST['observaciones']);
    $sql_update = "UPDATE proyectos SET estatus = '$nuevo_estatus', observaciones = '$observaciones' WHERE id = $id_proyecto";
    if (mysqli_query($conexion, $sql_update)) { $mensaje = "Dictamen guardado correctamente."; }
}

$query = "SELECT p.*, u.nombre as autor FROM proyectos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = $id_proyecto";
$res = mysqli_query($conexion, $query);
$p = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>UACM - Dictamen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen p-6 md:p-12">
    <main class="max-w-5xl mx-auto">
        <a href="admin-proyectos.php" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors font-black text-[10px] uppercase tracking-widest mb-8">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Volver
        </a>

        <div class="bg-white rounded-[3rem] shadow-xl p-10">
            <h2 class="text-3xl font-black text-gray-900 mb-8">Dictamen Académico</h2>
            <?php if ($mensaje): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-2xl mb-8 font-bold"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-4">Estatus del Proyecto</label>
                    <select name="estatus" class="w-full p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="Aprobado" <?php if($p['estatus'] == 'Aprobado') echo 'selected'; ?>>Aprobado</option>
                        <option value="Con Cambios" <?php if($p['estatus'] == 'Con Cambios') echo 'selected'; ?>>Con Cambios</option>
                        <option value="No Aprobado" <?php if($p['estatus'] == 'No Aprobado') echo 'selected'; ?>>No Aprobado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-4">Observaciones</label>
                    <textarea name="observaciones" rows="6" class="w-full p-6 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-primary/20"><?php echo htmlspecialchars($p['observaciones'] ?? ''); ?></textarea>
                </div>
                <button type="submit" name="dictaminar" class="w-full py-5 bg-black text-white rounded-2xl font-black uppercase tracking-widest hover:bg-primary transition-all">Guardar Dictamen</button>
            </form>
        </div>
    </main>
</body>
</html>