<?php

session_start();

if (isset($_SESSION["userId"])) {
    session_destroy();
    session_unset();
    header("Location: login.php");
}
