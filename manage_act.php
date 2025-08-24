<?php 
session_start();
include("db.php");

if(!isset($_SESSION['admin_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}
$admin_data = $_SESSION['admin_data'];

$sql = "SELECT * FROM `activity`";

// Check if a search query is provided
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchQuery) {
    $sql .= " WHERE activity.title LIKE ?";
    $searchQueryParam = "%" . $searchQuery . "%";
    $stmt = $con->prepare($sql);
    $stmt->execute([$searchQueryParam]);
    $result = $stmt->get_result();
}
else{
    $result = $con->query($sql);
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
    <script>
        function confirmDelete(activityId) {
            if (confirm("Are you sure you want to delete this activity?")) {
                // Redirect to delete.php with the activity ID to handle deletion
                document.location.href = 'delete.php?activityid=' + activityId;
            }
        }
    </script>
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
    <div class="whole">
    <h1>Activity List</h1>
    <form method="GET">
        <div class="search">
        <input type="hidden" name="activityid" value="<?php echo ($activityid); ?>">
        <input type="text" name="search" placeholder="Search..." value="<?php echo ($searchQuery); ?>">
        <button type="submit"><i class='bx bx-search '></i></button>
        </div>
    </form>
    <button class="add" onclick="location.href='create_act.php'"><i class='bx bx-add-to-queue bx-sm'></i></button>
    <div class="activities">
        <div class="list-row">
        <p><b>Activty Name</b></p>
        <p><b>Time</b></p>
        <p><b>Location</b></p>

        </div>
    <div class="scroll-act">    
    <?php
    
    while($row = $result->fetch_assoc()) {
    ?>
        <div class="manage-row">
        <p><b><?php echo $row['title']; ?></b></p>
        <p><b><?php echo $row['time']; ?></b></p>
        <p><b><?php echo $row['location']; ?></b></p>
        <button onclick="location.href='records_act.php? activityid=<?php echo urlencode($row['activityid']); ?>'"><i class='bx bxs-show bx-sm'></i></button>
        <button onclick="location.href='edit_act.php? activityid=<?php echo urlencode($row['activityid']); ?>'"><i class='bx bxs-edit bx-sm'></i></button>
        <button onclick="confirmDelete(<?php echo $row['activityid']; ?>)"><i class='bx bxs-trash-alt bx-sm'></i></button>
        </div>
        <?php }?>
        </div>
    </div>
    </div>
</body>
</html>