<?php
// Incluye el archivo de conexión a la base de datos
require '../Connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupera los datos del formulario de edición
        $nombre = $_POST['tarea-nombre'];
        $descripcion = $_POST['tarea-descripcion'];
        $fecha_compromiso = $_POST['fecha-de-compromiso'];
        $prioridad = $_POST['tarea-prioridad'];
        $responsable = $_POST['tarea-responsable'];

        // Actualiza los datos de the tarea en the base de datos
        $sql = "UPDATE tareas SET nombre = ?, descripcion = ?, fecha_compromiso = ?, prioridad = ?, responsable = ?, edited = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$nombre, $descripcion, $fecha_compromiso, $prioridad, $responsable, $id])) {
            // Tarea actualizada con éxito, redirige de vuelta a the vista de tareas pendientes
            header("Location: ../Dashboard.php");
        } else {
            echo "Error al actualizar la tarea.";
        }
    } else {
        // Consulta para seleccionar la tarea por su ID
        $sql = "SELECT * FROM tareas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $tarea = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$tarea) {
            echo "Tarea no encontrada.";
            exit();
        }

        // Formulario para editar la tarea
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Editar Tarea</title>
            <link rel="stylesheet" type="text/css" href="/css/edit.css"> <!-- Enlaza tu archivo CSS aquí -->
        </head>
        <body>
            <div class="card">
                <h2>Editar Tarea</h2>
                <form method="POST">
                    <input type="text" name="tarea-nombre" value="<?php echo $tarea['nombre']; ?>" placeholder="Nombre de la tarea">
                    <textarea name="tarea-descripcion" placeholder="Descripción de la tarea"><?php echo $tarea['descripcion']; ?></textarea>
                    <input type="date" name="fecha-de-compromiso" value="<?php echo $tarea['fecha_compromiso']; ?>">
                    <select name="tarea-prioridad">
                        <option value="Baja" <?php echo ($tarea['prioridad'] === 'Baja') ? 'selected' : ''; ?>>Baja</option>
                        <option value="Media" <?php echo ($tarea['prioridad'] === 'Media') ? 'selected' : ''; ?>>Media</option>
                        <option value="Alta" <?php echo ($tarea['prioridad'] === 'Alta') ? 'selected' : ''; ?>>Alta</option>
                    </select>
                    <input type="text" name="tarea-responsable" value="<?php echo $tarea['responsable']; ?>" placeholder="Responsable de la tarea">
                    <input type="submit" value="Guardar Cambios">
                </form>
                <?php
                if ($tarea['edited'] == 1) {
                    echo "<p><strong>Edited</strong></p>";
                }
                ?>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    echo "ID de tarea no proporcionado.";
}
