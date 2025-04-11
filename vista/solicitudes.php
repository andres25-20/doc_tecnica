<?php
require_once __DIR__ . '/../conexion/database.php'; 

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT id_autor, nombre FROM autor ORDER BY id_autor DESC";
$result = $conn->query($query);
?>


<div class="container mt-5">
    <h2><i class="fa-solid fa-sliders"></i>  Solicitudes - Acciones por Modulo </h2>

    <div class="col-12"><br><br>
        <a href="vista/registro_libro.php" class="btn btn-primary cargar-vista" style="width: 15%;"><i class="fa fa-book-medical" style="font-size: 7em;" aria-hidden="true"></i><br>Libros</a>
        <a href="vista/registro_categoria.php" class="btn btn-success cargar-vista" style="width: 15%;"><i class="fa fa-list-ul" style="font-size: 7em;" aria-hidden="true"></i><br>
         Categorias</a>
        <a href="vista/registro_autor.php" class="btn btn-secondary cargar-vista" style="width: 15%;"><i class="fa-solid fa-user-plus" style="font-size: 7em;" aria-hidden="true"></i><br>Autor</a>
        <a href="vista/registro_miembro.php" class="btn btn-warning cargar-vista" style="width: 15%;"><i class="fa-solid fa-users" style="font-size: 7em;" aria-hidden="true"></i><br>Miembros</a>
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
    
    $(document).on('click', '.cargar-vista', function (e) {
        e.preventDefault(); 

        const url = $(this).attr('href'); 

        $('#content-container').load(url); 
    });
});
</script>
<?php $conn->close(); ?>
