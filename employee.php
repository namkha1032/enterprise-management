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
          if ($department['departID'] == 'DE0001')
            continue;
          $deid = $department['departID'];
          if ($_SESSION['role'] != 'admin' && $_SESSION['departID'] != $deid)
            continue;
          $sql = "SELECT * FROM employee 
        INNER JOIN account ON employee.username = account.username WHERE employee.departID='$deid'";
          $emArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        ?>
          <div class="card border border-dark">
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

<?php
  require "./components/foot.php";
}
?>