<!-- jobs page, amy -->
 <?php
 include 'header.inc';
 include 'settings.inc';
 include 'nav.inc';
 $query = "SELECT FROM * jobs";
 $result = mysqli_query($conn, $query);
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
        } else {
            //fallback if there isn't any data available yet, can be use for testing purposes
            ?>
            <article class="job" aria-labelledby="ref-G0A31">
                <header>
                    <h3 id="ref-G0A31">
                        G0A31 - Digital Learning Designer <span class="badge">HEW 7</span>
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
                        <li>Build learning materials (e.g., media storyboards, LMS modules and interactive H5P etc.).
                        </li>
                        <li>Advice on assessment design, academic integrity and authentic tasks (advice on AI integrity).
                        </li>
                        <li>Champion accessibility (WCAG 2.2 AA) and inclusive learning practices.</li>
                        <li>Measures impact with learning analytics and iterate.</li>
                    </ul>
                </details>
                <details class="collapsible">
                    <summary>Requirements for the possition to be considers (ordered list):</summary>
                    <ol>
                        <li>Degree in Education, Instructional Design or related fields.</li>
                        <li>Portfolio demonstrating high levels of understanding for end-to-end learning design.</li>
                        <li>Proficiency with LMS such as Canvas, H5P/authoring tools along with basic skills of
                            HTML/CSS.</li>
                        <li>Knowledge of accessibility at a standard level and inclusive pedagogy.</li>
                        <li>Strong stakeholder engagement and facilitation skills.</li>
                    </ol>
                    <p class="meta">Preferable: Experienced with generative AI in assisted design and familiar with
                        media production work-flow.</p>
                </details>
            </article>

            <article class="job" aria-labelledby="ref-G0A32">
                <header>
                    <h3 id="ref-G0A32">
                        G0A32 - Educational Technology Research Assistant <span class="badge">HEW 5</span>
                    </h3>
                    <p>Support mixed-methods research on digital literacies and technology-enhanced learning.
                        Contribute to study design, data collection, and dissemination alongside academic leads.
                    </p>
                    <ul class="meta">
                    <li>Part-Time, 12-month fixed contract</li>
                    <li>Salary : AUD $70,000 - $87,000 + Super</li> 
                    <li>Reports to : Director, Digital Learning Lab</li>
                    </ul>
                </header>
                <details class="collapsible">
                    <summary>Key Responsibilities (unordered list):</summary>
                    <ul>
                        <li>Conduct literature searches and maintains annotated bibliographies.</li>
                        <li>Collect and clean survey/usage data; assisting with basic statistical analysis.</li>
                        <li>Prepare research instruments and ethics documentation.</li>
                        <li>Draft figures, tables, and summary reports.</li>
                        <li>Coordinate workshops and stakeholder communication.</li>
                    </ul>
                </details>
                <details class="collapsible">
                    <summary>Requirements for the possition to be considers (ordered list):</summary>
                    <ol>
                        <li>Bachelor's Degree in Education, Data/Information Science or related fields.</li>
                        <li>Experience with Excel/Sheets and one of analysis tool (e.g. R, Python, SPSS etc.)</li>
                        <li>Clear academic writing and data-visualisation skills.</li>
                        <li>Understanding of human research ethics and data privacy.</li>
                        <li>Ability to work collaboratively in multidisciplinary teams.</li>
                    </ol>
                    <p class="meta">Preferable: familiarity with learning analytics frameworks and LMS data exports.</p>
                </details>
            </article>
        <?php } ?>
        </section>
    </main>
    <?php include 'footer.inc'; ?>