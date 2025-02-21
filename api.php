<?php
session_start();

require __DIR__ . '/load_env.php';

// Cargar las variables de entorno
load_env(__DIR__ . '/.env');

// Configurar la conexión a la base de datos
$servername = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');


// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la lista de productos
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM productos";
    $result = $conn->query($sql);
    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode($products);
    exit;
}

// Agregar un nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['name']) && isset($data['price'])) {
        $name = $conn->real_escape_string($data['name']);
        $price = $conn->real_escape_string($data['price']);
        $sql = "INSERT INTO productos (name, price) VALUES ('$name', '$price')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Producto agregado']);
        } else {
            echo json_encode(['message' => 'Error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos']);
    }
    exit;
}

// Editar un producto existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['index']) && isset($data['name']) && isset($data['price'])) {
        $index = $conn->real_escape_string($data['index']);
        $name = $conn->real_escape_string($data['name']);
        $price = $conn->real_escape_string($data['price']);
        $sql = "UPDATE productos SET name='$name', price='$price' WHERE id=$index";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Producto editado']);
        } else {
            echo json_encode(['message' => 'Error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos']);
    }
    exit;
}

// Eliminar un producto
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['index'])) {
        $index = $conn->real_escape_string($data['index']);
        $sql = "DELETE FROM productos WHERE id=$index";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Producto eliminado']);
        } else {
            echo json_encode(['message' => 'Error: ' . $conn->error]);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos']);
    }
    exit;
}

// Cerrar la conexión
$conn->close();
?>