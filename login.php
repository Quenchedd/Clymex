<?php 
session_start();
include("db.php");
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $userid = $_POST['userid'];
    $password = $_POST['password'];
    

    if(!empty($userid) && !empty($password)) {
        $query_user = "SELECT * FROM `user` WHERE userid = '$userid' LIMIT 1";
        $query_admin = "SELECT * FROM `admin` WHERE adminid = '$userid' LIMIT 1";

        $result_user = mysqli_query($con, $query_user);
        $result_admin = mysqli_query($con, $query_admin);
        
        if($result_user && mysqli_num_rows($result_user) > 0) {
            $user_data = mysqli_fetch_assoc($result_user);
            
            if($user_data['password'] == $password) {
                $_SESSION['user_data'] = $user_data;
                $_SESSION['role'] = 'user';
                $_SESSION['username'] = $user_data['username']; 
                echo "<script>alert('Welcome " . ($user_data['username']) . "');
                document.location.href = 'menu.php';
            </script>";
                exit;
            } else {
                echo "<script> alert('Incorrect password')</script>";
            }
        } elseif ($result_admin && mysqli_num_rows($result_admin) > 0) {
            $admin_data = mysqli_fetch_assoc($result_admin);
            
            if($admin_data['password'] == $password) {
                $_SESSION['admin_data'] = $admin_data;
                $_SESSION['role'] = 'admin';
                $_SESSION['username'] = $admin_data['username']; 
                echo "<script>alert('Welcome " . ($admin_data['username']) . "');
                 document.location.href = 'admin_menu.php';
                </script>";
                exit;
            } else {
                echo "<script> alert('Incorrect password')</script>";
            }
        } else {
            echo "<script> alert('User not found')</script>";
        }
    } else {
        echo "<script> alert('Please enter both User ID and Password')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="mainstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
    <div class="wrapper">
        <form method="POST">
            <h1>Log In</h1>
            <div class="box">
                <input type="text" placeholder="User ID" name="userid" required>
                <i class="bx bxs-user"></i>

    </div>
    <div class="box">
        <input type="password" placeholder="Password" name="password" required>
        <i class="bx bxs-lock-alt"></i>

    </div>
    <div>
        <button type="submit" class="btn" >
        <img src="img/button2.png">
        </button>
    </div>
    <div class="sign-up">
        <p>Not a member?
            <a href="#" onclick="location.href=`signup.php`">Sign up</a>
        </p>
        </form>
    </div>
    </div>
</body>
</html>