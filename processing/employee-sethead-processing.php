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
    $username = $_GET['username'];
    $departID = $_GET['depart'];
    $sql = "SELECT * FROM employee
    INNER JOIN account ON employee.username = account.username 
    WHERE account.role = 'head' AND employee.departID = '$departID'";
    $oldhead = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0]['username'];
    $sql = "UPDATE account SET role = 'officer' WHERE username = '$oldhead' AND role='head'";
    $conn->query($sql);
    $sql = "UPDATE account SET role = 'head' WHERE username = '$username'";
    $conn->query($sql);
    header("location: ./index.php?page=employee");
}
