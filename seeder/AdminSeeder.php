<?php
      $sql = "INSERT INTO users (user_no, fname, lname, address, username, password, roles) VALUES ('1', 'admin', 'admin', 'Guimba', 'admin','admin', 'admin')";
 
    if($stmt = $pdo->prepare($sql)){
          // Attempt to execute the prepared statement
          if($stmt->execute()){
             // Records created successfully. Redirect to landing page
             echo "Admin Seeded";
        } else{
               echo "Something went wrong. Please try again later.";
         }
    } 
    // Close statement
    unset($stmt);
    
?>