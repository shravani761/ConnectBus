<?php
include 'db.php';
session_start(); // Start the session

$message = ""; // Initialize message variable

try {
    if ($link->connect_error) {
        throw new Exception("Connection failed: " . $link->connect_error);
    }

    // Retrieve and sanitize POST data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $Username = $link->real_escape_string($_POST['Username']);
        $Password = $link->real_escape_string($_POST['Password']);

        if (empty($Username) || empty($Password)) {
            $message = "All fields are required.";
        } else {
            // Using prepared statements to prevent SQL injection
            $sql = $link->prepare("SELECT * FROM users WHERE Username = ? AND Password = ?");
            $sql->bind_param("ss", $Username, $Password);

            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows > 0) {
                $_SESSION['loggedin'] = true;
                $_SESSION['Username'] = $Username; // Set session variable
                header("Location: searchbuses.php"); // Redirect to searchbuses.php
                exit();
            } else {
                $message = "Invalid username or password.";
            }
            $sql->close();
        }
    }
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
} finally {
    // Close connection if it was established
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
        input[type="text"], input[type="password"] {
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
        .message {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
    <script>
        function validateForm() {
            var username = document.getElementById('Username').value;
            var password = document.getElementById('Password').value;

            if (username == null || username == "") {
                alert("Username can't be blank");
                return false;
            } else if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="" method="post" id="loginForm" onsubmit="return validateForm()">
            <label for="Username">Username</label>
            <input type="text" id="Username" name="Username" required>

            <label for="Password">Password</label>
            <input type="password" id="Password" name="Password" required>

            <button type="submit">Login</button>
        </form>
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
    </div>
</body>
</html>
