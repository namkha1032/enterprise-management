<?php
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
} else {
  // Page
  require_once "./database.php";
  $employeeID = $_GET['employeeID'];
  $sql = "SELECT * FROM employee 
        INNER JOIN account ON employee.username = account.username WHERE employee.employeeID='$employeeID'";
  $em = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
  $sql = "SELECT * FROM department ";
  $departmentArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
  require "./components/head.php";
?>
  <!-- ///////////////////////////////////////////////////////// -->
  <!-- <form action="upload.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
  </form>
  <a href="./processing/download-processing.php?file=../uploads/task assignment.png">click to download</a> -->
  <!-- ///////////////////////////////////////////////////////// -->
  <div id="main-content">
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <div class="col-12 col-md-6 order-md-1 order-last">
            <h3 style="display:inline" class="me-4">Personal Profile</h3>
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

        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><?= $em['name'] ?></h4>
          </div>
          <div class="card-body">
            <div class="avatar me-3">
              <img src="<?= $em['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
            </div>
            <dl class="row mt-2" style="width:30%">
              <dt class="col-sm-4">Employee ID</dt>
              <dd class="col-sm-8"><?= $em['employeeID'] ?></dd>

              <dt class="col-sm-4">Name</dt>
              <dd class="col-sm-8"><?= $em['name'] ?></dd>

              <dt class="col-sm-4">Username</dt>
              <dd class="col-sm-8"><?= $em['username'] ?></dd>

              <dt class="col-sm-4">Password</dt>
              <dd class="col-sm-8"><?= $em['password'] ?></dd>


              <dt class="col-sm-4">Gender</dt>
              <dd class="col-sm-8"><?= $em['gender'] ?></dd>

              <dt class="col-sm-4">Date of Birth</dt>
              <dd class="col-sm-8"><?= date_format(date_create($em['dob']), "d/m/Y") ?></dd>

              <dt class="col-sm-4">Nationality</dt>
              <dd class="col-sm-8"><?= $em['nationality'] ?></dd>

              <dt class="col-sm-4">Address</dt>
              <dd class="col-sm-8"><?= $em['address'] ?></dd>

              <dt class="col-sm-4">Phone</dt>
              <dd class="col-sm-8"><?= $em['phone'] ?></dd>

              <dt class="col-sm-4">Salary</dt>
              <dd class="col-sm-8"><?= $em['salary'] ?></dd>

              <dt class="col-sm-4">Start Date</dt>
              <dd class="col-sm-8"><?= date_format(date_create($em['startDate']), "d/m/Y") ?></dd>

              <dt class="col-sm-4">Department</dt>
              <dd class="col-sm-8"><?= $em['departID'] ?></dd>
            </dl>
            <button data-bs-toggle="modal" data-bs-target="#updateEmployee" class="btn btn-primary" <?php if ($_SESSION['role'] != 'admin') echo "hidden" ?>>
              Update
            </button>
            <div class="modal fade" id="updateEmployee" tabindex="-1" aria-hidden="true">

              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5">Update employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="./index.php?page=employee-update-processing&emid=<?= $em['employeeID'] ?>" method="POST">
                    <div class="modal-body">
                      <label for="address">Address</label>
                      <textarea id="address" name="address" required><?= $em['address'] ?></textarea>
                      <br>
                      <label for="phone">Phone</label>
                      <input type='text' id="phone" name="phone" value="<?= $em['phone'] ?>" required>
                      <br>
                      <label for="salary">Salary</label>
                      <input id="salary" name="salary" type="number" value="<?= $em['salary'] ?>" required>
                      <br>
                      <label for="departID" <?php if ($em['role'] == "head") echo "hidden" ?>>Department:</label>
                      <select name="departID" id="departID" value="<?= $em['departID'] ?>" <?php if ($em['role'] == "head") echo "hidden" ?>>
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
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </form>
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
            <input id="title" name="title" required>
            <br>
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
            <br>
            <label for="deadline">Deadline</label>
            <input id="deadline" name="deadline" type="date" required>
            <br>
            <label for="officerID">Choose employee:</label>
            <select name="officerID" id="officerID" required>
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

<?php
  require "./components/foot.php";
}
?>