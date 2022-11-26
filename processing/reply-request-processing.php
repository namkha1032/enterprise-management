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
    $rep = $_GET['rep'];
    $rid = $_GET['rid'];
    $sql="UPDATE request SET status = '$rep' WHERE requestid='$rid'";
    $conn->query($sql);
    $today = date('Y-m-d');
    if($rep == 'pending')
        $today = "0000-00-00";
    $sql="UPDATE request SET datedecided = '$today' WHERE requestid='$rid'";
    $conn->query($sql);
    header("location: ./index.php?page=request");
}
