<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}

if (isset($_GET['activityid'])) {
    $activityId = intval($_GET['activityid']);

    // Prepared statement to delete the activity
    $stmt = $con->prepare("DELETE FROM `activity` WHERE `activityid` = '$activityId'");

    if ($stmt->execute()) {
        // Redirect to the activity list page after deletion
        echo "<script> alert('Activity Deleted')
        document.location.href = 'manage_act.php';
        </script>";
        exit;
    } else {
        echo "Error deleting activity.";
    }
    
    $stmt->close();
} else {
    echo "Invalid request.";
}

$con->close();
?>

