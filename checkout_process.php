<?php
session_start();
include "db.php";

if (isset($_SESSION["uid"])) {
    $f_name = $_POST["firstname"];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $cardname = $_POST['cardname'];
    $cardnumber = $_POST['cardNumber'];
    $expdate = $_POST['expdate'];
    $cvv = $_POST['cvv'];
    $user_id = $_SESSION["uid"];
    $cardnumberstr = (string)$cardnumber;
    $total_count = $_POST['total_count'];
    $prod_total = $_POST['total_price'];
    
    // Assuming exchange rate from USD to NPR is 120 (replace with your actual exchange rate)
    $exchange_rate = 100;

    $sql0 = "SELECT order_id from `orders_info`";
    $runquery = mysqli_query($con, $sql0);
    
    if (mysqli_num_rows($runquery) == 0) {
        $order_id = 1;
    } else if (mysqli_num_rows($runquery) > 0) {
        $sql2 = "SELECT MAX(order_id) AS max_val from `orders_info`";
        $runquery1 = mysqli_query($con, $sql2);
        $row = mysqli_fetch_array($runquery1);
        $order_id = $row["max_val"];
        $order_id = $order_id + 1;
    }

    $sql = "INSERT INTO `orders_info` 
    (`order_id`, `user_id`, `f_name`, `email`, `address`, 
    `city`, `state`, `zip`, `cardname`, `cardnumber`, `expdate`, `prod_count`, `total_amt`, `cvv`) 
    VALUES ($order_id, '$user_id', '$f_name', '$email', 
    '$address', '$city', '$state', '$zip', '$cardname', '$cardnumberstr', '$expdate', '$total_count', '$prod_total', '$cvv')";

    if (mysqli_query($con, $sql)) {
        $i = 1;

        while ($i <= $total_count) {
            $str = (string)$i;
            $prod_id_+$str = $_POST['prod_id_'.$i];
            $prod_id = $prod_id_+$str;
            
            $prod_price_+$str = $_POST['prod_price_'.$i];
            $prod_price = $prod_price_+$str;
            
            // Convert product price from USD to NPR using the exchange rate
            $prod_price_npr = (int)$prod_price * $exchange_rate; // Convert to NPR
            
            $prod_qty_+$str = $_POST['prod_qty_'.$i];
            $prod_qty = $prod_qty_+$str;
            
            $sub_total = (int)$prod_price_npr * (int)$prod_qty;
            $sql1 = "INSERT INTO `order_products` 
            (`order_pro_id`, `order_id`, `product_id`, `qty`, `amt`) 
            VALUES (NULL, '$order_id', '$prod_id', '$prod_qty', '$sub_total')";

            if (mysqli_query($con, $sql1)) {
                $del_sql = "DELETE from cart where user_id=$user_id";
                if (mysqli_query($con, $del_sql)) {
                    echo "<script>window.location.href='store.php'</script>";
                } else {
                    echo(mysqli_error($con));
                }
            } else {
                echo(mysqli_error($con));
            }
            $i++;
        }
    } else {
        echo(mysqli_error($con));
    }
} else {
    echo "<script>window.location.href='index.php'</script>";
}
?>
