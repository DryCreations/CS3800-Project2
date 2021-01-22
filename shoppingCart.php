<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Store</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <?php 
    
    session_start();
    if (isset($_SESSION['user'])) {
         require_once("inc/open_db.php");
        include("inc/functions.php");

        if (isset($_POST['submit'])){
            add_to_cart($db, $_POST['itemNum'], $_SESSION['user'], $_POST['value']);
        }
        $msg = check_cart($db, $_SESSION['user']);
        $cart = get_cart($db, $_SESSION['user']);

    }
   
    
    
    ?>
    <body>
        <header>
            <h1>Ducks</h1>
        </header>
        <main>
            <?php
            if (!isset($_SESSION['user'])) {
                echo '<p>you are not authorized to view this page, redirecting...</p>';
                header("refresh:2; url=index.php");
            } else if (count($cart) > 0) {
                if (isset($msg)) {
                    echo '<p>'.$msg.'</p>';
                    
                }
                echo '<table>';
                echo '<thead><tr>';
                echo '<th colspan="4">Order Summary - '.$cart[0]['firstName'].' '.$cart[0]['lastName'].'</th>';
                echo '</tr>';
                echo '<tr>';
                echo '<th>Description</th>';
                echo '<th>Quantity</th>';
                 echo '<th>Unit Price</th>';
                echo '<th>Total Price</th>';
                echo '</tr></thead>';
                echo '<tbody>';
                $total = 0;
                foreach($cart as $product) {
                    echo '<tr>';
                    echo '<td>'.$product['description'].'</td>';
                    echo '<td>'.$product['quantity'];
                    echo '<form action="shoppingCart.php" method="post"><input type="hidden" name="value" value="1" /><input type="hidden" name="itemNum" value="'.$product['itemNumber'].'"/><input '.($product['quantity'] >= $product['maxQuantity'] ? 'disabled title="Insufficient Stock"' : '').' name="submit" type="submit" value="+"/></form>';
                    echo '<form action="shoppingCart.php" method="post"><input type="hidden" name="value" value="-1" /><input type="hidden" name="itemNum" value="'.$product['itemNumber'].'"/><input name="submit" type="submit" value="-"/></form>';
                    echo '</td>';
                    echo '<td>$'.number_format($product['price'], 2).'</td>';
                    $total += $product['price'] * $product['quantity'];
                    echo '<td>$'.number_format($product['price'] * $product['quantity'], 2).'</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '<tfoot>';
                echo '<tr>';
                echo '<td colspan="3">Order Total</td>';
                echo '<td>$'.number_format($total,2).'</td>';
                echo '</tr>';
                echo '</tfoot>';
                echo '</table>';
                echo '<form action="index.php"><input type="submit" value="Continue Shopping" /></form>';
                echo '<form action="shipping.php"><input type="submit" value="Proceed to shipping info" /></form>';
            } else {
                if (isset($msg)) {
                    echo '<p>'.$msg.'</p>';
                }
                echo '<p>Your cart is empty</p>';
                echo '<form action="index.php" ><input type="submit" value="Continue Shopping" /></form>';
            }
            ?>
        </main>
        <?php include('inc/footer.php') ?>
    </body>
</html>
