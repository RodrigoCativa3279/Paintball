<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo 'Método POST recibido';
} else {
    echo 'Método no permitido';
}
?>