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
        // Mostrar alerta emergente si el correo electrónico ya está registrado
        echo "<script>alert('El correo electrónico ya está registrado.'); window.location.href = '../index.php';</script>";
    } else {
        // Insertar nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, email, contrasena) VALUES ('$nombre', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            // Mostrar alerta emergente si el usuario se registra correctamente
            echo "<script>alert('Usuario registrado exitosamente.'); window.location.href = '../index.php';</script>";
        } else {
            // Mostrar alerta emergente si hay un error al registrar el usuario
            echo "<script>alert('Error al registrar el usuario: " . $conn->error . "'); window.location.href = '../index.php';</script>";
        }
    }
}
?>

