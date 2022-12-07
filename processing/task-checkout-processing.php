
<?php
function validate($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$target_dir = "./files_submit/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
require_once "./database.php";
$tid = $_GET['tid'];
$sql = "UPDATE task SET status = 'pending' WHERE taskID='$tid'";
$conn->query($sql);
$sql = "UPDATE task SET checkoutDate = NOW() WHERE taskID='$tid'";
$conn->query($sql);
$path = "../files_submit/" . htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
$sql = "UPDATE task SET submitFile = '$path' WHERE taskID='$tid'";
$conn->query($sql);
header("location: ./index.php?page=task");

?>