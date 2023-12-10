<?php
$page = "Notifications";
$Role = "Student";
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
                                <h5 class="card-title">Notifications</h5>
                                <?php
                                $query = $conn->query("SELECT * FROM announcements ORDER BY UpdatedAt DESC");
                                while ($row = $query->fetch_object()) {
                                    ?>
                                    <hr>
                                    <h2><?= $row->Title ?></h2>
                                    <span class="text-muted">Author: <?= $conn->query("SELECT concat(FirstName,' ',LastName) as FullName FROM users where id = $row->Author")->fetch_object()->FullName 
?></span>
                                    <?= $row->Description ?>
                                    <p class="text-end"><?= $row->UpdatedAt ?></p>
                                    <hr>
                                    <?php
                                }
                                if ($query->num_rows == 0) {
                                    ?>
                                    <hr>
                                    <h2 class="text-center">No Announcement...</h2>
                                    <hr>
                                    <?php
                                }
                                ?>
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