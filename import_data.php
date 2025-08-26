<?php
include("db.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_data'])) {
    header("location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $file = $_FILES['file'];

    // Check if the file is a CSV
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    if ($fileType != 'csv') {
        echo "<script>alert('Please upload a valid CSV file.');</script>";
        exit;
    }

    // Move the uploaded file to a directory
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        echo "<script>alert('The file " . htmlspecialchars(basename($file['name'])) . " has been uploaded.');</script>";

        // Process the CSV file and insert into the database
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $con->begin_transaction();
            try {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($type == 'user') {
                        // Skip empty rows or rows with invalid data
                        if (count($data) != 4) {
                            continue;
                        }
                        $userid = $data[0];
                        $username = $data[1];
                        $password = $data[2];
                        $groupid = $data[3];

                        $stmt = $con->prepare("INSERT INTO user (userid, username, password, groupid) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$userid, $username, $password, $groupid]);
                        
                    } elseif ($type == 'group') {
                        // Skip empty rows or rows with invalid data
                        if (count($data) != 1) {
                            continue;
                        }
                        $groupname = $data[0];

                        $stmt = $con->prepare("INSERT INTO `group` (groupname) VALUES (?)");
                        $stmt->execute([$groupname]);
                    }
                }
                $con->commit();
                echo "<script>alert('CSV data has been successfully imported into the database.');</script>";
            } catch (Exception $e) {
                $con->rollback();
                echo "<script>alert('Failed to import CSV data into the database.');</script>";
            }
            fclose($handle);
        } else {
            echo "<script>alert('Error opening the file.');</script>";
        }
    } else {
        echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mainstyle.css">
    <?php include('header.php'); ?>
    <?php include('footer.php'); ?>
    <title>Import Data</title>
</head>
<body>
    <div class="whole">
        <h1>Import Data</h1>
        <div class="wrapper">
            <form method="post" enctype="multipart/form-data">
                <div class="dropbox">
                    <select name="type" id="drop">
                        <option value="user">User</option>
                        <option value="group">Group</option>
                    </select>
                </div>
                <div class="activity-image">
                    <label for="file">Please choose a data file (.csv only):</label>
                    <input type="file" name="file" id="file" accept=".csv">
                </div>
                <div class="submit">
                    <button type="submit"><b>Import</b></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>