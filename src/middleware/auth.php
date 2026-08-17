<?php
session_start();

if (! isset($_SESSION['userId'])) {
    http_response_code(403);

    header("Refresh: 5; login.php");

    echo "<h1>403 - Access Denied</h1>";
    echo "<p>You do not have permission to access this page. Please contact administration.</p>";
    echo "<p>You will be redirected to the login page in 5 seconds...</p>";
    echo '<a href="../views/login.php">Click here if you are not redirected automatically.</a>';

    exit();
}
