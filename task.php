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
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" style="width: 80px; height: 80px;" /></a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
                            </svg>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item">
                            <a href="./index.php?page=employee" <?php if ($_SESSION['role'] == 'officer') echo "hidden" ?> class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="./index.php?page=task" <?php if ($_SESSION['role'] == 'admin') echo "hidden" ?> class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Task manager</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="./index.php?page=announce" <?php if ($_SESSION['role'] == 'admin') echo "hidden" ?> class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Announcement manager</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="./index.php?page=request" <?php if ($_SESSION['role'] == 'admin') echo "hidden" ?> class="sidebar-link">
                                <i class="bi bi-grid-fill"></i>
                                <span>Request manager</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="main" class="layout-navbar navbar-fixed">
            <header class="mb-3">
                <nav class="navbar navbar-expand navbar-light navbar-top">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-lg-0">
                                <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-envelope bi-sub fs-4"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <h6 class="dropdown-header">Mail</h6>
                                        </li>
                                        <li><a class="dropdown-item" href="#">No new mail</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle text-gray-600" href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                        <i class="bi bi-bell bi-sub fs-4"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="dropdownMenuButton">
                                        <li class="dropdown-header">
                                            <h6>Notifications</h6>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-primary">
                                                    <i class="bi bi-cart-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">
                                                        Successfully check out
                                                    </p>
                                                    <p class="notification-subtitle font-thin text-sm">
                                                        Order ID #256
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dropdown-item notification-item">
                                            <a class="d-flex align-items-center" href="#">
                                                <div class="notification-icon bg-success">
                                                    <i class="bi bi-file-earmark-check"></i>
                                                </div>
                                                <div class="notification-text ms-4">
                                                    <p class="notification-title font-bold">
                                                        Homework submitted
                                                    </p>
                                                    <p class="notification-subtitle font-thin text-sm">
                                                        Algebra math homework
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <p class="text-center py-2 mb-0">
                                                <a href="#">See all notification</a>
                                            </p>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">John Ducky</h6>
                                            <p class="mb-0 text-sm text-gray-600">Administrator</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="assets/images/faces/1.jpg" />
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem">
                                    <li>
                                        <h6 class="dropdown-header">Hello, John!</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="./index.php?page=profile&employeeID=<?= $_SESSION['employeeID'] ?>"><i class="icon-mid bi bi-person me-2"></i> My
                                            Profile</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="./index.php?page=logout"><i class="icon-mid bi bi-box-arrow-left me-2"></i>
                                            Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
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