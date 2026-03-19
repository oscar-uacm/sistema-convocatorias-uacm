<?php
// 1. Conexión a la base de datos
require_once 'conexion.php';

$mensaje_error = "";

// 2. Procesamiento del Formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar'])) {
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $correo = mysqli_real_escape_string($conexion, $_POST['correo']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Validaciones básicas
    if ($pass !== $confirm_pass) {
        $mensaje_error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el correo ya existe
        $check_query = "SELECT id FROM usuarios WHERE correo = '$correo'";
        $resultado_check = mysqli_query($conexion, $check_query);

        if (mysqli_num_rows($resultado_check) > 0) {
            $mensaje_error = "Este correo ya está registrado en el sistema.";
        } else {
            // Encriptación de seguridad
            $pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);
            
            // Inserción (rol 'investigador' por defecto)
            $insert_query = "INSERT INTO usuarios (nombre, correo, password, rol) 
                            VALUES ('$nombre', '$correo', '$pass_encriptada', 'investigador')";

            if (mysqli_query($conexion, $insert_query)) {
                // REDIRECCIÓN: Envía al login con el aviso de éxito
                header("Location: login.php?status=registro_exitoso");
                exit;
            } else {
                $mensaje_error = "Error en el registro: " . mysqli_error($conexion);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UACM - Crear Cuenta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        body { font-family: 'Lexend', sans-serif; }
        .bg-uacm { background-color: #701705; }
        .text-uacm { color: #701705; }
        .ring-uacm:focus { --tw-ring-color: rgba(112, 23, 5, 0.2); }
    </style>
</head>
<body class="bg-[#fcfaf9] min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200 border border-gray-100 p-10 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 rounded-full -mr-16 -mt-16"></div>

            <div class="text-center mb-8 relative z-10">
                <div class="size-16 bg-uacm text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-900/20 transform -rotate-3">
                    <span class="material-symbols-outlined text-3xl">person_add</span>
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tighter">Crear Cuenta</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-2">Investigación Científica UACM</p>
            </div>

            <?php if ($mensaje_error): ?>
                <div class="bg-red-50 border border-red-100 text-red-600 text-[10px] font-black p-4 rounded-2xl mb-6 flex items-center gap-2 uppercase">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <?php echo $mensaje_error; ?>
                </div>
            <?php endif; ?>

            <form action="registro-usuario.php" method="POST" class="space-y-4">
                <div>
                    <input type="text" name="nombre" required 
                        placeholder="Nombre completo" 
                        class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 ring-uacm border-transparent transition-all"
                        value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                </div>

                <div>
                    <input type="email" name="correo" required 
                        placeholder="Correo institucional" 
                        class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 ring-uacm border-transparent transition-all"
                        value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input type="password" name="password" required 
                        placeholder="Contraseña" 
                        class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 ring-uacm border-transparent transition-all">
                    
                    <input type="password" name="confirm_password" required 
                        placeholder="Confirmar" 
                        class="w-full px-5 py-4 bg-gray-50 rounded-2xl text-sm outline-none focus:ring-2 ring-uacm border-transparent transition-all">
                </div>

                <button type="submit" name="registrar" class="w-full py-4 bg-uacm text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-black transition-all mt-2 transform active:scale-95">
                    Finalizar Registro
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-3 tracking-widest">¿Ya tienes una cuenta?</p>
                <a href="login.php" class="text-uacm font-black text-xs uppercase tracking-widest hover:underline transition-all">
                    Inicia Sesión aquí
                </a>
            </div>
        </div>
        
        <p class="text-center mt-8 text-[10px] text-gray-400 font-medium uppercase tracking-tighter italic">
            "Nada humano me es ajeno" — UACM 2026
        </p>
    </div>

</body>
</html>