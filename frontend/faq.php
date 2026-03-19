<?php
session_start();
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = $logueado ? $_SESSION['nombre'] : '';

// Preguntas extraídas de los lineamientos reales de tus archivos PDF y Excel
$faqs = [
    [
        'pregunta' => '¿Qué es el proceso de evaluación "Doble Ciego"?',
        'respuesta' => 'Según el Glosario institucional, consiste en que los autores no conocen a sus evaluadores y viceversa. Esto garantiza la imparcialidad y evita cualquier conflicto de interés durante la dictaminación.',
        'categoria' => 'Evaluación'
    ],
    [
        'pregunta' => '¿Quiénes pueden ser Responsables Técnicos (RT)?',
        'respuesta' => 'La convocatoria establece que deben ser profesores(as) investigadores(as) con contrato por tiempo indeterminado, activos y adscritos al Colegio de Ciencias y Humanidades (CCyH).',
        'categoria' => 'Elegibilidad'
    ],
    [
        'pregunta' => '¿Qué gastos se consideran "No Permitidos"?',
        'respuesta' => 'De acuerdo con el Formato III, no se pueden financiar: visas, taxis, gasolina no relacionada, mobiliario, pago de becas, sobresueldos, ni servicios de mensajería o telefonía móvil.',
        'categoria' => 'Presupuesto'
    ],
    [
        'pregunta' => '¿Qué pasa si mi proyecto no recibe financiamiento?',
        'respuesta' => 'Los proyectos que no resulten favorecidos con recurso económico quedarán registrados como proyectos de investigación vigentes ante el CCyH, manteniendo su estatus institucional.',
        'categoria' => 'Resultados'
    ],
    [
        'pregunta' => '¿Cuál es la vigencia de los proyectos aprobados?',
        'respuesta' => 'Los proyectos inician formalmente el 10 de febrero de 2026 y deben entregar su informe final en la tercera semana de febrero de 2027.',
        'categoria' => 'Tiempos'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Preguntas Frecuentes</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#9c2007",
                        "primary-dark": "#701705",
                        "background-light": "#f8f6f5",
                        "neutral-subtle": "#f4e9e7",
                        "neutral-text": "#1c0f0d",
                        "neutral-muted": "#9d5648",
                    },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Lexend', sans-serif; }
        .material-symbols-outlined { font-family: 'Material Symbols Outlined' !important; }
        .sidebar-item-active { background-color: #f4e9e7; border-left: 4px solid #9c2007; }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
</head>

<body class="bg-background-light text-neutral-text font-display">
    <div class="relative flex min-h-screen w-full flex-col">
        <header class="flex items-center justify-between border-b border-neutral-subtle px-10 py-4 bg-white sticky top-0 z-50 shadow-sm">
            <div class="flex items-center gap-8">
                <a href="index.php" class="flex items-center gap-4 text-primary hover:opacity-80 transition-opacity group">
                    <div class="size-10 flex items-center justify-center bg-primary text-white rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-neutral-text text-lg font-bold leading-tight uppercase group-hover:text-primary transition-colors">Colegio de Ciencias y Humanidades</h2>
                        <span class="text-primary text-[10px] font-bold uppercase tracking-[0.2em]">Centro de Ayuda</span>
                    </div>
                </a>
            </div>
            
            <div class="flex gap-4 items-center">
                <?php if($logueado): ?>
                    <span class="text-[10px] font-bold text-neutral-muted uppercase tracking-widest"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                    <a href="dashboard.php" class="bg-primary text-white px-4 py-2 rounded text-xs font-bold uppercase hover:bg-primary-dark transition-all">Mi Panel</a>
                <?php else: ?>
                    <a href="login.php" class="bg-primary text-white px-6 py-2 rounded text-sm font-bold shadow-md hover:bg-primary-dark transition-all">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </header>

        <div class="flex flex-1 w-full max-w-[1440px] mx-auto">
            <aside class="w-72 border-r border-neutral-subtle bg-white hidden md:block">
                <div class="p-6 sticky top-[73px]">
                    <h3 class="text-neutral-muted text-[10px] font-bold uppercase tracking-widest mb-6">Navegación</h3>
                    <nav class="space-y-2">
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors" href="convocatoria.php">
                            <span class="material-symbols-outlined text-neutral-muted">description</span>
                            <span class="text-sm font-medium">Convocatoria 2026</span>
                        </a>
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors" href="guias-usuario.php">
                            <span class="material-symbols-outlined text-neutral-muted">menu_book</span>
                            <span class="text-sm font-medium">Guías de Usuario</span>
                        </a>
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg sidebar-item-active" href="faq.php">
                            <span class="material-symbols-outlined text-primary">help_outline</span>
                            <span class="text-sm font-semibold">Preguntas Frecuentes</span>
                        </a>
                    </nav>

                    <div class="mt-10 p-5 bg-primary/5 rounded-2xl border border-primary/10">
                        <p class="text-[10px] font-black text-primary uppercase mb-2 tracking-widest">¿Dudas Técnicas?</p>
                        <p class="text-[11px] text-neutral-muted mb-4">Consulte las definiciones oficiales en el glosario.</p>
                        <a href="frontend/ConvocatoriaFinanciamiento/GLOSARIO%20DE%20LA%20CONVOCATORIA%20INTERNA%20PARA%20EL%20FINANCIAMIENTO%20DE%20PROYECTOS%20DE%20INVESTIGACIÓN%20DEL%20CCYH%202026.pdf" 
                           target="_blank" class="text-xs font-bold text-primary flex items-center gap-2 hover:underline">
                           Abrir Glosario <span class="material-symbols-outlined text-xs">open_in_new</span>
                        </a>
                    </div>
                </div>
            </aside>

            <main class="flex-1 bg-white p-8">
                <div class="max-w-3xl">
                    <h1 class="text-4xl font-black text-neutral-text mb-2 tracking-tight">Preguntas Frecuentes</h1>
                    <p class="text-neutral-muted mb-12 text-lg">Todo lo que necesita saber sobre el proceso de financiamiento 2026.</p>

                    <div class="space-y-4">
                        <?php foreach ($faqs as $faq): ?>
                        <details class="group border border-neutral-subtle rounded-2xl overflow-hidden shadow-sm hover:border-primary/30 transition-all">
                            <summary class="flex items-center justify-between p-6 cursor-pointer bg-white group-open:bg-neutral-subtle/20">
                                <div class="flex items-center gap-4">
                                    <span class="bg-primary/10 text-primary text-[10px] font-black px-2 py-1 rounded uppercase tracking-tighter">
                                        <?php echo $faq['categoria']; ?>
                                    </span>
                                    <h3 class="font-bold text-sm md:text-base text-neutral-text"><?php echo $faq['pregunta']; ?></h3>
                                </div>
                                <span class="material-symbols-outlined transition-transform group-open:rotate-180 text-neutral-muted">expand_more</span>
                            </summary>
                            <div class="p-6 bg-white text-sm text-neutral-muted leading-relaxed border-t border-neutral-subtle">
                                <?php echo $faq['respuesta']; ?>
                            </div>
                        </details>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-16 p-8 bg-neutral-subtle rounded-[2rem] flex flex-col md:flex-row items-center justify-between gap-6 border border-neutral-subtle">
                        <div>
                            <h4 class="font-bold text-lg text-neutral-text">¿Aún tiene dudas?</h4>
                            <p class="text-sm text-neutral-muted">Contacte directamente a la Comisión de Investigación del Colegio.</p>
                        </div>
                        <a href="mailto:ccyh.investigacion@uacm.edu.mx" class="bg-primary text-white px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-primary-dark transition-all shadow-md">
                            Contactar por Email
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>