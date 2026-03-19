<?php
session_start();
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = $logueado ? $_SESSION['nombre'] : '';

// 1. RUTA DE ARCHIVOS
$folder = "ConvocatoriaFinanciamiento/";

// 2. LÓGICA DE ESTATUS POR COLORES (Basada en tu original)
function obtenerEstatusFecha($fecha_fin_str) {
    $fecha_actual = new DateTime(); // Hoy es 16 de Marzo de 2026 según el sistema
    $fecha_fin = new DateTime($fecha_fin_str);
    
    if ($fecha_actual > $fecha_fin) {
        return ['clase' => 'bg-red-100 text-red-700 border-red-200', 'texto' => 'Concluido'];
    }
    
    $diff = $fecha_actual->diff($fecha_fin)->days;
    
    if ($diff <= 3) {
        return ['clase' => 'bg-orange-100 text-orange-700 border-orange-400 animate-pulse', 'texto' => 'Cierre Inminente'];
    }
    if ($diff <= 7) {
        return ['clase' => 'bg-yellow-100 text-yellow-700 border-yellow-400', 'texto' => 'Próximo a vencer'];
    }
    return ['clase' => 'bg-green-100 text-green-700 border-green-200', 'texto' => 'Vigente'];
}

// 3. CRONOGRAMA REAL (Extraído del PDF de la Convocatoria)
$cronograma = [
    ['actividad' => 'Publicación de Convocatoria', 'fin' => '2025-11-07'],
    ['actividad' => 'Límite de Solicitud de Registro', 'fin' => '2025-12-05'],
    ['actividad' => 'Evaluación de Propuestas', 'fin' => '2026-01-30'],
    ['actividad' => 'Publicación de Resultados', 'fin' => '2026-02-09'],
    ['actividad' => 'Inicio de Proyectos', 'fin' => '2026-02-10'],
    ['actividad' => 'Entrega 1er Informe (Parcial)', 'fin' => '2026-08-07'],
    ['actividad' => 'Entrega Informe Final', 'fin' => '2027-02-21']
];

