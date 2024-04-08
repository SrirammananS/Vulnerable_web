<?php
session_start();

//Check if user is logged in and is admin
//if(!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
if(!isset($_SESSION['login_user'])) {
        header("location: index.php");
        exit; // Ensure script stops execution after redirect
    }


// Database connection
$conn = new mysqli("localhost", "root", "02091431", "vulnerable_app");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add New Asset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_asset'])) {
    $UDID = $_POST['UDID'];
    $mobile_model = $_POST['mobile_model'];
    $OS_version = $_POST['OS_version'];
    $jail_status = $_POST['jail_status'];
    $phone_lock_pin = $_POST['phone_lock_pin'];
    $colour = $_POST['colour'];
    $remarks = $_POST['remarks'];

    $sql = "INSERT INTO asset (UDID, Mobile_Model, OS_Version, Jail_Status, Phone_Lock_Pin, Colour, Remarks) 
            VALUES ('$UDID', '$mobile_model', '$OS_version', '$jail_status', '$phone_lock_pin', '$colour', '$remarks')";

    if ($conn->query($sql) === TRUE) {
        echo "Asset added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Edit Asset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_asset'])) {
    $asset_id = $_POST['asset_id'];
    $UDID = $_POST['UDID'];
    $mobile_model = $_POST['mobile_model'];
    $OS_version = $_POST['OS_version'];
    $jail_status = $_POST['jail_status'];
    $phone_lock_pin = $_POST['phone_lock_pin'];
    $colour = $_POST['colour'];
    $remarks = $_POST['remarks'];

    $sql = "UPDATE asset 
            SET UDID='$UDID', Mobile_Model='$mobile_model', OS_Version='$OS_version', Jail_Status='$jail_status', 
                Phone_Lock_Pin='$phone_lock_pin', Colour='$colour', Remarks='$remarks' 
            WHERE id='$asset_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Asset updated successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete Asset
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_asset'])) {
    $asset_id = $_POST['asset_id'];

    $sql = "DELETE FROM asset WHERE id='$asset_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Asset deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Asset Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Asset Management</h2>
    <hr>
    <h3>Add New Asset</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="UDID">UDID:</label>
            <input type="text" class="form-control" id="UDID" name="UDID" required>
        </div>
        <div class="form-group">
            <label for="mobile_model">Mobile Model:</label>
            <input type="text" class="form-control" id="mobile_model" name="mobile_model" required>
        </div>
        <div class="form-group">
            <label for="OS_version">OS Version:</label>
            <input type="text" class="form-control" id="OS_version" name="OS_version" required>
        </div>
        <div class="form-group">
            <label for="jail_status">Jail Status:</label>
            <select class="form-control" id="jail_status" name="jail_status" required>
                <option value="Jailed">Jailed</option>
                <option value="Nonrooted">Nonrooted</option>
                <option value="Jailbroken">Jailbroken</option>
                <option value="Rooted">Rooted</option>
                <option value="Unknown">Unknown</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone_lock_pin">Phone Lock Pin:</label>
            <input type="text" class="form-control" id="phone_lock_pin" name="phone_lock_pin" required>
        </div>
        <div class="form-group">
            <label for="colour">Colour:</label>
            <input type="text" class="form-control" id="colour" name="colour">
        </div>
        <div class="form-group">
            <label for="remarks">Remarks:</label>
            <textarea class="form-control" id="remarks" name="remarks"></textarea>
        </div>
        <button type="submit" name="add_asset" class="btn btn-primary">Add Asset</button>
    </form>
    <hr>
    <h3>Edit/Delete Assets</h3>
    <table class="table">
        <thead>
        <tr>
            <th>UDID</th>
            <th>Mobile Model</th>
            <th>OS Version</th>
            <th>Jail Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Fetch assets from database
        $sql = "SELECT * FROM asset";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['UDID']."</td>";
                echo "<td>".$row['Mobile_Model']."</td>";
                echo "<td>".$row['OS_Version']."</td>";
                echo "<td>".$row['Jail_Status']."</td>";
                echo "<td>
                                <form action='edit_asset.php' method='post'>
                                    <input type='hidden' name='asset_id' value='".$row['id']."'>
                                    <button type='submit' name='edit_asset' class='btn btn-info'>Edit</button>
                                </form>
                                <form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post'>
                                    <input type='hidden' name='asset_id' value='".$row['id']."'>
                                    <button type='submit' name='delete_asset' class='btn btn-danger'>Delete</button>
                                </form>
                            </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No assets found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

