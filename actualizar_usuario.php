<?php
// Incluir la conexión a la base de datos
require_once('config/config.php');

// Verificar si se ha enviado un formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Obtener la acción enviada
    $action = $_POST['action'];

    // Lógica para cada acción
    switch ($action) {
        case 'actualizarUsuario':
            // Obtener datos del formulario
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $rol = $_POST['rol'];

            // Verificar que el valor de rol sea válido
            $roles_permitidos = array('administrador', 'usuario');
            if (!in_array($rol, $roles_permitidos)) {
                // Si el rol enviado no está permitido, manejar el error
                echo "Error: El rol especificado no es válido.";
                exit;
            }

            // Actualizar el usuario en la base de datos
            $query = "UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $nombre, $rol, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            // Redireccionar a la página de administración de usuarios
            header("Location: admin_usuarios.php");
            exit;
            break;
    }
}
?>

