<?php
// Incluir archivo de conexión a la base de datos
require_once('config/config.php');

// Verificar si se recibió un ID válido
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

     $query = "DELETE FROM citas WHERE id = '$id'";
     mysqli_query($conn, $query);

    // Simulación de eliminación exitosa
    echo "Cita eliminada correctamente.";
} else {
    echo "Error: No se recibió un ID válido.";
}
?>
