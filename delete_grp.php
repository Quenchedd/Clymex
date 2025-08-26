<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}

if (isset($_GET['groupid'])) {
    $groupid = intval($_GET['groupid']);

    // Prepared statement to delete the activity
    $stmt = $con->prepare("DELETE FROM `group` WHERE `groupid` = '$groupid'");

    if ($stmt->execute()) {
        // Redirect to the activity list page after deletion
        echo "<script> alert('Group Deleted')
        document.location.href = 'manage_grp.php';
        </script>";
        exit;
    } else {
        echo "Error deleting group.";
    }
    
    $stmt->close();
} else {
    echo "Invalid request.";
}

$con->close();
?>
