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
    for ($i = 0; $i <= 2; $i++) {
        if ($i == 0){
            $status = "pending";
            $tit = "Pending requests";
        }
        elseif ($i == 1){
            $status = "accepted";
            $tit = "Accepted requests";
        }
        else{
            $status = "rejected";
            $tit = "Rejected requests";
        }
        $sql = "SELECT * FROM request WHERE departmentid = '$deid' AND status = '$status'";
        $requestArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    ?>
        <h2><?= $tit ?></h2>
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Title</th>
                    <th>Officer</th>
                    <th>Date sent</th>
                    <th>Date decided</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($requestArray as $request) {
                    $userid = $request['userid'];
                    $sql = "SELECT * FROM user WHERE userid = '$userid'";
                    $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                    if ($_SESSION['username'] != $userArray[0]['username'] && $_SESSION['level'] == "officer")
                        continue;
                    $userName = $userArray[0]['name'];
                ?>
                    <tr>
                        <td><?= $request['requestid'] ?></td>
                        <td><?= $request['title'] ?></td>
                        <td><?= $userName ?></td>
                        <td><?= $request['datesent'] ?></td>
                        <td><?= $request['datedecided'] ?></td>
                        <td><?= $request['status'] ?></td>
                        <td>
                            <a class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewRequest<?= $request['requestid'] ?>">
                                View
                            </a>
                            <a data-bs-toggle="modal" data-bs-target="#updateRequest<?= $request['requestid'] ?>" class=" btn btn-sm rounded-pill btn-outline-warning" <?php if ($_SESSION['level'] == 'head' || $request['status'] != "pending") echo "hidden" ?>>
                                Update
                            </a>
                            <a href="./index.php?page=Delete-request-processing&rid=<?= $request['requestid'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['level'] == 'officer' && $request['status'] != "pending") echo "hidden" ?>>
                                Delete
                            </a>
                        </td>
                    </tr>
                    <div class="modal fade" id="viewRequest<?= $request['requestid'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Request info</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $rid = $request['requestid'];
                                    $sql = "SELECT * FROM request WHERE requestid = '$rid'";
                                    $requestArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                    $requestt = $requestArray[0];
                                    $uid = $request['userid'];
                                    $sql = "SELECT * FROM user WHERE userid = '$uid'";
                                    $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                    $userr = $userArray[0];
                                    ?>
                                    <dl class="row mt-2">
                                        <dt class="col-sm-4">Request ID</dt>
                                        <dd class="col-sm-8"><?= $requestt['requestid'] ?></dd>

                                        <dt class="col-sm-4">Title</dt>
                                        <dd class="col-sm-8"><?= $requestt['title'] ?></dd>

                                        <dt class="col-sm-4">Description</dt>
                                        <dd class="col-sm-8"><?= $requestt['description'] ?></dd>

                                        <dt class="col-sm-4">Officer ID</dt>
                                        <dd class="col-sm-8"><?= $requestt['userid'] ?></dd>

                                        <dt class="col-sm-4">Officer Name</dt>
                                        <dd class="col-sm-8"><?= $userr['name'] ?></dd>

                                        <dt class="col-sm-4">Status</dt>
                                        <dd class="col-sm-8"><?= $requestt['status'] ?></dd>

                                        <dt class="col-sm-4">Date sent</dt>
                                        <dd class="col-sm-8"><?= $requestt['datesent'] ?></dd>

                                        <dt class="col-sm-4">Date decided</dt>
                                        <dd class="col-sm-8"><?= $requestt['datedecided'] ?></dd>
                                    </dl>
                                </div>
                                <div class="modal-footer" <?php if ($_SESSION['level'] == 'officer' && $requestt['status'] != "pending") echo "hidden"?>>
                                    <a  <?php if ($_SESSION['level'] == 'officer' || $requestt['status'] == "pending") echo "hidden" ?> href="./index.php?page=reply-request-processing&rid=<?=$request['requestid']?>&rep=pending" class="btn btn-success">
                                        Pending
                                    </a>
                                    <a  <?php if ($_SESSION['level'] == 'officer' || $requestt['status'] == "accepted") echo "hidden" ?> href="./index.php?page=reply-request-processing&rid=<?=$request['requestid']?>&rep=accepted" class="btn btn-primary">
                                        Accept
                                    </a>
                                    <a  <?php if ($_SESSION['level'] == 'officer' || $requestt['status'] == "rejected") echo "hidden" ?> href="./index.php?page=reply-request-processing&rid=<?=$request['requestid']?>&rep=rejected" class="btn btn-warning">
                                        Reject
                                    </a>
                                    <a href="./index.php?page=delete-request-processing&rid=<?= $request['requestid'] ?>" class="btn btn-danger">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="updateRequest<?= $request['requestid'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5">Request info</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <?php
                                $rid = $request['requestid'];
                                $sql = "SELECT * FROM request WHERE requestid = '$rid'";
                                $requestArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                $requestt = $requestArray[0];
                                $uid = $request['userid'];
                                $sql = "SELECT * FROM user WHERE userid = '$uid'";
                                $userArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
                                $userr = $userArray[0];
                                ?>
                                <form action="./index.php?page=update-request-processing&rid=<?= $request['requestid'] ?>" method="POST">
                                    <div class="modal-body">
                                        <label for="title">Title</label>
                                        <input id="title" name="title" value="<?= $requestt['title'] ?>">
                                        <br>
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description"><?= $requestt['description'] ?></textarea>
                                        <br>
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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendRequest" <?php if ($_SESSION['level'] == 'head') echo "hidden" ?>>
        Send request
    </button>

    <div class="modal fade" id="sendRequest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Send request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./index.php?page=send-request-processing" method="POST">
                    <div class="modal-body">
                        <label for="title">Title</label>
                        <input id="title" name="title">
                        <br>
                        <label for="description">Description</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Send request</button>
                </form>
            </div>
        </div>
    </div>
    </div>

<?php
    require "./components/foot.php";
}
?>