$documentos = [
    ["titulo" => "Convocatoria 2026 (Bases Completas)", "archivo" => "CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026 FINAL.pdf"],
    ["titulo" => "Glosario de Términos", "archivo" => "GLOSARIO DE LA CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.pdf"],
    ["titulo" => "Formato I - Registro de Proyecto", "archivo" => "FORMATO I CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.docx"],
    ["titulo" => "Formato II - Protocolo de Investigación", "archivo" => "FORMATO II CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.docx"],
    ["titulo" => "Formato III - Presupuesto Detallado", "archivo" => "FORMATO III CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026.xlsx"],
    ["titulo" => "Acta de Resultados (Ratificación)", "archivo" => "RESULTADOS_CONVO_FINANCIAMIENTO_2026.pdf"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UACM - Convocatoria de Investigación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        body { font-family: 'Lexend', sans-serif; background-color: #fcfaf9; }
        .uacm-red { color: #701705; }
        .uacm-bg { background-color: #701705; }
    </style>
</head>
<body class="min-h-screen flex">

    <aside class="w-80 bg-white border-r border-gray-100 h-screen sticky top-0 p-10 flex flex-col justify-between hidden lg:flex">
        <div>
            <div class="flex items-center gap-4 mb-12">
                <div class="uacm-bg p-2 rounded-2xl text-white font-black text-xl">UACM</div>
                <div class="leading-tight">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Investigación</h2>
                    <p class="text-xs font-bold uacm-red italic">"Nada humano me es ajeno"</p>
                </div>
            </div>
            <nav class="space-y-3">
                <a href="index.php" class="flex items-center gap-4 px-6 py-4 text-gray-400 hover:text-uacm-red font-bold text-xs uppercase tracking-widest transition-all">
                    <span class="material-symbols-outlined">home</span> Inicio
                </a>
                <a href="#" class="flex items-center gap-4 px-6 py-4 bg-gray-50 uacm-red rounded-[1.5rem] font-bold text-xs uppercase tracking-widest border border-gray-100">
                    <span class="material-symbols-outlined">description</span> Convocatoria
                </a>
            </nav>
        </div>
    </aside>

    <main class="flex-1 p-8 lg:p-16">
        <header class="max-w-6xl mx-auto mb-16">
            <h1 class="text-5xl font-black text-gray-900 tracking-tighter leading-none mb-6">Financiamiento <span class="uacm-red">2026</span></h1>
            <p class="text-gray-400 text-lg font-medium max-w-2xl">Gestión de proyectos de investigación para profesores de tiempo indeterminado adscritos al CCyH.</p>
        </header>

        <div class="max-w-6xl mx-auto grid grid-cols-1 xl:grid-cols-12 gap-16">
            <div class="xl:col-span-8 space-y-16">
                
                <section>
                    <div class="flex items-center gap-4 mb-10">
                        <span class="h-px flex-1 bg-gray-100"></span>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-300">Expediente Oficial</h3>
                        <span class="h-px flex-1 bg-gray-100"></span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($documentos as $doc): 
                            $safe_url = $folder . rawurlencode($doc['archivo']);
                        ?>
                        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-sm hover:shadow-xl hover:scale-[1.02] transition-all group">
                            <div class="flex justify-between items-start mb-6">
                                <div class="size-12 bg-gray-50 rounded-2xl flex items-center justify-center uacm-red group-hover:bg-uacm-bg group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-2xl">
                                        <?php echo (strpos($doc['archivo'], '.pdf') !== false) ? 'picture_as_pdf' : 'description'; ?>
                                    </span>
                                </div>
                                <span class="text-[9px] font-bold text-gray-300 uppercase"><?php echo pathinfo($doc['archivo'], PATHINFO_EXTENSION); ?></span>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm mb-8 leading-snug"><?php echo $doc['titulo']; ?></h4>
                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="verDocumento('<?php echo $safe_url; ?>', '<?php echo $doc['titulo']; ?>')" 
                                        class="py-3 text-[9px] font-black uppercase bg-gray-50 text-gray-400 rounded-xl hover:bg-uacm-bg/5 hover:text-uacm-red transition-all">Ver</button>
                                <a href="<?php echo $safe_url; ?>" download 
                                   class="py-3 text-[9px] font-black uppercase bg-black text-white text-center rounded-xl hover:bg-uacm-bg transition-all">Bajar</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section>
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-300 mb-10">Preguntas del Programa</h3>
                    <div class="space-y-4">
                        <details class="group bg-white rounded-3xl border border-gray-50 p-6 cursor-pointer">
                            <summary class="flex justify-between items-center list-none font-bold text-xs uppercase tracking-widest text-gray-600 group-open:uacm-red">
                                ¿Cuáles son los requisitos para ser RT?
                                <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
                            </summary>
                            <p class="mt-6 text-sm text-gray-400 leading-relaxed pl-4 border-l-2 border-uacm-red/20">
                                Según el numeral III, el Responsable Técnico (RT) debe ser profesor/a de tiempo indeterminado adscrito al CCyH, activo y dictaminado favorablemente.
                            </p>
                        </details>
                        <details class="group bg-white rounded-3xl border border-gray-50 p-6 cursor-pointer">
                            <summary class="flex justify-between items-center list-none font-bold text-xs uppercase tracking-widest text-gray-600 group-open:uacm-red">
                                ¿Cuántos informes se deben entregar?
                                <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
                            </summary>
                            <p class="mt-6 text-sm text-gray-400 leading-relaxed pl-4 border-l-2 border-uacm-red/20">
                                Dos informes: uno parcial en la primera semana de agosto de 2026 y un informe final en la tercera semana de febrero de 2027 (Apartado X del PDF).
                            </p>
                        </details>
                    </div>
                </section>
            </div>

            <div class="xl:col-span-4">
                <div class="bg-white p-10 rounded-[3rem] border border-gray-50 shadow-sm sticky top-12">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-300 mb-12 flex items-center gap-3">
                        <span class="material-symbols-outlined text-lg">event_note</span> Agenda 2026
                    </h3>
                    <div class="space-y-10">
                        <?php foreach ($cronograma as $item): 
                            $estatus = obtenerEstatusFecha($item['fin']);
                        ?>
                        <div class="relative pl-8 border-l-2 border-gray-50 pb-2">
                            <div class="absolute -left-[9px] top-0 size-4 rounded-full bg-white border-4 <?php echo (strpos($estatus['clase'], 'red') !== false) ? 'border-red-500' : 'border-neutral-200'; ?>"></div>
                            <p class="text-[9px] font-black text-gray-300 uppercase mb-2 tracking-widest"><?php echo $item['actividad']; ?></p>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-sm font-bold text-gray-900"><?php echo date('d/m/Y', strtotime($item['fin'])); ?></span>
                                <span class="text-[8px] font-black px-3 py-1 rounded-full border <?php echo $estatus['clase']; ?> uppercase"><?php echo $estatus['texto']; ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="modalPDF" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden items-center justify-center z-[100] p-6">
        <div class="bg-white w-full max-w-6xl h-[90vh] rounded-[3rem] overflow-hidden flex flex-col shadow-2xl">
            <div class="px-10 py-6 border-b flex justify-between items-center bg-gray-50/50">
                <h3 id="modalTitle" class="text-[10px] font-black uppercase tracking-widest uacm-red"></h3>
                <button onclick="cerrarModal()" class="size-12 flex items-center justify-center hover:bg-red-50 hover:text-red-600 rounded-full transition-all">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div id="modalBody" class="flex-grow"></div>
        </div>
    </div>

    <script>
        function verDocumento(url, titulo) {
            const ext = url.split('.').pop().toLowerCase();
            if (ext !== 'pdf') {
                alert("La previsualización solo está disponible para documentos PDF. Use 'Descargar' para Word/Excel.");
                return;
            }
            document.getElementById('modalTitle').innerText = titulo;
            document.getElementById('modalBody').innerHTML = `<iframe src="${url}" class="w-full h-full border-none"></iframe>`;
            document.getElementById('modalPDF').classList.remove('hidden');
            document.getElementById('modalPDF').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModal() {
            document.getElementById('modalPDF').classList.add('hidden');
            document.getElementById('modalBody').innerHTML = '';
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>