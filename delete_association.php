<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $associationId = $_POST["associationId"];
    $action = $_POST["action"];

    if ($action === "showDetails") {
        // Retrieve association details
        $associationQuery = "SELECT association_name, association_icon FROM association WHERE association_id = '$associationId'";
        $associationResult = $conn->query($associationQuery);

        if ($associationResult->num_rows > 0) {
            $row = $associationResult->fetch_assoc();
            $associationName = $row["association_name"];
            $associationIcon = $row["association_icon"];

            echo "<form><h3>Association Details<h3>";
            echo "<p>Association Name: $associationName</p>";
            echo "<img src=\"$associationIcon\" alt=\"Association Icon\" style=\"width: 100px; height: 100px;\">";

            // Display the list of students
            $studentQuery = "SELECT student_id, student_name FROM student WHERE association_id = '$associationId'";
            $studentResult = $conn->query($studentQuery);

            if ($studentResult->num_rows > 0) {
                echo "<h3>Students</h3>";
                echo "<ul>";
                while ($row = $studentResult->fetch_assoc()) {
                    $studentId = $row["student_id"];
                    $studentName = $row["student_name"];
                    echo "<li>Student ID: $studentId, Student Name: $studentName</li>";
                }
                echo "</ul>";
            }

            // Display the list of activities
            $activityQuery = "SELECT activity_id, activity_name FROM activity WHERE association_id = '$associationId'";
            $activityResult = $conn->query($activityQuery);

            if ($activityResult->num_rows > 0) {
                echo "<h3>Activities</h3>";
                echo "<ul>";
                while ($row = $activityResult->fetch_assoc()) {
                    $activityId = $row["activity_id"];
                    $activityName = $row["activity_name"];
                    echo "<li>Activity ID: $activityId, Activity Name: $activityName</li>";
                }
                echo "</ul>";
            }

            // Display the list of staff
            $staffQuery = "SELECT staff_id, staff_name FROM staff WHERE association_id = '$associationId'";
            $staffResult = $conn->query($staffQuery);

            if ($staffResult->num_rows > 0) {
                echo "<h3>Staff</h3>";
                echo "<ul>";
                while ($row = $staffResult->fetch_assoc()) {
                    $staffId = $row["staff_id"];
                    $staffName = $row["staff_name"];
                    echo "<li>Staff ID: $staffId, Staff Name: $staffName</li>";
                }
                echo "</ul></form>";
            }

            // Display the delete button
            echo '<form method="POST" action="' . $_SERVER["PHP_SELF"] . '">';
            echo '<input type="hidden" name="associationId" value="' . $associationId . '">';
            echo '<button type="submit" name="action" value="delete">Delete</button>';
            echo '</form>';
        }
    } elseif ($action === "delete") {
        if (empty($associationId)) {
            echo '<script>alert("Please select an association.");</script>';
        } else {
            // Delete associated records from activity, staff, and student tables
            // Delete associated records from attendance table
            $deleteAttendanceQuery = "DELETE FROM attendance WHERE activity_id IN (SELECT activity_id FROM activity WHERE association_id = '$associationId')";
            $conn->query($deleteAttendanceQuery);

            // Delete associated records from activity table
            $deleteActivityQuery = "DELETE FROM activity WHERE association_id = '$associationId'";
            $conn->query($deleteActivityQuery);
            $deleteStaffQuery = "DELETE FROM staff WHERE association_id = '$associationId'";
            $deleteStudentQuery = "DELETE FROM student WHERE association_id = '$associationId'";

            $conn->query($deleteActivityQuery);
            $conn->query($deleteStaffQuery);
            $conn->query($deleteStudentQuery);

            // Delete association record from association table
            $deleteAssociationQuery = "DELETE FROM association WHERE association_id = '$associationId'";
            $conn->query($deleteAssociationQuery);

            echo '<script>alert("Association deleted successfully."); window.location.href = "' . $_SERVER["PHP_SELF"] . '";</script>';
            exit();
        }
    }
}

// Retrieve association data for dropdown
$associationQuery = "SELECT association_id, association_name, association_icon FROM association";
$associationResult = $conn->query($associationQuery);
?>

<!DOCTYPE html>
<html>
<head>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image/image60.jpg');
        }
        .container{
          max-width: 650px;
          width: 100%;
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
          width: 237px;
          border-radius: 5px;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }
        
        h1 {
            color: #333;
            text-align: center;
            padding: 20px 0;
        }
        ul {
            color: #333;
            text-align: center;
            padding: 20px 0;
        }
        h3 {
            color: #333;
            text-align: center;
            padding: 20px 0;
        }
        img{
            display: block;
            margin:0 auto;
        }
        h2{
            color: #333;
            text-align: center;
            padding: 20px 0;
        }
        p {
            color: #333;
            text-align: center;
            padding: 20px 0;
        }
        
        form {
           
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            text-align: center;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        
        select, button {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        
        button {
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            color: #fff;
            cursor: pointer;
        }
        
        button:hover {
            background: linear-gradient(-135deg, #9b59b6, #71b7e6);
        }
        
        ul {
            list-style-type: none;
            padding: 0;
        }
        
        li {
            margin-bottom: 5px;
        }
        .back-button {
        position: absolute;
        width:80px;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        padding: 10px;
        background: linear-gradient(-135deg, #71b7e6, #9b59b6);
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


</head>
<body>
   
        <h1 class="title">Delete Association Record</h1>
    
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="associationId">Select Association:</label>
        <select name="associationId" id="associationId">
            <option value="">Select an association</option>
            <?php
            while ($row = $associationResult->fetch_assoc()) {
                $associationId = $row["association_id"];
                $associationName = $row["association_name"];
                $associationIcon = $row["association_icon"];
                echo "<option value=\"$associationId\">$associationId - $associationName</option>";
            }
            ?>
        </select>
        <button type="submit" name="action" value="showDetails">Show Details</button>
    </form>

    <script>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && $action === "delete") {
            echo 'alert("Association deleted successfully."); window.location.href = "' . $_SERVER["PHP_SELF"] . '";';
        } elseif ($_SERVER["REQUEST_METHOD"] === "POST" && empty($associationId)) {
            echo 'alert("Please select an association.");';
        }
        ?>
    </script>
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
// Close the database connection
$conn->close();
?>
