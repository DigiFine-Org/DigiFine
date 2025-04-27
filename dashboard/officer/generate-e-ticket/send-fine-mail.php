<?php
require "../../../PHPMailer/mail.php";

function sendFineEmail($recipient, $fineDetails)
{
    
    $subject = "Fine Issued Notification - DigiFine";
    $message = "
    <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .email-container {
                    max-width: 600px;
                    margin: auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                }
                .header {
                    text-align: center;
                    background-color: #007bff;
                    color: white;
                    padding: 10px 0;
                    border-radius: 8px 8px 0 0;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 20px;
                }
                .content h2 {
                    color: #007bff;
                    font-size: 20px;
                }
                .content ul {
                    list-style-type: none;
                    padding: 0;
                }
                .content ul li {
                    margin-bottom: 10px;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #aaa;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <h1>DigiFine - Fine Issued</h1>
                </div>
                <div class='content'>
                    <h2>Dear Driver,</h2>
                    <p>A fine has been issued under your license. Below are the details:</p>
                    <ul>
                        <li><strong>Police ID:</strong> {$fineDetails['police_id']}</li>
                        <li><strong>Issued Date:</strong> {$fineDetails['issued_date']}</li>
                        <li><strong>Issued Time:</strong> {$fineDetails['issued_time']}</li>
                        <li><strong>Offence Type:</strong> {$fineDetails['offence_type']}</li>
                        <li><strong>Location:</strong> {$fineDetails['location']}</li>
                        <li><strong>Fine Amount:</strong> LKR {$fineDetails['fine_amount']}</li>
                        <li><strong>Nature of Offence:</strong> {$fineDetails['nature_of_offence']}</li>
                    </ul>
                    <p>Please log in to your DigiFine account to view and pay the fine.</p>
                    <p>Regards,<br>The DigiFine Team</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " DigiFine. All rights reserved.</p>
                </div>
            </div>
        </body>
    </html>
    ";

    
    return send_mail($recipient, $subject, $message);
}
