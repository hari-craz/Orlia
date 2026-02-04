<?php
// Start output buffering to catch any stray output
ob_start();

// Error handling - suppress warnings that could break JSON
error_reporting(0);
ini_set('display_errors', 0);

// Configure session for HTTPS/Cloudflare BEFORE starting
$isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
           (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
           (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on');

if (session_status() === PHP_SESSION_NONE) {
    // Session cookie settings for Cloudflare proxy
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => $isHttps ? 'None' : 'Lax'
    ]);
    session_start();
}

// Clear any buffered output before setting headers
ob_clean();

// Set headers for API responses
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include "db.php";

// Check if db.php failed
if (!isset($conn) || !$conn) {
    ob_end_clean();
    echo json_encode(['status' => 500, 'message' => 'Database connection failed']);
    exit;
}

if (isset($_POST['Add_newuser'])) {
    try {
        // Validate Roll Number Length
        if (strlen($_POST['rollNumber']) !== 12) {
            echo json_encode(['status' => 400, 'message' => 'Roll Number must be exactly 12 characters.']);
            exit;
        }

        $name = mysqli_real_escape_string($conn, $_POST['fullName']);
        $rollno = mysqli_real_escape_string($conn, $_POST['rollNumber']);
        $year = mysqli_real_escape_string($conn, $_POST['year']);
        $mail = mysqli_real_escape_string($conn, $_POST['mailid']);
        $phoneno = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
        $dept = mysqli_real_escape_string($conn, $_POST['department']);
        $day = mysqli_real_escape_string($conn, $_POST['daySelection']);
        $events = mysqli_real_escape_string($conn, $_POST['events']);

        // Check Event Status
        $statusQuery = "SELECT status FROM events WHERE event_key='$events'";
        $statusResult = mysqli_query($conn, $statusQuery);
        if ($statusResult && mysqli_num_rows($statusResult) > 0) {
            $statusRow = mysqli_fetch_assoc($statusResult);
            if ($statusRow['status'] == 0) {
                echo json_encode(['status' => 403, 'message' => 'Registration for this event is closed.']);
                exit;
            }
        }

        $video = '';
        if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['video']['tmp_name'];
            $fileName = $_FILES['video']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $allowedfileExtensions = array('mp4');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = 'uploads/videos/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }
                $safeRollNo = preg_replace('/[^a-zA-Z0-9]/', '', $rollno);
                $newFileName = $safeRollNo . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $video = $dest_path;
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Error moving the uploaded video.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 400, 'message' => 'Invalid file format. Only MP4 allowed.']);
                exit;
            }
        }

        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $allowedfileExtensions = array('jpg', 'jpeg', 'png');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $uploadFileDir = 'uploads/photos/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }
                $safeRollNo = preg_replace('/[^a-zA-Z0-9]/', '', $rollno);
                $newFileName = $safeRollNo . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $photo = $dest_path;
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Error moving the uploaded photo.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 400, 'message' => 'Invalid file format. Only JPG/PNG allowed.']);
                exit;
            }
        }

        // Generate Event Pass
        $eventChar = 'A';
        $eventChar = 'A';
        // Fetch full event details for Pass
        $eventIdQuery = "SELECT id, event_name, event_venue, event_time FROM events WHERE event_key='$events' AND day='$day' LIMIT 1";
        $eventIdResult = mysqli_query($conn, $eventIdQuery);

        $eventName = '';
        $eventVenue = 'MKCE Campus';
        $eventTime = '9:00 AM';

        if ($eventIdResult && mysqli_num_rows($eventIdResult) > 0) {
            $eventRow = mysqli_fetch_assoc($eventIdResult);
            // Generate Alphabet from ID (1=A, 2=B, etc.)
            $eventChar = chr(64 + $eventRow['id']);
            $eventName = $eventRow['event_name'];
            $eventVenue = $eventRow['event_venue'] ?: 'MKCE Campus';
            $eventTime = $eventRow['event_time'] ?: '9:00 AM';
        }

        $dayCode = ($day == 'day1') ? '01' : '02';
        $typeCode = 'S'; // Solo

        // Get Count for Sequence
        $countQuery = "SELECT COUNT(*) as count FROM soloevents WHERE events='$events' AND day='$day'";
        $countResult = mysqli_query($conn, $countQuery);
        $count = 0;
        if ($countResult) {
            $row = mysqli_fetch_assoc($countResult);
            $count = $row['count'];
        }
        $count++;
        $countStr = str_pad($count, 4, '0', STR_PAD_LEFT);

        $eventPass = "ORA" . $dayCode . $eventChar . $typeCode . $countStr;

        $query = "INSERT INTO soloevents (name, regno, year, phoneno, dept, day, events, mail, video, photo, event_pass) VALUES ('$name', '$rollno', '$year', '$phoneno', '$dept', '$day', '$events','$mail', '$video', '$photo', '$eventPass')";
        if (mysqli_query($conn, $query)) {

            // --- GLOBAL PASS CALCULATION START ---
            $eventKeys = [];

            // Solo
            $sQ = "SELECT events FROM soloevents WHERE regno='$rollno'";
            $sR = mysqli_query($conn, $sQ);
            if ($sR) {
                while ($r = mysqli_fetch_assoc($sR)) {
                    $eventKeys[] = $r['events'];
                }
            }

            // Group Leader
            $gLQ = "SELECT events FROM groupevents WHERE tregno='$rollno'";
            $gLR = mysqli_query($conn, $gLQ);
            if ($gLR) {
                while ($r = mysqli_fetch_assoc($gLR)) {
                    $eventKeys[] = $r['events'];
                }
            }

            // Group Member
            $sStr = '"roll":"' . $rollno . '"';
            $gMQ = "SELECT events FROM groupevents WHERE tmembername LIKE '%$sStr%'";
            $gMR = mysqli_query($conn, $gMQ);
            if ($gMR) {
                while ($r = mysqli_fetch_assoc($gMR)) {
                    $eventKeys[] = $r['events'];
                }
            }

            $totalEvents = count($eventKeys);
            $eventNames = [];
            if ($totalEvents > 0) {
                $kStr = "'" . implode("','", $eventKeys) . "'";
                $nQ = "SELECT event_name FROM events WHERE event_key IN ($kStr)";
                $nR = mysqli_query($conn, $nQ);
                if ($nR) {
                    while ($r = mysqli_fetch_assoc($nR)) {
                        $eventNames[] = $r['event_name'];
                    }
                }
            }

            $passType = 'No Pass';
            if ($totalEvents == 1)
                $passType = 'Local Pass';
            elseif ($totalEvents == 2)
                $passType = 'Elite Pass';
            elseif ($totalEvents == 3)
                $passType = 'Gold Pass';
            elseif ($totalEvents >= 4)
                $passType = 'Platinum Pass';

            // --- GLOBAL PASS CALCULATION END ---

            ob_end_clean();
            $res = [
                'status' => 200,
                'message' => 'Event registered Successfully. Your Event Pass: ' . $eventPass,
                'event_pass' => $eventPass,
                'pass_details' => [
                    'eventName' => $eventName,
                    'participantName' => $name,
                    'rollNo' => $rollno,
                    'venue' => $eventVenue,
                    'time' => $eventTime,
                    'day' => ucfirst($day),
                    // Add Global Pass Info
                    'passType' => $passType,
                    'totalEvents' => $totalEvents,
                    'registeredEvents' => $eventNames
                ]
            ];
            echo json_encode($res);
            exit;
        } else {
            throw new Exception('Query Failed: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        ob_end_clean();
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
        exit;
    }
}

