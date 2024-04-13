<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el correo electrónico ya está registrado
    $sql = "SELECT id FROM usuarios WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "El correo electrónico ya está registrado.";
    } else {
        // Insertar nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES ('$nombre', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Usuario registrado exitosamente.";
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    }
}
?>
