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
          <a class="nav-link" href="index.php">Profile</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#">Inbox<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Log out</a>
        </li>
      </ul>
      
    </div>
  </nav>
  <!--end of Navivation bar-->

  <div class="container" style="padding-top: 5%">
    <?php
        $sql = "SELECT * FROM term_account WHERE reciever='$user_id' ORDER BY date_sent, time_sent DESC";
        if($result = $pdo->query($sql)){
          if($result->rowCount() > 0){
            while($row = $result->fetch()){
              $sender_id = $row['sender'];
              $sqlSender = $pdo->prepare("SELECT * FROM users WHERE user_id ='$sender_id'");
              $sqlSender->execute();
              $sender = $sqlSender->fetch();
              $sender_name = $sender['fname'] . " " . $sender['lname'];
    ?>
    <div class="card">
      <div class="card-body">
      <strong><?php echo $sender_name;?></strong> | 
      <small style="padding-left: 1%"><?php echo $row['date_sent'];?></small><br>
        <div  style="padding-left: 1%">STATEMENT OF ACCOUNT | <b><?php echo $row['term'];?></b>  <small><?php echo $row['date'];?></small></div>
        
        <div  class="d-flex justify-content-end">
          <a href="statement of accounts.php?term_id=<?php echo $row['term_id'];?>" class="btn btn-primary btn-sm">View</a>
        </div>
      </div>
        
    </div>
    <?php
            }
          }
        }
      ?>
    

  </div>

</body>
</html>