if (isset($_POST['groupnewuser'])) {

    // Validate Leader Roll Number Length
    if (strlen($_POST['rollNumber']) !== 12) {
        echo json_encode(['status' => 400, 'message' => 'Team Leader Roll Number must be exactly 12 characters.']);
        exit;
    }

    $teamname = mysqli_real_escape_string($conn, $_POST['TeamName']);
    $teamleadname = mysqli_real_escape_string($conn, $_POST['fullName']);
    $tregno = mysqli_real_escape_string($conn, $_POST['rollNumber']);
    $temail = mysqli_real_escape_string($conn, $_POST['mailid']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $phoneno = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $dept = mysqli_real_escape_string($conn, $_POST['department']);
    $day = mysqli_real_escape_string($conn, $_POST['daySelection']);
    $events = mysqli_real_escape_string($conn, $_POST['events']);

    $song = '';
    if (isset($_FILES['song']) && $_FILES['song']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['song']['tmp_name'];
        $fileName = $_FILES['song']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = array('mp3');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = 'uploads/songs/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }
            $safeRollNo = preg_replace('/[^a-zA-Z0-9]/', '', $tregno);
            $newFileName = $safeRollNo . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $song = $dest_path;
            } else {
                echo json_encode(['status' => 500, 'message' => 'Error moving the uploaded file.']);
                exit;
            }
        } else {
            echo json_encode(['status' => 400, 'message' => 'Invalid file format. Only MP3 allowed.']);
            exit;
        }
    }

    // Check Event Status
    $statusQuery = "SELECT status FROM events WHERE event_key='$events'";
    $statusResult = mysqli_query($conn, $statusQuery);
    if ($statusResult && mysqli_num_rows($statusResult) > 0) {
        $statusRow = mysqli_fetch_assoc($statusResult);
        if ($statusRow['status'] == 0) {
            echo json_encode(['status' => 403, 'message' => 'Registration for this event is closed.']);
            exit;
        }
    }

    // Check if Team Name already exists
    $checkTeamQuery = "SELECT * FROM groupevents WHERE teamname='$teamname'";
    $checkTeamResult = mysqli_query($conn, $checkTeamQuery);
    if (mysqli_num_rows($checkTeamResult) > 0) {
        echo json_encode(['status' => 409, 'message' => 'Team name already exists. Please choose a different name.']);
        exit;
    }

    // Collect team members in an array
    $teamMembers = [];
    $teamMembersCount = $_POST['teamMembersCount'] ?? 0;

    for ($i = 1; $i <= $teamMembersCount; $i++) {
        if (!empty($_POST["memberName$i"]) && !empty($_POST["memberRoll$i"])) {

            // Validate Team Member Roll Number
            if (strlen($_POST["memberRoll$i"]) !== 12) {
                echo json_encode(['status' => 400, 'message' => "Team Member $i Roll Number must be exactly 12 characters."]);
                exit;
            }

            $teamMembers[] = [
                'name' => $_POST["memberName$i"],
                'roll' => $_POST["memberRoll$i"],
                'phone' => $_POST["memberPhone$i"] ?? '',
                'dept' => $_POST["memberDept$i"] ?? '',  // Capture Department
                'year' => $_POST["memberYear$i"] ?? ''   // Capture Year
            ];
        }
    }

    // Convert team members array to JSON
    $tmember_json = json_encode($teamMembers, JSON_UNESCAPED_UNICODE);

    // Generate Event Pass (Team Leader)
    $eventChar = 'A';
    // Fetch full event details
    $eventIdQuery = "SELECT id, event_name, event_venue, event_time FROM events WHERE event_key='$events' AND day='$day' LIMIT 1";
    $eventIdResult = mysqli_query($conn, $eventIdQuery);

    $eventName = '';
    $eventVenue = 'MKCE Campus';
    $eventTime = '9:00 AM';

    if ($eventIdResult && mysqli_num_rows($eventIdResult) > 0) {
        $eventRow = mysqli_fetch_assoc($eventIdResult);
        $eventChar = chr(64 + $eventRow['id']);
        $eventName = $eventRow['event_name'];
        $eventVenue = $eventRow['event_venue'] ?: 'MKCE Campus';
        $eventTime = $eventRow['event_time'] ?: '9:00 AM';
    }

    $dayCode = ($day == 'day1') ? '01' : '02';
    $typeCode = 'G'; // Group

    // Get Count for Sequence
    $countQuery = "SELECT COUNT(*) as count FROM groupevents WHERE events='$events' AND day='$day'";
    $countResult = mysqli_query($conn, $countQuery);
    $count = 0;
    if ($countResult) {
        $row = mysqli_fetch_assoc($countResult);
        $count = $row['count'];
    }
    $count++;
    $countStr = str_pad($count, 4, '0', STR_PAD_LEFT);

    $eventPass = "ORA" . $dayCode . $eventChar . $typeCode . $countStr;

    $query = "INSERT INTO groupevents (teamname, teamleadname, tregno, temail, tmembername, year, phoneno, dept, day, events, song, event_pass) VALUES ('$teamname', '$teamleadname', '$tregno', '$temail', '$tmember_json', '$year', '$phoneno', '$dept', '$day', '$events', '$song', '$eventPass')";
    if (mysqli_query($conn, $query)) {

        // --- GLOBAL PASS CALCULATION START ---
        // Use Team Leader Roll No ($tregno)
        $eventKeys = [];

        // Solo
        $sQ = "SELECT events FROM soloevents WHERE regno='$tregno'";
        $sR = mysqli_query($conn, $sQ);
        if ($sR) {
            while ($r = mysqli_fetch_assoc($sR)) {
                $eventKeys[] = $r['events'];
            }
        }

        // Group Leader
        $gLQ = "SELECT events FROM groupevents WHERE tregno='$tregno'";
        $gLR = mysqli_query($conn, $gLQ);
        if ($gLR) {
            while ($r = mysqli_fetch_assoc($gLR)) {
                $eventKeys[] = $r['events'];
            }
        }

        // Group Member
        $sStr = '"roll":"' . $tregno . '"';
        $gMQ = "SELECT events FROM groupevents WHERE tmembername LIKE '%$sStr%'";
        $gMR = mysqli_query($conn, $gMQ);
        if ($gMR) {
            while ($r = mysqli_fetch_assoc($gMR)) {
                $eventKeys[] = $r['events'];
            }
        }

        $totalEvents = count($eventKeys);
        $eventNames = [];
        if ($totalEvents > 0) {
            $kStr = "'" . implode("','", $eventKeys) . "'";
            $nQ = "SELECT event_name FROM events WHERE event_key IN ($kStr)";
            $nR = mysqli_query($conn, $nQ);
            if ($nR) {
                while ($r = mysqli_fetch_assoc($nR)) {
                    $eventNames[] = $r['event_name'];
                }
            }
        }

        $passType = 'No Pass';
        if ($totalEvents == 1)
            $passType = 'Local Pass';
        elseif ($totalEvents == 2)
            $passType = 'Elite Pass';
        elseif ($totalEvents == 3)
            $passType = 'Gold Pass';
        elseif ($totalEvents >= 4)
            $passType = 'Platinum Pass';
        // --- GLOBAL PASS CALCULATION END ---

        // Extract Team Member Names from JSON for Display
        $memberNamesList = [];
        foreach ($teamMembers as $tm) {
            if (isset($tm['name'])) {
                $memberNamesList[] = $tm['name'];
            }
        }

        $res = [
            'status' => 200,
            'message' => 'Event registered Successfully. Team Leader Pass: ' . $eventPass,
            'event_pass' => $eventPass,
            'pass_details' => [
                'eventName' => $eventName,
                'participantName' => $teamleadname, // Send Leader Name specifically
                'teamName' => $teamname,           // Send Team Name specifically
                'isTeam' => true,                  // Flag for frontend
                'teamMembers' => $memberNamesList, // List of member names
                'rollNo' => $tregno,
                'venue' => $eventVenue,
                'time' => $eventTime,
                'day' => ucfirst($day),
                // Add Global Pass Info
                'passType' => $passType,
                'totalEvents' => $totalEvents,
                'registeredEvents' => $eventNames
            ]
        ];
        echo json_encode($res);
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . mysqli_error($conn)
        ];
        echo json_encode($res);
    }
}

