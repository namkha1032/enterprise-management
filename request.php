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
                    for ($i = 0; $i <= 2; $i++) {
                        if ($i == 0) {
                            $status = "pending";
                            $tit = "Pending requests";
                        } elseif ($i == 1) {
                            $status = "accepted";
                            $tit = "Accepted requests";
                        } else {
                            $status = "rejected";
                            $tit = "Rejected requests";
                        }

                        $sql = "SELECT * FROM request 
                INNER JOIN employee ON request.officerID = employee.employeeID
                LEFT JOIN request_absence ON request.requestID = request_absence.absenceID
                LEFT JOIN request_salary ON request.requestID = request_salary.salaryID WHERE request.status='$status'";
                        $requestArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                    ?>
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title"><?= $tit ?></h2>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Type</th>
                                            <th>Title</th>
                                            <th>Officer</th>
                                            <th>Status</th>
                                            <th>Date sent</th>
                                            <th>Date decided</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($requestArray as $request) {
                                            if ($_SESSION['username'] != $request['username'] && $_SESSION['role'] == "officer")
                                                continue;
                                            $userName = $request['name'];
                                        ?>
                                            <tr>
                                                <td><?= $request['requestID'] ?></td>
                                                <td><?= $request['type'] ?></td>
                                                <td><?= $request['title'] ?></td>
                                                <td><?= $request['name']; ?></td>
                                                <td><?= $request['status'] ?></td>
                                                <td><?= $request['datesent'] ?></td>
                                                <td><?= $request['datedecided'] ?></td>
                                                <td>
                                                    <a class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRequest<?= $request['requestID'] ?>">
                                                        View
                                                    </a>
                                                    <a data-bs-toggle="modal" data-bs-target="#updateOtherRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] == 'head' || $request['status'] != "pending" || $request['type'] != "other") echo "hidden" ?>>
                                                        Update
                                                    </a>
                                                    <a data-bs-toggle="modal" data-bs-target="#updateAbsenceRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] == 'head' || $request['status'] != "pending" || $request['type'] != "absence") echo "hidden" ?>>
                                                        Update
                                                    </a>
                                                    <a data-bs-toggle="modal" data-bs-target="#updateSalaryRequest<?= $request['requestID'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['role'] == 'head' || $request['status'] != "pending" || $request['type'] != "salary") echo "hidden" ?>>
                                                        Update
                                                    </a>
                                                    <a href="./index.php?page=request-delete-processing&rid=<?= $request['requestID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] == 'officer' && $request['status'] != "pending") echo "hidden" ?>>
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
                                                            <dl class="row mt-2">
                                                                <dt class="col-sm-4">Title</dt>
                                                                <dd class="col-sm-8"><?= $request['title'] ?></dd>

                                                                <dt class="col-sm-4">Type</dt>
                                                                <dd class="col-sm-8"><?= $request['type'] ?></dd>

                                                                <dt class="col-sm-4">Request ID</dt>
                                                                <dd class="col-sm-8"><?= $request['requestID'] ?></dd>

                                                                <dt class="col-sm-4">Officer ID</dt>
                                                                <dd class="col-sm-8"><?= $request['employeeID'] ?></dd>

                                                                <dt class="col-sm-4">Officer Name</dt>
                                                                <dd class="col-sm-8"><?= $request['name'] ?></dd>



                                                                <dt class="col-sm-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>Date start</dt>
                                                                <dd class="col-sm-8" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>><?= $request['date_start_absence'] ?></dd>
                                                                <dt class="col-sm-4" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>>Date end</dt>
                                                                <dd class="col-sm-8" <?php echo $request['type'] == "absence" ? "" : "hidden" ?>><?= $request['date_end_absence'] ?></dd>


                                                                <dt class="col-sm-4" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>>Amount</dt>
                                                                <dd class="col-sm-8" <?php echo $request['type'] == "salary" ? "" : "hidden" ?>><?= $request['amount'] ?></dd>


                                                                <dt class="col-sm-4"><?php echo $request['type'] == "other" ? "Description" : "Reason" ?></dt>
                                                                <dd class="col-sm-8"><?= $request['description'] ?></dd>


                                                                <dt class="col-sm-4">Status</dt>
                                                                <dd class="col-sm-8"><?= $request['status'] ?></dd>

                                                                <dt class="col-sm-4">Date sent</dt>
                                                                <dd class="col-sm-8"><?= $request['datesent'] ?></dd>

                                                                <dt class="col-sm-4">Date decided</dt>
                                                                <dd class="col-sm-8"><?= $request['datedecided'] ?></dd>
                                                            </dl>

                                                        </div>
                                                        <div class="modal-footer" <?php if ($_SESSION['role'] == 'officer' || $request['status'] != "pending") echo "hidden" ?>>
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
                                            <div class="modal fade" id="updateAbsenceRequest<?= $request['requestID'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Update request</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="./index.php?page=request-update-processing&type=absence&rid=<?= $request['requestID'] ?>" method="post">
                                                            <div class="modal-body">
                                                                <label for="title">Title</label>
                                                                <input type="text" id="title" name="title" value="<?= $request['title'] ?>">
                                                                <br></br>
                                                                <label for="description">Reason for absence</label>
                                                                <textarea id="description" name="description"><?= $request['description'] ?></textarea>
                                                                <br></br>
                                                                <label for="date_start_absence">Date start absence</label>
                                                                <input type="date" id="title" name="date_start_absence" value="<?= $request['date_start_absence'] ?>">
                                                                <br></br>
                                                                <label for="date_end_absence">Date end absence</label>
                                                                <input type="date" id="title" name="date_end_absence" value="<?= $request['date_end_absence'] ?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="updateSalaryRequest<?= $request['requestID'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Update request</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="./index.php?page=request-update-processing&type=salary&rid=<?= $request['requestID'] ?>" method="post">
                                                            <div class="modal-body">
                                                                <label for="title">Title</label>
                                                                <input type="text" id="title" name="title" value="<?= $request['title'] ?>">
                                                                <br></br>
                                                                <label for="description">Reason for salary praise</label>
                                                                <textarea id="description" name="description"><?= $request['description'] ?></textarea>
                                                                <br></br>
                                                                <label for="amount">Amount</label>
                                                                <input type="number" id="amount" name="amount" value="<?= $request['amount'] ?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="updateOtherRequest<?= $request['requestID'] ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Update request</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="./index.php?page=request-update-processing&type=other&rid=<?= $request['requestID'] ?>" method="post">
                                                            <div class="modal-body">
                                                                <label for="title">Title</label>
                                                                <input id="title" name="title" value="<?= $request['title'] ?>">
                                                                <br></br>
                                                                <label for="description">Description</label>
                                                                <textarea id="description" name="description"><?= $request['description'] ?></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                            </div>
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

                <div class="modal fade" id="sendRequest" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Choose request type</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <button style="display:block" class="btn btn-primary mb-3" data-bs-target="#sendAbsenceRequest" data-bs-toggle="modal">Send Absence request</button>
                                <button style="display:block" class="btn btn-primary mb-3" data-bs-target="#sendSalaryRequest" data-bs-toggle="modal">Send Salary request</button>
                                <button style="display:block" class="btn btn-primary mb-3" data-bs-target="#sendOtherRequest" data-bs-toggle="modal">Send Other request</button>
                            </div>
                            <div class="modal-footer">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="sendAbsenceRequest" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Absence request</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="./index.php?page=request-send-processing&type=absence" method="POST">
                                <div class="modal-body">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title">
                                    <br></br>
                                    <label for="description">Reason for absence</label>
                                    <textarea id="description" name="description"></textarea>
                                    <br></br>
                                    <label for="date_start_absence">Date start absence</label>
                                    <input type="date" id="title" name="date_start_absence">
                                    <br></br>
                                    <label for="date_end_absence">Date end absence</label>
                                    <input type="date" id="title" name="date_end_absence">
                                </div>
                                <div class="modal-footer">
                                    <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
                                    <button type="submit" class="btn btn-primary">Send request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="sendSalaryRequest" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Salary request</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="./index.php?page=request-send-processing&type=salary" method="POST">
                                <div class="modal-body">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title">
                                    <br></br>
                                    <label for="description">Reason</label>
                                    <textarea id="description" name="description"></textarea>
                                    <br></br>
                                    <label for="amount">Amount</label>
                                    <input type="number" id="amount" name="amount">
                                </div>
                                <div class="modal-footer">
                                    <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
                                    <button type="submit" class="btn btn-primary">Send request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="sendOtherRequest" aria-hidden="true" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Other request</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="./index.php?page=request-send-processing&type=other" method="POST">
                                <div class="modal-body">
                                    <label for="title">Title</label>
                                    <input id="title" name="title">
                                    <br></br>
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
                                    <button type="submit" class="btn btn-primary">Send request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <a class="btn btn-primary" data-bs-toggle="modal" href="#sendRequest" role="button" <?php if ($_SESSION['role'] == 'head') echo "hidden" ?>>Send request</a>


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