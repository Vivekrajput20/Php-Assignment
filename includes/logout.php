<?php 
session_start();
function prepare($data){
    $data = trim($data);
      $data = stripslashes($data);
        $data = htmlspecialchars($data);
          return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && prepare($_GET["q"])==="reset"){
$sid =prepare($_COOKIE["user"]);
require_once("config.php");
  $conn = new mysqli($servername, $username , $passd , $dbname);
    $sid= $conn->escape_string($sid);      
      if($conn->connect_error){
            die("Connection failed: " . $conn->connect_error);
              }
        $sql = "delete from vivek_cookie   where sid = '$sid'";
    if ($conn->query($sql) === TRUE) {
     unset($_COOKIE['user']);
      unset($_COOKIE['uname']);
      setcookie("user", "", time() - 3600 , '/');
      setcookie("uname", "", time() - 3600 , '/');
    }
      else{
                echo "Error: " . $sql . "<br>" . $conn->error;
                      }

}

session_unset();
session_destroy(); 
header('location: /php_assign/vivek/index.php');
?>
