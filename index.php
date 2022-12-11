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
        if (strpos($pageName, 'processing')){
            require "./processing/${pageName}.php";
        }
        else{
            require "./pages/${pageName}.php";
        }
    }
    else{
        header("location: ./index.php?page=taskmanage");
    }
}