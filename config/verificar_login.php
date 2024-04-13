<?php
session_start();
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar usuario en la base de datos
    $sql = "SELECT id, nombre, rol FROM usuarios WHERE email='$email' AND contrasena='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Inicio de sesión exitoso
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_nombre'] = $row['nombre'];
        $_SESSION['user_rol'] = $row['rol'];
        
        // Redirigir al usuario según su rol
        if ($_SESSION['user_rol'] == 'administrador') {
            header("Location: ../citas_administrador.php");
        } else {
            header("Location: ../citas_usuario.php");
        }
    } else {
        // Credenciales incorrectas
        echo "Email o contraseña incorrectos.";
    }
}
?>

