<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch associations for dropdown list
$associationSql = "SELECT association_id, association_name FROM association";
$associationResult = mysqli_query($conn, $associationSql);

// Fetch activities based on search parameters
$searchAssociationId = isset($_GET['association_id']) ? $_GET['association_id'] : '';
$searchActivityId = isset($_GET['activity_id']) ? $_GET['activity_id'] : '';

$sql = "SELECT a.*, assoc.association_name FROM activity a 
        INNER JOIN association assoc ON a.association_id = assoc.association_id";

if (!empty($searchAssociationId)) {
    $sql .= " WHERE a.association_id = '$searchAssociationId'";
} elseif (!empty($searchActivityId)) {
    $sql .= " WHERE a.activity_id = '$searchActivityId'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Activity</title>
    <style>
        
        /* Styles for search bar */
        
        body {
            background-image: url('image/image60.jpg'); /* Replace with your background image path */
}
        h1 {
    text-align: center;
    margin-top: 20px;
}
       
        .search-container {
            margin-bottom: 20px;
        }

        .search-container input[type=text] {
            padding: 10px;
            width: 200px;
        }

        .search-container select {
            padding: 10px;
        }

        .search-container input[type=submit] {
            padding: 10px;
        }

        /* Rest of the CSS styles */
        .activity-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            background-color: #fff; /* White background */
            padding: 10px;
        }

        .activity-details {
        flex-grow: 1;
        margin-left: 50px; /* Adjust the margin as needed */
        }


        .activity-details h2 {
            margin-top: 0;
            text-decoration: underline;
            font-weight: bold;
        }

        .activity-details p {
            margin: 0;
        }

        .activity-description {
            background-color: #f2f2f2;
            padding: 10px;
            margin-top: 10px;
        }

        .activity-images {
            width: 200px;
        }

        .activity-images img {
            max-width: 100%;
            height: auto;
            cursor: pointer;
        }

        /* Enlarged Image Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 30px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 800px;
        }

        .modal-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: auto;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .data-table th {
            background-color: #fff;
            font-weight: bold;
        }
        .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        padding: 10px;
        background: linear-gradient(135deg, #9b59b6, #71b7e6);
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .back-button img {
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }

    </style>
    <script>
        // Function to show the clicked image in an enlarged format
        function displayImageModal(imageSrc) {
            var modal = document.getElementById('imageModal');
            var modalContent = document.getElementById('imageModalContent');
            var image = document.getElementById('modalImage');

            image.src = imageSrc;
            modal.style.display = 'block';
        }

        // Function to close the image modal
        function closeImageModal() {
            var modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }
    </script>
</head>
<body>
    <h1 class="title">View Activity</h1>

    <div class="search-container">
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="association_id">Association:</label>
            <select name="association_id" id="association_id">
                <option value="">All Associations</option>
                <?php
                // Populate dropdown list with association IDs and names
                while ($row = mysqli_fetch_assoc($associationResult)) {
                    $associationId = $row['association_id'];
                    $associationName = $row['association_name'];
                    $selected = ($searchAssociationId == $associationId) ? 'selected' : '';
                    echo "<option value='$associationId' $selected>$associationId - $associationName</option>";
                }
                ?>
            </select>
            <input type="submit" value="Search">
        </form>
        <br>
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="activity_id">Activity ID:</label>
            <input type="text" name="activity_id" placeholder="Enter Activity ID">
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    // Check if any activities found
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $activityId = $row['activity_id'];
            $activityName = $row['activity_name'];
            $description = $row['description'];
            $associationId = $row['association_id'];
            $associationName = $row['association_name'];
            $date = $row['date'];
            $place = $row['place'];
            $images = explode(",", $row['image_path']);
    ?>
            <div class="activity-container">
                <div class="activity-images">
                    <?php
                    foreach ($images as $image) {
                        echo "<img src='$image' alt='Activity Image' onclick='displayImageModal(\"$image\")'>";
                    }
                    ?>
                </div>
                <div class="activity-details">
                    <h2>Activity ID: <?php echo $activityId; ?></h2>
                    <p><strong><u>Activity Name:</u></strong> <?php echo $activityName; ?></p>
                    <p><strong>Association:</strong> (<?php echo $associationId; ?> - <?php echo $associationName; ?>)</p>
                    <div class="activity-description">
                        <h3>Description:</h3>
                        <p><?php echo $description; ?></p>
                    </div>
                    <p>Date: <?php echo $date; ?></p>
                    <p>Place: <?php echo $place; ?></p>

                    <table class="data-table">
                        <caption>Staff Members</caption>
                        <thead>
                            <tr>
                                <th>Staff ID</th>
                                <th>Staff Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query to fetch staff members belonging to the association
                            $staffQuery = "SELECT staff_id, staff_name FROM staff WHERE association_id = '$associationId'";
                            $staffResult = mysqli_query($conn, $staffQuery);

                            while ($staffRow = mysqli_fetch_assoc($staffResult)) {
                                echo "<tr>";
                                echo "<td>" . $staffRow['staff_id'] . "</td>";
                                echo "<td>" . $staffRow['staff_name'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <table class="data-table">
                        <caption>Students</caption>
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query to fetch students belonging to the association
                            $studentQuery = "SELECT student_id, student_name FROM student WHERE association_id = '$associationId'";
                            $studentResult = mysqli_query($conn, $studentQuery);

                            while ($studentRow = mysqli_fetch_assoc($studentResult)) {
                                echo "<tr>";
                                echo "<td>" . $studentRow['student_id'] . "</td>";
                                echo "<td>" . $studentRow['student_name'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>

    <?php
        }
    } else {
        echo "No activities found.";
    }
    ?>

    <!-- Enlarged Image Modal -->
    <div id="imageModal" class="modal" onclick="closeImageModal()">
        <span class="modal-content" id="imageModalContent">
            <img id="modalImage">
        </span>
    </div>
    <button class="back-button"  onclick="goToOtherPage()">

        <img src="image/image54.png" alt="Back">
        Back
    </button>
        <script>
            function goToOtherPage(){
                window.location.href="admin_board.html";
            }
            </script>

</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
