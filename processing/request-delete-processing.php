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
    $senderID = $conn->query("SELECT * FROM request WHERE requestID = '$rid'")->fetch_all(MYSQLI_ASSOC)[0]['lowerID'];
    $sql="DELETE FROM request WHERE requestID='$rid'";
    $conn->query($sql);
    if ($senderID == $_SESSION['employeeID']){
        header("location: ./index.php?page=requestme");
    }
    else
    {
        header("location: ./index.php?page=requestmanage");
    }
}
