<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Tracker - Manage Your Inventory Effortlessly</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #212529;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            color: white;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        
        .feature-card {
            border: none;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--light-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: var(--primary-color);
        }
        
        .stats-section {
            background: var(--dark-color);
            color: white;
            padding: 4rem 0;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .footer {
            background: #343a40;
            color: white;
            padding: 3rem 0 1rem;
        }
        
        .footer h5 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top p-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">
                <i class="bi bi-box-seam me-2"></i>Inventory Tracker
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-custom me-2">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-light btn-custom">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Crafting Efficiency, Redefining Inventory.
                        <span class="text-warning">Your Business, Your Control!</span>
                    </h1>
                    <p class="lead mb-4">
                        Transform your inventory management with our powerful tracking system. 
                        Monitor stock levels, organize categories, and streamline your business operations 
                        with real-time insights and intuitive controls.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-warning btn-custom btn-lg">
                                <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-warning btn-custom btn-lg">
                                <i class="bi bi-rocket-takeoff me-2"></i>Get Started Free
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-custom btn-lg">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative">
                        <i class="bi bi-boxes display-1" style="font-size: 15rem; opacity: 0.3;"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="bi bi-graph-up-arrow text-warning" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Powerful Features for Your Business</h2>
                <p class="lead text-muted">Everything you need to manage your inventory efficiently and effectively.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h4>Item Management</h4>
                        <p class="text-muted">
                            Add, edit, and organize your inventory items with detailed information, 
                            categories, and real-time stock tracking.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-tags"></i>
                        </div>
                        <h4>Category Organization</h4>
                        <p class="text-muted">
                            Create custom categories to organize your inventory and quickly 
                            find what you need with advanced filtering options.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Real-time Analytics</h4>
                        <p class="text-muted">
                            Monitor your inventory with comprehensive dashboards, 
                            low stock alerts, and detailed analytics reports.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h4>Smart Alerts</h4>
                        <p class="text-muted">
                            Get notified when items are running low, out of stock, 
                            or need attention to prevent business disruptions.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4>Advanced Search</h4>
                        <p class="text-muted">
                            Quickly find any item with powerful search functionality, 
                            filters by category, stock level, and custom criteria.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Secure & Reliable</h4>
                        <p class="text-muted">
                            Your data is protected with enterprise-grade security, 
                            regular backups, and reliable uptime for peace of mind.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center gy-5">
                <div class="col-md-3 col-6">
                    <div class="stat-number">99%</div>
                    <h5>Uptime Reliability</h5>
                    <p class="text-secondary">Consistent performance you can count on for your business operations.</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-number">100%</div>
                    <h5>Data Security</h5>
                    <p class="text-secondary">Enterprise-grade security protecting your valuable inventory data.</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-number">24/7</div>
                    <h5>System Availability</h5>
                    <p class="text-secondary">Access your inventory anytime, anywhere with our cloud-based solution.</p>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-number">∞</div>
                    <h5>Scalability</h5>
                    <p class="text-secondary">Grow your inventory without limits. Our system scales with your business.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Ready to Transform Your Inventory Management?</h2>
                    <p class="lead mb-4">
                        Join thousands of businesses that have streamlined their operations with our 
                        powerful inventory tracking system. Start your journey to better organization today.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Easy setup in minutes</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>No credit card required</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Free forever plan available</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Expert support included</li>
                    </ul>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-custom btn-lg my-2">
                            <i class="bi bi-rocket-takeoff me-2"></i>Start Free Today
                        </a>
                    @endguest
                </div>
                <div class="col-lg-6 text-center">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-primary text-white p-4 rounded-4 h-100 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="bi bi-speedometer2 display-6"></i>
                                    <h6 class="mt-2">Fast Performance</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success text-white p-4 rounded-4 h-100 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="bi bi-graph-up display-6"></i>
                                    <h6 class="mt-2">Growth Analytics</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-warning text-dark p-4 rounded-4 h-100 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="bi bi-shield-check display-6"></i>
                                    <h6 class="mt-2">Secure Data</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info text-white p-4 rounded-4 h-100 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="bi bi-cloud display-6"></i>
                                    <h6 class="mt-2">Cloud Based</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="bi bi-box-seam me-2"></i>Inventory Tracker</h5>
                    <p class="text-secondary">
                        The complete solution for modern inventory management. 
                        Built for businesses of all sizes who value efficiency and growth.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-linkedin fs-5"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Features</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Item Management</a></li>
                        <li><a href="#">Categories</a></li>
                        <li><a href="#">Analytics</a></li>
                        <li><a href="#">Reports</a></li>
                        <li><a href="#">Alerts</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Partners</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Status</a></li>
                        <li><a href="#">Security</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Legal</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">GDPR</a></li>
                        <li><a href="#">Licenses</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 border-secondary">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-secondary mb-0">
                        &copy; {{ date('Y') }} Inventory Tracker. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-secondary mb-0">
                        Made with <i class="bi bi-heart-fill text-danger"></i> for better business management
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scrolling -->
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-primary');
            } else {
                navbar.classList.remove('bg-primary');
            }
        });
    </script>
</body>
</html>
