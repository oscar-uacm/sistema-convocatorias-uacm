<?php
session_start();
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = $logueado ? $_SESSION['nombre'] : '';

// 1. RUTA DE ARCHIVOS
$folder = "ConvocatoriaFinanciamiento/";

// 2. LÓGICA DE ESTATUS POR COLORES
function obtenerEstatusFecha($fecha_fin_str) {
    $fecha_actual = new DateTime(); 
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
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>UACM - Documentación y Convocatorias</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#9c2007",
            "primary-dark": "#701705",
            "background-light": "#f8f6f5",
            "background-dark": "#221310",
            "neutral-subtle": "#f4e9e7",
            "neutral-text": "#1c0f0d",
            "neutral-muted": "#9d5648",
          },
          fontFamily: {
            "display": ["Lexend", "sans-serif"]
          },
          borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
        },
      },
    }
  </script>
  <style>
    body {
      font-family: 'Lexend', sans-serif;
    }

    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    .sidebar-item-active {
      background-color: #f4e9e7;
      border-left: 4px solid #9c2007;
    }

    .btn-download {
      background-color: #701705;
      transition: all 0.3s ease;
    }

    .btn-download:hover {
      background-color: white;
      color: black;
      border: 1px solid black;
    }
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-neutral-text font-display">
  <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <header
      class="flex items-center justify-between whitespace-nowrap border-b border-solid border-neutral-subtle px-10 py-4 bg-white sticky top-0 z-50">
      <div class="flex items-center gap-8">
        <a href="index.php" class="flex items-center gap-4 text-primary">
          <div class="size-10 flex items-center justify-center bg-primary text-white rounded-lg">
            <span class="material-symbols-outlined text-3xl">school</span>
          </div>
          <div class="flex flex-col">
            <h2 class="text-neutral-text text-lg font-bold leading-tight tracking-tight">Colegio de Ciencias y
              Humanidades</h2>
            <span class="text-primary text-xs font-bold tracking-widest uppercase">UACM Portal de Proyectos</span>
          </div>
        </a>
        <nav class="hidden lg:flex items-center gap-9 ml-8">
          <a class="text-neutral-text hover:text-primary text-sm font-medium transition-colors"
            href="index.php">Inicio</a>
          <a class="text-primary text-sm font-bold border-b-2 border-primary" href="convocatoria.php">Convocatorias</a>
          <a class="text-neutral-text hover:text-primary text-sm font-medium transition-colors"
            href="guias-usuario.php">Documentación</a>
          <a class="text-neutral-text hover:text-primary text-sm font-medium transition-colors" href="#">Proyectos</a>
          <a class="text-neutral-text hover:text-primary text-sm font-medium transition-colors" href="#">Contacto</a>
        </nav>
      </div>
      <div class="flex flex-1 justify-end gap-6 items-center">
        <label class="hidden md:flex flex-col min-w-40 h-10 max-w-64">
          <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
            <div
              class="text-neutral-muted flex border-none bg-neutral-subtle items-center justify-center pl-4 rounded-l-lg">
              <span class="material-symbols-outlined text-xl">search</span>
            </div>
            <input
              class="form-input flex w-full min-w-0 flex-1 border-none bg-neutral-subtle focus:ring-0 h-full placeholder:text-neutral-muted px-4 rounded-r-lg text-sm font-normal"
              placeholder="Buscar documentos..." value="" />
          </div>
        </label>
        
        <?php if ($logueado): ?>
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-neutral-text"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                <a href="logout.php" class="flex items-center justify-center rounded-lg h-10 px-5 bg-neutral-subtle text-primary text-sm font-bold hover:bg-primary hover:text-white transition-all">Salir</a>
            </div>
        <?php else: ?>
            <a href="login.php"
            class="flex min-w-[120px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold tracking-wide hover:bg-primary-dark transition-all shadow-sm">
            <span>Iniciar Sesión</span>
            </a>
        <?php endif; ?>
        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 border-2 border-neutral-subtle"
          data-alt="User profile picture in academic setting"
          style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAOZb_m90SpGyis2_SKkKzSqRws_3B3Xr5txIMpWZbqiBmvKihmhuJRFerqnTCPCvp9vYAwMaLCAnMHcI6DJ--sN1R9JFmZl2VvFmgVlp2xAuL1s6kFEcDJVA4DYbTs02AcwZaaQVb5QO3VWSvu9Xxl5oggNq4edRoJ85gBJVEl0Mq-cUtw0cnmeTABGayXZZWBBfZQQ7HmSW9GOC04s9K9Fe-d0jkgMSkhnj0Us2cURvh9J45a-9ul-jufqgDK4tEpbGCL8gJo38Qj");'>
        </div>
      </div>
    </header>
    <div class="flex flex-1 w-full max-w-[1440px] mx-auto">
      <aside
        class="w-80 border-r border-neutral-subtle bg-white hidden md:block overflow-y-auto h-[calc(100vh-73px)] sticky top-[73px]">
        <div class="p-6">
          <h3 class="text-neutral-muted text-xs font-bold uppercase tracking-widest mb-4">Portal de Proyectos</h3>
          <nav class="space-y-1">
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg sidebar-item-active group" href="convocatoria.php">
              <span class="material-symbols-outlined text-primary">description</span>
              <span class="text-neutral-text text-sm font-semibold">Convocatoria 2026</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors group"
              href="guias-usuario.php">
              <span class="material-symbols-outlined text-neutral-muted group-hover:text-primary">menu_book</span>
              <span class="text-neutral-text text-sm font-medium">Guías de Usuario</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors group"
              href="#">
              <span class="material-symbols-outlined text-neutral-muted group-hover:text-primary">cloud_download</span>
              <span class="text-neutral-text text-sm font-medium">Formatos Descargables</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors group"
              href="faq.php">
              <span class="material-symbols-outlined text-neutral-muted group-hover:text-primary">help_outline</span>
              <span class="text-neutral-text text-sm font-medium">Preguntas Frecuentes</span>
            </a>
            <div class="pt-6 mt-6 border-t border-neutral-subtle">
              <h3 class="text-neutral-muted text-xs font-bold uppercase tracking-widest mb-4">Archivo Histórico</h3>
              <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors group"
                href="#">
                <span class="material-symbols-outlined text-neutral-muted group-hover:text-primary">folder_open</span>
                <span class="text-neutral-text text-sm font-medium">Convocatoria 2025</span>
              </a>
              <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-neutral-subtle transition-colors group"
                href="#">
                <span class="material-symbols-outlined text-neutral-muted group-hover:text-primary">folder_open</span>
                <span class="text-neutral-text text-sm font-medium">Convocatoria 2024</span>
              </a>
            </div>
          </nav>
        </div>
        <div class="p-6 mt-auto">
          <div class="bg-primary/5 rounded-xl p-4 border border-primary/10">
            <p class="text-xs text-primary font-bold mb-2 uppercase">¿Necesitas ayuda?</p>
            <p class="text-sm text-neutral-text mb-3">Contáctanos para dudas técnicas sobre la plataforma.</p>
            <a class="text-primary text-sm font-bold flex items-center gap-1 hover:underline"
              href="mailto:soporte@uacm.edu.mx">
              <span class="material-symbols-outlined text-sm">mail</span> soporte@uacm.edu.mx
            </a>
          </div>
        </div>
      </aside>
      <main class="flex-1 bg-white min-h-screen overflow-x-hidden">
        <div class="px-8 pt-8 pb-4">
          <div class="flex items-center gap-2 text-neutral-muted text-xs font-medium mb-6">
            <a class="hover:text-primary transition-colors" href="index.php">Inicio</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a class="hover:text-primary transition-colors" href="#">Documentación</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-neutral-text">Convocatoria 2026</span>
          </div>
          <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 border-b border-neutral-subtle pb-8">
            <div class="max-w-2xl">
              <span
                class="bg-primary/10 text-primary px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4 inline-block">Convocatoria
                Abierta</span>
              <h1 class="text-neutral-text text-4xl font-extrabold leading-tight tracking-tight mb-4">
                Convocatoria de Proyectos de Investigación 2026
              </h1>
              <p class="text-neutral-muted text-lg font-light leading-relaxed">
                Bases, requisitos institucionales y cronograma oficial de actividades para el ciclo académico vigente
                del Colegio de Ciencias y Humanidades.
              </p>
            </div>
            <div class="flex gap-3">
              <?php $url_bases = $folder . rawurlencode("CONVOCATORIA INTERNA PARA EL FINANCIAMIENTO DE PROYECTOS DE INVESTIGACIÓN DEL CCYH 2026 FINAL.pdf"); ?>
              <button onclick="verDocumento('<?php echo $url_bases; ?>', 'Bases Completas')"
                class="flex items-center gap-2 btn-download text-white px-6 py-3 rounded-lg font-bold text-sm shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                Ver Bases Completas
              </button>
            </div>
          </div>
        </div>
        <div class="px-8 py-10 max-w-5xl">
          <section class="mb-12">
            <div class="flex items-center gap-2 mb-6">
              <span class="material-symbols-outlined text-primary">calendar_month</span>
              <h2 class="text-2xl font-bold text-neutral-text">Cronograma Académico 2026</h2>
            </div>
            <div class="overflow-hidden rounded-xl border border-neutral-subtle shadow-sm">
              <table class="w-full text-left border-collapse">
                <thead class="bg-neutral-subtle">
                  <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-neutral-muted">Actividad</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-neutral-muted">Fecha Límite</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-neutral-muted">Estado</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-neutral-subtle">
                  <?php foreach ($cronograma as $item): 
                      $estatus = obtenerEstatusFecha($item['fin']);
                  ?>
                  <tr class="hover:bg-neutral-subtle/30 transition-colors">
                    <td class="px-6 py-5 font-medium text-neutral-text"><?php echo $item['actividad']; ?></td>
                    <td class="px-6 py-5 text-neutral-muted"><?php echo date('d / m / Y', strtotime($item['fin'])); ?></td>
                    <td class="px-6 py-5">
                        <span class="text-[10px] px-3 py-1 rounded font-bold uppercase border bg-white <?php echo $estatus['clase']; ?>">
                            <?php echo $estatus['texto']; ?>
                        </span>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
            </div>
          </section>
          <article class="prose prose-neutral max-w-none mb-16 text-neutral-text">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">verified_user</span>
              Requisitos de Participación
            </h2>
            <p class="leading-relaxed mb-6 text-neutral-muted">
              Para ser considerados, los proyectos deberán alinearse a las líneas de investigación prioritarias del
              Colegio y cumplir estrictamente con los siguientes puntos:
            </p>
            <ul class="space-y-4 mb-8">
              <li class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                <div>
                  <strong class="block">Personal Académico:</strong>
                  <span>Podrán participar docentes e investigadores con contrato vigente en la UACM.</span>
                </div>
              </li>
              <li class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                <div>
                  <strong class="block">Estructura del Protocolo:</strong>
                  <span>Debe incluir justificación, objetivos, metodología, cronograma y presupuesto detallado.</span>
                </div>
              </li>
              <li class="flex items-start gap-4">
                <span class="material-symbols-outlined text-primary mt-1">check_circle</span>
                <div>
                  <strong class="block">Impacto Social:</strong>
                  <span>Los proyectos deberán demostrar un beneficio directo a la comunidad universitaria o al entorno
                    social.</span>
                </div>
              </li>
            </ul>
            
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
              <span class="material-symbols-outlined text-primary">folder_zip</span>
              Documentación de Apoyo
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <?php foreach ($documentos as $doc): 
                  $safe_url = $folder . rawurlencode($doc['archivo']);
                  $ext = strtolower(pathinfo($doc['archivo'], PATHINFO_EXTENSION));
                  $icon = ($ext === 'pdf') ? 'picture_as_pdf' : (($ext === 'xlsx') ? 'table_chart' : 'edit_document');
              ?>
              <div class="flex items-center p-5 border border-neutral-subtle rounded-xl hover:shadow-md transition-shadow group bg-neutral-subtle/10">
                <div onclick="verDocumento('<?php echo $safe_url; ?>', '<?php echo $doc['titulo']; ?>')" class="cursor-pointer size-12 bg-primary/10 text-primary flex items-center justify-center rounded-lg mr-4 hover:bg-primary hover:text-white transition-colors">
                  <span class="material-symbols-outlined text-3xl"><?php echo $icon; ?></span>
                </div>
                <div class="flex-1">
                  <h4 onclick="verDocumento('<?php echo $safe_url; ?>', '<?php echo $doc['titulo']; ?>')" class="font-bold text-sm cursor-pointer hover:text-primary transition-colors"><?php echo $doc['titulo']; ?></h4>
                  <p class="text-[11px] text-neutral-muted uppercase font-bold tracking-widest mt-1"><?php echo strtoupper($ext); ?> • Archivo</p>
                </div>
                <div class="flex items-center gap-1">
                    <button onclick="verDocumento('<?php echo $safe_url; ?>', '<?php echo $doc['titulo']; ?>')" class="p-2 text-primary-dark hover:bg-primary/10 rounded-full transition-colors" title="Previsualizar">
                      <span class="material-symbols-outlined text-xl">visibility</span>
                    </button>
                    <a href="<?php echo $safe_url; ?>" download class="p-2 text-primary-dark hover:bg-primary/10 rounded-full transition-colors" title="Descargar">
                      <span class="material-symbols-outlined text-xl">download</span>
                    </a>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            </article>
        </div>
        <footer class="bg-neutral-subtle/40 border-t border-neutral-subtle px-8 py-12 mt-10">
          <div class="flex flex-col md:flex-row justify-between gap-10">
            <div class="max-w-sm">
              <div class="flex items-center gap-3 text-primary mb-4">
                <span class="material-symbols-outlined text-2xl">school</span>
                <span class="text-lg font-bold tracking-tight text-neutral-text">UACM CCH</span>
              </div>
              <p class="text-sm text-neutral-muted leading-relaxed">
                Universidad Autónoma de la Ciudad de México. Colegio de Ciencias y Humanidades. Portal oficial para la
                gestión y divulgación de proyectos académicos.
              </p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
              <div>
                <h5 class="font-bold text-sm text-neutral-text mb-4">Enlaces Rápidos</h5>
                <ul class="text-sm text-neutral-muted space-y-2">
                  <li><a class="hover:text-primary transition-colors" href="convocatoria.php">Convocatorias</a></li>
                  <li><a class="hover:text-primary transition-colors" href="#">Transparencia</a></li>
                  <li><a class="hover:text-primary transition-colors" href="#">Directorio</a></li>
                </ul>
              </div>
              <div>
                <h5 class="font-bold text-sm text-neutral-text mb-4">Legal</h5>
                <ul class="text-sm text-neutral-muted space-y-2">
                  <li><a class="hover:text-primary transition-colors" href="#">Privacidad</a></li>
                  <li><a class="hover:text-primary transition-colors" href="#">Terminos de uso</a></li>
                  <li><a class="hover:text-primary transition-colors" href="#">Reglamento</a></li>
                </ul>
              </div>
              <div class="col-span-2 md:col-span-1">
                <h5 class="font-bold text-sm text-neutral-text mb-4">Redes Sociales</h5>
                <div class="flex gap-4">
                  <a class="size-10 bg-neutral-subtle rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all"
                    href="#">
                    <span class="material-symbols-outlined text-lg">share</span>
                  </a>
                  <a class="size-10 bg-neutral-subtle rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all"
                    href="#">
                    <span class="material-symbols-outlined text-lg">forum</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div
            class="border-t border-neutral-subtle mt-12 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-neutral-muted gap-4">
            <p>© 2026 Colegio de Ciencias y Humanidades UACM. Todos los derechos reservados.</p>
            <p class="flex items-center gap-2">
              Desarrollado por la Dirección de Informática
              <span class="material-symbols-outlined text-xs">code</span>
            </p>
          </div>
        </footer>
      </main>
    </div>
  </div>

  <div id="modalPDF" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden items-center justify-center z-[100] p-6">
      <div class="bg-white w-full max-w-6xl h-[90vh] rounded-[1rem] overflow-hidden flex flex-col shadow-2xl">
          <div class="px-10 py-6 border-b border-neutral-subtle flex justify-between items-center bg-neutral-subtle/30">
              <h3 id="modalTitle" class="text-sm font-bold uppercase tracking-widest text-primary"></h3>
              <button onclick="cerrarModal()" class="size-10 flex items-center justify-center hover:bg-primary/10 hover:text-primary rounded-full transition-all">
                  <span class="material-symbols-outlined">close</span>
              </button>
          </div>
          <div id="modalBody" class="flex-grow bg-neutral-subtle/10"></div>
      </div>
  </div>

  <script>
      function verDocumento(url, titulo) {
          const ext = url.split('.').pop().toLowerCase();
          if (ext !== 'pdf') {
              alert("La previsualización en navegador solo está disponible para documentos PDF. Por favor, utiliza el botón de 'Descargar' para visualizar archivos Word (.docx) o Excel (.xlsx).");
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