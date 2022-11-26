<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    require_once "./database.php";
    $sql = "SELECT * FROM department WHERE name <> 'Admin'";
    $departmentArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    // Page
    require "./components/head.php";
?>

    <?php
    foreach ($departmentArray as $department) {
        $deid = $department['departmentid'];
        if ($_SESSION['department'] != $deid && $_SESSION['level'] != 'admin')
            continue;
        $sql = "SELECT * FROM user WHERE departmentid='$deid'";
        $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    ?>
        <h2>Department: <?= $department['name'] ?></h2>
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th <?php if ($_SESSION['level'] != 'admin') echo "hidden" ?>>Password</th>
                    <th>Level</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Date of birth</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Start date</th>
                    <th>Salary</th>
                    <th>Department</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userArray as $userr) {
                ?>
                    <tr>
                        <td><?= $userr['userid'] ?></td>
                        <td><?= $userr['username'] ?></td>
                        <td <?php if ($_SESSION['level'] != 'admin') echo "hidden" ?>><?= $userr['password'] ?></td>
                        <td><?= $userr['level'] ?></td>
                        <td><?= $userr['name'] ?></td>
                        <td><?= $userr['gender'] ?></td>
                        <td><?= $userr['dob'] ?></td>
                        <td><?= $userr['phone'] ?></td>
                        <td><?= $userr['address'] ?></td>
                        <td><?= $userr['startdate'] ?></td>
                        <td><?= $userr['salary'] ?></td>
                        <td><?= $department['name'] ?></td>
                        <td>
                            <a class="btn btn-sm rounded-pill btn-outline-success">
                                View
                            </a>
                            <button data-bs-toggle="modal" data-bs-target="#assignTask<?= $userr['userid'] ?>" class="btn btn-sm rounded-pill btn-outline-primary" <?php if ($_SESSION['level'] != 'head' || $userr['level'] == 'head') echo "hidden" ?>>
                                Assign task
                            </button>
                            <a href="#" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['level'] != 'admin') echo "hidden" ?>>
                                Delete
                            </a>
                        </td>
                    </tr>
                    <div class="modal fade" id="assignTask<?= $userr['userid'] ?>" tabindex="-1" aria-hidden="true">
                        <?php
                        $uid = $userr['userid'];
                        ?>
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Assign task</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="./index.php?page=assign-task-processing&uid=<?= $uid ?>" method="POST">
                                    <div class="modal-body">
                                        <label for="title">Title</label>
                                        <input id="title" name="title">
                                        <br>
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description"></textarea>
                                        <br>
                                        <label for="deadline">Deadline</label>
                                        <input id="deadline" name="deadline" type="date">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    <?php
    }
    ?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" <?php if ($_SESSION['level'] != 'admin') echo "hidden" ?>>
        Add user
    </button>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add new user</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./processing/insert-user-processing.php"></form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<?php
    require "./components/foot.php";
}
?>