if (isset($_POST['Addadmins'])) {
    try {

        $userid = mysqli_real_escape_string($conn, $_POST['userid']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);


        // ✅ Step 6: Insert Data into Database
        $query = "INSERT INTO users(userid, password, role ) VALUES ('$userid','$password','$role')";
        if (mysqli_query($conn, $query)) {
            ob_end_clean();
            $res = [
                'status' => 200,
                'message' => 'User Added Successfully'
            ];
            echo json_encode($res);
            exit;
        } else {
            throw new Exception('Query Failed: ' . mysqli_error($conn));
        }
    } catch (Exception $e) {
        ob_end_clean();
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
        exit;
    }
}

if (isset($_POST['delete_user'])) {
    $id = mysqli_real_escape_string($conn, $_POST['userid']);
    $query = "DELETE FROM users WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    ob_end_clean();
    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Details Deleted Successfully'
        ];
        echo json_encode($res);
        exit;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Details Not Deleted'
        ];
        echo json_encode($res);
        exit;
    }
}

if (isset($_POST['edit_user'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['user_id']);

    $query = "SELECT * FROM users WHERE id='$student_id'";
    $query_run = mysqli_query($conn, $query);

    $User_data = mysqli_fetch_array($query_run);

    ob_end_clean();
    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'details Fetch Successfully by id',
            'data' => $User_data
        ];
        echo json_encode($res);
        exit;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Details Not Deleted'
        ];
        echo json_encode($res);
        exit;
    }
}

