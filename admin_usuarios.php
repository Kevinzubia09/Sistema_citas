<?php
// Incluir la conexión a la base de datos
require_once('config/config.php');

// Función para cargar todos los usuarios
function cargarUsuarios($conn) {
    $query = "SELECT id, nombre, email FROM usuarios";
    $result = mysqli_query($conn, $query);
    $usuarios = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
    return $usuarios;
}

// Función para actualizar un usuario en la base de datos
function actualizarUsuario($conn, $id, $nombre, $email) {
    $query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nombre, $email, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Función para eliminar un usuario de la base de datos
function eliminarUsuario($conn, $id) {
    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Verificar si se ha enviado un formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Obtener la acción enviada
    $action = $_POST['action'];

    // Lógica para cada acción
    switch ($action) {
        case 'actualizarUsuario':
            // Obtener datos del formulario
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            // Actualizar el usuario en la base de datos
            actualizarUsuario($conn, $id, $nombre, $email);
            break;
        case 'eliminarUsuario':
            // Obtener el ID del usuario a eliminar
            $id = $_POST['id'];
            // Eliminar el usuario de la base de datos
            eliminarUsuario($conn, $id);
            break;
    }
    exit; // Terminar el script después de procesar la acción
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Usuarios</title>
    <!-- Enlaces a los estilos necesarios -->
    <style>
        /* Estilos CSS */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button {
            padding: 8px 15px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-right: 10px;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Administración de Usuarios</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener y mostrar todos los usuarios
            $usuarios = cargarUsuarios($conn);
            foreach ($usuarios as $usuario) {
                echo "<tr>";
                echo "<td>{$usuario['nombre']}</td>";
                echo "<td>{$usuario['email']}</td>";
                echo "<td>";
                echo "<button class='editarUsuario button' data-id='{$usuario['id']}' data-nombre='{$usuario['nombre']}' data-email='{$usuario['email']}'>Editar</button>";
                echo "<button class='eliminarUsuario button' data-id='{$usuario['id']}'>Eliminar</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Formulario oculto para actualizar usuario -->
    <form id="updateUserForm" style="display: none;">
        <input type="hidden" id="userId" name="id">
        <label for="userName">Nombre:</label>
        <input type="text" id="userName" name="nombre"><br>
        <label for="userEmail">Email:</label>
        <input type="email" id="userEmail" name="email"><br>
        <button type="submit" class="button">Actualizar</button>
        <button type="button" id="cancelUpdate" class="button">Cancelar</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        // Script JavaScript para la funcionalidad de la página
        $(document).ready(function() {
            // Mostrar formulario de actualización al hacer clic en el botón de editar
            $('.editarUsuario').click(function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var email = $(this).data('email');
                $('#userId').val(id);
                $('#userName').val(nombre);
                $('#userEmail').val(email);
                $('#updateUserForm').show();
            });

            // Ocultar formulario de actualización al hacer clic en el botón de cancelar
            $('#cancelUpdate').click(function() {
                $('#updateUserForm').hide();
            });

            // Manejar envío del formulario de actualización
            $('#updateUserForm').submit(function(event) {
                event.preventDefault();
                var id = $('#userId').val();
                var nombre = $('#userName').val();
                var email = $('#userEmail').val();
                // Actualizar el usuario en la base de datos
                $.ajax({
                    url: 'admin_usuarios.php',
                    type: 'POST',
                    data: {
                        action: 'actualizarUsuario',
                        id: id,
                        nombre: nombre,
                        email: email
                    },
                    success: function(response) {
                        // Recargar la página después de actualizar
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Manejar clic en el botón de eliminar usuario
            $('.eliminarUsuario').click(function() {
                var id = $(this).data('id');
                // Eliminar el usuario de la base de datos
                $.ajax({
                    url: 'admin_usuarios.php',
                    type: 'POST',
                    data: {
                        action: 'eliminarUsuario',
                        id: id
                    },
                    success: function(response) {
                        // Recargar la página después de eliminar
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>

