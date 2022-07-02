<?php
 
    $sql = "INSERT INTO role_user (id, role_id, user_id) VALUES ('1', '1', '1')";
 
    if($stmt = $pdo->prepare($sql)){
          // Attempt to execute the prepared statement
          if($stmt->execute()){
             // Records created successfully. Redirect to landing page
             echo "Role_User Seeded";
        } else{
               echo "Something went wrong. Please try again later.";
         }
    } 
    // Close statement
    unset($stmt);

    
?>