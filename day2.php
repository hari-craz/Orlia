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
  <a href="day1.php" class="view-day-btn">
    View Day 1 <i class="ri-arrow-right-line"></i>
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
          // Prioritize dynamic image from DB
          if (!empty($row['event_image'])) {
            $image = $row['event_image'];
          } else {
            $image = isset($images[$key]) ? $images[$key] : 'assets/images/agastya.png';
          }
          $isClosed = $row['status'] == 0;
          $registerPage = ($row['event_type'] === 'Group') ? 'teamRegister.php' : 'register.php';
          ?>
          <div class="event-card">
            <div class="event-image">
              <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['event_name']) ?>" />
              <?php if ($isClosed): ?>
                <button class="register-btn disabled" disabled style="background: #555; cursor: not-allowed;">Closed</button>
              <?php else: ?>
                <a href="<?= $registerPage ?>?day=<?= $day ?>&event=<?= $key ?>" class="register-btn">Register Now</a>
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
              <?php
              $perfType = ($row['event_type'] === 'Group') ? 'Group Performance' : 'Solo Performance';

              // Helper function for compatibility (defined once or available in scope)
              if (!function_exists('getCleanList')) {
                function getCleanList($raw)
                {
                  if (empty($raw))
                    return [];
                  $decoded = json_decode($raw, true);
                  if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                  }
                  return explode("\n", str_replace("•", "\n", $raw));
                }
              }
              $rulesList = getCleanList($row['event_rules']);
              $topicsList = getCleanList($row['event_topics']);

              // Filter out empty lines
              $topicsList = array_filter($topicsList, function ($value) {
                return !is_null($value) && trim($value) !== '';
              });
              $rulesList = array_filter($rulesList, function ($value) {
                return !is_null($value) && trim($value) !== '';
              });
              ?>
              <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                <h3 style="margin-bottom: 0; flex: 1;"><?= htmlspecialchars($row['event_name']) ?></h3>
                <div style="display: flex; gap: 8px; flex-shrink: 0;">
                  <?php if (!empty($topicsList)): ?>
                    <button onclick="toggleModal('modal_<?= $key ?>')"
                      style="background: transparent; border: 1px solid var(--fest-orange); color: var(--fest-orange); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 4px;"
                      onmouseover="this.style.background='var(--fest-orange)'; this.style.color='white'"
                      onmouseout="this.style.background='transparent'; this.style.color='var(--fest-orange)'">
                      Topics <i class="ri-lightbulb-line"></i>
                    </button>
                  <?php endif; ?>
                  <?php if (!empty($rulesList)): ?>
                    <button onclick="toggleModal('rules_modal_<?= $key ?>')"
                      style="background: transparent; border: 1px solid var(--google-blue); color: var(--google-blue); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 4px;"
                      onmouseover="this.style.background='var(--google-blue)'; this.style.color='white'"
                      onmouseout="this.style.background='transparent'; this.style.color='var(--google-blue)'">
                      Rules <i class="ri-information-line"></i>
                    </button>
                  <?php endif; ?>
                </div>
              </div>
              <p
                style="width: 100%; font-style: italic; color: #666; font-weight: 500; font-size: 0.95rem; margin-top: 5px;">
                <?= $perfType ?>
              </p>
            </div>
            <div class="rules-topics-container"></div>
          </div>

          <?php if (!empty($topicsList) && !empty($topicsList[0])): ?>
            <!-- Topics Modal -->
            <div id="modal_<?= $key ?>" class="modal">
              <div class="modal-content">
                <span class="close-modal" onclick="toggleModal('modal_<?= $key ?>')">&times;</span>
                <h3 class="modal-title"><?= htmlspecialchars($row['event_name']) ?> Topics</h3>
                <ul class="modal-rules">
                  <?php foreach ($topicsList as $topic):
                    if (trim($topic) == '')
                      continue;
                    ?>
                    <li><i class="ri-checkbox-circle-line"></i><?= htmlspecialchars(trim($topic)) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($rulesList)): ?>
            <!-- Rules Modal -->
            <div id="rules_modal_<?= $key ?>" class="modal">
              <div class="modal-content">
                <span class="close-modal" onclick="toggleModal('rules_modal_<?= $key ?>')">&times;</span>
                <h3 class="modal-title"><?= htmlspecialchars($row['event_name']) ?> Rules</h3>
                <ul class="modal-rules">
                  <?php foreach ($rulesList as $rule):
                    if (trim($rule) == '')
                      continue;
                    ?>
                    <li><i class="ri-checkbox-circle-line"></i><?= htmlspecialchars(trim($rule)) ?></li>
                  <?php endforeach; ?>
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