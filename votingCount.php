<?php
include 'includes/auth.php';
// Special access check for Photography Voting Count
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
    <title>Voting Counts - Orlia'26</title>
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
        <!-- Sidebar -->
        <?php
        $page = 'voting_count';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Real-time Analysis</span>
                        <h1 class="admin-title">Voting Counts</h1>
                    </div>
                </div>

                <div class="header-right">
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

            <!-- CSS for 3D Podium -->
            <style>
                .podium-container {
                    display: flex;
                    justify-content: center;
                    align-items: flex-end;
                    gap: 15px;
                    /* Gap between cylinders */
                    margin-bottom: 50px;
                    padding-top: 40px;
                    perspective: 1000px;
                }

                .podium-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    position: relative;
                    z-index: 1;
                    min-width: 130px;
                    transition: transform 0.3s ease;
                }

                .podium-item:hover {
                    transform: translateY(-5px);
                }

                /* 3D Cylinder Body - General */
                .podium-block {
                    width: 100px;
                    /* Reduced from 140px */
                    display: flex;
                    justify-content: center;
                    align-items: flex-start;
                    padding-top: 15px;
                    position: relative;
                    border-radius: 0 0 10px 10px;
                    transition: all 0.3s ease;
                }

                /* Cylinder Top Cap - General */
                .podium-block::before {
                    content: '';
                    position: absolute;
                    top: -14px;
                    /* PROPORTIONAL ADJUST: Reduced from -19px to match new width/aspect */
                    left: 0;
                    right: 0;
                    height: 28px;
                    /* Reduced from 40px */
                    border-radius: 50%;
                    z-index: 1;
                    transition: all 0.3s ease;
                }

                /* Rank Number styling */
                .rank-number {
                    font-family: 'Outfit', sans-serif;
                    font-size: 3.5rem;
                    /* Reduced from 5rem */
                    font-weight: 800;
                    z-index: 5;
                    line-height: 1;
                    margin-top: 10px;
                    background: none;
                    -webkit-text-fill-color: #fff;
                    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
                }

                /* Rank 1 - Golden/Yellow */
                .rank-1 {
                    order: 2;
                    z-index: 10;
                    margin: 0 5px;
                }

                .rank-1 .podium-block {
                    min-height: 180px;
                    /* Reduced from 240px */
                    background: linear-gradient(90deg, #FFC107 0%, #FFD700 25%, #FFECB3 50%, #FFC107 75%, #FFA000 100%);
                    box-shadow: 0 15px 30px rgba(255, 160, 0, 0.4), inset 0 -10px 20px rgba(180, 100, 0, 0.1);
                }

                .rank-1 .podium-block::before {
                    background: radial-gradient(circle at center, #FFF8E1 30%, #FFC107 100%);
                    border: 1px solid #FFECB3;
                }

                /* Rank 2 - Silver/Blue-Grey */
                .rank-2 {
                    order: 1;
                    margin-right: -10px;
                    z-index: 5;
                }

                .rank-2 .podium-block {
                    min-height: 140px;
                    /* Reduced from 180px */
                    background: linear-gradient(90deg, #B0BEC5 0%, #CFD8DC 25%, #ECEFF1 50%, #B0BEC5 75%, #78909C 100%);
                    box-shadow: 0 15px 30px rgba(84, 110, 122, 0.3), inset 0 -10px 20px rgba(0, 0, 0, 0.1);
                }

                .rank-2 .podium-block::before {
                    background: radial-gradient(circle at center, #FFFFFF 30%, #CFD8DC 100%);
                    border: 1px solid #ECEFF1;
                }

                /* Rank 3 - Bronze/Deep Orange */
                .rank-3 {
                    order: 3;
                    margin-left: -10px;
                    z-index: 5;
                }

                .rank-3 .podium-block {
                    min-height: 100px;
                    /* Reduced from 140px */
                    background: linear-gradient(90deg, #D84315 0%, #FF7043 25%, #FFAB91 50%, #E64A19 75%, #BF360C 100%);
                    box-shadow: 0 15px 30px rgba(191, 54, 12, 0.3), inset 0 -10px 20px rgba(0, 0, 0, 0.1);
                }

                .rank-3 .podium-block::before {
                    background: radial-gradient(circle at center, #FFCCBC 30%, #FF7043 100%);
                    border: 1px solid #FFAB91;
                }

                /* Content on top (Avatar + Trophy) */
                .podium-content {
                    position: relative;
                    z-index: 10;
                    margin-bottom: -20px;
                    /* Sit on the podium */
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding-bottom: 25px;
                    /* Space from surface */
                }

                .trophy-icon {
                    font-size: 2.5rem;
                    margin-bottom: 5px;
                    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
                    animation: float 3s ease-in-out infinite;
                }

                .rank-1 .trophy-icon {
                    color: #FFD700;
                    font-size: 3.5rem;
                }

                .rank-2 .trophy-icon {
                    color: #C0C0C0;
                }

                .rank-3 .trophy-icon {
                    color: #CD7F32;
                }

                .podium-avatar {
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: white;
                    border: 4px solid #fff;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    color: #444;
                    font-size: 1rem;
                    position: relative;
                }

                .rank-1 .podium-avatar {
                    width: 75px;
                    height: 75px;
                    font-size: 1.2rem;
                    border-color: #FFD700;
                }

                .rank-2 .podium-avatar {
                    border-color: #C0C0C0;
                }

                .rank-3 .podium-avatar {
                    border-color: #CD7F32;
                }

                .vote-badge {
                    background: #fff;
                    padding: 4px 14px;
                    border-radius: 20px;
                    font-size: 0.75rem;
                    font-weight: 700;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    margin-top: -10px;
                    z-index: 12;
                    position: relative;
                    color: #555;
                    white-space: nowrap;
                }

                @keyframes float {

                    0%,
                    100% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-8px);
                    }
                }

                /* Responsive Adjustments */
                @media (max-width: 768px) {
                    .podium-container {
                        gap: 5px;
                    }

                    .podium-block {
                        width: 90px;
                    }

                    .rank-number {
                        font-size: 3rem;
                    }

                    .podium-avatar {
                        width: 45px;
                        height: 45px;
                        font-size: 0.8rem;
                    }

                    .rank-1 .podium-avatar {
                        width: 60px;
                        height: 60px;
                    }
                }
            </style>

            <?php
            include 'db.php';
            // Aggregate votes: Count distinct rows per 'vote' value
            $sql = "SELECT vote, COUNT(*) as vote_count 
                    FROM photography 
                    GROUP BY vote 
                    ORDER BY vote_count DESC";
            $result = mysqli_query($conn, $sql);

            $rows = [];
            if ($result && mysqli_num_rows($result) > 0) {
                while ($r = mysqli_fetch_assoc($result)) {
                    $rows[] = $r;
                }
            }

            // Top 3 for Podium
            $top1 = isset($rows[0]) ? $rows[0] : null;
            $top2 = isset($rows[1]) ? $rows[1] : null;
            $top3 = isset($rows[2]) ? $rows[2] : null;
            ?>

            <!-- Podium Display -->
            <div class="podium-container">
                <!-- 1st Runner Up (Rank 2) -->
                <?php if ($top2): ?>
                    <div class="podium-item rank-2">
                        <div class="podium-content">
                            <i class="ri-trophy-fill trophy-icon"></i>
                            <div class="podium-avatar">
                                #<?= $top2['vote'] ?>
                            </div>
                            <div class="vote-badge"><?= $top2['vote_count'] ?> Votes</div>
                        </div>
                        <div class="podium-block">
                            <span class="rank-number">2</span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Winner (Rank 1) -->
                <?php if ($top1): ?>
                    <div class="podium-item rank-1">
                        <div class="podium-content">
                            <i class="ri-trophy-fill trophy-icon"></i>
                            <div class="podium-avatar">
                                #<?= $top1['vote'] ?>
                            </div>
                            <div class="vote-badge"><?= $top1['vote_count'] ?> Votes</div>
                        </div>
                        <div class="podium-block">
                            <span class="rank-number">1</span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 2nd Runner Up (Rank 3) -->
                <?php if ($top3): ?>
                    <div class="podium-item rank-3">
                        <div class="podium-content">
                            <i class="ri-trophy-fill trophy-icon"></i>
                            <div class="podium-avatar">
                                #<?= $top3['vote'] ?>
                            </div>
                            <div class="vote-badge"><?= $top3['vote_count'] ?> Votes</div>
                        </div>
                        <div class="podium-block">
                            <span class="rank-number">3</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Leaderboard Table -->
            <div class="table-container">
                <table id="votingTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Photo ID (Vote)</th>
                            <th>Total Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($rows)) {
                            $i = 1;
                            foreach ($rows as $row) {
                                ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><span
                                            style="font-weight: 600; font-size: 1.1em;">#<?= htmlspecialchars($row['vote']) ?></span>
                                    </td>
                                    <td><span class="status-badge status-active"
                                            style="font-size: 1em; padding: 6px 12px;"><?= htmlspecialchars($row['vote_count']) ?></span>
                                    </td>
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
                orientation: 'portrait',
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
                    // 3 Columns: S.No, Photo ID, Count
                    widths = ['15%', '40%', '45%'];
                    doc.content[1].table.widths = widths;

                    var body = doc.content[1].table.body;
                    for (var i = 1; i < body.length; i++) {
                        body[i][0].margin = [0, 10, 0, 10];
                    }

                    doc.header = function (currentPage, pageCount, pageSize) {
                        var headerContent = [];

                        // Header logos only
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

            $('#votingTable').DataTable({
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
                order: [[2, 'desc']] // Sort by Total Votes (column index 2) descending
            });
        });
    </script>
</body>

</html>