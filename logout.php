<?php
session_start();
session_unset();
session_destroy();
echo"<script>alert('Successfully Logged Out');
window.location.href = 'index.php';
</script>";
exit;
?>
