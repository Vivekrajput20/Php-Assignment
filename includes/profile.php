<?php
session_start();
if (!isset($_SESSION["uid"]))
header('location:../index.php');
?>
<!DOCTYPE html>
<html lang="en" >
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profile</title>
<link rel="stylesheet" type="text/css" href="../styles/reset.css">
<link rel="stylesheet" type="text/css" href="../styles/profile.php">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<?php 
require_once("config.php");
$conn = new mysqli($servername, $username , $passd , $dbname);
if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
};
$sql = "select * From vivek_userinfo where userid='" . $_SESSION["uid"] . "'";
$result = $conn->query($sql);
if ($result->num_rows ===1){
  $user = $result->fetch_assoc();
  $phone =$user["phone"];
  $fname = $user["name"];
  $branch = $user["branch"];
  $interest =$user["interest"];
}
function prepare($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
};
$phoneerr= $nameerr = $brancherr = $interesterr = "";
$flag1= $err1 = $flag2 = $flag3 = $flag4 ="";
if ($_SERVER["REQUEST_METHOD"] === "POST" ){
  if(empty($_POST["phone"])){
    $phoneerr  = "<div class=\"error\">  *Phone is required! </div>";
  } 
  else{
    $phonef  = prepare($_POST["phone"]);
    $phonef = $conn->escape_string($phonef);
    if (preg_match("/(^((\+91|0)([\s-])?)?[7-9]{1}[0-9]{3}\4[0-9]{3}\4[0-9]{3}$|^[7-9]{1}[0-9]{3}([\s-])?[0-9]{3}\5[0-9]{3}$)/", $phonef)){
      $phoneerr = "<div class=\"error\"> Invalid Phone </div>";
    }
    else{
      $phoneerr="";
      $flag1 = 1;
    }
  };
  if(empty($_POST["fname"])){
    $nameerr  = "<div class=\"error\">  *Name is required! </div>";
  } 
  else{
    $fnamef  = prepare($_POST["fname"]);
    $fnamef = $conn->escape_string($fnamef);   
    if (!preg_match("/^[a-zA-Z]{1}[a-zA-Z\s'-]+[a-zA-Z]{1}$/i", $fnamef)){
      $nameerr = "<div class=\"error\"> Invalid Name </div>";
    }
    else{
      $nameerr="";
      $flag2 = 1;
    }
  };
  if(!($_POST["branch"]==="")){
    $branchf = prepare($_POST["branch"]);
    $branchf = $conn->escape_string($branchf);    
    if (!preg_match("/^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/", $branchf)){
      $brancherr = "<div class=\"error\"> Invalid Branch Name </div>";
    }
    else{
      $brancherr="";
      $flag3= 1;
    }
  };
  if(!($_POST["interest"]==="")){
    $interestf  = prepare($_POST["interest"]);
    $interestf = $conn->escape_string($interestf);    
    if (!preg_match("/^[a-zA-Z]+([\.-_\s,]*[0-9]*[a-zA-Z]*[0-9]*)*$/", $interestf)){
      $interesterr = "<div class=\"error\"> Invalid interests string </div>";
    }
    else{
      $interseterr="";
      $flag4 = 1;
    }
  };

  if ($flag1 === 1 && $flag2 === 1  && $flag3 ===1 && $flag4 === 1 ){
    $sql = "UPDATE vivek_userinfo SET phone = '$phonef' , name = '$fnamef' , branch = '$branchf', interest = '$interestf' where userid = '". $_SESSION["uid"] . "'" ;
    if ($conn->query($sql) === TRUE) {
      $_SESSION["name"] = $fnamef;
      echo "Profile Updated" ;
    }
    else{
      echo "Error: " . $sql . "<br>" . $conn->error;
    }

  };
  if($_FILES["profile"]["name"]!==""){
    function file_upload($file_name ,  $usid , &$err){
      $ext = end(explode("." , $_FILES[$file_name][ "name"]));
      $file_dir = "../images/". $usid . "/"  ;
      $image_dir = $file_dir . "image." . $ext;
      $check = getimagesize($_FILES[$file_name]["tmp_name"]);           
      if($check !== false) { 
        if (move_uploaded_file($_FILES[$file_name]["tmp_name"] , $image_dir)){

        }
        else {
          $err = "<div class='error'> Your image was not uploaded!</div>";
        }
      }
      else {
        $err =  "<div class='error'>File is not an image.</div>";
      }
    }
    $file_dir = "../images/" . $_SESSION["uid"] . "/"  ;
if (!file_exists($file_dir)) {
    mkdir($file_dir);
}
    $finame = "profile";
    file_upload($finame, $_SESSION["uid"] , $err1);
  }


}

?>
<a href="home.php">Home</a>  <span id="edit">edit profile</span>
<form id="msform"enctype="multipart/form-data"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<h2>Profile </h2>
<div>Phone:<input type="text" class="form-control" name="phone" value="<?php echo $phone ?>" placeholder="Phone Number" required pattern="(^((\+91|0)([\s-])?)?[7-9]{1}[0-9]{3}\4[0-9]{3}\4[0-9]{3}$|^[7-9]{1}[0-9]{3}([\s-])?[0-9]{3}\5[0-9]{3}$)"  /></div>
<?php echo $phoneerr ?>
<div>Name: <input type="text" class="form-control" name="fname" value="<?php echo $fname ?>"  placeholder="Name" required pattern="^[a-zA-Z]{1}[a-zA-Z\s'-]+[a-zA-Z]{1}$"  /></div>
<?php echo $nameerr ; ?>
<div>Branch<input type="text" pattern="^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$"  value="<?php echo $branch ?>" name="branch" Placeholder="branch" class="form-control" ></div>
<?php echo $brancherr ?>
<div>Interests<input type="text" value="<?php echo $interest ?>" pattern="^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$" name="interest" Placeholder="interests" class="form-control" ></div>
<?php echo $interesterr ?>
<div><label for="profile" class="label-btn">
Update Profile Picture
</label>
<?php echo $err1 ?>
<input type="file"  name="profile" id="file1" class="fileinput">
</div><input type="submit" class="form-control" name="submit" class="submit action-button" value="Submit"  />
</form>
<script>
$("#edit").css("cursor", "pointer");
$(".form-control").attr('disabled','disabled');
$("#edit").click(function(){
    $(".form-control").removeAttr('disabled'); ;});
</script>
</body>
</html>
