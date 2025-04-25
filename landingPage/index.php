
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digi-Fine | Digital Traffic Fine Payment System</title>
    <link rel="stylesheet" href="landing-page.css">
 
    <link rel="icon" href="../../assets/favicon.ico" type="image/x-icon">
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    
    <main>

    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Digital Traffic Fine Management System</h1>
            <p class="hero-subtitle">Streamlined solutions for efficient fine processing and payment</p>
            <div class="hero-cta">
                <a href="/digifine/login/index.php" class="btn btn-primary btn-large">Pay Fines Online</a>
                <a href="/digifine/landingPage/services.php" class="btn btn-outline btn-large">Our Services</a>
            </div>
        </div>
    </section>
        <section class="about">
        <h2>About DigiFine</h2>
        <p>
                Welcome to <strong>DigiFine</strong>, a pioneering platform designed to revolutionize traffic fine
                management in Sri Lanka.
                Our mission is to simplify the complexities of issuing, managing, and paying traffic fines, while
                fostering a culture of accountability, transparency, and efficiency.
            </p>
            <p>
                At DigiFine, we leverage state-of-the-art technology to create an intuitive and seamless experience for
                police officers, drivers, and administrators alike.
                By digitalizing every aspect of the process, we aim to eliminate cumbersome paperwork, streamline
                operations, and ensure precise record-keeping.
            </p>

        </section>
       

        <!-- Features Section -->
        <section class="features">
            <div class="feature">
                <a href="/digifine/login/index.php">
                    <div class="icon"><img src="../assets/landingPage/icons/fine.png" alt="Fine Icon"></div>
                    <h3>Pay Fines Online</h3>
                    <p>Quickly pay your traffic fines online with our secure payment gateway</p>
                </a>
            </div>
           
            <div class="feature">
                <a href="#safety">
                    <div class="icon"><img src="../assets/landingPage/icons/shield.png" alt="Shield Icon"></div>
                    <h3>Safety & Security</h3>
                    <p>Road safety guidelines and personal security recommendations</p>
                </a>
            </div>
            <div class="feature">
                <a href="/digifine/landingPage/tell-igp/tell-igp.php">
                    <div class="icon"><img src="../assets/landingPage/icons/stats.png" alt="Stats Icon"></div>
                    <h3>Tell IGP</h3>
                    <p>Direct communication with Inspector with General's Office</p>
                </a>
            </div>
        </section>

        <!-- Info Section -->
        <section class="info-section">
            <div class="info-column">
                <h2>Criminal Facts</h2>
                <div class="tabs">
                    <button class="active">Property Crimes</button>
                    <button>Traffic Violations</button>
                    <button>Cyber Crimes</button>
                    <button>Drug Offences</button>
                </div>
                <div class="fact-content">
                    <div class="fact-icon">📊</div>
                    <p>
                        Sri Lanka Police has implemented advanced crime prevention strategies resulting in significant reductions across all categories. Recent data shows an 80% reduction in property crimes through community policing initiatives. Traffic violations have decreased by 65% with the introduction of automated enforcement systems.
                    </p>
                </div>
            </div>
            <div class="info-column">
                <h2>Latest News</h2>
                <div class="news-item">
                    <div class="date">
                        <p>03</p>
                        <span>Jan 2024</span>
                    </div>
                    <div class="news-content">
                        <h3>New Traffic Regulation Policies</h3>
                        <p>The Police Department introduced stricter measures for reckless driving to reduce accidents by 40% this year.</p>
                    </div>
                </div>
                <div class="news-item">
                    <div class="date">
                        <p>06</p>
                        <span>Jan 2024</span>
                    </div>
                    <div class="news-content">
                        <h3>Enhanced Crime Detection Systems</h3>
                        <p>Sri Lanka Police launches AI-powered surveillance systems to combat urban crime rates effectively.</p>
                    </div>
                </div>
            </div>
        </section>

      
        <div class="services-container">
            

            <div class="services-tabs">
                <button class="tab-btn active" data-tab="drivers">For Drivers</button>
                <button class="tab-btn" data-tab="officers">For Police Officers</button>
                <button class="tab-btn" data-tab="oics">For OICs</button>
            </div>

            <div id="drivers" class="services-content active">
                <div class="service-cards">
                    <div class="service-card">
                        <h3><i class="fas fa-money-bill-wave"></i> Fine Payment</h3>
                        <p>Conveniently manage and pay your traffic fines through our secure online platform.</p>
                        <ul>
                            <li>View all outstanding fines in one place</li>
                            <li>Multiple secure payment options</li>
                            <li>Instant payment confirmation</li>
                            <li>Download payment receipts</li>
                        </ul>
                    </div>

                    <div class="service-card">
                        <h3><i class="fas fa-gavel"></i> Dispute Resolution</h3>
                        <p>Challenge unfair fines through our transparent dispute process.</p>
                        <ul>
                            <li>Submit evidence and documentation</li>
                            <li>Track dispute status in real-time</li>
                            <li>Receive official responses digitally</li>
                            <li>Appeal process guidance</li>
                        </ul>
                    </div>

                    <div class="service-card">
                        <h3><i class="fas fa-history"></i> Fine History</h3>
                        <p>Maintain a complete record of all your traffic violations and payments.</p>
                        <ul>
                            <li>Access your complete fine history</li>
                            <li>Filter by date, type, or status</li>
                            <li>Export records for personal use</li>
                            <li>Payment reminders and alerts</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="officers" class="services-content">
                <div class="service-cards">
                    <div class="service-card">
                        <h3><i class="fas fa-ticket-alt"></i> E-Ticketing</h3>
                        <p>Issue digital tickets with all the necessary details in seconds.</p>
                        <ul>
                            <li>Mobile-friendly interface</li>
                            <li>Automatic violation code lookup</li>
                            <li>Vehicle/license verification</li>
                            <li>GPS location tagging</li>
                        </ul>
                    </div>

                    <div class="service-card">
                        <h3><i class="fas fa-clipboard-check"></i> Duty Reporting</h3>
                        <p>Submit comprehensive duty reports with minimal effort.</p>
                        <ul>
                            <li>Pre-filled templates</li>
                            <li>Photo/video evidence upload</li>
                            <li>Digital signatures</li>
                            <li>Automatic timestamping</li>
                        </ul>
                    </div>

                    <div class="service-card">
                        <h3><i class="fas fa-database"></i> Records Management</h3>
                        <p>Maintain accurate, searchable records of all enforcement actions.</p>
                        <ul>
                            <li>Centralized database access</li>
                            <li>Quick search functionality</li>
                            <li>Export reports in multiple formats</li>
                            <li>Offline capability with sync</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="oics" class="services-content">
                <div class="service-cards">
                    <div class="service-card">
                        <h3><i class="fas fa-chart-line"></i> Performance Analytics</h3>
                        <p>Monitor and analyze enforcement activities across your jurisdiction.</p>
                        <ul>
                            <li>Real-time dashboards</li>
                            <li>Customizable reports</li>
                            <li>Trend analysis tools</li>
                            <li>Officer performance metrics</li>
                        </ul>
                    </div>

                    <div class="service-card">
                        <h3><i class="fas fa-balance-scale"></i> Dispute Management</h3>
                        <p>Efficiently review and adjudicate contested fines.</p>
                        <ul>
                            <li>Case prioritization</li>
                            <li>Evidence review portal</li>
                            <li>Decision templates</li>
                            <li>Automated notifications</li>
                        </ul>
                    </div>

                    <div class="service-card">
                        <h3><i class="fas fa-users-cog"></i> Team Administration</h3>
                        <p>Manage your personnel and resources effectively.</p>
                        <ul>
                            <li>Duty roster management</li>
                            <li>Resource allocation tools</li>
                            <li>Approval workflows</li>
                            <li>Incident escalation</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        </section>

  

        <!-- Safety Section -->
        <section class="safety-section" id="safety">
            <h2>Road Safety Tips</h2>
            <div class="safety-tips">
                <div class="tip">
                    <div class="tip-icon">🚗</div>
                    <h3>Speed Limits</h3>
                    <p>Always adhere to posted speed limits - they're calculated for optimal safety.</p>
                </div>
                <div class="tip">
                    <div class="tip-icon">📱</div>
                    <h3>No Phones</h3>
                    <p>Avoid using mobile devices while driving - it's illegal and dangerous.</p>
                </div>
                <div class="tip">
                    <div class="tip-icon">🛑</div>
                    <h3>Stop Signs</h3>
                    <p>Come to a complete stop at all stop signs - rolling stops are violations.</p>
                </div>
                <div class="tip">
                    <div class="tip-icon">🍷</div>
                    <h3>Don't Drink & Drive</h3>
                    <p>Never drive under influence - it's the leading cause of fatal accidents.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'footer.php'; ?>

    <script>
        // Simple animation for features on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const features = document.querySelectorAll('.feature');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            features.forEach(feature => {
                feature.style.opacity = 0;
                feature.style.transform = 'translateY(20px)';
                feature.style.transition = 'all 0.6s ease';
                observer.observe(feature);
            });

            // Tab functionality
            const tabs = document.querySelectorAll('.tabs button');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    // Here you would typically load different content
                });
            });
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const serviceContents = document.querySelectorAll('.services-content');
            
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remove active class from all buttons and contents
                    tabBtns.forEach(b => b.classList.remove('active'));
                    serviceContents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to clicked button
                    btn.classList.add('active');
                    
                    // Show corresponding content
                    const tabId = btn.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
</body>

</html>