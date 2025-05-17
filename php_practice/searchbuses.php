<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: userlogin.php"); // Redirect to login page
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve data based on user input
function retrieveData($Source, $Destination, $Bustype, $conn) {
    // Prepare SQL statement with parameterized query to prevent SQL injection
    $stmt = $conn->prepare("SELECT bd.*, dr.Username AS DriverName,dr.Phone__no AS Contact FROM busdetails bd INNER JOIN driverreg dr ON bd.DriverID = dr.DriverID WHERE bd.Source = ? AND bd.Destination = ? AND bd.Bustype = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $Source, $Destination, $Bustype); // "sss" indicates the types of parameters (string in this case)

    // Execute query
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Get result set
    $result = $stmt->get_result();

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return [];
    }

    // Close statement
    $stmt->close();
}

// Function to retrieve all bus data
function retrieveAllData($conn) {
    $sql = "SELECT bd.*, dr.Username AS DriverName,dr.Phone__no AS Contact FROM busdetails bd INNER JOIN driverreg dr ON bd.DriverID = dr.DriverID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return [];
    }
}

$result = []; // Initialize result variable

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Source = $_POST["Source"];
    $Destination = $_POST["Destination"];
    $Bustype = $_POST["Bustype"];
    $result = retrieveData($Source, $Destination, $Bustype, $conn);
} else {
    // By default, display all buses
    $result = retrieveAllData($conn);
}

// Close database connection
$conn->close();
?>

<!-- HTML form to accept user input -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Buses</title>
    <style>
              body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f1f1f1;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    width: 100%;
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

input[type="text"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    width: 100%;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

.search-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
}

.results-container {
    margin-top: 20px;
}

.result {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.result:hover {
    transform: translateY(-5px);
}

.result h3 {
    margin-top: 0;
    font-size: 20px;
    color: #333;
}

.result p {
    margin: 10px 0;
    color: #666;
}

.result .bus-type {
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
}

.result .bus-type.normal {
    background-color: #007bff;
    color: #fff;
}

.result .bus-type.ac {
    background-color: #28a745;
    color: #fff;
}

.result .bus-type.luxury {
    background-color: #dc3545;
    color: #fff;
}
/* Profile dropdown */
.profile {
            position: relative;
            display: inline-block;
            float: right;
            margin: 10px;
        }

        .profile-info {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .profile-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #007bff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            margin-right: 10px;
        }

        .profile-info:hover .logout-btn {
            display: block;
        }
        .result-details {
            display: none;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .logout-btn {
            display: none;
            position: absolute;
            right: 0;
            background-color: #dc3545;
            color: #fff;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 5px;
            white-space: nowrap;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
     <script>
        // JavaScript to handle showing/hiding details on click
        document.addEventListener("DOMContentLoaded", function() {
            const results = document.querySelectorAll(".result");

            results.forEach(result => {
                result.addEventListener("click", function() {
                    // Toggle visibility of details for the clicked result
                    const details = result.querySelector(".result-details");
                    details.style.display = details.style.display === "block" ? "none" : "block";
                });
            });
        });
    </script>
</head>
<body>
<div class="profile">
    <div class="profile-info">
        <!-- Display user's first letter of username and username -->
        <div class="profile-circle">
            <h3><?php echo strtoupper($_SESSION['Username'][0]); ?></h3>
        </div>
        <span><?php echo htmlspecialchars($_SESSION['Username']); ?></span>
        <!-- Logout button -->
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Search Buses</h2>
    <!-- Search form -->
    <div class="search-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display: flex; gap: 15px; align-items: center;">
            <div>
                <label for="Source">Source</label>
                <input type="text" id="Source" name="Source" required>
            </div>
            <div>
                <label for="Destination">Destination</label>
                <input type="text" id="Destination" name="Destination" required>
            </div>
            <div>
                <label for="Bustype">Bustype</label>
                <input type="text" id="Bustype" name="Bustype" required>
            </div>
            <div>
                <button type="submit">Search</button>
            </div>
        </form>
    </div>

    <!-- Results display -->
    <div class="results-container">
        <h2>Search Results</h2>
        <?php
        if (!empty($result)) {
            foreach ($result as $bus) {
                ?>
                <div class="result">
                    <h3><?php echo htmlspecialchars($bus['Source']) ?> - <?php echo htmlspecialchars($bus['Destination']) ?></h3>
                    <p><strong>Bus Type:</strong> <span class="bus-type <?php echo strtolower($bus['Bustype']) ?>"><?php echo htmlspecialchars($bus['Bustype']) ?></span></p>
                    <p><strong>Capacity:</strong> <?php echo htmlspecialchars($bus['Capacity']) ?></p>
                    <!-- Display driver details -->
                    <div class="result-details">
                        <p><strong>Driver:</strong> <?php echo htmlspecialchars($bus['DriverName']) ?></p>
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($bus['Contact']) ?></p>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No records found.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
