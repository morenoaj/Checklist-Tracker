<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Tareas</title>
    <link rel="stylesheet" href="./css/restilos.css">
</head>
<body>
<nav class="navbar">
        <ul class="navbar-list">
            <li class="navbar-item"><a class="button-link" href="Dashboard.php">Dashboard</a></li>
        </ul>
    </nav>
    <div class="content-container">
        <?php
        // Función para mostrar un mensaje de error
        function mostrarError($mensaje) {
            echo "<div class='error'>$mensaje</div>";
        }

        try {
            // Abrir la conexión a la base de datos
            $conn = new mysqli("localhost", "root", "", "my_checklist_tracker");

            if ($conn->connect_error) {
                throw new Exception("Conexión fallida: " . $conn->connect_error);
            }

            $tareas = [];

            if (isset($_POST['ConsultarFiltro'])) {
                $filtro = $_POST['filtro'];
                // Verifica si el índice 'valor' está definido en el arreglo $_POST
                $valor = isset($_POST['valor']) ? $_POST['valor'] : '';

                $sql = "";
                switch ($filtro) {
                    case "prioridad":
                        $sql = "CALL FilterByPriority(?)";
                        break;
                    case "estado":
                        $sql = "CALL FilterByStatus(?)";
                        break;
                    case "responsable":
                        $sql = "CALL FilterByResponsible(?)";
                        break;
                    case "dia":
                        $sql = "CALL FilterByDay(?)";
                        break;
                    case "semana":
                        $sql = "CALL FilterByWeek(?)";
                        break;
                    case "mes":
                        $sql = "CALL FilterByMonth(?)";
                        break;
                    case "año":
                        $sql = "CALL FilterByYear(?)";
                        break;
                }

                if (!empty($sql)) {
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $valor);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $tareas[] = $row;
                        }
                    }

                    $stmt->close();
                }
            } else {
                // Consultar todas las tareas
                $sql = "CALL GetAllTasks()";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $tareas[] = $row;
                    }
                }
            }

            // Resto del código para mostrar las tareas
            $nfilas = count($tareas);

            if ($nfilas > 0) {
                echo "<h1 class='kanban-head-title'>Reporte de Tareas</h1>";

                // Formulario de filtro
                echo '<form name="FormFiltro" method="post" action="Report.php" class="kanban-form-container">';
                echo '<br/> Filtrar por: ';
                echo '<select name="filtro" class="input-text" id="filtroSelect" onchange="cambiarTipoCampo()">';
                echo '<option value="" disabled selected>Selecione un Filtro</option>';
                echo '<option value="prioridad">Prioridad</option>';
                echo '<option value="estado">Estado</option>';
                echo '<option value="responsable">Responsable</option>';
                echo '<option value="dia">Día</option>';
                echo '<option value="semana">Semana</option>';
                echo '<option value="mes">Mes</option>';
                echo '<option value="año">Año</option>';
                echo '</select>';
                echo ' con el valor <span id="campoValor"></span>';
                echo '<input type="submit" name="ConsultarFiltro" value="Filtrar Datos" class="btn-crear" />';
                echo '<input type="submit" name="ConsultarTodos" value="Ver todos" class="btn-crear" />';
                echo '</form>';

                // Tabla de tareas
                echo '<table>';
                echo '<tr>';
                echo '<th>Nombre</th>';
                echo '<th>Descripción</th>';
                echo '<th>Estado</th>';
                echo '<th>Prioridad</th>';
                echo '<th>Responsable</th>';
                echo '<th>Fecha Compromiso</th>';
                echo '<th>Hora de Culminación</th>';
                echo '</tr>';

                foreach ($tareas as $tarea) {
                    echo '<tr>';
                    echo '<td>' . $tarea['nombre'] . '</td>';
                    echo '<td>' . $tarea['descripcion'] . '</td>';
                    echo '<td>' . $tarea['estado'] . '</td>';
                    echo '<td>' . $tarea['prioridad'] . '</td>';
                    echo '<td>' . $tarea['responsable'] . '</td>';
                    echo '<td>' . $tarea['fecha_compromiso'] . '</td>';
                    echo '<td>' . $tarea['hora_culminacion'] . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo "No hay tareas disponibles";
            }
        } catch (Exception $e) {
            mostrarError("Error: " . $e->getMessage());
        } finally {
            // Cerrar la conexión a la base de datos
            if (isset($conn)) {
                $conn->close();
            }
        }
        ?>

        <script>
        function cambiarTipoCampo() {
        var filtroSelect = document.getElementById("filtroSelect");
        var campoValor = document.getElementById("campoValor");

        var selectedValue = filtroSelect.options[filtroSelect.selectedIndex].value;

        // Comprobar el valor seleccionado y cambiar el tipo de campo de entrada
        if (selectedValue === "dia" || selectedValue === "semana") {
            campoValor.innerHTML = '<input type="date" name="valor" class="input-text">';
        } else {
            campoValor.innerHTML = '<input type="text" name="valor" class="input-text">';
        }
    }
        </script>
    </div>
</body>
</html>