<?php
// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si el servidor no está en localhost
$username = "root"; // Cambia esto si tu usuario es diferente
$password = ""; // Cambia esto si tienes una contraseña para la base de datos
$dbname = "paintball_reservas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registro'])) {
    $nombre = $_POST['userName'];
    $correo = $_POST['userEmail'];
    $contraseña = $_POST['userPassword'];

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($correo) && !empty($contraseña)) {
        // Insertar en la base de datos utilizando una sentencia preparada
        $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la consulta: " . $conn->error);
        }

        $stmt->bind_param("sss", $nombre, $correo, $contraseña);

        if ($stmt->execute()) {
            // Redirigir a la página de reservas
            header("Location: pagina reservar.html");
            exit(); // Asegurarse de que el script termine después de la redirección
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<script>alert('Todos los campos son obligatorios'); window.location.href = 'index.html';</script>";
    }
}

$conn->close();
?>



