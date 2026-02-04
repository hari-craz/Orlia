<?php
include 'includes/auth.php';
checkUserAccess();
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - Orlia'26</title>
    <link rel="icon" href="assets/images/agastya.png" type="image/png">
    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="stylesheet" href="assets/styles/admin.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
</head>

<body>
    <!-- Global Loader -->
    <div id="loader-wrapper">
        <div class="loader">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="admin-body">
        <!-- Sidebar -->
        <?php
        $role = 'super';
        $page = 'dashboard';
        include 'includes/sidebar.php';
        ?>

        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">System Overview</span>
                        <h1 class="admin-title">Super Dashboard</h1>
                    </div>
                </div>
                <div class="header-right">

                    <?php
                    // Get tables for export selector
                    $tablesList = [];
                    $tRes = mysqli_query($conn, "SHOW TABLES");
                    if ($tRes) {
                        while ($r = mysqli_fetch_array($tRes))
                            $tablesList[] = $r[0];
                    }
                    ?>
                    <script>
                        const dbTables = <?php echo json_encode($tablesList); ?>;
                    </script>

                    <button onclick="confirmDownloadUploads()" class="btn-download-uploads"
                        style="margin-right:15px; background-color: #3b82f6; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-weight: 500;">
                        <i class="ri-download-cloud-2-line"></i> Download Uploads
                    </button>

                    <button onclick="confirmExport()" class="btn-export-db" style="margin-right:15px;">
                        <i class="ri-database-2-line"></i> Export Database
                    </button>
                    <!-- <div class="theme-switch" id="theme-toggle">
                        <i class="ri-moon-line"></i>
                    </div> -->
                    <!-- User Profile -->
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="ri-user-3-line"></i>
                        </div>
                        <div class="user-dropdown">
                            <div class="dropdown-header">
                                <h4>Super Admin</h4>
                                <p>root@orlia.com</p>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="ri-user-settings-line"></i> Profile</a></li>
                                <li><a href="#"><i class="ri-settings-4-line"></i> Settings</a></li>
                                <li class="divider"></li>
                                <li><a href="logout.php" class="text-danger"><i class="ri-logout-box-line"></i>
                                        Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <?php
            // Calculate Stats
            $admin_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
            $solo_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM soloevents"));
            $group_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM groupevents"));
            $total_reg = $solo_count + $group_count;
            $active_events = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM events WHERE status=1"));
            ?>

            <div class="welcome-card glass"
                style="padding: 30px; border-radius: 24px; margin-bottom: 30px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between;">
                <div style="position: relative; z-index: 2;">
                    <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 5px; color: var(--text-main);">Welcome,
                        Super Admin</h2>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 0;">System Overview & Control
                    </p>
                </div>
                <!-- Optional Minimal Decoration -->
                <div style="font-size: 3rem; color: var(--google-blue); opacity: 0.8;">
                    <i class="ri-shield-star-line"></i>
                </div>
            </div>
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-admin-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $admin_count ?></h3>
                        <p>Total Admins</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-user-follow-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $total_reg ?></h3>
                        <p>Total Registrations</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-calendar-2-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $active_events ?></h3>
                        <p>Active Events</p>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 class="mb-4" style="margin-bottom: 0 !important;">Recent Registrations (Solo)</h2>
                    <button class="btn-reset-ids" data-type="solo" title="Reset Solo IDs"
                        style="padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: none;">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
                <table id="activityTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User/Team</th>
                            <th>Department</th>
                            <th>Event</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Showing recent 5 solo registrations as activity
                        $query = "SELECT * FROM soloevents ORDER BY id ASC LIMIT 5";
                        $result = mysqli_query($conn, $query);
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['dept'] ?></td>
                                <td><?= $row['events'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="assets/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('#activityTable').DataTable({
                responsive: true,
                searching: false,
                paging: false,
                info: false
            });

            // Reset IDs Button Click
            $('.btn-reset-ids').click(function () {
                const type = $(this).data('type'); // 'solo' or 'group'
                const typeName = type.charAt(0).toUpperCase() + type.slice(1);

                Swal.fire({
                    title: `Reset ${typeName} IDs?`,
                    text: "This will re-order all IDs sequentially starting from 1. This cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Reset IDs!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'backend.php',
                            data: { reset_ids: true, type: type },
                            dataType: 'json',
                            xhrFields: { withCredentials: true },
                            success: function (res) {
                                if (res.status == 200) {
                                    Swal.fire('Reset!', res.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', res.message, 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'Connection failed', 'error');
                            }
                        });
                    }
                });
            });
        });

        function confirmDownloadUploads() {
            Swal.fire({
                title: 'Download Uploads',
                text: "Select what you want to download:",
                icon: 'info',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="ri-folder-zip-line"></i> All Uploads',
                denyButtonText: 'Specific Folder',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3b82f6',
                denyButtonColor: '#8b5cf6'
            }).then((result) => {
                if (result.isConfirmed) {
                    initiateDownload('all');
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Select Folder',
                        input: 'select',
                        inputOptions: {
                            'photos': 'Photos (Images)',
                            'videos': 'Videos (Shortfilms)',
                            'songs': 'Songs (Music)'
                        },
                        inputPlaceholder: 'Select a folder',
                        showCancelButton: true,
                        confirmButtonText: 'Download',
                        confirmButtonColor: '#8b5cf6'
                    }).then((res) => {
                        if (res.isConfirmed && res.value) {
                            initiateDownload(res.value);
                        }
                    });
                }
            });
        }

        function initiateDownload(type) {
            const token = new Date().getTime();

            Swal.fire({
                title: 'Processing...',
                html: 'Compressing files for download.<br>This may take a while depending on file sizes.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    window.location.href = 'backend.php?download_type=' + type + '&token=' + token;
                }
            });

            const downloadTimer = setInterval(() => {
                const cookieName = 'download_started';
                const matches = document.cookie.match(new RegExp('(^| )' + cookieName + '=([^;]+)'));
                const cookieValue = matches ? matches[2] : null;

                if (cookieValue == token) {
                    clearInterval(downloadTimer);
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Download Started',
                        text: 'Your file is being downloaded.',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    // Clear cookie
                    document.cookie = 'download_started=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                }
            }, 1000);
        }

        function confirmExport() {
            Swal.fire({
                title: 'Export Database',
                text: "Choose export type:",
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: '<i class="ri-database-2-line"></i> Full Database',
                denyButtonText: '<i class="ri-table-line"></i> Specific Table',
                confirmButtonColor: '#10b981',
                denyButtonColor: '#3b82f6',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Full Export
                    submitExport('all');
                } else if (result.isDenied) {
                    // Specific Table
                    // Build options
                    let options = {};
                    dbTables.forEach(t => options[t] = t);

                    Swal.fire({
                        title: 'Select Table',
                        input: 'select',
                        inputOptions: options,
                        inputPlaceholder: 'Select a table',
                        showCancelButton: true,
                        confirmButtonText: 'Export',
                        confirmButtonColor: '#3b82f6'
                    }).then((res) => {
                        if (res.isConfirmed && res.value) {
                            submitExport(res.value);
                        }
                    });
                }
            });
        }

        function submitExport(table) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'backend.php';
            form.style.display = 'none';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'export_table'; // Changed name to match backend check
            input.value = table;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</body>

</html>