<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$datos_paso2 = $_SESSION['proyecto_paso2'] ?? [];
$nombre_investigador = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Registro de Proyecto: Colaboradores</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { "primary": "#701705", "primary-accent": "#9C2007", "bg-soft": "#f8f6f5" },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-bg-soft font-display text-slate-800">
    <main class="max-w-4xl mx-auto px-6 py-12">
        
        <div class="mb-8">
            <a href="index.php" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">home</span>
                Abandonar registro y volver al inicio
            </a>
        </div>

        <form action="procesar-paso2.php" method="POST">
            <header class="mb-10 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black text-primary tracking-tight">Paso 2: Equipo de Trabajo</h1>
                    <p class="text-slate-500 font-medium mt-1">Registre a los colaboradores y alumnos participantes.</p>
                </div>
                <div class="text-right">
                    <span class="text-5xl font-black text-primary/10 italic">02</span>
                </div>
            </header>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-primary mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">group</span> Colaborador Docente
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Nombre Completo</label>
                            <input type="text" name="colaborador_nombre" value="<?php echo htmlspecialchars($datos_paso2['colaborador_nombre'] ?? ''); ?>"
                                class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Nombre del docente" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">No. Empleado</label>
                                <input type="text" name="colaborador_id" value="<?php echo htmlspecialchars($datos_paso2['colaborador_id'] ?? ''); ?>"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="ID" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Adscripción</label>
                                <input type="text" name="colaborador_adscripcion" value="<?php echo htmlspecialchars($datos_paso2['colaborador_adscripcion'] ?? ''); ?>"
                                    class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Plantel" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="font-bold text-primary mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">person</span> Alumno Auxiliar
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Nombre del Estudiante</label>
                            <input type="text" name="alumno_nombre" value="<?php echo htmlspecialchars($datos_paso2['alumno_nombre'] ?? ''); ?>"
                                class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Nombre completo" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Matrícula</label>
                            <input type="text" name="alumno_matricula" value="<?php echo htmlspecialchars($datos_paso2['alumno_matricula'] ?? ''); ?>"
                                class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary/20" placeholder="Matrícula UACM" />
                        </div>
                    </div>
                </div>
            </section>

            <div class="mt-12 flex items-center justify-between">
                <a href="registro.php" class="px-8 h-12 rounded-lg border-2 border-primary text-primary font-bold hover:bg-primary/5 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Anterior
                </a>
                <button type="submit" class="px-8 h-12 rounded-lg bg-primary text-white font-bold hover:bg-primary-accent shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                    Siguiente
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </div>
        </form>
    </main>
</body>
</html>