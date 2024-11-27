<?php

$sidebar_user = $_SESSION['user'];

function renderLink(string $title, string $link)
{
    $path = rtrim($_SERVER['REQUEST_URI'], "/");
    $active = $path === $link || str_starts_with($path, $link . "?");
    echo "<a href='$link' class=" . ($active ? "active" : "") . ">$title</a>";
}
?>
<div class="sidebar">
    <?php if ($sidebar_user['role'] === 'oic'): ?>
        <!-- oic links -->
        <?php renderLink("Home", "/digifine/dashboard/oic/index.php") ?>
        <?php renderLink("Fines", "/digifine/dashboard/oic/fine-management/index.php") ?>
        <?php renderLink("Station Officers", "/digifine/dashboard/oic/officer-management/index.php") ?>
        <?php renderLink("Duty Submissions", "/digifine/dashboard/oic/duty-submissions/index.php") ?>
        <?php renderLink("Assign Duties", "/digifine/dashboard/oic/assign-duties/index.php") ?>
    <?php endif ?>

    <?php if ($sidebar_user['role'] === 'officer'): ?>
        <?php renderLink("Home", "/digifine/dashboard/officer/index.php") ?>
        <?php renderLink("Check Vehicle Details", "/digifine/dashboard/officer/check-vehicle-details/index.php") ?>
        <?php renderLink("Generate E-Ticket", "/digifine/dashboard/officer/generate-e-ticket/index.php") ?>
        <?php renderLink("Verify Driver Details", "/digifine/dashboard/officer/verify-driver-details/index.php") ?>
        <?php renderLink("Submit Duty", "/digifine/dashboard/officer/submit-duty/index.php") ?>
    <?php endif ?>

    <?php if ($sidebar_user['role'] === 'driver'): ?>
        <?php renderLink("Home", "/digifine/dashboard/driver/index.php") ?>
        <?php renderLink("My Fines", "/digifine/dashboard/driver/my-fines/index.php") ?>
        <?php renderLink("Gov-Offence List", "/digifine/dashboard/gov-fine-list/index.php") ?>
        <?php renderLink("Payments", "/digifine/dashboard/driver/payments/index.php") ?>
    <?php endif ?>

    <?php if ($sidebar_user['role'] === 'admin'): ?>
        <?php renderLink("Home", "/digifine/dashboard/admin/index.php") ?>
        <?php renderLink("Offence Management", "/digifine/dashboard/admin/Offence-management/index.php") ?>
        <?php renderLink("Fine Management", "/digifine/dashboard/admin/fine-management/index.php") ?>
        <?php renderLink("Assign OIC", "/digifine/dashboard/admin/assign-oic/index.php") ?>
        <?php renderLink("Driver Management", "/digifine/dashboard/admin/driver-management/index.php") ?>
        <?php renderLink("Vehicle Management", "/digifine/dashboard/admin/vehicle-management/index.php") ?>
        <?php renderLink("Publish Announcements", "/digifine/dashboard/admin/announcements/index.php") ?>
        <?php renderLink("Police Officer Management", "/digifine/dashboard/admin/officer-management/index.php") ?>
    <?php endif ?>
</div>