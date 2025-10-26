<!-- jobs page, amy -->
<?php
include 'header.inc';
include 'settings.inc';
include 'nav.inc';

// Step 1: connect to the database
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("<p>❌ Database connection failed: " . mysqli_connect_error() . "</p>");
}
?>

<main id="jobs-main" class="jobs">
    <a href="#jobs-main" class="visually-hidden">Skip to main content of this page</a>

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

    <section class="job-listing">
        <h2 id="open-roles">Open Positions</h2>

        <?php
        // Step 2: verify if the table exists
        $query = "SHOW TABLES LIKE 'jobs'";
        $table_exists = mysqli_query($conn, $query);

        if ($table_exists && mysqli_num_rows($table_exists) > 0) {
            // Step 3: fetch job listings
            $jobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY job_code ASC");

            if ($jobs && mysqli_num_rows($jobs) > 0) {
                // Step 4: display each job dynamically
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

                    echo "</ul></details>
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
                    </article>";
                }
            } else {
                echo "<p>Currently there aren't any open positions available yet. Please check back soon.</p>";
            }
        } else {
            // Step 5: fallback if no database table found
        ?>
            <article class="job" aria-labelledby="ref-GOA31">
                <header>
                    <h3 id="ref-GOA31">
                        GOA31 - Digital Learning Designer <span class="badge">HEW 7</span>
                    </h3>
                    <p>Lead the design of engaging, accessible learning experiences for campus and online units.
                        You’ll partner with academics to translate subject outcomes into active, technology-enhanced
                        learning.</p>
                    <ul class="meta">
                        <li>Full-time, ongoing hiring</li>
                        <li>Salary : AUD $85,000 - $106,000 + Super</li>
                        <li>Reports to : Manager, Learning Design and Innovations</li>
                    </ul>
                </header>
                <details class="collapsible">
                    <summary>Key Responsibilities (unordered list):</summary>
                    <ul>
                        <li>Co-design curriculum using constructive alignments and UDL principles.</li>
                        <li>Build learning materials (e.g., media storyboards, LMS modules and interactive H5P etc.).</li>
                        <li>Advice on assessment design, academic integrity and authentic tasks (advice on AI integrity).</li>
                        <li>Champion accessibility (WCAG 2.2 AA) and inclusive learning practices.</li>
                        <li>Measures impact with learning analytics and iterate.</li>
                    </ul>
                </details>
                <details class="collapsible">
                    <summary>Requirements for the position to be considered (ordered list):</summary>
                    <ol>
                        <li>Degree in Education, Instructional Design or related fields.</li>
                        <li>Portfolio demonstrating high levels of understanding for end-to-end learning design.</li>
                        <li>Proficiency with LMS such as Canvas, H5P/authoring tools along with basic skills of HTML/CSS.</li>
                        <li>Knowledge of accessibility at a standard level and inclusive pedagogy.</li>
                        <li>Strong stakeholder engagement and facilitation skills.</li>
                    </ol>
                    <p class="meta">Preferable: Experienced with generative AI in assisted design and familiar with
                        media production workflow.</p>
                </details>
            </article>
        <?php
        } // end fallback
        mysqli_close($conn);
        ?>
    </section>
</main>

<?php include 'footer.inc'; ?>
