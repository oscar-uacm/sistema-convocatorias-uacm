<?php
session_start();
require_once 'conexion.php';

// SEGURIDAD: Solo el rol 'admin' puede entrar aquí
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php"); 
    exit;
}

// LÓGICA DE ACTUALIZACIÓN DE ROLES (Tres niveles)
if (isset($_GET['rol']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $nuevo_rol = $_GET['rol'];
    
    // Validamos que el rol sea uno de los permitidos
    $roles_permitidos = ['investigador', 'evaluador', 'comite'];
    
    if (in_array($nuevo_rol, $roles_permitidos)) {
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

$resultado = mysqli_query($conexion, "SELECT id, nombre, correo, rol FROM usuarios WHERE rol != 'admin' ORDER BY nombre ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>UACM - Gestión de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>body { font-family: 'Lexend', sans-serif; }</style>
</head>
<body class="bg-[#fcfaf9] p-8">
    <main class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10">
            <div>
                <a href="admin-dashboard.php" class="text-primary text-xs font-black uppercase flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Volver al Panel
                </a>
                <h1 class="text-4xl font-black text-gray-900 tracking-tighter">Gestión de Usuarios</h1>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-primary/5 border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Usuario</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Rol Actual</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] text-right">Asignar Nuevo Rol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while ($user = mysqli_fetch_assoc($resultado)): ?>
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <p class="font-bold text-gray-900"><?php echo htmlspecialchars($user['nombre']); ?></p>
                            <p class="text-xs text-gray-400"><?php echo htmlspecialchars($user['correo']); ?></p>
                        </td>
                        <td class="px-8 py-6">
                            <?php 
                                $color = $user['rol'] == 'comite' ? 'bg-purple-100 text-purple-700' : ($user['rol'] == 'evaluador' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600');
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase <?php echo $color; ?>">
                                <?php echo $user['rol']; ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right space-x-2">
                            <a href="admin-usuarios.php?rol=investigador&id=<?php echo $user['id']; ?>" 
                               class="inline-flex items-center gap-1 text-[9px] font-black px-3 py-2 rounded-xl border border-gray-200 hover:bg-white transition-all uppercase tracking-tighter">
                                <span class="material-symbols-outlined text-xs">person</span> Investigador
                            </a>
                            <a href="admin-usuarios.php?rol=evaluador&id=<?php echo $user['id']; ?>" 
                               class="inline-flex items-center gap-1 text-[9px] font-black px-3 py-2 rounded-xl border border-blue-100 text-blue-600 hover:bg-blue-50 transition-all uppercase tracking-tighter">
                                <span class="material-symbols-outlined text-xs">edit_document</span> Evaluador
                            </a>
                            <a href="admin-usuarios.php?rol=comite&id=<?php echo $user['id']; ?>" 
                               class="inline-flex items-center gap-1 text-[9px] font-black px-3 py-2 rounded-xl border border-purple-100 text-purple-600 hover:bg-purple-50 transition-all uppercase tracking-tighter">
                                <span class="material-symbols-outlined text-xs">gavel</span> Comité
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>