<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$home_link = 'login.php'; // default link if not logged in
$is_logged_in = false;

if (isset($_SESSION['role'])) {
    $is_logged_in = true;
    if ($_SESSION['role'] === 'admin') {
        $home_link = 'admin_menu.php';
    } elseif ($_SESSION['role'] === 'user') {
        $home_link = 'menu.php';
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
</head>
<body>
    <header class="header">
        <?php if($is_logged_in){?>
    <a href="<?php echo $home_link; ?>" class="home-button"><b>Home</b></a>
    <?php } ?>
        </header>
</body>
</html>