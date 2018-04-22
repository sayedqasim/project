<?php
    if (!isset($_SESSION['email']) || !$_SESSION['usertype']=='admin') {
        echo "<a href='$htmlpath'.'index.php'>Main Page</a><br/>";
        die("Unauthorized access, you must be an admin to use this page.");
    }
?>
