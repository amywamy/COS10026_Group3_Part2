<!DOCTYPE html>
<html lang="en">
<style>
            * {
        box-sizing: border-box;
        margin: 0px;
        padding: 7px;
        }

    

        :root {
        --ink: #222;
        --muted: #555;
        --line: #d8d8d8;
        --brand: #05a;
        --space: clamp(0.75rem, 1.2vw + 0.25rem, 1.25rem);
        }

        /*hero section of homepage*/
    
        /* This is the background image/banner of the hero section*/
        .hero {
            background-image: url("./images/hero.jpg");
            background-size: cover;
            background-position: right center;
            text-align: left;
            padding: 0px;
            margin:  0px;
            min-height: 70vh;
        }
        /* hero.jpg is originally from: https://www.elementadvisory.com.au/careers */

        /* These are the texts in the hero section*/
        .hero_heading{
            font-size: 2.5rem;
            line-height:1.2;
            font-weight: 500;
            margin: 60px 0 60px;
            text-align: left;
           
        }
        .hero_text1{
            font-size: 1.5rem;
            line-height: 1.2;
            font-weight: 150;
            margin: 40px 0 20px;
            text-align: left;
            
        }
            /* Apply banner */
        .apply-banner{
            
            text-align: center;
            padding: 20px 0;
        }   
        /* Background */
        .bg-brand-color{
            background-color: var(--brand);
            color: #fff;
        }
        .bg-ink{
            background: var(--ink);
            color: #fff;
        }
            
        /* Testimonials Section */
        
        .testimonials{
            padding:40px 0;
        }
        .testimonials .testimonials-heading{
            width:700px;
            margin-bottom: 40px;
            text-align: left;
        }
        .testimonials-grid{
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 30px;

        }

        /*card*/

        .card{
            background: #fff;
            color: var(--ink);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>


<body>

    <!---Header Section--->
    <?php include 'header.inc'; ?>
    <a href="#home-main" class="visually-hidden">Skip to main content of this page</a>
    <main id="home-main" class="home">
        <!---Hero Section--->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h2 class="hero_heading">
                        Start your journey with SDLRC
                    </h2>
                    <p class=hero_desc>
                        At SDLRC - The School of Digital Learning & Research Careers, we dedicated to prepare students
                        for success in the digital workforce.
                        We connect professional studies with real-word opportunities along with innovation tools, and
                        specialise in career pathways in technology - enhanced learning and research opportunities.
                    </p>
                    <p class="hero_text1">
                        We are here to help you find your dream job in the digital world.
                        Explore our job listings and apply today!
                    </p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary">
                            Learn Modern
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!---Apply banner--->
        <section class="apply-banner bg-brand-color">
            <div class="container_small">
                <h2 class="section_title">Apply here!</h2>
            </div>
            <p>Fill out the application form to apply to a position</p>
        </section>



        <!---Testimonals Section--->
        <section class="testimonials bg-ink">
            <div class="container_mid">
                <h3 class="section_title">See what our past students have to say</h3>

                <div class="testimonials-grid">
                    <div class="card">
                        <p class="testimonial_text">"The job listings on SDLRC are always up-to-date and relevant. I
                            found multiple opportunities that matched my skills."</p>
                        <p class="testimonial_author"> <strong>Amber Brown</strong>
                           Project manager at Microsoft</p>
                    </div>
                    <div class="card">
                        <p class="testimonial_text">"Applying through SDLRC was a breeze. The application process was
                            straightforward, and I received timely feedback."</p>
                        <p class="testimonial_author"> <strong>Michael Johnson</strong>
                           Data analyst at Canva</p>
                    </div>
                    <div class="card">
                        <p class="testimonial_text">"SDLRC helped me land my dream job in digital marketing. The support
                            and resources they provided were invaluable."</p>
                        <p class="testimonial_author"> <strong>Clarence Smith</strong>
                           Software developer at Google</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!---Footer Section--->
    <?php include 'footer.inc'; ?>
</body>

</html>
