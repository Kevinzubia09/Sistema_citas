<?php
session_start();
require_once('config/config.php');

if (!isset($_SESSION['user_id'])) {
    exit;
}

$user_id = $_SESSION['user_id'];

// Si el método de solicitud es POST, se trata de una solicitud para crear o editar una cita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si la acción es para editar una cita existente
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $cita_id = $_POST['id'];
        $nueva_fecha = $_POST['fecha'];

        // Actualizar la fecha de la cita en la base de datos
        $sql = "UPDATE citas SET fecha_hora = '$nueva_fecha' WHERE id = $cita_id AND usuario_id = $user_id";
        $result = $conn->query($sql);

        if (!$result) {
            echo "Error al actualizar la cita.";
        }
        exit;
    }

    // Verificar si la acción es para eliminar una cita existente
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $cita_id = $_POST['id'];

        // Eliminar la cita de la base de datos
        $sql = "DELETE FROM citas WHERE id = $cita_id AND usuario_id = $user_id";
        $result = $conn->query($sql);

        if (!$result) {
            echo "Error al eliminar la cita.";
        }
        exit;
    }

    // Si no es editar ni eliminar, entonces es crear una nueva cita
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'];

    // Insertar nueva cita en la base de datos
    $sql = "INSERT INTO citas (usuario_id, fecha_hora, motivo) VALUES ($user_id, '$fecha', '$motivo')";
    $result = $conn->query($sql);

    if (!$result) {
        echo "Error al crear la cita.";
    }
    exit;
}

// Si el método de solicitud es GET, se trata de una solicitud para obtener las citas
$sql = "SELECT id, fecha_hora, motivo FROM citas WHERE usuario_id = $user_id";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $event = array(
            'id' => $row['id'],
            'title' => $row['motivo'],
            'start' => $row['fecha_hora']
        );
        $events[] = $event;
    }
}

echo json_encode($events);
?>
