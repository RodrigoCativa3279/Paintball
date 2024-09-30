document.addEventListener('DOMContentLoaded', function() {
    const seleccionarCancha = document.getElementById('seleccion-cancha');
    const seleccionarFecha = document.getElementById('fecha-reserva');
    const horariosRadios = document.querySelectorAll('input[name="horario"]');

    // Función para cargar horarios ocupados
    function cargarHorariosOcupados() {
        const sucursal = seleccionarCancha.value;
        const fecha = seleccionarFecha.value;

        if (!fecha) {
            return; // No hace falta mostrar la alerta en este momento
        }

        fetch('obtener_horarios.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ sucursal, fecha })
        })
        .then(response => response.json())
        .then(data => {
            const horariosOcupados = data.horarios_ocupados;

            // Restablecer los horarios para eliminar texto y habilitar opciones
            horariosRadios.forEach(radio => {
                radio.disabled = false;
                radio.nextElementSibling.textContent = radio.nextElementSibling.textContent.replace(' (No disponible)', '');
            });

            // Deshabilitar horarios ocupados y añadir texto "No disponible"
            horariosRadios.forEach(radio => {
                if (horariosOcupados.includes(radio.value)) {
                    radio.disabled = true;
                    if (!radio.nextElementSibling.textContent.includes('No disponible')) {
                        radio.nextElementSibling.textContent += ' (No disponible)';
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al obtener horarios ocupados:', error);
        });
    }

    // Evento cuando se cambia la selección de la cancha o la fecha
    seleccionarCancha.addEventListener('change', cargarHorariosOcupados);
    seleccionarFecha.addEventListener('change', cargarHorariosOcupados);

    document.getElementById('reservar-btn').addEventListener('click', function(event) {
        event.preventDefault();

        const horarioSeleccionado = document.querySelector('input[name="horario"]:checked');
        const fecha = seleccionarFecha.value;

        if (!horarioSeleccionado || !fecha) {
            alert('Por favor, selecciona una fecha y un horario.');
            return;
        }

        const reservaData = {
            id_usuario: 1, // Cambia esto para obtener el ID del usuario real
            fecha_reserva: fecha,
            sucursal: seleccionarCancha.value,
            horario: horarioSeleccionado.value
        };

        fetch('paintball.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(reservaData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reserva realizada exitosamente.');
                cargarHorariosOcupados(); // Actualizar horarios después de realizar una reserva
                window.location.href = 'pagina principal.html';

            } else {
                alert('Error: ' + (data.message || 'Error desconocido.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al procesar la solicitud.');
        });
    });

    // Cargar horarios ocupados al cargar la página
    cargarHorariosOcupados();
});

