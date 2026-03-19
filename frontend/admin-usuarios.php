<?php
session_start();
require_once 'conexion.php';

// 1. SEGURIDAD MÁXIMA: Solo el rol 'admin' puede entrar aquí
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php"); 
    exit;
}

// 2. LÓGICA DE ACTUALIZACIÓN DE ROL
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Validamos que la acción sea permitida
    if ($_GET['accion'] === 'ascender' || $_GET['accion'] === 'revocar') {
        $nuevo_rol = ($_GET['accion'] == 'ascender') ? 'evaluador' : 'investigador';
        
        $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ? AND rol != 'admin'");
        $stmt->bind_param("si", $nuevo_rol, $id);
        
        if ($stmt->execute()) {
            header("Location: admin-usuarios.php?status=ok");
        } else {
            header("Location: admin-usuarios.php?status=error");
        }
        exit;
    }
}

// 3. OBTENER LISTA DE USUARIOS (Excluyendo al admin actual para seguridad)
$resultado = mysqli_query($conexion, "SELECT id, nombre, correo, rol FROM usuarios WHERE rol != 'admin' ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UACM - Gestión de Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-[#fcfaf9] flex min-h-screen">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 p-8 md:p-12">
        <header class="mb-10">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight uppercase italic">Gestión de Personal</h1>
            <p class="text-gray-500 text-sm">Asigna o remueve permisos de evaluación a los investigadores registrados.</p>
        </header>

        <?php if(isset($_GET['status'])): ?>
            <div class="mb-6 p-4 rounded-2xl text-xs font-bold uppercase tracking-widest flex items-center gap-2 <?php echo $_GET['status'] == 'ok' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100'; ?>">
                <span class="material-symbols-outlined text-sm"><?php echo $_GET['status'] == 'ok' ? 'check_circle' : 'error'; ?></span>
                <?php echo $_GET['status'] == 'ok' ? 'Cambio realizado con éxito' : 'Hubo un error al procesar la solicitud'; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Nombre y Correo</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-center">Rol Actual</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Acciones de Rango</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while ($user = mysqli_fetch_assoc($resultado)): ?>
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800"><?php echo htmlspecialchars($user['nombre']); ?></span>
                                <span class="text-[10px] text-gray-400 font-medium"><?php echo htmlspecialchars($user['correo']); ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <?php if ($user['rol'] === 'evaluador'): ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-100">
                                    <span class="material-symbols-outlined text-[12px]">verified_user</span>
                                    Evaluador
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-gray-100 text-gray-400">
                                    Investigador
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <?php if ($user['rol'] === 'investigador'): ?>
                                <a href="admin-usuarios.php?accion=ascender&id=<?php echo $user['id']; ?>" 
                                   onclick="return confirm('¿Otorgar permisos de EVALUACIÓN a este usuario?')"
                                   class="inline-flex items-center gap-2 bg-[#701705] text-white text-[10px] font-black px-4 py-2 rounded-xl hover:bg-black transition-all uppercase tracking-widest shadow-md">
                                    <span class="material-symbols-outlined text-sm">add_moderator</span>
                                    Ascender
                                </a>
                            <?php else: ?>
                                <a href="admin-usuarios.php?accion=revocar&id=<?php echo $user['id']; ?>" 
                                   onclick="return confirm('¿Quitar permisos de evaluador?')"
                                   class="inline-flex items-center gap-2 text-gray-400 hover:text-red-600 text-[10px] font-bold px-4 py-2 rounded-xl border border-gray-200 hover:border-red-100 transition-all uppercase tracking-widest">
                                    <span class="material-symbols-outlined text-sm">person_remove</span>
                                    Revocar
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($resultado) == 0): ?>
                    <tr>
                        <td colspan="3" class="px-8 py-20 text-center text-gray-400 text-xs font-bold uppercase tracking-widest">
                            No hay otros usuarios registrados para gestionar.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>