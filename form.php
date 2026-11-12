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

    // 4. Duplicate Email check
    $sql_check_email = "SELECT * FROM entries WHERE email = '$email'";
    $result_email = $conn->query($sql_check_email);
    if ($result_email->num_rows > 0) {
        die("Email already exists!");
    }

    
    $sql_check_phone = "SELECT * FROM entries WHERE phone = '$phone'";
    $result_phone = $conn->query($sql_check_phone);
    if ($result_phone->num_rows > 0) {
        die("Phone number already exists!");
    }

    // 6. Insert data
    $sql = "INSERT INTO entries (name, email, phone, room)
            VALUES ('$name', '$email', '$phone', '$room')";

    if ($conn->query($sql) === TRUE) {
        
        echo "<div style='max-width:500px;margin:50px auto;padding:20px;background:#d1e7dd;color:#0f5132;border-radius:8px;text-align:center;font-family:sans-serif;'>
                <h3>✅ Entry added successfully!</h3>
                <p><a href='view.php' style='color:#4e54c8; font-weight:bold;'>View All Records</a> | <a href='form.html' style='color:#4e54c8;'>Add another</a></p>
              </div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>