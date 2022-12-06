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
                            <a href="index.html"><img src="assets/images/logo/logo.png" alt="Logo" style = "width: 80px; height: 80px;"/></a>
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
                                        <a class="dropdown-item" href="./index.php?page=profile&employeeID=<?= $_SESSION['employeeID'] ?>"
                                        ><i class="icon-mid bi bi-person me-2"></i> My
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
                        foreach ($departmentArray as $department) {
                            if ($department['departID'] == 'DE0001')
                                continue;
                            $deid = $department['departID'];
                            if ($_SESSION['role'] != 'admin' && $_SESSION['departID'] != $deid)
                                continue;
                            $sql = "SELECT * FROM employee 
        INNER JOIN account ON employee.username = account.username WHERE employee.departID='$deid'";
                            $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                        ?>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Department: <?= $department['name'] ?></h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover datatable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Username</th>
                                                <th <?php if ($_SESSION['role'] != 'admin') echo "hidden" ?>>Password</th>
                                                <th>Role</th>
                                                <th>Name</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($emArray as $em) {
                                            ?>
                                                <tr>
                                                    <td><?= $em['employeeID'] ?></td>
                                                    <td><?= $em['username'] ?></td>
                                                    <td <?php if ($_SESSION['role'] != 'admin') echo "hidden" ?>><?= $em['password'] ?></td>
                                                    <td><?= $em['role'] ?></td>
                                                    <td><?= $em['name'] ?></td>
                                                    <td><?= $em['gender'] ?></td>
                                                    <td><?= $em['address'] ?></td>
                                                    <td><?= $em['phone'] ?></td>
                                                    <td>
                                                        <a href="./index.php?page=profile&employeeID=<?= $em['employeeID'] ?>" class="btn btn-sm rounded-pill btn-outline-success">
                                                            View
                                                        </a>
                                                        <a href="./index.php?page=employee-delete-processing&username=<?= $em['username'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'admin' || $em['role'] == 'head') echo "hidden" ?>>
                                                            Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="viewEmployee<?= $em['employeeID'] ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5">Employee information</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <dl class="row mt-2">
                                                                    <dt class="col-sm-4">Employee ID</dt>
                                                                    <dd class="col-sm-8"><?= $em['employeeID'] ?></dd>

                                                                    <dt class="col-sm-4">Name</dt>
                                                                    <dd class="col-sm-8"><?= $em['name'] ?></dd>

                                                                    <dt class="col-sm-4">username</dt>
                                                                    <dd class="col-sm-8"><?= $em['username'] ?></dd>

                                                                    <dt class="col-sm-4">Gender</dt>
                                                                    <dd class="col-sm-8"><?= $em['gender'] ?></dd>

                                                                    <dt class="col-sm-4">Date of Birth</dt>
                                                                    <dd class="col-sm-8"><?= $em['dob'] ?></dd>

                                                                    <dt class="col-sm-4">Nationality</dt>
                                                                    <dd class="col-sm-8"><?= $em['nationality'] ?></dd>

                                                                    <dt class="col-sm-4">Address</dt>
                                                                    <dd class="col-sm-8"><?= $em['address'] ?></dd>

                                                                    <dt class="col-sm-4">Phone</dt>
                                                                    <dd class="col-sm-8"><?= $em['phone'] ?></dd>

                                                                    <dt class="col-sm-4">Salary</dt>
                                                                    <dd class="col-sm-8"><?= $em['salary'] ?></dd>

                                                                    <dt class="col-sm-4">Start Date</dt>
                                                                    <dd class="col-sm-8"><?= $em['startDate'] ?></dd>

                                                                    <dt class="col-sm-4">Department</dt>
                                                                    <dd class="col-sm-8"><?= $em['departID'] ?></dd>
                                                                </dl>
                                                            </div>
                                                            <div class="modal-footer" <?php if ($em['role'] == 'head') echo "hidden" ?>>
                                                                <a href="index.php?page=employee-sethead-processing&username=<?= $em['username'] ?>&depart=<?= $em['departID'] ?>" class="btn btn-primary">
                                                                    Set head
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
                    </section>

                    

                <?php
                        }
                ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertEmployee" <?php if ($_SESSION['role'] != 'admin') echo "hidden" ?>>
                    Add employee
                </button>
                <div class="modal fade" id="insertEmployee" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Add new employee</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="./index.php?page=employee-insert-processing" method="POST">
                                <div class="modal-body">
                                    <label for="name">Name</label><span class="text-danger">*</span>
                                    <input type="text" id="name" name="name" placeholder="Name..." required>
                                    <br>
                                    <label for="username">username</label><span class="text-danger">*</span>
                                    <input type="text" id="username" name="username" placeholder="username..." required>
                                    <br>
                                    <label for="password">password</label><span class="text-danger">*</span>
                                    <input type="text" id="password" name="password" placeholder="password..." required>
                                    <br>

                                    <label for="role">Role</label><span class="text-danger">*</span>
                                    <select name="role" id="role" required>
                                        <option value="officer">Officer</option>
                                        <option value="head">Head</option>
                                    </select>
                                    <br>
                                    <label for="departID">Department</label><span class="text-danger">*</span>
                                    <select name="departID" id="departID" required>
                                        <?php
                                        foreach ($departmentArray as $depart) {
                                            if ($depart['name'] == 'Admin')
                                                continue;
                                        ?>
                                            <option value="<?= $depart['departID'] ?>"><?= $depart['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <br>
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <br>
                                    <label for="dob">Date of birth</label>
                                    <input type="date" name="dob" id="dob">
                                    <br>
                                    <label for="nationality">Nationality</label>
                                    <input type="text" name="nationality" id="nationality">
                                    <br>
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address"></textarea>
                                    <br>
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone">
                                    <br>
                                    <label for="salary">Salary</label>
                                    <input type="number" name="salary" id="salary">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Insert</button>
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