<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Fallback if session role isn't set (shouldn't happen due to page checks)
$currentRole = $_SESSION['role'] ?? '';
$page = isset($page) ? $page : '';
?>
<nav class="admin-sidebar">
    <div class="admin-brand">
        <?php
        if ($currentRole == '2')
            echo 'SUPER ADMIN';
        elseif ($currentRole == '1')
            echo 'EVENT ADMIN';
        else
            echo 'ORLIA ADMIN';
        ?>
    </div>
    <ul class="admin-nav">
        <?php if ($currentRole == '2'): // Super Admin ?>
            <li><a href="superAdmin.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i
                        class="ri-dashboard-3-line"></i> Dashboard</a></li>
            <li><a href="manageAdmins.php" class="<?= $page === 'admins' ? 'active' : '' ?>"><i
                        class="ri-shield-user-line"></i> Manage Admins</a></li>
            <li><a href="overallParticipants.php" class="<?= $page === 'participants' ? 'active' : '' ?>"><i
                        class="ri-group-line"></i> Overall Participants</a></li>
            <li><a href="manageEvent.php" class="<?= $page === 'events' ? 'active' : '' ?>"><i
                        class="ri-calendar-check-line"></i> Manage Events</a></li>
            <?php
            // Check if any photography sub-page is active
            $photoPages = ['photo_collection', 'voting_dashboard', 'voting_count', 'voters_list'];
            $isPhotoActive = in_array($page, $photoPages);
            ?>
            <li class="has-submenu <?= $isPhotoActive ? 'open' : '' ?>">
                <a href="javascript:void(0)" class="submenu-toggle <?= $isPhotoActive ? 'active' : '' ?>">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <i class="ri-camera-lens-line"></i> Photography
                    </div>
                    <i class="ri-arrow-down-s-line menu-arrow"></i>
                </a>
                <ul class="custom-submenu"
                    style="<?= $isPhotoActive ? 'display:flex; flex-direction:column;' : 'display:none;' ?>">
                    <li><a href="photographyCollection.php" class="<?= $page === 'photo_collection' ? 'active' : '' ?>"><i
                                class="ri-image-line"></i> Photo Collection</a></li>
                    <li><a href="votingDashboard.php" class="<?= $page === 'voting_dashboard' ? 'active' : '' ?>"><i
                                class="ri-dashboard-line"></i> Dashboard</a></li>
                    <li><a href="votingCount.php" class="<?= $page === 'voting_count' ? 'active' : '' ?>"><i
                                class="ri-bar-chart-grouped-line"></i> Voting Counts</a></li>
                    <li><a href="votersList.php" class="<?= $page === 'voters_list' ? 'active' : '' ?>"><i
                                class="ri-list-check"></i> Voters List</a></li>
                </ul>
            </li>
            <li><a href="eventFeedback.php" class="<?= $page === 'feedback' ? 'active' : '' ?>"><i
                        class="ri-feedback-line"></i> Feedback</a></li>

        <?php elseif ($currentRole == '1'): // Event Admin ?>
            <?php
            $eventKey = $_SESSION['userid'] ?? '';
            if (strtolower($eventKey) === 'photography'):
                ?>
                <!-- Photography Admin Menu -->
                <li><a href="eventAdmin.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i
                            class="ri-dashboard-line"></i> Dashboard</a></li>
                <li><a href="eventParticipants.php" class="<?= $page === 'participants' ? 'active' : '' ?>"><i
                            class="ri-user-line"></i> Participants</a></li>
                <li><a href="photographyCollection.php" class="<?= $page === 'photo_collection' ? 'active' : '' ?>"><i
                            class="ri-image-line"></i> Photo Collection</a></li>
                <li><a href="votingDashboard.php" class="<?= $page === 'voting_dashboard' ? 'active' : '' ?>"><i
                            class="ri-dashboard-3-line"></i>Voting Dashboard</a></li>
                <li><a href="votingCount.php" class="<?= $page === 'voting_count' ? 'active' : '' ?>"><i
                            class="ri-bar-chart-grouped-line"></i> Voting Counts</a></li>
                <li><a href="votersList.php" class="<?= $page === 'voters_list' ? 'active' : '' ?>"><i
                            class="ri-list-check"></i> Voters List</a></li>

            <?php else: ?>
                <!-- Standard Event Admin Menu -->
                <li><a href="eventAdmin.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i
                            class="ri-dashboard-line"></i> Dashboard</a></li>
                <li><a href="eventParticipants.php" class="<?= $page === 'participants' ? 'active' : '' ?>"><i
                            class="ri-user-line"></i> Participants</a></li>
            <?php endif; ?>

        <?php else: // Co-Admin (Role 0) ?>
            <li><a href="adminDashboard.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>"><i
                        class="ri-dashboard-line"></i> Dashboard</a></li>
            <li><a href="manageParticipants.php" class="<?= $page === 'participants' ? 'active' : '' ?>"><i
                        class="ri-user-line"></i> Participants</a></li>
            <li><a href="eventFeedback.php" class="<?= $page === 'feedback' ? 'active' : '' ?>"><i
                        class="ri-feedback-line"></i> Feedback</a></li>
        <?php endif; ?>
        <!-- <li><a href="logout.php" class="text-danger"><i class="ri-logout-box-line"></i> Logout</a></li> -->
    </ul>
