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
        <input type="text" name="product-name" placeholder="Nombre del producto" required>
        <input type="number" name="product-price" placeholder="Precio del producto" required>
        <button type="submit">Agregar</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productList = document.getElementById('product-list');
            const productForm = document.getElementById('product-form');

            // Fetch products from API
            async function fetchProducts() {
                try {
                    const response = await fetch('api.php');
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const products = await response.json();
                    console.log(products); // Verifica la respuesta de la API
                    displayProducts(products);
                } catch (error) {
                    console.error('There was a problem with the fetch operation:', error);
                }
            }

            // Display products in the DOM
            function displayProducts(products) {
                productList.innerHTML = '';
                products.forEach((product, index) => {
                    const productItem = document.createElement('div');
                    productItem.className = 'product-item';
                    productItem.innerHTML = `
                        <span>${product.name}</span>
                        <span>$${parseFloat(product.price).toFixed(2)}</span>
                        <form class="edit-form" data-index="${index}">
                            <input type="text" name="product-name" value="${product.name}" required>
                            <input type="number" name="product-price" value="${product.price}" required>
                            <button type="submit">Guardar</button>
                        </form>
                        <button class="delete-button" data-index="${index}">Eliminar</button>
                    `;
                    productList.appendChild(productItem);
                });
            }

            // Handle form submission for adding products
            productForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                const formData = new FormData(productForm);
                const product = {
                    name: formData.get('product-name'),
                    price: parseFloat(formData.get('product-price'))
                };
                await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(product)
                });
                fetchProducts();
            });

            // Handle form submission for editing products
            productList.addEventListener('submit', async (event) => {
                if (event.target.classList.contains('edit-form')) {
                    event.preventDefault();
                    const index = String(Number(event.target.dataset.index) + 1);
                    console.log(index);
                    const formData = new FormData(event.target);
                    const product = {
                        index: index,
                        name: formData.get('product-name'),
                        price: parseFloat(formData.get('product-price'))
                    };
                    await fetch('api.php', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(product)
                    });
                    fetchProducts();
                }
            });

            // Handle button click for deleting products
            productList.addEventListener('click', async (event) => {
                if (event.target.classList.contains('delete-button')) {
                    const index = String(Number(event.target.dataset.index) + 1);
                    await fetch('api.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ index: index })
                    });
                    fetchProducts();
                }
            });

            // Initial fetch of products
            fetchProducts();
        });
    </script>
</body>

</html>