<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Carpeta donde se guardarán
    $upload_dir = 'uploads/proyectos/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Permisos más seguros (0755 en lugar de 0777)
    }

    $user_id = $_SESSION['user_id'];

    // Función avanzada para subir archivos con extrema seguridad
    function subirArchivoSeguro($input_name, $directorio, $user_id) {
        if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
            
            // 1. Validar Tamaño desde el servidor (Máximo 20MB por archivo)
            $max_size = 20 * 1024 * 1024; // 20 MB en bytes
            if ($_FILES[$input_name]['size'] > $max_size) {
                return false;
            }

            // 2. Validar el TIPO REAL de archivo (MIME Type)
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_real = finfo_file($finfo, $_FILES[$input_name]['tmp_name']);
            finfo_close($finfo);

            // Tipos permitidos exactos (Solo PDF, Word moderno y Excel moderno)
            $tipos_permitidos = [
                'application/pdf' => 'pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx'
            ];

            // Si el tipo real no está en nuestra lista segura, lo rechazamos
            if (!array_key_exists($mime_real, $tipos_permitidos)) {
                return false;
            }

            // 3. Crear un nombre encriptado y limpio
            $extension_segura = $tipos_permitidos[$mime_real];
            // Generamos un string aleatorio único para que nadie pueda adivinar el nombre del archivo
            $token_aleatorio = bin2hex(random_bytes(8)); 
            
            $nombre_seguro = $user_id . '_' . $input_name . '_' . $token_aleatorio . '.' . $extension_segura;
            $ruta_destino = $directorio . $nombre_seguro;

            // 4. Mover el archivo
            if (move_uploaded_file($_FILES[$input_name]['tmp_name'], $ruta_destino)) {
                return $ruta_destino;
            }
        }
        return false;
    }

    // Intentamos subir los 3 formatos
    $ruta_f1 = subirArchivoSeguro('formato1', $upload_dir, $user_id);
    $ruta_f2 = subirArchivoSeguro('formato2', $upload_dir, $user_id);
    $ruta_f3 = subirArchivoSeguro('formato3', $upload_dir, $user_id);

    // Si los 3 subieron correctamente
    if ($ruta_f1 && $ruta_f2 && $ruta_f3) {
        $_SESSION['proyecto_paso4'] = [
            'ruta_formato1' => $ruta_f1,
            'ruta_formato2' => $ruta_f2,
            'ruta_formato3' => $ruta_f3
        ];
        header("Location: registro-paso5.php");
        exit;
    } else {
        // Si falla la validación de alguno, lo regresamos con error
        die("<div style='text-align:center; padding:50px; font-family:sans-serif;'>
                <h2 style='color:#9c2007;'>Error de Seguridad o Tamaño</h2>
                <p>Ocurrió un problema con tus archivos. Verifica que sean estrictamente PDF, DOCX o XLSX y que no superen los 20MB cada uno.</p>
                <a href='javascript:history.back()' style='color:blue;'>Volver a intentar</a>
             </div>");
    }

} else {
    header("Location: registro-paso4.php");
    exit;
}