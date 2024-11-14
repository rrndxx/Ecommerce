<?php
include_once('../includes/connection.php');

$newConnection = new Connection();
$categories = $newConnection->getCategories();
$newConnection->addCategory();
$newConnection->addProduct();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
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
            backdrop-filter: blur(2px);
            color: white;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }


        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            text-align: start;
            border-radius: 5px;
            margin: 5px 0;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #E1ACAC;
        }

        .sidebar .active {
            background-color: #E1ACAC;
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

        .cards-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 150px;
            margin-top: 200px;
            flex-wrap: wrap;
        }

        .card {
            background-color: rgba(168, 118, 118, 0.9);
            border-radius: 10px;
            width: 350px;
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            background-color: #E1ACAC;
        }

        .card i {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .card h5 {
            font-size: 1.4rem;
            margin-top: 10px;
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

            .cards-container {
                justify-content: center;
            }

            .card {
                width: 200px;
                height: 250px;
            }
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
        <a href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <a href="" class="active"><i class="bi bi-box"></i> Manage Products</a>
        <div class="sidebar-footer">
            <hr><button class="btn btn-danger w-100" onclick="confirmLogout()">Logout</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <div class="d-flex justify-content-between">
                <h1>Manage Products</h1>
            </div>
            <hr>

            <!-- Animated Cards for Add Product and Add Category -->
            <div class="cards-container">
                <!-- Add Product Card -->
                <div class="card" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-circle"></i>
                    <h5>Add Product</h5>
                </div>

                <!-- Add Category Card -->
                <div class="card" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-folder-plus"></i>
                    <h5>Add Category</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-control" id="productCategory" name="productCategory" required>
                                <option value="" selected disabled>Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addProduct">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addCategory">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = '../logout.php';
            }
        }
    </script>
</body>

</html>