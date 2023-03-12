<?php 
include "product.php";

# Display all brands in a better UI
require_once '../db/Database.php';
# Use PDO (PHP Data Objects) to connect to MySQL (MariaDB) database

$database = new Database();

$db = $database->getConnection();


if (isset($_POST['checkuser'])) {
    
    $stmt = $db->prepare("INSERT INTO orders(`user_id`, `address_id`, `delivery_status_id`) VALUES (1, 1, 1)");
    $stmt->execute();
    $last_id = $db->lastInsertId();
    // echo "<pre>";
    // print_r($_COOKIE);
    // echo "</pre>";



    $get_cart = json_decode($_COOKIE['cart'], true);
    // print_r($get_cart);


    foreach ($get_cart as $key => $value) {

        $stmt = $db->prepare("INSERT INTO order_details (`order_id`, `product_id`, `product_price`,`quantity`) VALUES(:o_id , :pid , :price ,:qty )");
        $stmt->bindParam(':o_id', $last_id);
        $stmt->bindParam(':pid', $value["p_id"]);
        $stmt->bindParam(':price', $value["price"]);
        $stmt->bindParam(':qty', $value["qty"]);

        $stmt->execute();

        
    }

    $stmt = $db->prepare("update orders set order_total = (select sum(product_price) from order_details where order_id = :o_id) where order_id = :o_id");
    $stmt->bindParam(':o_id', $last_id);
    $stmt->execute();


    $url = "vieworder.php?order_id=" . $last_id;
    header("Location:$url");
}

?>



<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 align=center>My cart</h1>
    <table class="centerme tableui">
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        <?php
        if (isset($_COOKIE['cart'])) {
            $get_cart = json_decode($_COOKIE['cart'], true);

            foreach ($get_cart as $product) {
        ?>
                <tr>
                    <td><?php echo $product["p_name"] ?></td>
                    <td><?php echo $product["price"] ?></td>
                    <td> <?php echo $product["qty"] ?></td>
                    <td> <?php echo (int)$product["price"] * (int)$product["qty"] ?></td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <th colspan="4">no record</th>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="4">
                <form action=" <?php echo (basename($_SERVER['SCRIPT_FILENAME'])); ?>" method="post">
                    <input type="submit" value="Checkout" name="checkuser">
                </form>
            </td>
        </tr>
</body>

</html>