<?php
session_start();
require_once 'conexion.php'; // NUEVO: Necesitamos conexión para buscar las plantillas

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$nombre_investigador = $_SESSION['nombre'];

// Buscar si la convocatoria actual tiene plantillas registradas
$plantillas = [];
if (isset($_SESSION['conv_id'])) {
    $conv_id = intval($_SESSION['conv_id']);
    $query_plantillas = "SELECT ruta_plantilla1, ruta_plantilla2, ruta_plantilla3 FROM convocatorias WHERE id = $conv_id";
    $res_plantillas = mysqli_query($conexion, $query_plantillas);
    if ($res_plantillas && mysqli_num_rows($res_plantillas) > 0) {
        $plantillas = mysqli_fetch_assoc($res_plantillas);
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM Registro - Paso 4: Documentos</title>
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
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <div class="size-10 bg-primary text-white flex items-center justify-center rounded-xl shadow-lg">
                <span class="material-symbols-outlined">school</span>
            </div>
            <div>
                <h2 class="font-bold text-slate-800 leading-tight">Registro de Proyecto</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary">Paso 4 de 5</p>
            </div>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-xs font-bold text-slate-400 uppercase">Investigador</p>
            <p class="text-sm font-bold text-primary"><?php echo htmlspecialchars($nombre_investigador); ?></p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Documentación del Proyecto</h1>
            <p class="text-slate-500 font-medium">Sube los formatos oficiales debidamente llenados. Solo se permiten archivos PDF, Word o Excel.</p>
        </div>

        <div id="alertaPeso" class="hidden mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex gap-3 items-center shadow-sm">
            <span class="material-symbols-outlined text-red-600">error</span>
            <p class="text-sm text-red-700 font-medium">¡Uy! La suma total de tus archivos excede el límite permitido (60 MB). Por favor, comprime tus archivos e inténtalo de nuevo.</p>
        </div>

        <div class="bg-slate-900 p-8 rounded-3xl shadow-lg border border-slate-800 mb-8">
            <h3 class="text-sm font-black text-white uppercase tracking-widest mb-2 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">download</span> Formatos de la Convocatoria
            </h3>
            <p class="text-slate-400 text-xs mb-6">Descarga las plantillas oficiales, llénalas con la información de tu proyecto y súbelas en el formulario de abajo.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if(!empty($plantillas['ruta_plantilla1'])): ?>
                    <a href="<?php echo htmlspecialchars($plantillas['ruta_plantilla1']); ?>" download class="flex items-center gap-3 bg-slate-800 p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all group">
                        <span class="material-symbols-outlined text-blue-400 group-hover:text-blue-300">description</span>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-white uppercase tracking-wider">Formato I</p>
                            <p class="text-[10px] text-slate-500 group-hover:text-white transition-colors">Registro</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-500 group-hover:text-white text-sm">download</span>
                    </a>
                <?php else: ?>
                    <div class="flex items-center gap-3 bg-slate-800/50 p-4 rounded-xl border border-slate-800 opacity-50">
                        <span class="material-symbols-outlined text-slate-600">description</span>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Formato I</p>
                            <p class="text-[10px] text-slate-600">No disponible</p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!empty($plantillas['ruta_plantilla2'])): ?>
                    <a href="<?php echo htmlspecialchars($plantillas['ruta_plantilla2']); ?>" download class="flex items-center gap-3 bg-slate-800 p-4 rounded-xl border border-slate-700 hover:border-blue-500 transition-all group">
                        <span class="material-symbols-outlined text-blue-400 group-hover:text-blue-300">assignment</span>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-white uppercase tracking-wider">Formato II</p>
                            <p class="text-[10px] text-slate-500 group-hover:text-white transition-colors">Protocolo</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-500 group-hover:text-white text-sm">download</span>
                    </a>
                <?php else: ?>
                    <div class="flex items-center gap-3 bg-slate-800/50 p-4 rounded-xl border border-slate-800 opacity-50">
                        <span class="material-symbols-outlined text-slate-600">assignment</span>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Formato II</p>
                            <p class="text-[10px] text-slate-600">No disponible</p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!empty($plantillas['ruta_plantilla3'])): ?>
                    <a href="<?php echo htmlspecialchars($plantillas['ruta_plantilla3']); ?>" download class="flex items-center gap-3 bg-slate-800 p-4 rounded-xl border border-slate-700 hover:border-green-500 transition-all group">
                        <span class="material-symbols-outlined text-green-400 group-hover:text-green-300">table_view</span>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-white uppercase tracking-wider">Formato III</p>
                            <p class="text-[10px] text-slate-500 group-hover:text-white transition-colors">Presupuesto</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-500 group-hover:text-white text-sm">download</span>
                    </a>
                <?php else: ?>
                    <div class="flex items-center gap-3 bg-slate-800/50 p-4 rounded-xl border border-slate-800 opacity-50">
                        <span class="material-symbols-outlined text-slate-600">table_view</span>
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Formato III</p>
                            <p class="text-[10px] text-slate-600">No disponible</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <form id="formularioSubida" action="procesar-paso4.php" method="POST" enctype="multipart/form-data" class="space-y-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                <div class="space-y-6">
                    <div class="p-6 border border-slate-200 rounded-2xl bg-slate-50/50 hover:border-primary/30 transition-all">
                        <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">description</span>
                            Subir Formato I - Registro (.docx o .pdf)
                        </label>
                        <input type="file" name="formato1" id="file1" accept=".docx,.pdf" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                    </div>

                    <div class="p-6 border border-slate-200 rounded-2xl bg-slate-50/50 hover:border-primary/30 transition-all">
                        <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600">assignment</span>
                            Subir Formato II - Protocolo (.docx o .pdf)
                        </label>
                        <input type="file" name="formato2" id="file2" accept=".docx,.pdf" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                    </div>

                    <div class="p-6 border border-slate-200 rounded-2xl bg-slate-50/50 hover:border-primary/30 transition-all">
                        <label class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600">table_view</span>
                            Subir Formato III - Presupuesto (.xlsx o .pdf)
                        </label>
                        <input type="file" name="formato3" id="file3" accept=".xlsx,.pdf" required
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between mt-12 pt-8 border-t border-slate-100">
                <a href="registro-paso3.php" class="flex items-center gap-2 px-8 py-3 rounded-lg border-2 border-primary text-primary font-bold hover:bg-primary/5 transition-all text-sm">
                    <span class="material-symbols-outlined">arrow_back</span> Anterior
                </a>
                <button type="submit" class="flex items-center gap-2 px-10 py-3 rounded-lg bg-primary text-white font-bold shadow-lg shadow-primary/20 hover:bg-secondary-red transition-all text-sm">
                    Siguiente <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </form>
    </main>

    <script>
        document.getElementById('formularioSubida').addEventListener('submit', function(e) {
            let file1 = document.getElementById('file1').files[0];
            let file2 = document.getElementById('file2').files[0];
            let file3 = document.getElementById('file3').files[0];
            
            let totalSize = 0;
            if (file1) totalSize += file1.size;
            if (file2) totalSize += file2.size;
            if (file3) totalSize += file3.size;

            if (totalSize > 62914560) {
                e.preventDefault();
                document.getElementById('alertaPeso').classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                document.getElementById('alertaPeso').classList.add('hidden');
            }
        });
    </script>
</body>
</html>