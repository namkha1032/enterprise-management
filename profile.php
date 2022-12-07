<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    require_once "./database.php";
    $employeeID = $_GET['employeeID'];
    $sql = "SELECT * FROM employee 
        INNER JOIN account ON employee.username = account.username WHERE employee.employeeID='$employeeID'";
    $em = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
    $sql = "SELECT * FROM department ";
    $departmentArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    require "./components/head.php";
?>
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
                        <textarea id="address" name="address"><?= $em['address'] ?></textarea>
                        <br>
                        <label for="phone">Phone</label>
                        <input type='text' id="phone" name="phone" value="<?= $em['phone'] ?>">
                        <br>
                        <label for="salary">Salary</label>
                        <input id="salary" name="salary" type="number" value="<?= $em['salary'] ?>">
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
<?php
    require "./components/foot.php";
}

?>