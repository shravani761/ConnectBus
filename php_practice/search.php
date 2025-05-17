<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$source = $input['source'] ?? '';
$destination = $input['destination'] ?? '';

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "registrationdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT * FROM busdetails WHERE source = ? AND destination = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ss", $source, $destination);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['match' => true, 'source' => $row['source'], 'destination' => $row['destination']]);
    } else {
        echo json_encode(['match' => false]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Failed to prepare SQL statement']);
}

$conn->close();
?>
