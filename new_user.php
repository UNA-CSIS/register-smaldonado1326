<?php

// session start here...
// get all 3 strings from the form (and scrub w/ validation function)
// make sure that the two password values match!
include 'validate.php';
$user = test_input($_POST['user']);
$userPwd = test_input($_POST['pwd']);
$repeat = test_input($_POST['repeat']);

if ($userPwd !== $repeat) {
    header("location:index.php");
    exit();
}


// create the password_hash using the PASSWORD_DEFAULT argument
$hash = password_hash($userPwd, PASSWORD_DEFAULT);
// login to the database
// make sure that the new user is not already in the database
// insert username and password hash into db (put the username in the session
// or make them login)

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softball";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$check = "SELECT * FROM users WHERE username = '$user'";
$result = $conn->query($check);

if ($result->num_rows > 0) {
    echo 'Username exist: <a href="register.php">Go back to registration</a>';
    exit();
}

$sql = "INSERT INTO users (username, password)
VALUES ('$user', '$hash')";

if ($conn->query($sql) === TRUE) {

    echo "New record created successfully";
    $_SESSION['username'] = $user;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>