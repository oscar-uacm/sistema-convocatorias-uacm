<?php
// 1. Iniciar el acceso a la sesión actual
session_start();

// 2. Limpiar todas las variables de sesión
$_SESSION = array();

// 3. Borrar la cookie de sesión (Tu código original - Muy recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión en el servidor
session_destroy();

// 5. Redirigir al login con el mensaje de estatus para que aparezca el aviso verde
header("Location: login.php?status=sesion_cerrada");
exit;