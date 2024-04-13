<?php
// Iniciar sesión
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión u otra página según tu aplicación
header("Location: index.php");
exit();
?>
