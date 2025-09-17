<?php
// Database connection
$host = "localhost"; // usually localhost
$user = "root";      // your db username
$pass = "password";          // your db password
$dbname = "electromart"; // your db name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(empty($name) || empty($email) || empty($password)){
    echo "All fields are required!";
    exit;
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
    echo "Email already registered!";
    exit;
}
$stmt->close();

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if($stmt->execute()){
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
