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
    // INNER JOIN employee ON request.lowerID = employee.employeeID
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
    $lowerID = $_POST['lowerID'];
    echo $lowerID;
    echo "<br>";
    
    $upperID = $_SESSION['employeeID'];
    echo $upperID;
    echo "<br>";
    $deadline = validate($_POST['deadline']);
    echo $deadline;
    echo "<br>";
    $sql = "INSERT INTO task (title, description, lowerID, upperID, deadline)
                VALUES ('$title', '$description', '$lowerID','$upperID', '$deadline')";
    $conn->query($sql);
    header("location: ./index.php?page=taskmanage");
}
