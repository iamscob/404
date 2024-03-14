<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";


require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$email = '';
$metodaPlatnosci = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $metodaPlatnosci = $_POST['metoda_platnosci'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.zoho.eu';
        $mail->SMTPAuth = true;
        $mail->Username = 'phptestproject@zohomail.eu';
        $mail->Password = 'jakiekolwiek_haslo';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('phptestproject@zohomail.eu', 'McDup@');
        $mail->addAddress($email);
        $mail->Subject = 'Potwierdzenie zakupu';
        $produkty = array();
        $totalPrice = 0;

        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            try {
                $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $statement = $connection->prepare("SELECT * FROM products WHERE id = :product_id");
                    $statement->bindParam(':product_id', $product_id);
                    $statement->execute();
                    $product = $statement->fetch(PDO::FETCH_ASSOC);

                    $product_name = $product['name'];
                    $product_price = $product['price'];
                    $subtotal = $product_price * $quantity;
                    $totalPrice += $subtotal;

                    $produkty[] = array('nazwa' => $product_name, 'cena' => $subtotal);
                }

                $wiadomosc = "Kupione produkty:\n";

                foreach ($produkty as $produkt) {
                    $wiadomosc .= $produkt['nazwa'] . " - " . $produkt['cena'] . " PLN\n";
                }

                $wiadomosc .= "\nSuma zakupu: " . $totalPrice . " PLN";

                $mail->Subject = 'Potwierdzenie zakupu';
                $mail->Body = $wiadomosc;

                $mail->send();
                echo 'Paragon wysłany na adres: ' . $email;
            } catch (Exception $e) {
                echo 'nie wysłane ' . $mail->ErrorInfo;
            }
        } else {
            echo 'pusto.';
        }
    } catch (Exception $e) {
        echo 'nie wysłane: ' . $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Formularz płatności</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(190, 255, 255, 1) 60%, rgba(143, 236, 255, 1) 100%);

        }

        .form-container {
            min-width: 800px;
            padding: 20px;
            background-color: #FFF;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="header bg-blue-500 bg-opacity-60 text-gray-800 text-center py-4 text-2xl font-bold">
        Formularz płatności
    </div>
    <a class='back-button' href='kosz.php'>Wróć do koszyka</a>
    <div class="cart-container">
        <h2>Wybierz metodę płatności</h2>
        <?php
        $email = '';
        $metodaPlatnosci = '';
        ini_set('SMTP', 'smtp.gmail.com');
        ini_set('smtp_port', 587);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $metodaPlatnosci = $_POST['metoda_platnosci'];

            if ($metodaPlatnosci == 'karta') {
                $numerKarty = $_POST['numer_karty'];

                if (!preg_match('/^[0-9]+$/', $numerKarty)) {
                    echo "Numer karty kredytowej jest niepoprawny.";
                    exit;
                }
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "Adres e-mail jest niepoprawny.";
                exit;
            }}
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label class="inline-block mb-2">Metoda płatności:</label>
                <div class="flex items-center">
                    <input type="radio" id="karta" name="metoda_platnosci" value="karta" required>
                    <label for="karta" class="ml-2">Karta</label>
                </div>
                <div id="numer-karty" class="mb-4 hidden">
                    <label for="numer-karty-input" class="block mb-2 ">Numer karty kredytowej:</label>
                    <input type="text" id="numer-karty-input" name="numer_karty" class="w-full px-3 py-2 border border-gray-300  rounded">
                </div>
                <div class="flex items-center">
                    <input type="radio" id="gotowka" name="metoda_platnosci" value="gotowka" required>
                    <label for="gotowka" class="ml-2">Gotówka</label>
                </div>
            </div>


            <div class="mb-4">
                <label for="email" class="block mb-2">Adres e-mail:</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded"
                       required value="<?php echo $email; ?>">
            </div>

            <div class="mb-4">
                <label class="inline-block mb-2">Metoda odbioru:</label>
                <div class="flex items-center">
                    <input type="radio" id="kasa" name="metoda_odbioru" value="kasa" required>
                    <label for="kasa" class="ml-2">Przy kasie</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" id="stolik" name="metoda_odbioru" value="stolik" required>
                    <label for="stolik" class="ml-2">Przy stoliku</label>
                </div>
            </div>

            <div id="numer-stolika" class="mb-4 hidden">
                <label for="numer" class="block mb-2">Numer stolika:</label>
                <input type="text" id="numer" name="numer_stolika" class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>



            <button type="submit" class="pay-button bg-blue-500 text-white px-4 py-2 rounded">Zapłać</button>
        </form>
    </div>
</div>
</body>
<script>
    const metodaOdbioruRadio = document.querySelectorAll('input[name="metoda_odbioru"]');
    const numerStolikaInput = document.getElementById('numer-stolika');
    const numerKartyInput = document.getElementById('numer-karty');
    const metodaPlatnosciRadio = document.querySelectorAll('input[name="metoda_platnosci"]');

    metodaOdbioruRadio.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === 'Stolik') {
                numerStolikaInput.classList.add('hidden');
            } else {
                numerStolikaInput.classList.remove('hidden');
            }
        });
    });

    metodaPlatnosciRadio.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.value === 'karta') {
                numerKartyInput.classList.remove('hidden');
            } else {
                numerKartyInput.classList.add('hidden');
            }
        });
        });
</script>

</html>
