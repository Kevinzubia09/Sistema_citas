<?php
// Incluir la conexión a la base de datos
require_once('config/config.php');

// Consulta para obtener todos los usuarios
$query = "SELECT id, nombre, email FROM usuarios";
$result = mysqli_query($conn, $query);

// Verificar si se obtuvieron resultados
if ($result) {
    // Array para almacenar los usuarios
    $usuarios = [];

    // Recorrer los resultados y agregar cada usuario al array
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }

    // Devolver los usuarios como JSON
    echo json_encode($usuarios);
} else {
    // Si no se obtuvieron resultados, devolver un mensaje de error
    echo json_encode(['error' => 'No se pudieron obtener los usuarios']);
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
