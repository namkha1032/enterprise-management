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
    echo $tid . "<br>";
    $deid = $_SESSION['departID'];
    echo $deid . "<br>";
    $title = validate($_POST['title']);
    echo $title . "<br>";
    $description = validate($_POST['description']);
    echo $description . "<br>";
    $officerID = validate($_POST['officerID']);
    echo $officerID . "<br>";
    $headUsername = $_SESSION['username'];
    $sql = "SELECT * FROM employee
                            INNER JOIN account ON employee.username = account.username WHERE employee.username = '$headUsername'";
    $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    $headID = $emArray[0]['employeeID'];
    echo $headID;
    echo "<br>";
    $deadline = validate($_POST['deadline']);
    echo $deadline;
    echo "<br>";
    $sql = "UPDATE task SET title = '$title' WHERE taskID='$tid'";
    $conn->query($sql);
    $sql = "UPDATE task SET description = '$description' WHERE taskID='$tid'";
    $conn->query($sql);
    $sql = "UPDATE task SET officerID = '$officerID' WHERE taskID='$tid'";
    $conn->query($sql);
    $sql = "UPDATE task SET deadline = '$deadline' WHERE taskID='$tid'";
    $conn->query($sql);
    header("location: ./index.php?page=task");
}
