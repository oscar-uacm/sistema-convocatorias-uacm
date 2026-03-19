<?php
session_start();
require_once 'conexion.php';

// Mensajes de estado
$mensaje_exito = "";
if (isset($_GET['status']) && $_GET['status'] == 'sesion_cerrada') {
    $mensaje_exito = "Has cerrado sesión correctamente.";
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $password = $_POST['password'];

    $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $sql);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        if (password_verify($password, $fila['password'])) {
            
            // Seteamos la sesión
            $_SESSION['user_id'] = $fila['id'];
            $_SESSION['nombre']  = $fila['nombre'];
            $_SESSION['rol']     = $fila['rol'];

            // Redirección inmediata según el rol
            if ($_SESSION['rol'] === 'admin') {
                header("Location: admin-dashboard.php");
            } elseif ($_SESSION['rol'] === 'evaluador') {
                header("Location: admin-proyectos.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El correo no está registrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>UACM - Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body class="bg-[#f8f6f5] min-h-screen flex flex-col items-center justify-center p-4">

    <?php if ($mensaje_exito): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 text-xs font-bold rounded-2xl flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <?php echo $mensaje_exito; ?>
        </div>
    <?php endif; ?>

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-xl p-10 border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-[#701705] italic tracking-tighter uppercase">UACM</h1>
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-2">Acceso al Sistema</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 text-[10px] font-bold p-4 rounded-2xl mb-6 flex items-center gap-2 uppercase">
                <span class="material-symbols-outlined text-sm">error</span> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <input type="email" name="correo" required placeholder="Correo Institucional" class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 focus:ring-[#701705]/10 border-transparent focus:border-[#701705]">
            <input type="password" name="password" required placeholder="Contraseña" class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 focus:ring-[#701705]/10 border-transparent focus:border-[#701705]">
            <button type="submit" class="w-full py-4 bg-[#701705] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-black transition-all">Entrar</button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-50 flex flex-col items-center gap-4">
            <a href="index.php" class="text-xs font-bold text-gray-400 hover:text-[#701705] flex items-center gap-1 uppercase tracking-tighter">
                <span class="material-symbols-outlined text-sm">home</span> Regresar a la Página Principal
            </a>
            <p class="text-[10px] text-gray-300">¿No tienes cuenta? <a href="registro-usuario.php" class="text-primary font-bold">Regístrate</a></p>
        </div>
    </div>
</body>
</html>