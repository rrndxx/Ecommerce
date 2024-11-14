<?php
session_start();
include_once("../includes/connection.php");

$newConnection = new Connection();
$products = $newConnection->getProducts();

// Initialize cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['cart_total'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Get product details
    $product = $newConnection->getProductById($product_id);

    // Check if the product is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            $cart_item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    // If not found, add the product to the cart
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'product_name' => $product->product_name,
            'price' => $product->price,
            'quantity' => $quantity
        ];
    }

    // Recalculate the cart total
    $_SESSION['cart_total'] = 0;
    foreach ($_SESSION['cart'] as $item) {
        $_SESSION['cart_total'] += $item['price'] * $item['quantity'];
    }

    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid form resubmission
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Sour Gummy";
            background-image: url('../assets/background.jpg');
            background-size: cover;
            /* Ensures the image covers the full screen */
            background-position: center;
            /* Centers the image */
            background-attachment: fixed;
            /* Keeps the background fixed when scrolling */
            color: white;
            min-height: 100vh;
            /* Ensure full viewport height */
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: rgba(255, 255, 255, 0.3);
            /* Slight transparent background */
            backdrop-filter: blur(2px);
            /* Applies blur effect to the sidebar */
            color: white;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }


        .sidebar a {
            color: #E1ACAC;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            text-align: start;
            border-radius: 5px;
            margin: 5px 0;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .sidebar .active {
            background-color: rgba(255, 255, 255, 0.7);
            font-weight: bold;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 10px;
        }

        .sidebar i {
            margin-right: 10px;
        }

        .sidebar .btn {
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 10px;
            }

            .content {
                margin-left: 0;
            }
        }


        .highlight {
            background-color: #E1ACAC !important;
            transition: background-color 0.3s ease;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
            color: #333;
        }

        .product-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .cart-card {
            height: 100vh;
            overflow-y: auto;
        }

        .filter-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 15px;
        }

        .filter-btn,
        .search-input {
            border-radius: 5px;
        }

        .search-input {
            padding: 5px 10px;
            width: 250px;
        }

        .filter-btn {
            padding: 5px 10px;
            background-color: #E1ACAC;
            border: none;
            color: white;
            cursor: pointer;
        }

        .filter-btn:hover {
            background-color: #d79999;
        }

        .modal-content {
            background-color: #E1ACAC;
            color: white;
            border-radius: 10px;
        }

        .modal-header {
            background-color: #E1ACAC;
            border-bottom: 1px solid #E1ACAC;
            color: white;
        }

        .modal-footer {
            background-color: #E1ACAC;
            border-top: 1px solid #E1ACAC;
            color: white;
        }

        .modal-body img {
            width: 100%;
            height: auto;
            object-fit: cover;
            max-height: 200px;
        }

        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }

        .btn-primary {
            background-color: #d79999;
            border-color: #d79999;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white mb-2 mt-2">Roxanne's Shop</h4>
        <hr>
        <a href="" class="active"><i class="bi bi-house-door"></i> Dashboard</a>
        <div class="sidebar-footer">
            <hr><button class="btn btn-danger w-100" onclick="confirmLogout()">Logout</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <div class="d-flex justify-content-center">
                <h1>Welcome, <?php echo  $_SESSION['user'] . "!" ?></h1>
            </div>
            <hr>
            <div class="col-md-12">
                <!-- Products Cards -->
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="section-title">Products List</h4>
                        <div class="filter-section">
                            <button class="filter-btn">Filter By Category</button>
                            <input type="text" id="searchInput" class="search-input" placeholder="Search Products...">
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            <!-- Products and Cart -->
            <div class="row">
                <!-- Products Section (Two Columns) -->
                <div class="col-md-8">
                    <div class="product-cards">
                        <?php foreach ($products as $product): ?>
                            <div class="card product-row">
                                <img src="../assets/sample.avif" alt="<?= $product->product_name ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $product->product_name ?></h5>
                                    <p class="card-text">
                                        Category: <?= $product->category_name ?><br>
                                        Price: ₱<?= $product->price ?><br>
                                        Stock: <b style="color: <?php if ($product->stock > 70) {
                                                                    echo 'green';
                                                                } else if ($product->stock > 30) {
                                                                    echo 'orange';
                                                                } else {
                                                                    echo 'red';
                                                                } ?>"><?= $product->stock ?></b><br>
                                    </p>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cartModal" data-product-id="<?= $product->product_id ?>" data-product-name="<?= $product->product_name ?>" data-product-price="<?= $product->price ?>"><i class="bi bi-cart"></i>
                                            Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Cart Section (One Column) -->
                <div class="col-md-4">
                    <div class="card cart-card">
                        <div class="card-body">
                            <h5 class="card-title">Your Cart</h5>
                            <hr>
                            <?php if (!empty($_SESSION['cart'])): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($_SESSION['cart'] as $item): ?>
                                            <tr>
                                                <td><?= $item['product_name'] ?></td>
                                                <td>₱<?= $item['price'] ?></td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td>₱<?= $item['price'] * $item['quantity'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>Total:</strong>
                                    <strong>₱<?= $_SESSION['cart_total'] ?></strong>
                                </div>
                                <hr>
                                <button class="btn btn-success w-100">Proceed to Checkout</button>
                            <?php else: ?>
                                <p>Your cart is empty.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cartModalLabel">Add to Cart</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <input type="hidden" name="product_id" id="modal-product-id">
                                <div class="mb-3">
                                    <img src="../assets/sample.avif">
                                </div>
                                <div class="mb-3">
                                    <label for="modal-product-name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="modal-product-name" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="modal-product-price" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="modal-product-price" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="modal-quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="modal-quantity" class="form-control" min="1" required>
                                </div>
                                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = '../logout.php';
            }
        }
        const cartModal = document.getElementById('cartModal');
        cartModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productPrice = button.getAttribute('data-product-price');

            // Set modal values
            document.getElementById('modal-product-id').value = productId;
            document.getElementById('modal-product-name').value = productName;
            document.getElementById('modal-product-price').value = '₱' + productPrice;
        });
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();
            const productRows = document.querySelectorAll('.product-row');

            productRows.forEach(row => {
                const productName = row.querySelector('.card-title').textContent.toLowerCase();
                if (productName.includes(searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>