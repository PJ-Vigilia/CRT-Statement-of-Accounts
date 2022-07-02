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
  
  $reciever_id = $_GET['reciever_id'];

  $stmt = $pdo->prepare('SELECT * FROM users JOIN student USING(user_id) WHERE user_id=:user_id');
  $stmt->execute(['user_id' => $reciever_id]);
  $student = $stmt->fetch();

  $name = $student['fname'] . " " . $student['lname'];
  $course = $student['course'];
  $student_id = $student['student_id'];
  $reciever = $student['user_id'];

  $sid_err = $term_err = $date_err = $currentAccount_err = $oldAccount_err = $totalAmount_err = "";


  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate Student ID
    $input_id = trim($_POST["student_id"]);
    if(empty($input_id)){
        $sid_err = "Please enter the student id.";     
    } elseif(!ctype_digit($input_id)){
        $sid_err = "Please enter valid studnt id.";
    } else{
        $student_id = $input_id;
    }

    // Validate term
    $input_term = trim($_POST["term"]);
    if(empty($input_term)){
        $term_err = "Please enter course.";
    } elseif(!filter_var($input_term, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $term_err = "Please select term.";
    } else{
        $term = $input_term;
    }

    // Validate date
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Please enter date.";     
    } else{
        $date = $input_date;
    }

    // Validate Current Account
    $input_currentAccount = trim($_POST["current_account"]);
    if(empty($input_currentAccount)){
        $currentAccount_err = "Please enter current account.";     
    } elseif(!ctype_digit($input_currentAccount)){
        $currentAccount_err = "Please enter valid current account.";
    } else{
        $current_account = $input_currentAccount;
    }

    // Validate Old Account
    $input_oldAccount = trim($_POST["old_account"]);
    if($input_oldAccount == 0){
        $old_account = $input_oldAccount;
    }elseif(empty($input_oldAccount)){
        $oldAccount_err = "Please enter old account.";     
    } elseif(!ctype_digit($input_oldAccount)){
        $oldAccount_err = "Please enter valid old account.";
    } else{
        $old_account = $input_oldAccount;
    }

    // Validate Total Amount
    $input_totalAmount = trim($_POST["total_amount"]);
    if(empty($input_totalAmount)){
        $totalAmount_err = "Please enter total amount.";     
    } elseif(!ctype_digit($input_totalAmount)){
        $totalAmount_err = "Please enter valid total amount.";
    } else{
        $total_amount = $input_totalAmount;
    }

    //current date
    date_default_timezone_set('Asia/Manila');
    $date_sent  = date('Y-m-d');
    $time_sent = date('H:i:s');
    // Prepare an insert statement
    if(empty($sid_err) && empty($term_err) && empty($date_err) && empty($currentAccount_err) && empty($oldAccount_err) && empty($totalAmount_err)){
      
      // Prepare an insert statement
          $sql = "INSERT INTO term_account (sender, reciever, term, date, current_account, old_account, total_amount, date_sent, time_sent) VALUES (:sender, :reciever, :term, :date, :current_account, :old_account, :total_amount, :date_sent, :time_sent)";
          if($stmt = $pdo->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":sender", $param_sender);
                $stmt->bindParam(":reciever", $param_reciever);
                $stmt->bindParam(":term", $param_term);
                $stmt->bindParam(":date", $param_date);
                $stmt->bindParam(":current_account", $param_currentAccount);
                $stmt->bindParam(":old_account", $param_oldAccount);
                $stmt->bindParam(":total_amount", $param_totalAmount);
                $stmt->bindParam(":date_sent", $param_dateSent);
                $stmt->bindParam(":time_sent", $param_timeSent);

                // Set parameters
                $param_sender = $user_id;
                $param_reciever = $reciever_id;
                $param_term = $term;
                $param_date = $date;
                $param_currentAccount = $current_account;
                $param_oldAccount = $old_account;
                $param_totalAmount = $total_amount;
                $param_dateSent = $date_sent;
                $param_timeSent = $time_sent;

                // Attempt to execute the prepared statement
                if($stmt->execute()){
                  header("location:index.php");
                } else{
                    echo "Something went wrong. Please try again later.";
                }//end Attempt to execute the prepared statement

          }

    }// Prepare an insert statement end
  }//Prepare an insert statement end
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>

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
        <li class="nav-item active">
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
  <div class="container">
  <div class="row">
    <div class="col-sm">
      <!--form-->
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?reciever_id=<?php echo $reciever_id; ?>" method="post">
   <div class="form-group">
    <div class="form-group">
    <label for="date">Student ID: </label> <label style="color: red"><?php echo $sid_err; ?></label>
    <input type="text" name="student_id" class="form-control" id="student_id" value="<?php echo $student_id; ?>" readonly>
  </div>
    <label for="date">Name:</label>
    <input type="text" class="form-control" id="name" value="<?php echo $name; ?>" readonly>
  </div>
  <div class="form-group">
    <label for="date">Course:</label>
    <input type="text" class="form-control" id="course" value="<?php echo $course; ?>" readonly>
  </div>   
  <div class="form-group">
    <label for="term">Term:</label> <label style="color: red"><?php echo $term_err; ?></label>
    <select name="term" class="custom-select" id="term" onchange="Term()" required>
      <option value="Prelim">Prelim</option>
      <option value="Midterm">Midterm</option>
      <option value="Finals">Finals</option>
    </select>
  </div>  
   <div class="form-group">
    <label for="date">Date:</label> <label style="color: red"><?php echo $date_err; ?></label>
    <input type="date" name="date" class="form-control" id="date" required>
  </div>
  <div class="form-group">
    <label for="currentAccount">Current Account:</label> <label style="color: red"><?php echo $currentAccount_err; ?></label>
    <input type="text" name="current_account" class="form-control" id="currentAccount" required>
  </div>
  <div class="form-group">
    <label for="oldAccount">Old Account:</label> <label style="color: red"><?php echo $oldAccount_err; ?></label>
    <input type="text" name="old_account" class="form-control" id="oldAccount"  required>
  </div>
  <div class="form-group">
    <label for="totalAmount">Total Amount:</label> <label style="color: red"><?php echo $totalAmount_err; ?></label>
    <input type="text" name="total_amount" class="form-control" id="totalAmount" onchange="total()" required>
  </div>
  
  
    </div>
    <div class="col-sm">
      <div class="card" style="width: 100%;">
        <div class="card-body">
          Mr/Mrs.: <b><span id="msgName"></span></b><br>
          Course: <span id="msgCourse">ACT</span><br><br>
          Good day!<br><br>
          This is from CRT Accounting Cabanatuan.<br><br>
          <b><span id="msgTerm1">PRELIM</span> EXAMINATION</b> will be on <span id="msgDate"></span>. Please settle your account on or before the date of examination.<br><br>
          Your Statement of Account is <b><span id="msgTerm">Prelim</span> </b>Php <b><span id="msgCurrentAmount"></span></b><br>
          <b>Old Account:</b> Php <span id="msgOldAccount"></span><br>
          <b>Total Amount:</b> Php <span id="msgTotalAmount"></span>.<br><br>
          You can pay your Account on CRT Campus or Bank Payment.<br><br>
          <b>China Bank Savings</b><br><br>
          <table>
            <tr>
              <td>Account Name: </td>
              <td style="padding-left: 10px">College for Research and Technology</td>
            </tr>
            <tr>
              <td>Account Number: </td>
              <td style="padding-left: 10px">618302004327</td>
            </tr>
          </table><br>
          <b>Metropolitan Bank and Trust Co. (Metrobank)</b>
          <table>
            <tr>
              <td>Account Name: </td>
              <td style="padding-left: 10px">CRT</td>
            </tr>
            <tr>
              <td>Account Number: </td>
              <td style="padding-left: 10px">1197007059279</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="d-flex justify-content-center" style="padding-top: 2%">
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
    </form>
   <!--end form-->
  </div>
