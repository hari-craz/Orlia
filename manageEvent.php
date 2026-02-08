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
    <title>Manage Events - Orlia'26</title>
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

        /* DataTable Header Controls Alignment */
        .table-header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .left-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Override default DataTables float and margins */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dt-buttons {
            float: none !important;
            margin: 0 !important;
        }

        /* Ensure inputs and selects look good */
        .dataTables_length select {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px;
            margin: 0 5px;
        }

        .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 6px 12px;
            margin-left: 5px;
            outline: none;
        }

        .dataTables_filter input:focus {
            border-color: var(--primary-color);
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
        $page = 'events';
        include 'includes/sidebar.php';
        ?>

        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Control</span>
                        <h1 class="admin-title">Manage Events</h1>
                    </div>
                </div>
                <div class="header-right">
                    <button class="btn-add-event" title="Add New Event"
                        style="margin-right: 15px; padding: 10px 20px; font-size: 1rem; background-color: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <i class="ri-add-line"></i> Add Event
                    </button>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == '2'): ?>
                        <button class="btn-reset-ids" title="Reset Event IDs"
                            style="margin-right: 15px; padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; aspect-ratio: 1/1; box-shadow: none;">
                            <i class="ri-refresh-line"></i>
                        </button>
                    <?php endif; ?>
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
                                <h4>Admin User</h4>
                                <p>admin@orlia.com</p>
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
            include 'db.php';
            // Sort by event_name, day, event_time in ascending order (matches ID reset order)
            $query = "SELECT * FROM events ORDER BY event_name ASC, day ASC, COALESCE(STR_TO_DATE(REPLACE(event_time, '.', ':'), '%l:%i %p'), STR_TO_DATE(event_time, '%l %p'), event_time) ASC";
            $result = mysqli_query($conn, $query);
            $eventsData = [];
            $allActive = true;
            $soloActive = true;
            $groupActive = true;

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventsData[] = $row;
                    if ($row['status'] == 0) {
                        $allActive = false;
                        if ($row['event_type'] == 'Solo')
                            $soloActive = false;
                        if ($row['event_type'] == 'Group')
                            $groupActive = false;
                    }
                }
            }
            ?>

            <div class="table-actions"
                style="margin-bottom: 20px; display: flex; gap: 30px; flex-wrap: wrap; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div class="controls-row"
                    style="display: flex; gap: 20px; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <div style="display: flex; gap: 10px;">
                        <button id="bulkDeleteBtn" class="add-event-btn"
                            style="background-color: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 500; display: none; align-items: center; gap: 8px; cursor: pointer; transition: background-color 0.2s;">
                            <i class="ri-delete-bin-line"></i> Delete Selected
                        </button>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div class="bulk-switch-wrapper" style="display: flex; align-items: center; gap: 10px;">
                            <span class="bulk-label" style="font-weight: 500;">All Events</span>
                            <label class="switch">
                                <input type="checkbox" class="bulk-status-toggle" data-scope="all" <?= $allActive ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="bulk-switch-wrapper" style="display: flex; align-items: center; gap: 10px;">
                            <span class="bulk-label" style="font-weight: 500;">Solo Events</span>
                            <label class="switch">
                                <input type="checkbox" class="bulk-status-toggle" data-scope="solo" <?= $soloActive ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>

                        <div class="bulk-switch-wrapper" style="display: flex; align-items: center; gap: 10px;">
                            <span class="bulk-label" style="font-weight: 500;">Group Events</span>
                            <label class="switch">
                                <input type="checkbox" class="bulk-status-toggle" data-scope="group" <?= $groupActive ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-container">
                <table id="eventsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Type</th>
                            <th>Day</th>
                            <th>Venue</th> <!-- Added -->
                            <th>Time</th> <!-- Added -->
                            <th>Registration Status</th>
                            <th>Action</th> <!-- Added Action column -->
                            <th>Rules</th> <!-- Hidden, for PDF -->
                            <th>Topics</th> <!-- Hidden, for PDF -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($eventsData as $row) {
                            $checked = $row['status'] == 1 ? 'checked' : '';
                            // Fallback for missing keys if table not yet altered
                            $venue = isset($row['event_venue']) ? $row['event_venue'] : '';
                            $time = isset($row['event_time']) ? $row['event_time'] : '';
                            $rulesData = isset($row['event_rules']) ? $row['event_rules'] : ''; // Raw data for table cell
                            $rulesHtml = htmlspecialchars($rulesData); // Escaped for data attribute
                            $topicsData = isset($row['event_topics']) ? $row['event_topics'] : ''; // Raw data for table cell
                            $topicsHtml = htmlspecialchars($topicsData); // Escaped for data attribute
                            ?>
                            <tr data-rules="<?= $rulesHtml ?>" data-topics="<?= $topicsHtml ?>">
                                <td><input type="checkbox" class="select-row" value="<?= $row['id'] ?>"></td>
                                <td><?= $row['id'] ?></td>
                                <td data-key="<?= $row['event_key'] ?>"><?= $row['event_name'] ?></td>
                                <td><?= $row['event_type'] ?></td>
                                <td><?= ucfirst($row['day']) ?></td>
                                <td><?= $venue ?></td>
                                <td><?= $time ?></td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" class="event-status-toggle" data-id="<?= $row['id'] ?>"
                                            <?= $checked ?>>
                                        <span class="slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <button class="btn-edit"
                                            style="border:none; background:none; cursor:pointer; color: var(--primary-color);"
                                            title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="btn-delete" data-id="<?= $row['id'] ?>"
                                            style="border:none; background:none; cursor:pointer; color: #ef4444;"
                                            title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <?= $rulesData ?>
                                </td>
                                <td>
                                    <?= $topicsData ?>
                                </td>
                            </tr>
                        <?php } ?>
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
    <script src="assets/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global helpers for dynamic fields
        let rulesCount = 1;
        let topicsCount = 1;

        function addField(type, containerId) {
            const container = document.getElementById(containerId);
            const div = document.createElement('div');
            div.className = 'dynamic-field-row';
            div.style.display = 'flex';
            div.style.gap = '10px';
            div.style.marginBottom = '8px';

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'swal2-input ' + type + '-input';
            input.placeholder = type.charAt(0).toUpperCase() + type.slice(1);
            input.style.flex = '1';
            input.style.margin = '0';
            input.style.minWidth = '0'; // Prevent flex overflow

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.innerHTML = '<i class="ri-delete-bin-line"></i>';
            btn.style.background = '#fee2e2';
            btn.style.color = '#ef4444';
            btn.style.border = 'none';
            btn.style.borderRadius = '4px';
            btn.style.padding = '0 10px';
            btn.style.cursor = 'pointer';
            btn.onclick = function () { container.removeChild(div); };

            div.appendChild(input);
            div.appendChild(btn);
            container.appendChild(div);
        }

        function updateFileName(input) {
            const label = document.getElementById('file-label');
            if (input.files && input.files.length > 0) {
                label.innerText = input.files[0].name;
                label.style.color = '#10b981';
                label.style.fontWeight = '500';
            } else {
                label.innerText = 'Click to upload image';
                label.style.color = '#666';
            }
        }

        $(document).ready(function () {
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
                        // If few columns, just distribute equally to avoid huge spaces or small cramps
                        for (var i = 0; i < totalCols; i++) widths.push('*');
                    } else {
                        header.forEach(function (h) {
                            var text = h.text ? h.text.toString().toLowerCase() : '';

                            if (text.includes('id') || text === '#' || text.includes('no.')) {
                                widths.push('auto');
                            } else if (text.includes('description') || text.includes('rules') || text.includes('topics')) {
                                widths.push('*'); // Give significant fixed space or star
                            } else {
                                widths.push('*'); // Default to star for safety
                            }
                        });
                    }
                    doc.content[1].table.widths = widths;

                    // Smaller font for compact view
                    doc.defaultStyle.fontSize = 8;
                    doc.styles.tableHeader.fontSize = 9;

                    // Increase Row Height (padding)
                    var body = doc.content[1].table.body;
                    for (var i = 1; i < body.length; i++) {
                        // Apply margin to the first cell of each row to implicitly set height
                        if (body[i][0]) {
                            body[i][0].margin = [0, 5, 0, 5];
                        }
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

            $('#eventsTable').DataTable({
                responsive: true,
                dom: '<"table-header-controls"<"left-controls"lB>f>rtip',
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                columnDefs: [
                    { visible: false, targets: [9, 10] }, // Hide Rules and Topics on site
                    { orderable: false, targets: 0 } // Disable sorting on checkbox column
                ],
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="ri-layout-column-line"></i> Columns',
                        className: 'btn-colvis',
                        postfixButtons: ['colvisRestore']
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

            // Reset Event IDs Button Click
            $('.btn-reset-ids').click(function () {
                Swal.fire({
                    title: 'Reset Event IDs?',
                    text: "This will re-order all Event IDs sequentially starting from 1. This cannot be undone!",
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
                            data: { reset_ids: true, type: 'events' },
                            success: function (response) {
                                try {
                                    const res = JSON.parse(response);
                                    if (res.status == 200) {
                                        Swal.fire('Reset!', res.message, 'success').then(() => location.reload());
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid server response', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'Connection failed', 'error');
                            }
                        });
                    }
                });
            });

            // Make helper functions global for Swal interaction
            window.updateFileName = function (input) {
                var fileName = input.files[0].name;
                document.getElementById('file-label').innerText = fileName;
            }

            window.addField = function (type, containerId) {
                const container = document.getElementById(containerId);
                const count = container.children.length + 1;
                const div = document.createElement('div');
                div.className = 'dynamic-field-row';
                div.style = 'display:flex; margin-bottom:5px; gap: 5px;';
                div.innerHTML = `
                    <input type="text" class="swal2-input ${type}-input" placeholder="${type.charAt(0).toUpperCase() + type.slice(1)} ${count}" style="flex:1; margin:0; min-width:0;">
                    <button type="button" onclick="this.parentElement.remove()" style="background:#ef4444; border:none; color:white; border-radius:4px; width: 30px; cursor:pointer;"><i class="ri-delete-bin-line"></i></button>
                `;
                container.appendChild(div);
            }

            // Add Event Button Click
            $('.btn-add-event').click(function () {
                Swal.fire({
                    title: 'Add New Event',
                    width: '600px',
                    padding: '1.5em',
                    html: `
                        <style>
                            .swal2-input.form-control { margin: 0 !important; font-size: 0.9rem !important; width: 100%; box-sizing: border-box; }
                            .swal2-textarea { height: 80px !important; min-height: 80px !important; resize: vertical; margin: 0 !important; font-size: 0.9rem !important;}
                            .form-label { display: block; text-align: left; font-size: 0.85rem; color: #444; font-weight: 600; margin-bottom: 6px; margin-top: 10px; }
                            .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
                            .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
                            
                            /* Custom File Upload */
                            .file-upload-box {
                                position: relative;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                                padding: 20px;
                                border: 2px dashed #cbd5e1;
                                border-radius: 8px;
                                background: #f8fafc;
                                transition: all 0.3s;
                                cursor: pointer;
                            }
                            .file-upload-box:hover { border-color: #10b981; background: #f0fdf4; }
                            .file-upload-box i { font-size: 2rem; color: #94a3b8; margin-bottom: 5px; }
                            .file-upload-box span { font-size: 0.9rem; color: #64748b; }
                            .file-input-hidden { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

                            /* Dynamic Lists */
                            .dynamic-section { background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 5px; }
                            .add-btn-sm { background: #3b82f6; color: white; border: none; border-radius: 4px; padding: 4px 8px; font-size: 0.8rem; cursor: pointer; margin-top: 5px; display: inline-flex; align-items: center; gap: 5px; }
                        </style>
                        <div style="text-align: left;">
                            <div>
                                <label class="form-label" style="margin-top: 0;">Event Name</label>
                                <input id="swal-evname" class="swal2-input form-control" placeholder="Enter Event Name">
                            </div>

                             <div>
                                <label class="form-label">Event Image</label>
                                <div class="file-upload-box">
                                    <input id="swal-image" type="file" class="file-input-hidden" accept="image/*" onchange="updateFileName(this)">
                                    <i class="ri-upload-cloud-2-line"></i>
                                    <span id="file-label">Click to upload image</span>
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Type</label>
                                    <select id="swal-type" class="swal2-input form-control">
                                        <option value="Solo">Solo</option>
                                        <option value="Group">Group</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Day</label>
                                    <select id="swal-day" class="swal2-input form-control">
                                        <option value="day1">Day 1</option>
                                        <option value="day2">Day 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Venue</label>
                                    <input id="swal-venue" class="swal2-input form-control" placeholder="Venue">
                                </div>
                                <div></div>
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Start Time</label>
                                    <select id="swal-start-time" class="swal2-input form-control">
                                        <option value="">Select Start Time</option>
                                        <option value="8:00 AM">8:00 AM</option>
                                        <option value="8:30 AM">8:30 AM</option>
                                        <option value="9:00 AM">9:00 AM</option>
                                        <option value="9:30 AM">9:30 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="10:30 AM">10:30 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="11:30 AM">11:30 AM</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="12:30 PM">12:30 PM</option>
                                        <option value="1:00 PM">1:00 PM</option>
                                        <option value="1:30 PM">1:30 PM</option>
                                        <option value="2:00 PM">2:00 PM</option>
                                        <option value="2:30 PM">2:30 PM</option>
                                        <option value="3:00 PM">3:00 PM</option>
                                        <option value="3:30 PM">3:30 PM</option>
                                        <option value="4:00 PM">4:00 PM</option>
                                        <option value="4:30 PM">4:30 PM</option>
                                        <option value="5:00 PM">5:00 PM</option>
                                        <option value="5:30 PM">5:30 PM</option>
                                        <option value="6:00 PM">6:00 PM</option>
                                        <option value="6:30 PM">6:30 PM</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <select id="swal-end-time" class="swal2-input form-control">
                                        <option value="">Select End Time</option>
                                        <option value="8:00 AM">8:00 AM</option>
                                        <option value="8:30 AM">8:30 AM</option>
                                        <option value="9:00 AM">9:00 AM</option>
                                        <option value="9:30 AM">9:30 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="10:30 AM">10:30 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="11:30 AM">11:30 AM</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="12:30 PM">12:30 PM</option>
                                        <option value="1:00 PM">1:00 PM</option>
                                        <option value="1:30 PM">1:30 PM</option>
                                        <option value="2:00 PM">2:00 PM</option>
                                        <option value="2:30 PM">2:30 PM</option>
                                        <option value="3:00 PM">3:00 PM</option>
                                        <option value="3:30 PM">3:30 PM</option>
                                        <option value="4:00 PM">4:00 PM</option>
                                        <option value="4:30 PM">4:30 PM</option>
                                        <option value="5:00 PM">5:00 PM</option>
                                        <option value="5:30 PM">5:30 PM</option>
                                        <option value="6:00 PM">6:00 PM</option>
                                        <option value="6:30 PM">6:30 PM</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-grid-2" style="gap: 10px;">
                                <div style="margin-top: 10px;">
                                    <label class="form-label">Rules <button type="button" class="add-btn-sm" onclick="addField('rule', 'rules-container')"><i class="ri-add-line"></i> Add</button></label>
                                    <div id="rules-container" class="dynamic-section" style="padding: 6px;">
                                        <div class="dynamic-field-row" style="display:flex; margin-bottom:5px;">
                                            <input type="text" class="swal2-input rule-input" placeholder="Rule 1" style="flex:1; margin:0; min-width:0; padding: 0.3em 0.5em; font-size: 0.85rem;">
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top: 10px;">
                                    <label class="form-label">Topics <button type="button" class="add-btn-sm" onclick="addField('topic', 'topics-container')"><i class="ri-add-line"></i> Add</button></label>
                                    <div id="topics-container" class="dynamic-section" style="padding: 6px;">
                                        <div class="dynamic-field-row" style="display:flex; margin-bottom:5px;">
                                            <input type="text" class="swal2-input topic-input" placeholder="Topic 1" style="flex:1; margin:0; min-width:0; padding: 0.3em 0.5em; font-size: 0.85rem;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Add Event',
                    confirmButtonColor: '#10b981',
                    showCancelButton: true,
                    preConfirm: () => {
                        const name = document.getElementById('swal-evname').value;
                        if (!name) {
                            Swal.showValidationMessage('Event name is required');
                            return false;
                        }

                        // Collect Rules
                        const rules = [];
                        document.querySelectorAll('.rule-input').forEach(input => {
                            if (input.value.trim() !== '') rules.push(input.value.trim());
                        });

                        // Collect Topics
                        const topics = [];
                        document.querySelectorAll('.topic-input').forEach(input => {
                            if (input.value.trim() !== '') topics.push(input.value.trim());
                        });

                        return {
                            name: name,
                            image: document.getElementById('swal-image').files[0],
                            type: document.getElementById('swal-type').value,
                            day: document.getElementById('swal-day').value,
                            venue: document.getElementById('swal-venue').value,
                            time: document.getElementById('swal-start-time').value + ' - ' + document.getElementById('swal-end-time').value,
                            rules: JSON.stringify(rules),
                            topics: JSON.stringify(topics)
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('add_new_event', true);
                        formData.append('name', result.value.name);
                        formData.append('type', result.value.type);
                        formData.append('day', result.value.day);
                        formData.append('venue', result.value.venue);
                        formData.append('time', result.value.time);
                        formData.append('rules', result.value.rules);
                        formData.append('topics', result.value.topics);
                        if (result.value.image) {
                            formData.append('image', result.value.image);
                        }

                        $.ajax({
                            url: 'backend.php',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            success: function (response) {
                                if (response.status == 200) {
                                    Swal.fire('Added!', response.message, 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'Failed to communicate with server', 'error');
                            }
                        });
                    }
                });
            });

            // Edit Event Click
            $(document).on('click', '.btn-edit', function () {
                const row = $(this).closest('tr');
                const eventId = row.find('.event-status-toggle').data('id');
                const eventName = row.find('td:eq(1)').text().trim();
                const eventCategory = row.find('td:eq(2)').text().trim();
                const eventType = row.find('td:eq(3)').text().trim();
                const eventDayRaw = row.find('td:eq(4)').text().trim(); // "Day1" or "Day 1"
                const eventDay = eventDayRaw.toLowerCase().replace(' ', ''); // normalize to day1/day2
                const eventVenue = row.find('td:eq(5)').text().trim();
                const eventTime = row.find('td:eq(6)').text().trim();

                // Parse time range (e.g., "9:00 AM - 10:00 AM" -> ["9:00 AM", "10:00 AM"])
                const timeParts = eventTime.split(' - ');
                const eventStartTime = timeParts[0] || '';
                const eventEndTime = timeParts[1] || '';

                // Decode HTML entities if necessary
                const eventDesc = row.attr('data-description') || '';
                const eventRulesRaw = row.attr('data-rules') || '';
                const eventTopicsRaw = row.attr('data-topics') || '';

                // Helper to parse rules/topics (JSON or Legacy String)
                function parseList(raw) {
                    try {
                        const parsed = JSON.parse(raw);
                        if (Array.isArray(parsed)) return parsed;
                    } catch (e) {
                        // Not JSON, assume legacy text (split by bullet or newline)
                        if (raw.trim() === '') return [];
                        // Replace bullet points if present, then split
                        let clean = raw.replace(/•/g, '').split('\n').map(s => s.trim()).filter(s => s !== '');
                        return clean;
                    }
                    return [];
                }

                const rulesList = parseList(eventRulesRaw);
                const topicsList = parseList(eventTopicsRaw);

                // Build Dynamic Fields HTML
                function buildFields(list, type) {
                    let html = '';
                    list.forEach(item => {
                        html += `
                        <div class="dynamic-field-row" style="display:flex; margin-bottom:5px;">
                            <input type="text" class="swal2-input ${type}-edit-input" value="${item.replace(/"/g, '&quot;')}" style="flex:1; margin:0; min-width:0;">
                            <button type="button" style="background:#fee2e2; color:#ef4444; border:none; border-radius:4px; padding:0 10px; cursor:pointer;" onclick="this.parentElement.remove()">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>`;
                    });
                    // Fallback empty if list is empty
                    if (list.length === 0) {
                        html += `
                        <div class="dynamic-field-row" style="display:flex; margin-bottom:5px;">
                            <input type="text" class="swal2-input ${type}-edit-input" placeholder="Add ${type}..." style="flex:1; margin:0; min-width:0;">
                        </div>`;
                    }
                    return html;
                }

                Swal.fire({
                    title: `Edit ${eventName}`,
                    width: '600px',
                    padding: '1.5em',
                    html: `
                        <style>
                            .swal2-input.form-control { margin: 0 !important; font-size: 0.9rem !important; width: 100%; box-sizing: border-box; }
                            .swal2-textarea { height: 80px !important; min-height: 80px !important; resize: vertical; margin: 0 !important; font-size: 0.9rem !important;}
                            .form-label { display: block; text-align: left; font-size: 0.85rem; color: #444; font-weight: 600; margin-bottom: 6px; margin-top: 10px; }
                            .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
                            .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
                            
                            /* Dynamic Lists */
                            .dynamic-section { background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 5px; max-height: 200px; overflow-y: auto; }
                            .add-btn-sm { background: #3b82f6; color: white; border: none; border-radius: 4px; padding: 4px 8px; font-size: 0.8rem; cursor: pointer; margin-top: 5px; display: inline-flex; align-items: center; gap: 5px; }
                        </style>
                        <div style="text-align: left;">
                            <div>
                                <label class="form-label" style="margin-top: 0;">Event Name</label>
                                <input id="swal-evname" class="swal2-input form-control" placeholder="Event Name" value="${eventName}">
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Type</label>
                                    <select id="swal-type" class="swal2-input form-control">
                                        <option value="Solo" ${eventType === 'Solo' ? 'selected' : ''}>Solo</option>
                                        <option value="Group" ${eventType === 'Group' ? 'selected' : ''}>Group</option>
                                        <option value="Both" ${eventType === 'Both' ? 'selected' : ''}>Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Day</label>
                                    <select id="swal-day" class="swal2-input form-control">
                                        <option value="day1" ${eventDay === 'day1' ? 'selected' : ''}>Day 1</option>
                                        <option value="day2" ${eventDay === 'day2' ? 'selected' : ''}>Day 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Venue</label>
                                    <input id="swal-venue" class="swal2-input form-control" placeholder="Venue" value="${eventVenue}">
                                </div>
                                <div></div>
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Start Time</label>
                                    <select id="swal-start-time" class="swal2-input form-control">
                                        <option value="">Select Start Time</option>
                                        <option value="8:00 AM" ${eventStartTime === '8:00 AM' ? 'selected' : ''}>8:00 AM</option>
                                        <option value="8:30 AM" ${eventStartTime === '8:30 AM' ? 'selected' : ''}>8:30 AM</option>
                                        <option value="9:00 AM" ${eventStartTime === '9:00 AM' ? 'selected' : ''}>9:00 AM</option>
                                        <option value="9:30 AM" ${eventStartTime === '9:30 AM' ? 'selected' : ''}>9:30 AM</option>
                                        <option value="10:00 AM" ${eventStartTime === '10:00 AM' ? 'selected' : ''}>10:00 AM</option>
                                        <option value="10:30 AM" ${eventStartTime === '10:30 AM' ? 'selected' : ''}>10:30 AM</option>
                                        <option value="11:00 AM" ${eventStartTime === '11:00 AM' ? 'selected' : ''}>11:00 AM</option>
                                        <option value="11:30 AM" ${eventStartTime === '11:30 AM' ? 'selected' : ''}>11:30 AM</option>
                                        <option value="12:00 PM" ${eventStartTime === '12:00 PM' ? 'selected' : ''}>12:00 PM</option>
                                        <option value="12:30 PM" ${eventStartTime === '12:30 PM' ? 'selected' : ''}>12:30 PM</option>
                                        <option value="1:00 PM" ${eventStartTime === '1:00 PM' ? 'selected' : ''}>1:00 PM</option>
                                        <option value="1:30 PM" ${eventStartTime === '1:30 PM' ? 'selected' : ''}>1:30 PM</option>
                                        <option value="2:00 PM" ${eventStartTime === '2:00 PM' ? 'selected' : ''}>2:00 PM</option>
                                        <option value="2:30 PM" ${eventStartTime === '2:30 PM' ? 'selected' : ''}>2:30 PM</option>
                                        <option value="3:00 PM" ${eventStartTime === '3:00 PM' ? 'selected' : ''}>3:00 PM</option>
                                        <option value="3:30 PM" ${eventStartTime === '3:30 PM' ? 'selected' : ''}>3:30 PM</option>
                                        <option value="4:00 PM" ${eventStartTime === '4:00 PM' ? 'selected' : ''}>4:00 PM</option>
                                        <option value="4:30 PM" ${eventStartTime === '4:30 PM' ? 'selected' : ''}>4:30 PM</option>
                                        <option value="5:00 PM" ${eventStartTime === '5:00 PM' ? 'selected' : ''}>5:00 PM</option>
                                        <option value="5:30 PM" ${eventStartTime === '5:30 PM' ? 'selected' : ''}>5:30 PM</option>
                                        <option value="6:00 PM" ${eventStartTime === '6:00 PM' ? 'selected' : ''}>6:00 PM</option>
                                        <option value="6:30 PM" ${eventStartTime === '6:30 PM' ? 'selected' : ''}>6:30 PM</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">End Time</label>
                                    <select id="swal-end-time" class="swal2-input form-control">
                                        <option value="">Select End Time</option>
                                        <option value="8:00 AM" ${eventEndTime === '8:00 AM' ? 'selected' : ''}>8:00 AM</option>
                                        <option value="8:30 AM" ${eventEndTime === '8:30 AM' ? 'selected' : ''}>8:30 AM</option>
                                        <option value="9:00 AM" ${eventEndTime === '9:00 AM' ? 'selected' : ''}>9:00 AM</option>
                                        <option value="9:30 AM" ${eventEndTime === '9:30 AM' ? 'selected' : ''}>9:30 AM</option>
                                        <option value="10:00 AM" ${eventEndTime === '10:00 AM' ? 'selected' : ''}>10:00 AM</option>
                                        <option value="10:30 AM" ${eventEndTime === '10:30 AM' ? 'selected' : ''}>10:30 AM</option>
                                        <option value="11:00 AM" ${eventEndTime === '11:00 AM' ? 'selected' : ''}>11:00 AM</option>
                                        <option value="11:30 AM" ${eventEndTime === '11:30 AM' ? 'selected' : ''}>11:30 AM</option>
                                        <option value="12:00 PM" ${eventEndTime === '12:00 PM' ? 'selected' : ''}>12:00 PM</option>
                                        <option value="12:30 PM" ${eventEndTime === '12:30 PM' ? 'selected' : ''}>12:30 PM</option>
                                        <option value="1:00 PM" ${eventEndTime === '1:00 PM' ? 'selected' : ''}>1:00 PM</option>
                                        <option value="1:30 PM" ${eventEndTime === '1:30 PM' ? 'selected' : ''}>1:30 PM</option>
                                        <option value="2:00 PM" ${eventEndTime === '2:00 PM' ? 'selected' : ''}>2:00 PM</option>
                                        <option value="2:30 PM" ${eventEndTime === '2:30 PM' ? 'selected' : ''}>2:30 PM</option>
                                        <option value="3:00 PM" ${eventEndTime === '3:00 PM' ? 'selected' : ''}>3:00 PM</option>
                                        <option value="3:30 PM" ${eventEndTime === '3:30 PM' ? 'selected' : ''}>3:30 PM</option>
                                        <option value="4:00 PM" ${eventEndTime === '4:00 PM' ? 'selected' : ''}>4:00 PM</option>
                                        <option value="4:30 PM" ${eventEndTime === '4:30 PM' ? 'selected' : ''}>4:30 PM</option>
                                        <option value="5:00 PM" ${eventEndTime === '5:00 PM' ? 'selected' : ''}>5:00 PM</option>
                                        <option value="5:30 PM" ${eventEndTime === '5:30 PM' ? 'selected' : ''}>5:30 PM</option>
                                        <option value="6:00 PM" ${eventEndTime === '6:00 PM' ? 'selected' : ''}>6:00 PM</option>
                                        <option value="6:30 PM" ${eventEndTime === '6:30 PM' ? 'selected' : ''}>6:30 PM</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div style="margin-top: 10px;">
                                <label class="form-label">Rules <button type="button" class="add-btn-sm" onclick="addField('rule-edit', 'rules-edit-container')"><i class="ri-add-line"></i> Add</button></label>
                                <div id="rules-edit-container" class="dynamic-section">
                                    ${buildFields(rulesList, 'rule')}
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <label class="form-label">Topics <button type="button" class="add-btn-sm" onclick="addField('topic-edit', 'topics-edit-container')"><i class="ri-add-line"></i> Add</button></label>
                                <div id="topics-edit-container" class="dynamic-section">
                                        ${buildFields(topicsList, 'topic')}
                                </div>
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Update Event',
                    confirmButtonColor: '#134e4a',
                    showCancelButton: true,
                    preConfirm: () => {
                        const name = document.getElementById('swal-evname').value;

                        // Collect Rules
                        const rules = [];
                        document.querySelectorAll('.rule-edit-input').forEach(input => {
                            if (input.value.trim() !== '') rules.push(input.value.trim());
                        });

                        // Collect Topics
                        const topics = [];
                        document.querySelectorAll('.topic-edit-input').forEach(input => {
                            if (input.value.trim() !== '') topics.push(input.value.trim());
                        });

                        return {
                            name: name,
                            type: document.getElementById('swal-type').value,
                            day: document.getElementById('swal-day').value,
                            venue: document.getElementById('swal-venue').value,
                            time: document.getElementById('swal-start-time').value + ' - ' + document.getElementById('swal-end-time').value,
                            rules: JSON.stringify(rules),
                            topics: JSON.stringify(topics)
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'backend.php',
                            type: 'POST',
                            data: {
                                update_event_details: true,
                                id: eventId,
                                name: result.value.name,
                                type: result.value.type,
                                day: result.value.day,
                                venue: result.value.venue,
                                time: result.value.time,
                                rules: result.value.rules,
                                topics: result.value.topics
                            },
                            dataType: 'json',
                            success: function (response) {
                                if (response.status == 200) {
                                    Swal.fire('Updated!', response.message, 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error', 'Failed to communicate with server', 'error');
                            }
                        });
                    }
                });
            });

            // Toggle Switch Change
            $(document).on('change', '.event-status-toggle', function () {
                const isChecked = $(this).is(':checked');
                const row = $(this).closest('tr');
                const eventName = row.find('td:eq(1)').text();
                const eventId = $(this).data('id');
                const newStatus = isChecked ? 1 : 0;

                const statusText = isChecked ? 'Open' : 'Closed';

                $.ajax({
                    url: 'backend.php',
                    type: 'POST',
                    data: {
                        update_event_status: true,
                        id: eventId,
                        status: newStatus
                    },
                    success: function (response) {
                        try {
                            var res;
                            if (typeof response === 'string') {
                                try {
                                    res = JSON.parse(response);
                                } catch (e) { console.log(response); }
                            } else {
                                res = response;
                            }

                            if (res && res.status == 200) {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });

                                Toast.fire({
                                    icon: 'success',
                                    title: `${eventName}: ${statusText}`
                                });
                            } else {
                                Swal.fire('Error', res ? res.message : 'Unknown error', 'error');
                                // Revert switch
                                $(this).prop('checked', !isChecked);
                            }
                        } catch (e) {
                            console.error(e);
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Connection failed', 'error');
                        $(this).prop('checked', !isChecked);
                    }
                });
            });

            // Bulk Status Toggle Change
            $(document).on('change', '.bulk-status-toggle', function () {
                const isChecked = $(this).is(':checked');
                const scope = $(this).data('scope');
                const newStatus = isChecked ? 1 : 0;
                bulkUpdate(scope, newStatus, $(this));
            });

            // Bulk Delete Logic
            $('#selectAll').change(function () {
                $('.select-row').prop('checked', $(this).is(':checked'));
                toggleBulkDeleteBtn();
            });

            $(document).on('change', '.select-row', function () {
                toggleBulkDeleteBtn();
                // Update selectAll checkbox
                if ($('.select-row:checked').length === $('.select-row').length && $('.select-row').length > 0) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
            });

            function toggleBulkDeleteBtn() {
                if ($('.select-row:checked').length > 0) {
                    $('#bulkDeleteBtn').fadeIn();
                } else {
                    $('#bulkDeleteBtn').fadeOut();
                }
            }

            $('#bulkDeleteBtn').click(function () {
                const selectedIds = [];
                $('.select-row:checked').each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: 'Delete Selected Events?',
                    text: `You are about to delete ${selectedIds.length} event(s). This cannot be undone!`,
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
                            data: {
                                bulk_delete_events: true,
                                ids: selectedIds
                            },
                            success: function (response) {
                                try {
                                    const res = JSON.parse(response);
                                    if (res.status == 200) {
                                        Swal.fire('Deleted!', res.message, 'success').then(() => location.reload());
                                    } else {
                                        Swal.fire('Error', res.message, 'error');
                                    }
                                } catch (e) {
                                    Swal.fire('Error', 'Invalid server response', 'error');
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

        function bulkUpdate(scope, status, toggleElement) {
            let titleText = '';
            let statusText = status === 1 ? 'Open' : 'Close';

            if (scope === 'all') titleText = `${statusText} All Events?`;
            else if (scope === 'solo') titleText = `${statusText} All Solo Events?`;
            else if (scope === 'group') titleText = `${statusText} All Group Events?`;

            Swal.fire({
                title: 'Are you sure?',
                text: titleText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Yes, ${statusText} them!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'backend.php',
                        type: 'POST',
                        data: {
                            bulk_update_status: true,
                            scope: scope,
                            status: status
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 200) {
                                Swal.fire('Success', response.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                                toggleElement.prop('checked', !status); // Revert on logic error
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to communicate with server', 'error');
                            toggleElement.prop('checked', !status); // Revert on network error
                        }
                    });
                } else {
                    // User cancelled, revert toggle
                    toggleElement.prop('checked', !status);
                }
            });
        }

        // Delete Event Button Click
        $(document).on('click', '.btn-delete', function () {
            const eventId = $(this).data('id');
            Swal.fire({
                title: 'Delete Event?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'backend.php',
                        type: 'POST',
                        data: { delete_event: true, id: eventId },
                        success: function (response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.status == 200) {
                                    Swal.fire('Deleted!', res.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', res.message, 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Invalid server response: ' + response, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Connection failed', 'error');
                        }
                    });
                }
            });
        });

        // Reset Event IDs Button Click
        $('.btn-reset-ids').click(function () {
            Swal.fire({
                title: 'Reset Event IDs?',
                text: "This will re-order all Event IDs sequentially starting from 1. This cannot be undone!",
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
                        data: { reset_ids: true, type: 'events' },
                        success: function (response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.status == 200) {
                                    Swal.fire('Reset!', res.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', res.message, 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Invalid server response', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Connection failed', 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>