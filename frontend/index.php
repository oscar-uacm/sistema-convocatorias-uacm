<?php
session_start();
// Lógica para detectar si el usuario ya inició sesión
$logueado = isset($_SESSION['user_id']);
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
?>
<!DOCTYPE html>
<html class="dark" lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>CCyH - Registro de Proyectos 2026</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#701705",
                        "institutional-red": "#9C2007",
                        "background-light": "#f8f6f5",
                        "background-dark": "#221310",
                    },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Lexend', sans-serif; }
        .btn-hover-effect:hover { background-color: white !important; color: black !important; }
        .hero-gradient { background: linear-gradient(rgba(112, 23, 5, 0.8), rgba(34, 19, 16, 0.95)); }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-white overflow-x-hidden">
    <div class="relative flex min-h-screen flex-col">
        
        <header class="sticky top-0 z-50 w-full border-b border-white/10 bg-institutional-red/95 backdrop-blur px-6 lg:px-20 py-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-4xl text-white">account_balance</span>
                    <div class="flex flex-col">
                        <h1 class="text-lg font-bold leading-none tracking-tight">Colegio de Ciencias y Humanidades</h1>
                        <span class="text-xs opacity-80 font-medium uppercase tracking-widest text-[#cc998f]">CCyH UACM</span>
                    </div>
                </div>
                <nav class="hidden md:flex items-center gap-10">
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="index.php">Inicio</a>
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="convocatoria.php">Convocatoria</a>
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="login.php">Registro</a>
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="#">Contacto</a>
                </nav>
                <div class="flex items-center gap-4">
                    <?php if($logueado): ?>
                        <a href="dashboard.php" class="flex items-center justify-center rounded-lg h-10 px-6 bg-primary text-white text-sm font-bold btn-hover-effect transition-all">
                            <span>Mi Panel (<?php echo explode(' ', $nombre_usuario)[0]; ?>)</span>
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="flex items-center justify-center rounded-lg h-10 px-6 bg-primary text-white text-sm font-bold btn-hover-effect transition-all">
                            <span>Iniciar Sesión</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <section class="relative w-full aspect-[21/9] min-h-[500px] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB0z4j5DU4UJSx36jA8gdgQ5lMwR9YsE_B2wLPuKAN2OPS7ksvkXZysm6P6nlTKv7_vdDNr7XALb0_ADoPzTKVL2MqA1xli4UhEZYCX2fnuGh-nhQYmmz9NjgLIrZMdx0TI2iLz67OW4pfcLP4wP2KByUNtU3bRQroDwj9Bz-qNhm588AnIBAmvLiyESQrfljqQ_8efQ57CNS5u-O5A6b97Tn-otP3cS8sJzEk93_jqsnKXbfKtopbN3rXyYycr9DgafpFYcV08n8aT");'>
                <div class="absolute inset-0 hero-gradient"></div>
            </div>
            <div class="relative z-10 max-w-4xl px-6 text-center">
                <span class="inline-block px-4 py-1 mb-6 rounded-full bg-white/10 border border-white/20 text-xs font-bold uppercase tracking-widest">Periodo Académico 2026-2027</span>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight tracking-tight">
                    Convocatoria de <br /><span class="text-white/90">Proyectos de Investigación</span>
                </h1>
                <p class="text-lg md:text-xl text-white/80 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                    Impulsando la excelencia académica y la innovación científica en nuestra comunidad universitaria.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="convocatoria.php" class="flex items-center justify-center h-14 px-10 rounded-lg bg-primary text-white font-bold text-lg btn-hover-effect transition-all shadow-xl">
                        Ver Convocatoria
                    </a>
                </div>
            </div>
        </section>

        <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Portales de Acceso</h2>
                <div class="h-1 w-20 bg-primary mx-auto"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group flex flex-col bg-[#341d18] rounded-xl border border-white/5 overflow-hidden transition-all hover:translate-y-[-8px]">
                    <div class="h-48 bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCTb3qjWEP0CQ6FRr3VQvPXzGpqywRJPYRmBp1L5fxGPceZE2OcOrB22rztKPPw8UDodK_Rh8TR-TuiY6AkZoz850KMkMjmChOtzgby_UBbEUwd5d5ZKxTnGo7FFeomNYOwm_5p5zIL1P0KC_R81nAYWVB4LRtbOLLhfIxG_EjTm_rdUNs-l7a3x0CqTIeQ490tC6P8I_L0GjcMFlyxQm0yQBHRPX3bs7JOvAU42zmfIigtSkOS0BRFyIG8SrzTpuW38jgvgnFuB4Ro");'></div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-4 text-primary">
                            <span class="material-symbols-outlined text-3xl">description</span>
                            <h3 class="text-2xl font-bold text-white">Convocatoria</h3>
                        </div>
                        <p class="text-[#cc998f] text-sm mb-8 flex-1">Consulta las bases legales y criterios de evaluación para el periodo 2026.</p>
                        <a href="convocatoria.php" class="w-full h-12 rounded-lg bg-primary text-white font-bold btn-hover-effect flex items-center justify-center gap-2">Ver Bases</a>
                    </div>
                </div>
                <div class="group flex flex-col bg-[#341d18] rounded-xl border border-white/5 overflow-hidden transition-all hover:translate-y-[-8px] ring-2 ring-primary">
                    <div class="h-48 bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCMjFrdJQazsTIFhPiRLdIGGzQHRRgiNXOrOWNiH9Se9ocWrj96-9R32i_VJufLO70JUMONN_rtGJ_HabILS86JTjVnFewX5l8f1_VaKhKUEgQbBfWZFq2yHT3xEAZjGAhsEl4ss6h3TIPTOSUNqi1t-6izwmF8N7HHUJVEYNsWWEFmyUe608CYjdQMvTTlzCUAkpSIKjwX0BWcZVFO7EDTVHX45RwSXYhj24TTJ_7iytFiUeyzq0cxPDOTMqzSpAq0OY5Td5wHyNVa");'></div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-4 text-primary">
                            <span class="material-symbols-outlined text-3xl">app_registration</span>
                            <h3 class="text-2xl font-bold text-white">Registro</h3>
                        </div>
                        <p class="text-[#cc998f] text-sm mb-8 flex-1">Accede para iniciar tu registro, cargar tu propuesta y dar seguimiento.</p>
                        <a href="<?php echo $logueado ? 'dashboard.php' : 'login.php'; ?>" class="w-full h-12 rounded-lg bg-primary text-white font-bold btn-hover-effect flex items-center justify-center gap-2">
                            <?php echo $logueado ? 'Ir a mi Panel' : 'Iniciar Registro'; ?>
                        </a>
                    </div>
                </div>
                <div class="group flex flex-col bg-[#341d18] rounded-xl border border-white/5 overflow-hidden transition-all hover:translate-y-[-8px]">
                    <div class="h-48 bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC3lqFeKPyrOzllKqGwDi-r932aAZpDZKtsdHzREEzngx2tT7ehnYln3X6TQvz_6vs98g5Qa55IKMgCLKUje7CjUu__WNzc7sxgjEpHCE6W7G6mnSXk6minMy60Dw3b-88d5V800g_FjoNp71yQ-woJfTpc7YMHsWcX7WHCclqvbyf2e48-YcQWnkYgDBOqtkoExszXXYctnkxPJa0Dgx-LcgU9Nev7t7EDjaGr-AAGWjEIiKZhNBub27P_Gja5y8a4Wpg_VsQ9TZz-");'></div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-4 text-primary">
                            <span class="material-symbols-outlined text-3xl">folder_zip</span>
                            <h3 class="text-2xl font-bold text-white">Documentación</h3>
                        </div>
                        <p class="text-[#cc998f] text-sm mb-8 flex-1">Formatos oficiales y manuales de usuario para tu postulación.</p>
                        <a href="convocatoria.php" class="w-full h-12 rounded-lg bg-primary text-white font-bold btn-hover-effect flex items-center justify-center gap-2 text-sm">Descargar Formatos</a>
                    </div>
                </div>
            </div>
        </main>

        <section class="bg-institutional-red/10 py-20 border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6">Compromiso con la Excelencia</h2>
                    <p class="text-lg text-white/70 mb-8 leading-relaxed">El Colegio de Ciencias y Humanidades fomenta una cultura de investigación activa.</p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-4">
                            <span class="material-symbols-outlined text-primary mt-1">verified</span>
                            <div><h4 class="font-bold">Rigor Académico</h4><p class="text-sm text-white/60">Evaluaciones institucionales de calidad.</p></div>
                        </li>
                    </ul>
                </div>
                <div class="rounded-2xl overflow-hidden aspect-video border border-white/10 shadow-2xl bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDDZpvhx2mREmqXzaKVfYM0btixjTiA90vYu4FSExzhM2S8HQ40daZjoQmHV5z9ceaatAqKLYFXmaYv8OyoErUU8aE7a-kyQbQ8MuzbIOZHIGZ_JYH_ndIvTyyPNmwc8-ul-JCxoS_xDVqmXvUMhWrBx6wYs3akrE8rqNgs5lLJUVYz8pUG6T6Kv6hfVzLENbrEQelZc9kSoG3nSfzdb0Sa0OsRrUyYoRiuJwtZde-QcVQRNnUkbOJyura9FPTAmG5WOQ8rvw1uHpMR");'></div>
            </div>
        </section>

        <footer class="bg-[#1a0e0c] border-t border-white/10 py-16 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-3xl text-primary">account_balance</span>
                        <h3 class="font-bold text-xl">CCyH</h3>
                    </div>
                    <p class="text-sm text-white/50">Sistema Central de Registro de Proyectos Académicos. UACM.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-primary">Institución</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        <li><a class="hover:text-white transition-colors" href="#">Normatividad</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Transparencia</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-primary">Soporte</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        <li><a class="hover:text-white transition-colors" href="#">Guía de Usuario</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Mesa de Ayuda</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-primary">Ubicación</h4>
                    <p class="text-sm text-white/60 mb-4">Dr. García Diego 168, Col. Doctores, Ciudad de México.</p>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center text-xs text-white/30">
                <p>© 2026 Colegio de Ciencias y Humanidades. Todos los derechos reservados.</p>
                <div class="flex gap-8">
                    <img alt="UACM Seal" class="h-10 opacity-40 grayscale brightness-200" src="https://uacm.edu.mx/Portals/0/adam/blueimp%20Gallery/lf4XAVimxkeVIqZR5_Hc9Q/Images/anuncio-cuadrado-chikauak-catrin.jpg?w=200" />
                </div>
            </div>
        </footer>
    </div>
</body>
</html>