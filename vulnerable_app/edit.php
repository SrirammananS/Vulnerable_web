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

// Get ID of the record to be edited
$id = $_GET['id'];

// Fetch the existing data from the database
$sql = "SELECT * FROM data WHERE id=$id";
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Error: Record not found";
    exit; // Exit if record not found
}

// Update Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['update'])) {
        $name = $_POST['name'];

        // Vulnerable to SQL injection
        $sql = "UPDATE data SET name='$name' WHERE id=$id";
        $result = $conn->query($sql);
        if ($result === TRUE) {
            echo "Record updated successfully";
            header("location: home.php"); // Redirect to hello.php after update
            exit; // Ensure script stops execution after redirect
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable App - Edit Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Edit Data</h2>

        <!-- Edit Data Form -->
        <form class="mt-3" action="" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
