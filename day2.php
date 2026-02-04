<?php
include 'db.php';
// Function to check if event is closed is no longer needed as we fetch per row, 
// but we keep the structure clean.
?>
<?php
include 'includes/auth.php';
checkUserAccess(true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Day 2 Events - Orlia'26</title>
  <link rel="icon" href="assets/images/agastya.png" type="image/png">
  <link rel="stylesheet" href="assets/styles/styles.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">


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

  <!-- Animated Background Particles -->
  <div class="particles-container">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>
  <div class="theme-switch-wrapper">
    <!-- <div class="theme-switch" id="theme-toggle" title="Toggle Theme">
      <i class="ri-moon-line"></i>
    </div> -->
  </div>
  <a href="index.php" class="back-btn">
    <i class="ri-arrow-left-line"></i>
    Back to Home
  </a>

  <section class="day-hero day2-hero">
    <div class="glitch-wrapper">
      <h1 class="glitch-text" data-text="DAY 02">DAY 02</h1>
    </div>
    <p class="section-subtitle">
      March 14, 2026 - Cultural Events
    </p>
  </section>

  <section class="day2-events">
    <div class="events-container">
      <?php
      $day = 'day2';
      $query = "SELECT * FROM events WHERE day='$day' ORDER BY id ASC";
      $result = mysqli_query($conn, $query);

      $images = [
        'Rangoli' => 'assets/images/events/rangoli.jpg',
        'Photography' => 'assets/images/events/photo.jpeg',
        'Instrumentalplaying' => 'assets/images/events/instrument.jpg',
        'Treasurehunt' => 'assets/images/events/treasure.jpg',
        'Shortflim' => 'assets/images/events/shortflim.jpg',
        'Mime' => 'assets/images/events/mime.jpg',
        'Bestmanager' => 'assets/images/events/managerever.jpg',
        'Artfromwaste' => 'assets/images/events/art.jpg',
        'Sherlockholmes' => 'assets/images/events/sherlhome.jpg',
        'Freefire' => 'assets/images/events/freefire.jpg',
        'Rjvj' => 'assets/images/events/rjvj.jpg',
        'Vegetablefruitart' => 'assets/images/events/fruits.jpg',
        'Twindance' => 'assets/images/events/twindnce.jpg'
      ];

      if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
          $key = $row['event_key'];
          $image = isset($images[$key]) ? $images[$key] : 'assets/images/agastya.png';
          $isClosed = $row['status'] == 0;
          $registerPage = ($row['event_type'] === 'Group' || $row['event_category'] === 'Group') ? 'teamRegister.php' : 'register.php';
          ?>
          <div class="event-card">
            <div class="event-image">
              <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['event_name']) ?>" />
              <?php if ($isClosed): ?>
                <button class="register-btn disabled" disabled style="background: #555; cursor: not-allowed;">Closed</button>
              <?php else: ?>
                <a href="<?= $registerPage ?>?day=<?= $day ?>&event=<?= $key ?>" class="register-btn">Register Now</a>
              <?php endif; ?>

              <?php if (!empty($row['event_topics'])): ?>
                <button class="register-btn"
                  style="background: var(--fest-orange); border: none; margin-top: 10px; width: 100%; cursor: pointer;"
                  onclick="toggleModal('modal_<?= $key ?>')">View Topics</button>
              <?php endif; ?>
            </div>
            <div class="event-header">
              <div class="event-header-row">
                <div class="event-time">
                  <i class="ri-time-line"></i>
                  <span><?= htmlspecialchars($row['event_time']) ?></span>
                </div>
                <div class="event-venue">
                  <i class="ri-map-pin-line"></i>
                  <span><?= htmlspecialchars($row['event_venue']) ?></span>
                </div>
              </div>
            </div>
            <div class="event-content">
              <h3><?= htmlspecialchars($row['event_name']) ?></h3>
              <p><?= htmlspecialchars($row['event_description']) ?></p>
              <ul class="rules-list">
                <?php
                if (!empty($row['event_rules'])) {
                  $rulesText = str_replace("•", "\n", $row['event_rules']);
                  $rules = explode("\n", $rulesText);
                  foreach ($rules as $rule) {
                    $rule = trim($rule);
                    if (!empty($rule)) {
                      echo '<li><i class="ri-checkbox-circle-line"></i>' . htmlspecialchars($rule) . '</li>';
                    }
                  }
                }
                ?>
              </ul>
            </div>
          </div>

          <?php if (!empty($row['event_topics'])): ?>
            <!-- Topics Modal for <?= $row['event_name'] ?> -->
            <div id="modal_<?= $key ?>" class="modal">
              <div class="modal-content">
                <span class="close-modal" onclick="toggleModal('modal_<?= $key ?>')">&times;</span>
                <h3 class="modal-title"><?= htmlspecialchars($row['event_name']) ?> Topics</h3>
                <ul class="modal-rules">
                  <?php
                  $topicsText = str_replace("•", "\n", $row['event_topics']);
                  $topics = explode("\n", $topicsText);
                  foreach ($topics as $topic) {
                    $topic = trim($topic);
                    if (!empty($topic)) {
                      echo '<li><i class="ri-checkbox-circle-line"></i>' . htmlspecialchars($topic) . '</li>';
                    }
                  }
                  ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>
          <?php
        }
      }
      ?>
    </div>
  </section>
  <footer class="single-line-footer">
    <p>&copy; 2026 TECHNOLOGY INNOVATION HUB. All Rights Reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
<script src="assets/script/script.js"></script>
<script src="assets/script/assistant.js"></script>
</body>

</html>