<?php
$page = "Dashboard";
$Role = "Admin";
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

          <!-- Sales Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card sales-card">

              <div class="card-body">
                <h5 class="card-title">To Approve</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-file-earmark-text"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?= $conn->query("SELECT * FROM payments WHERE Status='Pending'")->num_rows ?>
                    </h6>

                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Sales Card -->

          <!-- Revenue Card -->
          <div class="col-xxl-4 col-md-6">
            <div class="card info-card revenue-card">

              <div class="card-body">
                <h5 class="card-title">To Process</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-file-earmark-text"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?= $conn->query("SELECT * FROM payments WHERE Status='Processing'")->num_rows ?>
                    </h6>

                  </div>
                </div>
              </div>

            </div>
          </div><!-- End Revenue Card -->

          <!-- Customers Card -->
          <div class="col-xxl-4 col-xl-12">

            <div class="card info-card customers-card">

              <div class="card-body">
                <h5 class="card-title">To Claim</span></h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-file-earmark-text"></i>
                  </div>
                  <div class="ps-3">
                    <h6>
                      <?= $conn->query("SELECT * FROM payments WHERE Status='Completed'")->num_rows ?>
                    </h6>

                  </div>
                </div>

              </div>
            </div>

          </div><!-- End Customers Card -->

          <!-- Recent Sales -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="card-body">
                <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                <table class="table table-borderless datatable">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">ID No.</th>
                      <th scope="col">Name</th>
                      <th scope="col">Payment No.</th>
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
                        <td scope="row">
                          <?= $row->id ?>
                        </td>
                        <td>
                          <?= $row->IDNo ?>
                        </td>
                        <td>
                        <a href="?UserID=<?= $row->UserID ?>"><?= $row->LastName ?>, <?= $row->FirstName ?></a>
                        </td>
                        <td><a href="?PaymentNo=<?= $row->PaymentNo ?>">
                            <?= $row->PaymentNo ?>
                          </a></td>
                        <td class="text-end">
                          <?= $row->Total ?>
                        </td>
                        <td>
                        <?= $row->Status=="Pending"?'<span class="badge rounded-pill bg-warning">For Approval</span>':($row->Status=="Processing"?'<span class="badge rounded-pill bg-primary">Processing</span>':($row->Status=="Completed"?'<span class="badge rounded-pill bg-success">To Claim</span>':($row->Status=="Claimed"?'<span class="badge rounded-pill bg-info">Claimed</span>':'<span class="badge rounded-pill bg-danger">Failed</span>'))) ?>
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