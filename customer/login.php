<?php
session_start();

include_once("../includes/connection.php");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $newConnection = new Connection();
    $db = $newConnection->openConnection();

    $statement = $db->prepare('SELECT username, first_name, `password` FROM users WHERE username = ?');
    $statement->execute([$username]);
    $user = $statement->fetch();

    if ($user && $password === $user->password) {
        $_SESSION['user'] = $user->first_name;
        header("Location: dashboard.php");
        exit();
    } else {
        echo 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: "Sour Gummy", sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: url('../assets/background2.jpg') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        overflow: hidden;
        color: white;
    }

    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        filter: blur(10px);
        z-index: -1;
    }

    .container {
        position: relative;
        padding: 30px;
        max-width: 500px;
        width: 100%;
        background-color: rgba(202, 135, 135, 0.9);
        border-radius: 20px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    }

    .text-center h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .form-control {
        border-radius: 10px;
        margin-bottom: 1rem;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        width: 100%;
        padding: 12px;
        font-size: 1.2rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        background-color: orange;
        border: none;
    }

    .btn-primary:hover {
        background-color: #BE5504;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .register-link {
        font-size: 1rem;
    }

    .register-link a {
        text-decoration: none;
        font-weight: 600;
        color: orange;
    }

    .register-link a:hover {
        color: #BE5504;
    }

    .register-link .separator {
        padding: 0 10px;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .register-link p {
        margin: 0;
        font-size: 1rem;
    }


    .register-link p {
        margin: 0;
        /* Remove any extra margin */
        font-size: 1rem;
        /* Ensure the text has consistent sizing */
    }

    @media (max-width: 576px) {
        .container {
            padding: 20px;
        }

        .text-center h2 {
            font-size: 1.5rem;
        }

        .form-control {
            font-size: 0.9rem;
        }

        .btn-primary {
            font-size: 1rem;
        }
    }
</style>

<body>
    <div class="container p-4">
        <form action="" method="POST">
            <div class="text-center py-4">
                <h2>Customer Login</h2>
            </div>
            <div class="mb-3">
                <label for="inputUsername" class="form-label">Username</label>
                <input type="text" class="form-control" id="inputUsername" value="sample" name="username">
            </div>
            <div class="mb-3">
                <label for="inputPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="inputPassword" value="sample" name="password">
            </div>
            <div class="text-center pt-2">
                <button type="submit" class="btn btn-primary" name="login">Login</button>
            </div>
            <hr>
            <div class="register-link text-center">
                <a href="../admin/login.php" class="d-inline-block me-2">Login as Admin</a>
                <span class="separator">|</span>
                <a href="register.php" class="d-inline-block ms-2">Register</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>