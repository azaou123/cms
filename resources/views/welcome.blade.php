<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Aurora Club - Where Passion Meets Purpose">
    <meta name="author" content="Aurora Club">

    <title>{{ $settings->club_name }}</title>

    <!-- CSS FILES -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/templatemo-kind-heart-charity.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #5b43d6;
            --secondary-color: #8975e9;
        }
        
        .custom-btn {
            background: var(--primary-color);
        }
        
        .custom-border-btn {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .custom-border-btn:hover {
            background: var(--primary-color);
        }
        
        .progress-bar {
            background: var(--primary-color);
        }
        
        .site-header, .site-footer {
            background: #1c1445;
        }
        
        .section-bg {
            background: #f8f6ff;
        }
        
        .volunteer-section {
            background: linear-gradient(rgba(35, 20, 90, 0.85), rgba(35, 20, 90, 0.85)), url(images/volunteer-bg.jpg);
        }
        
        .board-member {
            transition: all 0.3s;
            margin-bottom: 30px;
        }
        
        .board-member:hover {
            transform: translateY(-10px);
        }
        
        .board-image {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .custom-block-wrap {
            transition: all 0.3s;
        }
        
        .custom-block-wrap:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(91, 67, 214, 0.15);
        }
        
        .event-countdown {
            background: var(--primary-color);
            color: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .countdown-digit {
            font-size: 32px;
            font-weight: bold;
        }
        
        .countdown-text {
            font-size: 14px;
        }
        
        .testimonial-section {
            background: linear-gradient(rgba(35, 20, 90, 0.8), rgba(35, 20, 90, 0.8)), url(images/testimonial-bg.jpg);
            color: white;
        }
        
        .achievement-icon {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .carousel-caption {
            background: rgba(35, 20, 90, 0.7);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>

<body id="section_1">

    <header class="site-header">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-12 d-flex flex-wrap">
                    <p class="d-flex me-4 mb-0">
                        <i class="bi-geo-alt me-2"></i>
                        {{ $settings->address }}
                    </p>

                    <p class="d-flex mb-0">
                        <i class="bi-envelope me-2"></i>
                        <a href="mailto:info@auroraclub.org">
                        {{ $settings->email }}
                        </a>
                    </p>
                </div>

                <div class="col-lg-3 col-12 ms-auto d-lg-block d-none">
                    <ul class="social-icon">
                        @if(!empty($settings?->facebook_url))
                            <li class="social-icon-item">
                                <a href="{{ $settings->facebook_url }}" class="social-icon-link bi-facebook"></a>
                            </li>
                        @endif

                        @if(!empty($settings?->twitter_url))
                            <li class="social-icon-item">
                                <a href="{{ $settings->twitter_url }}" class="social-icon-link bi-twitter"></a>
                            </li>
                        @endif

                        @if(!empty($settings?->instagram_url))
                            <li class="social-icon-item">
                                <a href="{{ $settings->instagram_url }}" class="social-icon-link bi-instagram"></a>
                            </li>
                        @endif

                        @if(!empty($settings?->linkedin_url))
                            <li class="social-icon-item">
                                <a href="{{ $settings->linkedin_url }}" class="social-icon-link bi-linkedin"></a>
                            </li>
                        @endif

                        @if(!empty($settings?->youtube_url))
                            <li class="social-icon-item">
                                <a href="{{ $settings->youtube_url }}" class="social-icon-link bi-youtube"></a>
                            </li>
                        @endif

                        @if(!empty($settings?->tiktok_url))
                            <li class="social-icon-item">
                                <a href="{{ $settings->tiktok_url }}" class="social-icon-link bi-tiktok"></a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg bg-light shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                @if(!empty($settings?->logo_path))
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" class="logo img-fluid" alt="{{ $settings->club_name }}">
                @endif
                <span>
                    {{ $settings->club_name }}
                    <small>{{ $settings->slogan }}</small>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#top">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_2">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_3">Activities</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_4">Leadership</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_5">Events</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_6">Gallery</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_7">Contact</a>
                    </li>

                    <li class="nav-item ms-3">
                        <a class="nav-link custom-btn custom-border-btn btn" href="#section_membership">Join</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero-section hero-section-full-height">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12 p-0">
                        <div id="hero-slide" class="carousel carousel-fade slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('storage/' . $settings->cover_image_path) }}" class="carousel-image img-fluid" alt="{{ $settings->club_name }}">
                                    <div class="carousel-caption d-flex flex-column justify-content-end">
                                        <h1>{{ $settings->club_name }}</h1>
                                        <p>{{ $settings->slogan }}</p>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#hero-slide"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <button class="carousel-control-next" type="button" data-bs-target="#hero-slide"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section class="section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-10 col-12 text-center mx-auto">
                        <h2 class="mb-5">Welcome to Aurora Club</h2>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_membership" class="d-block">
                                <img src="images/icons/hands.png" class="featured-block-image img-fluid" alt="">
                                <p class="featured-block-text">Become a <strong>Member</strong></p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_3" class="d-block">
                                <img src="images/icons/heart.png" class="featured-block-image img-fluid" alt="">
                                <p class="featured-block-text"><strong>Club</strong> Activities</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0 mb-md-4">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_4" class="d-block">
                                <img src="images/icons/receive.png" class="featured-block-image img-fluid" alt="">
                                <p class="featured-block-text">Meet our <strong>Leadership</strong></p>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 mb-4 mb-lg-0">
                        <div class="featured-block d-flex justify-content-center align-items-center">
                            <a href="#section_5" class="d-block">
                                <img src="images/icons/scholarship.png" class="featured-block-image img-fluid" alt="">
                                <p class="featured-block-text">Upcoming <strong>Events</strong></p>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="cta-section section-padding section-bg">
            <div class="container">
                <div class="row justify-content-center align-items-center">

                    <div class="col-lg-5 col-12 ms-auto">
                        <h2 class="mb-0">Ready to join us? <br> Make an impact.</h2>
                    </div>

                    <div class="col-lg-5 col-12">
                        <a href="#" class="me-4">Learn about membership</a>

                        <a href="#section_membership" class="custom-btn btn smoothscroll">Apply Now</a>
                    </div>

                </div>
            </div>
        </section>

        <footer class="bg-dark text-light py-4 mt-5">
            <div class="container">
                <div class="row">
                <!-- Company Info -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-3">Company Name</h5>
                    <p class="mb-3">Providing reliable tech services since 2010. We specialize in web development, WordPress management, and digital solutions.</p>
                    <div class="d-flex">
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-twitter fs-5"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-linkedin fs-5"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-3">Links</h5>
                    <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Home</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">About</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Services</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Blog</a></li>
                    <li><a href="#" class="text-decoration-none text-secondary">Contact</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-3">Services</h5>
                    <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">WordPress</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Web Design</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Hosting</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-secondary">Maintenance</a></li>
                    <li><a href="#" class="text-decoration-none text-secondary">Security</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-3">Contact</h5>
                    <p class="mb-2"><i class="bi bi-geo-alt me-2"></i>123 Street Name, City, Country</p>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i>contact@example.com</p>
                    <p class="mb-3"><i class="bi bi-telephone me-2"></i>+1 234 567 8900</p>
                    <form>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your Email">
                        <button class="btn btn-primary" type="submit">Subscribe</button>
                    </div>
                    </form>
                </div>
                </div>
                
                <hr class="my-4 bg-secondary">
                
                <!-- Copyright -->
                <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 Company Name. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                    <a href="#" class="text-secondary me-3">Privacy Policy</a>
                    <a href="#" class="text-secondary me-3">Terms of Use</a>
                    <a href="#" class="text-secondary">Cookie Policy</a>
                    </p>
                </div>
                </div>
            </div>
        </footer>
    </main>
</body>