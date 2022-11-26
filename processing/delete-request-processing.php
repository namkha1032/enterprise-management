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
    $rid = $_GET['rid'];
    $sql="DELETE FROM request WHERE requestid='$rid'";
    $conn->query($sql);
    header("location: ./index.php?page=request");
}
