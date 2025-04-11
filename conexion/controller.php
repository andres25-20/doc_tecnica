<?php //Archivo de ejecucion de acciones para todos los modulos y acciones a ejecutar

require_once __DIR__ . '/../conexion/database.php';

$db = new Database();
$conn = $db->getConnection();


if ($_POST['accion'] === 'buscar_libros') {
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');

    $query = "SELECT l.id_libro, l.titulo, a.nombre AS autor, c.nombre AS categoria, e.nombre AS estado, l.id_estado FROM libro l LEFT JOIN autor a ON l.id_autor = a.id_autor LEFT JOIN categoria c ON l.id_categoria = c.id_categoria LEFT JOIN estado e ON l.id_estado = e.id_estado WHERE l.titulo LIKE '%$nombre%'";

    $result = $conn->query($query);

    $libros = [];
    while ($row = $result->fetch_assoc()) {
        $libros[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $libros]);
    exit;
}

if ($_POST['accion'] === 'registrar_prestamo') {
    $id_libro = intval($_POST['id_libro']);
    $id_miembro = intval($_POST['id_miembro']);
    $id_estado_nuevo = intval($_POST['id_estado']);
    $check = $conn->query("SELECT id_estado FROM libro WHERE id_libro = $id_libro");
    $row = $check->fetch_assoc();

    if (!$row || $row['id_estado'] != 1) {
        echo json_encode(['success' => false, 'error' => 'Este libro no está disponible para préstamo.']);
        exit;
    }
    $conn->query("INSERT INTO miembro_libro (id_libro, id_miembro) VALUES ($id_libro, $id_miembro)");
    $conn->query("UPDATE libro SET id_estado = $id_estado_nuevo WHERE id_libro = $id_libro");
    $conn->query("UPDATE miembro SET libros_prestado = libros_prestado + 1 WHERE id_miembro = $id_miembro");

    echo json_encode(['success' => true]);
    exit;
}

if ($_POST['accion'] === 'agregar_categoria') {
    $nombre = trim($_POST['nombre'] ?? '');

        if (!empty($nombre)) {
            $stmt = $conn->prepare("INSERT INTO categoria (nombre) VALUES (?)");
            $stmt->bind_param("s", $nombre);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
        }
        
}


if ($_POST['accion'] === 'eliminar_categoria') {
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM categoria WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
    }
}

if ($_POST['accion'] === 'agregar_autor') {
    $nombre = trim($_POST['nombre'] ?? '');

    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO autor (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'El nombre está vacío']);
    }
}

if ($_POST['accion'] === 'eliminar_autor') {
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM autor WHERE id_autor = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
    }
}

if ($_POST['accion'] === 'agregar_libro') {
    $titulo = trim($_POST['titulo'] ?? '');
    $id_autor = intval($_POST['id_autor'] ?? 0);
    $id_categoria = intval($_POST['id_categoria'] ?? 0);
    $id_estado = intval($_POST['id_estado'] ?? 0);

    if (!empty($titulo) && $id_autor > 0 && $id_categoria > 0 && $id_estado > 0) {
        $stmt = $conn->prepare("INSERT INTO libro (titulo, id_autor, id_categoria, id_estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $titulo, $id_autor, $id_categoria, $id_estado);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    }
}

if ($_POST['accion'] === 'eliminar_libro') {
    $id = intval($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM libro WHERE id_libro = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'ID inválido']);
    }
}

if ($_POST['accion'] === 'modificar_libro') {
    $id = intval($_POST['id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $id_autor = intval($_POST['id_autor'] ?? 0);
    $id_categoria = intval($_POST['id_categoria'] ?? 0);
    $id_estado = intval($_POST['id_estado'] ?? 0);

    if ($id > 0 && !empty($titulo) && $id_autor > 0 && $id_categoria > 0 && $id_estado > 0) {
        $stmt = $conn->prepare("UPDATE libro SET titulo = ?, id_autor = ?, id_categoria = ?, id_estado = ? WHERE id_libro = ?");
        $stmt->bind_param("siiii", $titulo, $id_autor, $id_categoria, $id_estado, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    }
}

if ($_POST['accion'] === 'agregar_miembro') {
    $nombre = $_POST['nombre'] ?? '';
    $estado = $_POST['id_estado'] ?? 0;

    if ($nombre && $estado) {
        $stmt = $conn->prepare("INSERT INTO miembro (nombre, id_estado, libros_prestado) VALUES (?, ?, 0)");
        $stmt->bind_param("si", $nombre, $estado);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al registrar miembro.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos.']);
    }
    exit;
}

if ($_POST['accion'] === 'eliminar_miembro') {
    $id = $_POST['id'];
    $queryVerificar = $conn->prepare("SELECT libros_prestado FROM miembro WHERE id_miembro = ?");
    $queryVerificar->bind_param("i", $id);
    $queryVerificar->execute();
    $resultado = $queryVerificar->get_result();
    $miembro = $resultado->fetch_assoc();

    if ($miembro && $miembro['libros_prestado'] > 0) {
        echo json_encode([
            'success' => false,
            'error' => 'No se puede eliminar el miembro porque tiene libros prestados.'
        ]);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM miembro WHERE id_miembro = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el miembro.']);
    }

    exit;
}

if ($_POST['accion'] === 'devolver_libro') {
    $id_libro = $_POST['id_libro'];
    $id_miembro = $_POST['id_miembro'];

    try {
        $conn->query("UPDATE libro SET id_estado = 1 WHERE id_libro = $id_libro");

        $conn->query("DELETE FROM miembro_libro WHERE id_libro = $id_libro AND id_miembro = $id_miembro");

        $conn->query("UPDATE miembro SET libros_prestado = libros_prestado - 1 WHERE id_miembro = $id_miembro");

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

$conn->close();
?>
