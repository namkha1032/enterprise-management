<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    require_once "./database.php";
    $sql = "SELECT * FROM department";
    $departmentArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    // Page
    require "./components/head.php";
?>

    <div id="app">
        <?php require "components/sidebar.php"; ?>
        <div id="main-content">
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Vertical Layout with Navbar</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
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
                        </div>
                    </div>
                </div>

                <section class="section">
                    <?php
                    require_once "./database.php";
                    $deid = $_SESSION['departID'];
                    for ($i = 0; $i <= 3; $i++) {
                        if ($i == 0) {
                            $status = "assigned";
                            $tit = "Assigned Tasks";
                        }
                        if ($i == 1) {
                            $status = "in progress";
                            $tit = "In Progress";
                        }
                        if ($i == 2) {
                            $status = "completed";
                            $tit = "Completed Tasks";
                        }
                        if ($i == 3) {
                            $status = "overdue";
                            $tit = "Overdue Tasks";
                        }

                        $sql = "UPDATE task SET status = 'overdue' WHERE deadline<NOW()";
                        $conn->query($sql);
                        $sql = "SELECT * FROM task 
        INNER JOIN employee ON task.officerID = employee.employeeID WHERE employee.departID = '$deid' AND task.status = '$status'";
                        $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

                        $sql = "SELECT * FROM employee
        INNER JOIN account ON employee.username = account.username WHERE departID = '$deid'";
                        $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                        // foreach ($taskArray as $task) {
                        //     if (date("Y-m-d") > $task['deadline']) {
                        //         $tid = $task['taskID'];
                        //         $sql = "UPDATE task SET status = 'overdue' WHERE taskID='$tid'";
                        //         $conn->query($sql);
                        //     }
                        // }
                    ?>
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title"><?= $tit ?></h2>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>Task ID</th>
                                            <th>Title</th>
                                            <th>Officer</th>
                                            <th>Assigned Date</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
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
                                                <td><?= $task['assignedDate'] ?></td>
                                                <td><?= $task['deadline'] ?></td>
                                                <td><?= $task['status'] ?></td>
                                                <td>
                                                    <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskID'] ?>">
                                                        View
                                                    </button>
                                                    <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] != 'head' || $task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
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
                                                            </dl>
                                                        </div>
                                                        <div class="modal-footer" <?php if ($_SESSION['role'] == 'head' || $task['status'] == "completed" || $task['status'] == "overdue") echo "hidden" ?>>
                                                            <a href="./index.php?page=task-checkin-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] == "in progress") echo "hidden" ?>>
                                                                Check in
                                                            </a>
                                                            <a href="./index.php?page=task-checkout-processing&tid=<?= $task['taskID'] ?>" class="btn btn-primary" <?php if ($task['status'] == "assigned") echo "hidden" ?>>
                                                                Check out
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
                                                                <input id="title" name="title" value="<?= $task['title'] ?>">
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
                                                                <input id="deadline" name="deadline" type="date" value="<?= $task['deadline'] ?>">

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
                    <?php } ?>
                </section>

                <button data-bs-toggle="modal" data-bs-target="#assignTask" class="btn btn-primary" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
                    Assign task
                </button>
                <div class="modal fade" id="assignTask" tabindex="-1" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Assign task</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="./index.php?page=task-assign-processing" method="POST">
                                <div class="modal-body">
                                    <label for="title">Title</label>
                                    <input id="title" name="title">
                                    <br>
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description"></textarea>
                                    <br>
                                    <label for="deadline">Deadline</label>
                                    <input id="deadline" name="deadline" type="date">
                                    <br>
                                    <label for="officerID">Choose employee:</label>
                                    <select name="officerID" id="officerID">
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
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2022 &copy; Group 2 CC01</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    </div>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/app.js"></script>
<?php
    require "./components/foot.php";
}
?>