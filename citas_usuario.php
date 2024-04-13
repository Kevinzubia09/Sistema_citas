<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js'></script>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .title-container {
            display: flex;
            align-items: center;
        }
        .modal-container {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            width: 300px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .modal-content h2 {
            margin-top: 0;
        }
        .modal-content label {
            display: block;
            margin-bottom: 5px;
        }
        .modal-content input[type='text'],
        .modal-content input[type='time'] {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        .modal-content button {
            padding: 8px 15px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            margin-right: 10px;
        }
        .modal-content button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h2>Mis Citas</h2>
        </div>
        <button id="logoutButton" class="button" >Cerrar Sesión</button>
    </div>
    <div id='calendar'></div>
    <div class="modal-container" id='modalForm'>
        <div class="modal-content">
            <h2>Nueva Cita</h2>
            <label for='fecha'>Fecha:</label>
            <input type='text' id='fecha' disabled><br>
            <label for='hora'>Hora:</label>
            <input type='time' id='hora'><br>
            <label for='motivo'>Motivo:</label>
            <input type='text' id='motivo'><br>
            <button id='saveButton'>Guardar</button>
            <button id='cancelButton'>Cancelar</button>
        </div>
    </div>
    <div class="modal-container" id='editForm'>
        <div class="modal-content">
            <h2>Editar Cita</h2>
            <label for='editFecha'>Fecha:</label>
            <input type='text' id='editFecha' disabled><br>
            <label for='editHora'>Hora:</label>
            <input type='time' id='editHora'><br>
            <label for='editMotivo'>Motivo:</label>
            <input type='text' id='editMotivo'><br>
            <button id='updateButton'>Actualizar</button>
            <button id='deleteButton'>Eliminar</button>
            <button id='cancelEditButton'>Cancelar</button>
            <input type='hidden' id='eventId'>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'get_citas.php',
                editable: true,
                selectable: true,
                dateClick: function(info) {
                    var currentDate = moment().format('YYYY-MM-DD');
                    if (info.dateStr >= currentDate) {
                        $('#fecha').val(info.dateStr);
                        $('.modal-container#modalForm').show();
                    } else {
                        alert('No puedes programar citas en días anteriores a hoy.');
                    }
                },
                eventClick: function(info) {
                    // Mostrar formulario de edición
                    $('#editFecha').val(moment(info.event.start).format('YYYY-MM-DD'));
                    $('#editHora').val(moment(info.event.start).format('HH:mm'));
                    $('#editMotivo').val(info.event.title);
                    $('#eventId').val(info.event.id); // Poner el eventId en el campo oculto
                    $('.modal-container#editForm').show();
                },
                eventDrop: function(info) {
                    if (confirm('¿Deseas reprogramar esta cita?')) {
                        var nuevaFecha = moment(info.event.start).format('YYYY-MM-DD HH:mm:ss');
                        $.ajax({
                            url: 'reprogramar_cita.php',
                            type: 'POST',
                            data: {
                                id: info.event.id,
                                nuevaFecha: nuevaFecha
                            },
                            success: function(response) {
                                console.log(response);
                                calendar.refetchEvents();
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                }
            });
            calendar.render();

            $('#saveButton').click(function() {
                var fecha = $('#fecha').val();
                var hora = $('#hora').val();
                var motivo = $('#motivo').val();
                if (motivo != '') {
                    $.ajax({
                        url: 'get_citas.php',
                        type: 'POST',
                        data: {
                            fecha: fecha + ' ' + hora,
                            motivo: motivo
                        },
                        success: function(response) {
                            console.log(response);
                            calendar.refetchEvents();
                            $('.modal-container#modalForm').hide();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    alert('Por favor, ingresa un motivo para la cita.');
                }
            });

            $('#cancelButton').click(function() {
                $('.modal-container#modalForm').hide();
                $('#fecha').val('');
                $('#hora').val('');
                $('#motivo').val('');
            });

            $('#updateButton').click(function() {
                // Evento para actualizar la cita editada
                var id = $('#eventId').val(); // Obtener el eventId del campo oculto
                var fecha = $('#editFecha').val();
                var hora = $('#editHora').val();
                var motivo = $('#editMotivo').val();
                if (motivo != '') {
                    $.ajax({
                        url: 'actualizar_cita.php',
                        type: 'POST',
                        data: {
                            id: id,
                            fecha: fecha + ' ' + hora,
                            motivo: motivo
                        },
                        success: function(response) {
                            console.log(response);
                            calendar.refetchEvents();
                            $('.modal-container#editForm').hide();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    alert('Por favor, ingresa un motivo para la cita.');
                }
            });

            $('#deleteButton').click(function() {
                // Evento para eliminar la cita
                var eventId = $('#eventId').val(); // Obtener el eventId del campo oculto
                var confirmar = confirm('¿Deseas eliminar esta cita?');
                if (confirmar) {
                    $.ajax({
                        url: 'eliminar_cita.php',
                        type: 'POST',
                        data: {
                            id: eventId
                        },
                        success: function(response) {
                            console.log(response);
                            calendar.refetchEvents();
                            $('.modal-container#editForm').hide();
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });

            // Evento para cancelar la edición
            $('#cancelEditButton').click(function() {
                $('.modal-container#editForm').hide();
            });

            // Evento para cerrar sesión
            $('#logoutButton').click(function() {
                window.location.href = 'logout.php';
            });
        });
    </script>
</body>
</html>
