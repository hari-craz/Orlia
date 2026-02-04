<?php
include 'includes/auth.php';
// Special access check for Photography Voting Dashboard
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Dashboard - Orlia'26</title>
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
        $page = 'voting_dashboard';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <?php if ($role != '2'): ?>
                            <span class="section-subtitle">Voting System</span>
                            <h1 class="admin-title">Dashboard</h1>
                        <?php else: ?>
                            <span class="section-subtitle">Photography</span>
                            <h1 class="admin-title">Photography Voting Management</h1>
                        <?php endif; ?>
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

            <?php
            include 'db.php';
            // Get stats
            $total_votes = 0;
            // Ensure table exists or handle error silently
            $count_sql = "SELECT COUNT(*) as total FROM photography";
            $count_res = mysqli_query($conn, $count_sql);
            if ($count_res) {
                $row = mysqli_fetch_assoc($count_res);
                $total_votes = $row['total'];
            }
            ?>

            <?php if ($role != '2'): ?>
                <div class="welcome-card glass"
                    style="padding: 30px; border-radius: 24px; margin-bottom: 30px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: space-between;">
                    <div style="position: relative; z-index: 2;">
                        <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 5px; color: var(--text-main);">Welcome,
                            <?= $role == '2' ? 'Super Admin' : $eventKey . ' Admin' ?>
                        </h2>
                        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 0;">Manage Photography Voting &
                            Entries</p>
                    </div>
                    <div style="font-size: 3rem; color: var(--fest-blue); opacity: 0.8;">
                        <i class="ri-camera-3-line"></i>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Stats Grid -->
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-thumb-up-line"></i></div>
                    <div class="stat-info">
                        <h3><?= $total_votes ?></h3>
                        <p>Total Votes Cast</p>
                    </div>
                </div>
                <!-- Placeholder for future extensions -->
                <div class="stat-card">
                    <div class="stat-icon"><i class="ri-bar-chart-2-line"></i></div>
                    <div class="stat-info">
                        <h3>Live</h3>
                        <p>Voting Status</p>
                    </div>
                </div>
            </div>

            <!-- Recent Votes Table -->
            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 class="mb-4" style="margin-bottom: 0 !important;">Recent Votes</h2>
                    <button id="btnAddVote"
                        style="padding: 10px 20px; background: var(--primary-main); color: white; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                        <i class="ri-add-line"></i> Add Vote
                    </button>
                </div>
                <table id="recentEntriesTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Voter Reg. No</th>
                            <th>Voter Name</th>
                            <th>Dept</th>
                            <th>Voted For (Photo ID)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_sql = "SELECT * FROM photography ORDER BY id DESC LIMIT 10";
                        $recent_res = mysqli_query($conn, $recent_sql);
                        if ($recent_res && mysqli_num_rows($recent_res) > 0) {
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($recent_res)) {
                                ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['regno']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['dept']) ?></td>
                                    <td><span class="status-badge status-active">#<?= htmlspecialchars($row['vote']) ?></span>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/script/script.js"></script>
    <script>
        $(document).ready(function () {
            $('#recentEntriesTable').DataTable({
                responsive: true,
                paging: false,
                info: false,
                searching: false
            });

            // Add Vote Button Logic
            $('#btnAddVote').click(function () {
                Swal.fire({
                    title: 'Add New Vote',
                    html: `
                        <style>
                            .swal2-input, .swal2-select { margin: 5px 0 15px 0 !important; width: 100% !important; font-size: 0.95rem !important; }
                            .form-label { display: block; text-align: left; font-size: 0.85rem; color: #666; font-weight: 600; margin-bottom: 2px; }
                        </style>
                        <div>
                            <label class="form-label">Name</label>
                            <input id="swal-name" class="swal2-input" placeholder="Enter Full Name">

                            <label class="form-label">Year</label>
                            <select id="swal-year" class="swal2-select">
                                <option value="" disabled selected>Select Year</option>
                                <option value="I year">I Year</option>
                                <option value="II year">II Year</option>
                                <option value="III year">III Year</option>
                                <option value="IV year">IV Year</option>
                            </select>
                            
                            <label class="form-label">Department</label>
                            <select id="swal-dept" class="swal2-select">
                                <option value="" disabled selected>Select Department</option>
                                <option value="AIDS">Artificial Intelligence and Data Science</option>
                                <option value="AIML">Artificial Intelligence and Machine Learning</option>
                                <option value="CIVIL">Civil Engineering</option>
                                <option value="CSE">Computer Science Engineering</option>
                                <option value="CSBS">Computer Science And Business Systems</option>
                                <option value="ECE">Electronics & Communication Engineering</option>
                                <option value="EEE">Electrical & Electronics Engineering</option>
                                <option value="VLSI">Electronics Engineering (VLSI Design)</option>
                                <option value="IT">Information Technology</option>
                                <option value="MECH">Mechanical Engineering</option>
                                <option value="MCA">Master Of Computer Applications</option>
                                <option value="MBA">Master of Business Administration</option>
                            </select>

                            <label class="form-label">Registration Number</label>
                            <input id="swal-regno" class="swal2-input" placeholder="Select Year & Dept first" maxlength="12">

                            <label class="form-label">Vote (Photo ID)</label>
                            <input type="number" id="swal-vote" class="swal2-input" placeholder="Enter Photo ID">
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Submit Vote',
                    confirmButtonColor: '#134e4a',
                    didOpen: () => {
                        const yearSelect = document.getElementById('swal-year');
                        const deptSelect = document.getElementById('swal-dept');
                        const regInput = document.getElementById('swal-regno');

                        const yearCodes = {
                            'I year': '927625',
                            'II year': '927624',
                            'III year': '927623',
                            'IV year': '927622'
                        };

                        const deptCodes = {
                            'AIDS': 'BAD',
                            'AIML': 'BAM',
                            'CSE': 'BCS',
                            'CSBS': 'BCB',
                            'CYBER': 'BSC',
                            'ECE': 'BEC',
                            'EEE': 'BEE',
                            'MECH': 'BME',
                            'CIVIL': 'BCE',
                            'IT': 'BIT',
                            'VLSI': 'BEV',
                            'MBA': 'MBA',
                            'MCA': 'MCA'
                        };

                        let currentFixedPrefix = '';

                        function updateRegPrefix() {
                            const year = yearSelect.value;
                            const dept = deptSelect.value;

                            let prefix = '';

                            if (year && dept && yearCodes[year]) {
                                const yCode = yearCodes[year];
                                let dCode = deptCodes[dept] || '';

                                // AIML IV Year Exception
                                if (dept === 'AIML' && year === 'IV year') {
                                    dCode = 'BAL';
                                }

                                if (dCode) {
                                    prefix = yCode + dCode;
                                }
                            }

                            if (prefix) {
                                if (currentFixedPrefix !== prefix) {
                                    regInput.value = prefix;
                                } else if (!regInput.value.startsWith(prefix)) {
                                    regInput.value = prefix;
                                }
                                currentFixedPrefix = prefix;
                            } else {
                                currentFixedPrefix = '';
                            }
                        }

                        yearSelect.addEventListener('change', updateRegPrefix);
                        deptSelect.addEventListener('change', updateRegPrefix);

                        // Prevent deleting prefix
                        regInput.addEventListener('keydown', function (e) {
                            if (currentFixedPrefix && this.selectionStart <= currentFixedPrefix.length && e.key === 'Backspace') {
                                e.preventDefault();
                            }
                        });

                        // Enforce prefix checks on input
                        regInput.addEventListener('input', function () {
                            if (currentFixedPrefix && !this.value.startsWith(currentFixedPrefix)) {
                                this.value = currentFixedPrefix;
                            }
                        });
                    },
                    preConfirm: () => {
                        const regno = document.getElementById('swal-regno').value;
                        const name = document.getElementById('swal-name').value;
                        const dept = document.getElementById('swal-dept').value;
                        const year = document.getElementById('swal-year').value;
                        const vote = document.getElementById('swal-vote').value;

                        if (!regno || !name || !dept || !year || !vote) {
                            Swal.showValidationMessage('Please fill all fields');
                            return false;
                        }

                        if (regno.length !== 12) {
                            Swal.showValidationMessage('Registration Number must be exactly 12 characters');
                            return false;
                        }

                        return { regno: regno, name: name, dept: dept, year: year, vote: vote };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'backend.php',
                            data: {
                                submit_vote: true,
                                regno: result.value.regno.toUpperCase(),
                                name: result.value.name.toUpperCase(),
                                dept: result.value.dept.toUpperCase(),
                                year: result.value.year,
                                vote: result.value.vote
                            },
                            dataType: 'json',
                            xhrFields: { withCredentials: true },
                            success: function (response) {
                                if (response.status == 200) {
                                    Swal.fire('Success!', response.message, 'success')
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire('Error!', response.message, 'error');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(xhr.responseText);
                                Swal.fire('Error!', 'Something went wrong. Check console.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>