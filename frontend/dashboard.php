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

// 2. OBTENER ESTADÍSTICAS (Esto es lo que se pudo haber perdido)
// Contamos los proyectos del usuario actual filtrando por su ID
$queryStats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estatus = 'Enviado' THEN 1 ELSE 0 END) as enviados,
    SUM(CASE WHEN estatus = 'Aprobado' THEN 1 ELSE 0 END) as aprobados
    FROM proyectos WHERE usuario_id = '$user_id'";

$resStats = mysqli_query($conexion, $queryStats);
$stats = mysqli_fetch_assoc($resStats);

// 3. OBTENER LISTADO DE PROYECTOS (Asegurando traer el Folio para el PDF)
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
    <title>UACM - Mi Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-[#f8f6f5] min-h-screen flex">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 p-8 md:p-12">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight uppercase italic">Mi Panel</h1>
                <p class="text-gray-500 text-sm">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></p>
            </div>

            <?php if ($rol_actual === 'investigador'): ?>
                <a href="registro.php" class="bg-[#701705] text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg hover:bg-black transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span> Nuevo Registro
                </a>
            <?php endif; ?>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Registrados</p>
                <h3 class="text-3xl font-black"><?php echo $stats['total'] ?? 0; ?></h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 border-l-4 border-l-blue-400">
                <p class="text-blue-500 text-[10px] font-black uppercase tracking-widest mb-1">En Revisión</p>
                <h3 class="text-3xl font-black"><?php echo (int)$stats['enviados']; ?></h3>
            </div>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 border-l-4 border-l-green-500">
                <p class="text-green-500 text-[10px] font-black uppercase tracking-widest mb-1">Aprobados</p>
                <h3 class="text-3xl font-black"><?php echo (int)$stats['aprobados']; ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <h2 class="font-black text-gray-800 text-lg uppercase italic">Mis Proyectos Registrados</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Información</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Estatus</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php while ($fila = mysqli_fetch_assoc($resList)): ?>
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-primary uppercase tracking-tighter"><?php echo $fila['folio']; ?></span>
                                    <span class="text-sm font-bold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($fila['titulo']); ?></span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase"><?php echo $fila['area']; ?></span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-gray-100 text-gray-500">
                                    <?php echo $fila['estatus']; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="generar-pdf.php?folio=<?php echo $fila['folio']; ?>" target="_blank" 
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
                                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Aún no has registrado ningún proyecto.</p>
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