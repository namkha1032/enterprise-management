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
  $deid = $_SESSION['departID'];
  $uid = $_SESSION['employeeID'];
  $title = validate($_POST['title']);
  $description = validate($_POST['description']);
  $type = $_GET['type'];
  //
  $amount = '';
  $datestart = '';
  $dateend = '';
  //
  $sql = "INSERT INTO request (title, description,type, lowerID)
                      VALUES ('$title', '$description','$type', '$uid')";
  $conn->query($sql);

  $sql = "SELECT requestID FROM request ORDER BY requestID DESC";
  $requestID = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)['0']['requestID'];

  echo $requestID . "<br>";

  if ($type == 'absence') {
    $datestart = validate($_POST['date_start_absence']);
    $dateend = validate($_POST['date_end_absence']);
    $sql = "INSERT INTO request_absence (absenceID, date_start_absence,date_end_absence)
                      VALUES ('$requestID', '$datestart','$dateend')";
    $conn->query($sql);
  }
  if ($type == 'salary') {
    $amount = validate($_POST['amount']);
    $sql = "INSERT INTO request_salary (salaryID, amount)
                      VALUES ('$requestID', '$amount')";
    $conn->query($sql);
  }

  // $sql = "INSERT INTO request (title, description, userid, departmentid)
  //             VALUES ('$title', '$description', '$uid','$deid')";
  // $conn->query($sql);
  header("location: ./index.php?page=requestme");
}
