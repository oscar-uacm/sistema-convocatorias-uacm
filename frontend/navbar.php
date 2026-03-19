<nav class="sticky top-0 z-50 w-full py-4 px-8 flex justify-between items-center bg-white/80 backdrop-blur-md border-b border-gray-100">
    <a href="index.php" class="flex items-center gap-2 text-primary font-black tracking-tighter uppercase text-xl italic hover:opacity-80 transition-all">
        <span class="material-symbols-outlined">home</span>
        UACM
    </a>
    
    <div class="flex items-center gap-6">
        <a href="convocatoria.php" class="text-xs font-bold hover:text-primary transition-colors uppercase tracking-widest">Convocatoria</a>
        <a href="faq.php" class="text-xs font-bold hover:text-primary transition-colors uppercase tracking-widest">FAQ</a>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <div class="flex items-center gap-4 pl-4 border-l border-gray-200">
                <div class="text-right">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Investigador</p>
                    <p class="text-xs font-bold text-slate-800"><?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
                </div>
                <a href="logout.php" class="flex items-center gap-1 text-red-600 hover:text-red-800 transition-colors">
                    <span class="material-symbols-outlined text-lg">logout</span>
                </a>
            </div>
        <?php else: ?>
            <a href="login.php" class="bg-primary text-white px-6 py-2 rounded-lg text-xs font-bold hover:bg-black transition-all">INICIAR SESIÓN</a>
        <?php endif; ?>
    </div>
</nav>