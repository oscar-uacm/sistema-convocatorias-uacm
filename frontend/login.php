<?php
session_start();
require_once 'conexion.php';

// 1. GESTIÓN DE MENSAJES DINÁMICOS
$mensaje_exito = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sesion_cerrada') {
        $mensaje_exito = "Has cerrado sesión correctamente.";
    } elseif ($_GET['status'] == 'registro_exitoso') {
        $mensaje_exito = "¡Registro completado con éxito! Ya puedes iniciar sesión.";
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $password = $_POST['password'];

    $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $sql);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        if (password_verify($password, $fila['password'])) {
            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['nombre']  = $fila['nombre'];
            $_SESSION['rol']     = $fila['rol'];

            // Redirección por roles
            if ($_SESSION['rol'] === 'admin') {
                header("Location: admin-dashboard.php");
            } elseif ($_SESSION['rol'] === 'evaluador') {
                header("Location: admin-proyectos.php");
            } elseif ($_SESSION['rol'] === 'comite') {
                header("Location: comite-dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "La contraseña ingresada es incorrecta.";
        }
    } else {
        $error = "El correo electrónico no se encuentra registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UACM - Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        body { font-family: 'Lexend', sans-serif; background-color: #fcfaf9; }
        .uacm-red { color: #701705; }
        .bg-uacm { background-color: #701705; }
        .ring-uacm:focus { --tw-ring-color: #701705; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-10 lg:p-12">
            
            <header class="text-center mb-10">
                <div class="inline-flex items-center justify-center size-16 bg-gray-50 rounded-2xl mb-6">
                    <span class="material-symbols-outlined uacm-red text-3xl">lock</span>
                </div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Acceso al Sistema</h1>
                <p class="text-xs font-bold text-gray-400 mt-2 uppercase tracking-widest">Investigación CCyH</p>
            </header>

            <?php if ($mensaje_exito): ?>
                <div class="bg-green-50 border border-green-100 text-green-600 text-[10px] font-bold p-4 rounded-2xl mb-6 text-center uppercase">
                    <?php echo $mensaje_exito; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 text-[10px] font-bold p-4 rounded-2xl mb-6 text-center uppercase">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-4">
                <input type="email" name="correo" required placeholder="Correo Institucional" 
                    class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 ring-uacm border-transparent transition-all">
                
                <input type="password" name="password" required placeholder="Contraseña" 
                    class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 ring-uacm border-transparent transition-all">

                <button type="submit" class="w-full py-4 bg-uacm text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-black transition-all">
                    Iniciar Sesión
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">¿No tienes una cuenta?</p>
                <a href="registro-usuario.php" class="inline-flex items-center gap-2 text-uacm font-black text-xs uppercase tracking-widest hover:underline transition-all">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Registrarse ahora
                </a>
            </div>

            <div class="mt-6 text-center">
                <a href="index.php" class="text-[9px] font-black text-gray-300 hover:text-gray-500 transition-colors uppercase tracking-[0.2em] flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Regresar al Portal
                </a>
            </div>
        </div>
        
        <p class="text-center mt-8 text-[10px] text-gray-400 font-medium uppercase tracking-tighter italic">
            "Nada humano me es ajeno" — UACM 2026
        </p>
    </div>

</body>
</html>