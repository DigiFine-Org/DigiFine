<?php

session_start();
require_once "../../db/connect.php";

$sql = "SELECT id, complaint from complaint_category";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $complaints = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $complaints = [];
}

$conn->close();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digi-Fine | Pay Fines Online</title>
    <link rel="stylesheet" href="../landing-page.css">
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="../../../../styles/components.css">
    <link rel="stylesheet" href="../../../dashboard/dashboard.css">

</head>

<body>
    <?php
    include_once '../navbar.php';
    ?>

    <main>
        <section class="chief-message">
            <div class="message-content">
                <div class="container">
                    <h1>Tell IGP</h1>
                    <form action="tell-igp-process.php" method="post">
                        <div class="field">
                            <label for="">Your District:</label>
                            <input type="text" class="input" name="district" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Nearest Police Station:</label>
                            <input type="text" class="input" name="police_station" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Complaint Category:</label>
                            <select name="complaint" id="complaint" class="input">
                                <option value="">Select Category</option>
                                <?php foreach ($complaints as $complaint): ?>
                                    <option value="<?php echo htmlspecialchars($complaint['complaint']) ?>">
                                        <?php echo htmlspecialchars($complaint['complaint']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="">Your Name:</label>
                            <input type="text" class="input" name="name" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Address:</label>
                            <input type="text" class="input" name="address" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">NIC Number:</label>
                            <input type="text" class="input" name="nic" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Contact Number:</label>
                            <input type="text" class="input" name="contact_number" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Email Address:</label>
                            <input type="email" class="input" name="email" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Complaint Subject:</label>
                            <input type="text" class="input" name="complaint_subject" placeholder="" required>
                        </div>
                        <div class="field">
                            <label for="">Complaint:</label>
                            <textarea name="complaint_text" id="complaint_text"></textarea>
                        </div>
                        <div class="field">
                            <label for="fileInput">Attachment (If you have any document or image related to the
                                complaint,
                                Please attach to this complaint.)
                                :</label>
                            <input type="file" class="input" name="fileInput" required>
                        </div>
                        <button type="submit" name="submit">Submit</button>



                    </form>
                </div>
            </div>
        </section>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            var messageText = "<?= $_SESSION['status'] ?? ''; ?>"
            if (messageText != '') {
                Swal.fire({
                    title: "Thankyou",
                    text: messageText,
                    icon: "success"
                });
                <?php unset($_SESSION['status']) ?>
            }

        </script>
    </main>

    <?php
    include_once '../footer.php';
    ?>

</body>

</html>