<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    // Page
    require "./components/head.php";
    require_once "./database.php";
    $sql = "SELECT * FROM department";
    $departmentArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

    <?php
    foreach ($departmentArray as $department) {
        if ($department['departID'] == 'DE0001')
            continue;
        $deid = $department['departID'];
        $sql = "SELECT * FROM announce
                INNER JOIN department ON annouce.departID = department.departID
                INNER JOIN employee ON annouce.headID = employee.headID
                WHERE announce.departID='$deid'
                ORDER BY announce.announceDate DESC";
        $announceArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        
    ?>
            <h2>Department: <?= $department['name'] ?></h2>
            <table class="table table-hover datatable">
                <thead>
                    <tr>
                        <th>Announce ID</th>
                        <th>Title</th>
                        <th>Announce date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($announceArray as $announce) {
                    ?>
                        <tr>
                            <td><?= $announce['announceID'] ?></td>
                            <td><?= $announce['title'] ?></td>
                            <td><?= $announce['announceDate'] ?></td>
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
<?php
    require "./components/foot.php";
}
?>