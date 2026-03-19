<?php
session_start();
$logueado = isset($_SESSION['user_id']);
?>
<body class="bg-background-light font-display text-slate-900">
    <main class="max-w-4xl mx-auto px-6 py-20">
        <header class="text-center mb-16">
            <h2 class="text-4xl font-black text-primary-dark mb-4 tracking-tight">Preguntas Frecuentes</h2>
            <p class="text-slate-500">Todo lo que necesita saber sobre el portal de registro.</p>
        </header>

        <div class="space-y-4">
            <details class="group bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <summary class="list-none p-6 cursor-pointer flex justify-between items-center hover:bg-slate-50 transition-colors">
                    <span class="font-bold">¿Cómo recupero mi contraseña institucional?</span>
                    <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
                </summary>
                <div class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-50 pt-4">
                    Debe acudir personalmente a la oficina de servicios informáticos de su plantel o enviar un correo desde su cuenta personal a soporte.it@uacm.edu.mx.
                </div>
            </details>

            <details class="group bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <summary class="list-none p-6 cursor-pointer flex justify-between items-center hover:bg-slate-50 transition-colors">
                    <span class="font-bold">¿Puedo editar un proyecto ya enviado?</span>
                    <span class="material-symbols-outlined group-open:rotate-180 transition-transform">expand_more</span>
                </summary>
                <div class="px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-50 pt-4">
                    No. Una vez que finaliza el Paso 4, el proyecto entra en estatus "Enviado" y solo podrá ser editado si el comité evaluador solicita correcciones.
                </div>
            </details>
        </div>
    </main>
</body>
</html>