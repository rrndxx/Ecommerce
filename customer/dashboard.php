<?php
session_start();
include_once("../includes/connection.php");

$newConnection = new Connection();
$products = $newConnection->getProducts();
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
            color: white;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: rgba(168, 118, 118, 0.9);
            color: white;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            background-color: #FFD0D0;
            background-image: url('../assets/background.jpg');
            background-size: cover;
            background-position: center;
            height: auto;
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

        .table thead {
            background-color: rgba(168, 118, 118, 0.9);
            color: white;
        }

        table thead th {
            position: sticky;
            top: 0;
            background-color: rgba(168, 118, 118, 0.9);
            z-index: 1;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #E1ACAC;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .table-container {
            margin-bottom: 30px;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: black;
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

        .scrollable-table {
            max-height: 300px;
            overflow-y: auto;
            display: block;
        }

        /* Add min-height to tables */
        .table {
            min-height: 300px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white mb-2 mt-2">Roxanne's Shop</h4>
        <hr>
        <a href="" class="active"><i class="bi bi-house-door"></i> Dashboard</a>
        <a href="manage_products.php"><i class="bi bi-box"></i> Manage Products</a>
        <div class="sidebar-footer">
            <hr><button class="btn btn-danger w-100" onclick="confirmLogout()">Logout</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <div class="wew d-flex justify-content-between">
                <h1>Welcome, <?php echo  $_SESSION['user']?></h1>
            </div>
            <hr>

            <!-- Products Table -->
            <div class="table-container">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="section-title">Products List</h4>
                    <div class="filter-section">
                        <button class="filter-btn">Filter By Category</button>
                        <input type="text" id="searchInput" class="search-input" placeholder="Search Products...">
                    </div>
                </div>
                <hr>
                <div class="scrollable-table">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Category</th>
                                <th scope="col">Price</th>
                                <th scope="col">Stock</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center" id="productTableBody">
                            <?php foreach ($products as $product): ?>
                                <tr class="product-row">
                                    <th scope="row"><?= $product->id ?></th>
                                    <td><?= $product->product_name ?></td>
                                    <td><?= $product->category_name ?></td>
                                    <td>₱<?= $product->price ?></td>
                                    <td><?= $product->stock ?></td>
                                    <td><?= $product->date_created ?></td>
                                    <td>
                                        <button class="btn btn-primary mx-2"><i class="bi bi-pencil"></i> Edit</button>
                                        <button class="btn btn-danger mx-2"><i class="bi bi-trash"></i> Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Users Table -->
            <div class="table-container">
                <h4 class="section-title">Users List</h4>
                <div class="scrollable-table">
                    <table class="table  table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Birthdate</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Username</th>
                                <th scope="col">Date Joined</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <th scope="row"><?= $user->user_id ?></th>
                                    <td><?= $user->first_name ?></td>
                                    <td><?= $user->last_name ?></td>
                                    <td><?= $user->address ?></td>
                                    <td><?= $user->birthdate ?></td>
                                    <td><?= $user->gender ?></td>
                                    <td><?= $user->username ?></td>
                                    <td><?= $user->date_joined ?></td>
                                </tr>
                        </tbody>
                    <?php endforeach; ?>
                    </table>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="table-container">
                <h4 class="section-title">Orders List</h4>
                <div class="scrollable-table">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr class="text-center">
                                <th scope="col">ID</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <th scope="row">1</th>
                                <td>2024-11-10</td>
                                <td>John Doe</td>
                                <td>$150.00</td>
                                <td>Pending</td>
                                <td>
                                    <button class="btn btn-primary mx-2"><i class="bi bi-pencil"></i> Edit</button>
                                    <button class="btn btn-danger mx-2"><i class="bi bi-trash"></i> Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const productName = row.cells[1].textContent.toLowerCase();
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