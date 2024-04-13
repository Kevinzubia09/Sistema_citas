<?php
// Verificar si se recibieron los datos necesarios
if (isset($_POST['id']) && isset($_POST['fecha']) && isset($_POST['motivo'])) {
    // Incluir el archivo de configuración de la base de datos
    require_once('config/config.php');

    // Obtener los datos de la solicitud POST
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'];

    // Preparar la consulta SQL para actualizar la cita
    $query = "UPDATE citas SET fecha = ?, motivo = ? WHERE id = ?";
    
    // Preparar la declaración
    $stmt = $conn->prepare($query);

    // Vincular los parámetros
    $stmt->bind_param('ssi', $fecha, $motivo, $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // La cita se actualizó correctamente
        echo "Cita actualizada correctamente.";
    } else {
        // Ocurrió un error al actualizar la cita
        echo "Error al actualizar la cita: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    // Datos insuficientes
    echo "Datos insuficientes para actualizar la cita.";
}
?>
