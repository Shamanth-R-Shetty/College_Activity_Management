<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Form submission - Update staff data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected staff ID
    $selectedStaffId = $_POST["staff_id"];

    // Retrieve staff data based on selected ID
    $sql = "SELECT * FROM staff WHERE staff_id = '$selectedStaffId'";
    $result = mysqli_query($conn, $sql);
    $staffData = mysqli_fetch_assoc($result);

    // Update staff data
    if (isset($_POST["save_changes"])) {
        $staffId = $_POST["staff_id"];
        $staffName = $_POST["staff_name"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $contactNo = $_POST["contact_no"];
        $department = $_POST["department"];
        $associationId = $_POST["association_id"];

        // Validate form fields
        if (empty($staffName) || empty($password) || empty($email) || empty($contactNo) || empty($department) || empty($associationId)) {
            echo "<script>alert('Please fill in all fields.');</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');</script>";
        } elseif (!preg_match("/^[0-9]{10}$/", $contactNo)) {
            echo "<script>alert('Invalid contact number.');</script>";
        } else {
            $updateSql = "UPDATE staff SET staff_name = '$staffName', password = '$password', email = '$email', contact_no = '$contactNo', department = '$department', association_id = '$associationId' WHERE staff_id = '$staffId'";
            $updateResult = mysqli_query($conn, $updateSql);

            if ($updateResult) {
                echo "<script>alert('Staff record updated successfully.');</script>";
                echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page
            } else {
                echo "Error updating staff data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Staff Records</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

        *{
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: 'Poppins',sans-serif;
        }

        body{
          height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 10px;
          background-image: url('image/image60.jpg'); 
        }

        .container{
          max-width: 600px;
          width: 100%;
          background-color: #fff;
          padding: 25px 30px;
          border-radius: 5px;
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .container h1{
          font-size: 25px;
          font-weight: 600;
         
          margin-bottom: 20px;
        }

        .container form{
          width: 100%;
        }

        .container form label span{
          font-size: 12px;
          font-weight: 500;
          color: #888;
        }

        .container form input[type="text"],
        .container form input[type="password"]{
          width: 100%;
          padding: 10px;
          font-size: 14px;
          font-weight: 500;
          color: #333;
          margin-bottom: 15px;
          border-radius: 5px;
          border: 1px solid #ccc;
          outline: none;
        }

        .container form input[type="submit"]{
          width: auto;
          padding: 10px 20px;
          font-size: 14px;
          font-weight: 500;
          color: #fff;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
          cursor: pointer;
          border: none;
          border-radius: 5px;
          outline: none;
          transition: background-color 0.2s;
        }

        .container form input[type="submit"]:hover{
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
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
        <h1>Modify Staff Records</h1>

        <?php
        // Display staff data if it has been selected
        if (isset($staffData)) {
            ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="staff_id" value="<?php echo $staffData['staff_id']; ?>">
                <label for="staff_name">Staff Name:</label>
                <input type="text" name="staff_name" value="<?php echo $staffData['staff_name']; ?>"><br>
                <label for="password">Password:</label>
                <input type="text" name="password" value="<?php echo $staffData['password']; ?>"><br>
                <label for="email">Email:</label>
                <input type="text" name="email" value="<?php echo $staffData['email']; ?>"><br>
                <label for="contact_no">Contact Number:</label>
                <input type="text" name="contact_no" value="<?php echo $staffData['contact_no']; ?>"><br>
                <label for="department">Department:</label>
                <input type="text" name="department" value="<?php echo $staffData['department']; ?>"><br>
                <label for="association_id">Association ID:</label>
                <input type="text" name="association_id" value="<?php echo $staffData['association_id']; ?>" readonly><br>
                <input type="submit" name="save_changes" value="Save Changes">
            </form>
            <?php
        } else {
            // Fetch staff data for dropdown list
            $sql = "SELECT staff_id, staff_name FROM staff";
            $result = mysqli_query($conn, $sql);
            ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="staff_id">Select Staff:</label>
                <select name="staff_id" id="staff_id">
                    <option value="">Select a staff member</option>
                    <?php
                    // Populate dropdown list with staff IDs and names
                    while ($row = mysqli_fetch_assoc($result)) {
                        $staffId = $row['staff_id'];
                        $staffName = $row['staff_name'];
                        echo "<option value='$staffId'>$staffId - $staffName</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Show Staff Data">
            </form>
            <?php
        }
        ?>
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
