<?php
require_once __DIR__ . '/../conexion/database.php'; 

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT id_categoria, nombre FROM categoria ORDER BY id_categoria DESC";
$result = $conn->query($query);
?>

<div class="container mt-5" style="background-color: #dce8fd; padding: 30px; border-radius: 15px;">
    <h5><strong><i class="fa-solid fa-list"></i> Registro De Categorias</strong></h5>

    
    <div class="text-end">
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregar">Agregar Categoria</button>
    </div>

    <div class="table-responsive mt-3">
        <table class="table mb-0">
            <thead style="background-color: #1e2a78; color: white;">
                <tr>
                    <th style="border-top-left-radius: 10px;">No</th>
                    <th>Nombre</th>
                    <th style="border-top-right-radius: 10px;">Acción</th>
                </tr>
            </thead>
            <tbody style="background-color: #ffffff;" id="tablaCategorias">
                <?php $cont =1;
                        while ($row = $result->fetch_assoc()){ ?>
                    <tr>
                        <td><?= $cont ++ ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td style="text-align: center;">
                            <button class="btn btn-danger btn-sm eliminarCategoria" data-id="<?= $row['id_categoria'] ?>">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarLabel">Nueva Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 
                    <form id="formAgregar">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="nombreAgregar" name="nombre" placeholder="Ingrese nombre de la Categoria"  required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="container mt-5 mb-5">
    <div style="display: flex; flex-direction: column;">
        <img cñass="log-f" src="img/logo_p.png" alt="logo" style="width: 80px;">
        <p style="font-weight: bold; font-size: 11px;">VIRTUALBOOKS</p>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formAgregar').submit(function(e) {
        e.preventDefault();

        const nombre = $('#nombreAgregar').val();

        if (nombre === "") {
            Swal.fire({
                icon: "warning",
                title: "Campo vacío",
                text: "Por favor, ingresa el nombre de la Categoria.",
            });
            return;
        }
        $.ajax({
            type: 'POST',
            url: 'conexion/controller.php',
            data: {
                accion: 'agregar_categoria',
                nombre: nombre
            },
            dataType: 'json',
            success: function(response) {
                console.log('acag| llego esto');
                console.log(response.success);
                if (response.success===true) {
                    $('#modalAgregar').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "¡Éxito!",
                        text: "Categoría registrada correctamente.",
                        showConfirmButton: false,
                        timer: 2000
                    });

                setTimeout(function() {
                    $('#formAgregar')[0].reset();
                    $("#content-container").load("vista/registro_categoria.php", function() {
                    });
                }, 2000);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: response.error || "Ocurrió un error al registrar la categoría.",
                    });
                }
            },
            error: function() {
                console.error("AJAX Error:", status, error);
                Swal.fire({
                    icon: "error",
                    title: "Error de conexión",
                    text: "Hubo un problema con la solicitud. Intenta nuevamente.",
                });
            }
        });
    });

    function cargarCategorias() {
        $.ajax({
            url: 'controlador/controlador.php',
            type: 'GET',
            data: { accion: 'listar_categorias' },
            success: function(data) {
                $('#tablaCategorias').html(data);
            }
        });
    }

});

$(document).on('click', '.eliminarCategoria', function () {
    const id = $(this).data('id');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'POST',
                url: 'conexion/controller.php',
                data: {
                    accion: 'eliminar_categoria',
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Eliminado',
                            'La categoría ha sido eliminada.',
                            'success'
                        );
                        setTimeout(function () {
                            $("#content-container").load("vista/registro_categoria.php?nocache=" + new Date().getTime());
                        }, 1500);
                    } else {
                        Swal.fire('Error', response.error || 'Ocurrió un error', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error en la solicitud', 'error');
                }
            });
        }
    });
});
</script>

<?php $conn->close(); ?>
