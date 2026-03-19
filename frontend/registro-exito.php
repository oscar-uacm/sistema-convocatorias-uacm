<?php
session_start();
// Si no hay un folio registrado, redirigir al inicio (seguridad)
if (!isset($_SESSION['ultimo_folio'])) {
    header("Location: registro.php");
    exit;
}
$folio = $_SESSION['ultimo_folio'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Registro Exitoso | UACM</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: { "primary": "#701705", "success": "#2D6A4F" },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-[#f8f6f5] font-display text-slate-900 min-h-screen flex items-center justify-center p-6">
    
    <main class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">
        <div class="bg-success p-8 text-white text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4">
                <span class="material-symbols-outlined text-4xl">check_circle</span>
            </div>
            <h1 class="text-3xl font-black tracking-tight">¡Registro Completado!</h1>
            <p class="text-white/80 mt-2">Su propuesta ha sido recibida exitosamente por el sistema.</p>
        </div>

        <div class="p-10 text-center">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">Folio de Seguimiento</p>
            <div class="inline-block bg-slate-100 px-8 py-4 rounded-2xl border-2 border-dashed border-slate-200">
                <span class="text-4xl font-black text-primary tracking-wider"><?php echo $folio; ?></span>
            </div>

            <div class="mt-10 space-y-4">
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex gap-3 text-left">
                    <span class="material-symbols-outlined text-blue-600">info</span>
                    <p class="text-xs text-blue-800 leading-relaxed">
                        Se ha enviado un acuse de recibo a su correo institucional. El comité evaluador revisará su propuesta en un plazo máximo de 15 días hábiles.
                    </p>
                </div>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="dashboard.php" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">dashboard</span>
                    Ir a mis proyectos
                </a>
                <button onclick="window.print()" class="px-8 py-3 border-2 border-primary text-primary font-bold rounded-xl hover:bg-primary/5 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">print</span>
                    Imprimir Comprobante
                </button>
            </div>
        </div>

        <footer class="bg-slate-50 p-6 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                Universidad Autónoma de la Ciudad de México · 2024
            </p>
        </footer>
    </main>

</body>
</html>