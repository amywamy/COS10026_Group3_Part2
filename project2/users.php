<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "settings.php";

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; 

$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
}
$action = $_GET['action'] ?? 'list_all';
if ($action === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT reference_id, given_name, family_name, email, skills, status
    FROM eoi
    WHERE email = ? OR given_name = ?
    ORDER BY reference_id DESC
");
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Job Applications | SDLRC</title>
  <style>
body {
  font-family: Arial, sans-serif;
  background-color: #f7f9fb;
  margin: 2rem;
}

h1 {
  text-align: center;
  color: #00264d;
}

.welcome {
  text-align: center;
  color: #555;
  margin-bottom: 2rem;
}

form {
  margin-bottom: 1rem;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  background-color: #fff;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

th,
td {
  padding: 0.75rem;
  border-bottom: 1px solid #e0e0e0;
  text-align: left;
}

th {
  background-color: #f0f4fa;
  color: #00264d;
}

tr:hover {
  background-color: #f9fbfd;
}

select,
input[type="text"],
input[type="submit"] {
  padding: 0.4rem;
  margin: 0.2rem;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.btn-delete {
  background-color: #d9534f;
  color: white;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 4px;
  cursor: pointer;
}

.btn-delete:hover {
  background-color: #c9302c;
}

.btn-update {
  background-color: #004b87;
  color: white;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 4px;
  cursor: pointer;
}

.btn-update:hover {
  background-color: #0069d9;
}
.logout-btn {
      display: block;
      width: fit-content;
      margin: 2rem auto;
      background-color: #00264d;
      color: #fff;
      padding: 0.7rem 1.5rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease;
    }
    .logout-btn:hover {
      background-color: #004b87;
    }
</style>
</head>
<body>
  <div class="application-container">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
    <p class="subtitle">Here are your submitted job applications.</p>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Reference ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Skills</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['reference_id']); ?></td>
            <td><?php echo htmlspecialchars($row['given_name'] . " " . $row['family_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['skills']); ?></td>
            <td>
              <span class="status <?php echo strtolower($row['status']); ?>">
                <?php echo htmlspecialchars($row['status']); ?>
              </span>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="no-record">No applications found under your account.</p>
    <?php endif; ?>

    <div style="text-align:center;">
      <a href="manage.php?action=logout" class="logout-btn">Logout</a>
    </div>
  </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
