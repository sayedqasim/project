<?php
    if (!isset($_SESSION['email']) || $_SESSION['usertype']!='branch') {
        echo "<a href='$htmlpath'.'index.php'>Main Page</a><br/>";
        die("Unauthorized access, you must be a branch to use this page.");
    }
 ?>
