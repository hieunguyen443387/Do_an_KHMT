<div class="pagination">
    <?php
        $row_trang = $result_trang->fetch_assoc();
        $num_rows = $row_trang['total']; 
        $total_pages = ceil($num_rows / $limit);  

        if ($total_pages > 1) {
            if ($current_page > 1) {
                echo '<a href="?page=' . ($current_page - 1) . '"><</a>';
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $current_page) {
                    echo '<a class="active">' . $i . '</a>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            if ($current_page < $total_pages) {
                echo '<a href="?page=' . ($current_page + 1) . '">></a>';
            }
        }
    ?>
</div>