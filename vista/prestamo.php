<?php
require_once __DIR__ . '/../conexion/database.php'; // Ruta relativa a database.php

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT id_autor, nombre FROM autor ORDER BY id_autor DESC";
$result = $conn->query($query);
?>


<div class="container mt-5" style="background: #4982d773;">
    <div class="bloque-consulta"><br>
        <h5>Consulta de libros</h5>
        <div class="input-group mt-3">
            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" class="form-control" placeholder="Ingrese nombre del libro a consultar" id="busquedaLibro">
            <button class="btn btn-primary" type="button" id="btnBuscarLibro">Buscar</button>
        </div><br><br>

        <tbody id="resultadoLibros" style="background-color: #ffffff;">
        </tbody>
    </div>
</div>

<div class="modal fade" id="modalPrestamo" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formPrestamoLibro">
        <div class="modal-header">
          <h5 class="modal-title">Registrar Préstamo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_libro" id="prestamo_id_libro">
          <div class="mb-3">
            <label for="miembro" class="form-label">Seleccionar miembro:</label>
            <select class="form-select" name="id_miembro" id="miembro" required>
            <option value="" disabled selected >Opciones</option>
              <?php
              $miembros = $conn->query("SELECT id_miembro, nombre FROM miembro WHERE id_estado NOT IN (6)  ORDER BY nombre ASC");
              while ($row = $miembros->fetch_assoc()) {
                  echo '<option value="' . $row['id_miembro'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="estado" class="form-label">Seleccionar nuevo estado del libro:</label>
            <select class="form-select" name="id_estado" id="estado" required>
                <option value="" disabled selected>Opciones</option>
                <?php
                $estados = $conn->query("SELECT id_estado, nombre FROM estado WHERE id_estado IN (2,3,4)");
                while ($row = $estados->fetch_assoc()) {
                    echo '<option value="' . $row['id_estado'] . '">' . htmlspecialchars($row['nombre']) . '</option>';
                }
                ?>
            </select>
        </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Confirmar préstamo</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container mt-3" style="background: #4982d773; padding: 20px;">
    <h5>Resultados</h5>
    <table class="table mt-3" style="border-radius: 10px;">
        <thead style="background-color: #1e2a78; color: white;">
            <tr>
                <th style="border-top-left-radius: 10px;">LIBRO</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th style="border-top-right-radius: 10px;">Acción</th>
            </tr>
        </thead>
        <tbody id="resultadoLibros" style="background-color: #ffffff;">
        </tbody>
    </table>
</div>


<div class="container mt-5 mb-5">
    <div style="display: flex; flex-direction: column;">
        <img class="log-f" src="img/logo_p.png" alt="logo" style="width: 80px;">
        <p style="font-weight: bold; font-size: 11px;">VIRTUALBOOKS</p>
    </div>
</div>

<script>
    $(document).ready(function () {
    $('#btnBuscarLibro').click(function () {
        const nombre = $('#busquedaLibro').val();

        if (nombre.trim() === '') return;

        $.ajax({
            url: 'conexion/controller.php',
            type: 'POST',
            data: { accion: 'buscar_libros', nombre },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(libro => {
                        const boton = libro.estado === 'Disponible' ? 
                            `<button class="btn btn-light accion-bt prestarLibro" data-id="${libro.id_libro}" data-titulo="${libro.titulo}">+</button>` : 
                            `<span class="text-muted">No disponible</span>`;
                        
                        html += `
                            <tr>
                                <td><input class="border-inf" type="text" value="${libro.titulo}" readonly /></td>
                                <td><input class="border-inf" type="text" value="${libro.autor}" readonly /></td>
                                <td><input class="border-inf" type="text" value="${libro.categoria}" readonly /></td>
                                <td><input class="border-inf" type="text" value="${libro.estado}" readonly /></td>
                                <td>${boton}</td>
                            </tr>`;
                    });
                    $('#resultadoLibros').html(html);
                } else {
                    Swal.fire('Sin resultados', response.error || 'No se encontraron libros.', 'info');
                }
            },
            error: function () {
                Swal.fire('Error', 'No se pudo realizar la búsqueda.', 'error');
            }
        });
    });
});

$(document).on('click', '.prestarLibro', function () {
    const idLibro = $(this).data('id');
    $('#formPrestamoLibro')[0].reset();
    $('#prestamo_id_libro').val(idLibro);
    $('#modalPrestamo').modal('show');
});

$('#formPrestamoLibro').submit(function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + '&accion=registrar_prestamo';

    $.post('conexion/controller.php', formData, function (response) {
        if (response.success) {
            Swal.fire('Éxito', 'Préstamo registrado correctamente', 'success');
            $('#modalPrestamo').modal('hide');
            $('#btnBuscarLibro').click();
        } else {
            Swal.fire('Error', response.error || 'No se pudo registrar el préstamo', 'error');
        }
    }, 'json');
});


</script>
<?php $conn->close(); ?>
