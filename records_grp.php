<?php 
include("db.php");
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_data'])) {
    header("location: login.php");
    exit;
}

$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : '';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

if ($groupid) {
    // Fetch users associated with this activity and their attendance status
    $sql = "SELECT user.userid, user.username, user.groupid as user_groupid, `group`.groupname
            FROM `group`
            LEFT JOIN `user` ON `group`.groupid = user.groupid
            WHERE `group`.groupid = ?";
if ($searchQuery) {
    $sql .= " AND (user.userid LIKE ? OR user.username LIKE ?)";
    $searchQueryParam = "%" . $searchQuery . "%";
    $stmt = $con->prepare($sql);
    $stmt->execute([$groupid, $searchQueryParam, $searchQueryParam]);

}else{
    $stmt = $con->prepare($sql);
    $stmt->execute([$groupid]);
}    
    $result = $stmt->get_result();

    $sql_group = "SELECT * FROM `group` WHERE `groupid` = ?";
    $stmt_group = $con->prepare($sql_group);
    $stmt_group->execute([$groupid]);
    $group_result = $stmt_group->get_result();
    $group = $group_result->fetch_assoc();

    // Fetch all groups for the dropdown
    $sql_all_groups = "SELECT * FROM `group`";
    $stmt_all_groups = $con->prepare($sql_all_groups);
    $stmt_all_groups->execute();
    $all_groups_result = $stmt_all_groups->get_result();
    $all_groups = [];
    while($row = $all_groups_result->fetch_assoc()) {
    $all_groups[] = $row;
    }
} else {
    $group = NULL;
    $all_groups = [];
}

// Handle form submission to update group
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['users'])) {
    $users = $_POST['users'];

    foreach ($users as $userid => $new_groupid) {
        // Update the user's group in the database
        $sql_update = "UPDATE `user` SET `groupid` = ? WHERE `userid` = ?";
        $stmt_update = $con->prepare($sql_update);
        $stmt_update->execute([$new_groupid, $userid]);
    }        

        echo "<script>alert('Users\' Groups Updated');
        window.location.href = 'manage_grp.php';</script>";
    

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
    <?php include('header.php');?>
    <?php include('footer.php');?>
</head>
<body>
   
<div class="whole">
    <h1><?php echo ($group['groupname']);?></h1>
    <form method="GET">
        <div class="search">
        <input type="hidden" name="groupid" value="<?php echo ($groupid); ?>">
        <input type="text" name="search" placeholder="Search..." value="<?php echo ($searchQuery); ?>">
        <button type="submit"><i class='bx bx-search '></i></button>
        </div>
    </form>
    <form action="" method="POST">
    <div class="activities">
        <div class="top-row">
        <p><b>User ID</b></p>
        <p><b>Username</b></p>
        <p><b>Group</b></p>

        </div>
    <div class="scroll-act">    
    <?php
    while($row = $result->fetch_assoc()) {
    ?>
        <div class="status-row">
        <p><b><?php echo $row['userid']; ?></b></p>
        <p><b><?php echo $row['username']; ?></b></p>
                        <select id="status" name="users[<?php echo($row['userid']); ?>]"> 
                            <?php foreach($all_groups as $group_option){ ?>
                                <option value="<?php echo ($group_option['groupid']); ?>" <?php echo $row['user_groupid'] == $group_option['groupid'] ? 'selected' : ''; ?>>
                                    <?php echo($group_option['groupname']); ?>
                                </option>
                            <?php } ?>
                        </select>
            </div>
        <?php }?>
    </div>
    </div>
    <div class="update">
             <button type="submit"><b>Update</b></button>
        </div>
        </form>
</body>
</html>