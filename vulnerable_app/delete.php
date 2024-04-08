<?php
session_start();
if(!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit; // Ensure script stops execution after redirect
}

$conn = new mysqli("localhost", "root", "02091431", "vulnerable_app");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete Data
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Vulnerable to SQL injection
    $sql = "DELETE FROM data WHERE id=$id";
    $result = $conn->query($sql);
    if ($result === TRUE) {
        header("Location: home.php"); // Redirect to hello.php after deleting data
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