if (isset($_POST['save_edituser'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['id']);
    $userid = mysqli_real_escape_string($conn, $_POST['userid']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);


    $query = "UPDATE users SET userid='$userid', password='$password' , role='$role' WHERE id='$student_id'";
    $query_run = mysqli_query($conn, $query);

    ob_end_clean();
    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'details Updated Successfully'
        ];
        echo json_encode($res);
        exit;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Details Not Deleted'
        ];
        echo json_encode($res);
        exit;
    }
}

if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE userid='$username' AND password='$password'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_array($query_run);

        // Session already started at top of file
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['login_time'] = time();

        // Clear buffer and output clean JSON
        ob_end_clean();
        echo json_encode([
            'status' => 200,
            'message' => 'Login Successful',
            'redirect' => $row['role'] == '1' ? 'eventAdmin.php' : ($row['role'] == '2' ? 'superAdmin.php' : 'adminDashboard.php'),
            'user' => [
                'userid' => $row['userid'],
                'role' => $row['role']
            ]
        ]);
        exit;
    } else {
        ob_end_clean();
        echo json_encode([
            'status' => 401,
            'message' => 'Invalid Username or Password'
        ]);
        exit;
    }
}

if (isset($_POST['update_event_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE events SET status='$status' WHERE id='$id'";
    ob_end_clean();
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 200, 'message' => 'Status updated']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Error updating status']);
    }
    exit;
}

if (isset($_POST['bulk_update_status'])) {
    $scope = mysqli_real_escape_string($conn, $_POST['scope']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "";
    if ($scope == 'all') {
        $query = "UPDATE events SET status='$status'";
    } elseif ($scope == 'solo') {
        $query = "UPDATE events SET status='$status' WHERE event_type='Solo'";
    } elseif ($scope == 'group') {
        $query = "UPDATE events SET status='$status' WHERE event_type='Group'";
    }

    ob_end_clean();
    if (!empty($query)) {
        if (mysqli_query($conn, $query)) {
            echo json_encode(['status' => 200, 'message' => 'Bulk status updated successfully']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Error updating bulk status: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Invalid scope']);
    }
    exit;
}

if (isset($_POST['check_team_name'])) {
    $teamName = mysqli_real_escape_string($conn, $_POST['teamName']);
    $query = "SELECT * FROM groupevents WHERE teamname='$teamName'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['status' => 409, 'message' => 'Team name unavailable']);
    } else {
        echo json_encode(['status' => 200, 'message' => 'Team name available']);
    }
}

if (isset($_POST['update_event_details'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);

    // New Fields
    $venue = isset($_POST['venue']) ? mysqli_real_escape_string($conn, $_POST['venue']) : '';
    $time = isset($_POST['time']) ? mysqli_real_escape_string($conn, $_POST['time']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $rules = isset($_POST['rules']) ? mysqli_real_escape_string($conn, $_POST['rules']) : '';
    $topics = isset($_POST['topics']) ? mysqli_real_escape_string($conn, $_POST['topics']) : '';

    $query = "UPDATE events SET event_name='$name', event_category='$category', event_type='$type', day='$day', event_venue='$venue', event_time='$time', event_description='$description', event_rules='$rules', event_topics='$topics' WHERE id='$id'";
    ob_end_clean();
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 200, 'message' => 'Event details updated successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Error updating event details: ' . mysqli_error($conn)]);
    }
    exit;
}
if (isset($_GET['get_events'])) {
    $day = mysqli_real_escape_string($conn, $_GET['day']);
    $type = mysqli_real_escape_string($conn, $_GET['type']);

    // Select events matching the specific type OR valid for 'Both'
    $query = "SELECT event_name, event_key FROM events WHERE day='$day' AND (event_type='$type' OR event_type='Both')";
    $query_run = mysqli_query($conn, $query);

    $events = [];
    if ($query_run && mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $events[] = [
                'text' => $row['event_name'],
                'value' => $row['event_key']
            ];
        }
    }
    ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($events);
    exit;
}

