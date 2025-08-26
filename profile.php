<?php 
session_start();
include("db.php");
// Check if user is logged in
if(!isset($_SESSION['user_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}
$user_data = $_SESSION['user_data'];
$userId = $user_data['userid'];
$image = $user_data['image'];

if (isset($_FILES["image"])) {
    $imageName = $_FILES["image"]["name"];
    $imageSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

    // Image validation
    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    if (!in_array($imageExtension, $validImageExtension)) {
        echo "<script>
                alert('Invalid Image Extension');
                
              </script>";
    } elseif ($imageSize > 1200000) {
        echo "<script>
                alert('Image Size Is Too Large');
                
              </script>";
    } else {
        $newImageName = $imageName;
        $query = "UPDATE `user` SET `image` = '$newImageName' WHERE userid = '$userId'";
        
        if (mysqli_query($con, $query)) {
            move_uploaded_file($tmpName, 'img/' . $newImageName);
            $_SESSION['user_data']['image'] = $newImageName; // Update session image data
            echo "<script>
                    alert('Profile Picture Updated');
                  </script>";
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}

$sql = "SELECT a.title, a.time, a.points 
        FROM attendance AS att
        INNER JOIN activity AS a ON att.activityid = a.activityid
        WHERE att.userid = '$userId' AND att.status = 'Present'";
$result = $con->query($sql);

// Check if query was successful
if (!$result) {
    die("Error executing query: " . $con->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>

    <div class="profile">
       <h1>User Profile</h1>
          <div class="full">
            <div class="user_info">
    <div class="pic">  
        
        
       <img src="img/<?php echo $user_data['image']?>">
       
       </div>
        <div class="user_text">
          <h2><?php echo $user_data['username'];?></h2>
          <p>@<?php echo $user_data['userid']; ?></p>
          </div> 
          <div class="dropdown-container">
          <button onclick="toggleDropdown()" class="three_dots"><i class='bx bx-dots-vertical-rounded bx-md'></i></button>
          <div id="myDropdown" class="dropdown-content">
          <form id = "form" action="" enctype="multipart/form-data" method="post">
                            <label for="image">Edit Profile Picture</label>
                            <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                            </form>
                </div>
            </div>
        </div>
        <div class="table-full">
        <div class="table-top">
        <p><b>Activty Name</b></p>
        <p><b>Time</b></p>
        <p><b>Points</b></p>

        </div>
        <div class="scroll">
    <?php
    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    ?>
    
        <div class="table-bottom">
        <p><b><?php echo $row['title']; ?></b></p>
        <p><b><?php echo $row['time']; ?></b></p>
        <p><b><?php echo $row['points']; ?></b></p>
        </div>
        <?php
    }   
    } else {
        // No activities attended for the user
        echo "<p>......................................No activities attended.......................................</p>";
    }
    ?>
        </div>
        
    
    
    </div>
    </div>    
</div>
<script src="script.js"></script> 
</body>
</html>