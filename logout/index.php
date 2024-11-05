<?php

session_start();
session_unset();
session_destroy();

header("Location: /digifine/login/index.php");
exit();

