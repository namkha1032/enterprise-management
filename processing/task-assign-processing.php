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
    // $deid = $_SESSION['departID'];
    // echo $deid;
    // echo "<br>";

    
    // $sql = "SELECT * FROM request 
    // INNER JOIN employee ON request.officerID = employee.employeeID
    // LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
    // LEFT JOIN request_salary ON request.requestID = request_salary.salaryID";
    // $requestArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    // echo "<pre>";
    // print_r($requestArray);
    // echo "</pre>";
    
    
    
    
    
    $title = validate($_POST['title']);
    echo $title;
    echo "<br>";
    $description = validate($_POST['description']);
    echo $description;
    echo "<br>";
    $officerID = $_POST['officerID'];
    echo $officerID;
    echo "<br>";
    
    $headID = $_SESSION['employeeID'];
    echo $headID;
    echo "<br>";
    $deadline = validate($_POST['deadline']);
    echo $deadline;
    echo "<br>";
    $sql = "INSERT INTO task (title, description, officerID, headID, deadline)
                VALUES ('$title', '$description', '$officerID','$headID', '$deadline')";
    $conn->query($sql);
    header("location: ./index.php?page=task");
}
