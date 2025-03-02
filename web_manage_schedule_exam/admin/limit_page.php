<?php
    $limit = 15; 
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($current_page < 1) {
        $current_page = 1;
    }

    $offset = ($current_page - 1) * $limit;
?>                        