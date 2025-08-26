<?php 
include("db.php");
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_data'])) {
    header("location: login.php");
    exit;
}

$activityid = isset($_GET['activityid']) ? $_GET['activityid'] : '';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($activityid) {
    // Fetch users associated with this activity and their attendance status
    $sql = "SELECT user.userid, user.username, attendance.status 
            FROM `attendance`
            LEFT JOIN `user` ON attendance.userid = user.userid
            WHERE attendance.activityid = ?";

if ($searchQuery) {
    $sql .= " AND (user.userid LIKE ? OR user.username LIKE ?)";
    $searchQueryParam = "%" . $searchQuery . "%";
    $stmt = $con->prepare($sql);
    $stmt->execute([$activityid, $searchQueryParam, $searchQueryParam]);

} else{   
    $stmt = $con->prepare($sql);
    $stmt->execute([$activityid]);
}           
    $result = $stmt->get_result();
    
    $sql_activity = "SELECT * FROM `activity` WHERE `activityid` = ?";
    $stmt_activity = $con->prepare($sql_activity);
    $stmt_activity->execute([$activityid]);
    $activity_result = $stmt_activity->get_result();
    $activity = $activity_result->fetch_assoc();
} else {
    $activity = NULL;
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $statuses = $_POST['status'];
    foreach ($statuses as $userid => $status) {
    
    $query = "UPDATE `attendance` SET `status` = ? WHERE `userid` = ? AND `activityid` = ?";
    $stmt_update = $con->prepare($query);
    $stmt_update->execute([$status, $userid, $activityid]);
}
    echo"<script>alert('Attendance Updated');
    window.location.href = 'manage_act.php';</script>";

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
<div class="whole">
    <h1><?php echo ($activity['title']);?></h1>
    <form method="GET">
        <div class="search">
        <input type="hidden" name="activityid" value="<?php echo ($activityid); ?>">
        <input type="text" name="search" placeholder="Search..." value="<?php echo ($searchQuery); ?>">
        <button type="submit"><i class='bx bx-search '></i></button>
        </div>
    </form>
    <form method="POST">
    <div class="activities">
        <div class="top-row">
        <p><b>User ID</b></p>
        <p><b>Username</b></p>
        <p><b>Status</b></p>

        </div>
    <div class="scroll-act">    
    <?php
    $status_options = ['Present', 'Absent'];
    while($row = $result->fetch_assoc()) {
    ?>
        <div class="status-row">
        <p><b><?php echo $row['userid']; ?></b></p>
        <p><b><?php echo $row['username']; ?></b></p>
        
             <select id="status" name="status[<?php echo($row['userid']); ?>]">
             <?php foreach($status_options as $status) { ?>
                            <option value="<?php echo ($status); ?>" <?php if($status == $row['status']) echo 'selected'; ?>>
                                <?php echo ($status); ?>
                            </option>
                        <?php } ?>
        </select>
        
        </div>
        <?php }?>
    </div>
    </div>
    <div class="update">
             <button type="submit"><b>Update</b></button>
        </div>
        </form>
</body>
</html>