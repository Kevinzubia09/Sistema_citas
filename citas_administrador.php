<?php
// Incluir la conexión a la base de datos
require_once('config/config.php');

// Función para cargar todas las citas con el nombre del usuario
function cargarCitas($conn) {
    $query = "SELECT c.id, c.fecha, c.motivo, u.nombre FROM citas c JOIN usuarios u ON c.usuario_id = u.id";
    $result = mysqli_query($conn, $query);
    $citas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $citas[] = $row;
    }
    return $citas;
}

// Función para cargar todos los usuarios
function cargarUsuarios($conn) {
    $query = "SELECT id, nombre, rol FROM usuarios";
    $result = mysqli_query($conn, $query);
    $usuarios = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
    return $usuarios;
}

// Verificar si se ha enviado un formulario de actualización de cita
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    // Obtener la acción enviada
    $action = $_POST['action'];

    // Lógica para cada acción
    switch ($action) {
        case 'actualizarCita':
            // Obtener datos del formulario
            $id = $_POST['id'];
            $fecha = $_POST['fecha'];
            $motivo = $_POST['motivo'];
            // Actualizar la cita en la base de datos
            $query = "UPDATE citas SET fecha = ?, motivo = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $fecha, $motivo, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;
        case 'eliminarCita':
            // Obtener el ID de la cita a eliminar
            $id = $_POST['id'];
            // Eliminar la cita de la base de datos
            $query = "DELETE FROM citas WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;
        case 'actualizarUsuario':
            // Obtener datos del formulario
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $rol = $_POST['rol'];
            $contrasena = $_POST['contrasena'];
            // Actualizar el usuario en la base de datos
            $query = "UPDATE usuarios SET nombre = ?, rol = ?, contrasena = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssi", $nombre, $rol, $contrasena, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            break;
        case 'eliminarUsuario':
            // Obtener el ID del usuario a eliminar
            $id = $_POST['id'];
            // Eliminar el usuario de la base de datos
            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
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
    <title>Administración de Citas y Usuarios</title>
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
    <h2>Administración de Citas y Usuarios</h2>
    <h3>Citas</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener y mostrar todas las citas con el nombre del usuario
            $citas = cargarCitas($conn);
            foreach ($citas as $cita) {
                echo "<tr>";
                echo "<td>{$cita['fecha']}</td>";
                echo "<td>{$cita['motivo']}</td>";
                echo "<td>{$cita['nombre']}</td>";
                echo "<td>";
                echo "<button class='editarCita button' data-id='{$cita['id']}' data-fecha='{$cita['fecha']}' data-motivo='{$cita['motivo']}'>Editar</button>";
                echo "<button class='eliminarCita button' data-id='{$cita['id']}'>Eliminar</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Usuarios</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener y mostrar todos los usuarios
            $usuarios = cargarUsuarios($conn);
            foreach ($usuarios as $usuario) {
                echo "<tr>";
                echo "<td>{$usuario['id']}</td>";
                echo "<td>{$usuario['nombre']}</td>";
                echo "<td>{$usuario['rol']}</td>";
                echo "<td>";
                echo "<button class='editarUsuario button' data-id='{$usuario['id']}' data-nombre='{$usuario['nombre']}' data-rol='{$usuario['rol']}'>Editar</button>";
                echo "<button class='eliminarUsuario button' data-id='{$usuario['id']}'>Eliminar</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón para cerrar sesión -->
    <button id="logoutButton" class="button">Cerrar Sesión</button>

    <!-- Formulario de actualización de cita -->
    <form id="updateCitaForm" style="display: none;">
        <input type="hidden" name="action" value="actualizarCita">
        <input type="hidden" name="id" id="citaId">
        <label for="citaFecha">Fecha:</label>
        <input type="text" name="fecha" id="citaFecha"><br>
        <label for="citaMotivo">Motivo:</label>
        <input type="text" name="motivo" id="citaMotivo"><br>
        <button type="submit" class="button">Actualizar</button>
        <button id="cancelUpdate" class="button">Cancelar</button>
    </form>

    <!-- Script JavaScript para la funcionalidad de la página -->
    <script src='https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js'></script>
    <script>
        $(document).ready(function() {
            // Mostrar formulario de actualización al hacer clic en el botón de editar cita
            $('.editarCita').click(function() {
                var id = $(this).data('id');
                var fecha = $(this).data('fecha');
                var motivo = $(this).data('motivo');
                $('#citaId').val(id);
                $('#citaFecha').val(fecha);
                $('#citaMotivo').val(motivo);
                $('#updateCitaForm').show();
            });

            // Enviar formulario de actualización al servidor
            $('#updateCitaForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.post('actualizar_cita.php', formData, function(response) {
                    console.log(response); // Manejar respuesta del servidor
                    // Ocultar formulario y actualizar la página si es necesario
                    $('#updateCitaForm').hide();
                    location.reload();
                });
            });

            // Cancelar la actualización de la cita
            $('#cancelUpdate').click(function() {
                $('#updateCitaForm').hide();
            });

            // Eliminar cita al hacer clic en el botón correspondiente
            $('.eliminarCita').click(function() {
                var id = $(this).data('id');
                if (confirm("¿Estás seguro de que quieres eliminar esta cita?")) {
                    $.post('eliminar_cita.php', { id: id }, function(response) {
                        console.log(response); // Manejar respuesta del servidor
                        // Actualizar la página si es necesario
                        location.reload();
                    });
                }
            });

            // Mostrar formulario de actualización al hacer clic en el botón de editar usuario
            $('.editarUsuario').click(function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var rol = $(this).data('rol');
                var nuevoNombre = prompt("Introduce el nuevo nombre para el usuario:", nombre);
                var nuevoRol = prompt("Introduce el nuevo rol para el usuario:", rol);
                var nuevaContrasena = prompt("Introduce la nueva contraseña para el usuario:");
                if (nuevoNombre && nuevoRol && nuevaContrasena) {
                    $.post('actualizar_usuario.php', { id: id, nombre: nuevoNombre, rol: nuevoRol, contrasena: nuevaContrasena }, function(response) {
                        console.log(response); // Manejar respuesta del servidor
                        // Actualizar la página si es necesario
                        location.reload();
                    });
                }
            });

            // Eliminar usuario al hacer clic en el botón correspondiente
            $('.eliminarUsuario').click(function() {
                var id = $(this).data('id');
                if (confirm("¿Estás seguro de que quieres eliminar este usuario?")) {
                    $.post('eliminar_usuario.php', { id: id }, function(response) {
                        console.log(response); // Manejar respuesta del servidor
                        // Actualizar la página si es necesario
                        location.reload();
                    });
                }
            });

            // Cerrar sesión al hacer clic en el botón correspondiente
            $('#logoutButton').click(function() {
                window.location.href = 'logout.php';
            });
        });
    </script>
</body>
</html>



