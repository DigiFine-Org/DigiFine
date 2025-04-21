<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services | Digi-Fine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== Base Styles ===== */
        :root {
            --primary-color: #1a5f9a;
            --primary-dark: #0d4b7a;
            --primary-light: #2a7bc8;
            --accent-color: #ff9f1c;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #212529;
            --text-color: #333;
            --text-light: #6c757d;
            --transition: all 0.3s ease;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-gray);
        }

        h1, h2, h3, h4 {
            font-weight: 600;
            line-height: 1.2;
            color: var(--primary-dark);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.8rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            border: 2px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        /* ===== Services Section ===== */
        main {
            padding-top: 100px;
        }

        .services-hero {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            color: var(--white);
            padding: 5rem 2rem;
            text-align: center;
        }

        .services-hero h1 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            color: var(--white);
        }

        .services-hero p {
            max-width: 800px;
            margin: 0 auto 2rem;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .services-intro {
            text-align: center;
            margin-bottom: 3rem;
        }

        .services-intro h2 {
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
        }

        .services-intro h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .services-intro p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 1.1rem;
            color: var(--text-light);
        }

        /* Services Tabs */
        .services-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
            gap: 1rem;
        }

        .tab-btn {
            padding: 0.8rem 1.8rem;
            background-color: var(--light-gray);
            border: none;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }

        .tab-btn.active {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .tab-btn:hover:not(.active) {
            background-color: #e0e0e0;
        }

        /* Services Content */
        .services-content {
            display: none;
        }

        .services-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .service-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .service-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            transition: var(--transition);
            border-top: 4px solid var(--accent-color);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .service-card h3 i {
            color: var(--accent-color);
        }

        .service-card ul {
            margin-top: 1.5rem;
            padding-left: 1.5rem;
        }

        .service-card li {
            margin-bottom: 0.8rem;
            position: relative;
            list-style-type: none;
            padding-left: 1.8rem;
        }

        .service-card li::before {
            content: '\f054';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--accent-color);
            position: absolute;
            left: 0;
        }

        .cta-section {
            text-align: center;
            margin-top: 4rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 992px) {
            .service-cards {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .services-hero h1 {
                font-size: 2.2rem;
            }
            
            .services-tabs {
                flex-wrap: wrap;
            }
            
            .service-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .services-hero {
                padding: 3rem 1.5rem;
            }
            
            .services-hero h1 {
                font-size: 1.8rem;
            }
            
            .services-container {
                padding: 2rem 1rem;
            }
            
            .service-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <main>
        

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
    </main>

    <?php include_once 'footer.php'; ?>

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