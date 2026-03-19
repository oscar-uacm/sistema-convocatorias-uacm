<?php
session_start();
require_once 'conexion.php';

// 1. SEGURIDAD: Solo Admin o Evaluador pueden ver esta lista
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'evaluador')) {
    header("Location: dashboard.php");
    exit;
}

// 2. CONSULTA MEJORADA: Traemos el nombre del investigador y los datos del proyecto
$sql = "SELECT p.id, p.folio, p.titulo, p.area, p.estatus, p.fecha_creacion, u.nombre as investigador 
        FROM proyectos p 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.id DESC";
$res = mysqli_query($conexion, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UACM - Revisión de Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-[#fcfaf9] flex min-h-screen">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 p-8 md:p-12">
        <header class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight uppercase italic">Revisión de Proyectos</h1>
                <p class="text-gray-500 text-sm">Bandeja de entrada para dictaminación técnica y académica.</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-primary uppercase tracking-widest bg-primary/5 px-4 py-2 rounded-full border border-primary/10">
                    Modo: <?php echo ucfirst($_SESSION['rol']); ?>
                </span>
            </div>
        </header>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Folio y Título</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Investigador</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Estatus</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php while ($proyecto = mysqli_fetch_assoc($res)): 
                            // Configuración de colores según el estatus
                            $estatus = $proyecto['estatus'];
                            $badge_class = "bg-gray-100 text-gray-500";
                            if ($estatus == 'Enviado') $badge_class = "bg-blue-50 text-blue-600 border border-blue-100";
                            if ($estatus == 'Aprobado') $badge_class = "bg-green-50 text-green-700 border border-green-100";
                            if ($estatus == 'Con Cambios') $badge_class = "bg-amber-50 text-amber-700 border border-amber-100";
                        ?>
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-tighter mb-1"><?php echo $proyecto['folio']; ?></span>
                                    <span class="text-sm font-bold text-gray-800 line-clamp-1 italic"><?php echo htmlspecialchars($proyecto['titulo']); ?></span>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase mt-1"><?php echo $proyecto['area']; ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-gray-600"><?php echo htmlspecialchars($proyecto['investigador']); ?></span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider <?php echo $badge_class; ?>">
                                    <?php echo $estatus; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="admin-evaluacion.php?id=<?php echo $proyecto['id']; ?>" 
                                   class="inline-flex items-center gap-2 bg-black text-white text-[10px] font-black px-5 py-2.5 rounded-xl hover:bg-primary transition-all uppercase tracking-widest shadow-sm">
                                    <span class="material-symbols-outlined text-sm">rate_review</span>
                                    Dictaminar
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>

                        <?php if (mysqli_num_rows($res) == 0): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <span class="material-symbols-outlined text-4xl text-gray-200">folder_open</span>
                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">No hay proyectos pendientes de revisión</p>
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