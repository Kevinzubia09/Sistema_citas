<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    
    <style>
        /* Estilos adicionales para el formulario */
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .signup-link {
            text-align: center;
            margin-top: 15px;
        }
        .signup-link p {
            margin: 0;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
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
                <form action="config/registrar_usuario.php" method="POST">
                    <h2>Registrarse</h2>
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email_reg">Correo electrónico:</label>
                        <input type="email" id="email_reg" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password_reg">Contraseña:</label>
                        <input type="password" id="password_reg" name="password" required>
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
