<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    // Page
    require "./components/head.php";
?>
    <?php
    require_once "./database.php";
    $deid = $_SESSION['department'];
    for ($i = 0; $i <= 1; $i++) {
        $status = $i == 0 ? "unfinished" : "finished";
        $tit = $i == 0 ? "Unfinished tasks" : "Finished Task";
        $sql = "SELECT * FROM task WHERE departmentid = '$deid' AND status = '$status'";
        $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    ?>
        <h2><?= $tit ?></h2>
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Officer</th>
                    <th>Assigned Date</th>
                    <th>Deadline</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($taskArray as $task) {
                    $userid = $task['userid'];
                    $sql = "SELECT * FROM user WHERE userid = '$userid'";
                    $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                    if ($_SESSION['username'] != $userArray[0]['username'] && $_SESSION['level'] == "officer")
                        continue;
                    $userName = $userArray[0]['name'];
                ?>
                    <?php $tid = $task['taskid'] ?>
                    <tr>
                        <td><?= $task['taskid'] ?></td>
                        <td><?= $task['title'] ?></td>
                        <td><?= $userName ?></td>
                        <td><?= $task['assigneddate'] ?></td>
                        <td><?= $task['deadline'] ?></td>
                        <td>
                            <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewTask<?= $task['taskid'] ?>">
                                View
                            </button>
                            <button data-bs-toggle="modal" data-bs-target="#updateTask<?= $task['taskid'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['level'] != 'head' || $task['status'] == "finished") echo "hidden" ?>>
                                Update
                            </button>
                            <a href="./index.php?page=finish-task-processing&tid=<?= $task['taskid'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['level'] != 'head') echo "hidden" ?>>
                                Delete
                            </a>
                        </td>
                    </tr>
                    <div class="modal fade" id="viewTask<?= $task['taskid'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Task info</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $tid = $task['taskid'];
                                    $sql = "SELECT * FROM task WHERE taskid = '$tid'";
                                    $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                    $taskk = $taskArray[0];
                                    $uid = $task['userid'];
                                    $sql = "SELECT * FROM user WHERE userid = '$uid'";
                                    $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                    $userr = $userArray[0];
                                    ?>
                                    <dl class="row mt-2">
                                        <dt class="col-sm-4">Task ID</dt>
                                        <dd class="col-sm-8"><?= $taskk['taskid'] ?></dd>

                                        <dt class="col-sm-4">Title</dt>
                                        <dd class="col-sm-8"><?= $taskk['title'] ?></dd>

                                        <dt class="col-sm-4">Description</dt>
                                        <dd class="col-sm-8"><?= $taskk['description'] ?></dd>

                                        <dt class="col-sm-4">Officer ID</dt>
                                        <dd class="col-sm-8"><?= $taskk['userid'] ?></dd>

                                        <dt class="col-sm-4">Officer Name</dt>
                                        <dd class="col-sm-8"><?= $userr['name'] ?></dd>

                                        <dt class="col-sm-4">Status</dt>
                                        <dd class="col-sm-8"><?= $taskk['status'] ?></dd>

                                        <dt class="col-sm-4">Assigned date</dt>
                                        <dd class="col-sm-8"><?= $taskk['assigneddate'] ?></dd>

                                        <dt class="col-sm-4">Deadline</dt>
                                        <dd class="col-sm-8"><?= $taskk['deadline'] ?></dd>

                                        <dt class="col-sm-4">Finished date</dt>
                                        <dd class="col-sm-8"><?= $taskk['checkoutdate'] ?></dd>
                                    </dl>
                                </div>
                                <div class="modal-footer" <?php if ($_SESSION['level'] == 'head' || $taskk['status'] == "finished") echo "hidden" ?>>
                                    <a href="./index.php?page=finish-task-processing&tid=<?= $taskk['taskid'] ?>" class="btn btn-primary">
                                        Finish
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="updateTask<?= $task['taskid'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Task info</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <?php
                                $tid = $task['taskid'];
                                $sql = "SELECT * FROM task WHERE taskid = '$tid'";
                                $taskArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                $taskk = $taskArray[0];
                                $uid = $task['userid'];
                                $sql = "SELECT * FROM user WHERE userid = '$uid'";
                                $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                $userr = $userArray[0];
                                ?>

                                <form action="./index.php?page=update-task-processing&tid=<?= $task['taskid'] ?>" method="POST">
                                    <div class="modal-body">
                                        <label for="title">Title</label>
                                        <input id="title" name="title" value="<?= $taskk['title'] ?>">
                                        <br>
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description"><?= $taskk['description'] ?></textarea>
                                        <br>
                                        <label for="userid">Choose user:</label>

                                        <select name="userid" id="userid" value="<?= $userr['name'] ?>">
                                            <?php
                                            $sql = "SELECT * FROM user WHERE departmentid = '$deid'";
                                            $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                            foreach ($userArray as $userr) {
                                                if ($userr['level'] == 'head')
                                                    continue;
                                            ?>
                                                <option value="<?= $userr['userid'] ?>"><?= $userr['name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <br>
                                        <label for="deadline">Deadline</label>
                                        <input id="deadline" name="deadline" type="date" value="<?= $taskk['deadline'] ?>">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <a href = "./index.php?page=user" type="button" class="btn btn-primary"  <?php if ($_SESSION['level'] != 'head') echo "hidden" ?>>
        Assign task
    </button>

    

<?php
    require "./components/foot.php";
}
?>