<?php
include 'includes/auth.php';
checkUserAccess();

// Helper to get base64 for PDF
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
    <title>Overall Participants - Orlia'26</title>
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

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        /* Base Button Style */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s ease;
            background: transparent;
        }

        /* Edit Button - Blue/Indigo Theme */
        .btn-edit {
            color: #4f46e5;
            background: rgba(79, 70, 229, 0.1);
        }

        .btn-edit:hover {
            background: #4f46e5;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        /* Delete Button - Red Theme */
        .btn-delete {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.2);
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
        $role = 'super';
        $page = 'participants';
        include 'includes/sidebar.php';
        ?>

        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Data Overview</span>
                        <h1 class="admin-title">Overall Participants</h1>
                    </div>
                </div>
                <div class="header-right">
                    <div style="display: flex; gap: 10px; margin-right: 20px;">
                        <button class="btn-reset-ids" data-type="solo" title="Reset Solo IDs"
                            style="padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: none;">
                            <i class="ri-user-3-line"></i>
                        </button>
                        <button class="btn-reset-ids" data-type="group" title="Reset Group IDs"
                            style="padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: none;">
                            <i class="ri-group-line"></i>
                        </button>
                    </div>
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

            <!-- Tabs -->
            <div class="admin-tabs">
                <button class="tab-btn active" onclick="openTab('solo')">Solo Events</button>
                <button class="tab-btn" onclick="openTab('group')">Group Events</button>
            </div>

            <!-- Solo Participants Section -->
            <div id="solo" class="tab-content active">
                <h2 class="mb-3"
                    style="font-family: 'Space Grotesk'; color: var(--text-main); margin-bottom: 20px !important;">Solo
                    Event Participants</h2>
                <div class="filters-bar" style="margin-bottom: 20px;">
                    <label for="soloEventFilter"
                        style="font-weight: 500; margin-right: 10px; color: var(--text-main);">Filter by Event:</label>
                    <select id="soloEventFilter"
                        style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-glass); background: var(--bg-surface); color: var(--text-main); font-family: 'Outfit', sans-serif;">
                        <option value="">All Events</option>
                    </select>
                </div>
                <div class="table-container">
                    <table id="soloTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Roll No</th>
                                <th>Dept</th>
                                <th>Year</th>
                                <th>Event</th>
                                <th>Phone</th>
                                <th>Actions</th>
                                <th>Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db.php';
                            $query = "SELECT * FROM soloevents ORDER BY id ASC";
                            $query_run = mysqli_query($conn, $query);
                            if (mysqli_num_rows($query_run) > 0) {
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['name'] ?></td>
                                        <td><?= $row['regno'] ?></td>
                                        <td><?= $row['dept'] ?></td>
                                        <td><?= $row['year'] ?></td>
                                        <td><?= $row['events'] ?></td>
                                        <td><?= $row['phoneno'] ?><br>
                                            <small><?= $row['mail'] ?></small>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-edit"
                                                    onclick="openEditModal(<?= $row['id'] ?>, 'solo')" title="Edit"><i
                                                        class="ri-edit-line"></i></button>
                                                <button class="btn-action btn-delete"
                                                    onclick="deleteParticipant(<?= $row['id'] ?>, 'solo')" title="Delete"><i
                                                        class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Group Participants Section -->
            <div id="group" class="tab-content">
                <h2 class="mb-3"
                    style="font-family: 'Space Grotesk'; color: var(--text-main); margin-bottom: 20px !important;">
                    Group Event Participants</h2>
                <div class="filters-bar" style="margin-bottom: 20px;">
                    <label for="groupEventFilter"
                        style="font-weight: 500; margin-right: 10px; color: var(--text-main);">Filter by Event:</label>
                    <select id="groupEventFilter"
                        style="padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-glass); background: var(--bg-surface); color: var(--text-main); font-family: 'Outfit', sans-serif;">
                        <option value="">All Events</option>
                    </select>
                </div>
                <div class="table-container">
                    <table id="groupTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Team Name</th>
                                <th>Leader Name</th>
                                <th>Leader Roll No</th>
                                <th>Members</th>
                                <th>Event</th>
                                <th>Leader Phone</th>
                                <th>Actions</th>
                                <th>Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM groupevents ORDER BY id ASC";
                            $query_run = mysqli_query($conn, $query);
                            if (mysqli_num_rows($query_run) > 0) {
                                $j = 1;
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    $members = json_decode($row['tmembername'], true);
                                    $memberDetails = '<div style="text-align: left;">';
                                    if (is_array($members) && count($members) > 0) {
                                        foreach ($members as $m) {
                                            $mName = isset($m['name']) ? htmlspecialchars($m['name']) : '';
                                            $mRoll = isset($m['roll']) ? htmlspecialchars($m['roll']) : '';
                                            $memberDetails .= '<div style="font-size: 0.9em; margin-bottom: 2px;">• ' . $mName . ' <span style="color: #64748b; font-size: 0.85em;">(' . $mRoll . ')</span></div>';
                                        }
                                    } else {
                                        $memberDetails = '<span style="color: #94a3b8;">-</span>';
                                    }
                                    $memberDetails .= '</div>';
                                    ?>
                                    <tr>
                                        <td><?= $j++ ?></td>
                                        <td><?= $row['teamname'] ?></td>
                                        <td><?= $row['teamleadname'] ?></td>
                                        <td><?= $row['tregno'] ?></td>
                                        <td><?= $memberDetails ?></td>
                                        <td><?= $row['events'] ?></td>
                                        <td><?= $row['phoneno'] ?><br>
                                            <small><?= $row['temail'] ?></small>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-edit"
                                                    onclick="openEditModal(<?= $row['id'] ?>, 'group')" title="Edit"><i
                                                        class="ri-edit-line"></i></button>
                                                <button class="btn-action btn-delete"
                                                    onclick="deleteParticipant(<?= $row['id'] ?>, 'group')" title="Delete"><i
                                                        class="ri-delete-bin-line"></i></button>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Modal Removed, using SweetAlert -->

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
    <script src="assets/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // ... existing PDF config ...

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

            const pdfLogos = {
                kr: "<?= $krLogo ?>",
                mkce: "<?= $mkceLogo ?>",
                watermark: "<?= $agastyaWatermark ?>",
                w: <?= $watermarkW ?>,
                h: <?= $watermarkH ?>
            };

            // Common PDF config
            const pdfConfig = {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    // Simple styling
                    // doc.styles.tableHeader.fillColor = '#ffffff';
                    doc.styles.tableHeader.color = '#000000';
                    // doc.styles.tableBodyEven.fillColor = '#ffffff';
                    // doc.styles.tableBodyOdd.fillColor = '#ffffff';

                    // Simple table layout (borders only)
                    var objLayout = {};
                    objLayout['hLineWidth'] = function (i) { return 0.5; };
                    objLayout['vLineWidth'] = function (i) { return 0.5; };
                    objLayout['hLineColor'] = function (i) { return '#aaa'; };
                    objLayout['vLineColor'] = function (i) { return '#aaa'; };
                    objLayout['paddingLeft'] = function (i) { return 4; };
                    objLayout['paddingRight'] = function (i) { return 4; };
                    doc.content[1].layout = objLayout;

                    // Header alignment
                    doc.styles.tableHeader.alignment = 'center';

                    // Body alignment
                    doc.defaultStyle.alignment = 'center';

                    // Smart Widths based on Header Content and Count
                    var header = doc.content[1].table.body[0];
                    var widths = [];
                    var totalCols = header.length;

                    if (totalCols <= 5) {
                        for (var i = 0; i < totalCols; i++) widths.push('*');
                    } else {
                        header.forEach(function (h) {
                            var text = h.text ? h.text.toString().toLowerCase() : '';
                            if (text.includes('id') || text === '#' || text.includes('no.')) {
                                widths.push('4%');
                            } else if (text.includes('signature')) {
                                widths.push('15%');
                            } else if (text.includes('name') || text.includes('event') || text.includes('team')) {
                                widths.push('*');
                            } else {
                                widths.push('auto');
                            }
                        });
                    }
                    doc.content[1].table.widths = widths;

                    // Increase Row Height (padding)
                    var body = doc.content[1].table.body;
                    for (var i = 1; i < body.length; i++) {
                        body[i][0].margin = [0, 10, 0, 10];
                    }

                    // Add Header to EVERY PAGE using doc.header
                    doc.header = function (currentPage, pageCount, pageSize) {
                        var headerContent = [];

                        // 1. Watermark removed from header

                        // 2. Logos (Standard Header Layout)
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

                    // Adjust page top margin to accommodate the header
                    doc.pageMargins = [40, 120, 40, 60];
                }
            };

            // Initialize DataTables with Buttons
            var soloTable = $('#soloTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                columnDefs: [
                    { visible: false, targets: 8 } // Hide Signature column (index 8) on screen
                ],
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
                ]
            });
            var groupTable = $('#groupTable').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                columnDefs: [
                    { visible: false, targets: 8 } // Hide Signature column (index 8) on screen
                ],
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
                ]
            });

            // --- Populate Event Filters ---
            // Solo Table Filter (Column index 5 is Event)
            var uniqueSoloEvents = soloTable.column(5).data().unique().sort();
            uniqueSoloEvents.each(function (val) {
                if (val) {
                    $('#soloEventFilter').append('<option value="' + val + '">' + val + '</option>');
                }
            });

            // Group Table Filter (Column index 5 is Event)
            var uniqueGroupEvents = groupTable.column(5).data().unique().sort();
            uniqueGroupEvents.each(function (val) {
                if (val) {
                    $('#groupEventFilter').append('<option value="' + val + '">' + val + '</option>');
                }
            });

            // Filter Change Listeners
            $('#soloEventFilter').on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                soloTable.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
            });

            $('#groupEventFilter').on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                groupTable.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
            });


            // Adjust columns on tab switch because hidden tables miscalculate width
            window.openTab = function (tabName) {
                // Hide all tab content
                $('.tab-content').removeClass('active');
                $('.tab-btn').removeClass('active');

                // Show current tab
                $('#' + tabName).addClass('active');

                // Set active button
                // Find button with onclick="openTab('tabName')" - simple approach
                $(`button[onclick="openTab('${tabName}')"]`).addClass('active');

                // Recalculate DataTable dimensions
                if (tabName === 'solo') {
                    soloTable.columns.adjust().responsive.recalc();
                } else {
                    groupTable.columns.adjust().responsive.recalc();
                }
            }


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

            // Delete Participant
            window.deleteParticipant = function (id, type) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
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
                            data: { delete_participant: true, id: id, type: type },
                            dataType: 'json',
                            xhrFields: { withCredentials: true },
                            success: function (res) {
                                if (res.status == 200) {
                                    Swal.fire('Deleted!', res.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error!', res.message, 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error!', 'Connection failed.', 'error');
                            }
                        });
                    }
                });
            };

            // Open Edit Modal with SweetAlert
            window.openEditModal = function (id, type) {
                // Fetch details
                $.ajax({
                    url: 'backend.php',
                    type: 'POST',
                    data: { get_participant_details: true, id: id, type: type },
                    dataType: 'json',
                    xhrFields: { withCredentials: true },
                    success: function (res) {
                        if (res.status == 200) {
                            const data = res.data;
                                let html = '<div style="display: flex; flex-direction: column; gap: 10px; text-align: left;">';

                                const inputStyle = 'margin: 0; height: 36px; font-size: 0.95rem; padding: 0 10px; width: 100%; box-sizing: border-box; border: 1px solid #d9d9d9; border-radius: 4px;';
                                const labelStyle = 'font-weight: 500; font-size: 0.9rem; color: #555; margin-bottom: 4px; display: block;';
                                const groupStyle = 'display: flex; flex-direction: column;';

                                if (type === 'solo') {
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Name</label><input id="swal-name" class="swal2-input-custom" style="${inputStyle}" value="${data.name}"></div>`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Roll Number</label><input id="swal-regno" class="swal2-input-custom" style="${inputStyle}" value="${data.regno}"></div>`;
                                    html += `<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Department</label><input id="swal-dept" class="swal2-input-custom" style="${inputStyle}" value="${data.dept}"></div>`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Year</label><input id="swal-year" class="swal2-input-custom" style="${inputStyle}" value="${data.year}"></div>`;
                                    html += `</div>`;
                                } else {
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Team Name</label><input id="swal-teamname" class="swal2-input-custom" style="${inputStyle}" value="${data.teamname}"></div>`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Leader Name</label><input id="swal-name" class="swal2-input-custom" style="${inputStyle}" value="${data.teamleadname}"></div>`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Leader Roll No</label><input id="swal-regno" class="swal2-input-custom" style="${inputStyle}" value="${data.tregno}"></div>`;
                                    html += `<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Department</label><input id="swal-dept" class="swal2-input-custom" style="${inputStyle}" value="${data.dept || ''}"></div>`;
                                    html += `<div style="${groupStyle}"><label style="${labelStyle}">Year</label><input id="swal-year" class="swal2-input-custom" style="${inputStyle}" value="${data.year || ''}"></div>`;
                                    html += `</div>`;
                                }
                                html += `<div style="${groupStyle}"><label style="${labelStyle}">Phone Number</label><input id="swal-phone" class="swal2-input-custom" style="${inputStyle}" value="${data.phoneno}"></div>`;
                                html += '</div>';

                                Swal.fire({
                                    title: 'Edit Participant',
                                    html: html,
                                    width: '400px',
                                    focusConfirm: false,
                                    showCancelButton: true,
                                    confirmButtonText: 'Save',
                                    confirmButtonColor: '#f59e0b',
                                    cancelButtonColor: '#64748b',
                                    customClass: {
                                        popup: 'swal-compact-popup'
                                    },
                                    preConfirm: () => {
                                        return {
                                            id: id,
                                            type: type,
                                            name: $('#swal-name').val(),
                                            teamname: $('#swal-teamname').val(),
                                            teamleadname: $('#swal-name').val(), // Reuse swal-name for leader name
                                            regno: $('#swal-regno').val(),
                                            tregno: $('#swal-regno').val(),
                                            dept: $('#swal-dept').val(),
                                            year: $('#swal-year').val(),
                                            phoneno: $('#swal-phone').val()
                                        }
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const formValues = result.value;
                                        $.ajax({
                                            url: 'backend.php',
                                            type: 'POST',
                                            data: {
                                                update_participant: true,
                                                ...formValues
                                            },
                                            dataType: 'json',
                                            xhrFields: { withCredentials: true },
                                            success: function (updateRes) {
                                                if (updateRes.status == 200) {
                                                    Swal.fire({
                                                        title: 'Updated!',
                                                        text: updateRes.message,
                                                        icon: 'success',
                                                        timer: 1500,
                                                        showConfirmButton: false
                                                    }).then(() => location.reload());
                                                } else {
                                                    Swal.fire('Error!', updateRes.message, 'error');
                                                }
                                            },
                                            error: function() {
                                                Swal.fire('Error', 'Connection failed during update', 'error');
                                            }
                                        });
                                    }
                                });

                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Connection failed', 'error');
                    }
                });
            };
        });
    </script>
</body>

</html>