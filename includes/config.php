<?php
// Start the session at the very beginning
session_start();

// Database connection
$db_conn = mysqli_connect('localhost', 'root', '', 'sms_project');

if (!$db_conn) {
    echo 'Connection Failed';
    exit;
}

// Set the default timezone
date_default_timezone_set('Asia/Kolkata');

// Include any necessary functions
include('functions.php');
?>
