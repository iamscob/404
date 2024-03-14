<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        addToCart($product_id, $quantity);
    }
}

function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
try {
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Błąd" . $e->getMessage();
}

$query = "SELECT * FROM Categories";
$categories = $connection->query($query)->fetchAll(PDO::FETCH_ASSOC);

$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

class Product {
    protected $connection;
    public $products;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function fetchProducts($query) {
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $this->products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

class CategoryProduct extends Product {
    public function __construct($connection, $category_id) {
        parent::__construct($connection);

        if ($category_id) {
            $query = "SELECT p.* FROM Products p
                      INNER JOIN Categories c ON p.category_id = c.id
                      WHERE c.id = :category_id";
            $statement = $this->connection->prepare($query);
            $statement->bindParam(':category_id', $category_id);
            $statement->execute();
            $this->products = $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $query = "SELECT * FROM Products";
            $this->fetchProducts($query);
        }
    }
}

$products = new CategoryProduct($connection, $category_id);
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(190, 255, 255, 1) 60%, rgba(143, 236, 255, 1) 100%);
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: rgba(22, 107, 211, 0.715);
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        .left-sidebar {
            flex: 0 0 200px;
            padding-right: 20px;
        }

        .left-sidebar .categories {
            padding: 0;
        }

        .left-sidebar .categories li {
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        .left-sidebar .categories li:hover {
            background-color: rgba(22, 107, 211, 0.715);
            color: #333;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .content .products {
            list-style: none;
            padding: 0;
        }

        .content .products li {
            display: inline-block;
            vertical-align: top;
            width: 200px;
            margin-right: 20px;
            background-color: #EEE;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin-bottom: 20px;
            margin-left: 130px;
            margin-top: 20px;
        }

        .content .products li h3 {
            margin-top: 0;
            font-size: 18px;
        }

        .content .products li img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .content .products li p {
            margin-bottom: 10px;
        }

        .content .products li form {
            display: flex;
            align-items: center;
        }

        .content .products li form input[type="number"] {
            width: 50px;
            margin-right: 10px;
        }

        .content .products li form button {
            padding: 5px 10px;
            background-color: rgba(22, 107, 211, 0.715);
            border: none;
            color: #333;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="header bg-blue-500 bg-opacity-60 text-gray-800 text-center py-4 text-2xl font-bold flex justify-between">
    <div>
        Menu
    </div>
    <div>
        <a href="kosz.php" class="text-black">Koszyk</a>
    </div>
</div>

<div class="menu-container">
    <div class="content flex flex-wrap justify-start items-start">
        <div class="left-sidebar">
            <ul class="categories">
                <?php foreach ($categories as $category) { ?>
                    <li class="transition hover:bg-blue-600 bg-opacity-60 text-gray-800 hover:text-gray-900 py-2 px-4 my-1">
                        <a href="menu.php?category_id=<?php echo $category['id']; ?>">
                            <?php echo $category['name']; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <ul class="products">
            <?php if (isset($products->products)) { ?>
                <?php foreach ($products->products as $product) { ?>
                    <li class="p-4 bg-white rounded-lg shadow-md">
                        <h3 class="text-xl font-semibold"><?php echo $product['name']; ?></h3>
                        <img src="<?php echo $product['image']; ?>" alt="Product Image" class="mb-2 max-h-600">
                        <p class="mb-2"><?php echo $product['product_description']; ?></p>
                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="flex items-center">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" min="1" value="1" class="w-20 mr-2">
                            <button type="submit" class="px-4 py-2 bg-blue-500 bg-opacity-60 text-gray-800 font-bold">Dodaj do zamówienia</button>
                        </form>
                    </li>
                <?php } ?>
            <?php } else { ?>
                <li>pusto :c</li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
    setTimeout(function() {
        location.href = 'index.html';
    }, 25000);
</script>
</body>
</html>