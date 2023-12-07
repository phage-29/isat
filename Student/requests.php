<?php
$page = "Requests";
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

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent requests</h5>
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Payment No.</th>
                            <th scope="col">Total</th>
                            <th scope="col">Reference No.</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = $conn->query("SELECT * FROM payments where UserID=$acc->id ORDER BY id DESC LIMIT 0,5");
                        while ($row = $query->fetch_object()) {
                            ?>
                            <tr>
                                <td class="text-center" scope="row">
                                    <?= date_format(date_create($row->CreatedAt), 'd/m/Y') ?>
                                </td>
                                <td class="">
                                    <?= $row->PaymentNo ?>
                                </td>
                                <td class="text-end">
                                    <?= $row->Total ?>
                                </td>
                                <td class="text-center">
                                    <?= $row->ReferenceNo ?>
                                </td>
                                <td class="text-center">
                                    <?= $row->Status == "Pending" ? '<span class="badge rounded-pill bg-warning">For Approval</span>' : ($row->Status == "Processing" ? '<span class="badge rounded-pill bg-primary">Processing</span>' : ($row->Status == "Completed" ? '<span class="badge rounded-pill bg-success">To Claim</span>' : ($row->Status == "Claimed" ? '<span class="badge rounded-pill bg-info">Claimed</span>' : '<span class="badge rounded-pill bg-danger">Failed</span>'))) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-center">OFFICE OF THE UNIVERSITY REGISTRAR AND ADMISSION</h5>
                <div class="row">
                    <div class=" col-lg-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">ID No.</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" value="<?= $stud->IDNo ?>" disabled>
                        </div>
                    </div>
                    <div class=" col-lg-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Date</span>
                            <input type="date" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" value="<?= Date('Y-m-d') ?>" disabled>
                        </div>
                    </div>
                    <div class=" col-lg-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Name</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default"
                                value="<?= $acc->LastName ?>, <?= $acc->FirstName ?>" disabled>
                        </div>
                    </div>
                    <div class=" col-lg-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Course Yr & Sec</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default"
                                value="<?= $stud->Course ?> - <?= $stud->Year ?> - <?= $stud->Section ?>" disabled>
                        </div>
                    </div>
                    <div class=" col-lg-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Academic Status</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" value="<?= $stud->AcademicStatus ?>"
                                disabled>
                        </div>
                    </div>
                </div>
                <hr>
                <form action="../includes/process.php" method="POST">

                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="card-title">Requests (Please Check the Following)</h5>
                            <?php
                            $query = "SELECT * FROM documents";
                            $result = $conn->execute_query($query);
                            while ($row = $result->fetch_object()) {
                                ?>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input document-checkbox" type="checkbox"
                                            value="<?= $row->id ?>" id="document_<?= $row->id ?>"
                                            name="document_<?= $row->id ?>" data-document-name="<?= $row->Document ?>"
                                            data-document-price="<?= $row->Price ?>">
                                        <label class="form-check-label" for="document_<?= $row->id ?>">
                                            <?= $row->Document ?> (
                                            <?= number_format($row->Price, 2) ?> php)
                                        </label>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="col-lg-6">
                            <h5 class="card-title">Purpose:</h5>
                            <div class="row">
                                <?php
                                $query = "SELECT * FROM purposes";
                                $result = $conn->execute_query($query);
                                while ($row = $result->fetch_object()) {
                                    ?>
                                    <div class="col-lg-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="<?= $row->id ?>"
                                                id="purpose_<?= $row->id ?>" name="purpose_<?= $row->id ?>">
                                            <label class="form-check-label" for="purpose_<?= $row->id ?>">
                                                <?= $row->Purpose ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="card-title">Payment:</h5>
                            <table id="payment-table" class="w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center">Document</th>
                                        <th class="text-center">Amount</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th class="border-top">Total</th>
                                        <th class="border-top text-end" id="total-amount">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <h5 class="card-title"><br></h5>
                            <div class=" col-lg-6 w-100 text-center">
                                <img src="assets/img/gcash-qr.jpg" class="w-75 mb-3 border border-secondary border-5" />
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Payment No.</span>
                                    <input type="text" class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default"
                                        value="<?= generatePaymentNumber() ?>" name="PaymentNo" readonly>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Total Amount</span>
                                    <input type="number" class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default" id="input-total-amount"
                                        value="0.00" name="Total" readonly>
                                </div>
                                <p class="text-start text-muted"><em>Enter the Reference Number to confirm your
                                        request.</em></p>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Reference No.</span>
                                    <input type="text" class="form-control" aria-label="Sizing example input"
                                        aria-describedby="inputGroup-sizing-default" name="ReferenceNo"
                                        placeholder="Enter Reference Number" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var documentCheckboxes = document.querySelectorAll('.document-checkbox');
                            var paymentTableBody = document.querySelector('#payment-table tbody');
                            var totalAmountCell = document.getElementById('total-amount');
                            var inputTotalAmount = document.getElementById('input-total-amount');
                            var displayTotalAmount = document.getElementById('display-total-amount');

                            documentCheckboxes.forEach(function (checkbox) {
                                checkbox.addEventListener('change', updatePaymentTable);
                            });

                            function updatePaymentTable() {
                                var totalAmount = 0;

                                paymentTableBody.innerHTML = '';

                                documentCheckboxes.forEach(function (checkbox) {
                                    if (checkbox.checked) {
                                        var documentName = checkbox.getAttribute('data-document-name');
                                        var documentPrice = parseFloat(checkbox.getAttribute('data-document-price'));

                                        var newRow = paymentTableBody.insertRow();
                                        var cell1 = newRow.insertCell(0);
                                        var cell2 = newRow.insertCell(1);

                                        cell1.textContent = documentName;
                                        cell2.textContent = number_format(documentPrice, 2);

                                        // Add "text-end" class to cell2
                                        cell2.classList.add('text-end');

                                        totalAmount += documentPrice;
                                    }
                                });

                                totalAmountCell.textContent = number_format(totalAmount, 2);
                                inputTotalAmount.value = number_format(totalAmount, 2);
                                displayTotalAmount.textContent = number_format(totalAmount, 2);
                            }

                            function number_format(number, decimals, decPoint, thousandsSep) {
                                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                                var n = !isFinite(+number) ? 0 : +number,
                                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                                    sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep,
                                    dec = (typeof decPoint === 'undefined') ? '.' : decPoint,
                                    s = '',
                                    toFixedFix = function (n, prec) {
                                        var k = Math.pow(10, prec);
                                        return '' + Math.round(n * k) / k;
                                    };

                                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                                if (s[0].length > 3) {
                                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                                }
                                if ((s[1] || '').length < prec) {
                                    s[1] = s[1] || '';
                                    s[1] += new Array(prec - s[1].length + 1).join('0');
                                }

                                return s.join(dec);
                            }
                        });
                    </script>
                    <div class="mb-3 text-end">
                        <input type="hidden" name="Request" value="<?= $acc->id ?>" />
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
require_once "components/footer.php";
?>