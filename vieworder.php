<?php
require_once "../db/Database.php";
// include_once "../../day9/dassboard/header.php";

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    header('Location: checkout.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>

    <?php
    $database = new Database();
    $db_cursor = $database->getConnection();

    $statement = $db_cursor->prepare("select od.* ,p.* from order_details od inner join products p on od.product_id = p.product_id where order_id =:oid");
    $statement->bindParam("oid", $order_id);
    $statement->execute();
    $orderdata = $statement->fetchAll();
    // $userdata = $statement->fetch();

    // echo "<pre>";
    // print_r($orderdata);
    // echo "</pre>";
    ?>

    <div class="container">
        <h2>Order Details</h2>
        <a href="homepage.php">Home</a>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>order Id</th>
                    <th>product Id</th>
                    <th>image</th>
                    <th>product name</th>
                    <th>model number</th>
                    <th>price</th>
                    <th>quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($orderdata as $order) {
                ?>
                    <tr>
                        <td><?php echo $order['order_id'] ?> </td>
                        <td><?php echo $order['product_id'] ?> </td>
                        <td>
                            <img src="<?php echo $order['product_image']; ?>" style="height: 50px; width: 50px;">

                        </td>
                        <td><?php echo $order['display_name'] ?> </td>
                        <td><?php echo $order['model_number'] ?></td>
                        <td><?php echo $order['product_price'] ?></td>
                        <td><?php echo $order['quantity'] ?></td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
    </div>

</body>