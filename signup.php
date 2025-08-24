<?php 
session_start();
include("db.php");
$sql = "SELECT `groupid`, `groupname` FROM `group`";
$result = $con->query($sql);
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $username = $_POST['username'];
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    $groupid = $_POST['groupid'];

    if(!empty($userid) && !empty($password))
    {
        if(($password) >= 8 && ($password) <= 15)
        {
        $query = "INSERT INTO user (userid, password, username, groupid) VALUES ('$userid', '$password', '$username', '$groupid')";
        mysqli_query($con, $query);
        echo "<script> 
        alert ('Successfully Registered')</script>";

    }
    else{
        echo "<script> 
        alert ('Your password must be 8-15 characters long')</script>";
}
    }
    else
    { 
        echo "<script> 
        alert ('Please insert information')</script>";

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="mainstyle.css">
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
<?php
            $sql = "SELECT * FROM `user` WHERE 1 ";
            ?>
    <div class="wrapper">
        <form method="POST">
        <h1>Sign Up</h1>
        <div class="box2">
            <input type="text" placeholder="Username" name="username" required>
            
        </div>
        <div class="box2">
            <input type="text" placeholder="User ID (No more than 20 characters)" name="userid" required>

        </div>
        <div class="box2">
            <input type="password" placeholder="Password (Must be 8-15 characters)" name="password" required>

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
        
        <div>
            <button type="submit" class="btn">
                <img src="img/button3.png" alt="">

            </button>
        </div>
        <div class="back">
        <p>Already have an account?
            <a href="#" onclick="location.href=`login.php`">Log In</a>
        </p>

    </div>
</body>
</html>