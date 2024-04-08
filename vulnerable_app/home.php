<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['login_user'])) {
    header("location: index.php");
    exit;
}

// Check if the logged-in user is an admin
$is_admin = false; // Change this based on your user role implementation
if ($is_admin) {
    $asset_management_button = '<a href="asset_management.php" class="waves-effect waves-light btn">Asset Management</a>';
    $add_button = '<a href="add.php" class="waves-effect waves-light btn green">Add Asset</a>';
} else {
    $asset_management_button = '';
    $add_button = '<a href="add.php" class="waves-effect waves-light btn green">Add Asset</a>';
}

include_once "db_connection.php";

$search_query = "";

// Check if a search query has been submitted
if(isset($_POST['search'])) {
    $search_query = $_POST['search_query'];
}

// Fetch data from the asset table based on the search query
$sql = "SELECT * FROM asset WHERE UDID LIKE '%$search_query%' OR Mobile_Model LIKE '%$search_query%' OR OS_Version LIKE '%$search_query%' OR Jail_Status LIKE '%$search_query%' OR Phone_Lock_Pin LIKE '%$search_query%' OR Colour LIKE '%$search_query%' OR Remarks LIKE '%$search_query%' OR Creation_Date LIKE '%$search_query%' OR Last_Update_Date LIKE '%$search_query%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Search</title>
    <!-- Materialize CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Custom CSS -->
    <style>
        .container {
            margin-top: 50px;
        }
        .footer {
            text-align: center;
            padding: 10px 0; /* Adjust the padding as needed for spacing */
            background-color: #ff9800; /* Orange color */
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 999; /* Ensure the footer is on top of other content */
        }



    </style>
</head>
<body>
<!-- Navbar -->
<nav>
    <div class="nav-wrapper">
        <a href="#" class="brand-logo">Vulnerable App</a>
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="#">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><?php echo $asset_management_button; ?></li>
            <li><?php echo $add_button; ?></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h2>Asset Search</h2>
    <form method="post">
        <div class="input-field">
            <input type="text" id="search_query" name="search_query" class="validate">
            <label for="search_query">Search</label>
        </div>
        <button class="btn waves-effect waves-light" type="submit" name="search">Search</button>
    </form>
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="highlight">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>UDID</th>
                    <th>Mobile Model</th>
                    <th>OS Version</th>
                    <th>Jail Status</th>
                    <th>Phone Lock Pin</th>
                    <th>Colour</th>
                    <th>Remarks</th>
                    <th>Creation Date</th>
                    <th>Last Update Date</th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['UDID']; ?></td>
                        <td><?php echo $row['Mobile_Model']; ?></td>
                        <td><?php echo $row['OS_Version']; ?></td>
                        <td><?php echo $row['Jail_Status']; ?></td>
                        <td><?php echo $row['Phone_Lock_Pin']; ?></td>
                        <td><?php echo $row['Colour']; ?></td>
                        <td><?php echo $row['Remarks']; ?></td>
                        <td><?php echo $row['Creation_Date']; ?></td>
                        <td><?php echo $row['Last_Update_Date']; ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="card-panel orange lighten-4">No results found.</div>
    <?php endif; ?>
</div>



<!-- Materialize JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script>
    M.AutoInit();
</script>
</body>
</html>
