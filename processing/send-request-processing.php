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
    $uid = $_SESSION['userid'];
    $title = validate($_POST['title']);
    $description = validate($_POST['description']);
    $sql = "INSERT INTO request (title, description, userid, departmentid)
                VALUES ('$title', '$description', '$uid','$deid')";
    $conn->query($sql);
    header("location: ./index.php?page=request");
}
