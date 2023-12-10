<?php
$page = "Notifications";
$Role = "Admin";
require_once "../includes/session.php";
require_once "components/header.php";
require_once "components/topbar.php";
require_once "components/sidebar.php";
?>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: '<?= isset($_GET['GoTo']) ? $_GET['GoTo'] : date('Y-m-d') ?>',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            eventClick: function (arg) {
                window.location = '?PaymentNo=' + arg.event.title + '<?= isset($_GET['GoTo']) ? '&GoTo=' . $_GET['GoTo'] : '' ?>';
            },
            dayMaxEvents: 1,
            height: 'auto',
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            nowIndicator: true,
            events: [
                <?php
                $result = $conn->query("SELECT * FROM paymentshistory");
                while ($row = $result->fetch_object()) {
                    ?>
                            {
                        title: '<?= $row->PaymentNo ?>',
                        start: '<?= date_format(date_create($row->CreatedAt), 'Y-m-d') ?>',
                    },
                    <?php
                }
                ?>
            ]
        });

        calendar.render();

        var searchInput = document.getElementById('eventSearch');
        searchInput.addEventListener('input', function () {
            var searchString = searchInput.value.toLowerCase();
            calendar.getEvents().forEach(function (event) {
                var eventTitle = event.title.toLowerCase();
                if (eventTitle.includes(searchString)) {
                    event.show();
                } else {
                    event.hide();
                }
            });
        });
    });

</script>
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
                                <div class="w-25 mb-3">
                                    <form id="GoTo">
                                        <label for="eventSearch">Transaction Date:</label>
                                        <input type="date" id="eventSearch" class="form-control"
                                            value="<?= isset($_GET['GoTo']) ? $_GET['GoTo'] : '' ?>" name="GoTo"
                                            onchange="document.getElementById('GoTo').submit()"
                                            placeholder="Search events">
                                    </form>
                                </div>
                                <div id='calendar'></div>
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