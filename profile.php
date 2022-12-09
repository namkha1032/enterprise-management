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
            <form class="form" style="margin-top: 20px;">
              <div class="row">
                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Employee ID</label>
                    <div class="position-relative">
                      <input type="text" id="first-name-column" class="form-control" value="<?= $em['employeeID'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-id-card"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Name</label>
                    <div class="position-relative">
                      <input type="text" id="first-name-column" class="form-control" value="<?= $em['name'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-person"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Username</label>
                    <div class="position-relative">
                      <input type="text" id="first-name-column" class="form-control" value="<?= $em['username'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-user"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Password</label>
                    <div class="position-relative">
                      <input type="text" id="first-name-column" class="form-control" value="<?= $em['password'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-lock"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Gender</label>
                    <div class="position-relative">
                      <input type="text" id="first-name-column" class="form-control" value="<?= $em['gender'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-venus-mars"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Date of birth</label>
                    <div class="position-relative">
                      <input type="text" id="first-name-column" class="form-control" value="<?= date_format(date_create($em['dob']), "d/m/Y") ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-cake-candles"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Nationality</label>
                    <div class="position-relative">
                      <input type="text" id="last-name-column" class="form-control" value="<?= $em['nationality'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-globe"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Address</label>
                    <div class="position-relative">
                      <input type="text" id="last-name-column" class="form-control" value="<?= $em['address'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-location-dot"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Phone number</label>
                    <div class="position-relative">
                      <input type="text" id="last-name-column" class="form-control" value="<?= $em['phone'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-phone"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Salary</label>
                    <div class="position-relative">
                      <input type="text" id="last-name-column" class="form-control" value="<?= $em['salary'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-dollar-sign"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Start date</label>
                    <div class="position-relative">
                      <input type="text" id="last-name-column" class="form-control" value="<?= date_format(date_create($em['startDate']), "d/m/Y") ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-12">
                  <div class="form-group has-icon-left">
                    <label for="first-name-column">Department</label>
                    <div class="position-relative">
                      <input type="text" id="last-name-column" class="form-control" value="<?= $em['departID'] ?>" readonly />
                      <div class="form-control-icon">
                        <i class="fa-solid fa-building"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

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