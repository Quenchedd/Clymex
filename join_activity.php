<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve userid and activityid from POST data
    $userid = $_POST['userid'];
    $activityid = $_POST['activityid'];

    $query = "SELECT * FROM attendance WHERE userid = '$userid' AND activityid = '$activityid'";

    $result = mysqli_query($con, $query); 
 
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['status'] == 'Present') {
            // The user has already joined this activity
            echo "<script>alert('You have already joined this activity');
            document.location.href = 'activitylist.php';
            </script>";
            exit;
        } else {
            // The user has not joined yet or is marked as absent, update the status to 'Present'
            $updateQuery = "UPDATE attendance SET status = 'Present' WHERE userid = '$userid' AND activityid = '$activityid'";
            if (mysqli_query($con, $updateQuery)) {
                echo "<script>alert('Successfully Joined');
                document.location.href = 'activitylist.php';
                </script>";
                exit;
            } else {
                echo "<script>alert('Fail to join activity');
                document.location.href = 'activitylist.php';
                </script>";
                exit;
            }
        }
    } 
} else {
    // Redirect to an error page or display an error message for invalid request
    echo "<script>alert('Invalid request');
    document.location.href = 'activitylist.php';
    </script>";
    exit;
}
?>