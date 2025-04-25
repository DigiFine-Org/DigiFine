<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services | Digi-Fine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="services.css">
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