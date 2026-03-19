<?php
session_start();
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = $logueado ? $_SESSION['nombre'] : '';

function obtenerEstatusFecha($fecha_fin_str) {
    $fecha_actual = new DateTime(); // Hoy: 09 de marzo de 2026
    $fecha_fin = new DateTime($fecha_fin_str);
    if ($fecha_actual > $fecha_fin) return ['clase' => 'bg-red-100 text-red-700 border-red-200', 'texto' => 'Concluido'];
    $diff = $fecha_actual->diff($fecha_fin)->days;
    if ($diff <= 3) return ['clase' => 'bg-orange-100 text-orange-700 border-orange-400 animate-pulse', 'texto' => 'Cierre Inminente'];
    if ($diff <= 5) return ['clase' => 'bg-yellow-100 text-yellow-700 border-yellow-400', 'texto' => 'Próximo a vencer'];
    return ['clase' => 'bg-green-100 text-green-700 border-green-200', 'texto' => 'Vigente'];
}

$cronograma = [
    ['actividad' => 'Registro de Protocolos', 'fin' => '2025-12-05'],
    ['actividad' => 'Evaluación de Propuestas', 'fin' => '2026-01-30'],
    ['actividad' => 'Publicación de Resultados', 'fin' => '2026-02-09'],
    ['actividad' => 'Inicio de Proyectos', 'fin' => '2026-02-10'],
    ['actividad' => 'Entrega de Informe Final', 'fin' => '2027-02-21']
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UACM - Convocatoria 2026</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body { font-family: 'Lexend', sans-serif; }
        .material-symbols-outlined { font-family: 'Material Symbols Outlined' !important; }
        .sidebar-item-active { background-color: #f4e9e7; border-left: 4px solid #9c2007; }
    </style>
    <script>
        tailwind.config = { theme: { extend: { colors: { "primary": "#9c2007", "primary-dark": "#701705", "background-light": "#f8f6f5", "neutral-subtle": "#f4e9e7", "neutral-text": "#1c0f0d", "neutral-muted": "#9d5648" } } } }
    </script>
</head>
<body class="bg-background-light text-neutral-text">
    <div class="relative flex min-h-screen flex-col">
        <header class="flex items-center justify-between border-b border-neutral-subtle px-10 py-4 bg-white sticky top-0 z-50 shadow-sm">
            <a href="index.php" class="flex items-center gap-4 text-primary hover:opacity-80 transition-all group">
                <div class="size-10 flex items-center justify-center bg-primary text-white rounded-lg shadow group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined">school</span>
                </div>
                <div class="flex flex-col">
                    <h2 class="text-neutral-text text-lg font-bold leading-tight uppercase group-hover:text-primary transition-colors">Colegio de Ciencias y Humanidades</h2>
                    <span class="text-primary text-[10px] font-bold uppercase tracking-[0.2em]">Gestión de Investigación</span>
                </div>
            </a>
            <div class="flex gap-4 items-center">
                <?php if($logueado): ?>
                    <span class="text-[10px] font-bold text-neutral-muted uppercase"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                    <a href="dashboard.php" class="bg-primary text-white px-4 py-2 rounded text-xs font-bold uppercase hover:bg-primary-dark">Mi Panel</a>
                    <a href="logout.php" class="text-neutral-muted hover:text-primary"><span class="material-symbols-outlined">logout</span></a>
                <?php else: ?>
                    <a href="login.php" class="bg-primary text-white px-6 py-2 rounded text-sm font-bold shadow-md hover:bg-primary-dark">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </header>

        <div class="flex flex-1 w-full max-w-[1440px] mx-auto">
            <aside class="w-72 border-r border-neutral-subtle bg-white hidden md:block">
                <div class="p-6 sticky top-[73px]">
                    <h3 class="text-neutral-muted text-[10px] font-bold uppercase mb-6 tracking-widest">Navegación</h3>
                    <nav class="space-y-2">
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg sidebar-item-active" href="convocatoria.php">
                            <span class="material-symbols-outlined text-primary">description</span>
                            <span class="text-sm font-semibold">Convocatoria 2026</span>
                        </a>
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle" href="guias-usuario.php">
                            <span class="material-symbols-outlined text-neutral-muted">menu_book</span>
                            <span class="text-sm font-medium">Guías de Usuario</span>
                        </a>
                        <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle" href="faq.php">
                            <span class="material-symbols-outlined text-neutral-muted">help_outline</span>
                            <span class="text-sm font-medium">FAQ</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <main class="flex-1 bg-white p-8">
                <div class="max-w-4xl">
                    <h1 class="text-4xl font-black mb-10">Convocatoria 2026</h1>
                    
                    <h2 class="text-xl font-bold mb-6 border-l-4 border-primary pl-4 uppercase">Documentación Oficial</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-12">
                        <?php 
                        $docs = [
                            ['n' => 'Convocatoria Final 2026', 'e' => 'PDF', 'f' => 'CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026 FINAL.pdf'],
                            ['n' => 'Formato I - Registro', 'e' => 'DOCX', 'f' => 'FORMATO I CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.docx'],
                            ['n' => 'Resultados Ratificación', 'e' => 'PDF', 'f' => 'RESULTADOS_CONVO_FINANCIAMIENTO_2026.pdf']
                        ];
                        foreach ($docs as $d): ?>
                        <a href="frontend/ConvocatoriaFinanciamiento/<?php echo urlencode($d['f']); ?>" target="_blank" class="flex items-center p-4 border border-neutral-subtle rounded-xl hover:shadow-md transition-all">
                            <span class="material-symbols-outlined text-primary mr-3"><?php echo $d['e']=='PDF'?'picture_as_pdf':'description'; ?></span>
                            <div class="text-left">
                                <p class="text-sm font-bold"><?php echo $d['n']; ?></p>
                                <p class="text-[10px] text-neutral-muted uppercase"><?php echo $d['e']; ?> • Descargar</p>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>

                    <h2 class="text-xl font-bold mb-6 border-l-4 border-neutral-muted pl-4 uppercase">Convocatorias Anteriores</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">
                        <div class="p-4 border border-dashed border-neutral-subtle rounded-xl opacity-60">
                            <p class="text-[10px] font-bold text-neutral-muted uppercase">Ciclo 2025</p>
                            <p class="text-xs font-bold">Financiamiento 2025</p>
                        </div>
                        <div class="p-4 border border-dashed border-neutral-subtle rounded-xl opacity-60">
                            <p class="text-[10px] font-bold text-neutral-muted uppercase">Ciclo 2024</p>
                            <p class="text-xs font-bold">Financiamiento 2024</p>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold mb-6 border-l-4 border-primary pl-4 uppercase">Cronograma</h2>
                    <div class="overflow-hidden rounded-2xl border border-neutral-subtle">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-neutral-subtle/50 text-[10px] font-bold uppercase tracking-widest">
                                <tr><th class="px-6 py-4">Fase</th><th class="px-6 py-4">Fecha</th><th class="px-6 py-4 text-center">Estatus</th></tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-subtle">
                                <?php foreach ($cronograma as $c): $est = obtenerEstatusFecha($c['fin']); ?>
                                <tr class="hover:bg-background-light transition-colors">
                                    <td class="px-6 py-4 font-semibold"><?php echo $c['actividad']; ?></td>
                                    <td class="px-6 py-4 font-mono text-neutral-muted"><?php echo date('d/m/Y', strtotime($c['fin'])); ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border <?php echo $est['clase']; ?>"><?php echo $est['texto']; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>