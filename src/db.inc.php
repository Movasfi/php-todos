<?php

$servername = "127.0.0.1";
$username   = "root";
$password   = '';
$dbname     = "todos";

try {
<<<<<<< Updated upstream
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
=======
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
>>>>>>> Stashed changes
}
