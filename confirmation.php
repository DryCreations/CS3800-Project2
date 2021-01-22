<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require_once("inc/open_db.php");
include("inc/functions.php");
session_start();
if (isset($_SESSION['user'])) {
    $cart = get_cart($db, $_SESSION['user']);
}

?>

<html>
    <head>
        <title>Order Confirmation</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <?php
        if (isset($_SESSION['user'])) {
            update_inventory($db, $cart);
           
            clear_cart($db, $_SESSION['user']);

            $total = array_sum(array_map(function ($i) {
                return (float)($i['price'] * $i['quantity']);
                
            } ,$cart));
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
            } else {
               
                echo '<h2>Order Confirmation</h2>';
                echo '<p>Your order was placed on '.date('F j, Y \a\t g:i a').'</p>';
                echo '<p>Your order total is $'.number_format($total, 2).'</p>';
                echo '<p>Your order will be shipped to the following address</p><p>';
                
                
                echo filter_var($_POST['fname'], FILTER_SANITIZE_STRING).' '.filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
                echo '<br>';
                echo filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                echo '<br>';
                echo filter_var($_POST['city'], FILTER_SANITIZE_STRING).', '.filter_var($_POST['state'], FILTER_SANITIZE_STRING).' '.filter_var($_POST['zip'], FILTER_VALIDATE_INT);
                 
                echo '</p>';
                echo '<form action="index.php">';
                echo    '<input type="submit" value="Continue Shopping" />';
                echo '</form>';
                
            }
            ?>
            
        </main>
        <?php include('inc/footer.php') ?>
    </body>
</html>
