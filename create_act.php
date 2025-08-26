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
$sql = "SELECT `groupid`, `groupname` FROM `group`";
$result = $con->query($sql);

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $title = $_POST['title'];
        $time = $_POST['time'];
        $details = $_POST['details'];
        $location = $_POST['location'];
        $points = $_POST['points'];
        $groupid = $_POST['groupid'];
        $imageName = 'no_img.jpg';
    
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
    
    $query = "INSERT INTO `activity` (`title`, `detail`, `time`, `location`, `points`, `image`, `adminid`, `groupid`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($query);
$stmt->execute([$title, $details, $time, $location, $points, $imageName, $adminId, $groupid]);

// Get the last inserted activity id
$activityId = $con->insert_id;

// Retrieve all users in the selected group
$userQuery = "SELECT `userid` FROM `user` WHERE `groupid` = ?";
$userStmt = $con->prepare($userQuery);
$userStmt->execute([$groupid]);
$users = $userStmt->get_result()->fetch_all(MYSQLI_ASSOC);


// Insert absent status for each user in the group
foreach ($users as $user) {
$attendanceQuery = "INSERT INTO `attendance` (`status`, `activityid`, `userid`, `groupid`) VALUES ('Absent', ?, ?, ?)";
$attendanceStmt = $con->prepare($attendanceQuery);
$attendanceStmt->execute([$activityId, $user['userid'], $groupid]);
}

echo "<script>
  alert('Activity Added');
  window.location.href = 'manage_act.php';
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
    <link rel="stylesheet" href="mainstyle.css">
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
    <div class="add-whole">
   <h1>Add Activity</h1>
    <div class="wrapper">
        <form method="POST" enctype="multipart/form-data">
        <div class="activity-name">
            <input type="text" placeholder="Activity Name" name="title">
    </div>
        <div class="time">
            <input type="datetime-local" name="time">
    </div>    
        <div class="details">
        <textarea placeholder="Details (Optional)" name="details"></textarea>
    </div>
        <div class="location">
            <input type="text" placeholder="Location" name="location" id="">
    </div>
        <div class="points">
            <input type="number" placeholder="Points" name="points" id="">
    </div>
        <div class="activity-image">
            <label>Activity Image (Optional): </label>
            <input type="file" name="image" id="">
    </div>
    <div class="dropbox">
        
            <select id="group" name="groupid" >
            <?php
    
    while($row = $result->fetch_assoc()) {
    ?>
            <option value="<?php echo $row['groupid']?>"><?php echo $row['groupname']?></option>
            <?php 
    }
    ?>
            </select>

</div>
        <div class="create">
             <button type="submit"><b>Add</b></button>
        </div>
    </form>
    </div>
    </div>
</body>
</html>