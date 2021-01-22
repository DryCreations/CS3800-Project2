
  <?php 
    
    function verify_login($db, $username, $password)
    {
      $query = "SELECT password FROM customers WHERE username = :user";
      $statement = $db->prepare($query);
      $statement->bindValue(':user', $username);
      $statement->execute();
      $result = $statement->fetch();
      $statement->closeCursor();
      $hash = $result['password'];
      return password_verify($password, $hash);
    }
    
    function existing_username($db, $username)
    {
      $query = "SELECT COUNT(username) FROM customers WHERE username = :username";
      $statement = $db->prepare($query);
      $statement->bindValue(':username', $username);
      $statement->execute();
      $exists = $statement->fetch();
      $statement->closeCursor();
      return $exists[0] == 1;
    }

    function addUser($db, $username, $password, $fname, $lname, $address, $city, $state, $zip) {
      $query = "INSERT INTO customers (username, password, firstName, lastName, streetAddress, city, userState, zip)
                VALUES (:username, :password, :fname, :lname, :address, :city, :state, :zip)";
      $statement = $db->prepare($query);
      $statement->bindValue(':username', $username);
      $statement->bindValue(':password', $password);
      $statement->bindValue(':fname', $fname);
      $statement->bindValue(':lname', $lname);
      $statement->bindValue(':address', $address);
      $statement->bindValue(':city', $city);
       $statement->bindValue(':state', $state);
        $statement->bindValue(':zip', $zip);
      $success = $statement->execute();
      $statement->closeCursor();     
      return $success;
    }
    
     
    function validPassword($password){
      $valid_pattern = '/(?=^.{8,}$)(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*$/';
      if (preg_match($valid_pattern, $password))
        return true;
      else
        return false;
    }

?>
