
<?php
 include 'db.php';
 try {
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate input
        $Username = $link->real_escape_string($_POST['Username']);
        $Email = $link->real_escape_string($_POST['Email']);
        $Password = $link->real_escape_string($_POST['Password']);
        $Phone__no = $link->real_escape_string($_POST['Phone__no']);

        // Check if any field is empty
        if (empty($Username) || empty($Email) || empty($Password) || empty($Phone__no)) {
            echo "All fields are required.";
        } else {
            // Using prepared statement to insert data
            $sql = $link->prepare("INSERT INTO users (Username, Email, Password, Phone__no) VALUES (?, ?, ?, ?)");
            $sql->bind_param("ssss", $Username, $Email, $Password, $Phone__no);

            // Execute the statement
            if ($sql->execute()) {
                echo "Record inserted successfully.";
            } else {
                throw new Exception("ERROR: Could not execute query: " . $sql->error);
            }

            $sql->close(); // Close statement
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    // Close database connection
    if (isset($link) && $link instanceof mysqli) {
        $link->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"], input[type="date"], input[type="time"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration</h2>
        <form id="registrationForm" method="post" action="register.php">
            <label for="Username">Username</label>
            <input type="text" id="Username" name="Username" required>

            <label for="Email">Email</label>
            <input type="text" id="Email" name="Email" required>

            <label for="Password">Password</label>
            <input type="password" id="Password" name="Password" required>

            <label for="Phone__no">Phone__no</label>
            <input type="text" id="Phone__no" name="Phone__no" required>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>