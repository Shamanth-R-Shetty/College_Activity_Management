<?php
// Start session
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch activity data for dropdown list based on staff's association
$staffId = $_SESSION['user_id'] ?? '';
$sql = "SELECT activity_id, activity_name FROM activity WHERE association_id = (SELECT association_id FROM staff WHERE staff_id = '$staffId')";
$activityResult = mysqli_query($conn, $sql);

// Fetch association data for dropdown list
$sql = "SELECT association_id FROM association";
$associationResult = mysqli_query($conn, $sql);

// Form submission - Update activity data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected activity ID
    $selectedActivityId = $_POST["activity_id"];

    // Retrieve activity data based on selected ID and staff's association
    $sql = "SELECT * FROM activity WHERE activity_id = '$selectedActivityId' AND association_id = (SELECT association_id FROM staff WHERE staff_id = '$staffId')";
    $result = mysqli_query($conn, $sql);
    $activityData = mysqli_fetch_assoc($result);

    // Update activity data
    if (isset($_POST["save_changes"])) {
        // Validate form fields
        $activityName = $_POST["activity_name"];
        $description = $_POST["description"];
        $date = $_POST["date"];
        $place = $_POST["place"];

        if (empty($activityName) || empty($description) || empty($date) || empty($place)) {
            echo '<script>alert("Please enter all the required data.")</script>';
        } else {
            // Update activity data in the database
            $updateSql = "UPDATE activity SET activity_name = '$activityName', description = '$description', date = '$date', place = '$place' WHERE activity_id = '$selectedActivityId'";
            if (mysqli_query($conn, $updateSql)) {
                echo '<script>alert("Activity updated successfully.")</script>';
            } else {
                echo "Error updating activity: " . mysqli_error($conn);
            }

            // Handle image uploads
            $imagePath = "uploads/";

            // Check if any images were uploaded
            if (!empty($_FILES["images"]["name"][0])) {
                $fileCount = count($_FILES["images"]["name"]);

                // Iterate over each uploaded file
                $imagePaths = array(); // Store the image paths

                for ($i = 0; $i < $fileCount; $i++) {
                    $imageName = $_FILES["images"]["name"][$i];
                    $imageTmpName = $_FILES["images"]["tmp_name"][$i];
                    $imageDestination = $imagePath . $imageName;

                    // Move the uploaded file to the destination folder
                    if (move_uploaded_file($imageTmpName, $imageDestination)) {
                        // Store the image path in the array
                        $imagePaths[] = $imageDestination;
                    } else {
                        echo "Error uploading image: " . $_FILES["images"]["error"][$i];
                    }
                }

                // Update the image_path column in the activity table for the selected activity
                $imagePathString = implode(", ", $imagePaths);
                $updateImageSql = "UPDATE activity SET image_path = '$imagePathString' WHERE activity_id = '$selectedActivityId'";
                mysqli_query($conn, $updateImageSql);
            }

            // Clear the form
            $activityData = null;
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Modify Activity Records</title>
    <style>
         /* Insert the CSS code here */
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
        
        *{
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: 'Poppins', sans-serif;
        }
        body{
          height: 100vh;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 10px;
          background-image: url('image/image60.jpg'); 
        }

h1 {
    text-align: left;
    margin-bottom: 20px;
}



.container{
   margin-top:200px;
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
label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

select,
input[type="text"],
textarea,
input[type="date"],
input[type="file"],
input[type="submit"] {
    height:45px;
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 12px;
}

form .button{
          height: 40px;
          margin: 35px 0;
        }
        
        form .button input{
          height: 100%;
          width: 100%;
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

textarea {
    resize: vertical;
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
        <h1 class="title">Modify Activity Records</h1>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <label for="activity_id">Select Activity:</label>
            <select name="activity_id" id="activity_id">
                <?php
                // Populate dropdown list with activity IDs and names
                while ($row = mysqli_fetch_assoc($activityResult)) {
                    $activityId = $row['activity_id'];
                    $activityName = $row['activity_name'];
                    echo "<option value='$activityId'>$activityId - $activityName</option>";
                }
                ?>
            </select>
            <div class="button">
                <input type="submit" value="Show Activity Data">
            </div>
        </form>

        <?php
        // Display activity data if it has been selected
        if (isset($activityData)) {
            ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <input type="hidden" name="activity_id" value="<?php echo $activityData['activity_id']; ?>">
                <label for="activity_name">Activity Name:</label>
                <input type="text" name="activity_name" value="<?php echo $activityData['activity_name']; ?>"><br>
                <label for="description">Description:</label>
                <textarea name="description"><?php echo $activityData['description']; ?></textarea><br>
                <label for="date">Date:</label>
                <input type="date" name="date" value="<?php echo date('Y-m-d', strtotime($activityData['date'])); ?>"><br>
                <label for="place">Place:</label>
                <input type="text" name="place" value="<?php echo $activityData['place']; ?>"><br>
                <label for="images">Select Images:</label>
                <input type="file" name="images[]" multiple accept="image/*"><br>
                <div class="button">
                    <input type="submit" name="save_changes" value="Save Changes">
                </div>
            </form>
        <?php
    }
    ?>

    <button class="back-button" onclick="goToOtherPage()">
        <img src="image/image54.png" alt="Back">
        Back
    </button>

    <script>
        function goToOtherPage() {
            window.location.href = "staff_board.html";
        }
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>