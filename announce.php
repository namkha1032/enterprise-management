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
      <div class="page-title">
        <div class="row">
          <div class="col-12 col-md-6 order-md-1 order-last">
            <h3 style="display:inline" class="me-4">Announcement</h3>
            <!-- <p class="text-subtitle text-muted">
              Navbar will appear on the top of the page.
            </p> -->
            <button style="display:inline" data-bs-toggle="modal" data-bs-target="#createAnnounce" class="btn btn-primary mb-2" <?php if ($_SESSION['role'] != 'head') echo "hidden" ?>>
              Create announcement
            </button>
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
        <?php
        $sql = "SELECT * FROM announce
        JOIN employee ON announce.upperID = employee.employeeID
        WHERE announce.departID='$deid'";
        $anArray = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        $sql = "SELECT * FROM department WHERE departID='$deid'";
        $departName = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0]['name'];
        ?>
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Announcements from department <?= $departName ?></h4>
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
                      <a href="./index.php?page=task-delete-processing&tid=<?= $an['announceID'] ?>" class="btn btn-sm rounded-pill btn-outline-danger">
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
                          <dl class="row mt-2">
                            <dt class="col-sm-4">Announcement ID</dt>
                            <dd class="col-sm-8"><?= $an['announceID'] ?></dd>

                            <dt class="col-sm-4">Title</dt>
                            <dd class="col-sm-8"><?= $an['title'] ?></dd>

                            <dt class="col-sm-4">Description</dt>
                            <dd class="col-sm-8"><?= $an['description'] ?></dd>

                            <dt class="col-sm-4">Announcer ID</dt>
                            <dd class="col-sm-8"><?= $an['upperID'] ?></dd>

                            <dt class="col-sm-4">Announcer name</dt>
                            <dd class="col-sm-8"><?= $an['name'] ?></dd>

                            <dt class="col-sm-4">Announce date</dt>
                            <dd class="col-sm-8"><?= $an['announceDate'] ?></dd>

                            <dt class="col-sm-4">Announce file</dt>
                            <dd class="col-sm-8"><a href="./processing/file-download-processing.php?file=<?= $an['announceFile'] ?>"><?= str_replace("../files_announce/", "", $an['announceFile']) ?></a></dd>


                          </dl>
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
        <form action="./index.php?page=announce-create-processing" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <label for="title">Title</label>
            <input id="title" name="title" required>
            <br>
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
            <br>
            <label for="fileToUpload">Announce file</label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>
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