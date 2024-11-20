<?php

$user = $_SESSION['user'];

function renderLink(string $title, string $link)
{
    $path = rtrim($_SERVER['REQUEST_URI'], "/");
    $active = $path === $link || str_starts_with($path, $link . "?");
    echo "<a href='$link' class=" . ($active ? "active" : "") . ">$title</a>";
}
?>
<div class="sidebar">
    <?php renderLink("Home", "/digifine/dashboard/index.php") ?>
    <?php if ($user['role'] === 'officer'): ?>
        <?php if ($user['is_oic'] === "1"): ?>
            <?php renderLink("Reported Unfair Fines", "/digifine/dashboard/admin/unfair-fines/index.php") ?>
        <?php else: ?>
            <?php renderLink("Check Vehicle Details", "/digifine/dashboard/p-officer/check-vehicle-details/index.php") ?>
            <?php renderLink("Generate E-Ticket", "/digifine/dashboard/p-officer/generate-e-ticket/index.php") ?>
            <?php renderLink("Verify Driver Details", "/digifine/dashboard/p-officer/verify-driver-details/index.php") ?>
            <?php renderLink("Submit Duty", "/digifine/dashboard/p-officer/submit-duty/index.php") ?>
        <?php endif; ?>
    <?php endif ?>

    <?php if ($user['role'] === 'driver'): ?>
        <?php renderLink("My Fines", "/digifine/dashboard/driver/my-fines/index.php") ?>
        <?php renderLink("Gov-Offence List", "/digifine/dashboard/gov-fine-list/index.php") ?>
        <?php renderLink("Payments", "/digifine/dashboard/driver/payments/index.php") ?>
    <?php endif ?>

    <?php if ($user['role'] === 'admin'): ?>
        <?php renderLink("Assign OIC", "/digifine/dashboard/admin/assign-oic/index.php") ?>
        <?php renderLink("Offence Management", "/digifine/dashboard/admin/Offence-management/index.php") ?>
    <?php endif ?>
</div>