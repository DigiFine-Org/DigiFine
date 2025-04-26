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
    <title>File a Complaint | Digi-Fine</title>
    <style>
        /* ===== Base Styles ===== */
        :root {
            --primary-color: #1a5f9a;
            --primary-dark: #0d4b7a;
            --primary-light: #2a7bc8;
            --accent-color: #e63946;
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

        h1, h2, h3 {
            font-weight: 600;
            line-height: 1.2;
            color: var(--primary-dark);
        }

        a {
            text-decoration: none;
            color: var(--primary-color);
        }

        /* ===== Main Layout ===== */
        main {
            padding-top: 100px;
            min-height: calc(100vh - 150px);
        }

        .complaint-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* ===== Form Styles ===== */
        .complaint-form {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2.5rem;
            margin-top: 2rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            position: relative;
            display: inline-block;
        }

        .form-header h1::after {
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

        .form-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-dark);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background-color: var(--light-gray);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 154, 0.1);
            background-color: var(--white);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%231a5f9a' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 16px 12px;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .file-upload {
            position: relative;
            margin-top: 0.5rem;
        }

        .file-upload input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: block;
            padding: 1rem;
            background-color: var(--light-gray);
            border: 2px dashed #ccc;
            border-radius: var(--border-radius);
            text-align: center;
            transition: var(--transition);
        }

        .file-upload-label:hover {
            border-color: var(--primary-color);
            background-color: rgba(26, 95, 154, 0.05);
        }

        .file-upload-label i {
            display: block;
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 1rem;
            background-color: var(--accent-color);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 2rem;
        }

        .btn-submit:hover {
            background-color: #c1121f;
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }

        .btn-submit i {
            margin-left: 0.5rem;
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 768px) {
            .complaint-container {
                padding: 1.5rem;
            }
            
            .complaint-form {
                padding: 1.5rem;
            }
            
            .form-header h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .complaint-container {
                padding: 1rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <main>
        <div class="complaint-container">
            <div class="complaint-form">
                <div class="form-header">
                    <h1>File a Complaint</h1>
                    <p>Submit your concerns directly to the Inspector General of Police</p>
                </div>

                <form action="tell-igp-process.php" method="post" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="district">Your District</label>
                            <input type="text" id="district" name="district" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="police_station">Nearest Police Station</label>
                            <input type="text" id="police_station" name="police_station" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="complaint">Complaint Category</label>
                        <select id="complaint" name="complaint" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php foreach ($complaints as $complaint): ?>
                                <option value="<?php echo htmlspecialchars($complaint['complaint']) ?>">
                                    <?php echo htmlspecialchars($complaint['complaint']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="nic">NIC Number</label>
                            <input type="text" id="nic" name="nic" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="complaint_subject">Complaint Subject</label>
                        <input type="text" id="complaint_subject" name="complaint_subject" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="complaint_text">Complaint Details</label>
                        <textarea id="complaint_text" name="complaint_text" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Attachment</label>
                        <p class="help-text">If you have any document or image related to the complaint, please attach it below.</p>
                        <div class="file-upload">
                            <input type="file" id="fileInput" name="fileInput">
                            <label for="fileInput" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click to upload file</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" name="submit" class="btn-submit">
                        Submit Complaint <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                <?php if(isset($_SESSION['status'])): ?>
                    Swal.fire({
                        title: "Thank You",
                        text: "<?php echo $_SESSION['status']; ?>",
                        icon: "success",
                        confirmButtonColor: "#e63946"
                    });
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                // File input change handler
                $('#fileInput').change(function() {
                    if (this.files.length > 0) {
                        $('.file-upload-label span').text(this.files[0].name);
                    } else {
                        $('.file-upload-label span').text('Click to upload file');
                    }
                });
            });
        </script>
    </main>

    <?php include_once '../footer.php'; ?>
</body>
</html>