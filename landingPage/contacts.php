<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Contacts - Digi-Fine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="contacts.css">
   
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