<?php
session_start();
date_default_timezone_set("Asia/Ho_Chi_Minh");
if (!isset($_SESSION['username'])){
	header("Location: ./login.php");
	exit();
}
else{
    if (isset($_GET['page'])){
        $pageName = strtolower($_GET['page']);
        if ($pageName=="task-assign-processing" || $pageName=="task-checkin-processing" || $pageName=="task-checkout-processing" || $pageName=="task-delete-processing" || $pageName=="task-update-processing"){
            require "./processing/${pageName}.php";
        }
        elseif ($pageName=="request-send-processing" || $pageName=="request-rep-processing" || $pageName=="request-delete-processing" || $pageName=="request-update-processing"){
            require "./processing/${pageName}.php";
        }
        elseif ($pageName=="employee-insert-processing" || $pageName=="employee-sethead-processing" || $pageName=="employee-delete-processing" || $pageName=="employee-update-processing" ){
            require "./processing/${pageName}.php";
        }
        else{
            require "./${pageName}.php";
        }
    }
    else{
        header("location: ./index.php?page=task");
    }
}