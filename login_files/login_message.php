<?php
   session_start();   
   $login_status = $_SESSION['login'];
?>

<!DOCTYPE html>
<html>
  <head>    
    <title>Login Success</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
  </head>  
  
  
  <body>
    <main> 
        <?php
          if ($login_status == 'accept_existing') {
            echo "<h1>Login successful!</h1>";
          }
           if ($login_status == 'accept_new') {
            echo "<h1>Your account has been created.</h1>";
          }
          else if ($login_status == 'deny'){
            echo "<h1>Login failed.</h1>";            
          }
          echo "You will be redirected to the home page in 2 seconds.";
          header( "refresh:2; url=../index.php" );
        ?>
    </main>        
   
  </body>
</html>


