<?php
include_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Get form data
        $udid = $_POST['udid'];
        $received = isset($_POST['received']) ? $_POST['received'] : '';
        $submitted = isset($_POST['submitted']) ? $_POST['submitted'] : '';
        $received_from = isset($_POST['received_from']) ? $_POST['received_from'] : '';
        $received_on = isset($_POST['received_on']) ? $_POST['received_on'] : '';
        $submitted_to = isset($_POST['submitted_to']) ? $_POST['submitted_to'] : '';
        $submitted_on = isset($_POST['submitted_on']) ? $_POST['submitted_on'] : '';

        // Fetch additional data from asset table based on UDID
        $asset_query = "SELECT Mobile_Model, OS_Version, Jail_Status FROM asset WHERE UDID = '$udid'";
        $asset_result = $conn->query($asset_query);
        if ($asset_result->num_rows > 0) {
            $asset_row = $asset_result->fetch_assoc();
            $mobile_model = $asset_row['Mobile_Model'];
            $os_version = $asset_row['OS_Version'];
            $jail_status = $asset_row['Jail_Status'];

            // Insert data into entry table
            $insert_query = "INSERT INTO entry (UDID, Mobile_Model, OS_Version, Jail_Status, Received_From, Received_On, Submission_To, Submission_On) 
                             VALUES ('$udid', '$mobile_model', '$os_version', '$jail_status', '$received_from', '$received_on', '$submitted_to', '$submitted_on')";
            if ($conn->query($insert_query) === TRUE) {
                $alert_type = "success";
                $alert_message = "New entry added successfully.";
            } else {
                $alert_type = "danger";
                $alert_message = "Error: " . $insert_query . "<br>" . $conn->error;
            }
        } else {
            $alert_type = "danger";
            $alert_message = "Error: No asset found for UDID: $udid";
        }


    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Entry</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Entry</h2>
    <?php if(isset($alert_type) && isset($alert_message)): ?>
        <div class="alert alert-<?php echo $alert_type; ?>" role="alert">
            <?php echo $alert_message; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="udid">Select UDID:</label>
            <select class="form-control" id="udid" name="udid">
                <?php
                // Fetch data from asset table
                $sql = "SELECT UDID, Mobile_Model, OS_Version, Jail_Status FROM asset";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['UDID'] . "'>" . $row['UDID'] . " - " . $row['Mobile_Model'] . " - " . $row['OS_Version'] . " - " . $row['Jail_Status'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Choose:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="received" name="received" value="received">
                <label class="form-check-label" for="received">Received</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="submitted" name="submitted" value="submitted">
                <label class="form-check-label" for="submitted">Submitted</label>
            </div>
        </div>
        <div class="form-group" id="received_fields" style="display: none;">
            <label for="received_from">Received From:</label>
            <input type="text" class="form-control" id="received_from" name="received_from">
            <label for="received_on">Received On:</label>
            <input type="datetime-local" class="form-control" id="received_on" name="received_on">
        </div>
        <div class="form-group" id="submitted_fields" style="display: none;">
            <label for="submitted_to">Submitted To:</label>
            <input type="text" class="form-control" id="submitted_to" name="submitted_to">
            <label for="submitted_on">Submitted On:</label>
            <input type="datetime-local" class="form-control" id="submitted_on" name="submitted_on">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Show/hide fields based on user selection
    document.getElementById("received").addEventListener("change", function() {
        if(this.checked) {
            document.getElementById("received_fields").style.display = "block";
            document.getElementById("submitted_fields").style.display = "none";
        } else {
            document.getElementById("received_fields").style.display = "none";
        }
    });

    document.getElementById("submitted").addEventListener("change", function() {
        if(this.checked) {
            document.getElementById("submitted_fields").style.display = "block";
            document.getElementById("received_fields").style.display = "none";
        } else {
            document.getElementById("submitted_fields").style.display = "none";
        }
    });
</script>
</body>
</html>
