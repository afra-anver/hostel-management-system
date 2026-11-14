<?php
include 'config.php';

// 1. Data Fetch Panna
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM entries WHERE id=$id";
    $result = $conn->query($sql);
    
    if($result->num_rows == 0) {
        die("Record not found!");
    }
    $row = $result->fetch_assoc();
}

// 2. Data Update Panna
if(isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $room = trim($_POST['room']);

    // Server-side Validation
    if(empty($name) || empty($email) || empty($phone) || empty($room)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!is_numeric($phone) || strlen($phone) != 10) {
        $error = "Phone must be 10 digits!";
    } else {
        // Update Query
        $sql = "UPDATE entries SET name='$name', email='$email', phone='$phone', room='$room' WHERE id=$id";
        if($conn->query($sql) === TRUE){
            header("Location: view.php");
            exit();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Update Entry - Hostel Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root { --primary: #4e54c8; }
    body { background-color: #f5f6fa; }
    .navbar { background: var(--primary) !important; }
    .navbar-brand, .nav-link { color: #fff !important; font-weight: 500; }
    .card-custom {
      border: none; border-radius: 16px;
      box-shadow: 0 10px 30px rgba(78,84,200,0.15);
      border-top: 5px solid var(--primary);
    }
    .btn-primary { background-color: var(--primary) !important; border-color: var(--primary) !important; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.25rem rgba(78, 84, 200, 0.25); }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm mb-5">
    <div class="container">
      <a class="navbar-brand" href="index.html"><i class="bi bi-building"></i> Hostel Management System</a>
    </div>
  </nav>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card card-custom p-4">
          <h3 class="mb-4 text-center" style="color: var(--primary);"><i class="bi bi-pencil-square"></i> Update Student Entry</h3>
          
          <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Room Number</label>
              <input type="text" name="room" value="<?php echo htmlspecialchars($row['room']); ?>" class="form-control">
            </div>
            
            <div class="d-flex gap-2 mt-4">
              <button type="submit" name="update" class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> Update Record</button>
              <a href="view.php" class="btn btn-outline-secondary w-100">Cancel</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

</body>
</html>