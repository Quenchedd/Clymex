<?php 
include("db.php");
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_data'])) {
    header("location: login.php");
    exit;
}

// Retrieve user data from session
$user_data = $_SESSION['user_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clymex</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="script.js"></script>
</head>
<body>
  <div class="body">
  <div class="navigation">
    <button class="menu" onclick="show()">
    <div id="bar1" class="bar"></div>
    <div id="bar2" class="bar"></div>
    <div id="bar3" class="bar"></div>
    
  </button>
  
  <nav>
    <ul>
    <li><button class="user-interface" onclick="location.href=`profile.php`"><i class='bx bx-user'></i> Profile</button></li>
    <li><button class="user-interface" onclick="location.href=`activitylist.php`"><i class='bx bx-clipboard'></i> Activity List</button></li>
    <li><button class="user-interface" onclick="location.href=`logout.php`"><i class='bx bx-log-out'></i> Log Out</button></li>
    </ul>
  </nav>
  </div>
<div class="main-content">
<img class="clymex" src="img/clymex.png" alt="">
<h1 class="headline">Embrace Innovation</h1>
  <h2 class="bottom-text">Make designing interesting and convenient.
  </h2>
  <h3 class="bottom-text">Lengthen your ideas here in Clymex.
  </h3>
</div>
  </div>
</body>
</html>