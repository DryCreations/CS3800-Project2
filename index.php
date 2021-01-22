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
        require_once("inc/open_db.php");
        include("inc/functions.php");

        $inventory = get_inventory($db);
        if (isset($_SESSION['user'])) {
            

            if (isset($_POST['submit'])){
                if (!add_to_cart($db, $_POST['itemNum'], $_SESSION['user'])) {
                    $unavailable = $_POST['itemNum'];
                }
            }
        }
        
    ?>
    <body>
        <header>
            <h1>Ducks</h1>
        </header>
        <main>
            <?php
            if (isset($_SESSION['user'])) {
                echo '<a href="login_files/logout.php">logout</a>';
            } else {
                echo '<a href="login_files/login_start.php">sign in/create account</a>';
            }
            ?>
            <a href="shoppingCart.php"><img alt="shopping cart" src="images/cart.png"/></a>
            <div>
                <?php
                    foreach($inventory as $product) {
                        $msg = !isset($_SESSION['user']) ? 'disabled title="please login before purchasing items"' : '';
                        echo '<section>';
                        echo '<img src="images/'.$product['itemNumber'].'.jpg" alt="image of '.$product['description'].'"/>';
                        echo '<p>'.$product['description'].'</p>';
                        echo '<p>Currently Available: '.$product['quantity'].'</p>';
                        echo '<p>$'.number_format($product['price'], 2).'</p>';
                        echo '<form method="post" action="index.php">';
                        echo '<input type="hidden" name="itemNum" value="'.$product['itemNumber'].'">';
                        echo '<input type="hidden" name="description" value="'.$product['description'].'">';
                        echo '<input type="hidden" name="price" value="'.$product['price'].'">';
                        echo '<input '.$msg.' type="submit" value="add to cart" name="submit">';
                        if (isset($unavailable) && $unavailable == $product['itemNumber']) {
                            echo '<p>quantity unavailable</p>';
                        }
                        
                        echo '</form>';
                        echo '</section>';
                    }
                ?>
            </div>
        </main>
        <?php include('inc/footer.php') ?>
        
    </body>
</html>
