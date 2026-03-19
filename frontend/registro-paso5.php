<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Rescatamos toda la información de las sesiones anteriores
$p1 = $_SESSION['proyecto_paso1'] ?? [];
$p2 = $_SESSION['proyecto_paso2'] ?? [];
$p3 = $_SESSION['proyecto_paso3'] ?? [];
$p4 = $_SESSION['proyecto_paso4'] ?? []; // ¡Nuestra nueva sesión de archivos!
$nombre_investigador = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Paso 5: Revisión Final</title>
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
<body class="bg-background-light font-display text-slate-800 pb-20">
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Registro de Proyecto</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary">Paso 5 de 5</p>
            </div>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-xs font-bold text-slate-400 uppercase">Investigador</p>
            <p class="text-sm font-bold text-primary"><?php echo htmlspecialchars($nombre_investigador); ?></p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Revisión Final</h1>
            <p class="text-slate-500 font-medium">Verifica que toda la información y documentos de tu propuesta sean correctos antes de enviarla al Comité.</p>
        </div>

        <form action="finalizar-registro.php" method="POST" class="space-y-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                    <span class="material-symbols-outlined text-primary">feed</span> Datos Generales
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Título del Proyecto</p>
                        <p class="font-bold text-lg"><?php echo htmlspecialchars($p1['titulo'] ?? ''); ?></p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Área Académica</p>
                            <p class="font-medium"><?php echo htmlspecialchars($p1['area'] ?? ''); ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Duración</p>
                            <p class="font-medium"><?php echo htmlspecialchars($p1['duracion'] ?? ''); ?> meses</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                    <span class="material-symbols-outlined text-green-600">payments</span> Presupuesto y Cronograma
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Monto Solicitado</p>
                        <p class="text-xl font-black text-primary">$<?php echo number_format($p3['monto'] ?? 0, 2); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Periodo</p>
                        <p class="font-medium"><?php echo htmlspecialchars($p3['fecha_inicio'] ?? '') . ' al ' . htmlspecialchars($p3['fecha_fin'] ?? ''); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                    <span class="material-symbols-outlined text-blue-500">folder_zip</span> Archivos Adjuntos
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="material-symbols-outlined text-slate-400">check_circle</span>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-700">Formato I - Registro</p>
                            <p class="text-[10px] text-slate-400 italic"><?php echo basename($p4['ruta_formato1'] ?? 'No subido'); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="material-symbols-outlined text-slate-400">check_circle</span>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-700">Formato II - Protocolo</p>
                            <p class="text-[10px] text-slate-400 italic"><?php echo basename($p4['ruta_formato2'] ?? 'No subido'); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="material-symbols-outlined text-slate-400">check_circle</span>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-700">Formato III - Presupuesto</p>
                            <p class="text-[10px] text-slate-400 italic"><?php echo basename($p4['ruta_formato3'] ?? 'No subido'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 flex gap-4 items-start">
                <span class="material-symbols-outlined text-amber-600">warning</span>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Al hacer clic en <b>"Finalizar Registro"</b>, la propuesta y los documentos se guardarán en el sistema y no podrán ser editados hasta que el comité realice la primera revisión.
                </p>
            </div>

            <div class="flex items-center justify-between gap-4 pt-4">
                <a href="registro-paso4.php" class="px-8 py-3 rounded-xl border-2 border-primary text-primary font-bold hover:bg-primary/5 transition-all text-sm">Anterior</a>
                <button type="submit" class="flex-1 py-4 rounded-xl bg-primary text-white font-black hover:bg-black transition-all shadow-xl shadow-primary/20 uppercase tracking-widest text-sm">
                    Finalizar Registro
                </button>
            </div>
        </form>
    </main>
</body>
</html>