<?php
  session_start();
  require_once "../config.php";

  if(empty($_SESSION['user_id'])){
    header("location: ../login.php");
  }
  $user_id = $_SESSION['user_id'];

  $stmtStudent = $pdo->prepare('SELECT * FROM users JOIN student USING(user_id) WHERE user_id=:user_id');
  $stmtStudent->execute(['user_id' => $user_id]);
  $student = $stmtStudent->fetch();

  if($student['roles'] !="student"){
    header("location:../admin/warning page.php");
  }

  $stmt = $pdo->prepare('SELECT password FROM users WHERE user_id=:user_id');
  $stmt->execute(['user_id' => $user_id]);
  $user_password = $stmt->fetch();

  $password = $user_password['password'];
  $oldPassword = $newPassword = $confirmPassword = "";
  $password_err ="";

  if(isset($_POST["submit"])){
    $input_oldPassword = trim($_POST['oldPassword']);
    if(empty($input_oldPassword)){
        $password_err = "Please enter old password.";     
    }
    else{
      $oldPassword = $input_oldPassword;
    }

    $input_newPassword = trim($_POST['newPassword']);
    if(empty($input_newPassword)){
        $password_err = "Please enter the new password.";     
    }
    else{
      $newPassword = $input_newPassword;
    }

    $input_confirmPassword = trim($_POST['confirmPassword']);
    if(empty($input_confirmPassword)){
        $password_err = "Please enter confirm password.";     
    }
    else{
      $confirmPassword = $input_confirmPassword;
    }

    if($newPassword == $confirmPassword){
      if($password == $oldPassword){
        $sqlPassword = "UPDATE users SET password =:password WHERE user_id =:user_id";
        if($stmtPassword=$pdo->prepare($sqlPassword)){
          $stmtPassword->bindParam(":password", $param_password);
          $stmtPassword->bindParam(":user_id", $param_user_id);

          $param_password = $newPassword;
          $param_user_id = $user_id;

          // Attempt to execute the prepared statement
            if($stmtPassword->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again.";
            }
        }
      }
      else{
        $password_err ="Old password doesn't match!";
      }

    }
    else{
      $password_err ="Confirm password doesn't match!";
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
  <!--Navivation bar-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">SOA</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Profile<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="inbox.php">Inbox</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Log out</a>
        </li>
      </ul>
    </div>
  </nav>
  <!--end of Navivation bar-->

  <div class="container" style="padding-top:5%">
    <div class="card mb-3" style="padding-top:3%">
      <div class="container">
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST" >
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Old Password</label>
            <div class="col-sm-10">
              <input type="password" name="oldPassword" class="form-control" id="inputPassword" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
              <input type="password" name="newPassword" class="form-control" id="inputPassword" placeholder="Password" required>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-10">
                <input type="password" name="confirmPassword" class="form-control" id="inputPassword" placeholder="Password" required>
            </div>
          </div>
          
            <div class="container d-flex justify-content-center">
              <label style="color: red"><?php echo $password_err; ?></label>
            </div>
          <div class="container d-flex justify-content-end" style="padding-bottom: 2%">  
           <button type="submit" name="submit" class="btn btn-primary">Submit</button>&nbsp;
           <button type="button" class="btn btn-secondary">Clear</button>
         </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
