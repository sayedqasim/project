<?php
    if (!isset($_SESSION['email']) || $_SESSION['usertype']!='customer') {
        echo "<a href='$htmlpath'.'index.php'>Main Page</a><br/>";
        die("Unauthorized access, you must be logged in to use this page.");
    }
 ?>
