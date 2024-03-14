<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Koszyk</title>
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

        .cart-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #FFF;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .cart-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th,
        .cart-table td {
            padding: 10px;
            border-bottom: 1px solid #EEE;
        }

        .cart-table th {
            background-color: rgba(22, 107, 211, 0.715);
            color: #FFF;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #888;
            margin-top: 50px;
        }

        .delete-button {
            background-color: #FF0000;
            color: #FFF;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .back-button {
            background-color: #333;
            color: #FFF;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .total-price {
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }

        .pay-button {
            background-color: #0D6EFD;
            color: #FFF;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 20px;
            float: right;
        }
        .popup-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .popup-content {
            background-color: #FFF;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            text-align: center;
            position: relative; /* Dodano */
        }

        .popup-close {
            position: absolute;
            top: 1px;
            right: 10px;
            cursor: pointer;
            color: #888;
        }
        .back-button {
            background-color: #333;
            color: #FFF;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="header bg-blue-500 bg-opacity-60 text-gray-800 text-center py-4 text-2xl font-bold">
    Koszyk
</div>
<a class='back-button' href='menu.php'>Wróć do menu</a>
<div class="cart-container">
    <h2>Zawartość koszyka</h2>

    <?php
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        try {
            $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['remove_product']) && !empty($_POST['remove_product'])) {
                $product_id = $_POST['remove_product'];
                unset($_SESSION['cart'][$product_id]);
                echo "<p style='color: green;'>Produkt został usunięty z koszyka.</p>";
            }


            echo "<table class='cart-table'>
                    <thead>
                        <tr>
                            <th>Nazwa produktu</th>
                            <th>Ilość</th>
                            <th>Akcja</th>
                        </tr>
                    </thead>
                    <tbody>";

            $totalPrice = 0;

            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $statement = $connection->prepare("SELECT * FROM products WHERE id = :product_id");
                $statement->bindParam(':product_id', $product_id);
                $statement->execute();
                $product = $statement->fetch(PDO::FETCH_ASSOC);

                $product_name = $product['name'];
                $product_price = $product['price'];
                $subtotal = $product_price * $quantity;
                $totalPrice += $subtotal;

                echo "<tr>";
                echo "<td>" . $product_name . "</td>";
                echo "<td>$quantity</td>";
                echo "<td>
                        <form method='post' action=''>
                            <input type='hidden' name='remove_product' value='$product_id'>
                            <button class='delete-button' type='submit'>Usuń</button>
                        </form>
                      </td>";
                echo "</tr>";
            }

            echo "</tbody>
                </table>";

            echo "<p class='total-price'>Suma: " . number_format($totalPrice, 2) . " PLN</p>";

            echo "<a href='formularz.php' class='pay-button'>Zapłać</a>";
        } catch (PDOException $e) {
            echo "Błąd połączenia: " . $e->getMessage();
        }
    } else {
        echo "<p class='empty-cart'>pusto</p>";
    }
    ?>
    <script>
        setTimeout(function() {
            location.href = 'index.html';
        }, 50000);
    </script>
</body>
</html>
