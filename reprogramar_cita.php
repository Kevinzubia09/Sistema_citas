<?php
// Incluir archivo de conexión a la base de datos
require_once('../config/config.php');

// Verificar si se recibió un ID y una nueva fecha válidos
if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['nuevaFecha']) && !empty($_POST['nuevaFecha'])) {
    $id = $_POST['id'];
    $nuevaFecha = $_POST['nuevaFecha'];


     $query = "UPDATE citas SET fecha = '$nuevaFecha' WHERE id = '$id'";
     mysqli_query($conexion, $query);

    // Simulación de reprogramación exitosa
    echo "Cita reprogramada correctamente a $nuevaFecha.";
} else {
    echo "Error: No se recibió un ID o una nueva fecha válidos.";
}
?>
