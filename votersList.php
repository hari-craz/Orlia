<?php
include 'includes/auth.php';
// Special access check for Photography Voters List
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
$eventKey = $_SESSION['userid'];

// STRICT ACCESS CHECK: Only Super Admin (2) OR 'photography' user (Role 1)
if ($role == '2') {
    // Super Admin Allowed
} elseif ($role == '1' && $eventKey === 'Photography') {
    // Photography Admin Allowed
} else {
    // Unauthorized - Redirect to their standard dashboard
    if ($role == '2')
        header("Location: superAdmin.php");
    elseif ($role == '1')
        header("Location: eventAdmin.php");
    else
        header("Location: adminDashboard.php");
    exit();
}

// Fetch Logos for PDF
function getImgBase64($path)
{
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return null;
}
$krLogo = getImgBase64('assets/images/kr.jpg');
$mkceLogo = getImgBase64('assets/images/mkce.png');
$watermarkPath = 'assets/images/agastya.jpg';
$agastyaWatermark = getImgBase64($watermarkPath);
$watermarkW = 300;
$watermarkH = 300;
if (file_exists($watermarkPath)) {
    list($watermarkW, $watermarkH) = getimagesize($watermarkPath);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voters List - Orlia'26</title>
    <link rel="icon" href="assets/images/agastya.png" type="image/png">
    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="stylesheet" href="assets/styles/admin.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        /* Center Align Table Data */
        table.dataTable tbody td,
        table.dataTable thead th {
            text-align: center;
            vertical-align: middle;
        }

        .voted-badge {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
    </style>
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
        $page = 'voters_list';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Photography Event</span>
                        <h1 class="admin-title">Voters List</h1>
                    </div>
                </div>

                <div class="header-right">
                    <?php if ($role == '2'): ?>
                        <button id="btnBulkDelete"
                            style="display:none; margin-right: 15px; padding: 10px 20px; background-color: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; align-items: center; gap: 8px;">
                            <i class="ri-delete-bin-line"></i> Delete Selected
                        </button>
                    <?php endif; ?>
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="ri-user-3-line"></i>
                        </div>
                        <div class="user-dropdown">
                            <div class="dropdown-header">
                                <h4>Event Admin</h4>
                                <p>event@orlia.com</p>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="ri-user-settings-line"></i> Profile</a></li>
                                <li><a href="#"><i class="ri-settings-4-line"></i> Settings</a></li>
                                <li class="divider"></li>
                                <li><a href="index.php" class="text-danger"><i class="ri-logout-box-line"></i>
                                        Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <?php
            include 'db.php';
            // Fetch all voters
            $sql = "SELECT * FROM photography ORDER BY vote ASC";
            $result = mysqli_query($conn, $sql);

            $rows = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($r = mysqli_fetch_assoc($result)) {
                    $rows[] = $r;
                }
            }
            ?>

            <!-- Voters Table -->
            <div class="table-container" style="margin-top: 20px;">
                <table id="votersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <?php if ($role == '2'): ?>
                                <th><input type="checkbox" id="selectAll"></th>
                            <?php endif; ?>
                            <th>S.No</th>
                            <th>Reg No</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Voted For (Photo ID)</th>
                            <?php if ($role == '2'): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($rows)) {
                            $i = 1;
                            foreach ($rows as $row) {
                                ?>
                                <tr>
                                    <?php if ($role == '2'): ?>
                                        <td><input type="checkbox" class="vote-checkbox" value="<?= $row['id'] ?>"></td>
                                    <?php endif; ?>
                                    <td>
                                        <?= $i++ ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['regno']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['name']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['dept']) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($row['year']) ?>
                                    </td>
                                    <td>
                                        <span class="voted-badge">
                                            #
                                            <?= htmlspecialchars($row['vote']) ?>
                                        </span>
                                    </td>
                                    <?php if ($role == '2'): ?>
                                        <td>
                                            <button class="btn-delete-vote" data-id="<?= $row['id'] ?>"
                                                style="background:none; border:none; color: #ef4444; cursor: pointer; font-size: 1.2rem;">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/script/script.js"></script>
    <script>
        $(document).ready(function () {
            const pdfLogos = {
                kr: "<?= $krLogo ?>",
                mkce: "<?= $mkceLogo ?>",
                watermark: "<?= $agastyaWatermark ?>",
                w: <?= $watermarkW ?>,
                h: <?= $watermarkH ?>
            };

            const pdfConfig = {
                extend: 'pdfHtml5',
                orientation: 'portrait', // or 'landscape' if wide
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    // doc.styles.tableHeader.fillColor = '#ffffff';
                    doc.styles.tableHeader.color = '#000000';
                    // doc.styles.tableBodyEven.fillColor = '#ffffff';
                    // doc.styles.tableBodyOdd.fillColor = '#ffffff';

                    var objLayout = {};
                    objLayout['hLineWidth'] = function (i) { return 0.5; };
                    objLayout['vLineWidth'] = function (i) { return 0.5; };
                    objLayout['hLineColor'] = function (i) { return '#aaa'; };
                    objLayout['vLineColor'] = function (i) { return '#aaa'; };
                    objLayout['paddingLeft'] = function (i) { return 4; };
                    objLayout['paddingRight'] = function (i) { return 4; };
                    doc.content[1].layout = objLayout;

                    doc.styles.tableHeader.alignment = 'center';
                    doc.defaultStyle.alignment = 'center';

                    // Smart Widths
                    var header = doc.content[1].table.body[0];
                    var widths = [];
                    if (header.length > 6) {
                        // Super Admin (8 cols)
                        widths = ['5%', '5%', '15%', '20%', '15%', '10%', '15%', '15%'];
                    } else {
                        // Normal (6 cols)
                        widths = ['7%', '20%', '25%', '15%', '13%', '20%'];
                    }
                    doc.content[1].table.widths = widths;

                    var body = doc.content[1].table.body;
                    for (var i = 1; i < body.length; i++) {
                        body[i][0].margin = [0, 10, 0, 10];
                    }

                    doc.header = function (currentPage, pageCount, pageSize) {
                        var headerContent = [];

                        // Header Logos (MKCE Left, KR Right)
                        if (pdfLogos.kr && pdfLogos.mkce) {
                            headerContent.push({
                                margin: [40, 10, 40, 0],
                                columns: [
                                    { image: pdfLogos.mkce, width: 130 },
                                    { text: '', width: '*' },
                                    { image: pdfLogos.kr, width: 90, alignment: 'right' }
                                ]
                            });
                        }
                        return headerContent;
                    };

                    doc.background = function (currentPage, pageSize) {
                        if (pdfLogos.watermark) {
                            var targetW = 300;
                            var targetH = pdfLogos.h * (targetW / pdfLogos.w);
                            return {
                                image: pdfLogos.watermark,
                                width: targetW,
                                absolutePosition: {
                                    x: (pageSize.width - targetW) / 2,
                                    y: (pageSize.height - targetH) / 2
                                },
                                opacity: 0.2
                            };
                        }
                        return null;
                    };
                    doc.pageMargins = [40, 120, 40, 60];
                }
            };

            $('#votersTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="ri-layout-column-line"></i> Columns',
                        className: 'btn-colvis'
                    },
                    'copy',
                    {
                        extend: 'csv',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'excel',
                        exportOptions: { columns: ':visible' }
                    },
                    pdfConfig,
                    'print'
                ],
                order: [[0, 'asc']]
            });
        });

        // Select All Checkbox
        $('#selectAll').change(function () {
            $('.vote-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkDeleteBtn();
        });

        // Individual Checkbox
        $(document).on('change', '.vote-checkbox', function () {
            if (!$(this).prop('checked')) {
                $('#selectAll').prop('checked', false);
            }
            toggleBulkDeleteBtn();
        });

        function toggleBulkDeleteBtn() {
            if ($('.vote-checkbox:checked').length > 0) {
                $('#btnBulkDelete').fadeIn().css('display', 'inline-flex');
            } else {
                $('#btnBulkDelete').fadeOut();
            }
        }

        // Delete Single Vote
        $(document).on('click', '.btn-delete-vote', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: 'Delete this vote?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'backend.php',
                        type: 'POST',
                        data: { delete_vote: true, id: id },
                        dataType: 'json',
                        xhrFields: { withCredentials: true },
                        success: function (res) {
                            if (res.status == 200) {
                                Swal.fire('Deleted!', res.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Request failed: ' + error, 'error');
                        }
                    });
                }
            });
        });

        // Bulk Delete
        $('#btnBulkDelete').click(function (e) {
            e.preventDefault();
            const selected = [];
            $('.vote-checkbox:checked').each(function () {
                selected.push($(this).val());
            });

            if (selected.length === 0) return;

            Swal.fire({
                title: `Delete ${selected.length} votes?`,
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'backend.php',
                        type: 'POST',
                        data: { bulk_delete_votes: true, ids: selected },
                        dataType: 'json',
                        xhrFields: { withCredentials: true },
                        success: function (res) {
                            if (res.status == 200) {
                                Swal.fire('Deleted!', res.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'Request failed: ' + error, 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>