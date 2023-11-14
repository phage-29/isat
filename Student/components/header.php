<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>
        <?= $page ?>
    </title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- isat-logos -->
    <link href="assets/img/isat-logo.png" rel="icon">
    <link href="assets/img/isat-logo.png" rel="isat-logo">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <?php
    if ($_SESSION['Role'] == "Student") {
        $query = "SELECT * FROM students WHERE UserID = ?";
        $result = $conn->execute_query($query, [$acc->id]);
        if ($result->num_rows == 0) {
            ?>

            <!-- Modal -->
            <div class="modal fade" id="StudentProfileModal" data-bs-backdrop="static" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Student Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form inside the modal -->
                            <form action="../includes/process.php" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">ID Number</label>
                                    <input type="text" class="form-control" name="IDNo" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Course</label>
                                    <input type="text" class="form-control" name="Course" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Year</label>
                                    <input type="text" class="form-control" name="Year" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <input type="text" class="form-control" name="Section" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Academic Status</label>
                                    <input type="text" class="form-control" name="AcademicStatus" required />
                                </div>
                                <div class="mb-3 text-end">
                                    <input type="hidden" name="Student" value="<?= $acc->id ?>" />
                                    <a href="../includes/logout.php" class="btn btn-secondary">Logout</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var myModal = new bootstrap.Modal(document.getElementById('StudentProfileModal'));
                    myModal.toggle();
                    myModal.show();
                });
            </script>
            <?php
        }
    }
    ?>