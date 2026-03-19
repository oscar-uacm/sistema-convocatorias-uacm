<?php
// Aseguramos que la sesión esté disponible para leer el rol
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol_actual = $_SESSION['rol'] ?? 'investigador';
$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
?>
<aside class="w-72 bg-white min-h-screen border-r border-gray-100 flex flex-col sticky top-0">
    <div class="p-8">
        <a href="index.php" class="text-2xl font-black text-[#701705] italic tracking-tighter uppercase">
            UACM
        </a>
        <p class="text-[9px] font-bold text-gray-400 tracking-[0.2em] mt-1 uppercase">Investigación</p>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        
        <div class="pb-2 text-[10px] font-black text-gray-300 uppercase px-4 tracking-widest">Menú Principal</div>

        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all group">
            <span class="material-symbols-outlined text-gray-400 group-hover:text-primary">grid_view</span>
            Panel General
        </a>

        <?php if ($rol_actual === 'admin' || $rol_actual === 'evaluador'): ?>
            <div class="pt-6 pb-2 text-[10px] font-black text-gray-300 uppercase px-4 tracking-widest">Dictaminación</div>
            
            <a href="admin-proyectos.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all group">
                <span class="material-symbols-outlined text-gray-400 group-hover:text-primary">folder_special</span>
                Revisar Proyectos
            </a>
        <?php endif; ?>

        <?php if ($rol_actual === 'admin'): ?>
            <div class="pt-6 pb-2 text-[10px] font-black text-gray-300 uppercase px-4 tracking-widest">Sistema</div>
            
            <a href="admin-dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all group">
                <span class="material-symbols-outlined text-gray-400 group-hover:text-primary">monitoring</span>
                Métricas del Servidor
            </a>

            <a href="admin-usuarios.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-primary bg-primary/5 transition-all group">
                <span class="material-symbols-outlined">manage_accounts</span>
                Gestión de Usuarios
            </a>
        <?php endif; ?>

    </nav>

    <div class="p-6 border-t border-gray-50">
        <div class="bg-gray-50 rounded-2xl p-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-[#701705] flex items-center justify-center text-white text-xs font-bold">
                    <?php echo strtoupper(substr($nombre_usuario, 0, 1)); ?>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-800 line-clamp-1"><?php echo $nombre_usuario; ?></span>
                    <span class="text-[9px] font-bold text-primary uppercase tracking-tighter"><?php echo $rol_actual; ?></span>
                </div>
            </div>
            <a href="logout.php" class="flex items-center justify-center gap-2 w-full py-2 bg-white border border-red-100 text-red-600 rounded-xl text-[10px] font-bold uppercase hover:bg-red-50 transition-all">
                <span class="material-symbols-outlined text-sm">logout</span>
                Cerrar Sesión
            </a>
        </div>
    </div>
</aside>