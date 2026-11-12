<?php
include 'config.php';

// 1. Search Logic
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR room LIKE '%$search%'";
}

// 2. Pagination Logic
$limit = 5; // Oru page-la 5 records kaattum
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Total Records Count
$count_sql = "SELECT COUNT(id) AS total FROM entries $where_clause";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// 3. Fetch Data
$sql = "SELECT * FROM entries $where_clause ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Entries - Hostel Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --primary: #14185a;
      --primary-dark: #3b40a0;
      --accent: #3b3e7b;
      --footer-bg: #0f0e41;
    }
    body { background-color: #f5f6fa; }
    .navbar { background: var(--primary) !important; }
    .navbar-brand, .nav-link { color: #fff !important; font-weight: 500; }
    .nav-link:hover, .nav-link.active { color: #ffd369 !important; }
    
    .card-custom {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(116, 122, 238, 0.1);
    }
    .table thead {
      background-color: var(--primary);
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: rgba(78,84,200,0.05);
    }

    
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
      <a class="navbar-brand" href="index.html"><i class="bi bi-building"></i> Hostel Management System</a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="form.html">Form</a></li>
          <li class="nav-item"><a class="nav-link active" href="view.php">View Records</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="bi bi-table text-primary"></i> Student Records</h2>
      <a href="form.html" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Entry</a>
    </div>

    <!-- Search Bar -->
    <div class="card card-custom p-3 mb-4">
      <form method="GET" class="row g-2">
        <div class="col-md-10">
          <input type="text" name="search" class="form-control" placeholder="Search by Name, Email or Room..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
        </div>
      </form>
    </div>

    <!-- Data Table -->
    <div class="card card-custom p-3">
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Room No</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td><strong>{$row['name']}</strong></td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td><span class='badge bg-info text-dark'>{$row['room']}</span></td>
                        <td>" . date('d M Y, h:i A', strtotime($row['created_at'])) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center text-muted py-4'>No entries found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if($total_pages > 1): ?>
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          
          <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
            <a class="page-link" href="<?php if($page > 1){ echo "?page=".($page - 1)."&search=$search"; } ?>">Previous</a>
          </li>
          
          <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if($page == $i){ echo 'active'; } ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          
          <li class="page-item <?php if($page >= $total_pages){ echo 'disabled'; } ?>">
            <a class="page-link" href="<?php if($page < $total_pages){ echo "?page=".($page + 1)."&search=$search"; } ?>">Next</a>
          </li>
          
        </ul>
      </nav>
      <?php endif; ?>
      
    </div>
  </div>

  <!-- Footer (Day 1 & Day 2 Same Footer) -->
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
            <li><a href="view.php"><i class="bi bi-chevron-right"></i> View Records</a></li>
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
</body>
</html>