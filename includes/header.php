<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = null;
$pageStyles = [];
$authRequired = null;
if (isset($pageConfig)) {
    $pageTitle = $pageConfig['title'] ?? null;
    $pageStyles = $pageConfig['styles'] ?? [];
    $authRequired = $pageConfig['authRequired'] ?? null;
}

// check if auth required for current page
$currentUser = $_SESSION['user'] ?? null;
if (!is_null($authRequired)) {
    if (!$currentUser && $authRequired) {
        header("Location: /digifine/login/index.php");
    } else if ($currentUser && !$authRequired) {
        header("Location: /digifine/dashboard/index.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digifine<?php echo $pageTitle ? " | " . $pageTitle : "" ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/digifine/styles/globals.css">
    <link rel="stylesheet" href="/digifine/styles/components.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php
    foreach ($pageStyles as $style) {
        echo "<link rel='stylesheet' href='$style'/>";
    }

    ?>
</head>

<body>