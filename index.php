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
        if ($pageName=="assign-task-processing" || $pageName=="finish-task-processing" || $pageName=="delete-task-processing" || $pageName=="update-task-processing"){
            require "./processing/${pageName}.php";
        }
        elseif ($pageName=="send-request-processing" || $pageName=="reply-request-processing" || $pageName=="delete-request-processing" || $pageName=="update-request-processing"){
            require "./processing/${pageName}.php";
        }
        else{
            require "./${pageName}.php";
        }
    }
}