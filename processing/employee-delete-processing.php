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
    $sql="DELETE FROM account WHERE username='$username'";
    $conn->query($sql);
    header("location: ./index.php?page=employee");
}
