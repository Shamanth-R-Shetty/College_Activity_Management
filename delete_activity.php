<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch staff ID and association ID based on the logged-in user's session
session_start();
$user_id = $_SESSION['user_id'];
$staffQuery = "SELECT staff_id, association_id FROM staff WHERE staff_id = '$user_id'";
$staffResult = mysqli_query($conn, $staffQuery);

if (!$staffResult) {
    echo "Error retrieving staff data: " . mysqli_error($conn);
    exit;
}

$staffData = mysqli_fetch_assoc($staffResult);
$staffId = $staffData['staff_id'];
$associationId = $staffData['association_id'];

// Fetch activity data for dropdown list
$sql = "SELECT activity_id, activity_name FROM activity WHERE association_id = '$associationId'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error retrieving activity data: " . mysqli_error($conn);
    exit;
}

// Variables to store activity details
$selectedActivityId = "";
$activityName = "";
$description = "";
$imagePath = "";
$date = "";
$place = "";
$associationId = "";

// Form submission - Show activity details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["show_details"])) {
    // Get the selected activity ID
    if (isset($_POST["activity_id"])) {
        $selectedActivityId = $_POST["activity_id"];

        if (empty($selectedActivityId)) {
            echo "<script>alert('Please select a valid activity.');</script>";
        } else {
            // Fetch activity details
            $activityInfoQuery = "SELECT * FROM activity WHERE activity_id = '$selectedActivityId'";
            $activityInfoResult = mysqli_query($conn, $activityInfoQuery);

            if (!$activityInfoResult) {
                echo "Error retrieving activity information: " . mysqli_error($conn);
                exit;
            }

            if ($row = mysqli_fetch_assoc($activityInfoResult)) {
                $activityName = $row['activity_name'];
                $description = $row['description'];
                $imagePath = $row['image_path'];
                $date = $row['date'];
                $place = $row['place'];
                $associationId = $row['association_id'];
            }
        }
    } else {
        echo "<script>alert('Please select a valid activity.');</script>";
    }
}

// Form submission - Delete activity record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_activity"])) {
    // Get the selected activity ID
    if (isset($_POST["activity_id"])) {
        $selectedActivityId = $_POST["activity_id"];

        if (empty($selectedActivityId)) {
            echo "<script>alert('Please select a valid activity.');</script>";
        } else {
            // Check attendance records with status = 1
            $checkAttendanceSql = "SELECT student_id FROM attendance WHERE activity_id = '$selectedActivityId' AND status = 1";
            $checkAttendanceResult = mysqli_query($conn, $checkAttendanceSql);

            if (!$checkAttendanceResult) {
                echo "Error checking attendance records: " . mysqli_error($conn);
                exit;
            }

            // Decrement attendance records in student table
            while ($row = mysqli_fetch_assoc($checkAttendanceResult)) {
                $studentId = $row['student_id'];
                $decrementAttendanceSql = "UPDATE student SET attendance = attendance - 1 WHERE student_id = '$studentId'";
                $decrementAttendanceResult = mysqli_query($conn, $decrementAttendanceSql);

                if (!$decrementAttendanceResult) {
                    echo "Error decrementing attendance records in student table: " . mysqli_error($conn);
                    exit;
                }
            }

            // Delete associated attendance records
            $deleteAttendanceSql = "DELETE FROM attendance WHERE activity_id = '$selectedActivityId'";
            $deleteAttendanceResult = mysqli_query($conn, $deleteAttendanceSql);

            if (!$deleteAttendanceResult) {
                echo "Error deleting attendance records: " . mysqli_error($conn);
                exit;
            }

            // Delete activity record
            $deleteActivitySql = "DELETE FROM activity WHERE activity_id = '$selectedActivityId'";
            $deleteActivityResult = mysqli_query($conn, $deleteActivitySql);

            if ($deleteActivityResult) {
                echo "<script>alert('Activity record deleted successfully.');</script>";
                header("Refresh:0"); // Refresh the page to show only the dropdown list
                exit;
            } else {
                echo "Error deleting activity record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "<script>alert('Please select a valid activity.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Activity Record</title>
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background-image: url('image/image60.jpg');
        }

        h1 {
            color: #333;
        }

        .container {
            margin-top: 220px;
            max-width: 600px;
            width: 70%;
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        .container .title {
            font-size: 25px;
            font-weight: 500;
            position: relative;
        }

        .container .title::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 250px;
            border-radius: 5px;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select,
        input[type="text"],
        textarea {
            height: 30px;
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        form .button {
            height: 40px;
            margin: 35px 0;
        }

        form .button input {
            height: 100%;
            width: 100%;
            border-radius: 5px;
            border: none;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }

        form .button input:hover {
            background: linear-gradient(-135deg, #71b7e6, #9b59b6);
        }

        .activity-details {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .activity-details h2 {
            margin-top: 0;
            color: #333;
        }

        .go-back-link {
            display: block;
            margin-top: 20px;
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
</head>

<body>
    <div class="container">
        <h1 class="title">Delete Activity Records</h1>

        <?php if ($selectedActivityId == "") : ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="activity_id">Select Activity:</label>
                <select name="activity_id" id="activity_id">
                    <?php
                    // Populate dropdown list with activity IDs and names
                    while ($row = mysqli_fetch_assoc($result)) {
                        $activityId = $row['activity_id'];
                        $activityName = $row['activity_name'];
                        echo "<option value='$activityId'>$activityId - $activityName</option>";
                    }
                    ?>
                </select>
                <div class="button">
                    <input type="submit" name="show_details" value="Show Details">
                </div>
            </form>
        <?php endif; ?>

        <?php if ($selectedActivityId != "") : ?>
            <h2>Activity Details</h2>
            <div>
                <label for="activity_name">Activity Name:</label>
                <input type="text" id="activity_name" value="<?php echo $activityName; ?>" readonly>
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" readonly><?php echo $description; ?></textarea>
            </div>
            <div>
                <label for="image_path">Image Path:</label>
                <input type="text" id="image_path" value="<?php echo $imagePath; ?>" readonly>
            </div>
            <div>
                <label for="date">Date:</label>
                <input type="text" id="date" value="<?php echo $date; ?>" readonly>
            </div>
            <div>
                <label for="place">Place:</label>
                <input type="text" id="place" value="<?php echo $place; ?>" readonly>
            </div>
            <div>
                <label for="association_id">Association ID:</label>
                <input type="text" id="association_id" value="<?php echo $associationId; ?>" readonly>
            </div>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="activity_id" value="<?php echo $selectedActivityId; ?>">
                <div class="button">
                    <input type="submit" name="delete_activity" value="Delete Activity">
                </div>
            </form>
    <?php endif; ?>

    <button class="back-button"  onclick="goToOtherPage()">

        <img src="image/image54.png" alt="Back">
        Back
    </button>
        <script>
            function goToOtherPage(){
                window.location.href="staff_board.html";
            }
            </script>
</body>
</html>
