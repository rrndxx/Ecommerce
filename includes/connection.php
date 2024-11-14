<?php
$newConnection = new Connection();

class Connection
{
    private $server = "mysql:host=localhost;dbname=ecommerce";
    private $user = "root";
    private $pass = "";
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
    protected $con;

    public function openConnection()
    {
        try {
            $this->con = new PDO($this->server, $this->user, $this->pass, $this->options);
            return $this->con;
        } catch (PDOException $th) {
            echo "There is a problem in the connection: " . $th->getMessage();
        }
    }

    public function beginTransaction()
    {
        $this->openConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->openConnection()->commit();
    }

    public function rollBack()
    {
        $this->openConnection()->rollBack();
    }

    public function getUsers()
    {
        try {
            $connection = $this->openConnection();
            $statement = $connection->prepare('SELECT * FROM users');
            $statement->execute();
            return $statement->fetchAll();
        } catch (PDOException $th) {
            echo '' . $th->getMessage();
            return [];
        }
    }

    public function registerUser()
    {
        if (isset($_POST['register'])) {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $address = $_POST['address'];
            $birthdate = $_POST['birthdate'];
            $gender = $_POST['gender'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            $db = $this->openConnection();

            $statement = $db->prepare('INSERT INTO users (first_name, last_name, address, birthdate, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $statement->execute([$first_name, $last_name, $address, $birthdate, $gender, $username, $password]);

            header('Location: login.php');
            exit();
        }
    }

    public function getProducts()
    {
        try {
            $connection = $this->openConnection();

            $query = "
            SELECT 
                p.id, 
                p.product_name, 
                c.category_name, 
                p.price, 
                p.stock, 
                p.date_created 
            FROM 
                products p 
            INNER JOIN 
                categories c ON p.category_id = c.category_id
        ";

            $statement = $connection->prepare($query);
            $statement->execute();
            return $statement->fetchAll();
        } catch (PDOException $th) {
            echo 'Error: ' . $th->getMessage();
        }
    }

    public function getProductById($productId)
    {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->openConnection()->prepare($query);
        $stmt->bindParam(1, $productId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addProduct()
    {
        if (isset($_POST['addProduct'])) {
            try {
                $product_name = $_POST['productName'];
                $category_id = $_POST['productCategory'];
                $price = $_POST['productPrice'];
                $stock = $_POST['productStock'];

                $connection = $this->openConnection();
                $insert = $connection->prepare('INSERT INTO products (product_name, category_id, price, stock) VALUES (?, ?, ?, ?)');
                $insert->execute([$product_name, $category_id, $price, $stock]);

                header('Location: manage_products.php');
                exit();
            } catch (PDOException $th) {
                echo '' . $th->getMessage();
            }
        }
    }

    public function addCategory()
    {
        if (isset($_POST['addCategory'])) {
            $category_name = htmlspecialchars($_POST['categoryName']);

            if (!empty($category_name)) {
                try {
                    $connection = $this->openConnection();

                    $statemnt = $connection->prepare('SELECT category_name FROM categories WHERE category_name = ?');
                    $statemnt->execute([$category_name]);
                    $result = $statemnt->fetch();

                    if (!$result) {
                        $statemnt = $connection->prepare('INSERT INTO categories (category_name) VALUES (?)');
                        $statemnt->execute([$category_name]);
                        header('Location: manage_products.php');
                        exit();
                    } else {
                        echo 'There is already a category called ' . $category_name . '.';
                    }
                } catch (PDOException $th) {
                    echo 'Error: ' . $th->getMessage();
                }
            } else {
                echo 'Category name cannot be empty.';
            }
        }
    }

    public function getCategories()
    {
        try {
            $connection = $this->openConnection();
            $statement = $connection->prepare('SELECT * FROM categories');
            $statement->execute();
            return $statement->fetchAll();
        } catch (PDOException $th) {
            echo '' . $th->getMessage();
            return [];
        }
    }

    public function processCheckout($orderData, $cartItems)
    {
        try {
            $this->beginTransaction();

            $stmt = $this->openConnection()->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
            $stmt->execute([$orderData['user_id'], $orderData['total_amount']]);

            $orderId = $this->openConnection()->lastInsertId();

            foreach ($cartItems as $item) {
                $stmt = $this->openConnection()->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            }

            $this->commit();
        } catch (Exception $e) {
            $this->rollBack();
            echo "Error during checkout: " . $e->getMessage();
        }
    }
}
