<?php 
include("db.php");
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_data'])) {
    header("location: login.php");
    exit;
}

$admin_data = $_SESSION['admin_data'];
$adminId = $admin_data['adminid'];

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $groupname = $_POST['groupname'];
   
    if(!empty($groupname))
    {
        $query = "INSERT INTO `group` (`groupname`) VALUES (?)";
        $stmt = $con->prepare($query);
        $stmt->execute([$groupname]);

        echo "<script> 
        alert ('Group Added');
        window.location.href ='manage_grp.php';
        </script>";

    }
    else
    { echo "<script> 
        alert ('Please insert information')</script>";

    }
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
   <h1>Add Group</h1>
    <div class="wrapper">
        <form method="POST">
        <div class="group-name">
            <input type="text" placeholder="Group Name" name="groupname">
    </div>
    <div class="create">
             <button type="submit"><b>Add</b></button>
        </div>
    </div> 
    </div>
</body>
</html>