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
            <input type="text" class="form-control" placeholder="Ingrese nombre del libro a consultar">
            <button class="btn btn-primary" type="button">Buscar</button>
        </div><br><br>
    </div>
</div>

<div class="container mt-3" style="background: #4982d773; padding: 20px;">
    <h5>Resultados</h5>
    <table class="table mt-3" styñe="border-radius: 10px; ">
    <thead style="background-color: #1e2a78; color: white;">
        <tr>
            <th style="border-top-left-radius: 10px;">LIBRO</th>
            <th>Autor</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th style="border-top-right-radius: 10px;">Acciones</th>
        </tr>
    </thead>
        <tbody style="background-color: #ffffff;">
            <tr>
                <td ><input class="border-inf" type="text" value="Texto" readonly onmousedown="return false;" /></td>
                <td ><input class="border-inf" type="text" value="Texto" readonly onmousedown="return false;" /></td>
                <td ><input class="border-inf" type="text" value="Texto" readonly onmousedown="return false;" /></td>
                <td ><input class="border-inf" type="text" value="Texto" readonly onmousedown="return false;" /></td>
                <td>
                    <button class="btn btn-light accion-bt" >+</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="container mt-5 mb-5">
    <div style="display: flex; flex-direction: column;">
        <img cñass="log-f" src="img/logo_p.png" alt="logo" style="width: 80px;">
        <p style="font-weight: bold; font-size: 11px;">VIRTUALBOOKS</p>
    </div>
</div>

<?php $conn->close(); ?>
