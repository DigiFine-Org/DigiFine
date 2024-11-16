<?php

$role = $_SESSION['user']['role'];
function renderLink(string $title, string $link)
{
    $path = rtrim($_SERVER['REQUEST_URI'], "/");
    $active = $path === $link || str_starts_with($path, $link . "?");
    echo "<a href='$link' class=" . ($active ? "active" : "") . ">$title</a>";
}
?>
<div class="sidebar">
    <?php renderLink("Home", "/digifine/dashboard/index.php") ?>
    <?php if ($role === 'officer'): ?>
        <?php renderLink("Check Vehicle Details", "/digifine/dashboard/p-officer/check-vehicle-details/index.php") ?>
        <?php renderLink("Generate E-Ticket", "/digifine/dashboard/p-officer/generate-e-ticket/index.php") ?>
        <?php renderLink("Verify Driver Details", "/digifine/dashboard/p-officer/verify-driver-details/index.php") ?>
        <?php renderLink("Submit Duty", "/digifine/dashboard/p-officer/submit-duty/index.php") ?>
    <?php endif ?>

    <?php if ($role === 'driver'): ?>
        <?php renderLink("My Fines", "/digifine/dashboard/driver/my-fines/index.php") ?>
        <?php renderLink("Gov-Fine List", "/digifine/dashboard/gov-fine-list/index.php") ?>
        <?php renderLink("Payments", "/digifine/dashboard/driver/payments/index.php") ?>
    <?php endif ?>

    <?php if ($role === 'admin'): ?>
        <?php renderLink("Assign OIC", "/digifine/dashboard/admin/assign-oic/index.php") ?>
    <?php endif ?>
</div>