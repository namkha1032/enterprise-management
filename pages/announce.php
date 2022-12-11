<?php
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
} else {
  // Page
  require_once "./database.php";
  require "./components/head.php";
  $deid = $_SESSION['departID'];
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
      <div class="page-title mb-2">
        <h1 style="display:inline" class="me-4">Announcement</h1>
        <div class="mb-4" <?php if ($_SESSION['role'] != 'officer') echo "style='display:inline'" ?>>
          <button style="display:inline" data-bs-toggle="modal" data-bs-target="#createAnnounce" class="btn btn-primary rounded-pill mb-4" <?php if ($_SESSION['role'] == 'officer') echo 'hidden' ?>>
            Create Announce
          </button>
        </div>
      </div>
      <section class="section">
        <?php
        $anArray = array();
        $departName = '';
        if ($_SESSION['role'] == 'ceo') {
          $anArray = $conn->query('SELECT * FROM announce INNER JOIN employee ON announce.upperID = employee.employeeID ORDER BY announce.announceID DESC')->fetch_all(MYSQLI_ASSOC);
          $departName = 'all';
        } else {
          $sql = "SELECT * FROM announce
          JOIN employee ON announce.upperID = employee.employeeID
          WHERE announce.departID='$deid' OR  announce.departID = 'DE0001'
          ORDER BY announce.announceID DESC";
          $anArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        }
        $sql = "SELECT * FROM department WHERE departID='$deid'";
        $departName = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0]['name'];
        ?>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Announcements from department <?= $departName ?></h3>
          </div>
          <div class="card-body">
            <table class="table table-hover datatable">
              <thead>
                <tr>
                  <th>Announce ID</th>
                  <th>Title</th>
                  <th>Announce Date</th>
                  <th>Announcer</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($anArray as $an) {

                ?>
                  <?php $tid = $an['announceID'] ?>
                  <tr>
                    <td><?= $an['announceID'] ?></td>
                    <td><?= $an['title'] ?></td>
                    <td><?= $an['announceDate'] ?></td>
                    <td><?= $an['name'] ?></td>
                    <td>
                      <button class="btn btn-sm rounded-pill btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewAnnounce<?= $an['announceID'] ?>">
                        View
                      </button>
                      <a href="./index.php?page=task-delete-processing&tid=<?= $an['announceID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger" <?php if ($_SESSION['role'] != 'ceo' && $an['upperID'] != $_SESSION['employeeID']) echo "hidden"; ?>>
                        Delete
                      </a>
                    </td>
                  </tr>
                  <!-- Modal for viewing announce -->
                  <div class="modal fade" id="viewAnnounce<?= $an['announceID'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5">Announcement info</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="avatar mb-4 d-flex justify-content-center">
                            <img src="<?= $an['avatar'] ?>" style="object-fit: cover; height:130px; width:130px" alt="" srcset="" />
                          </div>
                          <form class="form form-horizontal">
                            <div class="row">
                              <div class="col-md-4">
                                <label>Announcement ID</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <input type="text" class="form-control" readonly value="<?= $an['announceID'] ?>" />
                              </div>

                              <div class="col-md-4">
                                <label>Title</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <input type="text" class="form-control" readonly value="<?= $an['title'] ?>" />
                              </div>

                              <div class="col-md-4">
                                <label>Description</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <textarea class="form-control" readonly rows="5" cols="30" style="resize:none;"><?= $an['description'] ?></textarea>
                              </div>

                              <div class="col-md-4">
                                <label>Announcer ID</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <input type="text" class="form-control" readonly value="<?= $an['upperID'] ?>" />
                              </div>

                              <div class="col-md-4">
                                <label>Announcer name</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <input type="text" class="form-control" readonly value="<?= $an['name'] ?>" />
                              </div>

                              <div class="col-md-4">
                                <label>Announce date</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <input type="text" class="form-control" readonly value="<?= date_format(date_create($an['announceDate']), "d/m/Y") ?>" />
                              </div>

                              <div class="col-md-4">
                                <label>Announce file</label>
                              </div>
                              <div class="col-md-8 form-group">
                                <a href="./processing/file-download-processing.php?file=<?= $an['announceFile'] ?>"><input type="text" class="form-control" readonly value="<?= str_replace("../files/announceFiles/", "", $an['announceFile']) ?>" style="cursor:pointer; color:blue;" /></a>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </div>

    <footer>
      <div class="footer clearfix mb-0 text-muted">


      </div>
    </footer>
  </div>
  <div class="modal fade" id="createAnnounce" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Create announce</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="./index.php?page=announce-create-processing" method="POST" enctype="multipart/form-data" class="form form-horizontal">
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
                <label>Description</label>
              </div>
              <div class="col-md-8">
                <div class="form-group has-icon-left">
                  <div class="position-relative">
                    <textarea name="description" class="form-control" id="first-name-icon" cols="30" rows="5" placeholder="Announcement description..." style="resize:none;"></textarea>
                    <div class="form-control-icon">
                      <i class="bi bi-list-columns-reverse"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <label>Announce file</label>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <input type="file" name="fileToUpload" class="form-control" required />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
  require "./components/foot.php";
}
?>