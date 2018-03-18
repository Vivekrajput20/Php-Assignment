<?php 
$q = $_REQUEST["value"];
$p = $_REQUEST["field"];
function prepare($data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
$q = prepare($q);
$p = prepare($p);
require_once("config.php");
$conn = new mysqli($servername, $username , $passd , $dbname);
if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
};
$q = $conn->escape_string($q);
$p = $conn->escape_string($p);
$sql = "SELECT $p FROM vivek_userinfo where $p = '$q'";

if ($p === "username"){
	if (preg_match("/^[a-zA-Z]+([\.-_]*[0-9]*[a-zA-Z]*[0-9]*)*$/", $q)){
		$result = $conn->query($sql);
		if ($result->num_rows === 0 ){
			echo "Username Available";
		}
		else{
			echo "Username not Available";
		}
	}
}
else if ($p === "email"){
	if (filter_var($q, FILTER_VALIDATE_EMAIL)){
		$result = $conn->query($sql);
		if ($result->num_rows === 0 ){
			echo "Email Available";
		}
		else{
			echo "Email not Available";
		}
	}

}
?>