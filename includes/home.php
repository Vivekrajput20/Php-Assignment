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
<title>Homepage</title>
<link rel="stylesheet" type="text/css" href="../styles/reset.css">
<link rel="stylesheet" type="text/css" href="../styles/home.css">
</head>
<body>
<?php
require_once("config.php");
$conn = new mysqli($servername, $username , $passd , $dbname);
$success="";
if($conn->connect_error){
  die("Connection failed: " . $conn->connect_error);
}
$sql = "select * From vivek_userinfo where userid='" . $_SESSION["uid"] . "'";
$result = $conn->query($sql);
if ($result->num_rows ===1){
  $user = $result->fetch_assoc();
  $dp = $user["dp"];
  $branch = $user["branch"];
  $cover = $user["cover"];
  $interest = $user["interest"];
}
if ($_SERVER["REQUEST_METHOD"]=== "POST"){
  function prepare($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace("'", "&#039;", $data);
    return $data;
  }
  $post = prepare($_POST["post"]);
  $today = date("Y-m-d") ;
  $ctime = date("H:i");
  $post = $conn->escape_string($post); 
  if ($post != ""){
  $sql = "INSERT INTO vivek_posts (userid , post , time , date)
    VALUES ('" . $_SESSION["uid"] . "' , '$post' , '$ctime' , '$today')";
  if ($conn->query($sql) === TRUE) {
    $success = "<div class=\"login-message\">Posted Successfully! </div>";
  
  }
  else{
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  }
  else{
 $success = "<div class=\"login-message\">Post is empty!</div>";
  };
  
}

?>
<div class="main">
<div class="left">
<img src="<?php echo $dp ?>" class="dp" >
<div><?php echo "Welcome " . $_SESSION["name"] ?>
</div>
<a href="profile.php">Profile </a>
<br>
<a href="logout.php">Logout</a>
</div>
<div class = "right">
<div class="cover" style="background:url('<?php echo $cover ?>')" >
<a href="profile.php" >Change Cover Picture</a>
</div>
<?php 
if ($branch ==="" && $interest ==="" ){
  ?>
    <div>You need to update your<a href="profile.php"> profile</a> first</div>
    <?php 
}
else{
  ?>
    <form id="msform"enctype="multipart/form-data"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <textarea rows="3" cols="55" wrap="hard" name="post" placeholder="Post something Here!"></textarea>  
    <br><input type="submit" value="post" >
    </form>
    <?php echo $success ?>
    <div class="feed">
    <?php
   $sql = " select name , post , time , date  From vivek_posts as a inner join vivek_userinfo as b on a.userid = b.userid   order by postid DESC;";
  $result = $conn->query($sql);
      if ($result->num_rows >0){
              while(($row = $result->fetch_assoc()) ) {
                ?>
<div class="post">
<div><?php echo $row["name"] ?></div>
<div>Posted at <?php echo $row["time"] ?> </div>
<div> Posted on <?php echo $row["date"] ?> </div>
<div class="post-data"><?php echo $row["post"] ?></div> 
</div>
<?php }}; ?>
    </div>
    <?php
}
?>
</div>
</div>
</body>
</html>