if (isset($_POST['reset_ids'])) {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $table = '';

    if ($type === 'solo') {
        $table = 'soloevents';
    } elseif ($type === 'group') {
        $table = 'groupevents';
    } elseif ($type === 'admins') {
        $table = 'users'; // Admins are in users table
    } elseif ($type === 'events') {
        $table = 'events';
    }

    ob_end_clean();
    if (!empty($table)) {
        // Reset IDs logic
        $q1 = "SET @num := 0;";
        $q2 = "UPDATE $table SET id = @num := (@num+1);";
        $q3 = "ALTER TABLE $table AUTO_INCREMENT = 1;";

        $r1 = mysqli_query($conn, $q1);
        $r2 = mysqli_query($conn, $q2);
        $r3 = mysqli_query($conn, $q3);

        if ($r1 && $r2 && $r3) {
            echo json_encode(['status' => 200, 'message' => 'IDs have been reset and reordered successfully.']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Failed to reset IDs: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'Invalid type for ID reset.']);
    }
    exit;
}

if (isset($_POST['submit_vote'])) {
    ob_end_clean();
    try {
        $regno = isset($_POST['regno']) ? trim(mysqli_real_escape_string($conn, $_POST['regno'])) : '';
        $name = isset($_POST['name']) ? trim(mysqli_real_escape_string($conn, $_POST['name'])) : '';
        $dept = isset($_POST['dept']) ? trim(mysqli_real_escape_string($conn, $_POST['dept'])) : '';
        $year = isset($_POST['year']) ? trim(mysqli_real_escape_string($conn, $_POST['year'])) : '';
        $vote = isset($_POST['vote']) ? trim(mysqli_real_escape_string($conn, $_POST['vote'])) : '';

        if (empty($regno) || empty($vote)) {
            echo json_encode(['status' => 400, 'message' => 'Registration Number and Vote are required.']);
            exit;
        }

        // Check if regno already exists
        $check_sql = "SELECT regno FROM photography WHERE regno = '$regno'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            echo json_encode(['status' => 409, 'message' => 'Registration number has already voted!']);
            exit;
        }

        // Normalize Year to Roman Format (I year, II year, etc.)
        $yearFormatted = $year;
        $yearInput = strtolower(trim($year));

        if (in_array($yearInput, ['1', '1st', 'i', '1st year']))
            $yearFormatted = 'I year';
        elseif (in_array($yearInput, ['2', '2nd', 'ii', '2nd year']))
            $yearFormatted = 'II year';
        elseif (in_array($yearInput, ['3', '3rd', 'iii', '3rd year']))
            $yearFormatted = 'III year';
        elseif (in_array($yearInput, ['4', '4th', 'iv', '4th year', 'final']))
            $yearFormatted = 'IV year';

        // Parse Vote as integer
        $voteInt = intval($vote);

        // Insert
        $query = "INSERT INTO photography (regno, name, dept, year, vote) VALUES ('$regno', '$name', '$dept', '$yearFormatted', '$voteInt')";

        if (mysqli_query($conn, $query)) {
            $res = [
                'status' => 200,
                'message' => 'Vote submitted successfully!'
            ];
            echo json_encode($res);
        } else {
            throw new Exception('Query Failed: ' . mysqli_error($conn));
        }

    } catch (Exception $e) {
        $res = [
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ];
        echo json_encode($res);
    }
}



