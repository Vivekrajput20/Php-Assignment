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
<div class="main">
<div class="left">
<a href="profile.php">Profile </a>
</div>
<div class = "right">

</div>
</div>
</body>
</html>
