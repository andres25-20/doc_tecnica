<?php
require_once __DIR__ . '/../conexion/database.php';

$db = new Database();
$conn = $db->getConnection();

$estados = $conn->query("SELECT id_estado, nombre FROM estado WHERE id_estado IN (5, 6) ORDER BY nombre ASC");


$query = "SELECT m.id_miembro, m.nombre, m.libros_prestado, e.nombre AS estado FROM miembro m JOIN estado e ON m.id_estado = e.id_estado ORDER BY m.id_miembro DESC ";
$result = $conn->query($query);
?>

<div class="container mt-5" style="background-color: #dce8fd; padding: 30px; border-radius: 15px;">
    <h5><strong><i class="fa-solid fa-users"></i> Registro de Miembros</strong></h5>

    <div class="text-end">
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarMiembro">Agregar Miembro</button>
    </div>

    <div class="table-responsive mt-3">
        <table class="table mb-0">
            <thead style="background-color: #1e2a78; color: white;">
                <tr>
                    <th>No</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Libros Prestados</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody style="background-color: #ffffff;">
                <?php $cont = 1; while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $cont++ ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['estado']) ?></td>
                        <td><?= $row['libros_prestado'] ?></td>
                        <td>
                            <?php if ($row['libros_prestado'] == 0): ?>
                                <button class="btn btn-danger btn-sm eliminarMiembro" data-id="<?= $row['id_miembro'] ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-muted"><i class="fa-solid fa-lock"></i> Tiene libros</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalAgregarMiembro" tabindex="-1" aria-labelledby="modalAgregarMiembroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAgregarMiembro" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarMiembroLabel">Nuevo Miembro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre del miembro" required>
                </div>
                <div class="mb-3">
                    <select name="id_estado" class="form-select" required>
                        <option value="" disabled selected>Seleccione estado</option>
                        <?php while ($row = $estados->fetch_assoc()) { ?>
                            <option value="<?= $row['id_estado'] ?>"><?= $row['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="hidden" name="accion" value="agregar_miembro">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="container mt-5 mb-5"><br><br><br><br>
    <div style="display: flex; flex-direction: column;">
        <img cñass="log-f" src="img/logo_p.png" alt="logo" style="width: 80px;">
        <p style="font-weight: bold; font-size: 11px;">VIRTUALBOOKS</p>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#formAgregarMiembro').submit(function (e) {
        e.preventDefault();
        const datos = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'conexion/controller.php',
            data: datos,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#modalAgregarMiembro').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Miembro registrado',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    setTimeout(() => {
                        $("#content-container").load("vista/registro_miembro.php?nocache=" + new Date().getTime());
                    }, 2000);
                } else {
                    Swal.fire('Error', response.error || 'Ocurrió un error', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Ocurrió un error en la solicitud', 'error');
            }
        });
    });

    $(document).on('click', '.eliminarMiembro', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: '¿Estás seguro?',
            text: '¡No podrás revertir esto!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('conexion/controller.php', { accion: 'eliminar_miembro', id: id }, function (response) {
                    if (response.success) {
                        Swal.fire('Eliminado', 'Miembro eliminado con éxito.', 'success');
                        setTimeout(() => {
                            $("#content-container").load("vista/registro_miembro.php?nocache=" + new Date().getTime());
                        }, 2000);
                    }
                }, 'json');
            }
        });
    });
});
</script>

<?php $conn->close(); ?>
