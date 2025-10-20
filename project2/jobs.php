<!-- jobs page, amy -->
 <?php
 include 'header.inc';
 ?>

 <main id="jobs-main" class="jobs">
    <a href="#jobs-main" class="visually-hidden">Skip to main content of this page</a>
    <aside aria-labelledby="join-us">
        <h2 id="join-us">Why should you considers joining us at SDLRC?</h2>
        <p>
                Our Careers help advance inclusive, industrial connected-learnings. We embed<strong> digital
                literacies</strong> across curricula and explore new pedagogies with evidence-based practice.
        </p>
        <ul>
                <li>Flexible, supportive team dynamic and cultures</li>
                <li>Access to learning technologies and analytics sandboxes</li>
                <li>Professional development and Industrial mentoring</li>
        </ul>
        <h3>Learn more about our dynamic team at SDLRC</h3>
        <ol>
                <li><a rel="noopener" target="_blank"
                        href="https://www.swinburne.edu.au/about/strategy-initiatives/learning-teaching-at-swinburne/digital-literacies/">Digital
                        Literacies and Teaching at Swinburne
                </a></li>
        </ol>
        <p class="meta">Applications open now until <time datetime="2025-11-07">7th of November 2025</time>.</p>
    </aside>

    <section class="job-listing">
        <h2 id="open-roles">Open Positions</h2>
        <?php
        //Adding database settings from jobs table into the page display;
        $query = "SHOW TABLES LIKE 'jobs'";
        $table_exists = mysqli_query($conn, $query);

        if($table_exists && myslqi_num_rows($table_exists) > 0) {
            $jobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY job_ref ASC");
            if ($jobs && myslqi_num_rows($jobs) > 0) {
                while ($row = mysqli_fetch_assoc($jobs)) {
                    echo "<article class='job' aria-labelledby='ref-{$row['job_ref']}';
                    <header>
                    <h3 id='ref-{$row['job_ref']}'>
                    {$row['job_ref']} - " . htmlspecialchars($row[job_title]) . " <span class='badge'>" . htmlspecialchars($row['job_level']) . "</span>
                    </h3>
                    <p>". htmlspecialchars($row['job_summary']) . "</p>
                    <ul class='meta'>
                    <li>" . htmlspecialchars($row['job_type']) . "</li>
                    <li>Salary: " . htmlspecialchars($row['salary_range']) . "</li>
                    <li>Reports to: " . htmlspecialchars($row['report_to']) . "</li>
                    </ul>
                    </header>
                    <details class='collapsible'>
                    <summary>Key Responsibilities</summary>
                    <ul>" . htmlspecialchars($row['responsibilities']) . "</ul>
                    </details>
                    <details class='collapsible'>
                    <summary>Position Requirements</summary>
                    <ol>" . htmlspecialchars($row['requirements']) . "</ol>
                    <p class='meta'>Preferable: " . htmlspecialchars($row['preferable']) . "</p>
                    </details>
                    </article>";
                }
            } else {
                echo "<p> Currently there isn't any open position available yet, please check back regularly. </p>";
            }
        } 
        ?>
        </section>
    </main>
    <?php include 'footer.inc'; ?>