<!DOCTYPE html> 
<meta charset="utf-8">
<head>
<title>Homepage</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="styles/reset.css">
<link rel="stylesheet" href="styles/index.css">
</head>
<body>
<?php 
$email = $password = $password2 = $fname  = $gender  = $username = $phone = "";
$usererr = $emailerr = $phoneerr = $nameerr = $passerr = $pass2err =  "";
$flag1 = $flag2 = $flag3 = $flag4 =$flag5 = $flag6 = $flag7 = 0;  
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
    $username  = prepare($_POST["Username"]);
    if (!preg_match("/^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/", $username)){
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
    } 
  }


}
?>
<div class="navbar">
<div class="navbar-left"></div>
<div class="navbar-right">
<div>
<input type="text" pattern="/^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/"   placeholder="Username" name="usr" value="">
<input type="password" placeholder="password" name="pwd" value="">
</div>
<div>
<label for="remember">Remember me</label>
<input type="checkbox" id="remember" name="remember"  value="1">
<input type="submit" value="Login" name="submit">
</div>
</div>
</div>
<div class="main">
<div class="main-left"></div>
<div class="main-right">

<form id="msform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input type="hidden" value="signup" name = "formtype" >
<h2 class="form-title">Create your account</h2>
<input type="text" name="Username" value="<?php echo $username ?>" pattern="^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$" required  placeholder="Username"  />
<?php echo $usererr ?>
<input type="text" name="email" value="<?php echo $email ?>" required pattern="^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$"  placeholder="Email"  />
<?php echo $emailerr ?>
<input type="text" name="phone" value="<?php echo $phone ?>" placeholder="Phone Number" required pattern="(^((\+91|0)([\s-])?)?[7-9]{1}[0-9]{3}\4[0-9]{3}\4[0-9]{3}$|^[7-9]{1}[0-9]{3}([\s-])?[0-9]{3}\5[0-9]{3}$)"  />
<?php echo $phoneerr ?>
<input type="text" name="fname" value="<?php echo $fname ?>"  placeholder="Name" required pattern="^[a-zA-Z]{1}[a-zA-Z\s'-]+[a-zA-Z]{1}$"  />
<?php echo $nameerr ; ?>
<div class="gender">
<input type="radio" class="gen" name="gender" value="male" checked> Male
<input type="radio" class="gen" name="gender" value="female"> Female
<input type="radio" class="gen" name="gender" value="other"> Other
</div>

<input type="password" name="pass" placeholder="Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
<?php echo $passerr ; ?>
<input type="password" name="cpass" placeholder="Confirm Password" required pattern="[a-zA-Z\d!@#\$%\^&\*]{8,}" />
<?php echo $pass2err ; ?>
<?php echo $success ?>
<input type="submit" name="submit" class="submit action-button" value="Submit"  />
</form>
</div>
</div> 
</body>
</html>
