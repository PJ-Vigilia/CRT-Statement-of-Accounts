<?php
  session_start();
  require_once "../config.php";

  if(empty($_SESSION['user_id'])){
    header("location: ../login.php");
  }
  $user_id = $_SESSION['user_id'];
  $term_id = $_GET['term_id'];

  //user
  $sql = $pdo->prepare("SELECT * FROM users JOIN student USING(user_id) WHERE user_id ='$user_id'");
  $sql->execute();
  $user = $sql->fetch();

  if($user['roles'] !="student"){
    header("location:../admin/warning page.php");
  }

  $name = $user['fname'] . " " . $user['lname'];
  $course = $user['course'];

  //statement of account
  $sqlSOA = $pdo->prepare("SELECT * FROM term_account WHERE term_id ='$term_id'");
  $sqlSOA->execute();
  $soa = $sqlSOA->fetch();
  $term = $soa['term'];
  $current_account= $soa['current_account'];
  $old_account = $soa['old_account'];
  $total_amount = $soa['total_amount'];
  $date = $soa['date'];

  // Processing form data when form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $image = $_FILES["image"]["name"];

    $message=$_POST['msg'];

    //current date
    date_default_timezone_set('Asia/Manila');
    $date  = date('Y-m-d');
    $time = date('H:i:s');
    $sender = $user_id;

    //receiver id
    $sqlReciever = $pdo->prepare("SELECT * FROM term_account WHERE term_id ='$term_id'");
    $sqlReciever->execute();
    $stmtreciever = $sqlReciever->fetch();
    $reciever = $stmtreciever['sender'];

    $sqlReply = "INSERT INTO reply(term_id, sender, reciever,message, picture, date, time) VALUES(:term_id, :sender, :reciever, :message, :picture, :date, :time)";
    if($stmtReply = $pdo->prepare($sqlReply)){
      $stmtReply->bindParam(":term_id", $param_termID);
      $stmtReply->bindParam(":sender", $param_sender);
      $stmtReply->bindParam(":reciever", $param_reciever);
      $stmtReply->bindParam(":message", $param_message);
      $stmtReply->bindParam(":picture", $param_picture);
      $stmtReply->bindParam(":date", $param_date);
      $stmtReply->bindParam(":time", $param_time);

      $param_termID = $term_id;
      $param_sender = $sender;
      $param_reciever = $reciever;
      $param_message = $message;
      $param_picture = $image;
      $param_date = $date;
      $param_time = $time;

      if($stmtReply->execute()){
         header("location:statement of accounts.php?term_id=".$term_id);
      }
      else{
        echo "Something went wrong.";
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
          <a class="nav-link" href="inbox.php">Inbox<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">Log out</a>
        </li>
      </ul>
    </div>
  </nav>
  <!--end of Navivation bar-->

  <div class="container" style="padding-top: 3%">
    <div class="card" style="width: 100%;">
        <div class="card-body">
          Mr/Mrs.: <b><span id="msgName"><?php echo $name;?></span></b><br>
          Course: <span id="msgCourse"><?php echo $course;?></span><br><br>
          Good day!<br><br>
          This is from CRT Accounting Cabanatuan.<br><br>
          <b><?php echo $term;?> EXAMINATION</b> will be on <span id="msgDate"><?php echo $date;?></span>. Please settle your account on or before the date of examination.<br><br>
          Your Statement of Account is <b><span id="msgTerm"><?php echo $term;?></span> </b>Php <b><span id="msgCurrentAmount"><?php echo $current_account;?></span></b><br>
          <b>Old Account:</b> Php <span id="msgOldAccount"><?php echo $old_account;?></span><br>
          <b>Total Amount:</b> Php <span id="msgTotalAmount"><?php echo $total_amount;?></span>.<br><br>
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
          </table><br>
          <div style="text-align: justify;background-color: #13A4CD; padding: 2px;">
            Please <b>SEND</b> as a <b>COPY</b> of your <b>DEPOSIT SLIP or OFFICIAL RECEIPT</b> and wait foryour permit number to be sent back.
          </div>
          
        </div>
      </div>

    </div>
  </div>

  <div class="container" style="padding-top: 1%;padding-bottom: 1%">
    <div class="card" style="width: 100%;">
      <?php
        $sqlReplies = "SELECT * FROM reply WHERE term_id='$term_id' ORDER BY date, time ASC";
        if($resultReplies = $pdo->query($sqlReplies)){
          if($resultReplies->rowCount() > 0){
            while($rowReplies = $resultReplies->fetch()){
              $sender_id = $rowReplies['sender'];
              $sqlSender = $pdo->prepare("SELECT * FROM users WHERE user_id ='$sender_id'");
              $sqlSender->execute();
              $sender = $sqlSender->fetch();
              $sender_name = $sender['fname'] . " " . $sender['lname'];
      ?>

      <div class="card-body">
      <strong><?php echo $sender_name;?></strong> | 
      <small style="padding-left: 1%"><?php echo $rowReplies['date'];?> | <?php echo $rowReplies['time'];?></small><br>
        <div  style="padding-left: 1%">
          
          <?php
            if(!empty($rowReplies['picture'])){
          ?>
          <a href="../image/<?php echo $rowReplies['picture'];?>" class="image-popup">
            <img src="../image/<?php echo $rowReplies['picture'];?>" width="150px" height="150px">
          </a>
          <?php
            }
              echo $rowReplies['message'];
            
          ?>
        </div>        
      </div>
      <?php
            }
          }
        }
      ?>
        
      <div class="card-body">
        <!--form-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?term_id=<?php echo $term_id;?>" method="post" enctype="multipart/form-data">

        <p class="d-flex justify-content-end"><img id="output" width="100px" height="100px" src="temporaryheading.png" style="display: none;"></p>
       <textarea name="msg" class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
        <div class="d-flex justify-content-end" style="padding-top: 2px">
          <p><input type="file"  accept="image/*" name="image" id="file"  onchange="loadFile(event)" style="display: none;"></p>
                  
          
          <p><label  class="btn btn-secondary btn-sm" for="file" style="cursor: pointer;">Attach Photo</label>
         <button class="btn btn-primary btn-sm" style="margin-top: -7px">Send</button></p>
          <script>
          var loadFile = function(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
            image.style.display = "block";
          };
          </script>
        </div>

      </form>
      </div>
    </div>
  </div>
  
</body>
</html>
