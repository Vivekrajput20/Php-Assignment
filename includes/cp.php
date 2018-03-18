<?php session_start();
if (!isset($_SESSION["uid"]))
header('location:../index.php');
?>
<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password</title>
<link rel="stylesheet" type="text/css" href="../styles/reset.css">
</head>
<?php 
$passerr=$npasserr = $msg = $cpasserr= $flag1 = $flag2 = $flag3  ="";
if ($_SERVER["REQUEST_METHOD"] === "POST"){
  function prepare($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace("'", "&#039;", $data);
    return $data;
  }
  if(empty($_POST["pass"])){
    $passerr = "<div class=\"error\">  *Old password required</div>";
  }
  else{
    $pass = prepare($_POST["pass"]);
    if (!preg_match("/[a-zA-Z\d!@#\$%\^&\*]{8,}/" , $pass)){
      $passerr = "<div class=\"error\"> Password must be at least 8 characters long.</div>";
    }
    else{
      $passerr="";
      $flag1 = 1;
    }
  };
  if(empty($_POST["npass"])){
    $npasserr = "<div class=\"error\">  *New password cant be empty! </div>";
  }
  else{
    $npass = prepare($_POST["npass"]);
    if (!preg_match("/[a-zA-Z\d!@#\$%\^&\*]{8,}/" , $npass)){
      $npasserr = "<div class=\"error\">New password must be at least 8 characters</div>";
    }
    else{
      $npasserr ="";
      $flag2 = 1;
    };
      }
  if(empty($_POST["cpass"])){
    $cpasserr = "<div class=\"error\">  * Confirmation is required! </div>";
  }
  else{
    $cpass = prepare($_POST["cpass"]);
    if ($npass !== $cpass){
      $cpasserr = "<div class=\"error\">Confirm Password do not match Password</div>";
    }
    else{
      $cpasserr ="";
      $flag3 = 1;
    };
  };
  if ($flag1=== 1 && $flag2 ===1 && $flag3 ===1){
    require_once("config.php");
    $conn = new mysqli($servername, $username , $passd , $dbname);
    if($conn->connect_error){
      die("Connection failed: " . $conn->connect_error);
    }
    $uid = $_SESSION["uid"];
    $sql = "SELECT password  FROM vivek_userinfo where userid = '$uid'";
    $result = $conn->query($sql);
  if ($result->num_rows ===1){
    $user = $result->fetch_assoc();  
    $op = $user["password"];
      $passHash = hash("sha256" , $pass);
      if ($passHash === $op ){
$npassHash = hash("sha256" , $npass);
$sql = "Update vivek_userinfo set password = '$npassHash' where userid = '$uid';";
 if ($conn->query($sql) === TRUE) {
                           $msg = "<div>Password Changed</div>" ;
                               }
     else{
             echo "Error: " . $sql . "<br>" . $conn->error;
                 };
      }
      else{
      $msg = "<div>Old password do not match </div>";
      }
    }
  }
  }
  ?>
    <body>
    <form id="msform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
    <h1>Change Password</h1>
    <a href="home.php">Go Back </a>
    <div>Current Password: <input type="password" class="form-control" name="pass" placeholder="Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
    <?php echo $passerr ?>
    </div>
    <div>New Password: <input type="password" class="form-control" name="npass" placeholder="Confirm Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
    <?php echo $npasserr ?>
    </div>
    <div>Confirm New Password: <input type="password" class="form-control" name="cpass" placeholder="Confirm Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
    <?php echo $cpasserr ?>
    </div>
<?php echo $msg ?>
    <input type="submit" name="submit" class="submit action-button" value="Submit"  />
    </form>
    </body>
    </html>
