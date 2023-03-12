        <?php

        $p_name = $_POST['p_name'];
        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $p_id = $_POST['p_id'];

        $new_product = array(
            'p_id' =>  $p_id,
            'p_name' =>  $p_name,
            'qty' => $qty,
            'price' =>  $price
        );



        if (isset($_COOKIE['cart'])) {

            $get_cart = json_decode($_COOKIE['cart'], true);

            foreach ($get_cart as $id => $key) {
                if ($p_name == $key["p_name"]) {
                    unset($get_cart[$id]);
                }
            }
        } else {
            $get_cart = [];
        }

        array_push($get_cart, $new_product);
        setcookie('cart', json_encode($get_cart));

        echo "<script> location.href='homepage.php'; </script>";