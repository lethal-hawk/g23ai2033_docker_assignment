<?php
session_start(); // Start the session

$servername = "172.17.0.2";
$username = "root";
$password = "root";
$dbname = "student_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {       
    $studentName = $_POST["sName"];        
    $course = $_POST["degree"];           
    $college = $_POST["university"];      
    
    $stmt = $conn->prepare("INSERT INTO Student (Name, Degree, UNIVERSITY) VALUES (?, ?, ?)");
    $stmt->bind_param("sss",$studentName, $course, $college);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "New record created successfully"; // Set success message in session
    } else {
        echo "Error: " . $stmt->error;
    }
}


$sql = "SELECT * FROM Student";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px; /* Add some padding for spacing */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 800px;
            max-width: 100%;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            margin-bottom: 30px; /* Add margin bottom to separate form and table */
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555555;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select {
            width: calc(100% - 22px); /* Adjust for padding */
            padding: 10px;
            font-size: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
        }

        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Student Information</h2>
        <form id="studentForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            
            <div class="form-group">
                <label for="sName">Student Name:</label>
                <input type="text" id="sName" name="sName" required>
            </div>
            <div class="form-group">
                <label for="degree">Course:</label>
                <input type="text" id="degree" name="degree" required>
            </div>
            <div class="form-group">
                <label for="university">College:</label>
                <input type="text" id="university" name="university" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>

    <?php
    // Display success message as JavaScript alert
    if (isset($_SESSION['success_message'])) {
        echo '<script>alert("' . $_SESSION['success_message'] . '");</script>';
        unset($_SESSION['success_message']); // Clear the session variable after displaying the alert
    }
    ?>

    <?php
    // Display fetched data in a table
    if ($result && $result->num_rows > 0) {
        echo '<h2>Student Records</h2>';
        echo '<table>';
        echo '<tr><th>Roll Number</th><th>Student Name</th><th>Course</th><th>College</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["RollNumber"] . '</td>';
            echo '<td>' . $row["Name"] . '</td>';
            echo '<td>' . $row["Degree"] . '</td>';
            echo '<td>' . $row["UNIVERSITY"] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No records found.</p>';
    }
    ?>
</div>

</body>
</html>

