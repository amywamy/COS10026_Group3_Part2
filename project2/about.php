<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once 'header.inc';
include_once 'settings.php';
$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
}

$sql = "SELECT student_id, name, contribution_part1, contribution_part2 FROM about ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - SDLRC Team</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #fefefe;
            color: #222;
        }
        main {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1.5rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        h2 {
            color: #00264d;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f0f4fa;
            color: #00264d;
        }
        tr:hover {
            background-color: #f9fbfd;
        }
        .team-photo {
            text-align: center;
            margin-top: 2rem;
        }
        .team-photo img {
            border-radius: 12px;
            width: 80%;
            height: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>

<main>
    <section>
        <h2>Team Profile - SDLRC</h2>
        <p>Below are the contributions from each team member across both project parts, dynamically loaded from the database!</p>
    </section>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <section>
            <h2>Member Contributions</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Project Part 1 Contribution</th>
                        <th>Project Part 2 Contribution</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['contribution_part1']); ?></td>
                        <td><?php echo htmlspecialchars($row['contribution_part2']); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    <?php else: ?>
        <p>No contributions found in the database!</p>
    <?php endif; ?>

    <div class="team-photo">
        <img src="../project2/images/team-photo.jpg" alt="SDLRC team photo">
        <figcaption>Team SDLRC – Sarah, Amy, Nithya & Sanvidu working collaboratively.</figcaption>
    </div>
</main>
<?php include 'footer.inc';
mysqli_close($conn);
