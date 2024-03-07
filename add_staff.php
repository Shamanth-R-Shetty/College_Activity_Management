


<?php
// Assuming you have a database connection already established
// Replace the database credentials and connection code with your own
$dbHost = 'localhost';
$dbName = 'project';
$dbUser = 'root';
$dbPass = '';

$errorMessage = '';
$staffId = '';
$staffName = '';
$password = '';
$email = '';
$contactNo = '';
$department = '';
$associationId = '';

try {
    // Create a PDO instance to connect to your database
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the association records from the Association table
    $stmt = $db->query("SELECT association_id, association_name FROM association");
    $associations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve staff information from the form
        $staffId = $_POST['staff_id'];
        $staffName = $_POST['staff_name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $contactNo = $_POST['contact_no'];
        $department = $_POST['department'];
        $associationId = $_POST['association_id'];

        // Check if the staff ID already exists in the database
        $existingStmt = $db->prepare("SELECT COUNT(*) FROM staff WHERE staff_id = ?");
        $existingStmt->execute([$staffId]);
        $count = $existingStmt->fetchColumn();

        if ($count > 0) {
            // Staff ID already exists, display an alert and reject the submission
            echo "<script>alert('Staff record already exists.');</script>";
            // Clear the form fields
            $staffId = '';
            $staffName = '';
            $password = '';
            $email = '';
            $contactNo = '';
            $department = '';
            $associationId = '';
        } else {
            // Prepare and execute the database insertion
            $insertStmt = $db->prepare("INSERT INTO staff (staff_id, staff_name, password, email, contact_no, department, association_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->execute([$staffId, $staffName, $password, $email, $contactNo, $department, $associationId]);

            // Display success message
            echo "<script>alert('Staff record added successfully.');</script>";

            // Clear the form fields
            $staffId = '';
            $staffName = '';
            $password = '';
            $email = '';
            $contactNo = '';
            $department = '';
            $associationId = '';
        }
    }
} catch (PDOException $e) {
    // Handle any database connection or query errors
    echo 'Database Error: ' . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Staff</title>
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
          width: 110px;
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
          margin-bottom: 5px;
        }

        .user-details .input-box input{
          height: 45px;
          width: 100%;
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
        <div class="title">Add Staff</div>
        <div class="content">
            <form method="POST">
                <div class="user-details">
                    <div class="input-box">
                        <span class="details">Staff ID</span>
                        <input type="text" name="staff_id" value="<?php echo $staffId; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Staff Name</span>
                        <input type="text" name="staff_name" value="<?php echo $staffName; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Password</span>
                        <input type="password" name="password" value="<?php echo $password; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Email</span>
                        <input type="email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Contact No</span>
                        <input type="text" name="contact_no" value="<?php echo $contactNo; ?>" required>
                    </div>
                    <div class="input-box">
                        <span class="details">Department</span>
                        <input type="text" name="department" value="<?php echo $department; ?>" required>
                    </div>
                    <div class="input-box">
                    <span class="details">Association</span>
                    <select name="association_id" required>
                        <option value="">Select an Association</option>
                        <?php foreach ($associations as $association) : ?>
                            <option value="<?php echo $association['association_id']; ?>" <?php echo ($associationId === $association['association_id']) ? 'selected' : ''; ?>>
                                <?php echo $association['association_id'] . ' - ' . $association['association_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                </div>
                <div class="button">
                    <input type="submit" value="Add Staff">
                </div>
            </form>
            <?php if (!empty($errorMessage)) : ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
        </div>
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
