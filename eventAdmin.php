<?php
include 'includes/auth.php';
checkUserAccess();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Admin Dashboard - Orlia'26</title>
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
        <!-- Sidebar -->
        <?php
        $role = 'event';
        $page = 'dashboard';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Event Overview</span>
                        <h1 class="admin-title">Dashboard</h1>
                    </div>
                </div>
                <div class="header-right">
                    <!-- Theme Toggle -->
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

            <?php
            include 'db.php';
            $eventKey = $_SESSION['userid']; // Assuming userid is the event key
            
            // 1. Get Totals
            $solo_query = "SELECT * FROM soloevents WHERE events='$eventKey' ORDER BY id ASC";
            $group_query = "SELECT * FROM groupevents WHERE events='$eventKey' ORDER BY id ASC";

            $solo_run = mysqli_query($conn, $solo_query);
            $group_run = mysqli_query($conn, $group_query);

            $solo_count = mysqli_num_rows($solo_run);
            $group_count = mysqli_num_rows($group_run);
            $total_reg = $solo_count + $group_count;

            // Optional: Calculate 'paid/verified' if columns exist. default to 0 for now as schema is unsure.
            $verified_count = 0;
            $pending_count = $total_reg;

            // 2. Fetch Recent (Merge and sort by ID desc is tricky without UNION, so we just take last 5 of each or standard)
            // Simpler: Just show recent 5 from solo for now, or mix.
            $recent_rows = [];
            while ($row = mysqli_fetch_assoc($solo_run)) {
                $row['type'] = 'Solo';
                $recent_rows[] = $row;
            }
            while ($row = mysqli_fetch_assoc($group_run)) {
                $row['type'] = 'Group';
                $row['name'] = $row['teamname']; // Normalize name
                $row['regno'] = $row['tregno']; // Normalize roll
                $recent_rows[] = $row;
            }
            // Sort by ID ascending (new request logic)
            usort($recent_rows, function ($a, $b) {
                return $a['id'] - $b['id'];
            });
            $recent_rows = array_slice($recent_rows, 0, 5);
            ?>

            <?php
            // Fetch Event Name
            $eventName = "Event Admin";
            $eventDetailsQuery = "SELECT * FROM events WHERE event_key='$eventKey' LIMIT 1";
            $eventDetailsResult = mysqli_query($conn, $eventDetailsQuery);
            if ($eventDetailsResult && mysqli_num_rows($eventDetailsResult) > 0) {
                $eventRow = mysqli_fetch_assoc($eventDetailsResult);
                $eventName = $eventRow['event_name'];
            }
            ?>

            <div class="welcome-card glass"
                style="padding: 30px; border-radius: 24px; margin-bottom: 30px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between;">
                <div style="position: relative; z-index: 2;">
                    <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 5px; color: var(--text-main);">Welcome,
                        <?= $eventName ?>
                    </h2>
                    <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 0;">Event Management Console</p>
                </div>
                <div style="font-size: 3rem; color: var(--fest-orange); opacity: 0.8;">
                    <i class="ri-calendar-event-line"></i>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-user-follow-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $total_reg ?></h3>
                        <p>Total Registrations</p>
                    </div>
                </div>
                <!-- Placeholder stats until schema confirmed -->
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-check-double-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $solo_count ?></h3>
                        <p>Solo Participants</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-group-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $group_count ?></h3>
                        <p>Group Teams</p>
                    </div>
                </div>
            </div>

            <!-- Feedback Collection Block -->
            <div class="glass"
                style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <div>
                        <h2 style="margin: 0 0 5px 0; font-size: 1.5rem;">Collect Feedback</h2>
                        <p style="color: #666; margin: 0;">Enter participant pass and feedback</p>
                    </div>
                    <div style="font-size: 2rem; color: var(--fest-orange); opacity: 0.8;">
                        <i class="ri-chat-smile-2-line"></i>
                    </div>
                </div>
                <form id="feedbackForm">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                        <div>
                            <label
                                style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Event
                                Pass ID</label>
                            <input type="text" id="fb_event_pass" placeholder="Enter ORA..." required
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                        </div>

                        <div>
                            <label
                                style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Event
                                Rating</label>
                            <div class="rating-stars"
                                style="display: flex; gap: 10px; font-size: 2rem; color: #ddd; cursor: pointer;">
                                <i class="ri-star-fill star" data-value="1"></i>
                                <i class="ri-star-fill star" data-value="2"></i>
                                <i class="ri-star-fill star" data-value="3"></i>
                                <i class="ri-star-fill star" data-value="4"></i>
                                <i class="ri-star-fill star" data-value="5"></i>
                            </div>
                            <input type="hidden" id="fb_rating" value="0" required>
                        </div>

                        <div>
                            <label
                                style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 8px;">Suggestions
                                for Improvement</label>
                            <textarea id="fb_suggestions" placeholder="What can we improve next time?" required
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; min-height: 100px; resize: vertical;"></textarea>
                        </div>
                        <div>
                            <button type="submit"
                                style="padding: 12px 25px; background: var(--primary-main); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="ri-send-plane-fill"></i> Submit Feedback
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Event Details Management Section -->
            <?php if (isset($eventRow)): ?>
                <div class="details-card glass"
                    style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                        <div>
                            <h2 style="margin: 0 0 10px 0; font-size: 1.5rem;">Event Details</h2>
                            <p style="color: #666; margin: 0;">Manage your event's public information</p>
                        </div>
                        <button class="btn-edit-details" data-id="<?= $eventRow['id'] ?>"
                            data-name="<?= htmlspecialchars($eventRow['event_name']) ?>"
                            data-category="<?= htmlspecialchars($eventRow['event_category']) ?>"
                            data-type="<?= htmlspecialchars($eventRow['event_type']) ?>"
                            data-day="<?= htmlspecialchars($eventRow['day']) ?>"
                            data-venue="<?= htmlspecialchars($eventRow['event_venue']) ?>"
                            data-time="<?= htmlspecialchars($eventRow['event_time']) ?>"
                            data-desc="<?= htmlspecialchars($eventRow['event_description']) ?>"
                            data-rules="<?= htmlspecialchars($eventRow['event_rules']) ?>"
                            data-topics="<?= isset($eventRow['event_topics']) ? htmlspecialchars($eventRow['event_topics']) : '' ?>"
                            style="padding: 10px 20px; background: var(--primary-main); color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                            <i class="ri-edit-line"></i> Edit Details
                        </button>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div>
                            <label
                                style="display: block; font-size: 0.85rem; color: #666; margin-bottom: 5px;">Venue</label>
                            <div style="font-weight: 500; font-size: 1.1rem;">
                                <?= !empty($eventRow['event_venue']) ? htmlspecialchars($eventRow['event_venue']) : '<span style="color:#999; font-style:italic;">Not Set</span>' ?>
                            </div>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.85rem; color: #666; margin-bottom: 5px;">Timing</label>
                            <div style="font-weight: 500; font-size: 1.1rem;">
                                <?= !empty($eventRow['event_time']) ? htmlspecialchars($eventRow['event_time']) : '<span style="color:#999; font-style:italic;">Not Set</span>' ?>
                            </div>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.85rem; color: #666; margin-bottom: 5px;">Category</label>
                            <div style="font-weight: 500; font-size: 1.1rem;">
                                <?= htmlspecialchars($eventRow['event_category']) ?>
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; color: #666; margin-bottom: 5px;">Type</label>
                            <div style="font-weight: 500; font-size: 1.1rem;">
                                <?= htmlspecialchars($eventRow['event_type']) ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($eventRow['event_rules'])): ?>
                        <div style="margin-top: 20px;">
                            <label style="display: block; font-size: 0.85rem; color: #666; margin-bottom: 5px;">Rules</label>
                            <div
                                style="background: #f8f9fa; padding: 15px; border-radius: 8px; font-size: 0.95rem; line-height: 1.6; white-space: pre-line;">
                                <?= htmlspecialchars($eventRow['event_rules']) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($eventRow['event_topics'])): ?>
                        <div style="margin-top: 20px;">
                            <label style="display: block; font-size: 0.85rem; color: #666; margin-bottom: 5px;">Topics</label>
                            <div
                                style="background: #f8f9fa; padding: 15px; border-radius: 8px; font-size: 0.95rem; line-height: 1.6; white-space: pre-line;">
                                <?= htmlspecialchars($eventRow['event_topics']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Recent Activity / Registrations -->
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 class="mb-4" style="margin-bottom: 0 !important;">Recent Registrations</h2>
                    <div style="display: flex; gap: 8px;">
                        <button class="btn-reset-ids" data-type="solo" title="Reset Solo IDs"
                            style="padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: none;">
                            <i class="ri-user-3-line"></i>
                        </button>
                        <button class="btn-reset-ids" data-type="group" title="Reset Group IDs"
                            style="padding: 10px; font-size: 1.1rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: none;">
                            <i class="ri-group-line"></i>
                        </button>
                    </div>
                </div>
                <table id="eventRecentTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name / Team</th>
                            <th>Roll No</th>
                            <th>Year</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($recent_rows as $row): ?>
                            <tr>
                                <td>#<?= ($row['type'] == 'Solo' ? 'S' : 'G') . $i++ ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['regno'] . ($row['type'] == 'Group' ? ' (Lead)' : '') ?></td>
                                <td><?= $row['year'] ?></td>
                                <td><span class="status-badge status-active"><?= $row['type'] ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_rows)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No registrations yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/script/script.js"></script>
    <script>
        $(document).ready(function () {
            $('#eventRecentTable').DataTable({
                responsive: true,
                paging: false,
                info: false,
                searching: false
            });

            // Edit Event Details
            $('.btn-edit-details').click(function () {
                const btn = $(this);
                const eventId = btn.data('id');
                const eventName = btn.data('name');
                const eventCategory = btn.data('category');
                const eventType = btn.data('type');
                const eventDay = btn.data('day');
                const eventVenue = btn.data('venue');
                const eventTime = btn.data('time');
                const eventDesc = btn.data('desc');
                const eventRules = btn.data('rules');
                const eventTopics = btn.data('topics');

                Swal.fire({
                    title: `Edit ${eventName}`,
                    width: '500px',
                    padding: '1em',
                    html: `
                        <style>
                            .swal2-input, .swal2-textarea { margin: 0 !important; font-size: 0.9rem !important; }
                            .swal2-textarea { height: 80px !important; min-height: 80px !important; resize: vertical; }
                            .form-label { display: block; text-align: left; font-size: 0.8rem; color: #666; font-weight: 600; margin-bottom: 4px; margin-top: 8px; }
                            .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
                        </style>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <div class="form-grid-2">
                                <div>
                                    <label class="form-label" style="margin-top: 0;">Venue</label>
                                    <input id="swal-venue" class="swal2-input" placeholder="Venue" value="${eventVenue}" style="width: 100%;">
                                </div>
                                <div>
                                    <label class="form-label" style="margin-top: 0;">Time</label>
                                    <input id="swal-time" class="swal2-input" placeholder="Time" value="${eventTime}" style="width: 100%;">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Description</label>
                                <textarea id="swal-desc" class="swal2-textarea" placeholder="Description" style="width: 100%; height: 60px !important; min-height: 60px !important;">${eventDesc}</textarea>
                            </div>

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
                        </div>
                        
                        <!-- Hidden inputs -->
                        <input type="hidden" id="swal-evname" value="${eventName}">
                        <input type="hidden" id="swal-category" value="${eventCategory}">
                        <input type="hidden" id="swal-type" value="${eventType}">
                        <input type="hidden" id="swal-day" value="${eventDay}">
                    `,
                    confirmButtonText: 'Save Changes',
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

            // Star Rating Logic
            $('.star').click(function () {
                const value = $(this).data('value');
                $('#fb_rating').val(value);

                // Update UI
                $('.star').each(function () {
                    if ($(this).data('value') <= value) {
                        $(this).css('color', '#f59e0b');
                    } else {
                        $(this).css('color', '#ddd');
                    }
                });
            });

            // Feedback Submission
            $('#feedbackForm').submit(function (e) {
                e.preventDefault();
                const pass = $('#fb_event_pass').val();
                const text = $('#fb_suggestions').val(); // suggestions
                const rating = $('#fb_rating').val();

                if (rating == 0) {
                    Swal.fire('Error', 'Please provide a rating (1-5 stars).', 'error');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'backend.php',
                    data: {
                        submit_feedback: true,
                        event_pass: pass,
                        feedback_text: text, // sending as suggestions
                        rating: rating
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status == 200) {
                            Swal.fire('Success', res.message, 'success').then(() => {
                                $('#feedbackForm')[0].reset();
                                // Reset stars
                                $('.star').css('color', '#ddd');
                                $('#fb_rating').val(0);
                            });
                        } else if (res.status == 409) {
                            Swal.fire('Warning', res.message, 'warning');
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire('Error', 'Failed to submit feedback. Check console.', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>