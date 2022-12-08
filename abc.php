<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Layout - Mazer Admin Dashboard</title>

    <link rel="stylesheet" href="assets/css/main/app.css" />
    <link rel="stylesheet" href="assets/css/main/app-dark.css" />
    <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon" />
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png" />
</head>

<body>
    <script src="assets/js/initTheme.js"></script>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Horizontal Form</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form class="form form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>First Name</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="first-name" class="form-control" readonly />
                            </div>
                            <div class="col-md-4">
                                <label>Email</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="email" id="email-id" class="form-control" name="email-id" placeholder="Email" />
                            </div>
                            <div class="col-md-4">
                                <label>Mobile</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="number" id="contact-info" class="form-control" name="contact" placeholder="Mobile" />
                            </div>
                            <div class="col-md-4">
                                <label>Password</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password" />
                            </div>
                            <div class="col-12 col-md-8 offset-md-4 form-group">
                                <div class="form-check">
                                    <div class="checkbox">
                                        <input type="checkbox" id="checkbox1" class="form-check-input" checked />
                                        <label for="checkbox1">Remember Me</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>