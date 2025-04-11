<?php
require_once __DIR__ . '/../conexion/database.php';

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT  l.id_libro, l.titulo, l.id_estado, e.nombre AS estado_libro, m.id_miembro, m.nombre AS nombre_miembro, est_miembro.nombre AS estado_miembro FROM libro l LEFT JOIN estado e ON l.id_estado = e.id_estado LEFT JOIN miembro_libro ml ON l.id_libro = ml.id_libro LEFT JOIN miembro m ON ml.id_miembro = m.id_miembro LEFT JOIN estado est_miembro ON m.id_estado = est_miembro.id_estado ORDER BY l.id_libro DESC";

$result = $conn->query($query);
?>

<div class="container mt-5" style="background: #4982d773; padding: 20px; border-radius: 10px;">
    <h4><i class="fa-solid fa-book"></i> Reporte de Libros</h4>
    <table class="table mt-3">
        <thead style="background-color: #1e2a78; color: white;">
            <tr>
                <th>#</th>
                <th>Libro</th>
                <th>Miembro</th>
                <th>Estado del libro</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody style="background-color: #ffffff;">
            <?php $contador = 1; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $contador++ ?></td>
                    <td><?= htmlspecialchars($row['titulo']) ?></td>
                    <td><?= $row['nombre_miembro'] ? htmlspecialchars($row['nombre_miembro']) : '<em>No alquilado</em>' ?></td>
                    <td><?= htmlspecialchars($row['estado_libro']) ?></td>
                    <td>
                        <?php if (in_array($row['id_estado'], [2, 3, 4, 6])): ?>
                            <button class="btn btn-warning btn-sm devolverLibro" 
                                data-id="<?= $row['id_libro'] ?>" 
                                data-miembro="<?= $row['id_miembro'] ?>">
                                <i class="fa-solid fa-rotate-left"></i> Devolver
                            </button>
                        <?php else: ?>
                            <span class="text-muted">No disponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="container mt-5 mb-5">
    <div style="display: flex; flex-direction: column;">
        <img cñass="log-f" src="img/logo_p.png" alt="logo" style="width: 80px;">
        <p style="font-weight: bold; font-size: 11px;">VIRTUALBOOKS</p>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.devolverLibro').click(function() {
        const id_libro = $(this).data('id');
        const id_miembro = $(this).data('miembro');

        Swal.fire({
            title: '¿Devolver libro?',
            text: 'Esta acción actualizará el estado del libro y el miembro.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('conexion/controller.php', {
                    accion: 'devolver_libro',
                    id_libro: id_libro,
                    id_miembro: id_miembro
                }, function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Devuelto",
                            text: "El libro fue devuelto exitosamente.",
                            showConfirmButton: false,
                            timer: 2000
                        });

                            setTimeout(function () {
                                $("#content-container").load("vista/reporte.php");
                            }, 2000);
                    } else {
                        Swal.fire('Error', response.error || 'No se pudo devolver el libro.', 'error');
                    }
                }, 'json');
            }
        });
    });
});
</script>

<?php $conn->close(); ?>
