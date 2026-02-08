<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

include "db.php";
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate database connection before processing any request
if (!$conn) {
    echo json_encode(['status' => 500, 'message' => 'Database connection failed. Please try again later.']);
    exit;
}

// 1. Fetch All Events
if (isset($_POST['get_all_events'])) {
    $query = "SELECT * FROM events WHERE status = 1"; // Only active events
    $result = mysqli_query($conn, $query);

    $events = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Simplify structure for AI consumption
            $events[$row['event_key']] = [
                'name' => $row['event_name'],
                'key' => $row['event_key'],
                'day' => $row['day'],
                'time' => $row['event_time'],
                'venue' => $row['event_venue'],
                'type' => $row['event_type'],
                'category' => $row['event_category'],
                'rules' => $row['event_rules'], // Assuming JSON or string
                'description' => $row['event_description']
            ];
        }
    }
    echo json_encode(['status' => 200, 'data' => $events]);
    exit;
}

// 2. Fetch Specific Event Details
if (isset($_POST['get_event_details'])) {
    $eventKey = mysqli_real_escape_string($conn, $_POST['event_key']);
    $query = "SELECT * FROM events WHERE event_key = '$eventKey'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 200, 'data' => $row]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'Event not found']);
    }
    exit;
}

// 3. Register Solo Event - REMOVED

// 4. Register Group Event - REMOVED

// 5. Fetch Pass Details by Code
if (isset($_POST['get_pass_by_code'])) {
    $passCode = mysqli_real_escape_string($conn, $_POST['pass_code']);

    // Check Solo
    $soloQ = "SELECT * FROM soloevents WHERE event_pass='$passCode'";
    $soloRes = mysqli_query($conn, $soloQ);

    if (mysqli_num_rows($soloRes) > 0) {
        $row = mysqli_fetch_assoc($soloRes);
        // Get Event Details
        $eQ = "SELECT event_name, event_venue, event_time, day FROM events WHERE event_key='" . $row['events'] . "'";
        $eR = mysqli_fetch_assoc(mysqli_query($conn, $eQ));

        if ($eR) {
            $data = [
                'type' => 'Solo',
                'pass_code' => $row['event_pass'],
                'name' => $row['name'],
                'roll' => $row['regno'],
                'event' => $eR['event_name'],
                'venue' => $eR['event_venue'],
                'time' => $eR['event_time'],
                'day' => ucfirst($eR['day'])
            ];
            echo json_encode(['status' => 200, 'data' => $data]);
            exit;
        }
    }

    // Check Group
    $groupQ = "SELECT * FROM groupevents WHERE event_pass='$passCode'";
    $groupRes = mysqli_query($conn, $groupQ);

    if (mysqli_num_rows($groupRes) > 0) {
        $row = mysqli_fetch_assoc($groupRes);
        $eQ = "SELECT event_name, event_venue, event_time, day FROM events WHERE event_key='" . $row['events'] . "'";
        $eR = mysqli_fetch_assoc(mysqli_query($conn, $eQ));

        if ($eR) {
            $data = [
                'type' => 'Group',
                'pass_code' => $row['event_pass'],
                'name' => $row['teamleadname'], // Lead Name
                'team' => $row['teamname'],
                'members' => json_decode($row['tmembername']),
                'event' => $eR['event_name'],
                'venue' => $eR['event_venue'],
                'time' => $eR['event_time'],
                'day' => ucfirst($eR['day'])
            ];
            echo json_encode(['status' => 200, 'data' => $data]);
            exit;
        }
    }

    echo json_encode(['status' => 404, 'message' => 'Invalid Event Pass Code']);
    exit;
}
// 6. Fetch Passes by Register Number
if (isset($_POST['get_passes_by_regno'])) {
    $regno = mysqli_real_escape_string($conn, $_POST['regno']);
    $passes = [];

    // 1. Solo Events
    $soloQ = "SELECT * FROM soloevents WHERE regno='$regno'";
    $soloRes = mysqli_query($conn, $soloQ);
    if ($soloRes) {
        while ($row = mysqli_fetch_assoc($soloRes)) {
            $eQ = "SELECT event_name, event_venue, event_time, day FROM events WHERE event_key='" . $row['events'] . "'";
            $eR = mysqli_fetch_assoc(mysqli_query($conn, $eQ));

            if ($eR) {
                $passes[] = [
                    'type' => 'Solo',
                    'pass_code' => $row['event_pass'],
                    'name' => $row['name'], // Participant Name
                    'event' => $eR['event_name'],
                    'venue' => $eR['event_venue'],
                    'time' => $eR['event_time'],
                    'day' => ucfirst($eR['day'])
                ];
            }
        }
    }

    // 2. Group Events (Leader)
    $groupLQ = "SELECT * FROM groupevents WHERE tregno='$regno'";
    $groupLRes = mysqli_query($conn, $groupLQ);
    if ($groupLRes) {
        while ($row = mysqli_fetch_assoc($groupLRes)) {
            $eQ = "SELECT event_name, event_venue, event_time, day FROM events WHERE event_key='" . $row['events'] . "'";
            $eR = mysqli_fetch_assoc(mysqli_query($conn, $eQ));

            if ($eR) {
                $passes[] = [
                    'type' => 'Group Leader',
                    'pass_code' => $row['event_pass'],
                    'name' => $row['teamleadname'],
                    'team' => $row['teamname'],
                    'event' => $eR['event_name'],
                    'venue' => $eR['event_venue'],
                    'time' => $eR['event_time'],
                    'day' => ucfirst($eR['day'])
                ];
            }
        }
    }

    // 3. Group Events (Member) - Search in JSON string
    // Note: This is a simple LIKE check, ideally use JSON functions if available/reliable, but LIKE matching strict JSON structure is okay for this context
    $searchStr = '"roll":"' . $regno . '"';
    $groupMQ = "SELECT * FROM groupevents WHERE tmembername LIKE '%$searchStr%'";
    $groupMRes = mysqli_query($conn, $groupMQ);
    if ($groupMRes) {
        while ($row = mysqli_fetch_assoc($groupMRes)) {
            // Avoid duplicates if user is somehow both (data integrity issue usually, but good to check)
            $isLeader = ($row['tregno'] == $regno);
            if (!$isLeader) {
                $eQ = "SELECT event_name, event_venue, event_time, day FROM events WHERE event_key='" . $row['events'] . "'";
                $eR = mysqli_fetch_assoc(mysqli_query($conn, $eQ));

                if ($eR) {
                    $passes[] = [
                        'type' => 'Group Member',
                        'pass_code' => $row['event_pass'],
                        'name' => $row['teamleadname'], // Pass usually shows Leader/Team
                        'team' => $row['teamname'],
                        'event' => $eR['event_name'],
                        'venue' => $eR['event_venue'],
                        'time' => $eR['event_time'],
                        'day' => ucfirst($eR['day'])
                    ];
                }
            }
        }
    }

    if (!empty($passes)) {
        echo json_encode(['status' => 200, 'data' => $passes]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'No passes found for this Register Number']);
    }
    exit;
}
?>