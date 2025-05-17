<?php
session_start(); // Start the session

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['dloggedin']) || $_SESSION['dloggedin'] !== true) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$successMessage = '';
$errorMessage = '';

$link = null; // Initialize $link variable

try {
    // Attempt to connect to the database
    $link = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($link->connect_error) {
        throw new Exception("Connection failed: " . $link->connect_error);
    }

    // Retrieve and sanitize POST data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $Source = $link->real_escape_string($_POST['Source']);
        $Destination = $link->real_escape_string($_POST['Destination']);
        $Bustype = $link->real_escape_string($_POST['Bustype']);
        $Capacity = $link->real_escape_string($_POST['Capacity']);
        $DriverID = $link->real_escape_string($_POST['DriverID']); // Get selected DriverID from form

        // Validate inputs
        if (empty($Source) || empty($Destination) || empty($Bustype) || empty($Capacity) || empty($DriverID)) {
            throw new Exception("All fields are required.");
        }

        // Using prepared statements to prevent SQL injection
        $sql = $link->prepare("INSERT INTO busdetails (Source, Destination, Bustype, Capacity, DriverID) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("ssssi", $Source, $Destination, $Bustype, $Capacity, $DriverID);

        if ($sql->execute()) {
            $successMessage = "Record inserted successfully.";
            // Optionally redirect after successful insertion
            // header("Location: busdetails_list.php");
            // exit();
        } else {
            throw new Exception("ERROR: Could not execute query: " . $sql->error);
        }
        $sql->close();
    }

    // Fetch bus drivers from database
    $query = "SELECT DriverID, Username FROM driverreg";
    $result = $link->query($query);

    if (!$result) {
        throw new Exception("Error fetching bus drivers: " . $link->error);
    }

} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
} finally {
    // Close connection if it was established
    if ($link instanceof mysqli) {
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
        input[type="text"], input[type="number"], select {
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
            margin-top: 10px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
        .error-message {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
<h1>Hello Rider... <?php echo $_SESSION['Username']; ?></h1>

<div class="container">
    <h2>Enter Bus Details</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="busDetailsForm">
        <label for="Source">Source</label>
        <input type="text" id="Source" name="Source" required>

        <label for="Destination">Destination</label>
        <input type="text" id="Destination" name="Destination" required>

        <label for="Bustype">Bustype</label>
        <input type="text" id="Bustype" name="Bustype" required>

        <label for="Capacity">Capacity</label>
        <input type="number" id="Capacity" name="Capacity" min="1" required><br><br>

        <label for="DriverID">Bus Driver</label>
        <select id="DriverID" name="DriverID" required>
            <?php
            // Display options for bus drivers
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['DriverID'] . '">' . $row['Username'] . '</option>';
                }
            } else {
                echo '<option value="">No bus drivers found</option>';
            }
            ?>
        </select>

        <button type="submit">Submit</button>
    </form>

    <?php if (!empty($successMessage)) : ?>
        <div class="message"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)) : ?>
        <div class="message error-message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>
</div>

</body>
</html>
