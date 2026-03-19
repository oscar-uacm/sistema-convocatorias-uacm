<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
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
<body class="bg-background-light font-display text-slate-900">
    <main class="max-w-4xl mx-auto px-6 py-12">
        
        <div class="mb-8">
            <a href="index.php" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">home</span>
                Abandonar registro y volver al inicio
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 overflow-hidden border border-primary/10">
            <div class="bg-primary p-8 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white/60 text-[10px] uppercase font-bold tracking-[0.2em] mb-1">Nueva Propuesta 2026</p>
                        <h1 class="text-2xl font-black">Paso 1: Datos Generales</h1>
                    </div>
                    <span class="text-4xl font-black opacity-20 italic text-white">01</span>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <form action="procesar-paso1.php" method="POST">
                    <div class="space-y-8">
                        <div>
                            <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-3">Título del Proyecto</label>
                            <input type="text" name="titulo" required value="<?php echo htmlspecialchars($datos['titulo'] ?? ''); ?>"
                                class="w-full bg-background-light dark:bg-zinc-800 border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium" 
                                placeholder="Escriba el nombre completo de la investigación">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-3">Área de Conocimiento</label>
                                <select name="area" required class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:border-primary transition-all font-medium">
                                    <option value="" disabled <?php echo !isset($datos['area']) ? 'selected' : ''; ?>>Seleccione área...</option>
                                    <option value="Ciencias Exactas" <?php echo ($datos['area'] ?? '') == 'Ciencias Exactas' ? 'selected' : ''; ?>>Ciencias Exactas</option>
                                    <option value="Humanidades" <?php echo ($datos['area'] ?? '') == 'Humanidades' ? 'selected' : ''; ?>>Humanidades</option>
                                    <option value="Tecnología" <?php echo ($datos['area'] ?? '') == 'Tecnología' ? 'selected' : ''; ?>>Tecnología</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-3">Duración Estimada</label>
                                <select name="duracion" required class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:border-primary transition-all font-medium">
                                    <option value="6 meses" <?php echo ($datos['duracion'] ?? '') == '6 meses' ? 'selected' : ''; ?>>6 Meses</option>
                                    <option value="12 meses" <?php echo ($datos['duracion'] ?? '') == '12 meses' ? 'selected' : ''; ?>>12 Meses</option>
                                    <option value="24 meses" <?php echo ($datos['duracion'] ?? '') == '24 meses' ? 'selected' : ''; ?>>24 Meses</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-primary uppercase tracking-widest mb-3">Resumen del Proyecto</label>
                            <textarea name="resumen" required rows="6"
                                class="w-full bg-background-light border-2 border-[#e9d2ce] rounded-2xl px-5 py-4 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all font-medium leading-relaxed" 
                                placeholder="Describa los objetivos y alcances..."><?php echo htmlspecialchars($datos['resumen'] ?? ''); ?></textarea>
                        </div>
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