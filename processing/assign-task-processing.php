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
    $deid = $_SESSION['department'];
    echo $deid;
    echo "<br>";
    $title = validate($_POST['title']);
    echo $title;
    echo "<br>";
    $description = validate($_POST['description']);
    echo $description;
    echo "<br>";
    $userid = $_GET['uid'];
    echo $userid;
    echo "<br>";
    $deadline = validate($_POST['deadline']);
    echo $deadline;
    echo "<br>";
    $sql = "INSERT INTO task (title, description, userid, departmentid, deadline)
                VALUES ('$title', '$description', '$userid','$deid', '$deadline')";
    $conn->query($sql);
    header("location: ./index.php?page=task");
}
