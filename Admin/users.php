<?php
$page = "User Management";
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
                        <h5 class="card-title">User List</h5>
                        <p class="text-end">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseAddUser" aria-expanded="false" aria-controls="collapseAddUser">
                                Add User
                            </button>
                        </p>
                        <div class="collapse mb-3" id="collapseAddUser">
                            <div class="card card-body">
                                <h5 class="card-title"></h5>
                                <form class="user" action="../includes/process.php" method="POST">
                                    <div class="mb-3 row">
                                        <div class="col-lg-4 col-md-4">
                                            <label for="FirstName" class="form-label">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="FirstName" name="FirstName"
                                                value="" required>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <label for="MiddleName" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="MiddleName" name="MiddleName"
                                                value="">
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <label for="LastName" class="form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="LastName" name="LastName"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Username" class="form-label">Username <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="Username" name="Username"
                                                value="" required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Email" class="form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="Email" name="Email" value=""
                                                required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Phone" class="form-label">Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="Phone" name="Phone" value=""
                                                required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Role" class="form-label">Role <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" id="Role" name="Role" required>
                                                <option value="Student">Student</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-lg-12 col-md-12">
                                            <label for="Address" class="form-label">Address <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="Address" name="Address" value=""
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <input type="hidden" name="AddUser" />
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="collapse"
                                            data-bs-target="#collapseAddUser">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php
                        if (isset($_GET["UpdateUser"])) {
                            $query = "SELECT * FROM users WHERE id=" . $_GET["UpdateUser"];
                            $result = $conn->query($query);
                            $user = $result->fetch_object();
                            ?>
                            <div class="collapse mb-3 show" id="collapseEditUser">
                                <div class="card card-body">
                                    <h5 class="card-title"></h5>
                                    <form class="user" action="../includes/process.php" method="POST">
                                        <div class="mb-3 row">
                                            <div class="col-lg-4 col-md-4">
                                                <label for="FirstName" class="form-label">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="FirstName" name="FirstName"
                                                    value="<?= $user->FirstName ?>" required>
                                            </div>
                                            <div class="col-lg-4 col-md-4">
                                                <label for="MiddleName" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control" id="MiddleName" name="MiddleName"
                                                    value="<?= $user->MiddleName ?>">
                                            </div>
                                            <div class="col-lg-4 col-md-4">
                                                <label for="LastName" class="form-label">Last Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="LastName" name="LastName"
                                                    value="<?= $user->LastName ?>" required>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-6 col-md-6">
                                                <label for="Username" class="form-label">Username <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="Username" name="Username"
                                                    value="<?= $user->Username ?>" required>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <label for="Email" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="Email" name="Email" value="<?= $user->Email ?>"
                                                    required>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <label for="Phone" class="form-label">Phone <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="Phone" name="Phone" value="<?= $user->Phone ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-6 col-md-6">
                                                <label for="Role" class="form-label">Role <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" id="Role" name="Role" required>
                                                    <option value="" selected disabled>--</option>
                                                    <option value="Staff" <?= $user->Role=="Staff"?'selected':'' ?>>Staff</option>
                                                    <option value="Student" <?= $user->Role=="Student"?'selected':'' ?>>Student</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <label for="Address" class="form-label">Address <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="Address" name="Address" value="<?= $user->Address ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <input type="hidden" name="id" value="<?= $user->id ?>" />
                                            <input type="hidden" name="UpdateUser" />
                                            <button type="button" class="btn btn-secondary" data-bs-toggle="collapse"
                                                data-bs-target="#collapseEditUser">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM users WHERE id != ? AND Role != ?";
                                $result = $conn->execute_query($query, [$acc->id, 'Admin']);
                                while ($row = $result->fetch_object()) {
                                    ?>
                                    <tr>
                                        <td class="text-center text-nowrap">
                                            <?= $row->Username ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <?= $row->Email ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <?= $row->FirstName ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <?= $row->MiddleName ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <?= $row->LastName ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?= $row->Role ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a class='upd-btn btn btn-success btn-sm rounded-0 mx-1'
                                                href="?UpdateUser=<?= $row->id ?>"><i class="bi bi-pencil"></i></a>

                                            <a class='del-btn btn btn-warning btn-sm rounded-0 mx-1'
                                                href="../includes/process.php?ResetPassword=<?= $row->id ?>"><i
                                                    class="bi bi-key"></i></a>

                                            <a class='del-btn btn btn-danger btn-sm rounded-0 mx-1'
                                                href="../includes/process.php?DeleteUser=<?= $row->id ?>"><i
                                                    class="bi bi-trash"></i></a>

                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
    </section>

</main><!-- End #main -->

<?php
require_once "components/footer.php";
?>