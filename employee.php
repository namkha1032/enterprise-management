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
            <h3>Human Resources</h3>
            <!-- <p class="text-subtitle text-muted">
              Navbar will appear on the top of the page.
            </p> -->
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
          if ($department['departID'] == 'DE0001' || $department['departID'] == 'DE0002')
            continue;
          $deid = $department['departID'];
          if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'ceo' && $_SESSION['departID'] != $deid)
            continue;
          $sql = "SELECT * FROM employee 
        INNER JOIN account ON employee.username = account.username WHERE employee.departID='$deid'";
          $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        ?>
          <div class="card h-100 mb-4">
            <div class="card-header">
              <h4 class="card-title">Department: <?= $department['name'] ?></h4>
            </div>
            <div class="card-body" style="width:100%">
              <table class="table table-hover datatable">
                <thead>
                  <tr>
                    <th>Employee ID</th>
                    <th>Role</th>
                    <th>Name</th>
                    <th>Salary</th>
                    <th>Department</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($emArray as $em) {
                  ?>
                    <tr>
                      <td><?= $em['employeeID'] ?></td>
                      <td><?= $em['role'] ?></td>
                      <td>
                        <div class="avatar me-3">
                          <img src="<?= $em['avatar'] ?>" style="object-fit: cover;" alt="" srcset="" />
                        </div><?= $em['name'] ?>
                      </td>
                      <td><?= $em['salary'] ?></td>
                      <td><?= $department['name'] ?></td>
                      <td>
                        <a href="./index.php?page=profile&employeeID=<?= $em['employeeID'] ?>" class="btn btn-sm rounded-pill btn-outline-success">
                          View
                        </a>
                        <a href="index.php?page=employee-sethead-processing&username=<?= $em['username'] ?>&depart=<?= $em['departID'] ?>" class="btn btn-sm rounded-pill btn-outline-primary" <?php if ($_SESSION['role'] != 'admin' || $em['role'] == 'head') echo "hidden" ?>>
                          Set head
                        </a>
                        <a href="./index.php?page=employee-delete-processing&username=<?= $em['username'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'admin' || $em['role'] == 'head') echo "hidden" ?>>
                          Delete
                        </a>
                      </td>
                    </tr>
                    <!-- <div class="modal fade" id="viewEmployee<?= $em['employeeID'] ?>" tabindex="-1" aria-hidden="true">
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
                    </div> -->
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

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertEmployee" <?php if ($_SESSION['role'] != 'admin') echo "hidden" ?>>
          Add employee
        </button>
      </div>
    </footer>
  </div>

  <div class="modal fade" id="insertEmployee" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Add new employee</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="./index.php?page=employee-insert-processing" method="POST" class="form form-horizontal">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <label>Name</label><span class="text-danger">*</span>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="name" class="form-control" placeholder="Name..." id="first-name-icon" required autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-person"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Username</label><span class="text-danger">*</span>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="username" class="form-control" placeholder="Username..." id="first-name-icon" required autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-person-vcard"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Password</label><span class="text-danger">*</span>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="password" class="form-control" placeholder="Password..." id="first-name-icon" required autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-shield-lock"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Date of birth</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="date" name="dob" class="form-control" placeholder="Date of birth..." id="first-name-icon" />
                    <div class="form-control-icon">
                      <i class="bi bi-calendar"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Nationality</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="nationality" class="form-control" placeholder="Nationality..." id="first-name-icon" autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-globe"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Address</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="address" class="form-control" placeholder="Address..." id="first-name-icon" autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-house"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Phone number</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="text" name="phone" class="form-control" placeholder="Phone..." id="first-name-icon" autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-telephone"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Salary</label><span class="text-danger">*</span>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <input type="number" name="salary" class="form-control" placeholder="Salary..." id="first-name-icon" required autocomplete="off" />
                    <div class="form-control-icon">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                  </div>
                </div>
              </div>
              <style>
                input::-webkit-outer-spin-button,
                input::-webkit-inner-spin-button {
                  appearance: none;
                  margin: 0;
                }
              </style>

              <div class="col-md-4">
                <label>Role</label><span class="text-danger">*</span>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <select name="role" id="role" required style="width:100%">
                      <option selected hidden>Please choose a role...</option>
                      <option value="officer">Officer</option>
                      <option value="head">Head</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Department</label><span class="text-danger">*</span>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <select name="departID" id="departID" required style="width:100%">
                      <option selected hidden>Please choose a department...</option>
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
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Gender</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <select name="gender" id="gender" style="width:100%">
                      <option selected hidden>Please choose employee's gender...</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Insert</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  require "./components/foot.php";
}
?>