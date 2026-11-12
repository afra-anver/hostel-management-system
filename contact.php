<?php
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    // 1. Empty check
    if ($name === "" || $email === "" || $message === "") {
        $error = "All fields are required!";
    }
    // 2. Email format check
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    // 3. Message minimum length
    elseif (strlen($message) < 5) {
        $error = "Message must be at least 5 characters!";
    }

    // 4. Insert if no error
    if ($error === "") {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            header("Location: contact.php?success=1");
            exit();
        } else {
            $error = "Failed to send message. Please try again.";
        }
        $stmt->close();
    }

    // Redirect with error message
    if ($error !== "") {
        header("Location: contact.php?error=" . urlencode($error));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact - Hostel Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #14185a;
      --primary-dark: #3b40a0;
      --accent: #8f94fb;
      --footer-bg: #0f0e41;
    }
    body { background-color: #f5f6fa; }
    .navbar { background: var(--primary) !important; }
    .navbar-brand, .nav-link { color: #fff !important; font-weight: 500; }
    .nav-link:hover, .nav-link.active { color: #ffd369 !important; }

    .contact-card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(78,84,200,0.15);
      border-top: 5px solid var(--primary);
    }
    .contact-card h2 { font-weight: 700; color: var(--primary); }
    .form-label { font-weight: 500; }
    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(78,84,200,0.25);
    }
    .btn-primary { background: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

    footer {
      background: var(--footer-bg);
      color: #cfd2e6;
      padding: 50px 0 20px;
      margin-top: 70px;
    }
    footer h5 { color: #fff; font-weight: 600; margin-bottom: 16px; }
    footer a { color: #cfd2e6; text-decoration: none; transition: color 0.2s; }
    footer a:hover { color: var(--accent); }
    footer .footer-bottom {
      border-top: 1px solid #2e2e4d;
      margin-top: 30px;
      padding-top: 16px;
      text-align: center;
      font-size: 0.9rem;
      color: #9a9cb8;
    }
    footer ul { padding-left: 0; list-style: none; }
    footer ul li { margin-bottom: 8px; }
    footer .social-icons a {
      display: inline-flex;
      width: 36px; height: 36px;
      background: #2e2e4d;
      border-radius: 50%;
      align-items: center; justify-content: center;
      margin-right: 8px;
      color: #fff;
    }
    footer .social-icons a:hover { background: var(--primary); }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="bi bi-building"></i> Hostel Management System</a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="form.html">Form</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
          <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contact -->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card contact-card p-4">
          <h2 class="mb-4 text-center"><i class="bi bi-envelope-paper"></i> Contact Us</h2>

          <!-- Success / Error message -->
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center">
              <i class="bi bi-check-circle-fill"></i> Message successfully sent!
            </div>
          <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
              <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="contact.php" onsubmit="return validateContact()">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" id="c_name" class="form-control" placeholder="Enter Name">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" id="c_email" class="form-control" placeholder="Enter Email">
            </div>
            <div class="mb-3">
              <label class="form-label">Message</label>
              <textarea name="message" id="c_message" class="form-control" rows="4" placeholder="Enter Message"></textarea>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send"></i> Send Message</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <h5><i class="bi bi-building"></i> Hostel Management System</h5>
          <p>A web application to manage hostel rooms, student details and complaints efficiently.</p>
        </div>
        <div class="col-md-4">
          <h5>Quick Links</h5>
          <ul>
            <li><a href="index.html"><i class="bi bi-chevron-right"></i> Home</a></li>
            <li><a href="form.html"><i class="bi bi-chevron-right"></i> Add Student</a></li>
            <li><a href="about.html"><i class="bi bi-chevron-right"></i> About Us</a></li>
            <li><a href="contact.php"><i class="bi bi-chevron-right"></i> Contact Us</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h5>Contact Us</h5>
          <p><i class="bi bi-geo-alt-fill"></i> No. 25, Peradeniya Road, Kandy, Sri Lanka</p>
          <p><i class="bi bi-telephone-fill"></i> +94 74 1234567</p>
          <p><i class="bi bi-envelope-fill"></i> info@hostelms.com</p>
          <div class="social-icons mt-3">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
            <a href="#"><i class="bi bi-github"></i></a>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        © 2025 Hostel Management System | Developed by <strong>Afra Anver</strong>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function validateContact() {
      let name    = document.getElementById("c_name").value.trim();
      let email   = document.getElementById("c_email").value.trim();
      let message = document.getElementById("c_message").value.trim();

      if (name === "") {
        alert("Enter Name!");
        return false;
      }
      if (email === "") {
        alert("Enter Email!");
        return false;
      }
      if (!email.includes("@") || !email.includes(".")) {
        alert("Invalid email!");
        return false;
      }
      if (message === "") {
        alert("Enter Message!");   // ✅ message empty check
        return false;
      }
      return true;
    }
  </script>
</body>
</html>