<!DOCTYPE html>
<?php include "product.php";
$count = 0;
if (isset($_COOKIE['cart'])) {
    $count =  count(json_decode($_COOKIE['cart'], true));
}

require_once '../db/database.php';
# Use PDO (PHP Data Objects) to connect to MySQL (MariaDB) database
$database = new Database();
$db = $database->getConnection();

?>
<html>

<head>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <h1 align=center>Online shooping </h1>
    <table class="centerme tableui ">
        <tr>
            <td colspan=5 style="text-align: right;"> <a class="btn btn-primary" href="checkout.php">My
                    cart(<?php echo $count ?>)</a>
        </tr>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>price</th>
            <th>quantity</th>
        </tr>
        <?php

        $stmt = $db->prepare("SELECT * FROM products order by product_id desc ");
        $stmt->execute();
        $products = $stmt->fetchAll();


        foreach ($products as $product) {
        ?>

        <form action="add_to_cart.php" method="post">
            <tr>
                <td>
                    <img src="<?php echo $product['product_image']; ?>" style="height: 50px; width: 50px;">
                </td>
                <td>
                    <input type="text" name="p_name" value="<?php echo $product["display_name"]; ?>">
                    <input type="hidden" name="p_id" value="<?php echo $product["product_id"]; ?>">
                </td>
                <td>
                    <input type=text name="price" value="<?php echo $product["price"]; ?>">
                </td>
                <td>
                    enter qty :<input type="number" name="qty" id="quantity" value="1">
                </td>
                <td>
                    <input class="btn btn-dark" type="submit" value="add to cart" onclick="">
                </td>
            </tr>
        </form>
        <?php
        }
        ?>

    </table>
</body>
<script>
function checkform() {
    var qty = document.getElementById("quantity").value;
    var flag = true;

    if (qty == 0) {

        alert("enter quantity");
        // alert(qty);
        flag = false;
    }

    return flag;
}
</script>

</html>