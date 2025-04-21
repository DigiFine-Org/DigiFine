<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Contacts - Digi-Fine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== Base Styles ===== */
        :root {
            --primary-color: #1a5f9a;
            --primary-dark: #0d4b7a;
            --primary-light: #2a7bc8;
            --accent-color:rgb(33, 31, 31);
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --dark-gray: #212529;
            --text-color: #333;
            --text-light: #6c757d;
            --transition: all 0.3s ease;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 10px;
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
            color: var(--primary-color);
            transition: var(--transition);
        }

        a:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        /* ===== Main Content ===== */
        main {
            padding-top: 100px;
            min-height: calc(100vh - 150px);
        }

        .contacts {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .contacts h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            padding-bottom: 1rem;
        }

        .contacts h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .contacts > p {
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ===== Contact Cards Grid ===== */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .contact-card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            transition: var(--transition);
            border-top: 4px solid var(--accent-color);
            position: relative;
            overflow: hidden;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .contact-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .contact-card p {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .contact-card .contact-method {
            padding: 0.8rem;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .contact-card .contact-method:hover {
            background-color: rgba(230, 57, 70, 0.1);
        }

        .contact-icon {
            color: var(--accent-color);
            font-size: 1.2rem;
            min-width: 25px;
        }

        .emergency-banner {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 3rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .banner-title{
            color:var(--light-gray)
        }
        
        .emergency-banner i {
            font-size: 2rem;
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 768px) {
            .contacts {
                padding: 2rem 1.5rem;
            }
            
            .contacts h2 {
                font-size: 2rem;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .emergency-banner {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .contacts {
                padding: 2rem 1rem;
            }
            
            .contacts h2 {
                font-size: 1.8rem;
            }
            
            .contact-card {
                padding: 1.5rem;
            }
            
            .contact-card h3 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <main>
        <section class="contacts">
            <h2>Emergency Contacts</h2>
            <p>Immediate assistance is available through these critical services. Save these contacts for emergencies.</p>
            
            <div class="emergency-banner">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <h3 class="banner-title">For Immediate Emergency Assistance</h3>
                    <p>Dial <strong>119</strong> (Police) or <strong>1990</strong> (Ambulance)</p>
                </div>
            </div>

            <div class="contact-grid">
                <div class="contact-card">
                    <h3><i class="fas fa-shield-alt"></i> Police Emergency</h3>
                    <div class="contact-method">
                        <i class="fas fa-phone contact-icon"></i>
                        <a href="tel:119">119</a>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope contact-icon"></i>
                        <a href="mailto:police_emergency@srilanka.gov">police_emergency@srilanka.gov</a>
                    </div>
                   
                </div>

                <div class="contact-card">
                    <h3><i class="fas fa-ambulance"></i> Ambulance Service</h3>
                    <div class="contact-method">
                        <i class="fas fa-phone contact-icon"></i>
                        <a href="tel:1990">1990</a>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope contact-icon"></i>
                        <a href="mailto:ambulance@srilanka.gov">ambulance@srilanka.gov</a>
                    </div>
                   
                </div>

                <div class="contact-card">
                    <h3><i class="fas fa-fire-extinguisher"></i> Fire Brigade</h3>
                    <div class="contact-method">
                        <i class="fas fa-phone contact-icon"></i>
                        <a href="tel:110">110</a>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope contact-icon"></i>
                        <a href="mailto:fire_brigade@srilanka.gov">fire_brigade@srilanka.gov</a>
                    </div>
                   
                </div>

                <div class="contact-card">
                    <h3><i class="fas fa-car-crash"></i> Police Transport Division</h3>
                    <div class="contact-method">
                        <i class="fas fa-phone contact-icon"></i>
                        <a href="tel:+94112595418">0112 595 418</a>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope contact-icon"></i>
                        <a href="mailto:transport_division@srilanka.gov">transport_division@srilanka.gov</a>
                    </div>
                  
                </div>

                <div class="contact-card">
                    <h3><i class="fas fa-road"></i> Traffic Police Headquarters</h3>
                    <div class="contact-method">
                        <i class="fas fa-phone contact-icon"></i>
                        <a href="tel:+94112691222">0112 691 222</a>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope contact-icon"></i>
                        <a href="mailto:traffic_hq@srilanka.gov">traffic_hq@srilanka.gov</a>
                    </div>
                   
                </div>

                <div class="contact-card">
                    <h3><i class="fas fa-globe-asia"></i> Tourist Police</h3>
                    <div class="contact-method">
                        <i class="fas fa-phone contact-icon"></i>
                        <a href="tel:+94112424210">0112 424 210</a>
                    </div>
                    <div class="contact-method">
                        <i class="fas fa-envelope contact-icon"></i>
                        <a href="mailto:tourist_police@srilanka.gov">tourist_police@srilanka.gov</a>
                    </div>
                    
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'footer.php'; ?>
</body>
</html>