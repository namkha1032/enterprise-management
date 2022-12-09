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
            <div class="page-title mb-2">
                <h1 class="me-4" style="display:inline">Requests</h1>

                <button style="display:inline" data-bs-toggle="modal" data-bs-target="#sendRequest" class="btn btn-primary mb-2" <?php if ($_SESSION['role'] == 'head') echo "hidden" ?>>
                    Send request
                </button>
            </div>
            <section class="section">
                <div class="row">
                    <div class="col-3">
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3>Statistics</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <div class="row">
                                                <li class="nav-item col-12" role="presentation">
                                                    <a class="nav-link active p-3" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                                        <div class="row">
                                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                                <div class="stats-icon purple">
                                                                    <i class="iconly-boldShow"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                                <h6 class="font-semibold">
                                                                    Profile Views
                                                                </h6>
                                                                <h6 class="font-extrabold mb-0">112.000</h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item col-12" role="presentation">
                                                    <a class="nav-link p-3" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                                        <div class="row">
                                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                                <div class="stats-icon purple">
                                                                    <i class="iconly-boldShow"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                                <h6 class="font-semibold">
                                                                    Profile Views
                                                                </h6>
                                                                <h6 class="font-extrabold mb-0">112.000</h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li class="nav-item col-12" role="presentation">
                                                    <a class="nav-link p-3" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                                        <div class="row">
                                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                                <div class="stats-icon purple">
                                                                    <i class="iconly-boldShow"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                                <h6 class="font-semibold">
                                                                    Profile Views
                                                                </h6>
                                                                <h6 class="font-extrabold mb-0">112.000</h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h5>All requests</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="card-title">
                                    <h5>All requests</h5>
                                </div>
                            </div>
                            <div class="card-body">
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
    <div class="modal fade" id="sendRequest" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Choose request type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button style="display:block" class="btn btn-primary mb-3" data-bs-target="#sendAbsenceRequest" data-bs-toggle="modal">Send Absence request</button>
                    <button style="display:block" class="btn btn-primary mb-3" data-bs-target="#sendSalaryRequest" data-bs-toggle="modal">Send Salary request</button>
                    <button style="display:block" class="btn btn-primary mb-3" data-bs-target="#sendOtherRequest" data-bs-toggle="modal">Send Other request</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendAbsenceRequest" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Absence request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./index.php?page=request-send-processing&type=absence" method="POST" class="form form-horizontal">
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
                                <label>Reason</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <textarea name="description" cols="30" rows="3" class="form-control" required style="resize: none;"></textarea>
                                        <div class="form-control-icon">
                                            <i class="bi bi-list-columns-reverse"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Date start</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <input type="date" name="date_start_absence" class="form-control" required />
                                        <div class="form-control-icon">
                                            <i class="bi bi-calendar-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Date end</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <input type="date" name="date_end_absence" class="form-control" required />
                                        <div class="form-control-icon">
                                            <i class="bi bi-calendar-x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
                        <button type="submit" class="btn btn-primary">Send request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendSalaryRequest" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Salary request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./index.php?page=request-send-processing&type=salary" method="POST" class="form form-horizontal">
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
                                <label>Reason</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <textarea name="description" cols="30" rows="3" class="form-control" required style="resize: none;"></textarea>
                                        <div class="form-control-icon">
                                            <i class="bi bi-list-columns-reverse"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Amount</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <input type="number" name="amount" class="form-control" required />
                                        <style>
                                            input::-webkit-outer-spin-button,
                                            input::-webkit-inner-spin-button {
                                                appearance: none;
                                                margin: 0;
                                            }
                                        </style>
                                        <div class="form-control-icon">
                                            <i class="bi bi-currency-dollar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
                        <button type="submit" class="btn btn-primary">Send request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendOtherRequest" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Other request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="./index.php?page=request-send-processing&type=other" method="POST" class="form form-horizontal">
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
                                <label>Reason</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group has-icon-left">
                                    <div class="position-relative">
                                        <textarea name="description" cols="30" rows="3" class="form-control" required style="resize: none;"></textarea>
                                        <div class="form-control-icon">
                                            <i class="bi bi-list-columns-reverse"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" data-bs-target="#sendRequest" data-bs-toggle="modal">Back to first</a>
                        <button type="submit" class="btn btn-primary">Send request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    require "./components/foot.php";
}
?>