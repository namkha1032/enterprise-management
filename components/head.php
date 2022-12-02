<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
</head>

<body>
    <?php
    if (isset($_SESSION['username'])) {
    ?>
        <nav>
            <a href="./index.php?page=employee" <?php if ($_SESSION['role'] == 'officer') echo "hidden" ?>>
                Employee</a>
            <a href="./index.php?page=task" <?php if ($_SESSION['role'] == 'admin') echo "hidden" ?>>
                Task</a>
            <a href="./index.php?page=request" <?php if ($_SESSION['role'] == 'admin') echo "hidden" ?>>
                Request</a>
            <a href="./index.php?page=profile&employeeID=<?= $_SESSION['employeeID'] ?>" <?php if ($_SESSION['role'] == 'admin') echo "hidden" ?>>
                Profile</a>
            <a href="./index.php?page=logout">Log out</a>
            <span>Hello <?= $_SESSION['name'] ?></span>
        </nav>
    <?php } ?>