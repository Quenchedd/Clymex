<?php 
session_start();
include("db.php");

if(!isset($_SESSION['user_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}
$user_data = $_SESSION['user_data'];
$userid = $user_data['userid'];
$activityid = isset($_GET['activityid']) ? $_GET['activityid'] : '';

if ($activityid) {
    $sql = "SELECT * FROM `activity` WHERE `activityid` = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$activityid]);
    $result = $stmt->get_result();
    $activity = $result->fetch_assoc();
} else {
    $activity = null;
}
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
    <div class="attendance">
        <div class="join">
        <h1><?php echo ($activity['title']);?></h1>
        
            <img class="attendance-pic" src="img/<?php echo htmlspecialchars($activity['image']);?>" alt="">
            <p><?php echo ($activity['detail']); ?></p>
            <p><?php echo ($activity['time']); ?></p>
            <p><?php echo ($activity['location']); ?></p>
            
         <form action="join_activity.php" method="POST">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="activityid" value="<?php echo $activity['activityid']; ?>">
            <button type="submit"><img class="join-button" src="img/join.png" alt=""></button>
         </form>
        </div>
    </div>
</body>
</html>