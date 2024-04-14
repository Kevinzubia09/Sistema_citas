<?php
require_once('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar las credenciales del usuario
    $sql = "SELECT * FROM usuarios WHERE email='$email' AND contrasena='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Las credenciales son válidas
        session_start();
        $_SESSION['loggedin'] = true;
        $_SESSION['email'] = $email;
        header('Location: citas_administrador.php');
    } else {
        // Las credenciales son incorrectas
        echo "<script>alert('Credenciales incorrectas. Por favor, inténtelo de nuevo.'); window.location.href = '../index.php';</script>";
    }
}
?>


