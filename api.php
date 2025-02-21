<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wolkvox";

$conn = new mysqli($servername, $username, $password, $dbname);

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $price = $data['price'];
    $sql = "INSERT INTO productos (name, price) VALUES ('$name', '$price')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Producto agregado']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $index = $data['index'];
    $name = $data['name'];
    $price = $data['price'];
    $sql = "UPDATE productos SET name='$name', price='$price' WHERE id=$index";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Producto editado']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $index = $data['index'];
    $sql = "DELETE FROM productos WHERE id=$index";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Producto eliminado']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
    exit;
}

$conn->close();
?>