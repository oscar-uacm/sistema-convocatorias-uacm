<?php
session_start();
require_once 'conexion.php';

// 1. SEGURIDAD: Solo el rol 'comite' puede acceder
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'comite') {
    header("Location: login.php");
    exit;
}

$mensaje = "";

// 2. LÓGICA DE ASIGNACIÓN (Cuando el comité presiona "Asignar")
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asignar_evaluador'])) {
    $proyecto_id = intval($_POST['proyecto_id']);
    $evaluador_id = intval($_POST['evaluador_id']);
    
    if ($evaluador_id > 0) {
        $stmt = $conexion->prepare("UPDATE proyectos SET evaluador_id = ?, estatus = 'En Evaluación' WHERE id = ?");
        $stmt->bind_param("ii", $evaluador_id, $proyecto_id);
        
        if ($stmt->execute()) {
            $mensaje = "Asignación exitosa. El proyecto ahora está en fase de evaluación.";
        } else {
            $mensaje = "Error al asignar: " . $conexion->error;
        }
    }
}

// 3. OBTENER LISTA DE EVALUADORES DISPONIBLES (Para los selectores)
$resEval = mysqli_query($conexion, "SELECT id, nombre FROM usuarios WHERE rol = 'evaluador' ORDER BY nombre ASC");
$evaluadores = mysqli_fetch_all($resEval, MYSQLI_ASSOC);

// 4. OBTENER PROYECTOS CON SUS RESPECTIVOS INVESTIGADORES Y EVALUADORES (SI TIENEN)
$queryProyectos = "SELECT p.id, p.folio, p.titulo, p.area, p.estatus, 
                          u.nombre as investigador, 
                          e.nombre as evaluador_nombre 
                   FROM proyectos p 
                   JOIN usuarios u ON p.usuario_id = u.id 
                   LEFT JOIN usuarios e ON p.evaluador_id = e.id 
                   ORDER BY p.id DESC";
$proyectos = mysqli_query($conexion, $queryProyectos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>UACM - Panel del Comité</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-[#fcfaf9] p-6 md:p-12">
    <div class="max-w-7xl mx-auto">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
            <div>
                <div class="flex items-center gap-2 text-primary mb-2">
                    <span class="material-symbols-outlined text-lg font-bold">gavel</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.3em]">Comisión de Investigación</span>
                </div>
                <h1 class="text-5xl font-black text-gray-900 tracking-tighter">Panel de Gestión Académica</h1>
            </div>
            <div class="flex items-center gap-4 bg-white p-3 rounded-3xl shadow-sm border border-gray-100">
                <div class="text-right px-4">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Sesión Activa</p>
                    <p class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
                </div>
                <a href="logout.php" class="size-12 flex items-center justify-center bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            </div>
        </header>

        <?php if ($mensaje): ?>
            <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 font-bold text-sm">
                <span class="material-symbols-outlined">check_circle</span> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-[3rem] shadow-xl shadow-primary/5 border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h2 class="font-black text-xl text-gray-800">Proyectos y Asignación de Pares</h2>
                <div class="flex gap-2">
                    <span class="px-4 py-1.5 bg-white border border-gray-200 rounded-full text-[10px] font-black uppercase text-gray-500 tracking-widest">Ciclo 2026</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] border-b border-gray-50">
                            <th class="px-8 py-6">Información del Proyecto</th>
                            <th class="px-8 py-6">Estado de Evaluación</th>
                            <th class="px-8 py-6 text-right">Asignar Dictaminador</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php while ($p = mysqli_fetch_assoc($proyectos)): ?>
                        <tr class="hover:bg-gray-50/40 transition-all group">
                            <td class="px-8 py-8">
                                <span class="text-primary font-black text-[10px] bg-primary/5 px-2 py-1 rounded mb-2 inline-block"><?php echo $p['folio']; ?></span>
                                <h3 class="font-bold text-gray-900 text-sm leading-tight mb-1"><?php echo $p['titulo']; ?></h3>
                                <p class="text-xs text-gray-400 italic">Responsable: <?php echo $p['investigador']; ?></p>
                            </td>
                            <td class="px-8 py-8">
                                <?php if ($p['evaluador_nombre']): ?>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1.5 text-green-600 mb-1">
                                            <span class="material-symbols-outlined text-sm">verified_user</span>
                                            <span class="text-[11px] font-black uppercase tracking-tight">Asignado</span>
                                        </div>
                                        <p class="text-xs font-bold text-gray-700"><?php echo $p['evaluador_nombre']; ?></p>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-1.5 text-orange-500">
                                        <span class="material-symbols-outlined text-sm">hourglass_empty</span>
                                        <span class="text-[11px] font-black uppercase tracking-tight italic">Pendiente de Par</span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-8">
                                <form method="POST" class="flex items-center justify-end gap-2">
                                    <input type="hidden" name="proyecto_id" value="<?php echo $p['id']; ?>">
                                    <select name="evaluador_id" required class="text-[10px] font-bold uppercase bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 p-3 outline-none">
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($evaluadores as $ev): ?>
                                            <option value="<?php echo $ev['id']; ?>" <?php echo ($p['evaluador_nombre'] == $ev['nombre']) ? 'disabled selected' : ''; ?>>
                                                <?php echo $ev['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="asignar_evaluador" class="bg-black text-white p-3 rounded-xl hover:bg-primary transition-all flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm">person_add</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>