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

// Fetch association data for dropdown list
$sql = "SELECT association_id FROM association";
$associationResult = mysqli_query($conn, $sql);

// Form submission - Update student data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected student ID
    $selectedStudentId = $_POST["student_id"];

    // Retrieve student data based on selected ID
    $sql = "SELECT * FROM student WHERE student_id = '$selectedStudentId'";
    $result = mysqli_query($conn, $sql);
    $studentData = mysqli_fetch_assoc($result);

    // Update student data
    if (isset($_POST["save_changes"])) {
        $studentId = $_POST["student_id"];
        $studentName = $_POST["student_name"];
        $class = $_POST["class"];
        $associationId = $_POST["association_id"];

        // Validate form fields
        if (empty($studentName) || empty($class)) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } else {
            $updateSql = "UPDATE student SET student_name = '$studentName', class = '$class' WHERE student_id = '$studentId'";
            $updateResult = mysqli_query($conn, $updateSql);

            if ($updateResult) {
                echo "<script>alert('Student record updated successfully.');</script>";
                // Clear studentData variable
                $studentData = null;
                // Refresh the page to show updated data
                echo '<script>window.location.href = "update_student.php";</script>';
                exit();
            } else {
                echo "Error updating student data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modify Student Records</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('image/image60.jpg'); 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
            margin: 0;
        }

        h1 {
    text-align: left;
    margin-bottom: 20px;
}



.container{
  
    max-width: 600px;
    width: 90%;
    background-color: #fff;
    padding: 25px 30px;
    border-radius: 25px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.15);
}
        
.container .title{
    font-size: 25px;
    font-weight: 500;
    position: relative;
}
        
        .container .title::before{
          content: "";
          position: absolute;
          left: 0;
          bottom: 0;
          height: 3px;
          width: 260px;
          border-radius: 5px;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group select,
        .form-group input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
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
        <h1 class="title">Update Student Records</h1>

        <?php if (!isset($studentData)) { ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="student_id">Select Student:</label>
                    <select name="student_id" id="student_id">
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
                <div class="form-group">
                    <input type="submit" value="Show Student Data">
                </div>
            </form>
        <?php } ?>

        <?php if (isset($studentData)) { ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="student_id" value="<?php echo $studentData['student_id']; ?>">
                <div class="form-group">
                    <label for="student_name">Student Name:</label>
                    <input type="text" name="student_name" value="<?php echo $studentData['student_name']; ?>">
                </div>
                <div class="form-group">
                    <label for="class">Class:</label>
                    <input type="text" name="class" value="<?php echo $studentData['class']; ?>">
                </div>
                <div class="form-group">
                    <label for="association_id">Association ID:</label>
                    <input type="text" name="association_id" value="<?php echo $studentData['association_id']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="attendance">Attendance:</label>
                    <input type="text" name="attendance" value="<?php echo $studentData['attendance']; ?>" readonly>
                </div>
                <div class="form-group">
                    <input type="submit" name="save_changes" value="Save Changes">
                </div>
            </form>
        <?php } ?>
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

<?php
// Close database connection
mysqli_close($conn);
?>