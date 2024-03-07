<?php
session_start();

// Establish database connection
$connection = mysqli_connect("localhost", "root", "", "project");

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve staff ID from session
$staffId = $_SESSION['user_id'];

// Fetch the association ID of the staff member
$associationQuery = "SELECT association_id FROM staff WHERE staff_id = '$staffId'";
$associationResult = mysqli_query($connection, $associationQuery);
$associationRow = mysqli_fetch_assoc($associationResult);
$associationId = $associationRow['association_id'];

// Retrieve activity data excluding recorded activities
$query = "SELECT activity_id FROM activity WHERE association_id = '$associationId' AND activity_id NOT IN (SELECT activity_id FROM attendance)";
$result = mysqli_query($connection, $query);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['attendance'])) {
        // Retrieve form data
        $activityId = $_POST['activityId'];
        $attendance = $_POST['attendance'];

        // Update student attendance and insert records in the attendance table
        foreach ($attendance as $studentId => $status) {
            // Update student attendance column
            $updateQuery = "UPDATE student SET attendance = attendance + $status WHERE student_id = '$studentId'";
            mysqli_query($connection, $updateQuery);

            // Insert attendance record
            $insertQuery = "INSERT INTO attendance (student_id, activity_id, status) VALUES ('$studentId', '$activityId', $status)";
            mysqli_query($connection, $insertQuery);
        }

        echo "Attendance recorded successfully.";
        echo "<script>alert('Attendance recorded successfully.'); window.location.href = '$_SERVER[PHP_SELF]';</script>";
        exit;
    }

    $activityId = $_POST['activityId'];

    // Retrieve students for the selected association and activity
    $studentQuery = "SELECT s.student_id, s.student_name 
                     FROM student s 
                     WHERE s.association_id = (SELECT a.association_id FROM activity a WHERE a.activity_id = '$activityId')";
    $studentResult = mysqli_query($connection, $studentQuery);

    // Display the attendance form
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Attendance</title>
    <style>
        body {
            background-image: url('image/image60.jpg');
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        select {
            padding: 12px;
            font-size: 18px;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 20px;
        }

        button {
            padding: 12px 24px;
            background-color: #2980b9;
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #1a5276;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-wrapper {
            background-color: #34495e;
            padding: 40px;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ffffff;
        }

        th {
            background-color: #2c3e50;
        }

        input[type="radio"] {
            margin-right: 10px;
        }
    </style>
    <script>
        function validateForm() {
            var activityId = document.getElementsByName("activityId")[0].value;
            if (activityId === "") {
                alert("Please select an activity.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Record Attendance</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
                <input type="hidden" name="activityId" value="<?php echo $activityId; ?>">
                <table>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Attendance</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($studentResult)): ?>
                        <tr>
                            <td><?php echo $row['student_id']; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td>
                                <input type="radio" name="attendance[<?php echo $row['student_id']; ?>]" value="1" required>Present
                                <input type="radio" name="attendance[<?php echo $row['student_id']; ?>]" value="0">Absent
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
    // Close the database connection
    mysqli_close($connection);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Attendance</title>
    <style>
        body {
            background-color: #2c3e50;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        select {
            padding: 12px;
            font-size: 18px;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 20px;
        }

        button {
            padding: 12px 24px;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #1a5276;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-wrapper {
            background-color: #34495e;
            padding: 40px;
            border-radius: 8px;
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
        function validateForm() {
            var activityId = document.getElementsByName("activityId")[0].value;
            if (activityId === "") {
                alert("Please select an activity.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h2>Select Activity:</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
                <select name="activityId">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <option value="<?php echo $row['activity_id']; ?>"><?php echo $row['activity_id']; ?></option>
                    <?php endwhile; ?>
                </select>
                <br><br>
                <button type="submit">Select</button>
            </form>
        </div>
    </div>
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