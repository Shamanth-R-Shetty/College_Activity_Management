<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #222;
            color:#fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
        }


        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #fff;
        }

        select,
        input[type="submit"] {
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #333;
            color: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #555;
            color: #fff;
        }

        td:first-child {
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
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
    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "project");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch associations for dropdown list
    $associationSql = "SELECT association_id, association_name FROM association";
    $associationResult = mysqli_query($conn, $associationSql);

    // Fetch student attendance data based on selected association
    $searchAssociationId = isset($_GET['association_id']) ? $_GET['association_id'] : '';

    $sql = "SELECT s.student_id, s.student_name, a.association_id, assoc.association_name, COUNT(a.activity_id) AS total_activities, s.attendance AS total_present,
            (s.attendance / COUNT(a.activity_id) * 100) AS attendance_percentage
            FROM student s
            LEFT JOIN activity a ON s.association_id = a.association_id
            LEFT JOIN association assoc ON s.association_id = assoc.association_id";

    if (!empty($searchAssociationId)) {
        $sql .= " WHERE s.association_id = '$searchAssociationId'";
    }

    $sql .= " GROUP BY s.student_id";

    $result = mysqli_query($conn, $sql);
    ?>

    <h1>View Attendance</h1>

    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="association_id">Select Association:</label>
        <select name="association_id" id="association_id">
            <option value="">All Associations</option>
            <?php
            // Populate dropdown list with association IDs and names
            while ($row = mysqli_fetch_assoc($associationResult)) {
                $associationId = $row['association_id'];
                $associationName = $row['association_name'];
                $selected = ($searchAssociationId == $associationId) ? 'selected' : '';
                echo "<option value='$associationId' $selected>$associationId - $associationName</option>";
            }
            ?>
        </select>
        <input type="submit" value="Search">
    </form>

    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Total Activities Held (Association)</th>
                <th>Total Present</th>
                <th>Attendance Percentage</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if any student attendance data found
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $studentId = $row['student_id'];
                    $studentName = $row['student_name'];
                    $associationId = $row['association_id'];
                    $associationName = $row['association_name'];
                    $totalActivities = $row['total_activities'];
                    $totalPresent = $row['total_present'];
                    $attendancePercentage = $row['attendance_percentage'];
            ?>
                    <tr>
                        <td><?php echo $studentId; ?></td>
                        <td><?php echo $studentName; ?></td>
                        <td><?php echo "($associationId - $associationName) $totalActivities"; ?></td>
                        <td><?php echo $totalPresent; ?></td>
                        <td><?php echo number_format($attendancePercentage, 2) . "%"; ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5' class='no-data'>No attendance data found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Close database connection
    mysqli_close($conn);
    ?>
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