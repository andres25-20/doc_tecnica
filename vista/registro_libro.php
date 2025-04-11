<?php
require_once __DIR__ . '/../conexion/database.php';

$db = new Database();
$conn = $db->getConnection();


$autores = $conn->query("SELECT id_autor, nombre FROM autor ORDER BY nombre ASC");
$categorias = $conn->query("SELECT id_categoria, nombre FROM categoria ORDER BY nombre ASC");
$estados = $conn->query("SELECT id_estado, nombre FROM estado WHERE id_estado = 1 ORDER BY nombre ASC");

$libros = $conn->query("SELECT l.titulo, a.nombre AS autor, c.nombre AS categoria, e.nombre AS estado, l.id_libro, a.id_autor, c.id_categoria, e.id_estado,COUNT(ml.id_libro) AS prestado FROM libro l JOIN autor a ON l.id_autor = a.id_autor JOIN categoria c ON l.id_categoria = c.id_categoria JOIN estado e ON l.id_estado = e.id_estado LEFT JOIN miembro_libro ml ON l.id_libro = ml.id_libro GROUP BY l.id_libro ORDER BY l.id_libro DESC");
?>

<div class="container mt-5" style="background-color: #dce8fd; padding: 30px; border-radius: 15px;">
    <h5><strong><i class="fa-solid fa-book-medical"></i> Registro de Libros</strong></h5>

    <form id="formAgregarLibro">
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <input type="text" class="form-control" name="titulo" placeholder="Título del libro" required>
            </div>
            <div class="col-md-2 mb-3">
                <select class="form-select" name="id_autor"  required>
                    <option value="" disabled selected >Autor</option>
                    <?php while ($row = $autores->fetch_assoc()) { ?>
                        <option value="<?= $row['id_autor'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-select" name="id_categoria"  required>
                    <option value="" disabled selected>Categoria</option>
                    <?php while ($row = $categorias->fetch_assoc()) { ?>
                        <option value="<?= $row['id_categoria'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <select class="form-select" name="id_estado"  required>
                    <option value="" disabled selected >Estado</option>
                    <?php while ($row = $estados->fetch_assoc()) { ?>
                        <option value="<?= $row['id_estado'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-1 text-end">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </div>
    </form>

    <hr>

    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead style="background-color: #1e2a78; color: white;">
                <tr>
                    <th style="border-top-left-radius: 10px;">Título</th>
                    <th>Autor</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                    <th style="border-top-right-radius: 10px;">Acción</th>
                </tr>
            </thead>
            <tbody style="background-color: #ffffff;">
                <?php while ($row = $libros->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['titulo']) ?></td>
                        <td><?= htmlspecialchars($row['autor']) ?></td>
                        <td><?= htmlspecialchars($row['categoria']) ?></td>
                        <td><?= htmlspecialchars($row['estado']) ?></td>
                        
                        <td>
                            <?php if ($row['prestado'] == 0): ?>
                                <button class="btn btn-warning btn-sm editarLibro" 
                                    data-id="<?= $row['id_libro'] ?>"
                                    data-titulo="<?= htmlspecialchars($row['titulo']) ?>"
                                    data-id_autor="<?= $row['id_autor'] ?>"
                                    data-id_categoria="<?= $row['id_categoria'] ?>"
                                    data-id_estado="<?= $row['id_estado'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-muted" title="Libro en préstamo"><i class="fa-solid fa-lock"></i></span>
                            <?php endif; ?>
                            <button class="btn btn-danger btn-sm eliminarLibro" data-id="<?= $row['id_libro'] ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalEditarLibro" tabindex="-1" aria-labelledby="editarLibroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form id="formEditarLibro">
                <div class="modal-header">
                <h5 class="modal-title" id="editarLibroLabel">Editar Libro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="edit_id_libro" name="id">
                <div class="mb-3">
                    <label>Título</label>
                    <input type="text" class="form-control" name="titulo" id="edit_titulo" required>
                </div>
                <div class="mb-3">
                    <label>Autor</label>
                    <select class="form-select" name="id_autor" id="edit_autor" required>
                    <?php foreach ($autores as $autor): ?>
                        <option value="<?= $autor['id_autor'] ?>"><?= $autor['nombre'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Categoría</label>
                    <select class="form-select" name="id_categoria" id="edit_categoria" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id_categoria'] ?>"><?= $categoria['nombre'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Estado</label>
                    <select class="form-select" name="id_estado" id="edit_estado" required>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['id_estado'] ?>"><?= $estado['nombre'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Guardar cambios</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5"><br>
    <div style="display: flex; flex-direction: column;">
        <img cñass="log-f" src="img/logo_p.png" alt="logo" style="width: 80px;">
        <p style="font-weight: bold; font-size: 11px;">VIRTUALBOOKS</p>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#formAgregarLibro').submit(function (e) {
        e.preventDefault();

        const formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'conexion/controller.php',
            data: formData + '&accion=agregar_libro',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "¡Libro registrado!",
                        showConfirmButton: false,
                        timer: 2000
                    });

                    setTimeout(function () {
                        $('#formAgregarLibro')[0].reset();
                        $("#content-container").load("vista/registro_libro.php?nocache=" + new Date().getTime());
                    }, 2000);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.error || "Error al registrar libro.",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Problema con la solicitud.",
                });
            }
        });
    });
});

$(document).on('click', '.eliminarLibro', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('conexion/controller.php', { accion: 'eliminar_libro', id }, function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Libro eliminado",
                        showConfirmButton: false,
                        timer: 2000
                    });
                    setTimeout(() => {
                        $("#content-container").load("vista/registro_libro.php?nocache=" + new Date().getTime());
                    }, 2000);
                }
            }, 'json');
        }
    });
});

$(document).on('click', '.editarLibro', function () {
    $('#edit_id_libro').val($(this).data('id'));
    $('#edit_titulo').val($(this).data('titulo'));
    $('#edit_autor').val($(this).data('id_autor'));
    $('#edit_categoria').val($(this).data('id_categoria'));
    $('#edit_estado').val($(this).data('id_estado'));
    $('#modalEditarLibro').modal('show');
});

$('#formEditarLibro').submit(function (e) {
    e.preventDefault();
    const datos = $(this).serialize() + '&accion=modificar_libro';

    $.post('conexion/controller.php', datos, function (response) {
        if (response.success) {
            Swal.fire({
                icon: "success",
                title: "Libro actualizado",
                showConfirmButton: false,
                timer: 2000
            });
            $('#modalEditarLibro').modal('hide');
            setTimeout(() => {
                $("#content-container").load("vista/registro_libro.php?nocache=" + new Date().getTime());
            }, 2000);
        }
    }, 'json');
});

</script>

<?php $conn->close(); ?>
