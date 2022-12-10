<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    // Page
    require_once "./database.php";
    require "./components/head.php";
    $deid = $_SESSION['departID'];
    $sql = "SELECT * FROM department";
    $departmentArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    $pendingArray = array();
    $acceptedArray = array();
    $rejectedArray = array();
    $pendingAmount = 0;
    $acceptedAmount = 0;
    $rejectedAmount = 0;
    $emArray = array();
    if ($_SESSION['role'] == 'ceo') {
        $sql = "SELECT * FROM request 
                  INNER JOIN employee ON request.lowerID = employee.employeeID
                  INNER JOIN account on employee.username = account.username
                  LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                  LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE account.role = 'head' AND request.status='pending'";
        $pendingAmount = $conn->query($sql)->num_rows;
        $pendingArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * FROM request 
                  INNER JOIN employee ON request.lowerID = employee.employeeID
                  INNER JOIN account on employee.username = account.username
                  LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                  LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE account.role = 'head' AND request.status='accepted'";
        $acceptedAmount = $conn->query($sql)->num_rows;
        $acceptedArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * FROM request 
                  INNER JOIN employee ON request.lowerID = employee.employeeID
                  INNER JOIN account on employee.username = account.username
                  LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                  LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE account.role = 'head' AND request.status='rejected'";
        $rejectedAmount = $conn->query($sql)->num_rows;
        $rejectedArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $emArray = $conn->query("SELECT * FROM employee
                    INNER JOIN account ON employee.username = account.username WHERE account.role = 'head'")->fetch_all(MYSQLI_ASSOC);
    }
    if ($_SESSION['role'] == 'head') {
        $sql = "SELECT * FROM request 
                INNER JOIN employee ON request.lowerID = employee.employeeID
                INNER JOIN account on employee.username = account.username
                LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE account.role = 'officer' AND employee.departID = '$deid' AND request.status='pending'";
        $pendingAmount = $conn->query($sql)->num_rows;
        $pendingArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * FROM request 
                INNER JOIN employee ON request.lowerID = employee.employeeID
                INNER JOIN account on employee.username = account.username
                LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE account.role = 'officer' AND employee.departID = '$deid' AND request.status='accepted'";
        $acceptedAmount = $conn->query($sql)->num_rows;
        $acceptedArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * FROM request 
                INNER JOIN employee ON request.lowerID = employee.employeeID
                INNER JOIN account on employee.username = account.username
                LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE account.role = 'officer' AND employee.departID = '$deid' AND request.status='rejected'";
        $rejectedAmount = $conn->query($sql)->num_rows;
        $rejectedArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $emArray = $conn->query("SELECT * FROM employee
                                INNER JOIN account ON employee.username = account.username WHERE employee.departID = '$deid' AND account.role = 'officer'")->fetch_all(MYSQLI_ASSOC);
    }
?>

    <div id="main-content">
        <div class="page-heading">
            <div class="page-title mb-2">
                <h1 style="display:inline" class="me-4">Request Management</h1>
                <div class="mb-4">
                </div>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Statistics</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <div class="row">
                                                <li class="nav-item col-12" role="presentation">
                                                    <a class="nav-link active p-3" id="home-tab" data-bs-toggle="tab" href="#pendingTab" role="tab" aria-controls="home" aria-selected="true">
                                                        <div class="row">
                                                            <div class="col-4 d-flex justify-content-start">
                                                                <div class="stats-icon blue">
                                                                    <i class="bi-hourglass-bottom"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-8">
                                                                <h6 class="font-semibold">
                                                                    Pending requests
                                                                </h6>
                                                                <h6 class="font-extrabold mb-0"><?= $pendingAmount ?></h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item col-12" role="presentation">
                                                    <a class="nav-link p-3" id="profile-tab" data-bs-toggle="tab" href="#acceptedTab" role="tab" aria-controls="profile" aria-selected="false">
                                                        <div class="row">
                                                            <div class="col-4 d-flex justify-content-start">
                                                                <div class="stats-icon green">
                                                                    <i class="bi-check-lg"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-8">
                                                                <h6 class="font-semibold">
                                                                    Accepted Requests
                                                                </h6>
                                                                <h6 class="font-extrabold mb-0"><?= $acceptedAmount ?></h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item col-12" role="presentation">
                                                    <a class="nav-link p-3" id="contact-tab" data-bs-toggle="tab" href="#rejectedTab" role="tab" aria-controls="contact" aria-selected="false">
                                                        <div class="row">
                                                            <div class="col-4 d-flex justify-content-start">
                                                                <div class="stats-icon red">
                                                                    <i class="bi-x-lg"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-8">
                                                                <h6 class="font-semibold">
                                                                    Rejected Requests
                                                                </h6>
                                                                <h6 class="font-extrabold mb-0"><?= $rejectedAmount ?></h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Chart</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="request-pie-chart">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 mb-4">
                        <div class="card h-100">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="pendingTab" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Pending requests</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover" id="reqTable">
                                            <thead>
                                                <tr>
                                                    <th>Request ID</th>
                                                    <th>Type</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Date sent</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($pendingArray as $request) {
                                                    $userName = $request['name'];
                                                ?>
                                                    <tr>
                                                        <td><?= $request['requestID'] ?></td>
                                                        <td><?= $request['type'] ?></td>
                                                        <td><?= $request['title'] ?></td>
                                                        <td><?= $request['name']; ?></td>
                                                        <td><?= $request['datesent'] ?></td>
                                                        <td>
                                                            <a class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRequest<?= $request['requestID'] ?>">
                                                                View
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateOtherRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "other") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateAbsenceRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "absence") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateSalaryRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "salary") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a href="./index.php?page=request-delete-processing&rid=<?= $request['requestID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($request['status'] == "pending") echo "hidden" ?>>
                                                                Delete
                                                            </a>
                                                        </td>

                                                    </tr>
                                                    <div class="modal fade" id="viewRequest<?= $request['requestID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Request info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $request['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Type</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['type'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Request ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['requestID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['employeeID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <label>Date start</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['date_start_absence']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <label>Date end</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['date_end_absence']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>
                                                                                <label>Amount</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= $request['amount'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label><?php echo $request['type'] == "other" ? "Description" : "Reason" ?></label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <textarea class="form-control" readonly cols="30" rows="3" style="resize:none;"><?= $request['description'] ?></textarea>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Date sent</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['datesent']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Date decided</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?= ($request['status'] == "pending") ? "disabled" : "value=\"" . date_format(date_create($request['datedecided']), "d/m/Y") . "\""; ?> />
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer" <?php if ($request['status'] != "pending") echo "hidden" ?>>
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
                                <div class="tab-pane fade" id="acceptedTab" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Accepted requests</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover" id="reqTable">
                                            <thead>
                                                <tr>
                                                    <th>Request ID</th>
                                                    <th>Type</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Date sent</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($acceptedArray as $request) {
                                                    $userName = $request['name'];
                                                ?>
                                                    <tr>
                                                        <td><?= $request['requestID'] ?></td>
                                                        <td><?= $request['type'] ?></td>
                                                        <td><?= $request['title'] ?></td>
                                                        <td><?= $request['name']; ?></td>
                                                        <td><?= $request['datesent'] ?></td>
                                                        <td>
                                                            <a class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRequest<?= $request['requestID'] ?>">
                                                                View
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateOtherRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "other") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateAbsenceRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "absence") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateSalaryRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "salary") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a href="./index.php?page=request-delete-processing&rid=<?= $request['requestID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($request['status'] == "pending") echo "hidden" ?>>
                                                                Delete
                                                            </a>
                                                        </td>

                                                    </tr>
                                                    <div class="modal fade" id="viewRequest<?= $request['requestID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Request info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $request['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Type</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['type'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Request ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['requestID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['employeeID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <label>Date start</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['date_start_absence']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <label>Date end</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['date_end_absence']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>
                                                                                <label>Amount</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= $request['amount'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label><?php echo $request['type'] == "other" ? "Description" : "Reason" ?></label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <textarea class="form-control" readonly cols="30" rows="3" style="resize:none;"><?= $request['description'] ?></textarea>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Date sent</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['datesent']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Date decided</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?= ($request['status'] == "pending") ? "disabled" : "value=\"" . date_format(date_create($request['datedecided']), "d/m/Y") . "\""; ?> />
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer" <?php if ($request['status'] != "pending") echo "hidden" ?>>
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
                                <div class="tab-pane fade" id="rejectedTab" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Rejected requests</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-hover" id="reqTable">
                                            <thead>
                                                <tr>
                                                    <th>Request ID</th>
                                                    <th>Type</th>
                                                    <th>Title</th>
                                                    <th>Officer</th>
                                                    <th>Date sent</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($rejectedArray as $request) {
                                                    $userName = $request['name'];
                                                ?>
                                                    <tr>
                                                        <td><?= $request['requestID'] ?></td>
                                                        <td><?= $request['type'] ?></td>
                                                        <td><?= $request['title'] ?></td>
                                                        <td><?= $request['name']; ?></td>
                                                        <td><?= $request['datesent'] ?></td>
                                                        <td>
                                                            <a class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRequest<?= $request['requestID'] ?>">
                                                                View
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateOtherRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "other") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateAbsenceRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "absence") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a data-bs-toggle="modal" data-bs-target="#updateSalaryRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($request['status'] != "pending" || $request['type'] != "salary") echo "hidden" ?>>
                                                                Update
                                                            </a>
                                                            <a href="./index.php?page=request-delete-processing&rid=<?= $request['requestID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($request['status'] == "pending") echo "hidden" ?>>
                                                                Delete
                                                            </a>
                                                        </td>

                                                    </tr>
                                                    <div class="modal fade" id="viewRequest<?= $request['requestID'] ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5">Request info</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="avatar mb-4 d-flex justify-content-center">
                                                                        <img src="<?= $request['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                                                                    </div>
                                                                    <form class="form form-horizontal">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <label>Title</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['title'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Type</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['type'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Request ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['requestID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer ID</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['employeeID'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Officer name</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['name'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <label>Date start</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['date_start_absence']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <label>Date end</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['date_end_absence']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>
                                                                                <label>Amount</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>
                                                                                <input type="text" class="form-control" readonly value="<?= $request['amount'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label><?php echo $request['type'] == "other" ? "Description" : "Reason" ?></label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <textarea class="form-control" readonly cols="30" rows="3" style="resize:none;"><?= $request['description'] ?></textarea>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Status</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= $request['status'] ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Date sent</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($request['datesent']), "d/m/Y") ?>" />
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label>Date decided</label>
                                                                            </div>
                                                                            <div class="col-md-8 form-group">
                                                                                <input type="text" class="form-control" readonly <?= ($request['status'] == "pending") ? "disabled" : "value=\"" . date_format(date_create($request['datedecided']), "d/m/Y") . "\""; ?> />
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer" <?php if ($request['status'] != "pending") echo "hidden" ?>>
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