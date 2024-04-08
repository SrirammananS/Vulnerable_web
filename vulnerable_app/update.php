<?php
$conn = new mysqli("localhost", "root", "02091431", "vulnerable_app");
$id = $_POST['id']; // Vulnerable to SQL injection
$name = $_POST['name']; // Vulnerable to SQL injection
$sql = "UPDATE data SET name='$name' WHERE id='$id'";
$conn->query($sql);
header("Location: home.php");
?>
