<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./assets/css/index.css">
    <style>

    </style>
</head>

<body>
    <header class="container-fluid">
        <div class="p-4">
            <div class="row">
                <div class="col-lg-2">
                    <div class="logo">
                        <img src="./assets/img/logo.png" alt="logo">

                    </div>
                </div>
                <div class="col-lg-10 ">
                    <nav class="d-flex justify-content-end">
                        <ul class="nav">
                            <li class="nav-item"><a href="#homeSection" class="nav-link active">Home</a></li>
                            <li class="nav-item"><a href="#feature" class="nav-link">Features</a></li>
                            <li class="nav-item"><a href="#role" class="nav-link">Role</a></li>
                            <li class="nav-item"><a href="#howItWork" class="nav-link">How to it work</a></li>
                            <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                            <button class=" loginBtn"><a href="./public_note.php">Get Start</a></button>
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </header>

    <div class="container main ">

        <section id="homeSection" class="my-5">
            <div class="row">
                <div class="col-lg-6 p-3">
                    COLLEGE PROJECT 2026
                    <h1><strong>Smart Notes Sharing Platform</strong></h1>
                    <h4 class="mt-3 mb-4">
                        A modern and collaborative platform designed for students and teachers to
                        upload, manage, search, and download educational notes anytime, anywhere with ease and security.
                    </h4>
                    <button class="loginBtn"><a href="./public_note.php">Get Started</a></button> &ensp;
                    <button class="loginBtn bg-secondary text-dark border"><a href="#feature"
                            class="nav-link color-primary">Features</a></button>

                </div>
                <div class="col-lg-6 p-3">
                    <div class="dash"></div>
                </div>
            </div>
        </section>

        <section id="role">
            <h2 class="text-center"><strong>User Roles & Access</strong></h2>
            <p class="text-center">
                The system is divided into three main roles to ensure smooth and organized learning management.
            </p>

            <div class="d-flex justify-content-center flex-wrap gap-5 mt-5">
                <div class="role-card">
                    <div class="role-icon">👨‍💼</div>
                    <h3>Administrator</h3>
                    <ul>
                        <li>Full system control</li>
                        <li>Manage users</li>
                        <li>Manage categories</li>
                        <li>Moderate all content</li>
                    </ul>
                </div>
                <div class="role-card">
                    <div class="role-icon">👩‍🏫</div>
                    <h3>Teacher</h3>
                    <ul>
                        <li>Upload notes</li>
                        <li>Manage notes</li>
                        <li>Publish educational content</li>
                        <li>Share resources with students</li>
                    </ul>
                </div>
                <div class="role-card">
                    <div class="role-icon">👨‍🎓</div>
                    <h3>Student</h3>
                    <ul>
                        <li>View notes</li>
                        <li>Save favorite notes</li>
                        <li>Download study materials</li>
                        <li>Access learning content anytime</li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="feature" class="mt-4">
            <h2 class="text-center"><strong>Core Features</strong></h2>
            <p class="text-center mt-2">Robust tools designed to enhance productivity and learning outcomes.
            <div class="d-flex justify-content-center flex-wrap gap-4 mt-5">
                <div class="feat-card">
                    <h4>Role-based Login</h4>
                    <p>Secure login system based on user roles (Admin, Teacher, Student).</p>
                </div>
                <div class="feat-card">
                    <h4>Separate Dashboards</h4>
                    <p>Personalized workspaces for focused task management.</p>
                </div>
                <div class="feat-card">
                    <h4>Note Management</h4>
                    <p>Upload, edit, publish, and organize educational notes easily.</p>
                </div>
                <div class="feat-card">
                    <h4>Smart Search</h4>
                    <p>Quickly find notes by title, subject, category, or keywords.</p>
                </div>
                <div class="feat-card">
                    <h4>Download System</h4>
                    <p>Instant download of study materials in various file formats.</p>
                </div>
                <div class="feat-card">
                    <h4>Save & Favorites</h4>
                    <p>Save important notes for quick access later.</p>
                </div>
                <div class="feat-card">
                    <h4>Result Tracking</h4>
                    <p>Visualize progress through historical data logs.</p>
                </div>
                <div class="feat-card">
                    <h4>Profile Update</h4>
                    <p>Update personal information and profile settings easily anytime.</p>
                </div>
            </div>
            </p>
        </section>

        <section id="howItWork">
            <h2 class="text-center"><strong>How It Works</strong></h2>
            <p class="text-center mb-3">Simple and efficient workflow for all users.</p>
            <div class="d-flex justify-content-center flex-wrap gap-4 mt-5">
                <div class="step">
                    <div class="step-num">1</div>
                    <h4>Login</h4>
                    <p>Enter your credentials to enter the secure portal.</p>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <h4>Choose Role</h4>
                    <p>System automatically identifies and sets your workspace.</p>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <h4>Dashboard</h4>
                    <p>Access your tools from a centralized control panel.</p>
                </div>
                <div class="step">
                    <div class="step-num">4</div>
                    <h4>Execution</h4>
                    <p>Conduct notes, view content, or manage data.</p>
                </div>
                <div class="step">
                    <div class="step-num">5</div>
                    <h4>Results</h4>
                    <p>Get comprehensive analytics of your activities.</p>
                </div>
            </div>
        </section>



        <section class="mt-5 p-5">
            <h2 class="text-center"><strong>Technologies Used</strong></h2>
            <div class="align-items-center d-flex justify-content-center gap-3 usesArea">
                <span class="tech-badge">HTML5</span>
                <span class="tech-badge">CSS3 (Modern)</span>
                <span class="tech-badge">JavaScript (ES6)</span>
                <span class="tech-badge">Bootstrap 5</span>
                <span class="tech-badge">Font Awesome</span>
                <span class="tech-badge">Chart.js</span>
            </div>
        </section>

        <section id="FAQ" class="mt-5 p-5">
            <h2 class="text-center"><strong>Frequently Asked Questions</strong></h2>
            <div class="accordion mt-5" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            What is this project about? </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            This is a Bachelor-level web-based Note Sharing System that allows students and teachers to
                            upload, manage, and access academic notes efficiently.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Who can use this system?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            The system supports three roles: Administrator, Teacher, and Student with different access
                            permissions.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            What is the role of an Administrator? </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            The Administrator has full control over the system including user management, category
                            management, and content moderation.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            What can teachers do in this system?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Teachers can upload, manage, and publish notes, and share educational materials with
                            students.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            What can students do?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Students can view, search, save favorites, and download study materials for learning
                            purposes.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Is login required to use the platform?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, users must log in to access role-based features of the system.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSeven">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            Can users update their profile?
                        </button>
                    </h2>
                    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, all users can update their personal profile information including password and profile
                            details.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingEight">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            What is the main purpose of this system?
                        </button>
                    </h2>
                    <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight"
                        data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            The main purpose is to simplify academic note sharing and improve collaboration between
                            students and teachers.
                        </div>
                    </div>
                </div>
        </section>


        <section id="contact" class="mt-5">
            <div class="contact-container">
                <div>
                    <h2>Get in Touch</h2>
                    <p style="margin: 20px 0; color: #64748b;">If you have any questions, please send us a message. Our
                        team will
                        contact you shortly.</p>
                    <p>📍 Kathmandu, Nepal</p>
                    <p>📧 support@smartlms.com</p>
                    <p>📞 +977 98XXXXXXXX</p>
                </div>
                <form>
                    <input type="text" placeholder="Full Name">
                    <input type="email" placeholder="Email Address">
                    <textarea rows="5" placeholder="Your Message"></textarea>
                    <button type="button" class="btn btn-primary" style="width: 100%;">Send Message</button>
                </form>
            </div>
        </section>























    </div>
    <footer class="mt-5">
        <div class="footer-grid">
            <div>
                <div class="logo" style="color: white; margin-bottom: 20px;"></div>
                <p style="color: #94a3b8; font-size: 0.9rem;">Modernizing education through technology. Trusted by 100+
                    institutions.</p>
            </div>
            <div>
                <h4>Quick Links</h4>
                <p><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.85rem;">Features</a></p>
                <p><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.85rem;">Pricing</a></p>
            </div>
            <div>
                <h4>Support</h4>
                <p><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.85rem;">Help Center</a></p>
                <p><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.85rem;">Privacy Policy</a></p>
            </div>
            <div>
                <h4>Newsletter</h4>
                <input type="text" placeholder="Enter Email" style="background: #1e293b; border: none; color: white;">
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 SmartLMS Platform. All Rights Reserved.
        </div>
    </footer>

    <script>
        // Smooth scrolling for internal links
        document.querySelectorAll('a.nav-link').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>

</html>