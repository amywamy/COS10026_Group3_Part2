<!-- jobs page, amy -->
<?php
include 'header.inc';
include 'settings.inc';
include 'nav.inc';

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
}
?>

<main id="jobs-main" class="jobs">
    <a href="#jobs-main" class="visually-hidden">Skip to main content of this page</a>

    <!-- Sidebar -->
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
        </ol>
        <p class="meta">Applications open now until 
            <time datetime="2025-11-07">7th of November 2025</time>.
        </p>
    </aside>

    <!-- Main Job Listing Section -->
    <section class="job-listing">
        <h2 id="open-roles">Open Positions</h2>

        <?php
        $query = "SELECT * FROM jobs ORDER BY job_code ASC";
        $jobs = mysqli_query($conn, $query);

        if (!$jobs) {
            echo "<p>Query failed: " . mysqli_error($conn) . "</p>";
        } elseif (mysqli_num_rows($jobs) == 0) {
            // Table exists but no jobs available
            echo "<p>Currently there aren't any open positions available yet. Please check back soon.</p>";
        } else {
            while ($row = mysqli_fetch_assoc($jobs)) {
                echo "<article class='job' aria-labelledby='ref-{$row['job_code']}'>
                    <header>
                        <h3 id='ref-{$row['job_code']}'>" . htmlspecialchars($row['job_code']) . " - " . htmlspecialchars($row['title']) . "
                            <span class='badge'>" . htmlspecialchars($row['status_badge']) . "</span>
                        </h3>
                        <p>" . htmlspecialchars($row['description']) . "</p>
                        <ul class='meta'>
                            <li>" . htmlspecialchars($row['employment_type']) . "</li>
                            <li>Salary: " . htmlspecialchars($row['salary_range']) . "</li>
                            <li>Reports to: " . htmlspecialchars($row['reports_to']) . "</li>
                        </ul>
                    </header>

                    <details class='collapsible'>
                        <summary>Key Responsibilities (unordered list):</summary>
                        <ul>";

                $responsibilities = explode("\n", $row['key_responsibilities']);
                foreach ($responsibilities as $resp) {
                    if (trim($resp) !== "") {
                        echo "<li>" . htmlspecialchars(trim($resp)) . "</li>";
                    }
                }

                echo "</ul>
                    </details>

                    <details class='collapsible'>
                        <summary>Requirements for the position to be considered (ordered list):</summary>
                        <ol>";
                $requirements = explode("\n", $row['requirements']);
                foreach ($requirements as $req) {
                    if (trim($req) !== "") {
                        echo "<li>" . htmlspecialchars(trim($req)) . "</li>";
                    }
                }

                echo "</ol>
                        <p class='meta'>Preferable: " . htmlspecialchars($row['preferable']) . "</p>
                    </details>

                    <!-- Optional Apply Button -->
                    <p><a class='apply-btn' href='apply.php?job_code=" . urlencode($row['job_code']) . "'>Apply Now</a></p>
                </article>";
            }
        }
        mysqli_close($conn);
        ?>
    </section>
</main>

<?php include 'footer.inc'; ?>
