# Sistema de Registro y Evaluación de Proyectos UACM

Este es un sistema web autogestionable diseñado para administrar las convocatorias, registro y evaluación de proyectos de investigación. 

## Tecnologías Utilizadas
* **Frontend:** HTML, CSS (TailwindCSS), JavaScript
* **Backend:** PHP / Node.js
* **Base de Datos:** MySQL (Ejecutado en Docker)

##  Requisitos Previos
Para poder ejecutar este proyecto en tu computadora local, necesitas tener instalado:
* [Docker Desktop](https://www.docker.com/products/docker-desktop)
* [Node.js](https://nodejs.org/)
* Git

##  Instrucciones de Instalación

**1. Clonar el repositorio**
Abre tu terminal y ejecuta:
\`\`\`bash
git clone https://github.com/oscar-uacm/sistema-convocatorias-uacm
cd bd_convocatoria
\`\`\`

**2. Instalar dependencias**
Como el proyecto usa Node.js, debes instalar los módulos necesarios:
\`\`\`bash
npm install
\`\`\`

**3. Configurar la Base de Datos (Docker)**
Asegúrate de tener Docker abierto. Levanta tu contenedor de MySQL e importa la estructura de la base de datos incluida en este proyecto:
\`\`\`bash
# Comando  para iniciar tu docker,  docker-compose up -d
\`\`\`
Una vez que MySQL esté corriendo, importa las tablas usando el archivo `estructura_bd.sql`.

**4. Configurar conexión**
Renombra el archivo de configuración (si aplica) o asegúrate de que tu archivo `conexion.php` apunte al usuario y contraseña de tu contenedor de Docker.

**5. Iniciar el servidor**
\`\`\`bash
# ( comando que usas para arrancar el proyecto, 
\`\`\`

##  Notas de Seguridad
El usuario administrador por defecto debe ser configurado directamente en la base de datos una vez inicializada. Por seguridad, no se incluyen scripts de recuperación de contraseñas de administrador en este repositorio público.