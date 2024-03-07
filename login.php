<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
        <style type="text/css">
        body {
            background-image: url('image/login2.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            color: #fff;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        form {
        background-color: rgba(0,0,0,0.5);
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
        padding: 20px;
        width: 400px; /* Change this value to make the form narrower */
        }
        input[type="text"], input[type="password"] {
            background-color: transparent;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border-bottom: 4px solid red;
            padding-left: 5px;
            background-image: url('human.png');
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 20px;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.2);
            background-image: url('human.png');
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 20px;
        }
        input[type="text"]:valid, input[type="password"]:valid {
            border-bottom: 4px solid green;
        }
        input[type="text"]:invalid {
            border-bottom: 4px solid red;
        }
        input[type="text"]:invalid:focus {
            outline: none;
        }
        input[type="password"]:invalid {
            border-bottom: 4px solid red;
        }
        input[type="password"]:invalid:focus {
            outline: none;
        }
        input[type="password"] {
            background-image: url('key.png');
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 20px;
        }
        input[type="password"]:focus {
            background-image: url('key.png');
            background-repeat: no-repeat;
            background-position: right center;
            background-size: 20px;
        }
		input[type="submit"] {
        background-color: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
        padding: 10px;
        width: 100%;
        transition: background-color 0.5s ease-out;
        background-repeat: no-repeat;
        background-position: right center;
        background-size: 20px;
        }

        input[type="submit"]:hover {
        background-color: rgba(255, 255, 255, 0.6);
        }

        input[type="submit"]:active {
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.6);
        }
		.login-label {
			margin-bottom: 5px;
			padding-left: 5px;
		}
		.warning-message {
			color: red;
			font-size: 14px;
			margin-top: 5px;
			margin-bottom: 10px;
			text-align: center;
		}
        .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }
        .human-icon {
            background-image: url('human.png');
        }
        .key-icon {
            background-image: url('key.png');
        }
	</style>
</head>
<body>
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user id and password from the form
    $user_id = $_POST["user_id"];
    $password = $_POST["password"];

    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "project";
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if user id and password are in the admin table
    $sql_admin = "SELECT * FROM admin WHERE admin_id='$user_id' AND password='$password'";
    $result_admin = $conn->query($sql_admin);

    if ($result_admin->num_rows > 0) {
        // Login successful, set session and redirect to admin dashboard
        $_SESSION["user_id"] = $user_id;
        echo "<script>alert('Login successful!'); window.location.href = 'admin_board.html';</script>";
        exit;
    } else {
        // Check if user id and password are in the staff table
        $sql_staff = "SELECT * FROM staff WHERE staff_id='$user_id' AND password='$password'";
        $result_staff = $conn->query($sql_staff);
    
        if ($result_staff->num_rows > 0) {
            // Login successful, set session and redirect to staff dashboard
            $_SESSION["user_id"] = $user_id;
            echo "<script>alert('Login successful!'); window.location.href = 'staff_board.html';</script>";
            exit;
        } else {
            // Login failed, display error message and redirect to login page
            echo "<script>alert('Invalid username or password!'); window.location.href = 'login.php';</script>";
        }
    }
    

    // Close the database connection
    $conn->close();
}
?>

	<div class="container">
		<form action="" method="post" onsubmit="return validateForm()">
			<h2>Login Here</h2>
			<label class="login-label" for="user_id">User ID:</label>
			<input type="text" id="user_id" name="user_id" required pattern="[a-z0-9._%+-]+.@basck.in$" title="Enter an email id">
           

			<div class="warning-message" id="user_id_warning"></div>
			<label class="login-label" for="password">Password:</label>
			<input type="password" id="password" name="password" required pattern="[0-9]+" title="Enter a numerical value">
			<div class="warning-message" id="password_warning"></div>
			<div class="warning-message" id="password_warning"></div>
			<input type="submit" value="Login">
	</form>
	</div>
	<script type="text/javascript">
		function validateForm() {
			var userId = document.getElementById("user_id").value;
			var password = document.getElementById("password").value;
			if (userId == "" || password == "") {
				alert("Please enter both User ID and Password");
				return false;
			}
			return true;
		}
	</script>
</body>
</html>
