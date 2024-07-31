<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if staff is logged in and get staff ID
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Fetch staff ID and association ID
$sqlStaff = "SELECT staff_id, association_id FROM staff WHERE staff_id = '$userID'";
$resultStaff = mysqli_query($conn, $sqlStaff);
if (mysqli_num_rows($resultStaff) > 0) {
    $rowStaff = mysqli_fetch_assoc($resultStaff);
    $staffID = $rowStaff['staff_id'];
    $associationID = $rowStaff['association_id'];
} else {
    // Handle case when staff record is not found
    echo "Staff record not found.";
    exit();
}

// Fetch activities based on search parameters
$searchActivityId = isset($_GET['activity_id']) ? $_GET['activity_id'] : '';

$sql = "SELECT a.*, assoc.association_name FROM activity a 
        INNER JOIN association assoc ON a.association_id = assoc.association_id 
        WHERE a.association_id = '$associationID'";

if (!empty($searchActivityId)) {
    $sql .= " AND a.activity_id = '$searchActivityId'";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Activity - Staff</title>
    <style>
    body {
        background-image: url('image/image60.jpg'); 
     }
        h1 {
    text-align: center;
    margin-top: 20px;
    }
        /* Styles for search bar */
        .search-container {
            margin-bottom: 20px;
        }

        .search-container input[type=text] {
            padding: 10px;
            width: 200px;
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
            padding: 10px;
        }

        .activity-details {
            flex-grow: 1;
            margin-left: 20px;
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            grid-gap: 10px;
            max-width: 600px;
        }

        .activity-images img {
            max-width: 100%;
            height: auto;
            cursor: pointer;
        }

        /* Staff and Student tables */
        .details-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .staff-table,
        .student-table {
            width: 45%;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        .staff-table th,
        .staff-table td,
        .student-table th,
        .student-table td {
            padding: 8px;
            text-align: left;
        }

        .staff-table th,
        .student-table th {
            background-color: #f2f2f2;
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
        .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        padding: 10px;
        background: linear-gradient(135deg, #71b7e6, #9b59b6);
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

    .back-button:hover {
        background: linear-gradient(-135deg, #71b7e6, #9b59b6);
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
                </div>
            </div>

            <div class="details-container">
                <table class="staff-table">
                    <caption>Staff Details</caption>
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Staff Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlStaff = "SELECT staff_id, staff_name FROM staff WHERE association_id = '$associationId'";
                        $resultStaff = mysqli_query($conn, $sqlStaff);
                        if (mysqli_num_rows($resultStaff) > 0) {
                            while ($rowStaff = mysqli_fetch_assoc($resultStaff)) {
                                $staffId = $rowStaff['staff_id'];
                                $staffName = $rowStaff['staff_name'];
                                echo "<tr><td>$staffId</td><td>$staffName</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2'>No staff found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <table class="student-table">
                    <caption>Student Details</caption>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlStudent = "SELECT s.student_id, s.student_name, a.status
                                       FROM student s
                                       LEFT JOIN attendance a ON a.activity_id = '$activityId' AND a.student_id = s.student_id
                                       WHERE s.association_id = '$associationId'";
                        $resultStudent = mysqli_query($conn, $sqlStudent);
                        if (mysqli_num_rows($resultStudent) > 0) {
                            while ($rowStudent = mysqli_fetch_assoc($resultStudent)) {
                                $studentId = $rowStudent['student_id'];
                                $studentName = $rowStudent['student_name'];
                                $status = $rowStudent['status'];

                                $attendanceStatus = "-";
                                if ($status === "0") {
                                    $attendanceStatus = "Absent";
                                } elseif ($status === "1") {
                                    $attendanceStatus = "Present";
                                }

                                echo "<tr><td>$studentId</td><td>$studentName</td><td>$attendanceStatus</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No students found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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

</body><button class="back-button"  onclick="goToOtherPage()">

<img src="image/image54.png" alt="Back">
Back
</button>
<script>
    function goToOtherPage(){
        window.location.href="staff_board.html";
    }
    </script>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>