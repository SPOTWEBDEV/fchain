<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// error_reporting(E_ALL);

// Don't show errors in browser
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);

// Enable error logging
ini_set('log_errors', 1);

define("HOST", "localhost");

$isLocalhost = ($_SERVER['HTTP_HOST'] === 'localhost');

if ($isLocalhost) {
    // Local
    define("USER", "root");
    define("PASSWORD", "");
    define("DATABASE", "fchain");
    $domain = "http://localhost/fchain/";
} else {
    // Live
    define("USER", "boostkor_db");
    define("PASSWORD", "boostkor_db");
    define("DATABASE", "boostkor_db");
    $domain = "https://boostkore.com/";
}

// Object-oriented mysqli connection
$connection = new mysqli(HOST, USER, PASSWORD, DATABASE);

if ($connection->connect_error) {
    die("Database connection failed: " . $connection->connect_error);
}

// Site config
$sitename = "MetaStake";
$siteemail = "support@metastake.com";
$sitephone = "+1 234 567 890";

session_start();


