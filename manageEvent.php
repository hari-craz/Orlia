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
                    <button class="btn-reset-ids" title="Reset Event IDs"
                        style="margin-right: 15px; padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; aspect-ratio: 1/1; box-shadow: none;">
                        <i class="ri-refresh-line"></i>
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
            // Sort by Day then by Time (handling formats: 9:00 AM, 9.00 AM, 9 AM)
            $query = "SELECT * FROM events ORDER BY day ASC, COALESCE(STR_TO_DATE(REPLACE(event_time, '.', ':'), '%l:%i %p'), STR_TO_DATE(event_time, '%l %p'), event_time) ASC";
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


            <div class="table-container">
                <table id="eventsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Category</th>
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
                        $i = 1;
                        foreach ($eventsData as $row) {
                            $checked = $row['status'] == 1 ? 'checked' : '';
                            // Fallback for missing keys if table not yet altered
                            $venue = isset($row['event_venue']) ? $row['event_venue'] : '';
                            $time = isset($row['event_time']) ? $row['event_time'] : '';
                            $desc = isset($row['event_description']) ? htmlspecialchars($row['event_description']) : '';
                            $rulesData = isset($row['event_rules']) ? $row['event_rules'] : ''; // Raw data for table cell
                            $rulesHtml = htmlspecialchars($rulesData); // Escaped for data attribute
                            $topicsData = isset($row['event_topics']) ? $row['event_topics'] : ''; // Raw data for table cell
                            $topicsHtml = htmlspecialchars($topicsData); // Escaped for data attribute
                            ?>
                            <tr data-description="<?= $desc ?>" data-rules="<?= $rulesHtml ?>"
                                data-topics="<?= $topicsHtml ?>">
                                <td><?= $i++ ?></td>
                                <td data-key="<?= $row['event_key'] ?>"><?= $row['event_name'] ?></td>
                                <td><?= $row['event_category'] ?></td>
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
                                    <button class="btn-edit"
                                        style="border:none; background:none; cursor:pointer; color: var(--primary-color);">
                                        <i class="ri-edit-line"></i>
                                    </button>
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
                dom: 'Bfrtip',
                columnDefs: [
                    { visible: false, targets: [9, 10] } // Hide Rules and Topics on site
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

            // Edit Event Click
            $(document).on('click', '.btn-edit', function () {
                const row = $(this).closest('tr');
                const eventId = row.find('.event-status-toggle').data('id');
                const eventName = row.find('td:eq(1)').text().trim();
                const eventCategory = row.find('td:eq(2)').text().trim();
                const eventType = row.find('td:eq(3)').text().trim();
                const eventDay = row.find('td:eq(4)').text().trim();
                const eventVenue = row.find('td:eq(5)').text().trim();
                const eventTime = row.find('td:eq(6)').text().trim();

                // Decode HTML entities if necessary
                const eventDesc = row.attr('data-description') || '';
                const eventRules = row.attr('data-rules') || '';
                const eventTopics = row.attr('data-topics') || '';

                const eventKey = row.find('td:eq(1)').data('key');

                // Determine if Topics should be shown
                const allowedTopics = ['Tamilspeech', 'Englishspeech', 'Drawing'];
                const showTopics = allowedTopics.includes(eventKey);

                let rulesTopicsHtml = '';
                if (showTopics) {
                    rulesTopicsHtml = `
                        <div class="form-grid-2">
                            <div>
                                <label class="form-label">Rules</label>
                                <textarea id="swal-rules" class="swal2-textarea" placeholder="Rules" style="width: 100%;">${eventRules}</textarea>
                            </div>
                            <div>
                                <label class="form-label">Topics</label>
                                <textarea id="swal-topics" class="swal2-textarea" placeholder="Topics" style="width: 100%;">${eventTopics}</textarea>
                            </div>
                        </div>
                    `;
                } else {
                    rulesTopicsHtml = `
                        <div>
                            <label class="form-label">Rules</label>
                            <textarea id="swal-rules" class="swal2-textarea" placeholder="Rules" style="width: 100%;">${eventRules}</textarea>
                            <input type="hidden" id="swal-topics" value="">
                        </div>
                    `;
                }

                Swal.fire({
                    title: `Edit ${eventName}`,
                    width: '500px',
                    padding: '1.5em',
                    html: `
                        <style>
                            .swal2-input, .swal2-textarea { margin: 0 !important; font-size: 0.9rem !important; }
                            .swal2-textarea { height: 80px !important; min-height: 80px !important; resize: vertical; }
                            .form-label { display: block; text-align: left; font-size: 0.8rem; color: #666; font-weight: 600; margin-bottom: 4px; margin-top: 8px; }
                            .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
                            .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
                        </style>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <div>
                                <label class="form-label" style="margin-top: 0;">Event Name</label>
                                <input id="swal-evname" class="swal2-input" placeholder="Event Name" value="${eventName}" style="width: 100%;">
                            </div>

                            <div class="form-grid-3">
                                <div>
                                    <label class="form-label">Category</label>
                                    <select id="swal-category" class="swal2-input" style="width: 100%;">
                                        <option value="Technical" ${eventCategory === 'Technical' ? 'selected' : ''}>Technical</option>
                                        <option value="Non-Technical" ${eventCategory === 'Non-Technical' ? 'selected' : ''}>Non-Tech</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Type</label>
                                    <select id="swal-type" class="swal2-input" style="width: 100%;">
                                        <option value="Solo" ${eventType === 'Solo' ? 'selected' : ''}>Solo</option>
                                        <option value="Group" ${eventType === 'Group' ? 'selected' : ''}>Group</option>
                                        <option value="Both" ${eventType === 'Both' ? 'selected' : ''}>Both</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Day</label>
                                    <select id="swal-day" class="swal2-input" style="width: 100%;">
                                        <option value="day1" ${eventDay === 'day1' ? 'selected' : ''}>Day 1</option>
                                        <option value="day2" ${eventDay === 'day2' ? 'selected' : ''}>Day 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label">Venue</label>
                                    <input id="swal-venue" class="swal2-input" placeholder="Venue" value="${eventVenue}" style="width: 100%;">
                                </div>
                                <div>
                                    <label class="form-label">Time</label>
                                    <input id="swal-time" class="swal2-input" placeholder="Time" value="${eventTime}" style="width: 100%;">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Description</label>
                                <textarea id="swal-desc" class="swal2-textarea" placeholder="Event Description" style="width: 100%; height: 60px !important; min-height: 60px !important;">${eventDesc}</textarea>
                            </div>
                            
                            ${rulesTopicsHtml}
                        </div>
                    `,
                    // width: '600px', // Removed to reduce size per user request
                    confirmButtonText: 'Update Event',
                    confirmButtonColor: '#134e4a',
                    showCancelButton: true,
                    preConfirm: () => {
                        return {
                            name: document.getElementById('swal-evname').value,
                            category: document.getElementById('swal-category').value,
                            type: document.getElementById('swal-type').value,
                            day: document.getElementById('swal-day').value,
                            venue: document.getElementById('swal-venue').value,
                            time: document.getElementById('swal-time').value,
                            description: document.getElementById('swal-desc').value,
                            rules: document.getElementById('swal-rules').value,
                            topics: document.getElementById('swal-topics').value
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
                                category: result.value.category,
                                type: result.value.type,
                                day: result.value.day,
                                venue: result.value.venue,
                                time: result.value.time,
                                description: result.value.description,
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