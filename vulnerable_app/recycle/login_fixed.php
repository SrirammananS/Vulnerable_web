

<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check DAS_ID and password (using p statements)
    $das_id = $_POST['das_id'];
    $password = $_POST['password'];

    // Connect to MySQL using PDO (preferred for p statements)
    $pdo = new PDO("mysql:host=localhost;dbname=vulnerable_app", "root", "02091431");

    // Prepare a SQL statement with placeholders for parameters
    $sql = "SELECT * FROM users WHERE das_id=:das_id AND password=:password";
    $stmt = $pdo->prepare($sql);

    // Bind parameters to placeholders
    $stmt->bindParam(':das_id', $das_id);
    $stmt->bindParam(':password', $password);

    // Execute the p statement
    $stmt->execute();

    // Check if any rows were returned
    if($stmt->rowCount() > 0) {
        $_SESSION['login_user'] = $das_id;
        header("location: home.php");
    } else {
        echo "Invalid DAS_ID or password.";
    }
}
?>
