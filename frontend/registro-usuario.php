<?php
// 1. Incluimos la conexión a la base de datos
include 'conexion.php';

$mensaje_error = "";

// 2. Detectamos si se envió el formulario
if (isset($_POST['registrar'])) {
    // Limpiamos los datos para evitar caracteres extraños o inyecciones SQL
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Validar que las contraseñas sean iguales
    if ($pass !== $confirm_pass) {
        $mensaje_error = "Las contraseñas no coinciden. Inténtalo de nuevo.";
    } else {
        // Verificar si el correo ya existe en nuestra tabla
        $check_query = "SELECT id FROM usuarios WHERE correo = '$correo'";
        $resultado_check = mysqli_query($conexion, $check_query);

        if (mysqli_num_rows($resultado_check) > 0) {
            $mensaje_error = "Este correo ya está registrado en el sistema.";
        } else {
            // 3. ENCRIPTACIÓN: Usamos el estándar de PHP para seguridad
            $pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);

            // 4. INSERTAR: Por defecto registramos como 'investigador'
            $insert_query = "INSERT INTO usuarios (nombre, correo, password, rol) 
                            VALUES ('$nombre', '$correo', '$pass_encriptada', 'investigador')";

            if (mysqli_query($conexion, $insert_query)) {
                // Si tiene éxito, lo enviamos al login con un parámetro de éxito
                header("Location: login.php?registro=exito");
                exit;
            } else {
                $mensaje_error = "Error al registrar en la base de datos: " . mysqli_error($conexion);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>UACM - Registro de Usuario</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#701705",
                        "uacm-red": "#9C2007",
                        "background-light": "#f8f6f5",
                        "background-dark": "#221310",
                    },
                    fontFamily: { "display": ["Lexend", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-primary min-h-screen flex items-center justify-center p-6 font-display">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-2xl">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-primary uppercase tracking-tight">Crear Cuenta</h2>
            <p class="text-gray-400 text-xs font-bold mt-1">Registro de Investigadores UACM</p>
        </div>

        <?php if ($mensaje_error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-6 rounded flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500 text-sm">warning</span>
                <p class="text-red-700 text-[11px] font-bold"><?php echo $mensaje_error; ?></p>
            </div>
        <?php endif; ?>

        <form action="registro-usuario.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre Completo</label>
                <input type="text" name="nombre" required 
                    class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-primary focus:ring-primary transition-all text-sm" 
                    placeholder="Ej. Dr. Roberto Pérez" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Correo Institucional</label>
                <input type="email" name="correo" required 
                    class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-primary focus:ring-primary transition-all text-sm" 
                    placeholder="correo@uacm.edu.mx" value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Contraseña</label>
                    <input type="password" name="password" required 
                        class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-primary focus:ring-primary transition-all text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Confirmar</label>
                    <input type="password" name="confirm_password" required 
                        class="w-full rounded-xl border-gray-100 bg-gray-50 focus:border-primary focus:ring-primary transition-all text-sm">
                </div>
            </div>

            <button type="submit" name="registrar" 
                class="w-full bg-primary text-white font-bold py-4 rounded-xl shadow-lg hover:bg-uacm-red transition-all mt-4 flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                Finalizar Registro
                <span class="material-symbols-outlined text-sm">how_to_reg</span>
            </button>

            <div class="pt-6 text-center border-t border-gray-100 mt-6">
                <p class="text-xs text-gray-500 font-medium">
                    ¿Ya tienes una cuenta registrada? 
                    <a class="text-primary font-bold hover:underline" href="login.php">Inicia Sesión</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>