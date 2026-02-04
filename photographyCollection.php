<?php
include 'includes/auth.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
$eventKey = $_SESSION['userid'];

// Access Check: Super Admin OR Photography Admin
if ($role == '2') {
    // Super Admin Allowed
} elseif ($role == '1' && strtolower($eventKey) === 'photography') {
    // Photography Admin Allowed
} else {
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
    <title>Photography Collection - Orlia'26</title>
    <link rel="icon" href="assets/images/agastya.png" type="image/png">
    <link rel="stylesheet" href="assets/styles/styles.css">
    <link rel="stylesheet" href="assets/styles/admin.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }

        .photo-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .photo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .photo-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f5f5f5;
            position: relative;
        }

        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .photo-card:hover .photo-wrapper img {
            transform: scale(1.05);
        }

        .card-details {
            padding: 15px;
        }

        .card-number {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }

        .participant-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 5px;
        }

        .participant-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: var(--text-muted);
            grid-column: 1 / -1;
        }

        .download-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 3;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            background: var(--fest-blue);
            color: white;
            transform: scale(1.1);
        }

        .swal-photo-view {
            max-height: 70vh !important;
            max-width: 90vw !important;
            object-fit: contain;
            margin: 10px auto;
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
        $page = 'photo_collection';
        include 'includes/sidebar.php';
        ?>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <i class="ri-menu-line menu-toggle" id="sidebarToggle"
                        style="display:none; margin-right: 15px;"></i>
                    <div>
                        <span class="section-subtitle">Photography</span>
                        <h1 class="admin-title">Photo Collection</h1>
                    </div>
                </div>

                <div class="header-right">
                    <div class="user-profile">
                        <div class="user-avatar">
                            <i class="ri-user-3-line"></i>
                        </div>
                        <div class="user-dropdown">
                            <div class="dropdown-header">
                                <h4>Admin</h4>
                            </div>
                            <ul class="dropdown-menu">
                                <li><a href="index.php" class="text-danger"><i class="ri-logout-box-line"></i>
                                        Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <?php
            include 'db.php';
            // Fetch participants who registered for photography (key='photography') and have a photo
            // Assuming table is soloevents
            $sql = "SELECT * FROM soloevents WHERE events='photography' AND photo IS NOT NULL AND photo != '' ORDER BY id ASC";
            $result = mysqli_query($conn, $sql);
            ?>

            <div class="photo-grid">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $photoPath = htmlspecialchars($row['photo']);
                        $name = htmlspecialchars($row['name']);
                        $nameJs = addslashes($row['name']); // Escape for JS string
                        $regno = htmlspecialchars($row['regno']);
                        $dept = htmlspecialchars($row['dept']);
                        $ext = pathinfo($row['photo'], PATHINFO_EXTENSION);
                        ?>
                        <div class="photo-card">
                            <div class="card-number">#<?= $i++ ?></div>
                            <?php if ($role == '2'): ?>
                                <a href="<?= $photoPath ?>" download="Entry_<?= $regno ?>.<?= $ext ?>" class="download-btn"
                                    title="Download" onclick="event.stopPropagation();">
                                    <i class="ri-download-2-line"></i>
                                </a>
                            <?php endif; ?>
                            <div class="photo-wrapper" onclick="viewPhoto('<?= $photoPath ?>', 'Entry by <?= $nameJs ?>')"
                                style="cursor: pointer;">
                                <img src="<?= $photoPath ?>" alt="Entry by <?= $name ?>" loading="lazy">
                            </div>
                            <div class="card-details">
                                <h3 class="participant-name"><?= $name ?></h3>
                                <div class="participant-meta">
                                    <span><?= $regno ?></span>
                                    <span><?= $dept ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="empty-state">
                        <i class="ri-image-line" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                        <p>No photos uploaded yet.</p>
                    </div>
                    <?php
                }
                ?>
            </div>

        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/script/script.js"></script>
    <script>
        function viewPhoto(url, caption) {
            Swal.fire({
                imageUrl: url,
                imageAlt: caption,
                title: caption,
                width: 'auto',
                padding: '1em',
                background: '#fff',
                showConfirmButton: false,
                showCloseButton: true,
                backdrop: `rgba(0,0,0,0.8)`,
                customClass: {
                    image: 'swal-photo-view'
                }
            });
        }
    </script>
</body>

</html>