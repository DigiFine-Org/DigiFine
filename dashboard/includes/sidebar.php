<?php

$sidebar_user = $_SESSION['user'];

function renderLink(string $title, string $link, string $icon)
{
    $path = rtrim($_SERVER['REQUEST_URI'], "/");
    $active = $path === $link || str_starts_with($path, $link . "?");
    echo "<a href='$link' class='" . ($active ? "active" : "") . "'>
            <span class='material-icons'>$icon</span>
            <span>$title</span>
          </a>";
}
?>
<div class="sidebar">
    <?php if ($sidebar_user['role'] === 'oic'): ?>
        <!-- oic links -->
        <?php renderLink("Home", "/digifine/dashboard/oic/index.php", "home") ?>
        <?php renderLink("Fines", "/digifine/dashboard/oic/fine-management/index.php", "money") ?>
        <?php renderLink("Station Officers", "/digifine/dashboard/oic/officer-management/index.php", "group") ?>
        <?php renderLink("Duty Submissions", "/digifine/dashboard/oic/duty-submissions/index.php", "description") ?>
        <?php renderLink("Assign Duties", "/digifine/dashboard/oic/assign-duties/index.php", "assignment") ?>
    <?php endif ?>

    <?php if ($sidebar_user['role'] === 'officer'): ?>
        <?php renderLink("Home", "/digifine/dashboard/officer/index.php", "home") ?>
        <?php renderLink("Check Vehicle Details", "/digifine/dashboard/officer/check-vehicle-details/index.php", "directions_car") ?>
        <?php renderLink("Generate E-Ticket", "/digifine/dashboard/officer/generate-e-ticket/index.php", "receipt") ?>
        <?php renderLink("Verify Driver Details", "/digifine/dashboard/officer/verify-driver-details/index.php", "person_search") ?>
        <?php renderLink("Submit Duty", "/digifine/dashboard/officer/submit-duty/index.php", "send") ?>
    <?php endif ?>

    <?php if ($sidebar_user['role'] === 'driver'): ?>
        <?php renderLink("Home", "/digifine/dashboard/driver/index.php", "home") ?>
        <?php renderLink("My Fines", "/digifine/dashboard/driver/my-fines/index.php", "money_off") ?>
        <?php renderLink("Gov-Offence List", "/digifine/dashboard/gov-fine-list/index.php", "list_alt") ?>
        <?php renderLink("Payments", "/digifine/dashboard/driver/payments/index.php", "credit_card") ?>
    <?php endif ?>

    <?php if ($sidebar_user['role'] === 'admin'): ?>
        <?php renderLink("Home", "/digifine/dashboard/admin/index.php", "home") ?>
        <?php renderLink("Offence Management", "/digifine/dashboard/admin/Offence-management/index.php", "gavel") ?>
        <?php renderLink("Fine Management", "/digifine/dashboard/admin/fine-management/index.php", "money") ?>
        <?php renderLink("Assign OIC", "/digifine/dashboard/admin/assign-oic/index.php", "person_add") ?>
        <?php renderLink("Driver Management", "/digifine/dashboard/admin/driver-management/index.php", "person") ?>
        <?php renderLink("Vehicle Management", "/digifine/dashboard/admin/vehicle-management/index.php", "directions_car") ?>
        <?php renderLink("Publish Announcements", "/digifine/dashboard/admin/announcements/index.php", "campaign") ?>
        <?php renderLink("Police Officer Management", "/digifine/dashboard/admin/officer-management/index.php", "badge") ?>
    <?php endif ?>
</div>
