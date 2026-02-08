<?php
include 'includes/auth.php';
checkUserAccess(true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orlia'26</title>
    <link rel="icon" href="assets/images/agastya.png" type="image/png">
    <link rel="stylesheet" href="assets/styles/styles.css">
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
    <nav class="sidebar">
        <ul class="nav-links">
            <li><a href="#home"><i class="ri-home-5-line"></i>Home</a></li>
            <li><a href="#about"><i class="ri-information-line"></i>About</a></li>
            <li><a href="#patrons"><i class="ri-user-star-line"></i>Patrons</a></li>
            <li><a href="#events"><i class="ri-calendar-event-line"></i>Events</a></li>
            <li><a href="#gallery"><i class="ri-image-2-line"></i>Gallery</a></li>
            <li><a href="#contact"><i class="ri-chat-1-line"></i>Contact</a></li>
        </ul>
    </nav>
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="glitch-wrapper">
                <h1 class="glitch-text" data-text="ORLIA'26">ORLIA'26</h1>
            </div>
            <p class="hero-subtitle">CULTURE & INNOVATION REIMAGINED</p>
            <p class="hero-desc">Step into a realm where tradition meets the digital frontier. Two days of electrifying
                performances, art, and tech.</p>
            <div class="hero-details">
                <div class="hero-detail">
                    <i class="ri-map-pin-2-line"></i>
                    <span>MKCE Campus</span>
                </div>
                <div class="hero-detail">
                    <i class="ri-calendar-event-line"></i>
                    <span>March 13-14, 2026</span>
                </div>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="#events" class="hero-btn">
                    <span>Explore Events</span>
                    <i class="ri-arrow-right-line"></i>
                </a>
                <a href="login.php" class="hero-btn secondary">
                    <span>Admin</span>
                    <i class="ri-shield-key-line"></i>
                </a>
            </div>
        </div>
        <div class="holo-card-container">
            <div class="holo-card">
                <div class="holo-content">
                    <div class="holo-header">
                        <span>OFFICIAL PASS</span>
                        <i class="ri-wireless-charging-line"></i>
                    </div>
                    <div class="holo-body">
                        <h2>ORLIA</h2>
                        <h3>2026</h3>
                        <div class="holo-lines"></div>
                    </div>
                    <div class="holo-footer">
                        <div class="scan-line"></div>
                        <p>ACCESS GRANTED</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="about" id="about">
        <div class="about-content">
            <div class="about-split">
                <div class="about-text">
                    <h2>About Orlia</h2>
                    <p class="section-subtitle">Where Tradition Meets Innovation</p>
                    <p class="about-desc">Orlia'26 is the flagship cultural festival that celebrates artistic
                        expression, creativity, and diversity. Join us for an unforgettable experience where talent
                        meets tradition, creating memories that will last a lifetime.</p>
                    <div class="about-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 220px)); justify-content: start; gap: 15px;">
                        <div class="about-card" style="padding: 12px;">
                            <i class="ri-calendar-line" style="font-size: 1.8rem; color: var(--fest-purple); margin-bottom: 8px;"></i>
                            <h3 style="font-size: 1rem; margin-bottom: 4px;">Annual Event</h3>
                            <p style="font-size: 0.85rem; margin-bottom: 0;">Join us for our premier cultural extravaganza</p>
                        </div>
                        <div class="about-card" style="padding: 12px;">
                            <i class="ri-trophy-line" style="font-size: 1.8rem; color: var(--fest-pink); margin-bottom: 8px;"></i>
                            <h3 style="font-size: 1rem; margin-bottom: 4px;">Competition</h3>
                            <p style="font-size: 0.85rem; margin-bottom: 0;">Showcase your skills and win exciting prizes</p>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <img src="assets/images/agastya.jpg" alt="Cultural Event">
                </div>
            </div>
        </div>
    </section>
    <section class="patrons" id="patrons">
        <div class="patrons-content">
            <div class="patron-category">
                <h3>Chief Patrons</h3>
                <div class="patron-grid technical-scroll-grid">
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/tamil.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>TAMILAN RAGUL GANDHI</h4>
                            </h4>
                            <p>Public Speaker</p>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/rajavelu.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>RAJAVELU</h4>
                            <p>Kalakka Povathu Yaaru</p>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/kuraishi.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>MOHAMED KURAISHI</h4>
                            <p>Kalakka Povathu Yaaru</p>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/sathish.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>SATHISH</h4>
                            <p>Kalakka Povathu Yaaru</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="patron-category">
                <h3>DJ Artist</h3>
                <div class="about-content" style="margin-top: 20px;">
                    <div class="about-split reverse-split">
                        <div class="about-image" style="flex: 0 1 40%;">
                            <img src="assets/images/dj_placeholder.jpg" onerror="this.src='assets/images/agastya.png'"
                                alt="DJ Artist"
                                style="max-height: 400px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                        </div>
                        <div class="about-text"
                            style="padding-left: 40px; display: flex; flex-direction: column; justify-content: center;">
                            <h2 style="font-size: 2rem; margin-bottom: 10px;">Special Performance</h2>
                            <p class="section-subtitle" style="margin-bottom: 20px; color: var(--fest-orange);">DJ Night
                            </p>
                            <p class="about-desc">
                                Get ready to experience an electrifying night of music and dance! Join us for a
                                high-energy DJ performance that will keep you on your feet. It's time to celebrate,
                                groove to the beats, and make memories that resonate with the rhythm of Orlia'26.
                            </p>
                            <div class="about-grid"
                                style="margin-top: 10px; grid-template-columns: repeat(auto-fit, minmax(160px, 220px)); justify-content: center; gap: 15px;">
                                <div class="about-card" style="padding: 12px;">
                                    <i class="ri-music-2-line"
                                        style="font-size: 1.8rem; color: var(--fest-orange); margin-bottom: 8px;"></i>
                                    <h3 style="font-size: 1rem; margin-bottom: 4px;">Live Mixing</h3>
                                    <p style="font-size: 0.85rem; margin-bottom: 0;">Experience top-tier sound mixing
                                    </p>
                                </div>
                                <div class="about-card" style="padding: 12px;">
                                    <i class="ri-lightbulb-flash-line"
                                        style="font-size: 1.8rem; color: var(--google-blue); margin-bottom: 8px;"></i>
                                    <h3 style="font-size: 1rem; margin-bottom: 4px;">Light Show</h3>
                                    <p style="font-size: 0.85rem; margin-bottom: 0;">A visual spectacle to match the
                                        beats</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="patron-category">
                <h3>Patrons</h3>
                <div class="patron-grid technical-scroll-grid safe-center-grid">
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/maniraj.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>Mr. P. MANIRAJ</h4>
                            </h4>
                            <p>Fine Arts - HEAD</p>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/ramesh.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>Dr. C. RAMESH</h4>
                            <p>Students Affairs - HEAD</p>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/sudharsan.jpg" alt="Coordinator">
                        </div>
                        <div class="patron-info">
                            <h4>Mr. R. SUDHARSANAN</h4>
                            <p>Fine Arts - Coordinator</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="patron-category">
                <h2>Technical StartUp</h2>
                <p class="section-subtitle">Students Startups who are Contributed for Orlia</p>
                <div class="patron-grid technical-scroll-grid startup-grid">
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/syraa.png" alt="Syraa Groups">
                        </div>
                        <div class="patron-info">
                            <h4>Syraa Groups</h4>
                            <p>Technical Partner</p>
                            <div class="social-links">
                                <a href="#" target="_blank"><i class="ri-mail-line"></i></a>
                                <a href="#" target="_blank"><i class="ri-github-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-global-line"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/agastya.png" alt="FewInfos">
                        </div>
                        <div class="patron-info">
                            <h4>FewInfos</h4>
                            <p>Knowledge Partner</p>
                            <div class="social-links">
                                <a href="#" target="_blank"><i class="ri-mail-line"></i></a>
                                <a href="#" target="_blank"><i class="ri-github-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-global-line"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/agastya.png" alt="Thinkloop AI">
                        </div>
                        <div class="patron-info">
                            <h4>Thinkloop AI</h4>
                            <p>AI Innovation Partner</p>
                            <div class="social-links">
                                <a href="#" target="_blank"><i class="ri-mail-line"></i></a>
                                <a href="#" target="_blank"><i class="ri-github-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-global-line"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/agastya.png" alt="SignBridge AI">
                        </div>
                        <div class="patron-info">
                            <h4>SignBridge AI</h4>
                            <p>Accessibility Partner</p>
                            <div class="social-links">
                                <a href="#" target="_blank"><i class="ri-mail-line"></i></a>
                                <a href="#" target="_blank"><i class="ri-github-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-global-line"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="patron-card">
                        <div class="patron-image">
                            <img src="assets/images/prisoltech.png" alt="Prisol Technologies">
                        </div>
                        <div class="patron-info">
                            <h4>Prisol Technologies</h4>
                            <p>Tech Solution Partner</p>
                            <div class="social-links">
                                <a href="#" target="_blank"><i class="ri-mail-line"></i></a>
                                <a href="#" target="_blank"><i class="ri-github-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-linkedin-fill"></i></a>
                                <a href="#" target="_blank"><i class="ri-global-line"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="events" id="events">
        <div class="events-content">
            <h2>Our Events</h2>
            <p class="section-subtitle">Mark Your Calendar for These Exciting Events</p>
            <div class="events-grid">
                <div class="event-card" onclick="window.location.href='day1.php'" style="cursor: pointer;">
                    <div class="event-image">
                        <img src="assets/images/day1.jpg" alt="Day 1 Events">
                        <div class="event-date">Day 1</div>
                    </div>
                    <div class="event-content">
                        <h3>Day 1: Grand Inception</h3>
                        <p>Kickstart the celebrations with an electrifying opening ceremony, followed by mesmerizing
                            performances.</p>
                    </div>
                    <div class="event-footer">
                        <div class="event-location">
                            <i class="ri-map-pin-line"></i>
                            <span>MKCE</span>
                        </div>
                        <span class="event-btn">View Schedule</span>
                    </div>
                </div>

                <div class="event-card" onclick="window.location.href='day2.php'" style="cursor: pointer;">
                    <div class="event-image">
                        <img src="assets/images/day2.webp" alt="Day 2 Events">
                        <div class="event-date">Day 2</div>
                    </div>
                    <div class="event-content">
                        <h3>Day 2: Cultural Extravaganza</h3>
                        <p>Immerse yourself in a day filled with vibrant performances, artistic showcases, and cultural
                            celebrations.</p>
                    </div>
                    <div class="event-footer">
                        <div class="event-location">
                            <i class="ri-map-pin-line"></i>
                            <span>MKCE</span>
                        </div>
                        <span class="event-btn">View Schedule</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="gallery" id="gallery">
        <div class="gallery-content">
            <h2>Past Events Gallery</h2>
            <p class="section-subtitle">Glimpses of Our Journey</p>
            <div class="gallery-wrapper">
                <h3 style="text-align: center; margin-bottom: 20px;">2025 Highlights</h3>
                <div class="gallery-track">
                    <div class="gallery-row">
                        <img src="assets/images/25/gal1.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal2.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal3.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal4.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal5.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal6.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal7.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal8.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal9.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal10.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal11.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal12.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal13.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal14.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal15.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal16.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal17.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal18.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal19.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal20.jpeg" alt="Gallery Image">
                    </div>
                    <div class="gallery-row">
                        <img src="assets/images/25/gal1.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal2.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal3.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal4.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal5.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal6.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal7.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal8.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal9.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal10.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal11.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal12.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal13.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal14.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal15.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal16.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal17.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal18.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal19.jpeg" alt="Gallery Image">
                        <img src="assets/images/25/gal20.jpeg" alt="Gallery Image">
                    </div>
                </div>
                <h3 style="text-align: center; margin: 40px 0 20px;">2024 Highlights</h3>
                <div class="gallery-track reverse">
                    <div class="gallery-row">
                        <img src="assets/images/24/gal1.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal2.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal3.jpg" alt="Gallery Image">
                        <img src="assets/images/24/balaannadance.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal4.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal5.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal6.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal7.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal8.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal9.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal10.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal11.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal12.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal13.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal14.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal15.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal16.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal17.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal18.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal19.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal20.jpg" alt="Gallery Image">
                    </div>
                    <div class="gallery-row">
                        <img src="assets/images/24/gal1.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal2.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal3.jpg" alt="Gallery Image">
                        <img src="assets/images/24/balaannadance.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal4.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal5.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal6.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal7.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal8.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal9.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal10.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal11.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal12.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal13.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal14.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal15.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal16.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal17.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal18.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal19.jpg" alt="Gallery Image">
                        <img src="assets/images/24/gal20.jpg" alt="Gallery Image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact" id="contact">
        <div class="contact-content">
            <h2>Contact Us</h2>
            <p class="section-subtitle">Get in Touch with Us</p>
            <div class="contact-info">
                <a href="https://maps.app.goo.gl/wQ1gC5k1yX5j5Z5x8" target="_blank" class="contact-card">
                    <i class="ri-map-pin-2-line"></i>
                    <h3>Location</h3>
                    <p>MKCE Campus, Karur</p>
                </a>
                <a href="mailto:fineartsclub2k25@gmail.com" class="contact-card">
                    <i class="ri-mail-line"></i>
                    <h3>Email</h3>
                    <p>fineartsclub2k25@gmail.com</p>
                </a>
                <a href="tel:+917373888818" class="contact-card">
                    <i class="ri-phone-line"></i>
                    <h3>Phone</h3>
                    <p>+91 7373888818</p>
                </a>
                <a href="#events" class="contact-card">
                    <i class="ri-time-line"></i>
                    <h3>Event Days</h3>
                    <p>March 13-14, 2026</p>
                </a>
            </div>
        </div>
    </section>
    <footer class="single-line-footer">
        <p>&copy; 2026 Syraa Groups. All Rights Reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/script/script.js"></script>
    <script src="assets/script/assistant.js"></script>
    <script>
        // Check if countdown is working, if not reinitialize
        setTimeout(() => {
            const daysElement = document.getElementById('days');
            if (daysElement && daysElement.textContent === '00') {
                updateCountdown();
            }
        }, 1000);
    </script>
</body>

</html>