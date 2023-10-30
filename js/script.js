function updateTaskStatus(taskId, newStatus) {
    $.ajax({
        url: 'app/update.php',
        method: 'POST',
        data: {
            taskId: taskId,
            newState: newStatus
        },
        success: function(response) {
            console.log("Tarea actualizada con éxito.");
            location.reload();
        },
        error: function(error) {
            console.error("Error al actualizar la tarea.");
        }
    });
}

$(document).ready(function() {
    $(".kanban-block").sortable({
        connectWith: ".kanban-block",
        update: function(event, ui) {
            var taskId = ui.item.attr("id").split("-")[1]; // Obtiene el ID de la tarea
            var targetKanbanBlock = ui.item.parent().attr("id");

            // Obtiene el estado actual de la tarea
            var currentState = ui.item.data("estado");

            if (targetKanbanBlock === "pendientes") {
                var newStatus = 'Pendiente';
                updateTaskStatus(taskId, newStatus);
            } else if (targetKanbanBlock === "procesos" && currentState !== 'Pendiente') {
                var newStatus = 'En Proceso';
                updateTaskStatus(taskId, newStatus);
            } else if (targetKanbanBlock === "completados" && currentState === 'En Proceso') {
                var newStatus = 'Completado';
                updateTaskStatus(taskId, newStatus);
            }

            console.log("taskId: " + taskId);
            console.log("targetKanbanBlock: " + targetKanbanBlock);
            console.log("currentState: " + currentState);
            console.log("newStatus: " + newStatus);

        }
    });

    $(".kanban-block").disableSelection();
});
