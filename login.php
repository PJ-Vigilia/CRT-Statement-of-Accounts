<?php
  include("config.php");

  $input_usn = $input_pwd = "";
  $login_err = "";

  if(isset($_POST['submit'])){
    $input_usn = trim($_POST['username']);
    $input_pwd = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$input_usn' AND password = '$input_pwd'";
    if($result = $pdo->query($sql)){
      if($result->rowCount() > 0){
        while($row = $result->fetch()){
          // Records created successfully. Redirect to landing page
          if($row['roles'] == "student"){
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            session_write_close();
            $url = "student/index.php";
            header("Location: $url");
            exit();  
          }
          else{
            session_start();
            $_SESSION['user_id'] = $row['user_id'];
            session_write_close();
            $url = "admin/index.php";
            header("Location: $url");
            exit();
          }
          
        }
        unset($result);
      }
      else{
        $login_err = "Worng Username or Password!";
      }
    }
    else{
      echo "Something went wrong";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="fluid-container bg-dark" style="height: 300px"><h1 class="text-lg-center" style="padding-top: 10%;color: white">STATEMENT OF ACCOUNT</h1></div>
<div class="container d-flex justify-content-center" style="position: relative;top: -100px">
  <div class="card" style="width: 48rem;">
    <div class="card-body">
      <!--form-->
      <form name="login" action="login.php" method="POST">
      <div class="form-group">
        <label for="exampleInputEmail1">Username</label>
        <input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Username" required>
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
      </div>
      <button type="submit" name="submit" class="btn btn-primary">Login</button>
      <a href="signup.php" class="btn btn-link">Signup</a>
    </form>
    <?php echo "$login_err" ?>
      <!--end form-->
    </div>
  </div>
  
</div>

</body>
</html>
