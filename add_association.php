<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "project");

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the values from the form
    $association_id = $_POST['association_id'];
    $association_name = $_POST['association_name'];
    $association_icon = $_FILES['association_icon']['name'];

    // Upload the association icon to the server
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["association_icon"]["name"]);
    move_uploaded_file($_FILES["association_icon"]["tmp_name"], $target_file);

    // Check if the association ID already exists
    $existing_query = "SELECT COUNT(*) FROM association WHERE association_id = '$association_id'";
    $existing_result = mysqli_query($conn, $existing_query);
    $existing_count = mysqli_fetch_array($existing_result)[0];

    if ($existing_count > 0) {
        // Association ID already exists, display alert and clear the form
        echo "<script>alert('Association record already exists');</script>";
    } else {
        // Insert the values into the database
        $sql = "INSERT INTO association (association_id, association_name, association_icon) VALUES ('$association_id', '$association_name', '$target_file')";
        if (mysqli_query($conn, $sql)) {
            // Association added successfully, display success message and clear the form
            echo "<script>alert('Association added successfully');</script>";
            $_POST = array(); // Clear form data
        } else {
            $error_message = "Error adding association: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Association</title>
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
        
        .container{
          max-width: 600px;
          width: 100%;
          background-color: #fff;
          padding: 25px 30px;
          border-radius: 5px;
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
          width: 270px;
          border-radius: 5px;
          background: linear-gradient(-135deg, #71b7e6, #9b59b6);
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
          margin-bottom: 5px;
        }
        
        .user-details .input-box input{
          height: 45px;
          width: 196%;
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
          border-radius: 5px;
          border: none;
          color: #fff;
          font-size: 18px;
          font-weight: 500;
          letter-spacing: 1px;
          cursor: pointer;
          transition: all 0.3s ease;
          background: linear-gradient(-135deg, #71b7e6, #9b59b6);
        }
        
        form .button input:hover{
          background: linear-gradient(-135deg, #9b59b6, #71b7e6);
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
        
          .content form .user-details{
            max-height: 300px;
            overflow-y: scroll;
          }
        
          .user-details::-webkit-scrollbar{
            width: 5px;
          }
        }
        
        @media(max-width: 459px){
          .container .content .category{
            flex-direction: column;
          }
        }
        .back-button {
        position: absolute;
        top: 20px;
        left: 20px;
        display: flex;
        align-items: center;
        padding: 10px;
        background: linear-gradient(-135deg, #71b7e6, #9b59b6);
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

  
   
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Add New Association</h1>
        <?php if (isset($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>
        <form method="post" enctype="multipart/form-data">
            <div class="content">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Association ID:</span>
                        <input type="text" name="association_id" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Association Name:</span>
                        <input type="text" name="association_name" required>
                    </div>
                </div>
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Association Icon:</span>
                        <input type="file" name="association_icon" accept="image/*" required>
                    </div>
                </div>
                <div class="button">
                    <input type="submit" value="Add Association">
                </div>
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
