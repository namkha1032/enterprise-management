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
    $type = $_GET['type'];
    $title = validate($_POST['title']);
    $description = validate($_POST['description']);
    $sql = "UPDATE request SET title = '$title' WHERE requestID='$rid'";
    $conn->query($sql);
    $sql = "UPDATE request SET description = '$description' WHERE requestID='$rid'";
    $conn->query($sql);
    $today = date('Y-m-d');
    $sql = "UPDATE request SET datesent = '$today' WHERE requestID='$rid'";
    $conn->query($sql);
    if($type == 'absence'){
        $datestart = validate($_POST['date_start_absence']);
        $dateend = validate($_POST['date_end_absence']);
        $sql = "UPDATE request_absence SET date_start_absence = '$datestart' WHERE absenceID='$rid'";
        $conn->query($sql);
        $sql = "UPDATE request_absence SET date_end_absence = '$dateend' WHERE absenceID='$rid'";
        $conn->query($sql);
    }
    if($type == 'salary'){
        $amount = validate($_POST['amount']);
        $sql = "UPDATE request_salary SET amount = '$amount' WHERE salaryID='$rid'";
        $conn->query($sql);
    }
    header("location: ./index.php?page=request");
}
