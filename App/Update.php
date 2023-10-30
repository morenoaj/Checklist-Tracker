<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["taskId"]) && isset($_POST["newState"])) {
    require '../Connect.php';

    $taskId = $_POST["taskId"];
    $newState = $_POST["newState"];

    // Valida que $taskId sea un número entero válido (puedes agregar más validaciones según sea necesario)
    if (!is_numeric($taskId)) {
        echo "Error: ID de tarea no válido.";
    } else {
        // Actualiza el estado de la tarea en la base de datos
        $sql = "UPDATE tareas SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$newState, $taskId])) {
            echo "Tarea actualizada con éxito.";
        } else {
            echo "Error al actualizar la tarea: " . implode(', ', $stmt->errorInfo());
        }
    }
} else {
    echo "Error: Parámetros faltantes o solicitud incorrecta.";
}
?>
