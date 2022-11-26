<?php
function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require_once "./database.php";
    $tid = $_GET['tid'];
    $sql="UPDATE task SET status = 'finished' WHERE taskid='$tid'";
    $conn->query($sql);
    $today = date('Y-m-d');
    $sql="UPDATE task SET checkoutdate = '$today' WHERE taskid='$tid'";
    $conn->query($sql);
    header("location: ./index.php?page=task");
}
