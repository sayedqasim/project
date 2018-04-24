<?php
    if (!isset($_SESSION['email']) || $_SESSION['usertype']!='manager') {
        echo "<a href='$htmlpath'.'index.php'>Main Page</a><br/>";
        die("Unauthorized access, you must be a manager to use this page.");
    }
 ?>
