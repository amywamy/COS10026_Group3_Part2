<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'list_all';

require_once('settings.php');
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

 function sanitize_input($data) {
    return htmlspecialchars(trim($data));
 }

if ($action == 'delete_job' && isset($_GET['job_reference'])) {
    $job_ref = sanitize_input($_GET['job_reference']);
    $sql = "DELETE FROM eoi WHERE job_reference = '$job_ref'";
    mysqli_query($conn, $sql);
    $action = 'list_all';
}

if ($action == 'update_status' && isset($_GET['eoi_number'], $_GET['status']))  {
    $eonumber = intval($_GET['eoi_number']);
    $new_status = sanitize_input($_GET['status']);
    $sql = "UPDATE eoi SET status = '$new_status' WHERE EOInumber = $eonumber";
    mysqli_query($conn, $sql);
    $action = 'list_all';
}

$where_clauses = [];
if ($action == 'search_job' && isset($_GET['job_reference'])) {
    $job_ref = sanitize_input($_GET['job_reference']);
    $where_clauses[] = "job_reference = '$job_ref'";
}

if ($action == 'search_name' && isset($_GET['name'])) {
    $name = sanitize_input($_GET['name']);
    $where_clauses[] = "(first_name LIKE '%$name%' OR last_name LIKE '%$name%')";
}

$sort_field = isset($_GET['sort']) ? $_GET['sort'] : 'EOInumber';
$allowed_sort = ['EOInumber', 'first_name', 'last_name', 'job_reference', 'status'];
if (!in_array($sort_field, $allowed_sort)) $sort_field = 'EOInumber';

$sql = "SELECT * FROM eoi";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY $sort_field ASC";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management Portal</title>   
</head>
     <h1>HR Management Portal</h1>
        <p class="welcome">Welcome, <?php echo $_SESSION['username']; ?>!</p>
    <!-- Search / Filter Forms -->
    <h2>Search / Filter EOIs</h2>
    <form method="GET" action="manage.php">
        <input type="hidden" name="action" value="search_job">
        Job Reference: <input type="text" name="job_reference">
        <input type="submit" value="Search">
    </form>

    <form method="GET" action="manage.php">
        <input type="hidden" name="action" value="search_name">
        Applicant Name: <input type="text" name="name">
        <input type="submit" value="Search">
    </form>

    <form method="GET" action="manage.php">
        <input type="hidden" name="action" value="list_all">
        Sort by:
        <select name="sort">
            <option value="EOInumber" <?php if($sort_field=='EOInumber') echo 'selected'; ?>>EOI Number</option>
            <option value="first_name" <?php if($sort_field=='first_name') echo 'selected'; ?>>First Name</option>
            <option value="last_name" <?php if($sort_field=='last_name') echo 'selected'; ?>>Last Name</option>
            <option value="job_reference" <?php if($sort_field=='job_reference') echo 'selected'; ?>>Job Reference</option>
            <option value="status" <?php if($sort_field=='status') echo 'selected'; ?>>Status</option>
        </select>
        <input type="submit" value="List All">
    </form>

    <!-- EOIs Table -->
    <h2>EOI List</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>EOI Number</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Job Reference</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
<?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['EOInumber']; ?></td>
                    <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['job_reference']); ?></td>
                    <td>
                        <form method="GET" style="display:inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="eoi_number" value="<?php echo $row['EOInumber']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Approved" <?php if($row['status']=='Approved') echo 'selected'; ?>>Approved</option>
                                <option value="Rejected" <?php if($row['status']=='Rejected') echo 'selected'; ?>>Rejected</option>
                            </select>
                            <input type="submit" value="Update">
                        </form>
                    </td>
                    <td>
                        <form method="GET" style="display:inline;">
                            <input type="hidden" name="action" value="delete_job">
                            <input type="hidden" name="job_reference" value="<?php echo $row['job_reference']; ?>">
                            <input type="submit" value="Delete" onclick="return confirm('Are you sure?');">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No EOIs found.</td></tr>
        <?php endif; ?>
    </table>

</body>
</html>

<?php
mysqli_close($conn);
?>
        
