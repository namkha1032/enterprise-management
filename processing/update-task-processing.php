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
    $tid = $_GET['tid'];
    $deid = $_SESSION['department'];
    $title = validate($_POST['title']);
    $description = validate($_POST['description']);
    $userid = validate($_POST['userid']);
    $deadline = validate($_POST['deadline']);
    $sql = "UPDATE task SET title = '$title' WHERE taskid='$tid'";
    $conn->query($sql);
    $sql = "UPDATE task SET description = '$description' WHERE taskid='$tid'";
    $conn->query($sql);
    $sql = "UPDATE task SET userid = '$userid' WHERE taskid='$tid'";
    $conn->query($sql);
    $sql = "UPDATE task SET deadline = '$deadline' WHERE taskid='$tid'";
    $conn->query($sql);
    header("location: ./index.php?page=task");
}
