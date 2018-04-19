<?php
    session_start();
    $htmlpath='/project/';
    $phppath=realpath('/').'xampp/htdocs/project/';
    $head=$phppath."head.php";
    $navigation=$phppath."structure/header/userNavigation.php";
    $carousel=$phppath."structure/carousel/userCarousel.php";
    $pagecontent=$phppath."structure/body/userContent.php";
    $footer=$phppath."structure/footer/userFooter.php";
    $jsbs=$phppath."jsbs.php";
    if(isset($_SESSION['usertype'])){
        if ($_SESSION['usertype']=='customer'){
            $navigation=$phppath."structure/header/customerNavigation.php";
            $pagecontent=$phppath."structure/body/customerContent.php";
            $footer=$phppath."structure/footer/customerFooter.php";
        }
        elseif ($_SESSION['usertype']=='manager'){
            $navigation=$phppath."structure/header/managerNavigation.php";
            $carousel=$phppath."structure/carousel/managerCarousel.php";
            $pagecontent=$phppath."structure/body/managerContent.php";
            $footer=$phppath."structure/footer/managerFooter.php";
        }
        elseif ($_SESSION['usertype']=='branch'){
            $navigation=$phppath."structure/header/branchNavigation.php";
            $carousel=$phppath."structure/carousel/branchCarousel.php";
            $pagecontent=$phppath."structure/body/branchContent.php";
            $footer=$phppath."structure/footer/branchFooter.php";
        }
        elseif ($_SESSION['usertype']=='admin'){
            $navigation=$phppath."structure/header/adminNavigation.php";
            $carousel=$phppath."structure/carousel/adminCarousel.php";
            $pagecontent=$phppath."structure/body/adminContent.php";
            $footer=$phppath."structure/footer/adminFooter.php";
        }
    }
?>