</div>
  
  
</div>
<script type="text/javascript">
  var name = document.getElementById("name").value;
  document.getElementById("msgName").innerHTML=name;

  var course = document.getElementById("course").value;
  document.getElementById("msgCourse").innerHTML=course;
  $("#currentAccount").keyup(function () {
      var currentAccount = String($(this).val());
      document.getElementById("msgCurrentAmount").innerHTML=currentAccount;
      total();
      
  });
  $("#date").keyup(function () {
      var date = String($(this).val());
      document.getElementById("msgDate").innerHTML=date;
  });
  $("#oldAccount").keyup(function () {
      var oldAccount = String($(this).val());
      document.getElementById("msgOldAccount").innerHTML=oldAccount;
      total();
  });
  $("#totalAmount").keyup(function () {
      var totalAmount = String($(this).val());
      document.getElementById("msgTotalAmount").innerHTML=totalAmount;
  });
  function Course(){
    var course = document.getElementById("course").value;
    document.getElementById("msgCourse").innerHTML=course; 
  }
  function Term(){
    var term = document.getElementById("term").value;
    document.getElementById("msgTerm1").innerHTML=term; 
    document.getElementById("msgTerm").innerHTML=term; 
  }
  function total(){
    var ca = document.getElementById("currentAccount").value * 1;
    var oa = document.getElementById("oldAccount").value * 1;
    var total = ca + oa;
    document.getElementById("totalAmount").value = total;
    document.getElementById("msgTotalAmount").innerHTML = total;

  }
</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
