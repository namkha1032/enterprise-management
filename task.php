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
    $sql = "SELECT * FROM employee
        INNER JOIN account ON employee.username = account.username WHERE departID = '$deid'";
    $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
    <div id="main-content">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3 style="display:inline" class="me-4">Task Assignment Section</h3>
                        <!-- <p class="text-subtitle text-muted">
              Navbar will appear on the top of the page.
            </p> -->
                        <button style="display:inline" data-bs-toggle="modal" data-bs-target="#assignTask" class="btn btn-primary rounded-pill mb-2" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
                            Assign task
                        </button>
                    </div>
                    <!-- <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="index.html">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  Layout Vertical Navbar
                </li>
              </ol>
            </nav>
          </div> -->
                </div>

            </div>
            <section class="section">
                <div class="row">
                    <div class="col-4 mb-4">
                        <div class="card h-100 border border-dark">
                            <div class="card-header">
                                <h3 class="card-title">Chart</h3>
                            </div>
                            <div class="card-body">
                                <?php

                                $amountAssigned = 0;
                                $amountInProgress = 0;
                                $amountPending = 0;
                                $amountCompleted = 0;
                                $amountOverdue = 0;
                                if ($_SESSION['role'] == 'officer') {
                                    $emid = $_SESSION['employeeID'];
                                    $amountAssigned = $conn->query("SELECT * FROM task WHERE status = 'assigned' AND officerID = '$emid'")->num_rows;
                                    $amountInProgress = $conn->query("SELECT * FROM task WHERE status = 'in progress' AND officerID = '$emid'")->num_rows;
                                    $amountPending = $conn->query("SELECT * FROM task WHERE status = 'pending' AND officerID = '$emid'")->num_rows;
                                    $amountCompleted = $conn->query("SELECT * FROM task WHERE status = 'completed' AND officerID = '$emid'")->num_rows;
                                    $amountOverdue = $conn->query("SELECT * FROM task WHERE status = 'overdue' AND officerID = '$emid'")->num_rows;
                                } else {
                                    $amountAssigned = $conn->query("SELECT * FROM task WHERE status = 'assigned'")->num_rows;
                                    $amountInProgress = $conn->query("SELECT * FROM task WHERE status = 'in progress'")->num_rows;
                                    $amountPending = $conn->query("SELECT * FROM task WHERE status = 'pending'")->num_rows;
                                    $amountCompleted = $conn->query("SELECT * FROM task WHERE status = 'completed'")->num_rows;
                                    $amountOverdue = $conn->query("SELECT * FROM task WHERE status = 'overdue'")->num_rows;
                                }
                                ?>
                                <div id="chart-visitors-profile"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="card h-100 border border-dark">
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active navHead" id="home-tab" data-bs-toggle="tab" href="#assignTab" role="tab" aria-controls="home" aria-selected="true"><h3 class="card-title">Assigned Tasks</h3></a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navHead" id="profile-tab" data-bs-toggle="tab" href="#progressTab" role="tab" aria-controls="profile" aria-selected="false"><h3 class="card-title">In Progress Tasks</h3></a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="assignTab" role="tabpanel" aria-labelledby="home-tab">
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.officerID = employee.employeeID WHERE employee.departID = '$deid' AND task.status = 'assigned'";
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
                                                    $emID = $task['officerID'];
                                                    if ($_SESSION['username'] != $task['username'] && $_SESSION['role'] == "officer")
                                                        continue;
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
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] != 'head' || $task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
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
                                                                    <dl class="row mt-2">
                                                                        <dt class="col-sm-4">Task ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['taskID'] ?></dd>

                                                                        <dt class="col-sm-4">Title</dt>
                                                                        <dd class="col-sm-8"><?= $task['title'] ?></dd>

                                                                        <dt class="col-sm-4">Description</dt>
                                                                        <dd class="col-sm-8"><?= $task['description'] ?></dd>

                                                                        <dt class="col-sm-4">Officer ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['officerID'] ?></dd>

                                                                        <dt class="col-sm-4">Officer Name</dt>
                                                                        <dd class="col-sm-8"><?= $task['name'] ?></dd>

                                                                        <dt class="col-sm-4">Status</dt>
                                                                        <dd class="col-sm-8"><?= $task['status'] ?></dd>

                                                                        <dt class="col-sm-4">Assigned date</dt>
                                                                        <dd class="col-sm-8"><?= $task['assignedDate'] ?></dd>

                                                                        <dt class="col-sm-4">Deadline</dt>
                                                                        <dd class="col-sm-8"><?= $task['deadline'] ?></dd>

                                                                        <dt class="col-sm-4">Check in date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkinDate'] ?></dd>

                                                                        <dt class="col-sm-4">Check out date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkoutDate'] ?></dd>

                                                                        <dt class="col-sm-4">Submit file</dt>
                                                                        <dd class="col-sm-8"><a href="./processing/file-download-processing.php?file=<?= $task['submitFile'] ?>"><?= str_replace("../files_submit/", "", $task['submitFile']) ?></a></dd>
                                                                    </dl>

                                                                </div>
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned" || $_SESSION['role'] == "head") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?>>Check out</button>
                                                                    </form>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=reject" class="btn btn-danger" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Reject
                                                                    </a>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=approve" class="btn btn-primary" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Approve
                                                                    </a>
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
                                                                <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST">
                                                                    <div class="modal-body">
                                                                        <label for="title">Title</label>
                                                                        <input id="title" name="title" value="<?= $task['title'] ?>" required>
                                                                        <br>
                                                                        <label for="description">Description</label>
                                                                        <textarea id="description" name="description"><?= $task['description'] ?></textarea>
                                                                        <br>
                                                                        <label for="officerID">Choose officer:</label>

                                                                        <select name="officerID" id="officerID" value="<?= $task['name'] ?>">
                                                                            <?php
                                                                            foreach ($emArray as $em) {
                                                                                if ($em['role'] == 'head')
                                                                                    continue;
                                                                            ?>
                                                                                <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <br>
                                                                        <label for="deadline">Deadline</label>
                                                                        <input id="deadline" name="deadline" type="date" value="<?= $task['deadline'] ?>" required>

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
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.officerID = employee.employeeID WHERE employee.departID = '$deid' AND task.status = 'in progress'";
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
                                                    $emID = $task['officerID'];
                                                    if ($_SESSION['username'] != $task['username'] && $_SESSION['role'] == "officer")
                                                        continue;
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
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] != 'head' || $task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
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
                                                                    <dl class="row mt-2">
                                                                        <dt class="col-sm-4">Task ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['taskID'] ?></dd>

                                                                        <dt class="col-sm-4">Title</dt>
                                                                        <dd class="col-sm-8"><?= $task['title'] ?></dd>

                                                                        <dt class="col-sm-4">Description</dt>
                                                                        <dd class="col-sm-8"><?= $task['description'] ?></dd>

                                                                        <dt class="col-sm-4">Officer ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['officerID'] ?></dd>

                                                                        <dt class="col-sm-4">Officer Name</dt>
                                                                        <dd class="col-sm-8"><?= $task['name'] ?></dd>

                                                                        <dt class="col-sm-4">Status</dt>
                                                                        <dd class="col-sm-8"><?= $task['status'] ?></dd>

                                                                        <dt class="col-sm-4">Assigned date</dt>
                                                                        <dd class="col-sm-8"><?= $task['assignedDate'] ?></dd>

                                                                        <dt class="col-sm-4">Deadline</dt>
                                                                        <dd class="col-sm-8"><?= $task['deadline'] ?></dd>

                                                                        <dt class="col-sm-4">Check in date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkinDate'] ?></dd>

                                                                        <dt class="col-sm-4">Check out date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkoutDate'] ?></dd>

                                                                        <dt class="col-sm-4">Submit file</dt>
                                                                        <dd class="col-sm-8"><a href="./processing/file-download-processing.php?file=<?= $task['submitFile'] ?>"><?= str_replace("../files_submit/", "", $task['submitFile']) ?></a></dd>
                                                                    </dl>

                                                                </div>
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned" || $_SESSION['role'] == "head") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?>>Check out</button>
                                                                    </form>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=reject" class="btn btn-danger" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Reject
                                                                    </a>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=approve" class="btn btn-primary" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Approve
                                                                    </a>
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
                                                                <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST">
                                                                    <div class="modal-body">
                                                                        <label for="title">Title</label>
                                                                        <input id="title" name="title" value="<?= $task['title'] ?>" required>
                                                                        <br>
                                                                        <label for="description">Description</label>
                                                                        <textarea id="description" name="description"><?= $task['description'] ?></textarea>
                                                                        <br>
                                                                        <label for="officerID">Choose officer:</label>

                                                                        <select name="officerID" id="officerID" value="<?= $task['name'] ?>">
                                                                            <?php
                                                                            foreach ($emArray as $em) {
                                                                                if ($em['role'] == 'head')
                                                                                    continue;
                                                                            ?>
                                                                                <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <br>
                                                                        <label for="deadline">Deadline</label>
                                                                        <input id="deadline" name="deadline" type="date" value="<?= $task['deadline'] ?>" required>

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
                        <div class="card h-100 border border-dark">
                            <div class="card-header">
                                <h3 class="card-title">Pending Tasks</h3>
                            </div>
                            <div class="card-body">
                                <?php
                                $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.officerID = employee.employeeID WHERE employee.departID = '$deid' AND task.status = 'pending'";
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
                                            $emID = $task['officerID'];
                                            if ($_SESSION['username'] != $task['username'] && $_SESSION['role'] == "officer")
                                                continue;
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
                                                    <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] != 'head' || $task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                        Update
                                                    </button>
                                                    <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
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
                                                            <dl class="row mt-2">
                                                                <dt class="col-sm-4">Task ID</dt>
                                                                <dd class="col-sm-8"><?= $task['taskID'] ?></dd>

                                                                <dt class="col-sm-4">Title</dt>
                                                                <dd class="col-sm-8"><?= $task['title'] ?></dd>

                                                                <dt class="col-sm-4">Description</dt>
                                                                <dd class="col-sm-8"><?= $task['description'] ?></dd>

                                                                <dt class="col-sm-4">Officer ID</dt>
                                                                <dd class="col-sm-8"><?= $task['officerID'] ?></dd>

                                                                <dt class="col-sm-4">Officer Name</dt>
                                                                <dd class="col-sm-8"><?= $task['name'] ?></dd>

                                                                <dt class="col-sm-4">Status</dt>
                                                                <dd class="col-sm-8"><?= $task['status'] ?></dd>

                                                                <dt class="col-sm-4">Assigned date</dt>
                                                                <dd class="col-sm-8"><?= $task['assignedDate'] ?></dd>

                                                                <dt class="col-sm-4">Deadline</dt>
                                                                <dd class="col-sm-8"><?= $task['deadline'] ?></dd>

                                                                <dt class="col-sm-4">Check in date</dt>
                                                                <dd class="col-sm-8"><?= $task['checkinDate'] ?></dd>

                                                                <dt class="col-sm-4">Check out date</dt>
                                                                <dd class="col-sm-8"><?= $task['checkoutDate'] ?></dd>

                                                                <dt class="col-sm-4">Submit file</dt>
                                                                <dd class="col-sm-8"><a href="./processing/file-download-processing.php?file=<?= $task['submitFile'] ?>"><?= str_replace("../files_submit/", "", $task['submitFile']) ?></a></dd>
                                                            </dl>

                                                        </div>
                                                        <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                            <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned" || $_SESSION['role'] == "head") echo "hidden" ?>>
                                                                Check in
                                                            </a>
                                                            <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                <input type="file" name="fileToUpload" id="fileToUpload" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?> required>
                                                                <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?>>Check out</button>
                                                            </form>
                                                            <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=reject" class="btn btn-danger" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                Reject
                                                            </a>
                                                            <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=approve" class="btn btn-primary" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                Approve
                                                            </a>
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
                                                        <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST">
                                                            <div class="modal-body">
                                                                <label for="title">Title</label>
                                                                <input id="title" name="title" value="<?= $task['title'] ?>" required>
                                                                <br>
                                                                <label for="description">Description</label>
                                                                <textarea id="description" name="description"><?= $task['description'] ?></textarea>
                                                                <br>
                                                                <label for="officerID">Choose officer:</label>

                                                                <select name="officerID" id="officerID" value="<?= $task['name'] ?>">
                                                                    <?php
                                                                    foreach ($emArray as $em) {
                                                                        if ($em['role'] == 'head')
                                                                            continue;
                                                                    ?>
                                                                        <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <br>
                                                                <label for="deadline">Deadline</label>
                                                                <input id="deadline" name="deadline" type="date" value="<?= $task['deadline'] ?>" required>

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
                    <div class="col-5 mb-4">
                        <div class="card h-100 border border-dark">
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active navHead" id="home-tab" data-bs-toggle="tab" href="#completeTab" role="tab" aria-controls="home" aria-selected="true"><h3 class="card-title">Completed Tasks</h3></a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link navHead" id="profile-tab" data-bs-toggle="tab" href="#overdueTab" role="tab" aria-controls="profile" aria-selected="false"><h3 class="card-title">Overdue Tasks</h3></a>
                                    </li>
                                </ul>

                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="completeTab" role="tabpanel" aria-labelledby="home-tab">
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.officerID = employee.employeeID WHERE employee.departID = '$deid' AND task.status = 'completed'";
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
                                                    $emID = $task['officerID'];
                                                    if ($_SESSION['username'] != $task['username'] && $_SESSION['role'] == "officer")
                                                        continue;
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
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] != 'head' || $task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
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
                                                                    <dl class="row mt-2">
                                                                        <dt class="col-sm-4">Task ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['taskID'] ?></dd>

                                                                        <dt class="col-sm-4">Title</dt>
                                                                        <dd class="col-sm-8"><?= $task['title'] ?></dd>

                                                                        <dt class="col-sm-4">Description</dt>
                                                                        <dd class="col-sm-8"><?= $task['description'] ?></dd>

                                                                        <dt class="col-sm-4">Officer ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['officerID'] ?></dd>

                                                                        <dt class="col-sm-4">Officer Name</dt>
                                                                        <dd class="col-sm-8"><?= $task['name'] ?></dd>

                                                                        <dt class="col-sm-4">Status</dt>
                                                                        <dd class="col-sm-8"><?= $task['status'] ?></dd>

                                                                        <dt class="col-sm-4">Assigned date</dt>
                                                                        <dd class="col-sm-8"><?= $task['assignedDate'] ?></dd>

                                                                        <dt class="col-sm-4">Deadline</dt>
                                                                        <dd class="col-sm-8"><?= $task['deadline'] ?></dd>

                                                                        <dt class="col-sm-4">Check in date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkinDate'] ?></dd>

                                                                        <dt class="col-sm-4">Check out date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkoutDate'] ?></dd>

                                                                        <dt class="col-sm-4">Submit file</dt>
                                                                        <dd class="col-sm-8"><a href="./processing/file-download-processing.php?file=<?= $task['submitFile'] ?>"><?= str_replace("../files_submit/", "", $task['submitFile']) ?></a></dd>
                                                                    </dl>

                                                                </div>
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned" || $_SESSION['role'] == "head") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?>>Check out</button>
                                                                    </form>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=reject" class="btn btn-danger" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Reject
                                                                    </a>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=approve" class="btn btn-primary" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Approve
                                                                    </a>
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
                                                                <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST">
                                                                    <div class="modal-body">
                                                                        <label for="title">Title</label>
                                                                        <input id="title" name="title" value="<?= $task['title'] ?>" required>
                                                                        <br>
                                                                        <label for="description">Description</label>
                                                                        <textarea id="description" name="description"><?= $task['description'] ?></textarea>
                                                                        <br>
                                                                        <label for="officerID">Choose officer:</label>

                                                                        <select name="officerID" id="officerID" value="<?= $task['name'] ?>">
                                                                            <?php
                                                                            foreach ($emArray as $em) {
                                                                                if ($em['role'] == 'head')
                                                                                    continue;
                                                                            ?>
                                                                                <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <br>
                                                                        <label for="deadline">Deadline</label>
                                                                        <input id="deadline" name="deadline" type="date" value="<?= $task['deadline'] ?>" required>

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
                                    <div class="tab-pane fade" id="overdueTab" role="tabpanel" aria-labelledby="profile-tab">
                                        <?php
                                        $sql = "SELECT * FROM task 
                                                    INNER JOIN employee ON task.officerID = employee.employeeID WHERE employee.departID = '$deid' AND task.status = 'overdue'";
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
                                                    $emID = $task['officerID'];
                                                    if ($_SESSION['username'] != $task['username'] && $_SESSION['role'] == "officer")
                                                        continue;
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
                                                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] != 'head' || $task['status'] == "completed" || $task['status'] == "overdue" || $task['status'] == "pending") echo "hidden" ?>>
                                                                Update
                                                            </button>
                                                            <a href="./index.php?page=task-delete-processing&tid=<?= $task['taskID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
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
                                                                    <dl class="row mt-2">
                                                                        <dt class="col-sm-4">Task ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['taskID'] ?></dd>

                                                                        <dt class="col-sm-4">Title</dt>
                                                                        <dd class="col-sm-8"><?= $task['title'] ?></dd>

                                                                        <dt class="col-sm-4">Description</dt>
                                                                        <dd class="col-sm-8"><?= $task['description'] ?></dd>

                                                                        <dt class="col-sm-4">Officer ID</dt>
                                                                        <dd class="col-sm-8"><?= $task['officerID'] ?></dd>

                                                                        <dt class="col-sm-4">Officer Name</dt>
                                                                        <dd class="col-sm-8"><?= $task['name'] ?></dd>

                                                                        <dt class="col-sm-4">Status</dt>
                                                                        <dd class="col-sm-8"><?= $task['status'] ?></dd>

                                                                        <dt class="col-sm-4">Assigned date</dt>
                                                                        <dd class="col-sm-8"><?= $task['assignedDate'] ?></dd>

                                                                        <dt class="col-sm-4">Deadline</dt>
                                                                        <dd class="col-sm-8"><?= $task['deadline'] ?></dd>

                                                                        <dt class="col-sm-4">Check in date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkinDate'] ?></dd>

                                                                        <dt class="col-sm-4">Check out date</dt>
                                                                        <dd class="col-sm-8"><?= $task['checkoutDate'] ?></dd>

                                                                        <dt class="col-sm-4">Submit file</dt>
                                                                        <dd class="col-sm-8"><a href="./processing/file-download-processing.php?file=<?= $task['submitFile'] ?>"><?= str_replace("../files_submit/", "", $task['submitFile']) ?></a></dd>
                                                                    </dl>

                                                                </div>
                                                                <div class="modal-footer" <?php if ($task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                                    <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] != "assigned" || $_SESSION['role'] == "head") echo "hidden" ?>>
                                                                        Check in
                                                                    </a>
                                                                    <form action="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" method="post" enctype="multipart/form-data">
                                                                        <input type="file" name="fileToUpload" id="fileToUpload" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?> required>
                                                                        <button type="submit" value="Upload Image" class="btn btn-primary" <?php if ($task['status'] != "in progress" || $_SESSION['role'] == "head") echo "hidden" ?>>Check out</button>
                                                                    </form>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=reject" class="btn btn-danger" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Reject
                                                                    </a>
                                                                    <a href="./index.php?page=task-check-processing&tid=<?= $task['taskID'] ?>&check=approve" class="btn btn-primary" <?php if ($task['status'] != "pending" || $_SESSION['role'] == "officer") echo "hidden" ?>>
                                                                        Approve
                                                                    </a>
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
                                                                <form action="./index.php?page=task-update-processing&tid=<?= $task['taskID'] ?>" method="POST">
                                                                    <div class="modal-body">
                                                                        <label for="title">Title</label>
                                                                        <input id="title" name="title" value="<?= $task['title'] ?>" required>
                                                                        <br>
                                                                        <label for="description">Description</label>
                                                                        <textarea id="description" name="description"><?= $task['description'] ?></textarea>
                                                                        <br>
                                                                        <label for="officerID">Choose officer:</label>

                                                                        <select name="officerID" id="officerID" value="<?= $task['name'] ?>">
                                                                            <?php
                                                                            foreach ($emArray as $em) {
                                                                                if ($em['role'] == 'head')
                                                                                    continue;
                                                                            ?>
                                                                                <option value="<?= $em['employeeID'] ?>"> <?= $em['employeeID'] ?> <?= $em['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <br>
                                                                        <label for="deadline">Deadline</label>
                                                                        <input id="deadline" name="deadline" type="date" value="<?= $task['deadline'] ?>" required>

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
                    <div class="col-12 mb-4" <?php if ($_SESSION['role'] == 'officer') echo 'hidden' ?>>
                        <div class="card h-100 border border-dark">
                            <div class="card-header">
                                <h3 class="card-title">Chart</h3>
                            </div>
                            <?php
                            $nameArray = array();
                            $incompletedArray = array();
                            $completedArray = array();
                            $overdueArray = array();
                            foreach ($emArray as $em) {
                                if ($em['role'] == 'head')
                                    continue;
                                $emid = $em['employeeID'];
                                $assignAmount = $conn->query("SELECT * FROM task WHERE status = 'assigned' AND officerID = '$emid'")->num_rows;
                                $progressAmount = $conn->query("SELECT * FROM task WHERE status = 'in progress' AND officerID = '$emid'")->num_rows;
                                $pendingAmount = $conn->query("SELECT * FROM task WHERE status = 'pending' AND officerID = '$emid'")->num_rows;
                                $completeAmount = $conn->query("SELECT * FROM task WHERE status = 'completed' AND officerID = '$emid'")->num_rows;
                                $overdueAmount = $conn->query("SELECT * FROM task WHERE status = 'overdue' AND officerID = '$emid'")->num_rows;
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
                                        <select name="officerID" class="form-control" required>
                                            <option disabled selected hidden value="">This task is assigned to...</option>
                                            <?php
                                            foreach ($emArray as $em) {
                                                if ($em['role'] == 'head')
                                                    continue;
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
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    require "./components/foot.php";
}
?>