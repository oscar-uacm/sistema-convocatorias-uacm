<?php
session_start();
$logueado = isset($_SESSION['user_id']);
?>
<body class="bg-background-light font-display text-slate-900">
    <main class="max-w-5xl mx-auto px-6 py-16">
        <section class="bg-white rounded-[2.5rem] p-10 md:p-16 shadow-sm border border-slate-200">
            <header class="mb-12">
                <h1 class="text-3xl font-black text-primary-dark flex items-center gap-3">
                    <span class="material-symbols-outlined text-4xl">menu_book</span> Manuales y Guías
                </h1>
                <p class="text-slate-500 mt-2 font-medium">Siga estas recomendaciones para asegurar un registro exitoso.</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div class="flex gap-5">
                        <div class="size-10 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">1</div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Preparación de Datos</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">Tenga a la mano los números de empleado de sus colaboradores y las matrículas de los estudiantes participantes.</p>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <div class="size-10 rounded-full bg-primary text-white flex items-center justify-center font-bold flex-shrink-0">2</div>
                        <div>
                            <h3 class="font-bold text-lg mb-2">Resumen Ejecutivo</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">El sistema requiere un mínimo de descripción técnica. Recomendamos redactar su resumen antes en un documento aparte.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 p-8 rounded-3xl border border-amber-100 self-start">
                    <h4 class="text-amber-800 font-bold flex items-center gap-2 mb-4 italic">
                        <span class="material-symbols-outlined">info</span> Aviso Importante
                    </h4>
                    <p class="text-xs text-amber-700 leading-relaxed font-medium">
                        El sistema utiliza <strong>Sesiones Temporales</strong>. Sus avances se mantienen mientras mantenga abierta su sesión. Si cierra el navegador sin llegar a la confirmación final (Paso 4), deberá iniciar el registro nuevamente.
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>