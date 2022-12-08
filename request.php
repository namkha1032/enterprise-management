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
?>

  <div id="main-content">
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <div class="col-12 col-md-6 order-md-1 order-last">
            <h3 class="me-4" style="display:inline">Requests</h3>
            <!-- <a style="display:inline" class="btn btn-primary mb-3" data-bs-toggle="modal" href="#sendRequest" role="button" <?php if ($_SESSION['role'] == 'head') echo "hidden" ?>>
              Send request
            </a> -->
            <button style="display:inline" data-bs-toggle="modal" data-bs-target="#sendRequest" class="btn btn-primary mb-2" <?php if ($_SESSION['role'] == 'head') echo "hidden" ?>>
              Send request
            </button>
            <!-- <p class="text-subtitle text-muted">
              Navbar will appear on the top of the page.
            </p> -->
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
        <?php
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
              <h4 class="card-title"><?= $tit ?></h4>
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
                              <input type="text" id="title" name="title" value="<?= $request['title'] ?>" required>
                              <br></br>
                              <label for="description">Reason for absence</label>
                              <textarea id="description" name="description" required><?= $request['description'] ?></textarea>
                              <br></br>
                              <label for="date_start_absence">Date start absence</label>
                              <input type="date" id="title" name="date_start_absence" value="<?= $request['date_start_absence'] ?>" required>
                              <br></br>
                              <label for="date_end_absence">Date end absence</label>
                              <input type="date" id="title" name="date_end_absence" value="<?= $request['date_end_absence'] ?>" required>
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
                          <form action="./index.php?page=request-update-processing&type=salary&rid=<?= $request['requestID'] ?>" method="post" class="form form-horizontal">
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-4">
                                  <label>Title</label>
                                </div>
                                <div class="col-md-8">
                                  <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                      <input type="text" name="title" class="form-control" value="<?= $request['title'] ?>" required />
                                      <div class="form-control-icon">
                                        <i class="bi bi-card-list"></i>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <label>Reason for salary praise</label>
                                </div>
                                <div class="col-md-8">
                                  <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                      <textarea name="description" cols="30" rows="3" required class="form-control" style="resize:none;"><?= $request['description'] ?></textarea>
                                      <div class="form-control-icon">
                                        <i class="bi bi-list-columns-reverse"></i>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <div class="col-md-4">
                                  <label>Amount</label>
                                </div>
                                <div class="col-md-8">
                                  <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                      <input type="number" name="amount" class="form-control" value="<?= $request['amount'] ?>" required />
                                      <style>
                                        input::-webkit-outer-spin-button,
                                        input::-webkit-inner-spin-button {
                                          appearance: none;
                                          margin: 0;
                                        }
                                      </style>
                                      <div class="form-control-icon">
                                        <i class="bi bi-currency-dollar"></i>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
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
                              <input id="title" name="title" value="<?= $request['title'] ?>" required>
                              <br></br>
                              <label for="description">Description</label>
                              <textarea id="description" name="description" required><?= $request['description'] ?></textarea>
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
    </div>

    <footer>
      <div class="footer clearfix mb-0 text-muted">

      </div>
    </footer>
  </div>
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
        <form action="./index.php?page=request-send-processing&type=absence" method="POST" class="form form-horizontal">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <label>Title</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="title" class="form-control" required />
                    <div class="form-control-icon">
                      <i class="bi bi-card-list"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Reason</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <textarea name="description" cols="30" rows="3" class="form-control" required style="resize: none;"></textarea>
                    <div class="form-control-icon">
                      <i class="bi bi-list-columns-reverse"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Date start</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="date" name="date_start_absence" class="form-control" required />
                    <div class="form-control-icon">
                      <i class="bi bi-calendar-check"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Date end</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="date" name="date_end_absence" class="form-control" required />
                    <div class="form-control-icon">
                      <i class="bi bi-calendar-x"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
            <dl class="row mt-2">
              <dt class="col-sm-5"><label for="title">Title</label></dt>
              <dd class="col-sm-7"><input type="text" id="title" name="title" required></dd>

              <dt class="col-sm-5"><label for="description">Reason</label></dt>
              <dd class="col-sm-7"><textarea id="description" name="description" required></textarea></dd>

              <dt class="col-sm-5"><label for="amount">Amount</label></dt>
              <dd class="col-sm-7"><input type="number" id="amount" name="amount" required></dd>
            </dl>
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
            <dl class="row mt-2">
              <dt class="col-sm-5"><label for="title">Title</label></dt>
              <dd class="col-sm-7"><input id="title" name="title" required></dd>

              <dt class="col-sm-5"><label for="description">Description</label></dt>
              <dd class="col-sm-7"><textarea id="description" name="description" required></textarea></dd>
            </dl>
          </div>
          <div class="modal-footer">
            <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
            <button type="submit" class="btn btn-primary">Send request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  require "./components/foot.php";
}
?>