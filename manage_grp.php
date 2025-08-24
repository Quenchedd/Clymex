<?php 
session_start();
include("db.php");

if(!isset($_SESSION['admin_data'])) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}
$admin_data = $_SESSION['admin_data'];

$sql = "SELECT `group`.groupid, `group`.groupname, COUNT(user.userid) as member_count
        FROM `group`
        LEFT JOIN user ON `group`.groupid = user.groupid";

// Check if a search query is provided
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchQuery) {
    $sql .= " WHERE group.groupname LIKE ?";
    $searchQueryParam = "%" . $searchQuery . "%";
    $sql .= " GROUP BY `group`.groupid, `group`.groupname";
    $stmt = $con->prepare($sql);
    $stmt->execute([$searchQueryParam]);
    $result = $stmt->get_result();
}
else{
    $sql .= " GROUP BY `group`.groupid, `group`.groupname";
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
        function confirmDelete(groupid) {
            if (confirm("Are you sure you want to delete this group?")) {
                // Redirect to delete_grp.php with the group ID to handle deletion
                document.location.href = 'delete_grp.php? groupid=' + groupid;
            }
        }
    </script>
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
<div class="whole">
    <h1>Group List</h1>
    <form method="GET">
        <div class="search">
        <input type="hidden" name="groupid" value="<?php echo ($groupid); ?>">
        <input type="text" name="search" placeholder="Search..." value="<?php echo ($searchQuery); ?>">
        <button type="submit"><i class='bx bx-search '></i></button>
        </div>
    </form>
    <button class="add-2" onclick="location.href='create_grp.php'"><i class='bx bx-add-to-queue bx-sm'></i></button>
    <div class="activities-2">
        <div class="list-row-2">
        <p><b>Group Name</b></p>
        <p><b>Members</b></p>

        </div>
    <div class="scroll-grp">    
    <?php
    
    while($row = $result->fetch_assoc()) {
    ?>
        <div class="manage-row-2">
        <p><b><?php echo $row['groupname']; ?></b></p>
        <p><b><?php echo $row['member_count']; ?></b></p>
        <button onclick="location.href='records_grp.php? groupid=<?php echo urlencode($row['groupid']); ?>'"><i class='bx bxs-show bx-sm'></i></button>
        <button onclick="location.href='edit_grp.php? groupid=<?php echo urlencode($row['groupid']); ?>'"><i class='bx bxs-edit bx-sm'></i></button>
        <button onclick="confirmDelete(<?php echo $row['groupid']; ?>)"><i class='bx bxs-trash-alt bx-sm'></i></button>
        </div>
        <?php }?>
        </div>
    </div>
    </div>
</body>
</html>