<?php
session_start();
if (isset($_SESSION["uid"]))
 header('location:includes/home.php');
 ?>
<!DOCTYPE html> 
<meta charset="utf-8">
<head>
<title>Homepage</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="scripts/index.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="styles/reset.css">
<link rel="stylesheet" href="styles/index.css">
</head>
<body>
<?php 
$email =$success = $password = $password2 = $fname  = $gender  = $uname = $phone = "";
$usererr = $emailerr = $phoneerr = $nameerr = $passerr = $pass2err =  "";
$flag1 = $flag2 = $flag3 = $flag4 =$flag5 = $flag6 = $flag7 = 0;  
$usrerr= $pwderr = ""; 

function prepare($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && prepare($_POST["formtype"])==="signup"){
  if(empty($_POST["Username"])){
    $usererr  = "<div class=\"error\">  * Username is required! </div>";
  }
  else{
    $uname  = prepare($_POST["Username"]);
    if (!preg_match("/^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/", $uname)){
      $usererr = "<div class=\"error\"> Invalid Username </div>";
    }
    else{
      $usererr="";
      $flag1 = 1;
    }
  };
  if(empty($_POST["email"])){
    $emailerr  = "<div class=\"error\">  * Email is required! </div>";
  } 
  else{
    $email  = prepare($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $emailerr = "<div class=\"error\"> Invalid Email </div>";
    }
    else{
      $emailerr="";
      $flag2 = 1;
    }
  };

  if(empty($_POST["phone"])){
    $phoneerr  = "<div class=\"error\">  *Phone is required! </div>";
  } 
  else{
    $phone  = prepare($_POST["phone"]);
    if (preg_match("/(^((\+91|0)([\s-])?)?[7-9]{1}[0-9]{3}\4[0-9]{3}\4[0-9]{3}$|^[7-9]{1}[0-9]{3}([\s-])?[0-9]{3}\5[0-9]{3}$)/", $phone)){
      $phoneerr = "<div class=\"error\"> Invalid Phone </div>";
    }
    else{
      $phoneerr="";
      $flag3 = 1;
    }
  };
  if(empty($_POST["fname"])){
    $nameerr  = "<div class=\"error\">  *Name is required! </div>";
  } 
  else{
    $fname  = prepare($_POST["fname"]);
    if (!preg_match("/^[a-zA-Z]{1}[a-zA-Z\s'-]+[a-zA-Z]{1}$/i", $fname)){
      $nameerr = "<div class=\"error\"> Invalid Name </div>";
    }
    else{
      $nameerr="";
      $flag4 = 1;
    }
  };
  $gender = prepare($_POST["gender"]);
  if($gender === "male" || $gender ==="female" || $gender ==="other"){
    $flag5 = 1;
  };
  if(empty($_POST["pass"])){
    $passerr = "<div class=\"error\">  * Password is required! </div>";
  }
  else{
    $password = prepare($_POST["pass"]);
    if (!preg_match("/[a-zA-Z\d!@#\$%\^&\*]{8,}/" , $password)){
      $passerr = "<div class=\"error\"> Your password must be at least 8 characters long.</div>";
    }
    else{
      $passerr="";
      $flag6 = 1;
    }
  };
  if(empty($_POST["cpass"])){
    $pass2err = "<div class=\"error\">  * Confirmation is required! </div>";
  }
  else{
    $password2 = prepare($_POST["cpass"]);
    if ($password !== $password2){
      $pass2err = "<div class=\"error\">Confirm Password do not match Password</div>";
    }
    else{
      $pass2err ="";
      $flag7 = 1;
    };
  };
  if ($flag1 === 1 && $flag2 === 1  && $flag3 ===1 && $flag4 === 1 && $flag5 === 1 && $flag6 === 1 && $flag7 === 1){
    require_once("includes/config.php");
    $conn = new mysqli($servername, $username , $passd , $dbname);
    if($conn->connect_error){
      die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT email , username FROM vivek_userinfo";
    $result = $conn->query($sql);
    if ($result->num_rows > 0 ){
      while ($row = $result->fetch_assoc()) {
        if (strtolower($email) === strtolower($row["email"])){
          $emailerr = "<div class=\"error\"> Email already exists ! </div>";
          $flag2 = 0;
        }
        if (strtolower($uname) === strtolower($row["username"])){
          $usererr = "<div class=\"error\"> Username already exists ! </div>";
          $flag1 = 0;
        }
      }
    };
    if ($flag1 ===1 && $flag2===1){
      $passHash = hash("sha256" , $password);
      $email = $conn->escape_string($email);
      $fname = $conn->escape_string($fname);
      $uname = $conn->escape_string($uname);
      $phone = $conn->escape_string($phone);
      $gender = $conn->escape_string($gender);
      $sql = "INSERT INTO vivek_userinfo (email, name, username , phone , gender ,  password)
        VALUES ('$email', '$fname', '$uname' , '$phone' ,  '$gender' , '$passHash')";
      if ($conn->query($sql) === TRUE) {
        $success = "<div class=\"login-message\">Account Successfully Created! </div>";
      }
      else{
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
    $conn->close(); 
  };

}
else if ($_SERVER["REQUEST_METHOD"] === "POST" && prepare($_POST["formtype"])==="login"){
  if(empty($_POST["usr"])){
    $usrerr = "<div class=\"lerror\">  *Username is required! </div>";
  }
  else{
    $flg = 1;
    $usr = prepare($_POST["usr"]);
    if (filter_var($usr, FILTER_VALIDATE_EMAIL)){
      $utype ="email";
    }
    else if(preg_match("/^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/", $usr)){
      $utype="username";
    }
    else{
      $usrerr = "<div class=\"lerror\">  *Invalid Username/Email format </div>";
      $flg =0;
    }
    if($flg===1){
      $pas = prepare($_POST["pwd"]);
      $passHash = hash("sha256" , $pas);
      require_once("includes/config.php");
      $conn = new mysqli($servername, $username , $passd , $dbname);
      $usr = $conn->escape_string($usr);      
      if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
      }
      $sql = "select * From vivek_userinfo where ". $utype . "='" . $usr . "' and password='" . $passHash . "'";
      $result = $conn->query($sql);
      if ($result->num_rows ===1){
        $user = $result->fetch_assoc();
        $_SESSION["uid"] = $user["userid"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["gender"] = $user["gender"];
        $conn->close();
       header('Location: includes/home.php');
      }
      else{
        $pwderr = "<div class=\"lerror\"> Wrong Email or Password ! </div>";
      };
    }


  };
}  
?>
<div class="navbar">
<div class="navbar-left">ConnectThemUp</div>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"  class="navbar-right">
<input type="hidden" value="login" name = "formtype" >
<div>
<input type="text" class="form-control"  placeholder="Username" name="usr" value="">
<?php echo $usrerr ?>

<input type="password" class="form-control" placeholder="password" name="pwd" value="">
</div>
<div>
<label for="remember">Remember me</label>
<input type="checkbox" id="remember" name="remember"  value="1">
<input type="submit" value="Login" class="submit" name="submit">
</div>
</form>
</div>
<div class="main">
<div class="main-left"></div>
<div class="main-right">

<form id="msform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input type="hidden" value="signup" name = "formtype" >
<h2 class="form-title">Create your account</h2>
<div><input type="text" class="form-control" onkeyup="validate(this.value ,'ajax-v1' , 'username')" name="Username" value="<?php echo $uname ?>" pattern="^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$" required  placeholder="Username"  /><span id="ajax-v1"></span></div>
<?php echo $usererr ?>
<div><input type="text" class="form-control" name="email" onkeyup="validate(this.value ,'ajax-v2' , 'email')" value="<?php echo $email ?>" required pattern="^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$"  placeholder="Email"  /><span id="ajax-v2"></span></div>
<?php echo $emailerr ?>
<input type="text" class="form-control" name="phone" value="<?php echo $phone ?>" placeholder="Phone Number" required pattern="(^((\+91|0)([\s-])?)?[7-9]{1}[0-9]{3}\4[0-9]{3}\4[0-9]{3}$|^[7-9]{1}[0-9]{3}([\s-])?[0-9]{3}\5[0-9]{3}$)"  />
<?php echo $phoneerr ?>
<input type="text" class="form-control" name="fname" value="<?php echo $fname ?>"  placeholder="Name" required pattern="^[a-zA-Z]{1}[a-zA-Z\s'-]+[a-zA-Z]{1}$"  />
<?php echo $nameerr ; ?>
<div class="gender">
<input type="radio" class="gen" name="gender" value="male" checked> Male
<input type="radio" class="gen" name="gender" value="female"> Female
<input type="radio" class="gen" name="gender" value="other"> Other
</div>

<input type="password" class="form-control" name="pass" placeholder="Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
<?php echo $passerr ; ?>
<input type="password" class="form-control" name="cpass" placeholder="Confirm Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
<?php echo $pass2err ; ?>
<?php echo $success ?>
<input type="submit" name="submit" class="submit action-button" value="Submit"  />
</form>
</div>
</div> 
</body>
</html>
