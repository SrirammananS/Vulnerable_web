<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "02091431", "vulnerable_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registration form submitted

    // Sanitize user inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $das_id = mysqli_real_escape_string($conn, $_POST['das_id']);

    // Check if DAS_ID already exists
    $check_query = "SELECT * FROM users WHERE das_id='$das_id'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "DAS ID already exists. Please choose a different one.";
    } else {
        // Set default role
        $role = 'normal'; // Default role for new users

        // Insert new user into the database with default role and DAS ID
        $insert_query = "INSERT INTO users (username, password, role, das_id) VALUES ('$username', '$password', '$role', '$das_id')";
        if ($conn->query($insert_query) === TRUE) {
            // Registration successful, set success message
            $_SESSION['message'] = "Registration successful. You can now login.";
        } else {
            $_SESSION['message'] = "Error: " . $conn->error;
        }
    }
    header("location: index.html");
    exit;
}
?>
