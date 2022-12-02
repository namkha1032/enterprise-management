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
    $emid = $_GET['emid'];
    $address = validate($_POST['address']);
    $phone = validate($_POST['phone']);
    $salary = validate($_POST['salary']);
    $departID = validate($_POST['departID']);
    $sql = "UPDATE employee SET address = '$address' WHERE employeeID='$emid'";
    $conn->query($sql);
    $sql = "UPDATE employee SET phone = '$phone' WHERE employeeID='$emid'";
    $conn->query($sql);
    $sql = "UPDATE employee SET salary = '$salary' WHERE employeeID='$emid'";
    $conn->query($sql);
    $sql = "UPDATE employee SET departID = '$departID' WHERE employeeID='$emid'";
    $conn->query($sql);
    header("location: ./index.php?page=profile&employeeID=$emid");
}
