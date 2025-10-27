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
  <link rel="stylesheet" href="styles/style.css">
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
      <a href="logout.php" class="logout">Logout</a>
    </div>
  </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
