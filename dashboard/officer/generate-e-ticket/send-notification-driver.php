<?php


$notificationTitle = "New Fine Issued";
$notificationMessage = "A fine of lkr {$fine_amount} has been issued for " . ($offence_type === "court" ? "a court case" : "an offence") . " at {$location}. Please check your fines section for details. [FINE_ID:{$fine_id}]";


notify_driver($driver_id, $notificationTitle, $notificationMessage, "fine_system");