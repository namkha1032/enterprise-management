<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    // Page
    require_once "./database.php";
    require "./components/head.php";
    $deid = $_SESSION['departID'];
    $emid = $_SESSION['employeeID'];
    $sql = "UPDATE task SET status = 'overdue' WHERE deadline<NOW()";
    $conn->query($sql);
    $sql = "SELECT * FROM employee
        INNER JOIN account ON employee.username = account.username WHERE departID = '$deid'";
    $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title mb-2">
                <h1 style="display:inline" class="me-4">My Tasks</h1>
                <div class="mb-4">
                </div>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Calendar</h3>
                            </div>
                            <div class="card-body" style="text-align:center">
                                <iframe src="https://calendar.google.com/calendar/embed?height=300&wkst=2&bgcolor=%23ffffff&ctz=Asia%2FHo_Chi_Minh&showTitle=0&showDate=1&showPrint=0&showTabs=0&showCalendars=0&showTz=1" style="border-width:0" width="380" height="300" frameborder="0" scrolling="no"></iframe>
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
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.lowerID = employee.employeeID WHERE task.status = 'assigned' AND task.lowerID = '$emid'";
                                        $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                        ?>
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
                                                foreach ($taskArray as $task) {
                                                    $lowerID = $task['lowerID'];
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
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload formFile" class="form-control" <?php if ($task['status'] != "in progress") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress") echo "hidden" ?>>
                                                                            Check out
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="progressTab" role="tabpanel" aria-labelledby="profile-tab">
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.lowerID = employee.employeeID WHERE task.lowerID = '$emid' AND task.status = 'in progress'";
                                        $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                        ?>
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
                                                foreach ($taskArray as $task) {
                                                    $lowerID = $task['lowerID'];
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
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload formFile" class="form-control" <?php if ($task['status'] != "in progress") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress") echo "hidden" ?>>
                                                                            Check out
                                                                        </button>
                                                                    </form>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=reject" class="btn btn-danger" <?php if ($task['status'] != "pending") echo "hidden" ?>>
                                                                        Reject
                                                                    </a>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=approve" class="btn btn-primary" <?php if ($task['status'] != "pending") echo "hidden" ?>>
                                                                        Approve
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
                        </div>
                    </div>
                    <div class="col-7 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title">Pending Tasks</h3>
                            </div>
                            <div class="card-body">
                                <?php
                                $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.lowerID = employee.employeeID WHERE task.lowerID = '$emid' AND task.status = 'pending'";
                                $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                ?>
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
                                        foreach ($taskArray as $task) {
                                            $lowerID = $task['lowerID'];
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
                                                        <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                            <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned") echo "hidden" ?>>
                                                                Check in
                                                            </a>
                                                            <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                <input type="file" name="fileToUpload" id="fileToUpload formFile" class="form-control" <?php if ($task['status'] != "in progress") echo "hidden" ?> required>
                                                                <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress") echo "hidden" ?>>
                                                                    Check out
                                                                </button>
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
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.lowerID = employee.employeeID WHERE task.lowerID = '$emid' AND task.status = 'completed'";
                                        $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                        ?>
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
                                                foreach ($taskArray as $task) {
                                                    $lowerID = $task['lowerID'];
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
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload formFile" class="form-control" <?php if ($task['status'] != "in progress") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress") echo "hidden" ?>>Check out</button>
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
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.lowerID = employee.employeeID WHERE task.lowerID = '$emid' AND task.status = 'overdue'";
                                        $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                        ?>
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
                                                foreach ($taskArray as $task) {
                                                    $lowerID = $task['lowerID'];
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
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload formFile" class="form-control" <?php if ($task['status'] != "in progress") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress") echo "hidden" ?>>Check out</button>
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

                </div>
            </section>
        </div>

        <footer>
            <div class="footer clearfix mb-0 text-muted">


            </div>
        </footer>
    </div>


<?php
    require "./components/foot.php";
}
?>