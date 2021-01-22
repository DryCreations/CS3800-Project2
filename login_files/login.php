<!DOCTYPE html>
<html>
  <head>    
    <title>Log In - password hash</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
  </head>   
    
  <?php
        
    require_once("../inc/open_db.php");  
    include ("login_functions.php");
    session_start();
    
    //need to save whether log in is new or existing
    if (isset($_POST['type'])) {  
      $_SESSION['type'] = $_POST['type'];
      unset($_POST['type']);
    }
    
    //store username and other info
    if (isset($_POST['username'])) {
      $username = htmlspecialchars($_POST['username']);
    }
    else {
      $username = "";
    }
    if (isset($_POST['fname'])) {
      $fname = htmlspecialchars($_POST['fname']);
    }
    else {
      $fname = "";
    }
    if (isset($_POST['lname'])) {
      $lname = htmlspecialchars($_POST['lname']);
    }
    else {
      $lname = "";
    }
    if (isset($_POST['address'])) {
      $address = htmlspecialchars($_POST['address']);
    }
    else {
      $address = "";
    }
    if (isset($_POST['city'])) {
      $city = htmlspecialchars($_POST['city']);
    }
    else {
      $city = "";
    }
    if (isset($_POST['state'])) {
      $state = htmlspecialchars($_POST['state']);
    }
    else {
      $state = "";
    }
    if (isset($_POST['zip'])) {
      $zip = htmlspecialchars($_POST['zip']);
    }
    else {
      $zip = "";
    }
    
     
    
    //check username availability
    if (isset($_POST['check_username']) && isset($_POST['username'])) {
      $check = true;  //will need to know if username needs to be put back
      if (existing_username($db, $username)) {
        echo "<script type='text/javascript'>alert('Username unavailable.');</script>";
      } 
      else {
        echo "<script type='text/javascript'>alert('Username is available.');</script>";
      }
      unset($_POST['check_username']);
      unset($_POST['username']);
    }
    else {
      $check = false; //no name in the input box
    }
    
    //log in existing user
    if ($_SESSION['type'] == 'existing') {
      if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        if (verify_login($db, $username, $password)) {
          $_SESSION['login'] = 'accept_existing';
          $_SESSION['user'] = $username;
          header('Location: login_message.php');
        }
        else {
          $_SESSION['login'] = 'deny';
          header('Location: login_message.php');
        }
      }
    }
    else {  //create new user
     if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);  
               
        if (validPassword($password)) {
          $password2 = htmlspecialchars($_POST['password2']);
          if ($password !== $password2) {
            echo "<script type='text/javascript'>alert('Passwords do not match.');</script>";
          }
          else  //passwords match
          {
            if (existing_username($db, $username)) {
              echo "<script type='text/javascript'>alert('username unavailable');</script>";
            }
            else {  //username available
              $encrypt_password = password_hash($password, PASSWORD_DEFAULT);
              if (addUser($db, $username, $encrypt_password, $fname, $lname, $address, $city, $state, $zip)){
                $_SESSION['login'] = 'accept_new';
                $_SESSION['user'] = $username;
                header('Location: login_message.php');
              }
            else {
              echo "<script type='text/javascript'>alert('Unable to create account.');</script>";
            }
          }//!existing_username
        }//passwords match
      }//valid password
      else {    //invalid password
        echo "<script type='text/javascript'>alert('Password must be at least 8 characters and "
        . "contain at least one number, one uppercase letter, and one lowercase letter');</script>";
      }      
    }//isset
  }//else (new user)
?>
  <body>      
    <header>
        <?php 
          if ($_SESSION['type'] == "existing"){
            echo "<h1>User Log-In</h1>";
          }
          else {
            echo "<h1>Enter new account information</h1>";
          }
        ?>
        
    </header>
    <main>              
        <form action="" method="post">
          <label for="username" class="login_label">Username</label>
          <?php
              echo "<input type='text' name='username' value=$username>"; 
              if ($_SESSION['type'] == "new") {
                echo '<input type="submit" name="check_username" value="Check Username Availability" id="check_button">';
              }
              echo '<br/>';
          ?>          
          <label for="password" class="login_label">Password</label>
          <input type="password" name="password" value=""><br />
          <?php
             if ($_SESSION['type'] == "new"){
                echo "<label for='password2' class='login_label'>Retype password</label>";
                echo "<input type='password' name='password2' value=''><br /><br />";
                
                echo "<label for='fname' class='login_label'>First Name</label>";
                echo "<input type='text' name='fname' value='$fname' required><br />";
                echo "<label for='lname' class='login_label'>Last Name</label>";
                echo "<input type='text' name='lname' value='$lname' required><br />";
                echo "<label for='address' class='login_label'>Street Address</label>";
                echo "<input type='text' name='address' value='$address' required ><br />";
                echo "<label for='city' class='login_label'>City</label>";
                echo "<input type='text' name='city' value='$city' required><br />";
                echo "<label for='state' class='login_label'>State</label>";
                echo "<input type='text' name='state' value='$state' required><br />";
                echo "<label for='zip' class='login_label'>Zipcode</label>";
                echo "<input type='text' name='zip' value='$zip' required><br />";
                $submit_value = 'Create Account';
             }
             else {
               $submit_value = 'Log In';
             }   
            echo "<input type='submit' value='$submit_value'>";
            ?>
        </form>        
    </main>  
  </body>
</html>


