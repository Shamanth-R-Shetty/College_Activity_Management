<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password_db = "";
$dbname = "project";
$conn = new mysqli($servername, $username, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM staff WHERE staff_id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Error: More than one row returned.";
    exit();
}

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $department = $_POST['department'];
    $association_id = $_POST['association_id'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (empty($password) || empty($email) || empty($contact_no) || empty($department)) {
        $error = "<script>alert('Please fill in all the required fields!');</script>";
    } else {
        $sql = "UPDATE staff SET password='$password', email='$email', contact_no='$contact_no', department='$department' WHERE staff_id='$staff_id'";

        if ($conn->query($sql) === TRUE) {
            $message = "<script>alert('Staff profile updated successfully.');</script>";
        } else {
            $error = "Error editing staff info: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Staff Info</title>
<style>
    body {
    font-family: Arial, sans-serif;
    background-image: url('image/image60.jpg');
}

h1 {
    text-align: center;
    margin-top: 30px;
}

form {
    width: 500px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

input[type=text],
input[type=password],
input[type=email] {
    width: 430px; /* Adjust the width as desired */
    padding: 10px; /* Adjust the padding as desired */
    margin-bottom: 10px; /* Adjust the margin-bottom as desired */
    border-radius: 5px;
    border: 1px solid #ccc;
}


input[type=submit] {
    background: linear-gradient(135deg, #71b7e6, #9b59b6);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

input[type=submit]:hover {
    background: linear-gradient(135deg, #71b7e6, #9b59b6);
}

.error {
    color: red;
    margin-bottom: 10px;
}

.success {
    color: green;
    margin-bottom: 10px;
}
.success-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #4CAF50;
    color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  button {
    background: linear-gradient(135deg, #71b7e6, #9b59b6);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    display: inline-block;
}

button:hover {
    background: linear-gradient(135deg, #71b7e6, #9b59b6);
}

.button-container {
    text-align: center;
}

.button-container button:first-child {
    margin-right: 10px;
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
    <h1>Edit Staff Info</h1>
    <?php echo $message; ?>
    <?php echo $error; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="staff_id">Staff ID:</label>
        <input type="text" id="staff_id" name="staff_id" value="<?php echo $row['staff_id']; ?>" readonly><br><br>
        <label for="staff_name">Staff Name:</label>
        <input type="text" id="staff_name" name="staff_name" value="<?php echo $row['staff_name']; ?>" readonly><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo $row['password']; ?>"><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>"><br><br>
        <label for="contact_no">Contact Number:</label>
        <input type="text" id="contact_no" name="contact_no" value="<?php echo $row['contact_no']; ?>"><br><br>
        <label for="department">Department:</label>
        <input type="text" id="department" name="department" value="<?php echo $row['department']; ?>"readonly autocomplete="off" style="-webkit-appearance: none; -moz-appearance: none; appearance: none;"><br><br>
        <label for="association_id">Association ID:</label>
        <input type="text" id="association_id" name="association_id" value="<?php echo $row['association_id']; ?>" readonly><br><br>
        <div class="button-container">
            <button type="submit">Save Changes</button>
        
        </div>
    </form>
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
