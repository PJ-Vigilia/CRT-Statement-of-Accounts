<?php

      $sql = "INSERT INTO roles (role_id, role_name) VALUES ('1' ,'admin'), ('2','student')";
   
      if($stmt = $pdo->prepare($sql)){
            // Attempt to execute the prepared statement
            if($stmt->execute()){
               // Records created successfully. Redirect to landing page
               echo "Role Seeded";
          } else{
                 echo "Something went wrong. Please try again later.";
           }
      } 
      // Close statement
      unset($stmt);

       
    
    
?>