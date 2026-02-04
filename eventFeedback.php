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
    <title>Feedback - Orlia'26</title>
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
        $role = ($_SESSION['role'] == '2') ? 'super' : 'admin';
        $page = 'feedback';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Event Feedback</span>
                        <h1 class="admin-title">Feedback Collection</h1>
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
                                <li><a href="logout.php" class="text-danger"><i class="ri-logout-box-line"></i>
                                        Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="welcome-card glass"
                style="padding: 30px; border-radius: 24px; margin-bottom: 30px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between;">
                <div style="position: relative; z-index: 2;">
                    <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 5px; color: var(--text-main);">User
                        Feedback</h2>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 0;">View collected feedback from
                        events</p>
                </div>
                <div style="font-size: 3rem; color: var(--fest-orange); opacity: 0.8;">
                    <i class="ri-chat-smile-2-line"></i>
                </div>
            </div>

            <!-- Feedback Table -->
            <div class="table-container">
                <table id="feedbackTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Event Pass</th>
                            <th>Rating</th>
                            <th>Suggestions</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM feedback ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);
                        $i = 1;
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $rating = isset($row['rating']) ? $row['rating'] : 0;
                                // Display stars for rating
                                $stars = '';
                                for ($j = 1; $j <= 5; $j++) {
                                    if ($j <= $rating) {
                                        $stars .= '<i class="ri-star-fill" style="color: #f59e0b;"></i>';
                                    } else {
                                        $stars .= '<i class="ri-star-line" style="color: #ddd;"></i>';
                                    }
                                }
                                $suggestions = isset($row['suggestions']) ? $row['suggestions'] : ($row['feedback_text'] ?? ''); // Fallback
                                ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['event_name']) ?></td>
                                    <td><span
                                            class="status-badge status-active"><?= htmlspecialchars($row['event_pass']) ?></span>
                                    </td>
                                    <td><?= $stars ?></td>
                                    <td style="max-width: 300px; white-space: normal;">
                                        <?= nl2br(htmlspecialchars($suggestions)) ?></td>
                                    <td><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></td>
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
    <script src="assets/script/script.js"></script>
    <script>
        $(document).ready(function () {
            $('#feedbackTable').DataTable({
                responsive: true
            });
        });
    </script>
</body>

</html>