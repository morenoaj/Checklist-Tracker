<?php
require '../Connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para eliminar la tarea por su ID
    $sql = "DELETE FROM tareas WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$id])) {
        // Tarea eliminada con éxito, redirige de vuelta a la vista de tareas
        header("Location: ../Dashboard.php");
    } else {
        // Error al eliminar la tarea
        echo "Error al eliminar la tarea.";
    }
} else {
    echo "ID de tarea no proporcionado.";
}

// Cierra la conexión a la base de datos
$conn = null;
?>
