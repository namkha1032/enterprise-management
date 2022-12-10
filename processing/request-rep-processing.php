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
    $rep = $_GET['rep'];
    $rid = $_GET['rid'];
    $type = $_GET['type'];
    $sql = "UPDATE request SET status = '$rep' WHERE requestID='$rid'";
    $conn->query($sql);
    $today = date('Y-m-d');
    if ($rep == 'pending')
        $today = "";
    $sql = "UPDATE request SET datedecided = '$today' WHERE requestID='$rid'";
    $conn->query($sql);
    if ($type == 'salary' && $rep == 'accepted') {
        $sql = "SELECT * FROM request
                LEFT JOIN employee ON request.lowerID = employee.employeeID
                LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                LEFT JOIN request_salary ON request.requestID = request_salary.salaryID
                WHERE request.requestID = '$rid'";
        $em= $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $emid = $em[0]['employeeID'];
        $emsala = (int)$em[0]['salary'] + (int)$em[0]['amount'];
        echo "<pre>";
        print_r($em);
        echo $emid . "<br>";
        echo $emsala . "<br>";
        echo "</pre>";
        $sql = "UPDATE employee SET salary = '$emsala' WHERE employeeID='$emid'";
        $conn->query($sql);
    }
    
    header("location: ./index.php?page=requestmanage");
}
