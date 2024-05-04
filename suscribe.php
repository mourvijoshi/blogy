<?php
$servername = "localhost";
$username = "hari"; // default XAMPP username
$password = "12345678";     // default XAMPP password
$dbname = "subscribe";   // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO emails (email) VALUES (?)");
$stmt->bind_param("s", $email);

// Execute the statement
if ($stmt->execute()) {
    header("Location: homepage.html?success=1");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
