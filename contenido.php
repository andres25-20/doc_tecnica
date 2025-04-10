<?php

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'reporte':
        include('vista/reporte.php');
        break;
    case 'solicitudes':
        include('vista/solicitudes.php');
        break;
    case 'prestamo':
        include('vista/prestamo.php');
        break;
    default:
        include('vista/home.php'); 
        break;
}
?>
