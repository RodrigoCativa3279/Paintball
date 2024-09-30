<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paintball_reservas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Recibir los datos JSON del fetch en JavaScript
$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (isset($data['sucursal'], $data['fecha'])) {
    $sucursal = $data['sucursal'];
    $fecha_reserva = $data['fecha'];

    // Consulta para obtener los horarios ocupados
    $sql = "SELECT horario FROM reservas WHERE sucursal = ? AND fecha_reserva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $sucursal, $fecha_reserva);
    $stmt->execute();
    $result = $stmt->get_result();

    $horarios_ocupados = [];
    while ($row = $result->fetch_assoc()) {
        $horarios_ocupados[] = $row['horario'];
    }

    // Enviar los horarios ocupados como respuesta JSON
    echo json_encode(['horarios_ocupados' => $horarios_ocupados]);
}

$conn->close();
?>
