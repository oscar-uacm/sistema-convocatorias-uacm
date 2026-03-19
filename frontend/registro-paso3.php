<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$datos_paso3 = $_SESSION['proyecto_paso3'] ?? [];
$nombre_investigador = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM Registro - Paso 3: Presupuesto y Cronograma</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#701705", "secondary-red": "#9C2007", "background-light": "#f8f6f5" },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display text-slate-800">
    <main class="max-w-4xl mx-auto px-6 py-12">
        
        <div class="mb-8">
            <a href="index.php" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">home</span>
                Abandonar registro y volver al inicio
            </a>
        </div>

        <header class="mb-10 border-b border-slate-200 pb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tight uppercase">Paso 3: Recursos y Tiempo</h1>
                <p class="text-slate-500 font-medium">Defina el presupuesto solicitado y el periodo de ejecución.</p>
            </div>
            <span class="text-5xl font-black text-primary/5 italic">03</span>
        </header>

        <form action="procesar-paso3.php" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-primary mb-6 flex items-center gap-2 italic">Presupuesto Estimado</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Monto Solicitado (MXN)</label>
                            <input type="number" name="monto" value="<?php echo htmlspecialchars($datos_paso3['monto'] ?? ''); ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20 text-lg font-bold text-primary" placeholder="0.00" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Justificación Breve</label>
                            <textarea name="justificacion" rows="4" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20 text-sm" 
                                placeholder="¿En qué se utilizarán los recursos?"><?php echo htmlspecialchars($datos_paso3['justificacion'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-primary mb-6 flex items-center gap-2 italic">Cronograma</h3>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($datos_paso3['fecha_inicio'] ?? ''); ?>" required
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Fecha de Término</label>
                                <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($datos_paso3['fecha_fin'] ?? ''); ?>" required
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-12 pt-8 border-t border-slate-100">
                <a href="registro-paso2.php" class="flex items-center gap-2 px-8 py-3 rounded-lg border-2 border-primary text-primary font-bold hover:bg-primary/5 transition-all text-sm">
                    <span class="material-symbols-outlined">arrow_back</span> Anterior
                </a>
                <button type="submit" class="flex items-center gap-2 px-10 py-3 rounded-lg bg-primary text-white font-bold shadow-lg shadow-primary/20 hover:bg-secondary-red transition-all text-sm">
                    Siguiente <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </form>
    </main>
</body>
</html>