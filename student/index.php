<?php
  session_start();
  require_once "../config.php";

  if(empty($_SESSION['user_id'])){
    header("location: ../login.php");
  }
  $user_id = $_SESSION['user_id'];

    
  $stmt = $pdo->prepare('SELECT * FROM users JOIN student USING(user_id) WHERE user_id=:user_id');
  $stmt->execute(['user_id' => $user_id]);
  $student = $stmt->fetch();

  if($student['roles'] !="student"){
    header("location:../admin/warning page.php");
  }

  $student_id = $student['student_id'];
  $fname = $student['fname'];
  $lname = $student['lname'];

  $bday = $student['bday'];
  $address = $student['address'];
  $course = $student['course'];
  $year_level = $student['year_level'];
  $picture = $student['picture'];

  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    // attach picture
    $picture = $_FILES["image"]["name"];

    $sql = "UPDATE users SET picture=:picture WHERE user_id=:user_id";
    if($stmt = $pdo->prepare($sql)){
      $stmt->bindParam(":picture", $param_picture);
      $stmt->bindParam(":user_id", $param_userID);

      $param_picture = $picture;
      $param_userID = $user_id;

      // Attempt to execute the prepared statement
      if($stmt->execute()){
        // Records updated successfully. Redirect to landing page
        header("location: index.php");
      } else{
           echo "Something went wrong. Please try again later.";
      }
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">SOA</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Profile <span class="sr-only">(current)</span></a>
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

  <div class="container" style="padding-top: 5%">
    <div class="card">
      <div class="card-body">
        <div class="container">
          <div class="row">
            <div class="col">
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <p><input type="file"  accept="image/*" name="image" id="file"  onchange="loadFile(event),saveButton()" style="display: none;"></p>
                  
                  <p><img id="output" width="120px" height="120px" src="../image/<?php echo $picture; ?>" alt="Profile Picture"></p>
                  <p><label  class="btn btn-secondary btn-sm" for="file" style="cursor: pointer;width: 114px">Upload Photo</label></p>
                  <script>
                  var loadFile = function(event) {
                    var image = document.getElementById('output');
                    image.src = URL.createObjectURL(event.target.files[0]);
                  };
                  </script>
                    <button type="submit" class="btn btn-primary btn-sm" id="save" style="margin-top: -40px;margin-left: 34px;display: none">Save</button>
              </form>
            </div>
            <div class="col">
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Student ID</label>
                <div class="col-sm-9">
                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $student_id; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $fname . " " . $lname; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Birthdate</label>
                <div class="col-sm-9">
                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $bday; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-9">
                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $address; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Course</label>
                <div class="col-sm-9">
                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $course; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label">Year Level</label>
                <div class="col-sm-9">
                  <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="<?php echo $year_level; ?>">
                </div>
              </div>
              <div class="form-group row">
                <label for="staticEmail" class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9">
                  <a href="changePassword.php" class="btn btn-primary">Change Password</a>
                </div>
              </div>

            </div>
            <div class="w-100"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
     function saveButton(){
       var save = document.getElementById("save");
       save.style.display = "inline-block";
     }
  </script>
</body>
</html>
