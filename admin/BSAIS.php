<?php
  session_start();
  require_once "../config.php";

  if(empty($_SESSION['user_id'])){
    header("location: ../login.php");
  }
  $user_id = $_SESSION['user_id'];

  $stmtAdmin = $pdo->prepare('SELECT * FROM users WHERE user_id=:user_id');
  $stmtAdmin->execute(['user_id' => $user_id]);
  $adminAdmin = $stmtAdmin->fetch();

  if($adminAdmin['roles'] !="admin"){
    header("location:../student/warning page.php");
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <style type="text/css">
    .my-custom-scrollbar {
    position: relative;
    height: 500px;
    overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
  </style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<!--Navivation bar-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">SOA</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="profile.php">Profile</a>
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
  <div class="container" style="padding-top:2%">
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">ACT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="BSAT.php">BSAT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="BSAIS.php">BSAIS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="BSIT.php">BSIT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="BTVTE.php">BTVTE</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="BSHM.php">BSHM</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <br>

    <div class="table-wrapper-scroll-y my-custom-scrollbar">
     <h5>1st year</h5>
      <table class="table table-bordered table-striped mb-0">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Student ID</th>
            <th scope="col">Name</th>
            <th scope="col">Course</th>
            <th scope="col">Year level</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $no = 0; 
            $role ="student";
            $course = "BSAIS";
            $year_level = "1st year";
            $sql = "SELECT * FROM users JOIN student USING(user_id) WHERE roles='$role' AND course='$course' AND year_level='$year_level'";
            if($result = $pdo->query($sql)){
              if($result->rowCount() > 0){
                while($row = $result->fetch()){
                $no++;
          ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['fname'] . " " . $row['lname']; ?></td>
            <td><?php echo $row['course']; ?></td>
            <td><?php echo $row['year_level']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['password']; ?></td>
            <td>
              <a href="soa.php?reciever_id=<?php echo $row['user_id']; ?>">
                <button type="button" class="btn btn-primary btn-sm">Send Message</button>
              </a>
            </td>
          </tr>
          <?php
                }
              }
            }
          ?>
        </tbody>
      </table>
    </div>

    <div class="table-wrapper-scroll-y my-custom-scrollbar">
     <h5>2nd year</h5>
      <table class="table table-bordered table-striped mb-0">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Student ID</th>
            <th scope="col">Name</th>
            <th scope="col">Course</th>
            <th scope="col">Year level</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $no1 = 0; 
            $role1 ="student";
            $course1 = "BSAIS";
            $year_level1 = "2nd year";
            $sql1 = "SELECT * FROM users JOIN student USING(user_id) WHERE roles='$role1' AND course='$course1' AND year_level='$year_level1'";
            if($result1 = $pdo->query($sql1)){
              if($result1->rowCount() > 0){
                while($row1 = $result1->fetch()){
                $no1++;
          ?>
          <tr>
            <td><?php echo $no1; ?></td>
            <td><?php echo $row1['student_id']; ?></td>
            <td><?php echo $row1['fname'] . " " . $row1['lname']; ?></td>
            <td><?php echo $row1['course']; ?></td>
            <td><?php echo $row1['year_level']; ?></td>
            <td><?php echo $row1['username']; ?></td>
            <td><?php echo $row1['password']; ?></td>
            <td>
              <a href="soa.php?reciever_id=<?php echo $row1['user_id']; ?>">
                <button type="button" class="btn btn-primary btn-sm">Send Message</button>
              </a>
            </td>
          </tr>
          <?php
                }
              }
            }
          ?>
        </tbody>
      </table>
    </div>
        
  </div>



<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
