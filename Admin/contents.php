<?php
$page = "Content Management";
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
                <li class="breadcrumb-item">Pages</li>
                <li class="breadcrumb-item active">
                    <?= $page ?>
                </li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Announcements</h5>
                        <p class="text-end">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAddAnnouncement" aria-expanded="false"
                                aria-controls="collapseAddAnnouncement">
                                Add Announcement
                            </button>
                        </p>
                        <div class="collapse mb-3" id="collapseAddAnnouncement">
                            <div class="card card-body">
                                <h5 class="card-title"></h5>
                                <form class="user" action="../includes/process.php" method="POST">
                                    <div class="mb-3 row">
                                        <div class="col-lg-12 mb-3">
                                            <label for="Title" class="form-label">Title <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="Title" name="Title" value=""
                                                required>
                                        </div>
                                        <div class="col-lg-12 mb-5">
                                            <label for="Description" class="form-label">Description <span
                                                    class="text-danger">*</span></label>
                                            <div class="quill-editor-full" id="quill-editor"></div>
                                            <input type="hidden" id="quill-content" name="Description" />
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    var quill = new Quill('#quill-editor', {
                                                        theme: 'snow', // or use another theme
                                                    });

                                                    var form = document.querySelector('.user');

                                                    form.addEventListener('submit', function () {
                                                        var quillContent = quill.root.innerHTML;
                                                        document.getElementById('quill-content').value = quillContent;
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <div class="text-end mt-5 pt-5">
                                        <input type="hidden" name="AddAnnouncement" />
                                        <input type="hidden" name="UpdateAnnouncement" disabled />
                                        <input type="hidden" name="Author"
                                            value="<?= $acc->FirstName ?> <?= $acc->LastName ?>" />
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse"
                                            data-bs-target="#collapseAddAnnouncement">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="recent-sales overflow-auto">

                                <h5 class="card-title">Notifications</h5>
                                <?php
                                $query = $conn->query("SELECT * FROM announcements ORDER BY UpdatedAt DESC");
                                while ($row = $query->fetch_object()) {
                                    ?>
                                    <hr>
                                    <h2>
                                        <?= $row->Title ?>
                                        <div class="float-end">
                                            <button class="btn text-primary" data-bs-toggle="collapse"
                                                data-bs-target="#collapseAddAnnouncement"
                                                onclick="updAnnouncement('<?= $row->Title ?>','<?= $row->Description ?>')"><i
                                                    class="bi bi-pencil-square"></i></button>
                                            <button class="btn text-danger"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </h2>
                                    <span class="text-muted">Author:
                                        <?= $row->Author ?>
                                    </span>
                                    <?= $row->Description ?>
                                    <p class="text-end">
                                        <?= $row->UpdatedAt ?>
                                    </p>
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
                        </div><!-- End Recent Sales -->
                        <script>
                            function updAnnouncement(title, description) {
                                document.getElementById('Title').value = title;

                                // Set the content of the Quill editor
                                quill.root.innerHTML = description;
                            }

                        </script>
                    </div>
                </div>
            </div>
    </section>

</main><!-- End #main -->

<?php
require_once "components/footer.php";
?>