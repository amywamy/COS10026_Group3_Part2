<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

require_once('settings.php');
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

$action = $_GET['action'] ?? 'list_all';
if ($action === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($action == 'delete_eoi' && isset($_GET['reference_id'])) {
    $ref_id = intval($_GET['reference_id']);
    $stmt = $conn->prepare("DELETE FROM eoi WHERE reference_id = ?");
    $stmt->bind_param("i", $ref_id);
    $stmt->execute();
    $stmt->close();
    $action = 'list_all';
}


if ($action == 'update_status' && isset($_GET['reference_id'], $_GET['status'])) {
    $ref_id = intval($_GET['reference_id']);
    $new_status = sanitize_input($_GET['status']);
    $stmt = $conn->prepare("UPDATE eoi SET status = ? WHERE reference_id = ?");
    $stmt->bind_param("si", $new_status, $ref_id);
    $stmt->execute();
    $stmt->close();
    $action = 'list_all';
}

$where_clauses = [];
$params = [];
$param_types = "";

if ($action == 'search_name' && isset($_GET['name'])) {
    $name = "%" . sanitize_input($_GET['name']) . "%";
    $where_clauses[] = "(given_name LIKE ? OR family_name LIKE ?)";
    $params[] = $name;
    $params[] = $name;
    $param_types .= "ss";
}

$sort_field = $_GET['sort'] ?? 'reference_id';
$allowed_sort = ['reference_id', 'given_name', 'family_name', 'status'];
if (!in_array($sort_field, $allowed_sort)) {
    $sort_field = 'reference_id';
}

$sql = "SELECT reference_id, given_name, family_name, email, skills, status FROM eoi";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}
$sql .= " ORDER BY $sort_field ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR Management Portal</title>
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
  <h1>HR Management Portal</h1>
  <p class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

  <h2>Search EOIs by Applicant Name</h2>
  <form method="GET" action="manage.php">
    <input type="hidden" name="action" value="search_name">
    <input type="text" name="name" placeholder="Enter name">
    <input type="submit" value="Search">
  </form>

  <form method="GET" action="manage.php">
    <input type="hidden" name="action" value="list_all">
    Sort by:
    <select name="sort">
      <option value="reference_id" <?php if($sort_field=='reference_id') echo 'selected'; ?>>Reference ID</option>
      <option value="given_name" <?php if($sort_field=='given_name') echo 'selected'; ?>>First Name</option>
      <option value="family_name" <?php if($sort_field=='family_name') echo 'selected'; ?>>Last Name</option>
      <option value="status" <?php if($sort_field=='status') echo 'selected'; ?>>Status</option>
    </select>
    <input type="submit" value="Sort">
  </form>

  <h2>EOI List</h2>
  <table>
    <tr>
      <th>Reference ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Email</th>
      <th>Skills</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['reference_id']; ?></td>
          <td><?php echo htmlspecialchars($row['given_name']); ?></td>
          <td><?php echo htmlspecialchars($row['family_name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['skills']); ?></td>
          <td>
            <form method="GET" style="display:inline;">
              <input type="hidden" name="action" value="update_status">
              <input type="hidden" name="reference_id" value="<?php echo $row['reference_id']; ?>">
              <select name="status">
                <option value="New" <?php if($row['status']=='New') echo 'selected'; ?>>New</option>
                <option value="Current" <?php if($row['status']=='Current') echo 'selected'; ?>>Current</option>
                <option value="Final" <?php if($row['status']=='Final') echo 'selected'; ?>>Final</option>
              </select>
              <input type="submit" class="btn-update" value="Update">
            </form>
          </td>
          <td>
            <form method="GET" style="display:inline;">
              <input type="hidden" name="action" value="delete_eoi">
              <input type="hidden" name="reference_id" value="<?php echo $row['reference_id']; ?>">
              <input type="submit" class="btn-delete" value="Delete" onclick="return confirm('Are you sure?');">
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7">No EOIs found.</td></tr>
    <?php endif; ?>
  </table>
    <a href="manage.php?action=logout" class="logout-btn">Logout</a>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
