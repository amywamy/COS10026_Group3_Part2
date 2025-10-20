<!-- header page -->
<header class ="header" role="banner">
<div class="logo-title">
   <a href="index.php" class="logo-link">
   <img src="/images_index/Website_logo.png" alt="SDLRC Logo" class="logo-icon">
   </a>
   <div class="text-group">
   <h1 class="site-name">SDLRC</h1>
   <p class="slogan">Digital Careers for the Smart Future</p>
   </div>
</div>
<nav aria-label="Primary navigation" class="main-nav">
            <ul>
                <li><a href="index.php"<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Home</a></li>
                <li><a href="jobs.php"<?php if(basename($_SERVER['PHP_SELF']) == 'jobs.php') echo 'class="active"'; ?>>Jobs Available</a></li>
                <li><a href="apply.php"<?php if(basename($_SERVER['PHP_SELF']) == 'apply.php') echo 'class="active"'; ?>>Apply Now</a></li>
                <li><a href="about.php"<?php if(basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About Us</a></li>
                <li><a href="https://www.swinburne.edu.au/student-login/" target="_blank" rel="noopener noreferrer">Log
                        In</a></li>
            </ul>
        </nav>
</header>     