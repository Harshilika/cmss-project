<?php
session_start(); // Start the session

include('../includes/config.php');

if (isset($_POST['login'])) {
    // Retrieve and sanitize user inputs
    $email = mysqli_real_escape_string($db_conn, $_POST['email']);
    $pass  = mysqli_real_escape_string($db_conn, $_POST['password']);

    // Hash the password using a stronger algorithm (if applicable)
    $pass_md5 = md5($pass); // Consider switching to password_hash() in the future

    // Query to check user credentials
    $query = mysqli_query($db_conn, "SELECT * FROM `accounts` WHERE `email` = '$email' AND `password` = '$pass_md5'");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_object($query);
        
        // Store session variables
        $_SESSION['login'] = true;
        $_SESSION['session_id'] = uniqid(); // Consider a more secure method for session IDs
        
        $user_type = $user->type;
        $_SESSION['user_type'] = $user_type;
        $_SESSION['user_id'] = $user->id;

        // Store the student's email in the session
        $_SESSION['student_email'] = $user->email; // Assuming the email column is named 'email'

        // Redirect to the appropriate dashboard
        header('Location: ../' . $user_type . '/dashboard.php');
        exit();
    } 
    // Separate check for admin credentials
    else if ($email === 'admin@example.com' && $pass === 'admin@sms') {
        $_SESSION['login'] = true;
        $_SESSION['user_type'] = 'admin'; // Store user type for admin
        $_SESSION['student_email'] = $email; // Store admin email as well
        header('Location: ../admin/dashboard.php');
        exit();
    } 
    else {
        echo 'Invalid Credentials'; // Fixed typo
    }
}
?>
