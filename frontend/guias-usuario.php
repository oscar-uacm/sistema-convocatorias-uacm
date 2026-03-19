<?php
session_start();
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = $logueado ? $_SESSION['nombre'] : '';

// Mapeo completo de los archivos reales en tu carpeta ConvocatoriaFinanciamiento
$documentacion = [
    'Formatos de Registro y Protocolo' => [
        ['nombre' => 'Formato I - Registro de Proyecto', 'icon' => 'description', 'color' => 'text-blue-600', 'file' => 'FORMATO I CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.docx'],
        ['nombre' => 'Formato II - Protocolo Técnico', 'icon' => 'assignment', 'color' => 'text-blue-600', 'file' => 'FORMATO II CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.docx'],
    ],
    'Gestión Presupuestal' => [
        ['nombre' => 'Formato III - Solicitud de Bienes y Servicios', 'icon' => 'table_view', 'color' => 'text-green-600', 'file' => 'FORMATO III CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.xlsx'],
    ],
    'Bases y Glosarios' => [
        ['nombre' => 'Convocatoria Final 2026 (Bases)', 'icon' => 'picture_as_pdf', 'color' => 'text-primary', 'file' => 'CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026 FINAL.pdf'],
        ['nombre' => 'Glosario de Términos Oficial', 'icon' => 'menu_book', 'color' => 'text-orange-600', 'file' => 'GLOSARIO DE LA CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.pdf'],
    ],
    'Resultados y Ratificación' => [
        ['nombre' => 'Acta de Resultados 2026', 'icon' => 'verified', 'color' => 'text-purple-600', 'file' => 'RESULTADOS_CONVO_FINANCIAMIENTO_2026.pdf'],
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Guías de Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>

<body class="bg-background-light text-neutral-text">
    <div class="relative flex min-h-screen w-full flex-col">
        
        <header class="flex items-center justify-between border-b border-neutral-subtle px-10 py-4 bg-white sticky top-0 z-50 shadow-sm">
            <div class="flex items-center gap-8">
                <a href="index.php" class="flex items-center gap-4 text-primary hover:opacity-80 transition-all group">
                    <div class="size-10 flex items-center justify-center bg-primary text-white rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined">school</span>
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-neutral-text text-lg font-bold leading-tight uppercase group-hover:text-primary transition-colors">Colegio de Ciencias y Humanidades</h2>
                        <span class="text-primary text-[10px] font-bold uppercase tracking-[0.2em]">Guías y Documentación</span>
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
                    <h3 class="text-neutral-muted text-[10px] font-bold uppercase tracking-widest mb-6">Secciones</h3>
                    <nav class="space-y-2">
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors" href="convocatoria.php">
                            <span class="material-symbols-outlined text-neutral-muted">description</span>
                            <span class="text-sm font-medium">Convocatoria 2026</span>
                        </a>
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg sidebar-item-active" href="guias-usuario.php">
                            <span class="material-symbols-outlined text-primary">menu_book</span>
                            <span class="text-sm font-semibold">Guías de Usuario</span>
                        </a>
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors" href="faq.php">
                            <span class="material-symbols-outlined text-neutral-muted">help_outline</span>
                            <span class="text-sm font-medium">Preguntas Frecuentes</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <main class="flex-1 bg-white p-8">
                <div class="max-w-5xl">
                    <h1 class="text-4xl font-black text-neutral-text mb-2 tracking-tight">Centro de Descargas</h1>
                    <p class="text-neutral-muted mb-12 text-lg">Consulte y descargue los archivos necesarios para su postulación.</p>

                    <?php foreach ($documentacion as $categoria => $items): ?>
                        <section class="mb-12">
                            <h2 class="text-xs font-black text-neutral-muted uppercase tracking-[0.2em] mb-6 flex items-center gap-3">
                                <span class="w-8 h-[1px] bg-primary"></span>
                                <?php echo $categoria; ?>
                            </h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php foreach ($items as $doc): ?>
                                    <div class="flex items-start p-6 border border-neutral-subtle rounded-2xl hover:border-primary/30 hover:shadow-lg transition-all bg-white group">
                                        <div class="size-12 bg-background-light flex items-center justify-center rounded-xl mr-5 group-hover:bg-primary/5 transition-colors">
                                            <span class="material-symbols-outlined <?php echo $doc['color']; ?> text-3xl"><?php echo $doc['icon']; ?></span>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-neutral-text text-sm mb-1"><?php echo $doc['nombre']; ?></h4>
                                            <p class="text-[10px] text-neutral-muted uppercase font-bold tracking-tighter mb-4">Ciclo 2026 • Oficial</p>
                                            
                                            <a href="frontend/ConvocatoriaFinanciamiento/<?php echo urlencode($doc['file']); ?>" 
                                               target="_blank" 
                                               class="inline-flex items-center text-primary font-bold text-[11px] uppercase tracking-widest hover:underline">
                                                Descargar Archivo
                                                <span class="material-symbols-outlined text-xs ml-1">download</span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                    
                    <div class="mt-12 p-8 bg-neutral-subtle/50 rounded-3xl border border-dashed border-neutral-muted/30 text-center">
                        <p class="text-sm text-neutral-muted font-medium italic">"Asegúrese de utilizar siempre las versiones más recientes de los formatos para evitar retrasos en su trámite."</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>