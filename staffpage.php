<!DOCTYPE html>
<html>
<head>
    <title>Staff Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            float: left;
            width: 200px;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .button {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 10px;
        }

        .right-content {
            float: right;
            width: 200px;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .right-content .button {
            margin-bottom: 10px;
        }

        h1, h2, h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <button class="button" onclick="loadContent('view-attendance')">View Attendance</button>
        <button class="button" onclick="loadManageStudents()">Manage Students</button>
        <button class="button" onclick="loadManageActivity()">Manage Activity</button>
        <button class="button" onclick="loadContent('record-attendance')">Record Attendance</button>
        <button class="button" onclick="loadContent('edit-profile')">Edit Profile</button>
    </div>

    <div class="content">
        <h1>Welcome to the Staff Page</h1>
        <div id="right-content"></div>
    </div>

    <script>
        // Function to load content in the right content area
        function loadContent(content) {
            var rightContent = document.getElementById("right-content");
            rightContent.innerHTML = "<h2>" + content + "</h2>";
        }

        // Function to load manage students content
        function loadManageStudents() {
            var rightContent = document.getElementById("right-content");
            rightContent.innerHTML = "";

            var addButton = createButton("Add Student", "add-student-btn");
            addButton.onclick = function () {
                console.log("Add Student button clicked");
            };
            rightContent.appendChild(addButton);

            var updateButton = createButton("Update Student", "update-student-btn");
            updateButton.onclick = function () {
                console.log("Update Student button clicked");
            };
            rightContent.appendChild(updateButton);

            var deleteButton = createButton("Delete Student", "delete-student-btn");
            deleteButton.onclick = function () {
                console.log("Delete Student button clicked");
            };
            rightContent.appendChild(deleteButton);
        }

        // Function to load manage activity content
        function loadManageActivity() {
            var rightContent = document.getElementById("right-content");
            rightContent.innerHTML = "";

            var addButton = createButton("Add Activity", "add-activity-btn");
            addButton.onclick = function () {
                console.log("Add Activity button clicked");
            };
            rightContent.appendChild(addButton);

            var updateButton = createButton("Update Activity", "update-activity-btn");
            updateButton.onclick = function () {
                console.log("Update Activity button clicked");
            };
            rightContent.appendChild(updateButton);

            var deleteButton = createButton("Delete Activity", "delete-activity-btn");
            deleteButton.onclick = function () {
                console.log("Delete Activity button clicked");
            };
            rightContent.appendChild(deleteButton);
        }

        // Function to create a button element
        function createButton(label, id) {
            var button = document.createElement("button");
            button.className = "button";
            button.innerText = label;
            button.id = id;
            return button;
        }
    </script>
</body>
</html>
