<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paintball_reservas";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $correo = $_POST['userEmail'];
    $contraseña = $_POST['userPassword'];

    // Validar que los campos no estén vacíos
    if (!empty($correo) && !empty($contraseña)) {
        $sql = "SELECT * FROM usuarios WHERE correo = ? AND password = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error en la consulta: " . $conn->error);
        }

        $stmt->bind_param("ss", $correo, $contraseña);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Redirigir a la página de reservas
            header("Location: pagina reservar.html");
            exit();
        } else {
            // Mostrar mensaje de error
            echo "<script>
                document.getElementById('login-error').style.display = 'block';
                document.getElementById('login-error').innerText = 'Correo o contraseña incorrectos';
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
            document.getElementById('login-error').style.display = 'block';
            document.getElementById('login-error').innerText = 'Todos los campos son obligatorios';
        </script>";
    }
}

$conn->close();
?>



