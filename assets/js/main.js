document.addEventListener('DOMContentLoaded', () => {
    injectComponents();
    initNavigation();
    initForms();
    initModals();
    checkAuth();
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
                    <div class="flex flex-col">
                        <h1 class="text-lg font-bold leading-none tracking-tight">Colegio de Ciencias y Humanidades</h1>
                        <span class="text-xs opacity-80 font-medium uppercase tracking-widest text-[#cc998f]">CCyH UACM</span>
                    </div>
                </div>
                <nav class="hidden md:flex items-center gap-10">
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="index.html">Inicio</a>
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="convocatoria.html">Convocatoria</a>
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="login.html">Registro</a>
                    <a class="text-sm font-medium hover:text-white/70 transition-colors" href="faq.html">Contacto</a>
                </nav>
                <div class="flex items-center gap-4">
                    <a href="login.html" id="header-auth-btn" class="flex items-center justify-center rounded-lg h-10 px-6 bg-primary text-white text-sm font-bold btn-hover-effect transition-all duration-200">
                        <span>Iniciar Sesión</span>
                    </a>
                </div>
            </div>
        </header>`,

    researcherSidebar: `
        <aside class="w-72 bg-institutional-red text-white flex flex-col shrink-0 shadow-xl z-20 h-screen sticky top-0">
            <a href="index.html" class="p-6 flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg">
                    <span class="material-symbols-outlined text-institutional-red text-3xl">account_balance</span>
                </div>
                <div class="flex flex-col text-white">
                    <h1 class="font-bold text-lg leading-tight">UACM</h1>
                    <p class="text-xs opacity-80 uppercase tracking-wider">Portal de Proyectos</p>
                </div>
            </a>
            <nav class="flex-1 px-4 mt-6 space-y-2">
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors nav-link" href="dashboard.html" data-page="dashboard.html">
                    <span class="material-symbols-outlined">home</span>
                    <span class="font-medium">Inicio</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors nav-link" href="#">
                    <span class="material-symbols-outlined">folder_shared</span>
                    <span class="font-medium">Mis Proyectos</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors nav-link" href="registro.html" data-page="registro.html">
                    <span class="material-symbols-outlined">add_circle</span>
                    <span class="font-medium">Nuevo Proyecto</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors nav-link" href="#">
                    <span class="material-symbols-outlined">fact_check</span>
                    <span class="font-medium">Evaluaciones</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors nav-link" href="#">
                    <span class="material-symbols-outlined">person</span>
                    <span class="font-medium">Perfil</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/20 bg-white/10 mt-8 transition-colors border border-white/20" href="admin-dashboard.html">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                    <span class="font-medium text-sm">Vista Administrador</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <div class="bg-white/10 rounded-xl p-4 flex items-center gap-3">
                    <div class="size-10 rounded-full bg-primary/20 flex items-center justify-center border border-white/20 overflow-hidden">
                        <img alt="Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCxwnyD1CuU7RmMmTsDsQtwYoymU3db6WfRLyV29yqeWIqRwvjD1vGD0_koqYWfzTkB-IAoslN191ZRu4Y53HrgFZiXYy2uH8gwjPm8rXsU2n_y8AC7ZB3_1VIYYr_m4IJcf9E7RUndXO7mh_gT-8y2dIfk82_gnE4SDQvzVVkJ10ygnT7qg23ZzpyA-Tz8Nh3kRT0W0aVdT4uALTK9kyx7TU3MDGyV1quC_IjVMrTvk5rXhjTobHLUbgZWaQun9HDgQkYKZvUy46g5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate user-name text-white">Cargando...</p>
                        <p class="text-[10px] opacity-70 truncate text-white">Colegio de CCH</p>
                    </div>
                </div>
                <a href="#logout" class="logout-link mt-4 w-full flex items-center justify-center gap-2 py-2 text-sm font-medium hover:text-red-200 transition-colors text-white">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar Sesión
                </a>
            </div>
        </aside>`,

    adminSidebar: `
        <aside class="w-64 bg-primary flex flex-col h-screen sticky top-0 shadow-xl">
            <a href="index.html" class="p-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-3xl">school</span>
                </div>
                <div>
                    <h1 class="text-white text-lg font-bold leading-tight">UACM CCH</h1>
                    <p class="text-white/70 text-xs uppercase tracking-wider font-semibold">Administración</p>
                </div>
            </a>
            <nav class="flex-1 px-4 py-4 space-y-2">
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-all font-medium nav-link" href="admin-dashboard.html" data-page="admin-dashboard.html">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span>Inicio</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-all font-medium nav-link" href="admin-proyectos.html" data-page="admin-proyectos.html">
                    <span class="material-symbols-outlined">folder_open</span>
                    <span>Proyectos</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-all font-medium nav-link" href="admin-usuarios.html" data-page="admin-usuarios.html">
                    <span class="material-symbols-outlined">group</span>
                    <span>Usuarios</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-all font-medium nav-link" href="admin-evaluacion.html" data-page="admin-evaluacion.html">
                    <span class="material-symbols-outlined">fact_check</span>
                    <span>Evaluaciones</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-4 p-2">
                    <div class="w-9 h-9 rounded-full bg-cover bg-center border border-white/20" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBkpsdXt9oe4fdRzmWjrfDX6CxUtXfy4Mb7XhTogigzZgcqcY8Ki-WedhNaS0cfbBGl0mOtZwsG4zQ1hDXHTUHQsCM1cft6ChxXV0i11le-1y6vXjpt_sOXMCTmebmAMJSBEf44ZXV8p6gNTwELcP0UpCCJ-NxLcxeDSGkycnX9EAlS62NghSTbT4eR-pcge9M29qSZGKtmEe-zEirH_8KN4qd6yvfCtWNwZSxu18oIGtBYJodJhIKH-SgnZtl4pDK0sy3Wfecvr-kB')"></div>
                    <div class="text-left overflow-hidden">
                        <p class="text-xs font-bold text-white truncate user-name">Admin UACM</p>
                        <p class="text-[10px] text-white/60 font-medium uppercase truncate">Super Administrador</p>
                    </div>
                </div>
                <a href="#logout" class="logout-link w-full flex items-center justify-center gap-2 bg-white/10 text-white px-4 py-2.5 rounded-lg font-bold text-sm shadow-md hover:bg-white/20 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Cerrar Sesión
                </a>
            </div>
        </aside>`,

    footer: `
        <footer class="bg-[#1a0e0c] border-t border-white/10 py-16 px-6 mt-auto">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-3 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="font-bold text-xl leading-tight text-white">CCyH</h3>
                    </div>
                    <p class="text-sm text-white/50 leading-relaxed">
                        Sistema Central de Registro de Proyectos Académicos. Colegio de Ciencias y Humanidades, UACM.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-primary">Institución</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        <li><a class="hover:text-white transition-colors" href="#">Directorio</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Transparencia</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Normatividad</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Aviso de Privacidad</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-primary">Soporte</h4>
                    <ul class="space-y-3 text-sm text-white/60">
                        <li><a class="hover:text-white transition-colors" href="faq.html">Mesa de Ayuda</a></li>
                        <li><a class="hover:text-white transition-colors" href="guias-usuario.html">Guía de Usuario</a></li>
                        <li><a class="hover:text-white transition-colors" href="faq.html">Preguntas Frecuentes</a></li>
                        <li><a class="hover:text-white transition-colors" href="#">Contacto Técnico</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-6 text-sm uppercase tracking-widest text-primary">Ubicación</h4>
                    <p class="text-sm text-white/60 mb-4 leading-relaxed">
                        Dr. García Diego 168, Col. Doctores, Alcaldía Cuauhtémoc, C.P. 06720, Ciudad de México.
                    </p>
                    <div class="flex gap-4">
                        <a class="text-white/40 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">public</span></a>
                        <a class="text-white/40 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">alternate_email</span></a>
                        <a class="text-white/40 hover:text-white transition-colors" href="#"><span class="material-symbols-outlined">share</span></a>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs text-white/30">© 2024 Colegio de Ciencias y Humanidades. Todos los derechos reservados.</p>
                <div class="flex gap-8">
                    <img alt="UACM Seal" class="opacity-40 grayscale brightness-200 h-8" src="https://uacm.edu.mx/Portals/0/adam/blueimp%20Gallery/lf4XAVimxkeVIqZR5_Hc9Q/Images/anuncio-cuadrado-chikauak-catrin.jpg?w=500&h=500&mode=max" />
                </div>
            </div>
        </footer>`
};

function injectComponents() {
    const headerRoot = document.getElementById('header-root');
    const footerRoot = document.getElementById('footer-root');
    const path = window.location.pathname;

    if (headerRoot) {
        if (path.includes('admin-')) {
            headerRoot.innerHTML = TEMPLATES.adminSidebar;
        } else if (path.includes('dashboard.html') || path.includes('registro') || path.includes('registro-exito')) {
            headerRoot.innerHTML = TEMPLATES.researcherSidebar;
        } else {
            headerRoot.innerHTML = TEMPLATES.publicHeader;
        }

        // Mark active link
        const currentFile = path.split('/').pop() || 'index.html';
        const activeLink = headerRoot.querySelector(`[data-page="${currentFile}"]`);
        if (activeLink) {
            if (activeLink.classList.contains('nav-link')) {
                activeLink.classList.add('bg-white/10', 'text-white', 'border-l-4', 'border-white');
            }
        }
    }

    if (footerRoot) {
        footerRoot.innerHTML = TEMPLATES.footer;
    }
}

/**
 * Handles mock authentication state
 */
function checkAuth() {
    const user = JSON.parse(localStorage.getItem('cch_user'));
    const isPublicPage = ['index.html', 'login.html', 'convocatoria.html', 'faq.html', 'guias-usuario.html'].some(page => window.location.pathname.includes(page)) || window.location.pathname === '/' || window.location.pathname.endsWith('CCH_Portal_Funcional/');

    if (!user && !isPublicPage) {
        window.location.href = 'login.html';
    }

    // Update UI based on auth state
    if (user) {
        const userElements = document.querySelectorAll('.user-name');
        userElements.forEach(el => el.textContent = user.name);
    }
}

/**
 * Global navigation links and behavior
 */
function initNavigation() {
    const loginBtn = document.querySelector('a[href="login.html"]');
    if (loginBtn && localStorage.getItem('cch_user')) {
        loginBtn.innerHTML = '<span>Mi Panel</span>';
        const user = JSON.parse(localStorage.getItem('cch_user'));
        loginBtn.href = user.role === 'admin' ? 'admin-dashboard.html' : 'dashboard.html';
    }

    // Logout functionality
    document.querySelectorAll('.logout-link, [href="#logout"]').forEach(el => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            localStorage.removeItem('cch_user');
            window.location.href = 'index.html';
        });
    });
}

/**
 * Mock form handling and Wizard state
 */
function initForms() {
    // Login form simulation
    const loginForm = document.querySelector('form');
    if (window.location.pathname.includes('login.html') && loginForm) {
        const submitBtn = loginForm.querySelector('button[type="button"]');
        if (submitBtn) {
            submitBtn.onclick = null; // Remove inline onclick
            submitBtn.addEventListener('click', () => {
                const email = loginForm.querySelector('#email').value;
                const role = email.includes('admin') ? 'admin' : 'researcher';
                const user = {
                    email: email,
                    name: email.split('@')[0],
                    role: role
                };
                localStorage.setItem('cch_user', JSON.stringify(user));
                window.location.href = role === 'admin' ? 'admin-dashboard.html' : 'dashboard.html';
            });
        }
    }

    // Wizard Persistence
    const wizardForms = ['registro.html', 'registro-paso2.html', 'registro-paso3.html', 'registro-paso4.html'];
    if (wizardForms.some(f => window.location.pathname.includes(f))) {
        restoreWizardData();
        const nextBtn = document.querySelector('a[href*="registro-paso"], a[href="registro-exito.html"]');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => saveWizardData());
        }
    }
}

function saveWizardData() {
    const formData = JSON.parse(localStorage.getItem('cch_wizard_data') || '{}');
    document.querySelectorAll('input, textarea, select').forEach(input => {
        if (input.id) formData[input.id] = input.value;
    });
    localStorage.setItem('cch_wizard_data', JSON.stringify(formData));
}

function restoreWizardData() {
    const formData = JSON.parse(localStorage.getItem('cch_wizard_data') || '{}');
    Object.keys(formData).forEach(key => {
        const input = document.getElementById(key);
        if (input) input.value = formData[key];
    });
}

/**
 * Modal handling
 */
function initModals() {
    // General modal triggers
    document.querySelectorAll('[data-modal-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal-target');
            document.getElementById(modalId)?.classList.remove('hidden');
        });
    });

    // Specific logic for 'Add New User' modal in admin-usuarios.html
    const addUserBtn = document.querySelector('button:has(span:contains("person_add")), button:contains("Nuevo Usuario"), .bg-primary:contains("Nuevo")');
    const modal = document.querySelector('.fixed.inset-0.z-50'); // Likely the modal container

    if (addUserBtn && modal) {
        addUserBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.remove('hidden');
        });
    }

    document.querySelectorAll('[onclick*="hidden"], .modal-close').forEach(btn => {
        btn.onclick = null;
        btn.addEventListener('click', () => {
            const m = btn.closest('.fixed.inset-0') || modal;
            if (m) m.classList.add('hidden');
        });
    });
}
