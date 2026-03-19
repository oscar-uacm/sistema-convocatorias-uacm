/**
 * ARCHIVO: main.js
 * Función: Control de interfaz, navegación y envío de datos al servidor.
 */

document.addEventListener('DOMContentLoaded', () => {
    injectComponents();
    initNavigation();
    initForms();
    initModals();
    checkAuth(); // Verifica sesión pero sin expulsar por ahora
});

const TEMPLATES = {
    publicHeader: `
        <header class="sticky top-0 z-50 w-full border-b border-white/10 bg-institutional-red/95 backdrop-blur px-6 lg:px-20 py-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex flex-col text-white text-left">
                        <h1 class="text-lg font-bold leading-none tracking-tight">Colegio de Ciencias y Humanidades</h1>
                        <span class="text-xs opacity-80 font-medium uppercase tracking-widest text-[#cc998f]">CCyH UACM</span>
                    </div>
                </div>
                <nav class="hidden md:flex items-center gap-10">
                    <a class="text-sm font-medium text-white hover:text-white/70" href="index.html">Inicio</a>
                    <a class="text-sm font-medium text-white hover:text-white/70" href="convocatoria.html">Convocatoria</a>
                    <a class="text-sm font-medium text-white hover:text-white/70" href="login.html">Registro</a>
                </nav>
                <div class="flex items-center gap-4">
                    <a href="login.html" class="rounded-lg h-10 px-6 bg-white text-institutional-red text-sm font-bold flex items-center">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </header>`,

    researcherSidebar: `
        <aside class="w-72 bg-institutional-red text-white flex flex-col shrink-0 shadow-xl z-20 h-screen sticky top-0">
            <div class="p-6 flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg">
                    <span class="material-symbols-outlined text-institutional-red text-3xl">account_balance</span>
                </div>
                <div class="flex flex-col text-white text-left">
                    <h1 class="font-bold text-lg leading-tight">UACM</h1>
                    <p class="text-xs opacity-80 uppercase tracking-wider">Portal de Proyectos</p>
                </div>
            </div>
            <nav class="flex-1 px-4 mt-6 space-y-2">
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white" href="dashboard.html">
                    <span class="material-symbols-outlined">home</span>
                    <span class="font-medium">Inicio</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 text-white" href="registro.html">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span class="font-medium">Nuevo Proyecto</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <div class="bg-white/10 rounded-xl p-4 flex items-center gap-3">
                    <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center border border-white/20">
                        <span class="material-symbols-outlined text-white">person</span>
                    </div>
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-semibold truncate user-name text-white">Invitado</p>
                        <p class="text-[10px] opacity-70 text-white">Colegio de CCH</p>
                    </div>
                </div>
                <a href="#" class="logout-link mt-4 w-full flex items-center justify-center gap-2 py-2 text-sm text-white hover:text-red-200">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar Sesión
                </a>
            </div>
        </aside>`,

    footer: `
        <footer class="bg-[#1a0e0c] border-t border-white/10 py-16 px-6 mt-auto">
            <div class="max-w-7xl mx-auto text-center">
                <p class="text-sm text-white/50">© 2026 Colegio de Ciencias y Humanidades. UACM.</p>
            </div>
        </footer>`
};

// 1. Inyectar componentes (Header/Footer)
function injectComponents() {
    const headerRoot = document.getElementById('header-root');
    const footerRoot = document.getElementById('footer-root');
    const path = window.location.pathname.toLowerCase();

    if (headerRoot) {
        if (path.includes('dashboard') || path.includes('registro')) {
            headerRoot.innerHTML = TEMPLATES.researcherSidebar;
        } else {
            headerRoot.innerHTML = TEMPLATES.publicHeader;
        }
    }
    if (footerRoot) footerRoot.innerHTML = TEMPLATES.footer;
}

// 2. Control de Seguridad (MODIFICADO: No expulsa al usuario)
function checkAuth() {
    const user = JSON.parse(localStorage.getItem('cch_user'));
    if (user) {
        const userElements = document.querySelectorAll('.user-name');
        userElements.forEach(el => el.textContent = user.name);
    }
}

// 3. Inicializar navegación y Logout
function initNavigation() {
    document.querySelectorAll('.logout-link').forEach(el => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            localStorage.removeItem('cch_user');
            window.location.href = 'login.html';
        });
    });
}

// 4. Manejo de Formularios y el botón Siguiente
function initForms() {
    const path = window.location.pathname.toLowerCase();
    
    // Si estamos en la página de registro
    if (path.includes('registro')) {
        // Buscamos el botón que tenga el href al paso 2
        const nextBtn = document.querySelector('a[href*="paso2"], button#btn-siguiente');
        if (nextBtn) {
            nextBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                await guardarPaso1();
            });
        }
    }
}

// 5. Función principal para guardar en la Base de Datos
async function guardarPaso1() {
    // Obtenemos los valores. IMPORTANTE: Los IDs deben coincidir con tu HTML
    const title = document.getElementById('project-title')?.value || document.getElementById('titulo')?.value;
    const area = document.getElementById('area')?.value;
    const startDate = document.getElementById('start-date')?.value || document.getElementById('fecha_inicio')?.value;
    const abstract = document.getElementById('abstract')?.value || document.getElementById('resumen')?.value;

    if (!title || !area || !startDate || !abstract) {
        alert("Por favor completa todos los campos obligatorios.");
        return;
    }

    // Feedback visual
    const nextBtn = document.querySelector('a[href*="paso2"], button#btn-siguiente');
    const originalText = nextBtn.innerHTML;
    nextBtn.innerHTML = "Guardando...";
    nextBtn.style.opacity = "0.5";
    nextBtn.style.pointerEvents = "none";

    try {
        const response = await fetch('/api/registro/paso1', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title, area, startDate, abstract })
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Datos guardados en MySQL:", data);
            // Guardamos el ID del proyecto para los siguientes pasos
            localStorage.setItem('proyecto_id', data.id);
            // Redirigimos al paso 2
            window.location.href = 'registro-paso2.html';
        } else {
            throw new Error("Error en el servidor");
        }
    } catch (error) {
        console.error("Error al conectar con Docker:", error);
        alert("Error de conexión con el servidor. Verifica que Node.js esté corriendo.");
        nextBtn.innerHTML = originalText;
        nextBtn.style.opacity = "1";
        nextBtn.style.pointerEvents = "auto";
    }
}

// 6. Modales (Cierre y apertura)
function initModals() {
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.fixed');
            if (modal) modal.classList.add('hidden');
        });
    });
}