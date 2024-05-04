<?php
// Database connection
$servername = "localhost"; // Change this if your MySQL server is hosted elsewhere
$db_username = "hari"; // Default username for MySQL
$db_password = "12345678"; // Default password for MySQL in XAMPP
$database = "WPL_Blogy"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Insert data into database
    $sql = "INSERT INTO users (fullname, username,email, password) VALUES ('$fullname', '$username','$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to homepage.html
        header("Location: homepage.html");
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
