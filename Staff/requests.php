<?php
$page = "Request Review";
$Role = "Staff";
require_once "../includes/session.php";
require_once "components/header.php";
require_once "components/topbar.php";
require_once "components/sidebar.php";
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>
            <?= $page ?>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Pages</li>
                <li class="breadcrumb-item active">
                    <?= $page ?>
                </li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="card-body">
                                <h5 class="card-title">My Requests</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Requestor</th>
                                            <th scope="col">Payment No.</th>
                                            <th scope="col">Reference No.</th>
                                            <th scope="col">Method</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = $conn->query("SELECT p.*, u.*, s.* FROM payments p LEFT JOIN users u ON p.UserID = u.id LEFT JOIN students s ON s.UserID = u.id ORDER BY UpdatedAt DESC");
                                        while ($row = $result->fetch_object()) {
                                            ?>
                                            <tr>
                                                <th scope="row">
                                                    <?= $row->id ?>
                                                </th>
                                                <td>
                                                    <a href="?UserID=<?= $row->UserID ?>">
                                                        <?= $row->LastName ?>,
                                                        <?= $row->FirstName ?>
                                                    </a>
                                                </td>
                                                <td><a href="?PaymentNo=<?= $row->PaymentNo ?>">
                                                        <?= $row->PaymentNo ?>
                                                    </a></td>
                                                <td>
                                                    <?= $row->ReferenceNo ?>
                                                </td>
                                                <td>
                                                    <?= $row->Method ?>
                                                </td>
                                                <td>
                                                    <?= $row->Total ?>
                                                </td>
                                                <td>
                                                    <?= $row->Status == "Pending" ? '<span class="badge rounded-pill bg-warning">For Approval</span>' : ($row->Status == "Processing" ? '<span class="badge rounded-pill bg-primary">Processing</span>' : ($row->Status == "Completed" ? '<span class="badge rounded-pill bg-success">To Claim</span>' : '<span class="badge rounded-pill bg-danger">Failed</span>')) ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->
                </div>
            </div><!-- End Left side columns -->
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once "components/footer.php";
?>