<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link collapsed" href="dashboard.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-heading">Pages</li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="users.php">
                <i class="bi bi-people"></i>
                <span>User Management</span>
            </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="requests.php">
                <i class="bi bi-view-list"></i>
                <span>Request Management</span>
            </a>
        </li><!-- End Profile Page Nav -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="analytics.php">
                <i class="bi bi-bar-chart"></i>
                <span>Analytics Reports</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="contents.php">
                <i class="bi bi-body-text"></i>
                <span>Content Management</span>
            </a>
        </li><!-- End Profile Page Nav -->

    </ul>

</aside><!-- End Sidebar-->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the current URL
        var currentURL = window.location.href.split('/');
        currentURL = currentURL[currentURL.length - 1].split('?');
        currentURL = currentURL[0];

        // Get all anchor elements in the navigation
        var navLinks = document.querySelectorAll('.nav-link');

        // Loop through each anchor element
        navLinks.forEach(function (link) {
            // Check if the href attribute matches the current URL
            if (link.getAttribute('href') === currentURL) {
                // Remove "collapsed" class and add "active" class
                link.classList.remove('collapsed');
                link.classList.add('active');
            }

        });
    });

</script>