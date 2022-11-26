<?php
function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "./database.php";
    $rid = $_GET['rid'];
    $title = validate($_POST['title']);
    $description = validate($_POST['description']);
    $sql = "UPDATE request SET title = '$title' WHERE requestid='$rid'";
    $conn->query($sql);
    $sql = "UPDATE request SET description = '$description' WHERE requestid='$rid'";
    $conn->query($sql);
    $today = date('Y-m-d');
    $sql = "UPDATE request SET datesent = '$today' WHERE requestid='$rid'";
    $conn->query($sql);
    header("location: ./index.php?page=request");
}
