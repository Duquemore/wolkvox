<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Wolkvox</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .product-list {
            margin-bottom: 20px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .product-item span {
            margin-right: 10px;
        }
        .product-form input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h1>Lista de Productos</h1>
    <div class="product-list" id="product-list">
        <!-- Aquí se mostrarán los productos -->
    </div>
    <h2>Agregar Producto</h2>
    <form class="product-form" id="product-form">
        <input type="text" id="product-name" placeholder="Nombre del producto" required>
        <input type="number" id="product-price" placeholder="Precio del producto" required>
        <button type="submit">Agregar</button>
    </form>

    <script>
        let products = [];

        function renderProducts() {
            const productList = document.getElementById('product-list');
            productList.innerHTML = '';
            products.forEach((product, index) => {
                const productItem = document.createElement('div');
                productItem.className = 'product-item';
                productItem.innerHTML = `
                    <span>${product.name}</span>
                    <span>$${product.price.toFixed(2)}</span>
                    <button onclick="editProduct(${index})">Editar</button>
                    <button onclick="deleteProduct(${index})">Eliminar</button>
                `;
                productList.appendChild(productItem);
            });
        }

        function addProduct(name, price) {
            products.push({ name, price });
            renderProducts();
        }

        function editProduct(index) {
            const newName = prompt('Nuevo nombre del producto:', products[index].name);
            const newPrice = prompt('Nuevo precio del producto:', products[index].price);
            if (newName !== null && newPrice !== null) {
                products[index].name = newName;
                products[index].price = parseFloat(newPrice);
                renderProducts();
            }
        }

        function deleteProduct(index) {
            products.splice(index, 1);
            renderProducts();
        }

        document.getElementById('product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = document.getElementById('product-name').value;
            const price = parseFloat(document.getElementById('product-price').value);
            addProduct(name, price);
            document.getElementById('product-form').reset();
        });

        renderProducts();
    </script>
</body>
</html>