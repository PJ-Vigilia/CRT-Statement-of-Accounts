<?php
  // Include config file
  require_once "config.php";

  $student_id = $course = $yearlevel = $fname = $lname = $address = $username = $password = $confirm_password ="";
  $sid_err = $course_err = $yearlevel_err = $fname_err = $lname_err = $address_err = $username_err = $password_err = $confirm_password_err ="";

  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Student ID
    $input_id = trim($_POST["student_id"]);
    if(empty($input_id)){
        $sid_err = "Please enter the student id.";     
    } elseif(!ctype_digit($input_id)){
        $sid_err = "Please enter valid student id.";
    } else{
        $student_id = $input_id;
    }

    // Validate course
    $input_course = trim($_POST["course"]);
    if(empty($input_course)){
        $course_err = "Please enter course.";
    } elseif(!filter_var($input_course, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $course_err = "Please enter a valid character.";
    } else{
        $course = $input_course;
    }

    // Validate yearlvl
    $input_yearlevel = trim($_POST["year_level"]);
    if(empty($input_yearlevel)){
        $yearlevel_err = "Please enter course.";
    } else{
         $yearlevel = $input_yearlevel;
    }
    // Validate fname
    $input_fname = trim($_POST["fname"]);
    if(empty($input_fname)){
        $fname_err = "Please enter first name.";
    }elseif(!filter_var($input_fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
          $fname_err = "Please enter a valid character.";
    } else{
       $fname = $input_fname;
    }

    // Validate lname
    $input_lname = trim($_POST["lname"]);
    if(empty($input_lname)){
        $lname_err = "Please enter first name.";
      } elseif(!filter_var($input_lname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
          $lname_err = "Please enter a valid character.";
      } else{
        $lname = $input_lname;
    }
    
    // Validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate username
    $input_username = trim($_POST["username"]);
    if(empty($input_address)){
        $address_username = "Please enter username.";     
    } else{
        $username = $input_username;
    }

    //bday
    $bday = $_POST["bday"];
    // Validate password
    $input_password = trim($_POST["password"]);
    if(empty($input_password)){
        $address_password = "Please enter password.";     
    } else{
        $password = $input_password;
    }
    // Validate password
    $input_confirmPassword = trim($_POST["confirm_password"]);
    
    // attach picture
    $picture = $_FILES["image"]["name"];

    if($input_password != $input_confirmPassword){
      $password_err = "Confirm password doesn't match.";
    }
    else{
      $sqlUsername = "SELECT * FROM users WHERE username = '$username'";
      if($resultUsername = $pdo->query($sqlUsername)){
        if($resultUsername->rowCount() > 0){
          $username_err = "Username " . $username ." already exist.";
        }
        else{
          if(empty($sid_err) && empty($fname_err) && empty($lname_err) && empty($address_err) && empty($course_err) && empty($yearlevel_err) && empty($username_err) && empty($password_err)){
        
            $sql = "INSERT INTO users (fname, lname, bday, address, username, password, picture, roles) VALUES (:fname, :lname, :bday, :address, :username, :password, :picture, 'student')";

            if($stmtUser = $pdo->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmtUser->bindParam(":fname", $param_fname);
              $stmtUser->bindParam(":lname", $param_lname);
              $stmtUser->bindParam(":bday", $param_bday);
              $stmtUser->bindParam(":address", $param_address);
              $stmtUser->bindParam(":username", $param_username);
              $stmtUser->bindParam(":password", $param_password);
              $stmtUser->bindParam(":picture", $param_picture);
              
              // Set parameters
              $param_fname = $fname;
              $param_lname = $lname;
              $param_bday = $bday;
              $param_address = $address;
              $param_username = $username;
              $param_password = $password;
              $param_picture = $picture;

              // Attempt to execute the prepared statement
              if($stmtUser->execute()){
                 //Getting user id
                  $sqlID = "SELECT * FROM users WHERE username ='$username'";
                  if($resultID = $pdo->query($sqlID)){
                    if($resultID->rowCount() > 0){
                      while($rowID = $resultID->fetch()){
                        $userID = $rowID['user_id'];
                        //insert student
                          $sqlStudent = "INSERT INTO student(student_id, user_id, course, year_level) VALUES (:student_id, :userID, :course, :yearlevel)";

                          if($stmtStudent = $pdo->prepare($sqlStudent)){
                            // Bind variables to the prepared statement as parameters
                            $stmtStudent->bindParam(":student_id", $param_sid);
                            $stmtStudent->bindParam(":userID", $param_uid);
                            $stmtStudent->bindParam(":course", $param_course);
                            $stmtStudent->bindParam(":yearlevel", $param_yearlevel);

                            // Set parameters
                            $param_sid = $student_id;
                            $param_uid = $userID;
                            $param_course = $course;
                            $param_yearlevel = $yearlevel;

                            // Attempt to execute the prepared statement
                            if($stmtStudent->execute()){
                              // Records created successfully. Redirect to landing page
                              session_start();
                              $_SESSION["user_id"] = $rowID['user_id'];
                              session_write_close();
                              $url = "student/index.php";
                              header("Location: $url");
                              exit();
                            }
                          }
                      }
                    }
                  }
              } else{
                  echo "Something went wrong. Please try again later.";
              }//end Attempt to execute the prepared statement
            }//end of statement user 
          }
        }
      }
      else{
        echo "Something went wrong.";
      }

      
    }
    // Close statement
        unset($stmt);
    // Close connection
    unset($pdo);
  }//end of Processing form data when form is submitted
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>

   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

   <link href="assets/css/phppot-style.css" type="text/css"
  rel="stylesheet" />
    <link href="assets/css/user-registration.css" type="text/css"
      rel="stylesheet" />
    <script src="vendor/jquery/jquery-3.3.1.js" type="text/javascript"></script>
</head>
<body>
<!--Navivation bar-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="login.php">STATE OF ACCOUNT</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
  </nav>
  <!--end of Navivation bar-->
<div class="container" style="padding-top:2%">
      
        <div class="row">
          <div class="col col-7">
            <!--form-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
              <div class="form-group row">
                <label for="student_id" class="col-sm-4 col-form-label">Student ID</label>
                <div class="col-sm-8">
                  <input type="text" name="student_id" class="form-control" id="student_id" required>
                  <label style="color:red"><?php echo $sid_err; ?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="fname" class="col-sm-4 col-form-label">First Name</label>
                <div class="col-sm-8">
                  <input type="text" name="fname" class="form-control" id="fname" required>
                  <label style="color:red"><?php echo $fname_err; ?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="lname" class="col-sm-4 col-form-label">Last Name</label>
                <div class="col-sm-8">
                  <input type="text" name="lname" class="form-control" id="lname" required>
                  <label style="color:red"><?php echo $lname_err; ?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="bday" class="col-sm-4 col-form-label">Birthdate</label>
                <div class="col-sm-8">
                  <input type="date" name="bday" class="form-control" id="bday" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="address" class="col-sm-4 col-form-label">Address</label>
                <div class="col-sm-8">
                  <input type="text" name="address" class="form-control" id="address" required>
                  <label style="color:red"><?php echo $address_err; ?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="course" class="col-sm-4 col-form-label">Course</label>
                <div class="col-sm-8">
                  <select class="form-select form-control" name="course" aria-label="Default select example" id="course" onclick="YearBaseOnCourse()">
                    <option selected value="ACT">ACT</option>2yrs
                    <option value="BSAT">BSAT</option>4
                    <option value="BSAIS">BSAIS</option>
                    <option value="BSIT">BSIT</option>4
                    <option value="BTVTE">BTVTE</option>4
                    <option value="BSHM">BSHM</option>
                  </select>
                  <label style="color:red"><?php echo $course_err; ?></label>
                  <script type="text/javascript">
                    function YearBaseOnCourse(){
                      var course = document.getElementById("course").value;
                      var yr1 = document.getElementById("1st-yr");
                      var yr2 = document.getElementById("2nd-yr");
                      var yr3 = document.getElementById("3rd-yr");
                      var yr4 = document.getElementById("4th-yr");
                      if(course == "ACT" || course == "BSAIS" || course == "BSHM"){
                        yr3.style.display = "none";
                        yr4.style.display = "none";
                      }
                      else{
                        yr1.style.display = "block";
                        yr2.style.display = "block";
                        yr3.style.display = "block";
                        yr4.style.display = "block";
                      }
                    }
                  </script>
                </div>
              </div>
              <div class="form-group row">
                <label for="year_level" class="col-sm-4 col-form-label" required>Year Level</label>
                <div class="col-sm-8">
                  <select class="form-select form-control" name="year_level" aria-label="Default select example" id="year_level">
                    <option selected value="1st year" id="1st-yr">1st year</option>
                    <option value="2nd year" id="2nd-yr">2nd year</option>
                    <option value="3rd year" id="3rd-yr" style="display: none">3rd year</option>
                    <option value="4th year" id="4th-yr" style="display: none">4th year</option>
                  </select>
                  <label style="color:red"><?php echo $yearlevel_err; ?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                  <input type="text" name="username" class="form-control" id="username" required>
                  <label style="color:red"><?php echo $username_err; ?></label>
                </div>
              </div>
              <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                  <input type="password" name="password" class="form-control" id="password" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="confirm_password" class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col-sm-8">
                  <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                  <label style="color:red"><?php echo $password_err; ?></label>
                </div>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
          </div>

          <div class="col col-5">
            <div class="form-row d-flex justify-content-center" style="padding-top: 30%">
                <div style="text-align: center;">
                            
                  <p><input type="file"  accept="image/*" name="image" id="file"  onchange="loadFile(event)" style="display: none;"></p>
                  
                  <p><img id="output" width="200" src="image/Account logo.png"></p>
                  <p><label  class="btn btn-secondary" for="file" style="cursor: pointer;">Attach Photo</label></p>
                  <script>
                  var loadFile = function(event) {
                    var image = document.getElementById('output');
                    image.src = URL.createObjectURL(event.target.files[0]);
                  };
                  </script>
                </div>
            </div>
          </div>



        </div>
      </form>
      <!--end form-->
</div>
<script type="text/javascript">
  
</script>
</body>
</html>
