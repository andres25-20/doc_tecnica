<?php
require_once __DIR__ . '/../conexion/database.php'; // Ruta relativa a database.php

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT id_autor, nombre FROM autor ORDER BY id_autor DESC";
$result = $conn->query($query);
?>


<div class="container mt-5">
    <h2><i class="fa-solid fa-book"></i>  Reporte de Libros</h2>
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th><i class="fa-solid fa-hashtag"></i> No</th>
                <th><i class="fa-solid fa-book-open-reader"></i> Libro</th>
                <th><i class="fa-solid fa-user"></i> Miembro</th>
                <th><i class="fa-solid fa-square-poll-vertical"></i> Estado</th>
            </tr>
        </thead>
    </table>
</div>

<?php $conn->close(); ?>
