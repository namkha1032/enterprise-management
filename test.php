<?php 
        if ('2022-11-15' < date("Y-m-d")){
            echo "overdue";
        }
        if ('2022-11-15' > date("Y-m-d")){
            echo "stillgood";
        }
    ?>