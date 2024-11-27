<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Contacts - Digi-Fine</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <main>
        <section class="contacts">
            <h2>Emergency Contacts</h2>
            <p>In case of emergencies, you can reach out to the following departments:</p>
            <div class="contact-card">
                <h3>Police Emergency</h3>
                <p>Phone: 119</p>
                <p>Email: <a href="mailto:police_emergency@srilanka.gov">police_emergency@srilanka.gov</a></p>
            </div>
            <div class="contact-card">
                <h3>Ambulance Service</h3>
                <p>Phone: 1990</p>
                <p>Email: <a href="mailto:ambulance@srilanka.gov">ambulance@srilanka.gov</a></p>
            </div>
            <div class="contact-card">
                <h3>Fire Brigade</h3>
                <p>Phone: 110</p>
                <p>Email: <a href="mailto:fire_brigade@srilanka.gov">fire_brigade@srilanka.gov</a></p>
            </div>
            <div class="contact-card">
                <h3>Traffic Division</h3>
                <p>Phone: 112-123-456</p>
                <p>Email: <a href="mailto:traffic_division@srilanka.gov">traffic_division@srilanka.gov</a></p>
            </div>
        </section>
    </main>

    <?php include_once 'footer.php'; ?>
</body>

</html>
