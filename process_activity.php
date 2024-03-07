<?php
// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $activityId = $_POST['activityId'];
    $activityName = $_POST['activityName'];
    $place = $_POST['place'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $association = $_POST['association'];

    // Handle uploaded images
    $imagePaths = [];
    if (isset($_FILES['images'])) {
        $fileCount = count($_FILES['images']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES['images']['name'][$i];
            $tempFilePath = $_FILES['images']['tmp_name'][$i];
            $targetFilePath = 'uploads/' . $fileName;

            // Move the uploaded file to the destination folder
            move_uploaded_file($tempFilePath, $targetFilePath);

            // Store the image path in the array
            $imagePaths[] = $targetFilePath;
        }
    }

    // Store the image paths in the database
    $imagePathsString = implode(',', $imagePaths);

    // Establish database connection
    $connection = mysqli_connect("localhost", "root", "", "project");

    // Check the connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the activity ID already exists
    $query = "SELECT * FROM activity WHERE activity_id = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $activityId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo '<script>alert("Activity record already exists"); window.location.href = "add_activity.php";</script>';
    } else {
        // Prepare and execute the SQL query
        $query = "INSERT INTO activity (activity_id, activity_name, description, image_path, date, place, association_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $activityId, $activityName, $description, $imagePathsString, $date, $place, $association);

        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Activity added successfully"); window.location.href = "add_activity.php";</script>';
        } else {
            echo '<script>alert("Error: ' . mysqli_error($connection) . '"); window.location.href = "add_activity.php";</script>';
        }
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
