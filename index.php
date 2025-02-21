<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Wolkvox</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="container-fluid row">
        <div class="col-4">
            <h2 class="my-4">Agregar Producto</h2>
            <form class="product-form" id="product-form">
                <div class="form-group">
                    <input type="text" class="form-control" name="product-name" placeholder="Nombre del producto" required>
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" name="product-price" placeholder="Precio del producto" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>
        <div class="col-8">
            <h1 class="my-4">Lista de Productos</h1>
            <div class="product-list" id="product-list">
                <!-- Aquí se mostrarán los productos -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                    productItem.className = 'product-item card p-3 mb-3';
                    productItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-weight-bold">${product.name}</span>
                                <span class="text-muted">$${parseFloat(product.price).toFixed(2)}</span>
                            </div>
                            <div>
                                <form class="edit-form d-inline" data-index="${index}">
                                    <input type="text" class="form-control d-inline-block" name="product-name" value="${product.name}" required>
                                    <input type="number" class="form-control d-inline-block" name="product-price" value="${product.price}" required>
                                    <button type="submit" class="btn btn-sm btn-success">Guardar</button>
                                </form>
                                <button class="btn btn-sm btn-danger delete-button" data-index="${index}">Eliminar</button>
                            </div>
                        </div>
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
                        body: JSON.stringify({
                            index: index
                        })
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