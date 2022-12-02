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
    $sql="UPDATE task SET status = 'in progress' WHERE taskID='$tid'";
    $conn->query($sql);
    $sql="UPDATE task SET checkinDate = NOW() WHERE taskID='$tid'";
    $conn->query($sql);
    header("location: ./index.php?page=task");
}
