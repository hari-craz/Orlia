<?php
include 'includes/auth.php';
checkUserAccess(true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Orlia'26</title>
    <link rel="icon" href="assets/images/agastya.png" type="image/png">
    <link rel="stylesheet" href="assets/styles/styles.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .step-container {
            display: none;
            animation: fadeIn 0.5s;
        }

        .step-container.active {
            display: block;
        }

        .step-indicators {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            display: none;
            /* Hidden by default */
        }

        .step-dot {
            height: 10px;
            width: 10px;
            background-color: rgba(150, 150, 150, 0.5);
            border-radius: 50%;
            display: inline-block;
            margin: 0 8px;
            transition: all 0.3s ease;
        }

        .step-dot.active {
            background-color: var(--fest-purple);
            transform: scale(1.4);
            box-shadow: 0 0 10px var(--fest-purple);
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-prev {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-glass);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-next {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            width: auto;
            /* Allow auto width */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
    <div class="registration-container">
        <div class="brand-section">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <h1>ORLIA'26</h1>
            <p>Join us for an incredible technical symposium experience at MKCE</p>
        </div>
        <div class="form-section">
            <div class="floating-circle"></div>
            <div class="floating-circle"></div>
            <div class="registration-form">
                <!-- <h2>ORLIA 2K26</h2> -->
                <form id="registerForm">

                    <div class="step-indicators" id="stepIndicators">
                        <span class="step-dot active"></span>
                        <span class="step-dot"></span>
                    </div>

                    <!-- Step 1: Personal Details & Event Selection -->
                    <div id="step1" class="step-container active">
                        <div class="form-group">
                            <input type="text" id="fullName" name="fullName" placeholder="Name" required>
                        </div>

                        <div class="form-group">
                            <select id="department" name="department" required>
                                <option value="" disabled selected>Select Department</option>
                                <option value="AIDS">Artificial Intelligence and Data Science</option>
                                <option value="AIML">Artificial Intelligence and Machine Learning</option>
                                <option value="CYBER">CSE - Cyber Security</option>
                                <option value="CSE">Computer Science Engineering</option>
                                <option value="CSBS">Computer Science And Business Systems</option>
                                <option value="ECE">Electronics & Communication Engineering</option>
                                <option value="EEE">Electrical & Electronics Engineering</option>
                                <option value="MECH">Mechanical Engineering</option>
                                <option value="CIVIL">Civil Engineering</option>
                                <option value="IT">Information Technology</option>
                                <option value="VLSI">Electronics Engineering (VLSI Design)</option>
                                <option value="MCA">Master of Computer Applications</option>
                                <option value="MBA">Master of Business Administration</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <select id="year" name="year" required>
                                <option value="" disabled selected>Select Year</option>
                                <option value="I year">I Year</option>
                                <option value="II year">II year</option>
                                <option value="III year">III year</option>
                                <option value="IV year">IV year</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <!-- Made placeholder slightly different to indicate user needs to fill suffix -->
                            <input type="text" id="rollNumber" name="rollNumber" placeholder="Roll Number" required
                                minlength="12" maxlength="12">
                        </div>

                        <div class="form-group">
                            <input type="email" id="mailid" name="mailid" placeholder="Mail Id" required>
                        </div>

                        <div class="form-group">
                            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
                        </div>

                        <div class="form-group">
                            <select id="daySelection" name="daySelection" required onchange="updateEvents()">
                                <option value="" disabled>Select Day</option>
                                <option value="day1">Day 1</option>
                                <option value="day2">Day 2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <select id="events" name="events" required>
                                <option value="" disabled selected>Select Event</option>
                            </select>
                        </div>

                        <!-- Navigation for Step 1 -->
                        <div class="form-navigation" id="navStep1">
                            <!-- Standard Register Button (Default) -->
                            <button type="submit" class="submit-btn" id="btnRegisterStep1">Register</button>
                            <!-- Next Button (Hidden by default, shown for Shortfilm) -->
                            <button type="button" class="btn-next" id="btnNextStep1" style="display: none; width: 100%;"
                                onclick="nextStep()">Next</button>
                        </div>
                    </div>

                    <!-- Step 2: Upload (Shortfilm / Photography) -->
                    <div id="step2" class="step-container">
                        <h3 id="uploadTitle">Upload File</h3>

                        <!-- Shortfilm Video -->
                        <div class="form-group" id="videoUploadGroup" style="display:none;">
                            <label for="videoFile"
                                style="display: block; margin-bottom: 5px; color: var(--text-color);">Upload Shortfilm
                                (MP4 only)</label>
                            <input type="file" id="videoFile" name="video" accept=".mp4">
                        </div>

                        <!-- Photography Photo -->
                        <div class="form-group" id="photoUploadGroup" style="display:none;">
                            <label for="photoFile"
                                style="display: block; margin-bottom: 5px; color: var(--text-color);">Upload Photo
                                (JPG/PNG)</label>
                            <input type="file" id="photoFile" name="photo" accept=".jpg, .jpeg, .png">
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn-prev" onclick="prevStep()">Back</button>
                            <button type="submit" class="submit-btn"
                                style="width: auto; padding: 10px 30px;">Register</button>
                        </div>
                    </div>

                    <div class="event-footer">
                        <div class="event-location">
                            <i class="ri-map-pin-line"></i>
                            <span>MKCE</span>
                        </div>
                        <a href="index.php" class="event-btn">Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/script/script.js"></script>
    <script src="assets/script/assistant.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        /* Global Pass Styles for Instant Download */
        /* Global Pass Styles for Instant Download */
        /* Local Pass */
        .local-pass {
            background: linear-gradient(135deg, #FF6B6B 0%, #556270 100%);
            border: 2px solid rgba(255, 255, 255, 0.4);
            position: relative;
        }

        /* Elite Pass */
        .elite-pass {
            background: linear-gradient(135deg, #12c2e9 0%, #c471ed 50%, #f64f59 100%);
            border: 2px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 0 30px rgba(18, 194, 233, 0.6);
            position: relative;
        }

        /* Sparkle effect for Elite */
        .elite-pass::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(white 1px, transparent 1px), radial-gradient(white 1px, transparent 1px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
            z-index: 1;
            pointer-events: none;
        }

        /* Gold Pass */
        .gold-pass {
            background: linear-gradient(135deg, #fce38a 0%, #f38181 100%);
            border: 2px solid #ffd700;
            box-shadow: 0 0 40px rgba(252, 227, 138, 0.6);
            position: relative;
        }

        .gold-pass::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
            pointer-events: none;
        }

        /* Platinum Pass */
        .platinum-pass {
            background: linear-gradient(135deg, #0cebeb 0%, #20e3b2 50%, #29ffc6 100%);
            border: 2px solid #fff;
            box-shadow: 0 0 50px rgba(41, 255, 198, 0.6);
            position: relative;
        }

        /* Holographic overlay for Platinum */
        .platinum-pass::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 0, 0, 0.1), rgba(0, 255, 0, 0.1), rgba(0, 0, 255, 0.1));
            mix-blend-mode: overlay;
            z-index: 1;
            pointer-events: none;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes shine {
            0% {
                transform: translate(-100%, -100%) rotate(45deg);
            }

            100% {
                transform: translate(100%, 100%) rotate(45deg);
            }
        }

        /* Hidden Pass Container Structure */
        #hidden-pass-container {
            position: absolute;
            left: -9999px;
            top: 0;
            width: 350px;
            font-family: 'Space Grotesk', sans-serif;
            color: white;
            z-index: -1;
        }

        #hidden-pass-card {
            width: 350px;
            height: 500px;
            border-radius: 25px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2rem;
            box-sizing: border-box;
        }

        .hp-header {
            text-align: center;
            z-index: 2;
        }

        .hp-header h3 {
            font-size: 2rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hp-badge {
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            backdrop-filter: blur(5px);
        }

        .hp-body {
            text-align: center;
            z-index: 2;
        }

        .hp-count {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .hp-label {
            font-size: 1rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hp-footer {
            text-align: center;
            z-index: 2;
        }

        .hp-user {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .hp-qr {
            width: 120px;
            height: 120px;
            background: white;
            margin: 0 auto;
            border-radius: 10px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <!-- Hidden Pass Element -->
    <div id="hidden-pass-container">
        <!-- Slightly larger width for better layout -->
        <div id="hidden-pass-card" class="local-pass">
            <!-- Decorative Background Elements -->
            <div
                style="position: absolute; top: -50px; right: -50px; width: 180px; height: 180px; background: rgba(255,255,255,0.1); border-radius: 50%;">
            </div>
            <div
                style="position: absolute; bottom: 50px; left: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.05); border-radius: 50%;">
            </div>

            <div class="hp-header-section"
                style="text-align: center; position: relative; z-index: 2; margin-bottom: 15px;">
                <h3
                    style="font-family: 'Space Grotesk', sans-serif; font-size: 2.2rem; font-weight: 800; letter-spacing: 2px; margin: 0; color: #fff; text-shadow: 0 4px 10px rgba(0,0,0,0.3);">
                    ORLIA '26</h3>
                <div id="hp-title"
                    style="font-size: 1.1rem; text-transform: uppercase; letter-spacing: 3px; font-weight: 700; color: rgba(255,255,255,0.9); margin-top: 5px;">
                    Local Pass</div>
            </div>

            <div class="hp-card-inner"
                style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 20px; padding: 15px; border: 1px solid rgba(255,255,255,0.2); flex: 1; display: flex; flex-direction: column; gap: 10px;">

                <!-- Participant Info -->
                <div
                    style="background: white; border-radius: 12px; padding: 10px 15px; color: #333; box-shadow: 0 5px 15px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 0.65rem; color: #888; text-transform: uppercase; font-weight: 700;">
                            Participant</div>
                        <div id="hp-name" style="font-size: 1rem; font-weight: 700; color: #000; line-height: 1.2;">Name
                            Here</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.65rem; color: #888; text-transform: uppercase; font-weight: 700;">Roll
                            No</div>
                        <div id="hp-roll" style="font-size: 1rem; font-weight: 700; color: #000; line-height: 1.2;">
                            927622...</div>
                    </div>
                </div>

                <!-- Event Count (smaller now) -->
                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; color: white;">
                    <div style="font-size: 0.9rem; opacity: 0.9;">Events Registered:</div>
                    <div id="hp-count"
                        style="font-family: 'Space Grotesk', sans-serif; font-size: 1.5rem; font-weight: 700;">0</div>
                </div>

                <!-- Registered Events List -->
                <div
                    style="background: rgba(0,0,0,0.2); border-radius: 12px; padding: 10px; flex: 1; overflow: hidden;">
                    <div
                        style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.8); margin-bottom: 5px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 3px;">
                        Access List</div>
                    <div id="hp-events-list"
                        style="font-size: 0.85rem; color: white; text-align: center; line-height: 1.4; font-weight: 500;">
                        <!-- Events populated here -->
                    </div>
                </div>

                <!-- QR Section -->
                <div
                    style="background: white; border-radius: 12px; padding: 10px; color: #333; display: flex; align-items: center; gap: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: auto;">
                    <div id="hp-qrcode"
                        style="width: 70px; height: 70px; flex-shrink: 0; display: flex; justify-content: center; align-items: center;">
                    </div>
                    <div style="flex: 1;">
                        <div
                            style="font-family: monospace; font-size: 1.2rem; font-weight: 800; letter-spacing: 1px; color: #333;">
                            SCAN ME</div>
                        <div style="font-size: 0.7rem; color: #666; line-height: 1.2;">Show this QR code at the
                            registration desk for entry.</div>
                    </div>
                </div>

            </div>

            <div
                style="text-align: center; margin-top: 10px; font-size: 0.65rem; color: rgba(255,255,255,0.6); position: relative; z-index: 2;">
                Official Event Pass • MKCE
            </div>
        </div>
    </div>

    <script>
        // Existing script continues...
        $(document).on('submit', '#registerForm', function (e) {
            e.preventDefault();
            // ... (validation logic same as before) ...
            var rollVal = $('#rollNumber').val();
            if (rollVal.length !== 12) {
                Swal.fire({ title: "Error!", text: "Roll Number must be exactly 12 characters.", icon: "error" }); return;
            }

            var daySelect = $('#daySelection');
            var eventsSelect = $('#events');
            var dayDisabled = daySelect.prop('disabled');
            var eventsDisabled = eventsSelect.prop('disabled');

            daySelect.prop('disabled', false);
            eventsSelect.prop('disabled', false);

            var Formdata = new FormData(this);
            Formdata.append("Add_newuser", true);

            if (dayDisabled) daySelect.prop('disabled', true);
            if (eventsDisabled) eventsSelect.prop('disabled', true);

            $.ajax({
                url: "backend.php",
                method: "POST",
                data: Formdata,
                processData: false,
                contentType: false,
                success: function (response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.status == 200) {
                            $('#registerForm')[0].reset();
                            prevStep();
                            checkEventFlow('');

                            // Auto Download Global Pass
                            if (res.pass_details) {
                                autoDownloadPass(res.event_pass, res.pass_details);
                            }

                            Swal.fire({
                                title: "Good job!",
                                text: res.message,
                                icon: "success",
                                confirmButtonColor: '#7066e0',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = "index.php#events";
                            });
                        } else {
                            Swal.fire({ title: "Error!", text: res.message, icon: "error" });
                        }
                    } catch (e) { console.error(e); }
                }
            });
        });

        function autoDownloadPass(passCode, details) {
            // Determine Tier Class
            let passClass = 'local-pass';
            if (details.passType === 'Elite Pass') passClass = 'elite-pass';
            else if (details.passType === 'Gold Pass') passClass = 'gold-pass';
            else if (details.passType === 'Platinum Pass') passClass = 'platinum-pass';

            // Update UI
            const card = document.getElementById('hidden-pass-card');
            card.className = ''; // remove old
            card.classList.add(passClass);

            // Update Header Name
            document.getElementById('hp-title').textContent = details.passType || 'Local Pass';

            // Update Details
            const nameEl = document.getElementById('hp-name');
            const rollEl = document.getElementById('hp-roll');
            const eventListEl = document.getElementById('hp-events-list');

            if (details.isTeam) {
                // Formatting for Team
                // Format: "TeamName (LeaderName)"
                nameEl.innerHTML = `<span style="display:block; font-size:1.1em; color:#0056b3;">${details.teamName}</span><span style="font-size:0.8em; color:#555;">Lead: ${details.participantName}</span>`;
                rollEl.textContent = details.rollNo;

                // Show Team Members in the list area
                let content = '';

                // 1. Show Registered Events first
                if (details.registeredEvents && details.registeredEvents.length > 0) {
                    content += `<div style="margin-bottom:8px; border-bottom:1px dashed rgba(255,255,255,0.3); padding-bottom:4px;"><strong>Events:</strong><br>${details.registeredEvents.join(', ')}</div>`;
                } else {
                    content += `<div style="margin-bottom:8px;"><strong>Event:</strong> ${details.eventName}</div>`;
                }

                // 2. Show Members
                if (details.teamMembers && details.teamMembers.length > 0) {
                    content += `<div style="font-size:0.85em; text-align:left; display:inline-block;"><strong>Members:</strong><ul style="margin:0; padding-left:15px; text-align:left;">`;
                    details.teamMembers.forEach(m => {
                        content += `<li>${m}</li>`;
                    });
                    content += `</ul></div>`;
                }

                eventListEl.innerHTML = content;

            } else {
                // Solo Format
                nameEl.textContent = details.participantName || 'Participant';
                rollEl.textContent = details.rollNo;

                // Populate Events List (Solo)
                if (details.registeredEvents && details.registeredEvents.length > 0) {
                    eventListEl.innerHTML = details.registeredEvents.map(e => `<div>• ${e}</div>`).join('');
                } else {
                    eventListEl.textContent = details.eventName || 'Event Registration';
                }
            }

            document.getElementById('hp-count').textContent = details.totalEvents || 1;

            // Generate QR
            document.getElementById('hp-qrcode').innerHTML = '';

            // Build QR Data with all events
            let qrData = `ORLIA:${details.rollNo}:${details.passType}`;
            if (details.registeredEvents && details.registeredEvents.length > 0) {
                qrData += `:${details.registeredEvents.join(',')}`;
            } else {
                qrData += `:${details.eventName}`; // Fallback
            }

            new QRCode(document.getElementById("hp-qrcode"), {
                text: qrData,
                width: 70,
                height: 70,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.L
            });

            // Capture and Download
            setTimeout(() => {
                html2canvas(document.getElementById("hidden-pass-card"), {
                    scale: 3,
                    backgroundColor: null,
                    useCORS: true
                }).then(canvas => {
                    var link = document.createElement('a');
                    // Filename differs for teams
                    let prefix = details.isTeam ? 'Team_' : '';
                    link.download = 'Orlia_' + prefix + details.passType.replace(' ', '_') + '_' + details.rollNo + '.png';
                    link.href = canvas.toDataURL("image/png");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            }, 600);
        }

        function updateEvents(preSelectedEvent = null) {
            const daySelection = document.getElementById("daySelection");
            const eventsDropdown = document.getElementById("events");

            eventsDropdown.innerHTML = '<option value="" disabled selected>Loading...</option>';
            eventsDropdown.disabled = true;

            const selectedDay = daySelection.value;

            if (!selectedDay) return;

            $.ajax({
                url: 'backend.php',
                type: 'GET',
                data: {
                    get_events: true,
                    day: selectedDay,
                    type: 'Solo' // Fetch Solo events for individual registration
                },
                success: function (response) {
                    try {
                        const events = JSON.parse(response);
                        eventsDropdown.innerHTML = '<option value="" disabled selected>Select Event</option>';

                        if (events.length > 0) {
                            events.forEach(event => {
                                const option = document.createElement("option");
                                option.value = event.value;
                                option.textContent = event.text;
                                if (preSelectedEvent && event.value.toLowerCase() === preSelectedEvent.toLowerCase()) {
                                    option.selected = true;
                                }
                                eventsDropdown.appendChild(option);
                            });

                            eventsDropdown.disabled = false;

                            if (preSelectedEvent) {
                                // Trigger check flow and lock if pre-selected
                                checkEventFlow(preSelectedEvent);
                                eventsDropdown.disabled = true;
                            }
                        } else {
                            eventsDropdown.innerHTML = '<option value="" disabled selected>No events available</option>';
                        }
                    } catch (e) {
                        console.error("Error parsing events", e);
                        eventsDropdown.innerHTML = '<option value="" disabled selected>Error loading events</option>';
                    }
                },
                error: function () {
                    eventsDropdown.innerHTML = '<option value="" disabled selected>Error connection</option>';
                }
            });
        }

        // Stepper Navigation Logic
        function nextStep() {
            // Validate Step 1 first
            const fullName = document.getElementById('fullName');
            const dept = document.getElementById('department');
            const year = document.getElementById('year');
            const roll = document.getElementById('rollNumber');
            const mail = document.getElementById('mailid');
            const phone = document.getElementById('phoneNumber');
            const event = document.getElementById('events');

            if (!fullName.checkValidity() || !dept.checkValidity() || !year.checkValidity() || !roll.checkValidity() || !mail.checkValidity() || !phone.checkValidity() || !event.checkValidity()) {
                // Trigger browser validation UI
                document.getElementById('registerForm').reportValidity();
                return;
            }

            document.getElementById('step1').classList.remove('active');
            document.getElementById('step2').classList.add('active');

            // Update dots
            const dots = document.querySelectorAll('.step-dot');
            dots[0].classList.remove('active');
            dots[1].classList.add('active');
        }

        function prevStep() {
            document.getElementById('step2').classList.remove('active');
            document.getElementById('step1').classList.add('active');

            // Update dots
            const dots = document.querySelectorAll('.step-dot');
            dots[1].classList.remove('active');
            dots[0].classList.add('active');
        }

        function checkEventFlow(eventName) {
            const lowerName = eventName ? eventName.toLowerCase() : '';
            const isShortfilm = lowerName === 'shortfilm' || lowerName === 'shortflim';
            const isPhotography = lowerName === 'photography';
            
            const btnRegister = document.getElementById('btnRegisterStep1');
            const btnNext = document.getElementById('btnNextStep1');
            const indicators = document.getElementById('stepIndicators');
            
            const videoGroup = document.getElementById('videoUploadGroup');
            const photoGroup = document.getElementById('photoUploadGroup');
            const uploadTitle = document.getElementById('uploadTitle');
            
            const videoInput = document.getElementById('videoFile');
            const photoInput = document.getElementById('photoFile');

            if (isShortfilm || isPhotography) {
                // Enable multi-step mode
                btnRegister.style.display = 'none';
                btnNext.style.display = 'block';
                indicators.style.display = 'flex'; // Show dots
                
                if (isShortfilm) {
                     uploadTitle.textContent = "Shortfilm Upload";
                     videoGroup.style.display = 'block';
                     photoGroup.style.display = 'none';
                     videoInput.required = true;
                     photoInput.required = false;
                } else if (isPhotography) {
                     uploadTitle.textContent = "Photography Upload";
                     videoGroup.style.display = 'none';
                     photoGroup.style.display = 'block';
                     videoInput.required = false;
                     photoInput.required = true;
                }
            } else {
                // Standard mode
                btnRegister.style.display = 'block';
                btnNext.style.display = 'none';
                indicators.style.display = 'none';
                
                videoGroup.style.display = 'none';
                photoGroup.style.display = 'none';
                videoInput.required = false;
                photoInput.required = false;
                videoInput.value = ''; 
                photoInput.value = '';

                // Ensure we are on Step 1
                prevStep();
            }
        }

        $(document).ready(function() {
            $('#events').change(function() {
                var selectedText = $(this).find("option:selected").val();
                checkEventFlow(selectedText);
            });
        });

        // Add change listener for Events to toggle stepper
        document.getElementById('events').addEventListener('change', function () {
            checkEventFlow(this.value);
        });

        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            const selectedDay = urlParams.get('day');
            const selectedEvent = urlParams.get('event');

            if (selectedDay) {
                const daySelect = document.getElementById('daySelection');
                daySelect.value = selectedDay;

                // If a valid day is selected
                if (daySelect.value) {
                    daySelect.disabled = true; // Fix day selection
                    // Update events and pre-select the event from URL if present
                    updateEvents(selectedEvent);
                }
            }

            // Auto-fill roll number logic
            const departmentSelect = document.getElementById('department');
            const yearSelect = document.getElementById('year');
            const rollNumberInput = document.getElementById('rollNumber');

            let currentFixedPrefix = '';
            let isRollPrefixLocked = false;

            const deptCodes = {
                'AIDS': 'BAD',
                'AIML': 'BAM',
                // 'CS': 'BSC', // Note: 'CS' is not in the dropdown options currently (likely refers to 'CSE' or 'CSBS' or 'Cyber'?), strictly following request mapping:
                // Assuming 'CS' in user request might map to something else, but dropdown has CSE, CSBS, CYBER. 
                // Let's stick to dropdown values. If dropdown has 'CSE', we use 'BCS'.
                'CSE': 'BCS',
                'CSBS': 'BCB',
                'CYBER': 'BSC', // Assuming 'CS' from prompt might map to 'CYBER' or just explicit mapping needed? 
                // PROMPT SAYS: "CS - BSC". Dropdown has "CYBER" "CSE" "CSBS". 
                // Usually 'Cyber' is often separate. Let's assume 'CYBER' -> 'BSC' based on exclusion or user might mean 'Computer Science'. 
                // Wait, prompt says: "CS - BSC" and "CSE - BCS". 
                // Let's try to map best as possible. 
                // If dropdown has 'CYBER', and prompt has 'CS', maybe 'CYBER' is 'BSC'? 
                // Let's proceed with knowns clearly, if valid matches found.
                'ECE': 'BEC',
                'EEE': 'BEE',
                'MECH': 'BME',
                'CIVIL': 'BCE',
                'IT': 'BIT',
                'VLSI': 'BEV',
                'MBA': 'MBA',
                'MCA': 'MCA'
            };

            const yearCodes = {
                'I year': '927625',
                'II year': '927624',
                'III year': '927623',
                'IV year': '927622'
            };

            // Enforce the prefix if locked
            rollNumberInput.addEventListener('input', function () {
                if (isRollPrefixLocked && currentFixedPrefix) {
                    if (!this.value.startsWith(currentFixedPrefix)) {
                        this.value = currentFixedPrefix;
                    }
                }
            });

            // Prevent deleting the prefix via backspace for better UX
            rollNumberInput.addEventListener('keydown', function (e) {
                if (isRollPrefixLocked && currentFixedPrefix) {
                    if (this.selectionStart <= currentFixedPrefix.length && e.key === 'Backspace') {
                        e.preventDefault();
                    }
                }
            });

            function checkAutoFillRollNumber() {
                const dept = departmentSelect.value;
                const year = yearSelect.value;

                let prefix = '';

                // Check if both valid
                if (dept && year && yearCodes[year]) {
                    const yCode = yearCodes[year];
                    let dCode = deptCodes[dept] || '';

                    // SPECIAL CASE: For AIML only if year == IV means prefix is 927622BAL
                    if (dept === 'AIML' && year === 'IV year') {
                        // Override dCode logic
                        // Standard AIML is BAM, but IV Year is BAL
                        dCode = 'BAL';
                    }

                    if (dCode) {
                        prefix = yCode + dCode;
                    }
                }

                if (prefix) {
                    // Start locking
                    if (currentFixedPrefix !== prefix) {
                        // If prefix changed (or started), update input
                        // If input was empty or had old prefix, replace/update
                        // If input has some user entered suffix *after* old prefix, try to keep it? 
                        // Simpler to just reset suffix if prefix changes drastically to avoid confusion.
                        rollNumberInput.value = prefix;
                    } else if (!rollNumberInput.value.startsWith(prefix)) {
                        // If same prefix but user cleared it
                        rollNumberInput.value = prefix;
                    }

                    currentFixedPrefix = prefix;
                    isRollPrefixLocked = true;
                } else {
                    // No valid combination (maybe valid year/dept but no code defined)
                    isRollPrefixLocked = false;
                    currentFixedPrefix = '';
                    // Optional: clear input? or leave as is?
                }
            }

            departmentSelect.addEventListener('change', checkAutoFillRollNumber);
            yearSelect.addEventListener('change', checkAutoFillRollNumber);

            // Real-time Roll Number Validation
            let rollTimer;
            const rollDoneTypingInterval = 500;

            rollNumberInput.addEventListener('keyup', function () {
                clearTimeout(rollTimer);
                const val = this.value;
                if (!val) {
                    setRollFeedback('', this);
                    return;
                }

                rollTimer = setTimeout(() => {
                    if (val.length === 12) {
                        setRollFeedback('valid', this);
                    } else {
                        setRollFeedback('invalid', this);
                    }
                }, rollDoneTypingInterval);
            });

            function setRollFeedback(status, input) {
                let existing = input.parentNode.querySelector('.feedback-msg');
                if (existing) existing.remove();

                if (!status) {
                    input.style.borderColor = '';
                    return;
                }

                const msg = document.createElement('span');
                msg.classList.add('feedback-msg');
                msg.style.fontSize = '0.8rem';
                msg.style.marginTop = '5px';
                msg.style.display = 'block';

                if (status === 'valid') {
                    input.style.borderColor = 'green';
                    msg.style.color = 'green';
                    msg.innerHTML = '<i class="ri-check-line"></i> Valid length';
                } else {
                    input.style.borderColor = 'red';
                    msg.style.color = 'red';
                    msg.innerHTML = '<i class="ri-close-line"></i> Must be 12 characters';
                }
                input.parentNode.appendChild(msg);
            }
        };

    </script>
</body>

</html>