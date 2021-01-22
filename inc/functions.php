<?php

function get_inventory($db) {
    $query = "SELECT * FROM inventory";
    
    $statement = $db->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $result;   
}

function check_cart($db, $user) {
    $query1 = "SELECT c.quantity as cartQuantity, inventory.quantity as inventoryQuantity, inventory.itemNumber, inventory.description FROM (SELECT * FROM cart WHERE cart.username=:user) c LEFT JOIN inventory on inventory.itemNumber = c.itemNumber";
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':user', $user);
    $statement1->execute();
    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
    $statement1->closeCursor();
    
    $msg = '';
    
    foreach ($result1 as $item) {
        if ($item['inventoryQuantity'] <= 0) {
            $query1 = "DELETE FROM cart WHERE cart.itemNumber=:itemId AND cart.username=:user";
            $statement1 = $db->prepare($query1);
            $statement1->bindValue(':itemId', $item['itemNumber']);
            $statement1->bindValue(':user', $user);
            $statement1->execute();
            $statement1->closeCursor();
            
            $msg .= '<br>'.$item['cartQuantity'].' '.$item['description'].' were removed from your cart';
            
        } else if ($item['cartQuantity'] > $item['inventoryQuantity']) {
            $query1 = "UPDATE cart SET quantity=:quantity WHERE cart.itemNumber=:itemId AND cart.username=:user";
            $statement1 = $db->prepare($query1);
            $statement1->bindValue(':itemId', $item['itemNumber']);
            $statement1->bindValue(':user', $user);
            $statement1->bindValue(':quantity', $item['inventoryQuantity']);
            $statement1->execute();
            $statement1->closeCursor();
            
            $msg .= '<br>'.($item['cartQuantity'] - $item['inventoryQuantity']).' '.$item['description'].' were removed from your cart';
        }
    }
    
    if (strlen($msg) > 0) {
        return 'due to changes in inventory, some changes were made to your cart:'.$msg;
    } 
}

function get_cart($db, $user) {
    $query = "SELECT c.itemNumber, customers.firstName, customers.lastName, inventory.description, inventory.price, c.quantity, inventory.quantity AS maxQuantity FROM (SELECT * FROM cart WHERE cart.username=:user ) c INNER JOIN inventory ON c.itemNumber = inventory.itemNumber INNER JOIN customers ON customers.username = c.username";
    $statement = $db->prepare($query);
    $statement->bindValue(':user', $user);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $result;
}

function add_to_cart($db, $itemId, $user, $quantity=1) {
    $query1 = "SELECT c.quantity as cartQuantity, inventory.quantity as inventoryQuantity FROM inventory LEFT JOIN (SELECT * FROM cart WHERE cart.username=:user) c on inventory.itemNumber = c.itemNumber WHERE inventory.itemNumber = :itemId";
    $statement1 = $db->prepare($query1);
    $statement1->bindValue(':itemId', $itemId);
    $statement1->bindValue(':user', $user);
    $statement1->execute();
    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
    $statement1->closeCursor();
    
    if ($result1[0]['cartQuantity'] + $quantity <= 0) {
        $query2 = "DELETE FROM cart WHERE cart.itemNumber=:itemId AND cart.username=:user";
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':itemId', $itemId);
        $statement2->bindValue(':user', $user);
        $statement2->execute();
        $statement2->closeCursor();
        return true;
    } else if ($result1[0]['cartQuantity'] + $quantity <= $result1[0]['inventoryQuantity']) {
        $query2 = "INSERT INTO cart (itemNumber, quantity, username) VALUES (:itemId, 1, :user) ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
        $statement2 = $db->prepare($query2);
        $statement2->bindValue(':itemId', $itemId);
        $statement2->bindValue(':quantity', $quantity);
        $statement2->bindValue(':user', $user);
        $statement2->execute();
        $statement2->closeCursor();
        return true;
    }

    
    return false;
    
}

function update_inventory($db, $cart) {
    foreach($cart as $item) {
        $query = "UPDATE inventory SET inventory.quantity = inventory.quantity - :quantity WHERE inventory.itemNumber = :itemId";
        $statement = $db->prepare($query);
        $statement->bindValue(':itemId', $item['itemNumber']);
        $statement->bindValue(':quantity', $item['quantity']);
        $statement->execute();
        $statement->closeCursor();
    }
}

function clear_cart($db, $user) {
    $query = "DELETE FROM cart WHERE username=:user";
    $statement = $db->prepare($query);
    $statement->bindValue(':user', $user);
    $statement->execute();
    $statement->closeCursor();
}

function get_user($db, $user) {
     $query = "SELECT * FROM customers WHERE username=:user";
     
    $statement = $db->prepare($query);
    $statement->bindValue(':user', $user);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $result;
}

function update_user($db, $user, $firstName, $lastName, $streetAddress, $city, $state, $zip) {
    $query = "UPDATE customers SET firstName=:fname, lastName=:lname, streetAddress=:address, city=:city, userState=:state, zip=:zip WHERE username=:user";
     
    $statement = $db->prepare($query);
    $statement->bindValue(':user', $user);
     $statement->bindValue(':fname', $firstName);
     $statement->bindValue(':lname', $lastName);
     $statement->bindValue(':address', $streetAddress);
     $statement->bindValue(':city', $city);
     $statement->bindValue(':state', $state);
     $statement->bindValue(':zip', $zip);
     
    $statement->execute();
    $statement->closeCursor();
    
}
?>