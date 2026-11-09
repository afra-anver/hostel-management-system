<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $room  = trim($_POST['room']);

    // 1. Empty check
    if (empty($name) || empty($email) || empty($phone) || empty($room)) {
        die("All fields are required!");
    }

    // 2. Email format check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format!");
    }

    // 3. Phone validation
    if (!is_numeric($phone) || strlen($phone) != 10) {
        die("Phone must be 10 digits!");
    }

    // 4. Duplicate email check (optional)
    $sql_check = "SELECT * FROM entries WHERE email = '$email'";
    $result = $conn->query($sql_check);
    if ($result->num_rows > 0) {
        die("Email already exists!");
    }

    // 5. Insert data
    $sql = "INSERT INTO entries (name, email, phone, room)
            VALUES ('$name', '$email', '$phone', '$room')";

    if ($conn->query($sql) === TRUE) {
        echo "<h2 style='color:green;text-align:center;margin-top:50px;'>Entry added successfully!</h2>";
        echo "<p style='text-align:center;'><a href='form.html'>← Add another entry</a></p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>