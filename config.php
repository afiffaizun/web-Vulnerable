<?php
// KERENTANAN: Database credentials exposed
$host = "localhost";
$username = "financeapp";
$password = "Finance@2024";
$database = "financedb";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// KERENTANAN: Error display enabled
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>
