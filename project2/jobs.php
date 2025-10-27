<!-- jobs.php -->
<?php
include_once 'header.inc';
include_once 'settings.php';

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("<p> Database connection failed: " . mysqli_connect_error() . "</p>");
}
?>

<main id="jobs" class="jobs">
    <style>
    body { background-color: #fefefe; }
    </style>
    
    <a href="#jobs" class="visually-hidden">Skip to main content of this page</a>
    <aside aria-labelledby="join-us">
        <h2 id="join-us">Why should you consider joining us at SDLRC?</h2>
        <p>
            Our Careers help advance inclusive, industrial connected-learnings.
            We embed <strong>digital literacies</strong> across curricula and explore new pedagogies with evidence-based practice.
        </p>
        <ul>
            <li>Flexible, supportive team dynamic and cultures</li>
            <li>Access to learning technologies and analytics sandboxes</li>
            <li>Professional development and Industrial mentoring</li>
        </ul>
        <h3>Learn more about our dynamic team at SDLRC</h3>
        <ol>
            <li>
                <a rel="noopener" target="_blank"
                   href="https://www.swinburne.edu.au/about/strategy-initiatives/learning-teaching-at-swinburne/digital-literacies/">
                   Digital Literacies and Teaching at Swinburne
                </a>
            </li>
            <li>
                <a rel="noopener" target="_blank"
                   href="https://www.swinburne.edu.au/news/2020/09/swinburne-learning-and-adapting-in-a-changing-world/">
                   Learning and Adapting in a changing world
                </a>
            </li>
            <li>
                <a rel="noopener" target="_blank"
                   href="https://www.swinburne.edu.au/news/2020/06/student-adobe-digital-coaches-are-using-storytelling-to-improve-digital-literacy/">
                   Adobe Digital Coaches Storytelling
                </a>
            </li>
        </ol>
        <p class="meta">Applications open now until <time datetime="2025-11-07">7th of November 2025</time>.</p>
    </aside>

    <section class="job-listing">
        <h2 id="open-roles">Open Positions</h2>

        <?php
        $query = "SELECT * FROM jobs ORDER BY job_code ASC";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "<p> Query failed: " . mysqli_error($conn) . "</p>";
        } elseif (mysqli_num_rows($result) === 0) {
            echo "<p> No jobs found in the database.</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<article class='job' aria-labelledby='ref-{$row['job_code']}'>
                    <header>
                        <h3 id='ref-{$row['job_code']}'>" . htmlspecialchars($row['job_code']) . " - " . htmlspecialchars($row['title']) . "
                            <span class='badge'>" . htmlspecialchars($row['status_badge']) . "</span>
                        </h3>
                        <p>" . htmlspecialchars($row['description']) . "</p>
                        <ul class='meta'>
                            <li>Type: " . htmlspecialchars($row['employment_type']) . "</li>
                            <li>Salary: " . htmlspecialchars($row['salary_range']) . "</li>
                            <li>Reports to: " . htmlspecialchars($row['reports_to']) . "</li>
                            <li>Deadline: " . htmlspecialchars($row['application_deadline']) . "</li>
                        </ul>
                    </header>

                    <details>
                        <summary>Key Responsibilities</summary>
                        <ul>";
                $responsibilities = explode("\n", $row['key_responsibilities']);
                foreach ($responsibilities as $r) {
                    if (trim($r) !== "") echo "<li>" . htmlspecialchars(trim($r)) . "</li>";
                }
                echo "</ul>
                    </details>

                    <details>
                        <summary>Requirements</summary>
                        <ol>";
                $requirements = explode("\n", $row['requirements']);
                foreach ($requirements as $req) {
                    if (trim($req) !== "") echo "<li>" . htmlspecialchars(trim($req)) . "</li>";
                }
                echo "</ol>
                        <p><strong>Preferable:</strong> " . htmlspecialchars($row['preferable']) . "</p>
                    </details>

                    <p><a class='apply-btn' href='apply.php?job=" . urlencode($row['job_code']) . "'>Apply Now</a></p>
                </article>";
            }
        }

        mysqli_close($conn);
        ?>
    </section>
</main>

<?php include 'footer.inc'; ?>
