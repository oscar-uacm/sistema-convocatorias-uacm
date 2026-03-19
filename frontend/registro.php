<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// === NUEVO: CAPTURAR LA CONVOCATORIA Y GUARDARLA EN SESIÓN ===
if (isset($_GET['conv_id'])) {
    $_SESSION['conv_id'] = intval($_GET['conv_id']);
}
// ==============================================================

$datos = $_SESSION['proyecto_paso1'] ?? [];
$nombre_investigador = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Registro de Proyecto - Paso 1</title>
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
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Registro de Proyecto</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary">Paso 1 de 5</p>
            </div>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-xs font-bold text-slate-400 uppercase">Investigador</p>
            <p class="text-sm font-bold text-primary"><?php echo htmlspecialchars($nombre_investigador); ?></p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Datos Generales</h1>
            <p class="text-slate-500 font-medium">Comencemos con la información básica de tu propuesta de investigación.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 sm:p-10">
                <form action="procesar-paso1.php" method="POST" class="space-y-8">
                    
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Título del Proyecto</label>
                        <input type="text" name="titulo" required value="<?php echo htmlspecialchars($datos['titulo'] ?? ''); ?>"
                            class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-800" 
                            placeholder="Ej. Análisis de..." />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Área de Conocimiento</label>
                            <select name="area" required class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-700">
                                <option value="">Seleccione un área...</option>
                                <option value="Ciencias y Humanidades" <?php echo (isset($datos['area']) && $datos['area'] == 'Ciencias y Humanidades') ? 'selected' : ''; ?>>Ciencias y Humanidades</option>
                                <option value="Ciencias y Tecnología" <?php echo (isset($datos['area']) && $datos['area'] == 'Ciencias y Tecnología') ? 'selected' : ''; ?>>Ciencias y Tecnología</option>
                                <option value="Ciencias Sociales" <?php echo (isset($datos['area']) && $datos['area'] == 'Ciencias Sociales') ? 'selected' : ''; ?>>Ciencias Sociales</option>
                                <option value="Artes y Letras" <?php echo (isset($datos['area']) && $datos['area'] == 'Artes y Letras') ? 'selected' : ''; ?>>Artes y Letras</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Duración (Meses)</label>
                            <input type="number" name="duracion" required min="1" max="48" value="<?php echo htmlspecialchars($datos['duracion'] ?? ''); ?>"
                                class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-bold text-slate-800" 
                                placeholder="Ej. 12" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Resumen del Proyecto</label>
                        <textarea name="resumen" required rows="6"
                            class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium leading-relaxed resize-none" 
                            placeholder="Describa los objetivos y alcances..."><?php echo htmlspecialchars($datos['resumen'] ?? ''); ?></textarea>
                    </div>

                    <div class="mt-12 flex flex-col sm:flex-row justify-end gap-4 border-t border-[#e9d2ce] pt-8">
                        <a href="dashboard.php" class="px-6 py-3 rounded-lg border-2 border-gray-200 text-gray-500 font-bold text-sm hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                             Cancelar
                        </a>
                        <button type="submit"
                            class="px-10 py-3 rounded-lg bg-primary text-white font-bold text-sm hover:shadow-xl hover:shadow-primary/20 border-2 border-primary transition-all flex items-center justify-center gap-2">
                            Siguiente Paso
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>