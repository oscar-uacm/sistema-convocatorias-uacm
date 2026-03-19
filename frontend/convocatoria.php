<?php
session_start();
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = $logueado ? $_SESSION['nombre'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Documentación y Convocatorias</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#9c2007",
                        "primary-dark": "#701705",
                        "background-light": "#f8f6f5",
                    },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display text-slate-900">
    <nav class="sticky top-0 z-50 w-full py-4 px-8 flex justify-between items-center bg-white/80 backdrop-blur-md border-b border-gray-100">
        <a href="index.php" class="text-primary-dark font-black tracking-tighter uppercase text-xl italic">UACM</a>
        <div class="flex items-center gap-6">
            <a href="faq.php" class="text-sm font-semibold hover:text-primary transition-colors">FAQ</a>
            <a href="guias-usuario.php" class="text-sm font-semibold hover:text-primary transition-colors">Guías</a>
            <?php if($logueado): ?>
                <div class="flex items-center gap-4 pl-4 border-l border-gray-200">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                    <a href="dashboard.php" class="bg-primary text-white px-5 py-2 rounded-lg text-xs font-bold hover:bg-primary-dark transition-all">MI PANEL</a>
                </div>
            <?php else: ?>
                <a href="login.php" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">INICIAR SESIÓN</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-8">
                <header>
                    <span class="text-primary font-bold uppercase tracking-[0.3em] text-xs">Ciclo Escolar 2026</span>
                    <h1 class="text-5xl font-black text-primary-dark tracking-tight mt-2">Convocatoria de Financiamiento</h1>
                    <p class="text-xl text-slate-500 mt-4 leading-relaxed">Apoyo a proyectos de investigación del Colegio de Ciencias y Humanidades.</p>
                </header>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                    <section>
                        <h3 class="text-lg font-bold mb-3 flex items-center gap-2 text-primary">
                            <span class="material-symbols-outlined">gavel</span> Bases y Requisitos
                        </h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Podrán participar todos los docentes e investigadores con adscripción vigente, presentando propuestas que impacten directamente en la formación académica de la comunidad universitaria...
                        </p>
                    </section>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-primary-dark text-white p-8 rounded-[2rem] shadow-2xl">
                    <h3 class="font-bold text-xl mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">folder_open</span> Documentación
                    </h3>
                    <div class="space-y-3">
                        <a href="ConvocatoriaFinanciamiento/FORMATO%20I%20CONVOCATORIA%20INTERNA%202026.pdf" target="_blank" class="flex items-center justify-between p-4 bg-white/10 rounded-xl hover:bg-white/20 transition-all group">
                            <span class="text-sm font-medium">Formato I (PDF)</span>
                            <span class="material-symbols-outlined text-sm group-hover:translate-y-1 transition-transform">download</span>
                        </a>
                        <a href="ConvocatoriaFinanciamiento/FORMATO%20II%20CONVOCATORIA%20INTERNA%202026.docx" target="_blank" class="flex items-center justify-between p-4 bg-white/10 rounded-xl hover:bg-white/20 transition-all group">
                            <span class="text-sm font-medium">Formato II (DOCX)</span>
                            <span class="material-symbols-outlined text-sm group-hover:translate-y-1 transition-transform">download</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</body>
</html>