</nav>

<style>
    /* Sidebar Submenu Styles - Inline to ensure immediate application */
    .admin-sidebar .custom-submenu {
        list-style: none !important;
        padding: 5px 0 5px 15px;
        /* Indent submenu */
        gap: 5px;
        flex-direction: column;
        /* Ensure vertical layout */
        margin: 0;
        width: 100%;
    }

    .admin-sidebar .custom-submenu li {
        width: 100%;
    }

    .admin-sidebar .custom-submenu li a {
        font-size: 0.9rem;
        padding: 8px 0 8px 12px;
        /* Adjust padding */
        background: transparent;
        opacity: 0.8;
        color: var(--text-muted);
        border-radius: 8px;
        display: flex;
        /* Ensure flex for alignment */
        width: 100%;
        text-decoration: none;
    }

    .admin-sidebar .custom-submenu li a:hover,
    .admin-sidebar .custom-submenu li a.active {
        background: var(--primary-light-bg);
        color: var(--primary-main);
        opacity: 1;
    }

    .admin-sidebar .has-submenu {
        position: relative;
        flex-direction: column;
        /* Allow wrapping */
        align-items: stretch;
        /* Stretch children */
        display: flex;
    }

    /* Override the default link style for the toggle to allow full width click */
    .admin-sidebar .submenu-toggle {
        justify-content: space-between !important;
        width: 100%;
        cursor: pointer;
    }

    .admin-sidebar .menu-arrow {
        transition: transform 0.3s ease;
        font-size: 1.1rem;
    }

    .admin-sidebar .has-submenu.open .menu-arrow {
        transform: rotate(180deg);
    }

    /* Ensure icons in submenu are sized correctly */
    .admin-sidebar .custom-submenu i {
        font-size: 1.1rem;
        margin-right: 8px;
        width: 20px;
        text-align: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.submenu-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent bubbling

                const parent = this.closest('.has-submenu');
                const submenu = parent.querySelector('.custom-submenu');
                const arrow = this.querySelector('.menu-arrow');

                if (parent.classList.contains('open')) {
                    // Close
                    parent.classList.remove('open');
                    submenu.style.display = 'none';
                    if (arrow) arrow.style.transform = 'rotate(0deg)';
                } else {
                    // Open
                    parent.classList.add('open');
                    submenu.style.display = 'flex';
                    submenu.style.flexDirection = 'column'; // Force column
                    if (arrow) arrow.style.transform = 'rotate(180deg)';
                }
            });
        });
    });
</script>