<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html>
<head>
<title>Add New Activity</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('image/image60.jpg');
    }
    .container {
        
        max-width: 600px;
        margin: 0 auto;
        padding: 15px;
        background-color: #fff;
        border-radius: 25px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
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
          width: 185px;
          border-radius: 5px;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }
        



    .title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    form {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="date"],
    textarea {
        width: 90%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-sizing: border-box;
        margin-bottom: 12px;
    }

    .button {
        text-align: left;
      
    }

    .button input[type="submit"],
    .button button {
        height:45px;
        width: 90%;
        padding: 8px 16px;
        font-size: 16px;
        font-weight: bold;
        background: linear-gradient(135deg, #71b7e6, #9b59b6);
        transition: all 0.3s ease;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
        display: inline-block;
       
       
 
    }
    form .button input:hover
    {
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

<script>
function showAlert(message) {
    alert(message);
}

function validateForm() {
    var activityId = document.getElementById("activityId").value;
    var activityName = document.getElementById("activityName").value;
    var place = document.getElementById("place").value;
    var date = document.getElementById("date").value;
    var images = document.getElementById("images").value;

    if (activityId === "" || activityName === "" || place === "" || date === "" || images === "") {
        showAlert("Please enter all the required data.");
        return false;
    }

    return true;
}
</script>
</head>
<body>
<div class="container">
    <h2 class="title">Add New Activity</h2>
    <form action="process_activity.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <label for="activityId">Activity ID:</label>
        <input type="text" id="activityId" name="activityId" required><br>
        <label for="activityName">Activity Name:</label>
        <input type="text" id="activityName" name="activityName" required><br>
        <label for="place">Place:</label>
        <input type="text" id="place" name="place" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br>
        <label for="association">Association:</label>
        <?php
        // Establish database connection
        $connection = mysqli_connect("localhost", "root", "", "project");
        // Check the connection
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // Retrieve the association ID based on staff ID from the database
        $staffId = $_SESSION['user_id'] ?? ''; // Get the staff ID from session or set it to an empty string
        $query = "SELECT association_id FROM staff WHERE staff_id = '$staffId'";
        $result = mysqli_query($connection, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $associationId = $row['association_id'];
            echo '<input type="text" id="association" name="association" value="' . $associationId . '" readonly><br>';
        } else {
            echo '<input type="text" id="association" name="association" readonly><br>';
        }
        // Close the database connection
        mysqli_close($connection);
        ?>
        <label for="images">Images:</label>
        <input type="file" id="images" name="images[]" multiple><br>
        <div class="button">
            <input type="submit" name="submit" value="Add Activity" class="add-button">
            
        </div>
    </form>
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