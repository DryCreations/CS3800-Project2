<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Shipping Information</title>
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

        if (isset($_POST['update'])){
            update_user($db, $_SESSION['user'], $_POST['fname'], $_POST['lname'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip']);
            $msg = 'information has been updated';
        }
        
        $user = get_user($db, $_SESSION['user']);
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
               
                echo '<form method="post" action="confirmation.php">';
                echo '<h2>Shipping Information</h2>';
               
                echo '<label for="fname" >First Name</label>';
                echo '<input type="text" name="fname" id="fname" required value="'.$user['firstName'].'"/><br/>';
                echo '<label for="lname" >Last Name</label>';
                echo '<input type="text" name="lname" id="lname" required value="'.$user['lastName'].'"/><br/>';
                echo '<label for="address" >Street Address</label>';
                echo '<input type="text" name="address" id="address" required value="'.$user['streetAddress'].'"/><br/>';
                echo '<label for="city" >City</label>';
                echo '<input type="text" name="city" id="city" required value="'.$user['city'].'"/><br/>';
                echo '<label for="state" >State</label>';
                
                include 'inc/states.php';
                
                echo '<br/>';
                echo '<label for="zip" >Zip Code</label>';
                echo '<input type="text" name="zip" id="zip" required value="'.$user['zip'].'" /><br/><br/>';
                
                echo '<input type="submit" name="submit" value="submit"/><br>';
                echo '<input formaction="shipping.php" type="submit" name="update" value="update information"/>';
                if (isset($msg)) {
                    echo '<p>information has been updated</p>';
                }
                echo '</form>';
                echo '<form action="shoppingCart.php">';
                echo '<input type="submit" value="Return to cart" />';
                echo '</form>';
                
                
            }
            ?>
            
        </main>
        <?php include('inc/footer.php') ?>
    </body>
</html>
