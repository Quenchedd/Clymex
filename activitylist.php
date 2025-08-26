<?php 
session_start();
include("db.php");

if(!isset($_SESSION['user_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}
$user_data = $_SESSION['user_data'];

// Initialize search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Prepared statement to prevent SQL injection
$sql = $con->prepare("SELECT * FROM `activity` WHERE groupid = ? AND title LIKE ?");
$searchPattern = '%' . $searchQuery . '%';
$sql->bind_param('is', $user_data['groupid'], $searchPattern);
$sql->execute();
$result = $sql->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
    <div class="whole">
    <h1>Activity List</h1>
    
    <form method="GET">
        <div class="search">
        <input type="hidden" name="activityid" value="<?php echo ($activityid); ?>">
        <input type="text" name="search" placeholder="Search..." value="<?php echo ($searchQuery); ?>">
        <button type="submit"><i class='bx bx-search '></i></button>
        </div>
    </form>
    <div class="list">
    <?php
    
    // Check if any activities found for the user's group
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
    ?>
    <div class="activity">
    <img class="activity-pic" onclick="location.href='join.php?activityid=<?php echo urlencode($row['activityid']); ?>'" src="img/<?php echo $row['image']; ?>" alt="">
    <p><?php echo $row['title']; ?></p>
    </div>
    
    <?php
        }
    } else {
        // No activities found for the user's group
        echo "<p>No activities found for your group.</p>";
    }
    ?>
    
    </div>
    </div>
    

</body>
</html>