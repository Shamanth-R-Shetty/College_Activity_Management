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

// Fetch staff's association ID from staff table
$sqlStaff = "SELECT association_id FROM staff WHERE staff_id = '$userID'";
$resultStaff = mysqli_query($conn, $sqlStaff);

if (mysqli_num_rows($resultStaff) > 0) {
    $rowStaff = mysqli_fetch_assoc($resultStaff);
    $associationID = $rowStaff['association_id'];
} else {
    // Handle case when staff record is not found
    echo "Staff record not found.";
    exit();
}

// Fetch student data for dropdown list from the specific association
$sql = "SELECT student_id, student_name FROM student WHERE association_id = '$associationID'";
$result = mysqli_query($conn, $sql);

// Form submission - Show student record
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected student ID
    if (isset($_POST["student_id"])) {
        $selectedStudentId = $_POST["student_id"];

        if (empty($selectedStudentId)) {
            echo "<script>alert('Please select a valid student ID.');</script>";
        } else {
            // Retrieve student data based on selected ID
            $sql = "SELECT * FROM student WHERE student_id = '$selectedStudentId'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $studentData = mysqli_fetch_assoc($result);
            } else {
                echo "<script>alert('Please select a valid student ID.');</script>";
            }
        }
    } else {
        echo "<script>alert('Please select a valid student ID.');</script>";
    }
}

// Form submission - Delete student record
if (isset($_POST['delete'])) {
    // Get the selected student ID
    if (isset($_POST["student_id"])) {
        $selectedStudentId = $_POST["student_id"];

        if (empty($selectedStudentId)) {
            echo "<script>alert('Please select a valid student ID.');</script>";
        } else {
            // Delete associated attendance record
            $deleteAttendanceSql = "DELETE FROM attendance WHERE student_id = '$selectedStudentId'";
            $deleteAttendanceResult = mysqli_query($conn, $deleteAttendanceSql);

            if ($deleteAttendanceResult) {
                // Delete student record
                $deleteStudentSql = "DELETE FROM student WHERE student_id = '$selectedStudentId'";
                $deleteStudentResult = mysqli_query($conn, $deleteStudentSql);

                if ($deleteStudentResult) {
                    echo "<script>alert('Student record deleted successfully.'); window.location.href='delete_student.php';</script>";
                    $studentData = null;

                    // Update the dropdown list of student IDs and names
                    $result = mysqli_query($conn, $sql);
                } else {
                    echo "Error deleting student record: " . mysqli_error($conn);
                }
            } else {
                echo "Error deleting attendance record: " . mysqli_error($conn);
            }
        }
    } else {
        echo "<script>alert('Please select a valid student ID.');</script>";
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Student Record</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            background-image: url('image/image60.jpg');
        }

        .container {
            max-width: 700px;
            width: 100%;
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
            width: 270px;
            border-radius: 5px;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }

        .content form .user-details {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 20px 0 12px 0;
        }

        form .user-details .input-box {
            margin-bottom: 15px;
            width: calc(100% / 2 - 20px);
        }

        form .input-box span.details {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .user-details .input-box input {
            height: 45px;
            width: 100%;
            outline: none;
            font-size: 16px;
            border-radius: 5px;
            padding-left: 15px;
            border: 1px solid #ccc;
            border-bottom-width: 2px;
            transition: all 0.3s ease;
        }

        .user-details .input-box input:focus,
        .user-details .input-box input:valid {
            border-color: #9b59b6;
        }

        form .button {
            height: 45px;
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

        @media (max-width: 584px) {
            .container {
                max-width: 100%;
            }

            form .user-details .input-box {
                margin-bottom: 15px;
                width: 100%;
            }

            form .category {
                width: 100%;
            }

            .content form .user-details {
                max-height: 300px;
                overflow-y: scroll;
            }

            .user-details::-webkit-scrollbar {
                width: 5px;
            }
        }

        @media (max-width: 459px) {
            .container .content .category {
                flex-direction: column;
            }
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
    <h1 class="title">Delete Student Record</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="content">
        <?php if (!isset($studentData)) { ?>
        <div class="user-details">
            <div class="input-box">
                <span class="details">Select Student ID:</span>
                <select name="student_id" id="student_id" onchange="this.form.submit()">
                    <option value="">-- Select Student ID --</option>
                    <?php
                    // Populate dropdown list with student IDs and names
                    while ($row = mysqli_fetch_assoc($result)) {
                        $studentId = $row['student_id'];
                        $studentName = $row['student_name'];
                        echo "<option value='$studentId'>$studentId - $studentName</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php } ?>

        <?php if (isset($studentData)) { ?>
        <div class="user-details">
            <div class="input-box">
                <span class="details">Student ID:</span>
                <input type="text" value="<?php echo $studentData['student_id']; ?>" readonly>
            </div>
            <div class="input-box">
                <span class="details">Student Name:</span>
                <input type="text" value="<?php echo $studentData['student_name']; ?>" readonly>
            </div>
            <div class="input-box">
                <span class="details">Class:</span>
                <input type="text" value="<?php echo $studentData['class']; ?>" readonly>
            </div>
            <div class="input-box">
                <span class="details">Association ID:</span>
                <input type="text" value="<?php echo $studentData['association_id']; ?>" readonly>
            </div>
            <div class="input-box">
                <span class="details">Attendance:</span>
                <input type="text" value="<?php echo $studentData['attendance']; ?>" readonly>
            </div>
        </div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!-- Add the hidden input field for student_id -->
            <input type="hidden" name="student_id" value="<?php echo $studentData['student_id']; ?>">
            <div class="button">
                <input type="submit" name="delete" value="Delete Record">
            </div>
        </form>
        <?php } ?>
    </div>
    </form>

    <button class="back-button" onclick="window.location.href='staff_board.html'">
        <img src="image/back.png" alt="Back">
        Back
    </button>
</div>
</body>
</html>
