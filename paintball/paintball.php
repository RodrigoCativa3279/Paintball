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

// Verificar si los datos fueron recibidos correctamente
if (isset($data['id_usuario'], $data['fecha_reserva'], $data['sucursal'], $data['horario'])) {
    $id_usuario = $data['id_usuario'];
    $fecha_reserva = $data['fecha_reserva'];
    $sucursal = $data['sucursal'];
    $horario = $data['horario'];

    // Verificar si el horario ya está reservado
    $check_sql = "SELECT disponibilidad FROM reservas WHERE sucursal = ? AND fecha_reserva = ? AND horario = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('sss', $sucursal, $fecha_reserva, $horario);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // El horario ya está reservado
        echo json_encode(['success' => false, 'message' => 'Horario no disponible.']);
    } else {
        // Inserta la reserva en la base de datos
        $sql = "INSERT INTO reservas (id_usuario, fecha_reserva, sucursal, horario, disponibilidad) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss', $id_usuario, $fecha_reserva, $sucursal, $horario);
        
        $response = [];
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }

        // Enviar la respuesta de vuelta al JavaScript en formato JSON
        echo json_encode($response);
        $stmt->close();
    }
}

$conn->close();
?>
