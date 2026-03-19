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

// 2. LÓGICA DE CONSULTA SEGÚN ROL
if ($rol === 'admin') {
    $sql = "SELECT p.id, p.folio, p.titulo, p.area, p.estatus, p.fecha_creacion, u.nombre as investigador 
            FROM proyectos p 
            JOIN usuarios u ON p.usuario_id = u.id 
            ORDER BY p.id DESC";
} else {
    $sql = "SELECT p.id, p.folio, p.titulo, p.area, p.estatus, p.fecha_creacion, u.nombre as investigador 
            FROM proyectos p 
            JOIN usuarios u ON p.usuario_id = u.id 
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
    <title>UACM - <?php echo ($rol === 'admin') ? 'Administración' : 'Dictaminación'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="<?php echo ($rol === 'evaluador') ? 'bg-slate-50' : 'bg-[#fcfaf9]'; ?> p-6 md:p-12">

    <main class="max-w-6xl mx-auto">
        
        <?php if ($rol === 'evaluador'): ?>
            <header class="mb-10 bg-slate-900 text-white p-10 rounded-[3rem] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 text-primary mb-4">
                            <span class="material-symbols-outlined font-bold">visibility_off</span>
                            <span class="text-[10px] font-black uppercase tracking-[0.4em]">Proceso Doble Ciego Activo</span>
                        </div>
                        <h1 class="text-4xl font-black tracking-tighter">Panel de Dictaminación</h1>
                        <p class="text-slate-400 text-sm mt-2 max-w-xl italic">
                            Estimado(a) <?php echo $nombre_usuario; ?>, su evaluación garantiza la excelencia científica.
                        </p>
                    </div>

                    <div class="flex items-center gap-4 bg-white/5 p-2 pr-6 rounded-2xl border border-white/10">
                        <a href="logout.php" class="size-12 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-lg shadow-red-500/10">
                            <span class="material-symbols-outlined">logout</span>
                        </a>
                        <div class="hidden md:block">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Cerrar Sesión</p>
                            <p class="text-xs font-bold text-slate-200">Salir</p>
                        </div>
                    </div>
                </div>
            </header>
        <?php else: ?>
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">Consola de Proyectos</h1>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Gestión Global</p>
                </div>
                <div class="flex items-center gap-4 bg-white p-3 rounded-3xl shadow-sm border border-gray-100">
                    <a href="logout.php" class="size-12 flex items-center justify-center bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                        <span class="material-symbols-outlined">logout</span>
                    </a>
                </div>
            </header>
        <?php endif; ?>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">
                        <th class="px-8 py-6">Propuesta</th>
                        <th class="px-8 py-6">Área</th>
                        <th class="px-8 py-6">Estatus</th>
                        <th class="px-8 py-6 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while ($p = mysqli_fetch_assoc($res)): ?>
                    <tr class="hover:bg-slate-50/50 transition-all">
                        <td class="px-8 py-8">
                            <span class="text-primary font-black text-[9px] mb-1 block"><?php echo $p['folio']; ?></span>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight"><?php echo $p['titulo']; ?></h3>
                            <?php if($rol === 'admin'): ?>
                                <p class="text-[10px] text-gray-400 mt-1 italic">Autor: <?php echo $p['investigador']; ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-8 text-[10px] font-bold text-gray-600 uppercase"><?php echo $p['area']; ?></td>
                        <td class="px-8 py-8">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-gray-100 text-gray-600">
                                <?php echo $p['estatus']; ?>
                            </span>
                        </td>
                        <td class="px-8 py-8 text-right">
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