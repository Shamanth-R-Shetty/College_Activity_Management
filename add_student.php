<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
$conn = mysqli_connect($servername, $username, $password, $dbname);

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

// Fetch staff ID and association ID from staff table
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

// Query to get association IDs for the logged-in staff
$assoc_query = "SELECT association_id FROM association WHERE association_id = '$associationID'";
$assoc_result = mysqli_query($conn, $assoc_query);

// Array to store association IDs
$assoc_ids = array();

// Loop through result and add IDs to array
if (mysqli_num_rows($assoc_result) > 0) {
    while($row = mysqli_fetch_assoc($assoc_result)) {
        array_push($assoc_ids, $row["association_id"]);
    }
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $student_id = $_POST["student_id"];
    $student_name = $_POST["student_name"];
    $class = $_POST["class"];
    $attendance = 0; // default attendance value
    $assoc_id = $associationID;

    // Check if student ID already exists
    $check_query = "SELECT * FROM student WHERE student_id = '$student_id' AND association_id = '$assoc_id'";
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Record already exists for this student ID');</script>";
    } else {
        // Insert new student into database
        $insert_query = "INSERT INTO student (student_id, student_name, class, association_id, attendance) VALUES ('$student_id', '$student_name', '$class', '$assoc_id', '$attendance')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Student added successfully.');</script>";
            // Clear the form data
            $_POST = array();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <style>
         body{
          height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 10px;
          background-image: url('image/image60.jpg');
        };
        
        
        h2 {
            color: #333;
        }
        
       
        
        .container{
         

         max-width: 600px;
         width: 100%;
         background-color: #fff;
         padding: 25px 25px;
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
         width:130px;
         border-radius: 5px;
         background: linear-gradient(135deg, #71b7e6, #9b59b6);
       }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        input[type="text"] {
            width: 90%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form .button{
          height: 45px;
          margin: 35px 0;
        }
        
        form .button input{
          height: 100%;
          width: 90%;
          border-radius: 10px;
          border: none;
          color: #fff;
          font-size: 18px;
          font-weight: 500;
          letter-spacing: 1px;
          cursor: pointer;
          transition: all 0.3s ease;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }
        
        form .button input:hover{
          background: linear-gradient(-135deg, #71b7e6, #9b59b6);
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
        <div class="title">Add Student</div>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="content">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Student ID:</span>
                        <input type="text" name="student_id" required><br><br>
                    </div> 
                    <div class="input-box">
                        <span class="details">Student Name:</span>
                        <input type="text" name="student_name" required><br><br>
                    </div>
                    <div class="input-box">
                        <span class="details">Class:</span><br>
                        <input type="text" name="class" required><br><br>
                     </div>
                     <div class="input-box">
                        <span class="details">Association ID:</span>
                        <input type="text" name="assoc_id" value="<?php echo $associationID; ?>" readonly><br><br>
                    </div>
                    <div class="input-box">
                        <span class="details">Attendance:</span>
                        <input type="text" name="attendance" value="0" readonly><br>
                    </div>
                    <div class="button">
                        <input type="submit" value="Add Student">
                    </div>
             </div>
            </div>
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
