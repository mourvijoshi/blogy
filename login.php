<?php
// Database connection
$servername = "localhost";
$username = "hari"; // Your MySQL username
$password = "12345678"; // Your MySQL password
$database = "WPL_Blogy"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL query to check if the username and password exist in the database
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // If the user exists, redirect to a specified page
    header("Location: homepage.html"); // Change 'welcome.php' to your desired page
} else {
    // If the user doesn't exist, redirect back to the login page with an alert
    echo "<script>alert('Invalid username or password!');</script>";
    echo "<script>window.location.href='login.html';</script>"; // Change 'login.html' to your login page
}

$conn->close();
?>
