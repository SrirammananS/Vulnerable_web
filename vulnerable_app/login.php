<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check DAS_ID and password (vulnerable to SQL injection)
    $das_id = $_POST['das_id'];
    $password = $_POST['password'];

    // Connect to MySQL
    $conn = new mysqli("localhost", "root", "02091431", "vulnerable_app");

    // SQL injection vulnerability here
    $sql = "SELECT * FROM users WHERE das_id='$das_id' AND password='$password'";

    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $_SESSION['login_user'] = $das_id;
        header("location: home.php");
    } else {
        echo "Invalid DAS_ID or password.";
    }
}
?>