<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$p1 = $_SESSION['proyecto_paso1'] ?? [];
$p2 = $_SESSION['proyecto_paso2'] ?? [];
$p3 = $_SESSION['proyecto_paso3'] ?? [];
$nombre_investigador = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Paso 4: Revisión Final</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#701705", "background-light": "#f8f6f5" },
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

        <form action="finalizar-registro.php" method="POST" class="space-y-6">
            <header class="bg-primary p-10 rounded-[2.5rem] text-white shadow-2xl shadow-primary/20 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-white/60 text-[10px] font-black uppercase tracking-[0.3em] mb-2">Paso Final</p>
                    <h1 class="text-3xl font-black italic">Revisión y Envío</h1>
                </div>
                <span class="absolute -right-4 -bottom-8 text-[12rem] font-black opacity-10 italic">04</span>
            </header>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-primary border-b pb-4 mb-6 uppercase tracking-widest text-xs flex justify-between items-center">
                    1. Información General
                    <a href="registro.php" class="text-[10px] text-gray-400 hover:text-primary">Editar</a>
                </h3>
                <div class="space-y-4">
                    <p class="text-lg font-black text-slate-800 leading-tight"><?php echo htmlspecialchars($p1['titulo'] ?? ''); ?></p>
                    <div class="flex flex-wrap gap-4 text-xs font-bold uppercase tracking-widest text-slate-500">
                        <span class="bg-slate-50 px-3 py-1 rounded-full"><?php echo htmlspecialchars($p1['area'] ?? ''); ?></span>
                        <span class="bg-slate-50 px-3 py-1 rounded-full"><?php echo htmlspecialchars($p1['duracion'] ?? ''); ?></span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-primary border-b pb-4 mb-6 uppercase tracking-widest text-xs">2. Equipo</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Colaborador</p>
                    <p class="font-medium text-slate-700 mb-4"><?php echo htmlspecialchars($p2['colaborador_nombre'] ?? 'Ninguno'); ?></p>
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Alumno</p>
                    <p class="font-medium text-slate-700"><?php echo htmlspecialchars($p2['alumno_nombre'] ?? 'Ninguno'); ?></p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-primary border-b pb-4 mb-6 uppercase tracking-widest text-xs">3. Recursos</h3>
                    <div class="mb-4">
                        <p class="text-xs font-bold text-slate-400 uppercase">Monto</p>
                        <p class="text-xl font-black text-primary">$<?php echo number_format($p3['monto'] ?? 0, 2); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Periodo</p>
                        <p class="font-medium"><?php echo ($p3['fecha_inicio'] ?? '') . ' al ' . ($p3['fecha_fin'] ?? ''); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 flex gap-4 items-start">
                <span class="material-symbols-outlined text-amber-600">warning</span>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Al hacer clic en <b>"Finalizar Registro"</b>, la propuesta se guardará en el sistema y no podrá ser editada hasta que el comité realice la primera revisión.
                </p>
            </div>

            <div class="flex items-center justify-between gap-4 pt-4">
                <a href="registro-paso3.php" class="px-8 py-3 rounded-xl border-2 border-primary text-primary font-bold hover:bg-primary/5 transition-all text-sm">Anterior</a>
                <button type="submit" class="flex-1 py-4 rounded-xl bg-primary text-white font-black hover:bg-black transition-all shadow-xl shadow-primary/20 uppercase tracking-widest text-sm">
                    Finalizar Registro
                </button>
            </div>
        </form>
    </main>
</body>
</html>