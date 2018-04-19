<?php
    function generateNavElements($arrayOfElements,$htmlpath){
        foreach ($arrayOfElements as $visible => $link) {
        echo "  <li class='nav-item'>
                    <a class='nav-link' href='$htmlpath$link'>$visible</a>
                </li>
             ";
         }
    }
    function generateDropElements($arrayOfDrop,$htmlpath){
        foreach ($arrayOfDrop as $visible => $link) {
            echo "  <li class='nav-item dropdown'>
                        <a class='nav-link dropdown-toggle' href='#' id='navbarDropdownBlog' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                            $visible
                        </a>
                        <div class='dropdown-menu dropdown-menu-right' aria-labelledby='navbarDropdownBlog'>
                ";
                foreach ($link as $visible1 => $link1) {
                    echo "<a class='dropdown-item' href='$htmlpath$link1'>$visible1</a>";
                }
                echo "  </div>
                    </li>
                     ";
         }
    }
?>
