<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <?php
    # Display all brands in a better UI
    require_once 'Database.php';
    # Use PDO (PHP Data Objects) to connect to MySQL (MariaDB) database
    $database = new Database();
    $db = $database->getConnection();


    $stmt = $db->prepare("SELECT * FROM brands");
    $stmt->execute();
    $brands = $stmt->fetchAll();

    if (isset($_POST['btnsubmit']) && $_POST['btnsubmit'] == 'Add Product') {

        $displayname = $_POST['productname'];
        $brandid = $_POST['brandname'];
        $modelnumber = $_POST['modelnumber'];
        $productdescription = $_POST['productdescription'];
        $price = $_POST['price'];

        $updatestatus = true;

        if (isset($_POST['isactive']) && $_POST['isactive'] == 'on') {
            $isactive = true;
        } else {
            $isactive = false;
        }


        // image upload
        #   Set a directory to save uploaded files
        $target_dir = "productImage";

        #   Check if directory exists, create if it does not
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }


        $path_of_file_to_save = $target_dir . '/' . basename($_FILES["productimage"]["name"]);

        $imageinfo = getimagesize($_FILES["productimage"]["tmp_name"]);

        $flag_safe_to_upload = true;

        if ($imageinfo['mime']  != 'image/jpeg' && $imageinfo['mime']  != 'image/jpg') {
            echo ("<br>Only JPG is allowe <br><hr>");
            $flag_safe_to_upload = false;
            $updatestatus = false;
        }

        if ($flag_safe_to_upload == true) {
            if (!move_uploaded_file($_FILES["productimage"]["tmp_name"], $path_of_file_to_save)) {
                $updatestatus = false;
            }
        }





        $stmt = $db->prepare("INSERT INTO products(`brand_id`,`display_name`,`model_number`,`product_description`,`price`,`isActive`,`product_image`) 
                    VALUES (:p_brand_id, :p_display_name, :p_model_number, :p_product_description, :p_price, :p_isActive ,:image_file)");

        $stmt->bindParam(':p_brand_id', $brandid);
        $stmt->bindParam(':p_display_name', $displayname);
        $stmt->bindParam(':p_model_number', $modelnumber);
        $stmt->bindParam(':p_product_description', $productdescription);
        $stmt->bindParam(':p_price', $price);
        $stmt->bindParam(':p_isActive', $isactive);
        $stmt->bindParam(':image_file', $path_of_file_to_save);

        $stmt->execute();

        if ($stmt->rowCount() == 1 && $updatestatus == true) {
            echo ("<br>Product added<br>");
        }
    }

    $stmt = $db->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll();

    ?>

    <form action="<?php echo (basename($_SERVER['SCRIPT_FILENAME'])); ?>" method="post" enctype="multipart/form-data">
        <table class="centerme tableui" cellspacing=15>
            <tr>
                <th colspan=2 align=center>
                    <h2>Add Products</h2>
                </th>
            </tr>
            <tr>
                <td>
                    Product name
                </td>
                <td>
                    <input type="text" name=productname>
                </td>
            </tr>
            <tr>
                <td>
                    Brand
                </td>
                <td>
                    <select name=brandname>
                        <?php
                        foreach ($brands as $brand) {
                        ?>
                            <option value="<?php echo ($brand['brand_id']); ?>"><?php echo ($brand['brand_name']); ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Model number
                </td>
                <td>
                    <input type="text" name=modelnumber>
                </td>
            </tr>
            <tr>
                <td>
                    Product description
                </td>
                <td>
                    <textarea name=productdescription rows="6" cols=20></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    Price
                </td>
                <td>
                    <input type="text" name=price>
                </td>
            </tr>
            <tr>
                <td>
                    Image
                </td>
                <td>
                    <input type="file" name="productimage">
                </td>
            </tr>

            <tr>
                <td>
                    Active
                </td>
                <td>
                    <input type="checkbox" name=isactive>
                </td>
            </tr>
            <tr>
                <td colspan=2 align=center>
                    <input type="submit" value="Add Product" name="btnsubmit">
                </td>
            </tr>
        </table>

        <div class="container">
        <h2>All Products</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Model</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($products as $product) {
                ?>
                    <tr>
                        <td><img src="<?php echo $product['product_image'] ?>" alt="" style="height:50px ; width: 50px;"></td>
                        <td><?php echo $product['product_id'] ?> </td>
                        <td><?php echo $product['display_name'] ?></td>
                        <td><?php echo $product['model_number'] ?></td>
                        <td><?php echo $product['product_description'] ?></td>
                        <td>Rs.<?php echo $product['price'] ?></td>
                        <td><?php
                         if($product['isActive'])
                         {
                            echo "Active";
                         }  
                         else
                         {
                            echo "Not active";
                         }
                         ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>


    </form>

    <style>
        .centerme {
            margin-left: auto;
            margin-right: auto;
        }

        .tableui {
            border: 4;
            border-style: dashed;
        }
    </style>
</body>

</html>