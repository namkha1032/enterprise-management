<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    // Page
    require_once "./database.php";
    require "./components/head.php";
    $deid = $_SESSION['departID'];
    $sql = "UPDATE task SET status = 'overdue' WHERE deadline<NOW()";
    $conn->query($sql);
    $emArray = array();
    $amountAssigned = 0;
    $amountInProgress = 0;
    $amountPending = 0;
    $amountCompleted = 0;
    $amountOverdue = 0;
    $arrayAssigned = array();
    $arrayInProgress = array();
    $arrayPending = array();
    $arrayCompleted = array();
    $arrayOverdue = array();
    if ($_SESSION['role'] == 'head') {
        $sql = "SELECT * FROM employee
                INNER JOIN account ON employee.username = account.username 
                WHERE departID = '$deid' AND account.role = 'officer'";
        $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'assigned' AND account.role = 'officer' AND employee.departID = '$deid'");
        $amountAssigned = $result->num_rows;
        $arrayAssigned = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'in progress' AND account.role = 'officer' AND employee.departID = '$deid'");
        $amountInProgress = $result->num_rows;
        $arrayInProgress = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'pending' AND account.role = 'officer' AND employee.departID = '$deid'");
        $amountPending = $result->num_rows;
        $arrayPending = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'completed' AND account.role = 'officer' AND employee.departID = '$deid'");
        $amountCompleted = $result->num_rows;
        $arrayCompleted = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'overdue' AND account.role = 'officer' AND employee.departID = '$deid'");
        $amountOverdue = $result->num_rows;
        $arrayOverdue = $result->fetch_all(MYSQLI_ASSOC);
    }
    if ($_SESSION['role'] == 'ceo') {
        $sql = "SELECT * FROM employee
            INNER JOIN account ON employee.username = account.username 
            WHERE account.role = 'head'";
        $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'assigned' AND account.role = 'head'");
        $amountAssigned = $result->num_rows;
        $arrayAssigned = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'in progress' AND account.role = 'head'");
        $amountInProgress = $result->num_rows;
        $arrayInProgress = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'pending' AND account.role = 'head'");
        $amountPending = $result->num_rows;
        $arrayPending = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'completed' AND account.role = 'head'");
        $amountCompleted = $result->num_rows;
        $arrayCompleted = $result->fetch_all(MYSQLI_ASSOC);
        $result = $conn->query("SELECT * FROM task
                                    INNER JOIN employee ON task.lowerID = employee.employeeID
                                    INNER JOIN account ON account.username = employee.username
                                    WHERE task.status = 'overdue' AND account.role = 'head'");
        $amountOverdue = $result->num_rows;
        $arrayOverdue = $result->fetch_all(MYSQLI_ASSOC);
    }
?>
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title mb-2">
                <h1 style="display:inline" class="me-4">Task Assignment Section</h1>
                <button style="display:inline" data-bs-toggle="modal" data-bs-target="#assignTask" class="btn btn-primary rounded-pill mb-4">
                    Assign task
                </button>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Chart</h3>
                            </div>
                            <div class="card-body">
                                <div id="chart-visitors-profile"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active navHead" id="home-tab" data-bs-toggle="tab" href="#assignTab" role="tab" aria-controls="home" aria-selected="true">
                                            <h3 class="card-title">Assigned Tasks</h3>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navHead" id="profile-tab" data-bs-toggle="tab" href="#progressTab" role="tab" aria-controls="profile" aria-selected="false">
                                            <h3 class="card-title">In Progress Tasks</h3>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="assignTab" role="tabpanel" aria-labelledby="home-tab">

                                        <table class="table table-hover datatable">
                                            <thead>
                                                <tr>
                                                    <th>Task ID</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($arrayAssigned as $task) {
                                                    $emID = $task['lowerID'];
                                                ?>
                                                    <?php $tid = $task['taskID'] ?>
                                                    <tr>
                                                        <td><?= $task['taskID'] ?></td>
                                                        <td><?= $task['title'] ?></td>
                                                        <td><?= $task['name'] ?></td>
                                                        <td>
                                                            <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskID'] ?>">
                                                                View
                                                            </button>
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger">
                                                                Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal for viewing task -->
                                                    <div class="modal fade" id="viewTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Task info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $task['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Task ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['taskID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Description</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['description'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['lowerID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Assign date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['assignedDate']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Due date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['deadline']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check in day</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkinDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check out date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkoutDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Submitted file</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <a <?php if ($task['status'] !== "assigned" && $task['status'] !== "in progress") echo "href=\"./processing/file-download-processing.php?file=" . $task['submitFile'] . "\""; ?>><input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                                                                                                                                                                                                    else echo "value=\"" . str_replace("../files_submit/", "", $task['submitFile']) . "\" " . "style=\"cursor:pointer; color:blue;\""; ?> /></a>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal for updating task -->
                                                    <div class="modal fade" id="updateTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Update Task</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST" class="form form-horizontal">
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group has-icon-left">
                                                                                    <div class="position-relative">
                                                                                        <input type="text" name="title" class="form-control" value="<?= $task['title'] ?>" required />
                                                                                        <div class="form-control-icon">
                                                                                            <i class="bi-card-list"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Description</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group has-icon-left">
                                                                                    <div class="position-relative">
                                                                                        <textarea id="description" name="description" class="form-control" rows="5" cols="10" style="resize:none;" required><?= $task['description'] ?></textarea>
                                                                                        <div class="form-control-icon">
                                                                                            <i class="bi-list-columns-reverse"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Deadline</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group has-icon-left">
                                                                                    <div class="position-relative">
                                                                                        <input type="date" name="deadline" class="form-control" value="<?= $task['deadline'] ?>" required />
                                                                                        <div class="form-control-icon">
                                                                                            <i class="bi-calendar"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Assign to</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <select name="lowerID" id="lowerID" value="<?= $task['name'] ?>" style="width:100%;">
                                                                                        <?php
                                                                                        foreach ($emArray as $em) {
                                                                                        ?>
                                                                                            <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="progressTab" role="tabpanel" aria-labelledby="profile-tab">
                                        <table class="table table-hover datatable">
                                            <thead>
                                                <tr>
                                                    <th>Task ID</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($arrayInProgress as $task) {
                                                    $emID = $task['lowerID'];
                                                ?>
                                                    <?php $tid = $task['taskID'] ?>
                                                    <tr>
                                                        <td><?= $task['taskID'] ?></td>
                                                        <td><?= $task['title'] ?></td>
                                                        <td><?= $task['name'] ?></td>
                                                        <td>
                                                            <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskID'] ?>">
                                                                View
                                                            </button>
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger">
                                                                Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal for viewing task -->
                                                    <div class="modal fade" id="viewTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Task info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $task['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Task ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['taskID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Description</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['description'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['lowerID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Assign date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['assignedDate']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Due date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['deadline']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check in day</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkinDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check out date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkoutDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Submitted file</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <a <?php if ($task['status'] !== "assigned" && $task['status'] !== "in progress") echo "href=\"./processing/file-download-processing.php?file=" . $task['submitFile'] . "\""; ?>><input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                                                                                                                                                                                                    else echo "value=\"" . str_replace("../files_submit/", "", $task['submitFile']) . "\" " . "style=\"cursor:pointer; color:blue;\""; ?> /></a>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal for updating task -->
                                                    <div class="modal fade" id="updateTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Update Task</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST" class="form form-horizontal">
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group has-icon-left">
                                                                                    <div class="position-relative">
                                                                                        <input type="text" name="title" class="form-control" value="<?= $task['title'] ?>" required />
                                                                                        <div class="form-control-icon">
                                                                                            <i class="bi-card-list"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Description</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group has-icon-left">
                                                                                    <div class="position-relative">
                                                                                        <textarea id="description" name="description" class="form-control" rows="5" cols="10" style="resize:none;" required><?= $task['description'] ?></textarea>
                                                                                        <div class="form-control-icon">
                                                                                            <i class="bi-list-columns-reverse"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Deadline</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group has-icon-left">
                                                                                    <div class="position-relative">
                                                                                        <input type="date" name="deadline" class="form-control" value="<?= $task['deadline'] ?>" required />
                                                                                        <div class="form-control-icon">
                                                                                            <i class="bi-calendar"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Assign to</label>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="form-group">
                                                                                    <select name="lowerID" id="lowerID" value="<?= $task['name'] ?>" style="width:100%;">
                                                                                        <?php
                                                                                        foreach ($emArray as $em) {
                                                                                        ?>
                                                                                            <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Pending Tasks</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>Task ID</th>
                                            <th>Title</th>
                                            <th>Officer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($arrayPending as $task) {
                                            $emID = $task['lowerID'];
                                        ?>
                                            <?php $tid = $task['taskID'] ?>
                                            <tr>
                                                <td><?= $task['taskID'] ?></td>
                                                <td><?= $task['title'] ?></td>
                                                <td><?= $task['name'] ?></td>
                                                <td>
                                                    <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskID'] ?>">
                                                        View
                                                    </button>
                                                    <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                        Update
                                                    </button>
                                                    <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <!-- Modal for viewing task -->
                                            <div class="modal fade" id="viewTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Task info</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="avatar mb-4 d-flex justify-content-center">
                                                                <img src="<?= $task['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                            </div>
                                                            <form class="form form-horizontal">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Task ID</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= $task['taskID'] ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Title</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= $task['title'] ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Description</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= $task['description'] ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Officer ID</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= $task['lowerID'] ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Officer name</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= $task['name'] ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Status</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= $task['status'] ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Assign date</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['assignedDate']), "d/m/Y") ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Due date</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['deadline']), "d/m/Y") ?>" />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Check in day</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned") echo "disabled";
                                                                                                                            else echo "value=\"" . date_format(date_create($task['checkinDate']), "d/m/Y") . "\""; ?> />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Check out date</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                            else echo "value=\"" . date_format(date_create($task['checkoutDate']), "d/m/Y") . "\""; ?> />
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <label>Submitted file</label>
                                                                    </div>
                                                                    <div class="col-md-8 form-group">
                                                                        <a <?php if ($task['status'] !== "assigned" && $task['status'] !== "in progress") echo "href=\"./processing/file-download-processing.php?file=" . $task['submitFile'] . "\""; ?>><input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                                                                                                                                                                                            else echo "value=\"" . str_replace("../files_submit/", "", $task['submitFile']) . "\" " . "style=\"cursor:pointer; color:blue;\""; ?> /></a>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer" <?php if ($task['status'] != "pending") echo "hidden" ?>>
                                                            <a href="./index.php?page=request-rep-processing&rid=<?= $request['requestID'] ?>&rep=accepted&type=<?= $request['type'] ?>" class="btn btn-primary">
                                                                Accept
                                                            </a>
                                                            <a href="./index.php?page=request-rep-processing&rid=<?= $request['requestID'] ?>&rep=rejected" class="btn btn-warning">
                                                                Reject
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-5 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active navHead" id="home-tab" data-bs-toggle="tab" href="#completeTab" role="tab" aria-controls="home" aria-selected="true">
                                            <h3 class="card-title">Completed Tasks</h3>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navHead" id="profile-tab" data-bs-toggle="tab" href="#overdueTab" role="tab" aria-controls="profile" aria-selected="false">
                                            <h3 class="card-title">Overdue Tasks</h3>
                                        </a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="completeTab" role="tabpanel" aria-labelledby="home-tab">
                                        <table class="table table-hover datatable">
                                            <thead>
                                                <tr>
                                                    <th>Task ID</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($arrayCompleted as $task) {
                                                ?>
                                                    <tr>
                                                        <td><?= $task['taskID'] ?></td>
                                                        <td><?= $task['title'] ?></td>
                                                        <td><?= $task['name'] ?></td>
                                                        <td>
                                                            <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskID'] ?>">
                                                                View
                                                            </button>
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger">
                                                                Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal for viewing task -->
                                                    <div class="modal fade" id="viewTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Task info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $task['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Task ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['taskID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Description</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['description'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['lowerID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Assign date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['assignedDate']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Due date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['deadline']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check in day</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkinDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check out date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkoutDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Submitted file</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <a <?php if ($task['status'] !== "assigned" && $task['status'] !== "in progress") echo "href=\"./processing/file-download-processing.php?file=" . $task['submitFile'] . "\""; ?>><input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                                                                                                                                                                                                    else echo "value=\"" . str_replace("../files_submit/", "", $task['submitFile']) . "\" " . "style=\"cursor:pointer; color:blue;\""; ?> /></a>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="overdueTab" role="tabpanel" aria-labelledby="profile-tab">
                                        <table class="table table-hover datatable">
                                            <thead>
                                                <tr>
                                                    <th>Task ID</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($arrayOverdue as $task) {
                                                ?>
                                                    <?php $tid = $task['taskID'] ?>
                                                    <tr>
                                                        <td><?= $task['taskID'] ?></td>
                                                        <td><?= $task['title'] ?></td>
                                                        <td><?= $task['name'] ?></td>
                                                        <td>
                                                            <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskID'] ?>">
                                                                View
                                                            </button>
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger">
                                                                Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <!-- Modal for viewing task -->
                                                    <div class="modal fade" id="viewTask<?= $task['taskID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Task info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $task['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Task ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['taskID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Description</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['description'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['lowerID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $task['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Assign date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['assignedDate']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Due date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($task['deadline']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check in day</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkinDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Check out date</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                    else echo "value=\"" . date_format(date_create($task['checkoutDate']), "d/m/Y") . "\""; ?> />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Submitted file</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <a <?php if ($task['status'] !== "assigned" && $task['status'] !== "in progress") echo "href=\"./processing/file-download-processing.php?file=" . $task['submitFile'] . "\""; ?>><input type="text" class="form-control" readonly <?php if ($task['status'] == "assigned" || $task['status'] == "in progress") echo "disabled";
                                                                                                                                                                                                                                                                                                                    else echo "value=\"" . str_replace("../files_submit/", "", $task['submitFile']) . "\" " . "style=\"cursor:pointer; color:blue;\""; ?> /></a>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Chart</h3>
                            </div>
                            <?php
                            $nameArray = array();
                            $incompletedArray = array();
                            $completedArray = array();
                            $overdueArray = array();
                            foreach ($emArray as $em) {
                                $emid = $em['employeeID'];
                                $assignAmount = $conn->query("SELECT * FROM task WHERE status = 'assigned' AND lowerID = '$emid'")->num_rows;
                                $progressAmount = $conn->query("SELECT * FROM task WHERE status = 'in progress' AND lowerID = '$emid'")->num_rows;
                                $pendingAmount = $conn->query("SELECT * FROM task WHERE status = 'pending' AND lowerID = '$emid'")->num_rows;
                                $completeAmount = $conn->query("SELECT * FROM task WHERE status = 'completed' AND lowerID = '$emid'")->num_rows;
                                $overdueAmount = $conn->query("SELECT * FROM task WHERE status = 'overdue' AND lowerID = '$emid'")->num_rows;
                                array_push($nameArray, $em['name']);
                                array_push($incompletedArray, $assignAmount + $progressAmount + $pendingAmount);
                                array_push($completedArray, $completeAmount);
                                array_push($overdueArray, $overdueAmount);
                            }
                            ?>
                            <div class="card-body">
                                <div id="task-bar-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer>
            <div class="footer clearfix mb-0 text-muted">


            </div>
        </footer>
    </div>
    <div class="modal fade" id="assignTask" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Assign task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./index.php?page=task-assign-processing" method="POST" class="form form-horizontal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Title</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <input type="text" name="title" class="form-control" placeholder="Task title" id="first-name-icon" required autocomplete="off" />
                                        <div class="form-control-icon">
                                            <i class="bi bi-card-heading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Deadline</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <input type="date" name="deadline" class="form-control" placeholder="Deadline" required />
                                        <div class="form-control-icon">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Assign to</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <select name="lowerID" class="form-control" required>
                                            <option disabled selected hidden value="">This task is assigned to...</option>
                                            <?php
                                            foreach ($emArray as $em) {
                                            ?>
                                                <option value="<?= $em['employeeID'] ?>"><?= $em['name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <div class="form-control-icon">
                                            <i class="bi bi-person-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Description</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <textarea name="description" class="form-control" id="first-name-icon" cols="30" rows="5" placeholder="Task description..." style="resize:none;"></textarea>
                                        <div class="form-control-icon">
                                            <i class="bi bi-list-columns-reverse"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    require "./components/foot.php";
}
?>