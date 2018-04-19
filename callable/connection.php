<?php
    $username="root";
    $pw="";
    $db = new PDO('mysql:host=localhost;dbname=foodordering;charset=utf8', $username, $pw);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
