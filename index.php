<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="login-container">
        <form action="config/verificar_login.php" method="POST" class="login-form">
            <h2>Iniciar Sesión</h2>
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Iniciar Sesión</button>
            </div>
        </form>
        <div class="signup-link">
            <p>¿No tienes una cuenta? <a href="#" id="signup-toggle">Crear cuenta</a></p>
            <div id="signup-form" style="display: none;">
                <h2>Registrarse</h2>
                <form action="config/registrar_usuario.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit">Registrarse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Mostrar u ocultar el formulario de registro al hacer clic en el enlace
        document.getElementById('signup-toggle').addEventListener('click', function() {
            var signupForm = document.getElementById('signup-form');
            if (signupForm.style.display === 'none') {
                signupForm.style.display = 'block';
            } else {
                signupForm.style.display = 'none';
            }
        });
    </script>
</body>
</html>