if (isset($_POST['get_global_pass'])) {
    try {
        $rollno = mysqli_real_escape_string($conn, $_POST['rollno']);

        if (strlen($rollno) !== 12) {
            echo json_encode(['status' => 400, 'message' => 'Roll Number must be exactly 12 characters.']);
            exit;
        }

        $eventKeys = [];

        // Fetch Solo Events
        $soloQuery = "SELECT events FROM soloevents WHERE regno='$rollno'";
        $soloResult = mysqli_query($conn, $soloQuery);
        if ($soloResult) {
            while ($row = mysqli_fetch_assoc($soloResult)) {
                $eventKeys[] = $row['events'];
            }
        }

        // Fetch Group Events (Leader)
        $groupLeaderQuery = "SELECT events FROM groupevents WHERE tregno='$rollno'";
        $groupLeaderResult = mysqli_query($conn, $groupLeaderQuery);
        if ($groupLeaderResult) {
            while ($row = mysqli_fetch_assoc($groupLeaderResult)) {
                $eventKeys[] = $row['events'];
            }
        }

        // Fetch Group Events (Member)
        $searchStr = '"roll":"' . $rollno . '"';
        $groupMemberQuery = "SELECT events FROM groupevents WHERE tmembername LIKE '%$searchStr%'";
        $groupMemberResult = mysqli_query($conn, $groupMemberQuery);
        if ($groupMemberResult) {
            while ($row = mysqli_fetch_assoc($groupMemberResult)) {
                $eventKeys[] = $row['events'];
            }
        }

        $totalEvents = count($eventKeys);
        $eventNames = [];

        if ($totalEvents > 0) {
            // Fetch readable event names
            $keysString = "'" . implode("','", $eventKeys) . "'";
            $nameQuery = "SELECT event_name FROM events WHERE event_key IN ($keysString)";
            $nameResult = mysqli_query($conn, $nameQuery);
            if ($nameResult) {
                while ($row = mysqli_fetch_assoc($nameResult)) {
                    $eventNames[] = $row['event_name'];
                }
            }
        }

        $passType = 'No Pass';
        $passClass = 'no-pass';

        if ($totalEvents == 1) {
            $passType = 'Local Pass';
            $passClass = 'local-pass';
        } elseif ($totalEvents == 2) {
            $passType = 'Elite Pass';
            $passClass = 'elite-pass';
        } elseif ($totalEvents == 3) {
            $passType = 'Gold Pass';
            $passClass = 'gold-pass';
        } elseif ($totalEvents >= 4) {
            $passType = 'Platinum Pass';
            $passClass = 'platinum-pass';
        }

        echo json_encode([
            'status' => 200,
            'rollno' => $rollno,
            'total_events' => $totalEvents,
            'pass_type' => $passType,
            'pass_class' => $passClass,
            'registered_events' => $eventNames
        ]);

    } catch (Exception $e) {
        echo json_encode(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit;
}

if (isset($_POST['delete_vote'])) {
    ob_end_clean();
    header('Content-Type: application/json');
    if (!isset($_POST['id'])) {
        echo json_encode(['status' => 400, 'message' => 'No ID provided']);
        exit;
    }
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $query = "DELETE FROM photography WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 200, 'message' => 'Vote deleted successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Error deleting vote: ' . mysqli_error($conn)]);
    }
    exit;
}

if (isset($_POST['bulk_delete_votes'])) {
    ob_end_clean();
    header('Content-Type: application/json');
    if (!isset($_POST['ids'])) {
        echo json_encode(['status' => 400, 'message' => 'No IDs provided']);
        exit;
    }
    $ids = $_POST['ids']; // Array of IDs
    if (!empty($ids) && is_array($ids)) {
        $escaped_ids = array_map(function ($id) use ($conn) {
            return mysqli_real_escape_string($conn, $id);
        }, $ids);
        $ids_str = "'" . implode("','", $escaped_ids) . "'";
        $query = "DELETE FROM photography WHERE id IN ($ids_str)";
        if (mysqli_query($conn, $query)) {
            echo json_encode(['status' => 200, 'message' => 'Selected votes deleted successfully']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Error deleting selected votes: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 400, 'message' => 'No votes selected']);
    }
    exit;
}

if (isset($_POST['submit_feedback'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $eventPass = mysqli_real_escape_string($conn, $_POST['event_pass']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback_text']);
    $eventKey = $_SESSION['userid'] ?? ''; // The logged-in event admin

    ob_end_clean();
    if (empty($eventKey)) {
        echo json_encode(['status' => 401, 'message' => 'Unauthorized']);
        exit;
    }

    // 1. Check if feedback already exists
    $checkQ = "SELECT * FROM feedback WHERE event_pass='$eventPass'";
    $checkR = mysqli_query($conn, $checkQ);
    if (mysqli_num_rows($checkR) > 0) {
        $row = mysqli_fetch_assoc($checkR);
        echo json_encode(['status' => 409, 'message' => 'Feedback previously entered for the ' . $row['event_name']]);
        exit;
    }

    // 2. Verify Pass belongs to this Event Admin
    $found = false;
    $eventName = "";

    // Check Solo Table
    $sQ = "SELECT events, name FROM soloevents WHERE event_pass='$eventPass'";
    $sR = mysqli_query($conn, $sQ);
    if ($sR && mysqli_num_rows($sR) > 0) {
        $sRow = mysqli_fetch_assoc($sR);
        if ($sRow['events'] == $eventKey) {
            $found = true;
        }
    }

    // Check Group Table if not found
    if (!$found) {
        $gQ = "SELECT events, teamname FROM groupevents WHERE event_pass='$eventPass'";
        $gR = mysqli_query($conn, $gQ);
        if ($gR && mysqli_num_rows($gR) > 0) {
            $gRow = mysqli_fetch_assoc($gR);
            if ($gRow['events'] == $eventKey) {
                $found = true;
            }
        }
    }

    if (!$found) {
        // Optional: Allow global feedback regardless of event? No, logic says "entered for the event_name".
        // It implies the feedback is for THIS event.
        echo json_encode(['status' => 404, 'message' => 'Invalid internal Event Pass or Pass does not belong to this event.']);
        exit;
    }

    // Get Event Name for the record
    $eNameQ = "SELECT event_name FROM events WHERE event_key='$eventKey'";
    $eNameR = mysqli_query($conn, $eNameQ);
    if ($eNameR && mysqli_num_rows($eNameR) > 0) {
        $eNameRow = mysqli_fetch_assoc($eNameR);
        $eventName = $eNameRow['event_name'];
    } else {
        $eventName = "Unknown Event";
    }

    // 3. Insert
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    // feedback_text is functioning as 'suggestions'
    $ins = "INSERT INTO feedback (event_pass, event_key, event_name, feedback_text, rating, suggestions) VALUES ('$eventPass', '$eventKey', '$eventName', '', '$rating', '$feedback')";
    if (mysqli_query($conn, $ins)) {
        echo json_encode(['status' => 200, 'message' => 'Feedback collected successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Error saving feedback: ' . mysqli_error($conn)]);
    }
    exit;
}

if (isset($_GET['download_type'])) {
    // Permission check
    if (!isset($_SESSION['role']) || $_SESSION['role'] != '2') {
        http_response_code(403);
        die("Access Denied");
    }

    $type = $_GET['download_type'];
    $baseUploadDir = 'uploads';
    $targetDir = $baseUploadDir;
    $zipPrefix = 'orlia_uploads_all_';

    switch ($type) {
        case 'photos':
            $targetDir = $baseUploadDir . '/photos';
            $zipPrefix = 'orlia_photos_';
            break;
        case 'videos':
            $targetDir = $baseUploadDir . '/videos';
            $zipPrefix = 'orlia_videos_';
            break;
        case 'songs':
            $targetDir = $baseUploadDir . '/songs';
            $zipPrefix = 'orlia_songs_';
            break;
        case 'all':
        default:
            $targetDir = $baseUploadDir;
            $zipPrefix = 'orlia_uploads_all_';
            break;
    }

    if (!is_dir($targetDir)) {
        die("Directory not found: " . htmlspecialchars($targetDir));
    }

    $zipFileName = $zipPrefix . date('Y-m-d_H-i-s') . '.zip';
    $zipTmpPath = sys_get_temp_dir() . '/' . $zipFileName;

    set_time_limit(600);

    // ZIP Logic
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($zipTmpPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("Cannot create zip file at " . $zipTmpPath);
        }
        if (count(scandir($targetDir)) <= 2) {
            die("Directory is empty.");
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($targetDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen(realpath($targetDir)) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    } else {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $sourcePath = realpath($targetDir);
            $winSource = $sourcePath . DIRECTORY_SEPARATOR . '*';
            $cmd = 'powershell -NoProfile -Command "Compress-Archive -Path \'' . $winSource . '\' -DestinationPath \'' . $zipTmpPath . '\' -Force"';
            exec($cmd, $output, $returnVar);
            if (!file_exists($zipTmpPath)) {
                die("Error: ZipArchive missing and PowerShell fallback failed.");
            }
        } else {
            die("Error: Class ZipArchive not found.");
        }
    }

    if (file_exists($zipTmpPath)) {
        if (isset($_GET['token'])) {
            setcookie('download_started', $_GET['token'], time() + 300, '/');
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($zipTmpPath));
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($zipTmpPath);
        unlink($zipTmpPath);
        exit;
    } else {
        die("Failed to create zip file.");
    }
}

if (isset($_POST['export_table'])) {
    // Permission check
    if (!isset($_SESSION['role']) || $_SESSION['role'] != '2') {
        die("Access Denied");
    }

    $dbName = 'orlia';
    $targetTable = $_POST['export_table'];
    $tables = [];

    if ($targetTable === 'all') {
        $tablesResult = mysqli_query($conn, "SHOW TABLES");
        if ($tablesResult) {
            while ($row = mysqli_fetch_array($tablesResult)) {
                $tables[] = $row[0];
            }
        }
    } else {
        $targetTable = mysqli_real_escape_string($conn, $targetTable);
        $checkQ = mysqli_query($conn, "SHOW TABLES LIKE '$targetTable'");
        if (mysqli_num_rows($checkQ) > 0) {
            $tables[] = $targetTable;
        } else {
            die("Table not found");
        }
    }

    // Capture Output Buffer for safety
    while (ob_get_level()) {
        ob_end_clean();
    }

    $sql = "-- phpMyAdmin SQL Dump\n";
    $sql .= "-- Generation Time: " . date('M d, Y \a\t h:i A') . "\n";
    $sql .= "-- Server version: " . mysqli_get_server_info($conn) . "\n";
    $sql .= "-- PHP Version: " . phpversion() . "\n\n";
    $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
    $sql .= "START TRANSACTION;\n";
    $sql .= "SET time_zone = \"+00:00\";\n\n";
    $sql .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
    $sql .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
    $sql .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
    $sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";
    $sql .= "--\n-- Database: `$dbName`\n--\n\n";

    $indexesSql = "";
    $autoIncSql = "";

    foreach ($tables as $table) {
        $sql .= "-- --------------------------------------------------------\n\n";
        $sql .= "--\n-- Table structure for table `$table`\n--\n\n";

        $createQ = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
        $row = mysqli_fetch_row($createQ);
        $createStmt = $row[1];

        $lines = explode("\n", $createStmt);
        $cleanLines = [];
        $keys = [];
        $autoVal = "";
        $lastLineIndex = count($lines) - 1;
        if (preg_match('/AUTO_INCREMENT=(\d+)/', $lines[$lastLineIndex], $matches)) {
            $autoVal = $matches[1];
            $lines[$lastLineIndex] = preg_replace('/ AUTO_INCREMENT=\d+/', '', $lines[$lastLineIndex]);
        }

        foreach ($lines as $line) {
            if (preg_match('/^\s*(PRIMARY KEY|UNIQUE KEY|KEY|FULLTEXT KEY|SPATIAL KEY|CONSTRAINT)\b/i', $line)) {
                $keys[] = rtrim(trim($line), ',');
            } else {
                $cleanLines[] = $line;
            }
        }
        if (count($cleanLines) > 2) {
            $lastColIndex = count($cleanLines) - 2;
            $cleanLines[$lastColIndex] = rtrim($cleanLines[$lastColIndex], ',');
        }
        $sql .= implode("\n", $cleanLines) . ";\n\n";

        if (!empty($keys)) {
            $indexesSql .= "--\n-- Indexes for table `$table`\n--\n";
            $indexesSql .= "ALTER TABLE `$table`\n";
            foreach ($keys as $k => $keyDef) {
                $indexesSql .= "  ADD " . $keyDef;
                $indexesSql .= ($k < count($keys) - 1) ? ",\n" : ";\n";
            }
            $indexesSql .= "\n";
        }

        $colQ = mysqli_query($conn, "SHOW COLUMNS FROM `$table` WHERE Extra LIKE '%auto_increment%'");
        if ($aiCol = mysqli_fetch_assoc($colQ)) {
            $autoIncSql .= "--\n-- AUTO_INCREMENT for table `$table`\n--\n";
            $autoIncSql .= "ALTER TABLE `$table`\n";
            $colName = $aiCol['Field'];
            $colType = $aiCol['Type'];
            $nullStr = ($aiCol['Null'] === 'NO') ? 'NOT NULL' : 'NULL';
            $autoIncSql .= "  MODIFY `$colName` $colType $nullStr AUTO_INCREMENT";
            if ($autoVal) {
                $autoIncSql .= ", AUTO_INCREMENT=$autoVal";
            }
            $autoIncSql .= ";\n\n";
        }

        $sql .= "--\n-- Dumping data for table `$table`\n--\n\n";
        $dataQ = mysqli_query($conn, "SELECT * FROM `$table`");
        $numRows = mysqli_num_rows($dataQ);
        if ($numRows > 0) {
            $sql .= "INSERT INTO `$table` VALUES\n";
            $i = 0;
            while ($r = mysqli_fetch_row($dataQ)) {
                $sql .= "(";
                $vals = [];
                foreach ($r as $val) {
                    if ($val === null)
                        $vals[] = "NULL";
                    else
                        $vals[] = "'" . mysqli_real_escape_string($conn, $val) . "'";
                }
                $sql .= implode(", ", $vals) . ")";
                $sql .= ($i < $numRows - 1) ? ",\n" : ";\n";
                $i++;
            }
        } else {
            $sql .= "-- No data found for `$table`\n";
        }
        $sql .= "\n";
    }

    if ($indexesSql)
        $sql .= "--\n-- Indexes for dumped tables\n--\n\n" . $indexesSql;
    if ($autoIncSql)
        $sql .= "--\n-- AUTO_INCREMENT for dumped tables\n--\n\n" . $autoIncSql;

    $sql .= "COMMIT;\n\n";
    $sql .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
    $sql .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
    $sql .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";

    header('Content-Type: application/sql');
    $fileName = ($targetTable === 'all') ? $dbName . '_full_backup_' : $targetTable . '_backup_';
    header('Content-Disposition: attachment; filename="' . $fileName . date('Y-m-d') . '.sql"');
    echo $sql;
    exit;
}

if (isset($_POST['delete_participant'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $type = mysqli_real_escape_string($conn, $_POST['type']); // 'solo' or 'group'
    $table = ($type == 'solo') ? 'soloevents' : 'groupevents';

    $query = "DELETE FROM $table WHERE id='$id'";
    ob_end_clean();
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 200, 'message' => 'Participant deleted successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Deletion failed: ' . mysqli_error($conn)]);
    }
    exit;
}

if (isset($_POST['get_participant_details'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $table = ($type == 'solo') ? 'soloevents' : 'groupevents';

    $query = "SELECT * FROM $table WHERE id='$id'";
    $res = mysqli_query($conn, $query);
    ob_end_clean();
    if ($res && mysqli_num_rows($res) > 0) {
        echo json_encode(['status' => 200, 'data' => mysqli_fetch_assoc($res)]);
    } else {
        echo json_encode(['status' => 404, 'message' => 'Participant not found']);
    }
    exit;
}

if (isset($_POST['update_participant'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);

    if ($type == 'solo') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $regno = mysqli_real_escape_string($conn, $_POST['regno']);
        $dept = mysqli_real_escape_string($conn, $_POST['dept']);
        $year = mysqli_real_escape_string($conn, $_POST['year']);
        $phone = mysqli_real_escape_string($conn, $_POST['phoneno']);

        $query = "UPDATE soloevents SET name='$name', regno='$regno', dept='$dept', year='$year', phoneno='$phone' WHERE id='$id'";
    } else {
        $teamname = mysqli_real_escape_string($conn, $_POST['teamname']);
        $teamleadname = mysqli_real_escape_string($conn, $_POST['teamleadname']);
        $tregno = mysqli_real_escape_string($conn, $_POST['tregno']);
        $phone = mysqli_real_escape_string($conn, $_POST['phoneno']);

        $query = "UPDATE groupevents SET teamname='$teamname', teamleadname='$teamleadname', tregno='$tregno', phoneno='$phone' WHERE id='$id'";
    }

    ob_end_clean();
    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 200, 'message' => 'Participant updated successfully']);
    } else {
        echo json_encode(['status' => 500, 'message' => 'Update failed: ' . mysqli_error($conn)]);
    }
    exit;
}

?>