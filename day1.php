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
  <title>Day 1 Events - Orlia'26</title>
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

  <section class="day-hero day1-hero">
    <div class="glitch-wrapper">
      <h1 class="glitch-text" data-text="DAY 01">DAY 01</h1>
    </div>
    <p class="section-subtitle">
      March 13, 2026 - Cultural Events
    </p>
  </section>

  <section class="day1-events">
    <div class="events-container">
      <?php
      $day = 'day1';
      $query = "SELECT * FROM events WHERE day='$day' ORDER BY id ASC";
      $result = mysqli_query($conn, $query);

      $images = [
        'Tamilspeech' => 'assets/images/events/tamilspeech.jpg',
        'Englishspeech' => 'assets/images/events/engspeech.jpg',
        'Singing' => 'assets/images/events/sing.jpg',
        'Solodance' => 'assets/images/events/solo.jpg',
        'Drawing' => 'assets/images/events/draw.jpg',
        'Trailertime' => 'assets/images/events/trailertime.jpg',
        'Firelesscooking' => 'assets/images/events/firelesscook.jpg',
        'Dumpcharades' => 'assets/images/events/dumb.jpeg',
        'Iplauction' => 'assets/images/events/ipl.jpg',
        'Mehandi' => 'assets/images/events/mehandi.jpg',
        'Lyricalhunt' => 'assets/images/events/lyrical.jpg',
        'Divideconquer' => 'assets/images/events/divideconquer.jpg',
        'Memecreation' => 'assets/images/events/meme.jpg',
        'Groupdance' => 'assets/images/events/group.jpg'
      ];

      if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
          $key = $row['event_key'];
          $image = isset($images[$key]) ? $images[$key] : 'assets/images/agastya.png';
          $isClosed = $row['status'] == 0;
          
          // Determine registration link based on event type or category
          // Logic: Default to register.php. If Group event, use teamRegister.php
          // Using 'event_type' from DB which can be 'Solo', 'Group', 'Both'.
          // If 'Both' or 'Group', assuming teamRegister for safety if not specified purely Solo.
          // However, checking previous hardcoded links:
          // Tamilspeech (Solo) -> register.php
          // Trailertime (Group) -> teamRegister.php
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
                <button class="register-btn" style="background: var(--fest-orange); border: none; margin-top: 10px; width: 100%; cursor: pointer;" onclick="toggleModal('modal_<?= $key ?>')">View Topics</button>
              <?php endif; ?>
            </div>
            <div class="event-header">
              <div class="event-header-row">
                <div class="event-time">
                  <i class="ri-time-line"></i>
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
                  // Split by newline or bullet point if manually entered
                  // We'll normalize newlines and explode
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

  <!-- Add modals before closing body tag -->
  <!-- Modals used to be here but rules are now inline -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="assets/script/script.js"></script>
  <script src="assets/script/assistant.js"></script>
</body>

</html>