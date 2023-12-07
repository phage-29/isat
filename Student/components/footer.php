<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../includes/logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<?php
if (!is_null($acc->ChangePassword)) {
    ?>
    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="passwordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Change Password</h5>
                </div>
                <div class="modal-body">
                    <!-- Password change form -->
                    <form class="user" action="../includes/process.php" method="POST">
                        <div class="mb-3">
                            <label for="NewPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="NewPassword" name="NewPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="VerifyPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="VerifyPassword" name="VerifyPassword" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <input type="hidden" name="ChangePassword" />
                            <a href="../includes/logout.php" class="btn btn-secondary">Logout</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
            passwordModal.show();
        });
    </script>
    <?php
}
if (is_null($acc->ChangePassword)) {
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
}
?>
<?php
if (isset($_GET['PaymentNo'])) {
    $result = $conn->execute_query("SELECT * FROM payments WHERE PaymentNo=?", [$_GET['PaymentNo']]);
    $transaction = $result->fetch_object();
    ?>
    <!-- Change Password Modal -->
    <div class="modal fade" id="PaymentNoModal" tabindex="-1" aria-labelledby="PaymentNoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PaymentNoModalLabel">Transaction</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <table id="payment-table" class="w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">Document</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result2 = $conn->execute_query('SELECT r.*, d.Document, d.Price FROM requests r LEFT JOIN documents d ON r.DocumentID=d.id WHERE r.PaymentID=? AND r.DocumentID IS NOT NULL', [$transaction->id]);
                                    while ($row2 = $result2->fetch_object()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $row2->Document ?>
                                            </td>
                                            <td class="text-end">
                                                <?= $row2->Price ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="border-top">Total</th>
                                        <th class="border-top text-end" id="total-amount">
                                            <?= $transaction->Total ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <div class=" col-lg-6 w-100 text-center">
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Payment No.</span>
                                    <input type="text" class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default" value="<?= $transaction->PaymentNo ?>"
                                        name="PaymentNo" disabled>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Total Amount</span>
                                    <input type="number" class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default" id="input-total-amount"
                                        value="<?= $transaction->Total ?>" name="Total" disabled>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Reference No.</span>
                                    <input type="text" class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default" name="ReferenceNo"
                                        placeholder="Enter Reference Number" value="<?= $transaction->ReferenceNo ?>"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class=" col-lg-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Remarks</span>
                                <textarea type="text" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-default"
                                    disabled><?= $transaction->Remarks ?></textarea>
                            </div>
                        </div>
                        <div class=" col-lg-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Assisted By</span>
                                <select class="form-control" name="AssistedBy" disabled>
                                    <option value="0" selected>--</option>
                                    <?php
                                    $result = $conn->execute_query('SELECT * FROM users WHERE Role=?', ['Staff']);
                                    while ($row = $result->fetch_object()) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $transaction->AssistedBy == $row->id ? 'selected' : '' ?>>
                                            <?= $row->FirstName ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class=" col-lg-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Released By</span>
                                <select class="form-control" name="ReleasedBy" disabled>
                                    <option value="0" selected>--</option>
                                    <?php
                                    $result = $conn->execute_query('SELECT * FROM users WHERE Role=?', ['Staff']);
                                    while ($row = $result->fetch_object()) {
                                        ?>
                                        <option value="<?= $row->id ?>" <?= $transaction->AssistedBy == $row->id ? 'selected' : '' ?>>
                                            <?= $row->FirstName ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 text-end">
                        <input type="hidden" name="ChangePassword" />
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        
                        <?php
                        if ($transaction->Status == 'Completed') {
                            ?>
                            <button type="submit" class="btn btn-outline-success"
                                onclick="location='../includes/process.php?claimed=<?= $_GET['PaymentNo'] ?>'">Claimed</button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var PaymentNoModal = new bootstrap.Modal(document.getElementById('PaymentNoModal'));
            PaymentNoModal.show();
        });
    </script>
    <?php
}
?>

<!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>