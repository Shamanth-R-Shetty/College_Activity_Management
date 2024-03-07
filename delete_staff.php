<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch staff data for dropdown list
$sql = "SELECT staff_id, staff_name FROM staff";
$result = mysqli_query($conn, $sql);

// Initialize variables for staff details
$staffId = "";
$staffName = "";
$password = "";
$email = "";
$contactNo = "";
$department = "";
$associationId = "";
$showDetails = false; // Flag to track if details should be shown
$showDropdown = false; // Flag to track if dropdown list should be shown

// Retrieve staff details based on selected ID
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedStaffId = $_POST["staff_id"];

    $sql = "SELECT * FROM staff WHERE staff_id = '$selectedStaffId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $staffId = $row["staff_id"];
        $staffName = $row["staff_name"];
        $password = $row["password"];
        $email = $row["email"];
        $contactNo = $row["contact_no"];
        $department = $row["department"];
        $associationId = $row["association_id"];
        $showDetails = true; // Set flag to true when details are available
    }
}

// Form submission - Delete staff record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $selectedStaffId = $_POST["staff_id"];

    $deleteSql = "DELETE FROM staff WHERE staff_id = '$selectedStaffId'";
    $deleteResult = mysqli_query($conn, $deleteSql);

    if ($deleteResult) {
        echo "<script>alert('Staff record deleted successfully.'); window.location.href='delete_staff.php';</script>";
        $showDropdown = true; // Set flag to true to show the dropdown list
    } else {
        echo "Error deleting staff record: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Staff Record</title>
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
        <h1>Delete Staff Record</h1>

        <?php
        if (!$showDetails && !$showDropdown) {
            // Show the initial dropdown list if no details are shown and the dropdown list is not hidden
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
                <input type="submit" name="show_details" value="Show Staff Details">
            </form>
            <?php
        } elseif ($showDetails) {
            // Display staff details if available and the flag is true
            ?>
            <h2>Staff Details</h2>
            <p>Staff ID: <?php echo $staffId; ?></p>
            <p>Staff Name: <?php echo $staffName; ?></p>
            <p>Password: <?php echo $password; ?></p>
            <p>Email: <?php echo $email; ?></p>
            <p>Contact Number: <?php echo $contactNo; ?></p>
            <p>Department: <?php echo $department; ?></p>
            <p>Association ID: <?php echo $associationId; ?></p>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="staff_id" value="<?php echo $staffId; ?>">
                <input type="submit" name="delete" value="Delete Staff">
            </form>
            <?php
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["show_details"])) {
            // Show message if no staff details found
            echo "<p>No staff details found.</p>";
        }

        // Show the dropdown list if the flag is true and details are not shown
        if ($showDropdown && !$showDetails) {
            ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="staff_id">Select Staff:</label>
                <select name="staff_id" id="staff_id" style="display: none;">
                    <option value="">Select a staff member</option>
                    <?php
                    // Populate dropdown list with staff IDs and names
                    mysqli_data_seek($result, 0); // Reset the result pointer
                    while ($row = mysqli_fetch_assoc($result)) {
                        $staffId = $row['staff_id'];
                        $staffName = $row['staff_name'];
                        echo "<option value='$staffId'>$staffId - $staffName</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="show_details" value="Show Staff Details" style="display: none;">
            </form>
            <?php
        }
        ?>
    </div>
    <button class="back-button" onclick="goToOtherPage()">
        <img src="image/image54.png" alt="Back">
        Back
    </button>
    <script>
        function goToOtherPage() {
            window.location.href = "admin_board.html";
        }
    </script>
</body>
</html>