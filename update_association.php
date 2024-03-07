<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch association data for dropdown list
$sql = "SELECT association_id, association_name, association_icon FROM association";
$result = mysqli_query($conn, $sql);

// Form submission - Update association data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected association ID
    $selectedAssociationId = $_POST["association_id"];

    // Retrieve association data based on selected ID
    $sql = "SELECT * FROM association WHERE association_id = '$selectedAssociationId'";
    $result = mysqli_query($conn, $sql);
    $associationData = mysqli_fetch_assoc($result);

    // Update association data
    if (isset($_POST["save_changes"])) {
        $associationId = $_POST["association_id"];
        $associationName = $_POST["association_name"];

        // Validate association name
        if (empty($associationName)) {
            echo "<script>alert('Please enter the required data.');</script>";
        } else {
            // Check if a new image is selected
            if ($_FILES["association_icon"]["name"]) {
                $imagePath = "uploads/" . $_FILES["association_icon"]["name"];
                // Save the new image
                move_uploaded_file($_FILES["association_icon"]["tmp_name"], $imagePath);
                // Update the association icon path
                $updateIconSql = "UPDATE association SET association_icon = '$imagePath' WHERE association_id = '$associationId'";
                $updateIconResult = mysqli_query($conn, $updateIconSql);

                if (!$updateIconResult) {
                    echo "Error updating association icon: " . mysqli_error($conn);
                }
            }

            // Update the association name
            $updateNameSql = "UPDATE association SET association_name = '$associationName' WHERE association_id = '$associationId'";
            $updateNameResult = mysqli_query($conn, $updateNameSql);

            if ($updateNameResult) {
                echo "<script>alert('Record updated successfully.');</script>";
                // Reload the page for the selection of new association
                echo "<script>window.location.href = window.location.href;</script>";
            } else {
                echo "Error updating association data: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Association Data</title>
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
          width:320px;
          border-radius: 5px;
          background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }

        .content form .user-details{
          display: flex;
          flex-wrap: wrap;
          justify-content: space-between;
          margin: 20px 0 12px 0;
        }

        form .user-details .input-box{
          margin-bottom: 15px;
          width: calc(100% / 2 - 20px);
        }

        form .input-box span.details{
          display: block;
          font-weight: 500;
          margin-bottom: 8px;
        }

        .user-details .input-box input{
          height: 45px;
          width: 190%;
          outline: none;
          font-size: 16px;
          border-radius: 5px;
          padding-left: 15px;
          border: 1px solid #ccc;
          border-bottom-width: 2px;
          transition: all 0.3s ease;
        }

        .user-details .input-box input:focus,
        .user-details .input-box input:valid{
          border-color: #9b59b6;
        }

        form .gender-details .gender-title{
          font-size: 20px;
          font-weight: 500;
        }

        form .category{
          display: flex;
          width: 80%;
          margin: 14px 0 ;
          justify-content: space-between;
        }

        form .category label{
          display: flex;
          align-items: center;
          cursor: pointer;
        }

        form .category label .dot{
          height: 18px;
          width: 18px;
          border-radius: 50%;
          margin-right: 10px;
          background: #d9d9d9;
          border: 5px solid transparent;
          transition: all 0.3s ease;
        }

        #dot-1:checked ~ .category label .one,
        #dot-2:checked ~ .category label .two,
        #dot-3:checked ~ .category label .three{
          background: #9b59b6;
          border-color: #d9d9d9;
        }

        form input[type="radio"]{
          display: none;
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
          background: linear-gradient(135deg, #9b59b6, #71b7e6);
        }

        @media(max-width: 584px){
         .container{
          max-width: 100%;
        }

        form .user-details .input-box{
            margin-bottom: 15px;
            width: 100%;
          }

          form .category{
            width: 100%;
          }

          .category label .dot{
            margin-right: 5px;
          }
        }
        .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        padding: 10px;
        background: linear-gradient(135deg,  #9b59b6, #71b7e6);
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
    .dropdown-list{
          display: <?php echo isset($associationData) ? 'none' : 'block'; ?>;
        }

        .association-form{
          display: <?php echo isset($associationData) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <div class="container">
      <div class="title">Update Association Data</div>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" class="dropdown-list">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Select Association:</span>
                    <select name="association_id" id="association_id">
                        <?php
                        // Populate dropdown list with association IDs and names
                        while ($row = mysqli_fetch_assoc($result)) {
                            $associationId = $row['association_id'];
                            $associationName = $row['association_name'];
                            echo "<option value='$associationId'>$associationId - $associationName</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="button">
                <input type="submit" value="Show Association Data">
            </div>
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" class="association-form">
            <input type="hidden" name="association_id" value="<?php echo $associationData['association_id']; ?>">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Association Name:</span>
                    <input type="text" name="association_name" value="<?php echo $associationData['association_name']; ?>">
                </div>
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Association Icon:</span>
                    <input type="file" name="association_icon">
                </div>
            </div>
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Current Image Path:</span>
                    <span><?php echo $associationData['association_icon']; ?></span>
                </div>
            </div>
            <div class="button">
                <input type="submit" name="save_changes" value="Save Changes">
            </div>
        </form>
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