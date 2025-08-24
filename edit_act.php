<?php 
include("db.php");
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_data'])) {
    header("location: login.php");
    exit;
}

// Retrieve user data from session
$admin_data = $_SESSION['admin_data'];
$adminId = $admin_data['adminid'];
$sql = "SELECT * FROM `group`";
$groups_result = $con->query($sql);
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

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = !empty($_POST['title']) ? $_POST['title'] : $activity['title'];
    $time = !empty($_POST['time']) ? $_POST['time'] : $activity['time'];
    $details = !empty($_POST['details']) ? $_POST['details'] : $activity['detail'];
    $location = !empty($_POST['location']) ? $_POST['location'] : $activity['location'];
    $points = !empty($_POST['points']) ? $_POST['points'] : $activity['points'];
    $groupid = !empty($_POST['groupid']) ? $_POST['groupid'] : $activity['groupid'];
    $imageName = $activity['image'];
    
    if (isset($_FILES["image"]) && $_FILES["image"]["name"]) {
        $imageName = $_FILES["image"]["name"];
        $imageSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
    
        // Image validation
        $validImageExtensions = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    
        if (!in_array($imageExtension, $validImageExtensions)) {
            echo "<script>
                    alert('Invalid Image Extension');
                    window.history.back();
                  </script>";
            exit;
        } elseif ($imageSize > 1200000) { // 1.2MB
            echo "<script>
                    alert('Image Size Is Too Large');
                    window.history.back();
                  </script>";
            exit;
        } 

        if (!move_uploaded_file($tmpName, 'img/' . $imageName)) {
            echo "<script>
                    alert('Failed to upload image');
                    window.history.back();
                  </script>";
            exit;
        }
    }

    $query = "UPDATE `activity` 
              SET `title` = ?, `detail` = ?, `time` = ?, `location` = ?, `points` = ?, `image` = ?, `adminid` = ?, `groupid` = ?
              WHERE `activityid` = ?";

    $stmt = $con->prepare($query);

    $stmt->execute([$title, $details, $time, $location, $points, $imageName, $adminId, $groupid ,$activityid]);

    if ($activity['groupid'] != $groupid) {
        // Delete all attendance records for this activity
        $deleteAttendanceQuery = "DELETE FROM `attendance` WHERE `activityid` = ?";
        $deleteAttendanceStmt = $con->prepare($deleteAttendanceQuery);
        $deleteAttendanceStmt->execute([$activityid]);

        // Retrieve all users in the updated group
        $userQuery = "SELECT `userid` FROM `user` WHERE `groupid` = ?";
        $userStmt = $con->prepare($userQuery);
        $userStmt->execute([$groupid]);
        $users = $userStmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Insert new attendance records for each user in the updated group
        $attendanceQuery = "INSERT INTO `attendance` (`status`, `activityid`, `userid`, `groupid`) VALUES ('Absent', ?, ?, ?)";
        $attendanceStmt = $con->prepare($attendanceQuery);
        foreach ($users as $user) {
            $attendanceStmt->execute([$activityid, $user['userid'], $groupid]);
        }
    }

    echo "<script>
            alert('Activity Updated');
            window.location.href ='manage_act.php';
        </script>";
        exit;
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
<div class="add-whole">
   <h1>Edit Activity</h1>
    <div class="wrapper">
        <form method="POST" enctype="multipart/form-data">
        <div class="activity-name">
            <input type="text" placeholder="Activity Name" name="title">
    </div>
        <div class="time">
            <input type="datetime-local" name="time">
    </div>    
        <div class="details">
        <textarea placeholder="Details" name="details"></textarea>
    </div>
        <div class="location">
            <input type="text" placeholder="Location" name="location" id="">
    </div>
        <div class="points">
            <input type="number" placeholder="Points" name="points" id="">
    </div>
        <div class="activity-image">
            <label>Activity Image: </label>
            <input type="file" name="image" id="">
    </div>
    <div class="dropbox">
                <select id="group" name="groupid" required>
                <?php
                while ($row = $groups_result->fetch_assoc()) {
                    $selected = ($row['groupid'] == $activity['groupid']) ? 'selected' : '';
                    echo "<option value='{$row['groupid']}' {$selected}>{$row['groupname']}</option>";
                }
                ?>
                </select>
            </div>
        <div class="create">
             <button type="submit"><b>Update</b></button>
        </div>
    </form>
    </div>
    </div>
</body>
</html>