 <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>DocMe</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset($homepage->where('name', 'favicon_image')->first()->image_path ?? 'imgs/icon.png')}}" rel="icon">
  <link href="{{ asset('imgs/docme_new_logo.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

 <!-- Vendor CSS Files -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/css/glightbox.min.css" rel="stylesheet">
<link href="https://unpkg.com/swiper/swiper-bundle.min.css" rel="stylesheet">


  <!-- Main CSS File -->
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="/" class="logo d-flex align-items-center me-auto me-xl-0">
       <img src="{{ asset($homepage->where('name', 'nav_image')->first()->image_path ?? 'imgs/docme_new_logo.png')}}" alt="">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#features">Features</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <li><a href="#contact" style="padding-right: 25px;">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" style="background-color: #3b68b2; color: white;" href="{{ route('login') }}">Get Started</a>

    </div>
  </header>

  <main class="main">
    <section id="hero" class="hero section">

      <div class="container-fluid" data-aos="" data-aos-delay="100">

        <div class="container" >

          <div class="row align-items-center align-items-stretch">
            <div class="col-lg-4">
              <div class="hero-content" data-aos="" data-aos-delay="200">
                <h1 class="mb-4">
                  {!! nl2br(e($homepage->where('name', 'home_title')->first()->content ?? 'Maecenas Vitae
                  Consectetur Led
                  Vestibulum Ante')) !!}
                </h1>
                <p class="mb-4 mb-md-5">
                  {!! nl2br(e($homepage->where('name', 'home_subtitle')->first()->content ?? 'Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt.
                  Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna.')) !!}
                </p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="hero-image" data-aos="" data-aos-delay="300">
                <img src="{{ asset($homepage->where('name', 'home_image')->first()->image_path ?? 'img/illustration-1.webp')}}" alt="Hero Image" class="img-fluid">
              </div>
            </div>
            <div class="col-lg-4 d-flex flex-column justify-content-end">
              <div class="banner-data" data-aos="" data-aos-delay="300">
                <h3>Working For Your Success</h3>
                <div class="banner-box">
                  <h4>15+ Years</h4>
                  <label>Of experience in business service</label>
                  <a class="banner-started" href="{{ route('login') }}">Get Started</a>
                  <a class="banner-free-trial" href="{{ route('login') }}">Start Free Trial</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row stats-row">
          <div class="container">
            <div class="row">
              @for ($i = 1; $i <= 4; $i++)
                <div class="col-lg-3 col-md-6">
                  <div class="stat-item card-style-{{ $i }} position-relative">
                    <div class="stat-content">
                      <h4>{{ $homepage->where('name', 'icon_title' . $i)->first()->content ?? 'Title' }}</h4>
                      <p>{{ $homepage->where('name', 'icon_desc' . $i)->first()->content ?? 'Description' }}</p>
                    </div>
                    <div class="stat-icon position-absolute">
                      @php $iconImage = $homepage->where('name', 'icon_image' . $i)->first()->image_path ?? ''; @endphp
                      @if ($iconImage)
                        <img src="{{ asset($iconImage) }}" alt="Stat Icon">
                      @else
                        <i class="bi bi-trophy-fill"></i>
                      @endif
                    </div>
                  </div>
                </div>
              @endfor
            </div>
          </div>
        </div>

      </div>
    </section><!-- /Hero Section -->


    <!-- Features Section -->
    <section id="features" class="features section mt-5">
      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>{!! nl2br(e($homepage->where('name', 'feature_title')->first()->content ?? 'Features')) !!}</h2>
        <p>{!! nl2br(e($homepage->where('name', 'feature_description')->first()->content ?? 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit')) !!}</p>
      </div><!-- End Section Title -->

      <div id="about" class="container features-parts mt-5">

        <div class="d-flex justify-content-center" style="margin-top: -83px">

          <ul class="nav nav-tabs" data-aos="fade-up" data-aos-delay="100">

            <li class="nav-item">
              <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                <h4>{!! nl2br(e($homepage->where('name', 'feat_nav1')->first()->content ?? 'Modisit')) !!}</h4>
              </a>
            </li><!-- End tab nav item -->

            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                <h4>{!! nl2br(e($homepage->where('name', 'feat_nav2')->first()->content ?? 'Praesenti')) !!}</h4>
              </a><!-- End tab nav item -->

            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
                <h4>{!! nl2br(e($homepage->where('name', 'feat_nav3')->first()->content ?? 'Explica')) !!}</h4>
              </a>
            </li><!-- End tab nav item -->

          </ul>

        </div>

        <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

          <div class="tab-pane fade active show" id="features-tab-1">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3> {!! nl2br(e($homepage->where('name', 'nav_title1')->first()->content ?? 'Voluptatem dignissimos provident ')) !!}</h3>
                <p class="fst-italic"></i> {!! nl2br(e($homepage->where('name', 'nav_desc1')->first()->content ?? ' Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                  magna aliqua.')) !!}</p>
                <ul>
                  <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check1')->first()->content ?? 'Ullamco laboris nisi ut aliquip ex ea commodo consequat.')) !!}</span></li>
                  <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check4')->first()->content ?? 'Duis aute irure dolor in reprehenderit in voluptate velit.')) !!} </span></li>
                  <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check7')->first()->content ?? 'Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.')) !!}</span></li>
                </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="{{ asset($homepage->where('name', 'nav_image1')->first()->image_path ?? 'img/features-illustration-1.webp')}}" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

          <div class="tab-pane fade" id="features-tab-2">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>{!! nl2br(e($homepage->where('name', 'nav_title2')->first()->content ?? 'Neque exercitationem debitis')) !!}</h3>
                <p class="fst-italic">{!! nl2br(e($homepage->where('name', 'nav_desc2')->first()->content ?? ' Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                  magna aliqua.')) !!}</p>
                <ul>
                  <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check2')->first()->content ?? 'Ullamco laboris nisi ut aliquip ex ea commodo consequat.')) !!} </span></li>
                  <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check5')->first()->content ?? 'Duis aute irure dolor in reprehenderit in voluptate velit.')) !!}</span></li>
                  <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check8')->first()->content ?? 'Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.')) !!} </span></li>
                </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="{{ asset($homepage->where('name', 'nav_image2')->first()->image_path ?? 'img/features-illustration-2.webp')}}" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

          <div class="tab-pane fade" id="features-tab-3">
            <div class="row">
              <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0 d-flex flex-column justify-content-center">
                <h3>{!! nl2br(e($homepage->where('name', 'nav_title3')->first()->content ?? 'Voluptatibus commodi accusamu')) !!}</h3>
                <p class="fst-italic">
                    {!! nl2br(e($homepage->where('name', 'nav_desc3')->first()->content ?? ' Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                  magna aliqua.')) !!}
                </p>
                <ul>
                    <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check3')->first()->content ?? 'Ullamco laboris nisi ut aliquip ex ea commodo consequat.')) !!} </span></li>
                    <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check6')->first()->content ?? 'Duis aute irure dolor in reprehenderit in voluptate velit.')) !!}</span></li>
                    <li><i class="bi bi-check2-all"></i> <span> {!! nl2br(e($homepage->where('name', 'nav_check9')->first()->content ?? 'Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.')) !!} </span></li>
                  </ul>
              </div>
              <div class="col-lg-6 order-1 order-lg-2 text-center">
                <img src="{{ asset($homepage->where('name', 'nav_image3')->first()->image_path ?? 'img/features-illustration-3.webp ')}}" alt="" class="img-fluid">
              </div>
            </div>
          </div><!-- End tab content item -->

        </div>

      </div>

    </section><!-- /Features Section -->

    <section id="features" class="why-choose features section mt-5">
      <div class="container contents">

        <div class="row">
          <div class="col-lg-6">

            <h3>Why Choose <br>.docME?</h3>
            <p class="pt-2">We understand the demands of sophisticated, time-conscious professionals. That’s why .docME has been designed to simplify complexity, streamline your workflow, and safeguard your data—without the learning curve.</p>

            <ul class="pt-2 pb-4">
              <li>Centralised Document Management</li>
              <li>Bank-Grade Security</li>
              <li>Instant, Controlled Sharing</li>
              <li>Intelligent Folder Structures</li>
              <li>Saves Time and Reduces Admin</li>
              <li>Built for Ambitious Professionals</li>
            </ul>

            <a href="" class="why-choose-btn">Get Started</a>

          </div>

          <div class="col-lg-6 features-cards" id="features-cards">
            <div class="container">
              <div class="row gy-4">
                <div class="col-xl-6 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                  <div class="feature-box orange">
                      @php
                              $iconImage = $homepage->where('name', 'card_image1')->first()->image_path ?? '';
                          @endphp
                           @if ($iconImage)
                              <img src="{{ asset($iconImage) }}"
                                  alt="Stat Icon"
                                  style="width: 40px; height: 40px; object-fit: contain;">
                          @else
                              <i class="bi bi-award"></i>
                          @endif

                    <h4>{{ $homepage->where('name', 'card_title1')->first()->content ?? 'Corporis voluptates' }} </h4>
                    <p> {{ $homepage->where('name', 'card_desc1')->first()->content ?? 'Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip' }}</p>
                  </div>
                </div><!-- End Feature Borx-->
                <div class="col-xl-6 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                  <div class="feature-box blue">
                      @php
                          $iconImage = $homepage->where('name', 'card_image2')->first()->image_path ?? '';
                      @endphp
                      @if ($iconImage)
                          <img src="{{ asset($iconImage) }}"
                              alt="Stat Icon"
                              style="width: 40px; height: 40px; object-fit: contain;">
                      @else
                      <i class="bi bi-patch-check"></i>
                      @endif
                    <h4>{{ $homepage->where('name', 'card_title2')->first()->content ?? 'Explicabo consectetur' }} </h4>
                    <p> {{ $homepage->where('name', 'card_desc2')->first()->content ?? 'Est autem dicta beatae suscipit. Sint veritatis et sit quasi ab aut inventore' }}</p>
                  </div>
                </div><!-- End Feature Borx-->
                <div class="col-xl-6 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                  <div class="feature-box green">
                      @php
                          $iconImage = $homepage->where('name', 'card_image3')->first()->image_path ?? '';
                      @endphp
                      @if ($iconImage)
                          <img src="{{ asset($iconImage) }}"
                              alt="Stat Icon"
                              style="width: 40px; height: 40px; object-fit: contain;">
                      @else
                      <i class="bi bi-sunrise"></i>
                      @endif

                      <h4>{{ $homepage->where('name', 'card_title3')->first()->content ?? 'Ullamco laboris' }} </h4>
                      <p> {{ $homepage->where('name', 'card_desc3')->first()->content ?? 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt' }}</p>
                  </div>
                </div><!-- End Feature Borx-->
                <div class="col-xl-6 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                  <div class="feature-box red">
                      @php
                          $iconImage = $homepage->where('name', 'card_image4')->first()->image_path ?? '';
                      @endphp
                      @if ($iconImage)
                          <img src="{{ asset($iconImage) }}"
                              alt="Stat Icon"
                              style="width: 40px; height: 40px; object-fit: contain;">
                      @else
                      <i class="bi bi-shield-check"></i>
                      @endif
                      <h4>{{ $homepage->where('name', 'card_title4')->first()->content ?? 'Labore consequatur' }} </h4>
                      <p> {{ $homepage->where('name', 'card_desc4')->first()->content ?? 'Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere' }}</p>

                  </div>
                </div><!-- End Feature Borx-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="section-container pricing section light-background">

      <!-- Section Title -->
      <div class="pricing-bg">
        <div class="container section-title" data-aos="fade-up">
          <h2>{!! nl2br(e($homepage->where('name', 'price_title')->first()->content ?? 'Pricing')) !!}</h2>
          <p>{!! nl2br(e($homepage->where('name', 'price_description')->first()->content ?? 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit')) !!}</p>
        </div><!-- End Section Title -->
      </div><!-- End Section Title -->

      <div class="container pricing-boxes" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4 justify-content-center">

          <!-- Basic Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card">
              <h3>Basic Plan</h3>
              <div class="price">
                <span class="currency">$</span>
                <span class="amount">{!! nl2br(e($homepage->where('name', 'price_day')->first()->content ?? '1.00')) !!}</span>
                <span class="period">/ day</span>
              </div>
              <p class="description">{!! nl2br(e($homepage->where('name', 'price_desc1')->first()->content ?? 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam.')) !!}</p>
              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state1')->first()->content ?? 'Duis aute irure dolor')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state4')->first()->content ?? ' Excepteur sint occaecat')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state7')->first()->content ?? ' Nemo enim ipsam voluptatem')) !!}
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    {!! nl2br(e($homepage->where('name', 'include_state10')->first()->content ?? ' Nemo enim ipsam voluptatem')) !!}
                </li>
              </ul>

              <a href="https://buy.stripe.com/7sYcN54Znc214m00sAbII01" class="btn " style="background-color:linear-gradient(90deg, #ed1d7e , #3b68b2);">
                Buy Now
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Standard Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="pricing-card popular">
              <div class="popular-badge">Most Popular</div>
              <h3>Standard Planss</h3>
              <div class="price">
                <span class="currency">$</span>
                <span class="amount">{!! nl2br(e($homepage->where('name', 'price_month')->first()->content ?? '4.00')) !!}</span>
                <span class="period">/ month</span>
              </div>
              <p class="description">{!! nl2br(e($homepage->where('name', 'price_desc2')->first()->content ?? 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum.')) !!} </p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state2')->first()->content ?? 'Lorem ipsum dolor sit amet')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state5')->first()->content ?? 'Consectetur adipiscing elit')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state8')->first()->content ?? 'Sed do eiusmod tempor')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state11')->first()->content ?? ' Ut labore et dolore magna')) !!}
                </li>
              </ul>

               <a href="https://buy.stripe.com/dRm7sL4Znc21bOs5MUbII03" class="get-started-btn" style="background-color: #3b68b2; color: white;">
                 Buy Now
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Premium Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="pricing-card">
              <h3>Premium Plan</h3>
              <div class="price">
                <span class="currency">$</span>
                <span class="amount">{!! nl2br(e($homepage->where('name', 'price_year')->first()->content ?? '52.00')) !!}</span>
                <span class="period">/ month</span>
              </div>
              <p class="description">{!! nl2br(e($homepage->where('name', 'price_desc3')->first()->content ?? 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae.')) !!}</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state3')->first()->content ?? '  Saepe eveniet ut et voluptates ')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state6')->first()->content ?? 'Nam libero tempore soluta')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state9')->first()->content ?? ' Cumque nihil impedit quo ')) !!}
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {!! nl2br(e($homepage->where('name', 'include_state12')->first()->content ?? '  Maxime placeat facere possimus')) !!}
                </li>
              </ul>

              <a href="{{route('premium.plan')}}" class="btn " style="background-color: #3b68b2; color: white;">
                Buy Now
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Pricing Section -->

    <!-- Features 2 Section -->
    <section id="features-2" class="features-2 section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">

          <div class="col-lg-4">

            <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="200">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-content">
                  <h3>{{ $homepage->where('name', 'footer_title1')->first()->content ?? 'Use On Any Device' }} </h3>
                  <p>{{ $homepage->where('name', 'footer_desc1')->first()->content ?? 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In ac dui quis mi consectetuer lacinia' }} </p>
                </div>
                <div class="feature-icon flex-shrink-0">
                    @php
                        $iconImage = $homepage->where('name', 'footer_image1')->first()->image_path ?? '';
                    @endphp
                    @if ($iconImage)
                        <img src="{{ asset($iconImage) }}"
                            alt="Stat Icon"
                            style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                    <i class="bi bi-display"></i>
                    @endif
                </div>
              </div>
            </div><!-- End .feature-item -->

            <div class="feature-item text-end mb-5" data-aos="fade-right" data-aos-delay="300">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-content">
                    <h3>{{ $homepage->where('name', 'footer_title2')->first()->content ?? 'Feather Icons' }} </h3>
                    <p>{{ $homepage->where('name', 'footer_desc2')->first()->content ?? 'Phasellus ullamcorper ipsum rutrum nunc nunc nonummy metus vestibulum volutpat sapien arcu sed augue aliquam erat volutpat.' }} </p>
                </div>
                <div class="feature-icon flex-shrink-0">
                    @php
                        $iconImage = $homepage->where('name', 'footer_image2')->first()->image_path ?? '';
                    @endphp
                    @if ($iconImage)
                        <img src="{{ asset($iconImage) }}"
                            alt="Stat Icon"
                            style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                    <i class="bi bi-feather"></i>
                    @endif

                </div>
              </div>
            </div><!-- End .feature-item -->

            <div class="feature-item text-end" data-aos="fade-right" data-aos-delay="400">
              <div class="d-flex align-items-center justify-content-end gap-4">
                <div class="feature-content">
                    <h3>{{ $homepage->where('name', 'footer_title3')->first()->content ?? 'Retina Ready' }} </h3>
                  <p>{{ $homepage->where('name', 'footer_desc3')->first()->content ?? 'Aenean tellus metus bibendum sed posuere ac mattis non nunc vestibulum fringilla purus sit amet fermentum aenean commodo.' }} </p>
                </div>
                <div class="feature-icon flex-shrink-0">
                    @php
                        $iconImage = $homepage->where('name', 'footer_image3')->first()->image_path ?? '';
                    @endphp
                    @if ($iconImage)
                        <img src="{{ asset($iconImage) }}"
                            alt="Stat Icon"
                            style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                        <i class="bi bi-eye"></i>
                    @endif
                </div>
              </div>
            </div><!-- End .feature-item -->

          </div>

          <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="phone-mockup text-center">
              <img src="{{ asset($homepage->where('name', 'mock_image')->first()->image_path ?? 'img/phone-app-screen.webp')}}" alt="Phone Mockup" class="img-fluid">
            </div>
          </div><!-- End Phone Mockup -->

          <div class="col-lg-4">

            <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="200">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                   @php
                        $iconImage = $homepage->where('name', 'footer_image4')->first()->image_path ?? '';
                    @endphp
                    @if ($iconImage)
                        <img src="{{ asset($iconImage) }}"
                            alt="Stat Icon"
                            style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                        <i class="bi bi-code-square"></i>
                    @endif
                </div>
                <div class="feature-content">
                    <h3>{{ $homepage->where('name', 'footer_title4')->first()->content ?? 'W3c Valid Code' }} </h3>
                  <p>{{ $homepage->where('name', 'footer_desc4')->first()->content ?? 'Donec vitae sapien ut libero venenatis faucibus nullam quis ante etiam sit amet orci eget eros faucibus tincidunt.' }} </p>
                </div>
              </div>
            </div><!-- End .feature-item -->

            <div class="feature-item mb-5" data-aos="fade-left" data-aos-delay="300">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                   @php
                        $iconImage = $homepage->where('name', 'footer_image5')->first()->image_path ?? '';
                    @endphp
                    @if ($iconImage)
                        <img src="{{ asset($iconImage) }}"
                            alt="Stat Icon"
                            style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                        <i class="bi bi-phone"></i>
                    @endif
                </div>
                <div class="feature-content">
                    <h3>{{ $homepage->where('name', 'footer_title5')->first()->content ?? 'Fully Responsive' }} </h3>
                  <p>{{ $homepage->where('name', 'footer_desc5')->first()->content ?? 'Maecenas tempus tellus eget condimentum rhoncus sem quam semper libero sit amet adipiscing sem neque sed ipsum.' }} </p>
                </div>
              </div>
            </div><!-- End .feature-item -->

            <div class="feature-item" data-aos="fade-left" data-aos-delay="400">
              <div class="d-flex align-items-center gap-4">
                <div class="feature-icon flex-shrink-0">
                  @php
                        $iconImage = $homepage->where('name', 'footer_image6')->first()->image_path ?? '';
                    @endphp
                    @if ($iconImage)
                        <img src="{{ asset($iconImage) }}"
                            alt="Stat Icon"
                            style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                      <i class="bi bi-browser-chrome"></i>
                    @endif
                </div>
                <div class="feature-content">

                    <h3>{{ $homepage->where('name', 'footer_title6')->first()->content ?? 'Browser Compatibility' }} </h3>
                  <p>{{ $homepage->where('name', 'footer_desc6')->first()->content ?? 'Nullam dictum felis eu pede mollis pretium integer tincidunt cras dapibus vivamus elementum semper nisi aenean vulputate.' }} </p>
                </div>
              </div>
            </div><!-- End .feature-item -->

          </div>
        </div>

      </div>

    </section><!-- /Features 2 Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row content justify-content-center align-items-center position-relative">
          <div class="col-lg-8 mx-auto text-center">
            <h2 class="display-4 mb-4"> {{ $homepage->where('name', 'call_title')->first()->content ?? 'Maecenas tempus tellus eget condimentum' }}</h2>
            <p class="mb-4"> {{ $homepage->where('name', 'call_description')->first()->content ?? 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel' }} </p>
            <a href="#" class="btn btn-cta">Call To Action</a>
          </div>

          <!-- Abstract Background Elements -->
          <div class="shape shape-1">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path d="M47.1,-57.1C59.9,-45.6,68.5,-28.9,71.4,-10.9C74.2,7.1,71.3,26.3,61.5,41.1C51.7,55.9,35,66.2,16.9,69.2C-1.3,72.2,-21,67.8,-36.9,57.9C-52.8,48,-64.9,32.6,-69.1,15.1C-73.3,-2.4,-69.5,-22,-59.4,-37.1C-49.3,-52.2,-32.8,-62.9,-15.7,-64.9C1.5,-67,34.3,-68.5,47.1,-57.1Z" transform="translate(100 100)"></path>
            </svg>
          </div>

          <div class="shape shape-2">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path d="M41.3,-49.1C54.4,-39.3,66.6,-27.2,71.1,-12.1C75.6,3,72.4,20.9,63.3,34.4C54.2,47.9,39.2,56.9,23.2,62.3C7.1,67.7,-10,69.4,-24.8,64.1C-39.7,58.8,-52.3,46.5,-60.1,31.5C-67.9,16.4,-70.9,-1.4,-66.3,-16.6C-61.8,-31.8,-49.7,-44.3,-36.3,-54C-22.9,-63.7,-8.2,-70.6,3.6,-75.1C15.4,-79.6,28.2,-58.9,41.3,-49.1Z" transform="translate(100 100)"></path>
            </svg>
          </div>

          <!-- Dot Pattern Groups -->
          <div class="dots dots-1">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <pattern id="dot-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
              </pattern>
              <rect width="100" height="100" fill="url(#dot-pattern)"></rect>
            </svg>
          </div>

          <div class="dots dots-2">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
              <pattern id="dot-pattern-2" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
              </pattern>
              <rect width="100" height="100" fill="url(#dot-pattern-2)"></rect>
            </svg>
          </div>

          <div class="shape shape-3">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
              <path d="M43.3,-57.1C57.4,-46.5,71.1,-32.6,75.3,-16.2C79.5,0.2,74.2,19.1,65.1,35.3C56,51.5,43.1,65,27.4,71.7C11.7,78.4,-6.8,78.3,-23.9,72.4C-41,66.5,-56.7,54.8,-65.4,39.2C-74.1,23.6,-75.8,4,-71.7,-13.2C-67.6,-30.4,-57.7,-45.2,-44.3,-56.1C-30.9,-67,-15.5,-74,0.7,-74.9C16.8,-75.8,33.7,-70.7,43.3,-57.1Z" transform="translate(100 100)"></path>
            </svg>
          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->
    @php
        $clientShow = optional($homepage->where('name', 'show_clients')->first())->content ?? 'yes';
    @endphp

    @if($clientShow == 'yes')
    <!-- Clients Section -->
    <section id="clients" class="clients section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 40
                },
                "480": {
                  "slidesPerView": 3,
                  "spaceBetween": 60
                },
                "640": {
                  "slidesPerView": 4,
                  "spaceBetween": 80
                },
                "992": {
                  "slidesPerView": 6,
                  "spaceBetween": 120
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="{{ asset($homepage->where('name', 'client_image1')->first()->image_path ?? 'img/clients/client-1.png')}}" alt="Phone Mockup" class="img-fluid"></div>
            <div class="swiper-slide"><img src="{{ asset($homepage->where('name', 'client_image2')->first()->image_path ?? 'img/clients/client-2.png')}}" alt="Phone Mockup" class="img-fluid">
            </div>
            <div class="swiper-slide"><img src="{{ asset($homepage->where('name', 'client_image3')->first()->image_path ?? 'img/clients/client-3.png')}}" alt="Phone Mockup" class="img-fluid">
            </div>
            <div class="swiper-slide"><img src="{{ asset($homepage->where('name', 'client_image4')->first()->image_path ?? 'img/clients/client-4.png')}}" alt="Phone Mockup" class="img-fluid">
            </div>
            <div class="swiper-slide"><img src="{{ asset($homepage->where('name', 'client_image5')->first()->image_path ?? 'img/clients/client-5.png')}}" alt="Phone Mockup" class="img-fluid">
            </div>
            <div class="swiper-slide"><img src="{{ asset($homepage->where('name', 'client_image6')->first()->image_path ?? 'img/clients/client-6.png')}}" alt="Phone Mockup" class="img-fluid">
            </div>
            <div class="swiper-slide"><img src="{{ asset('img/clients/client-7.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('img/clients/client-8.png') }}" class="img-fluid" alt=""></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Clients Section -->
    @endif
      @php
        $testShow = optional($homepage->where('name', 'show_testimonials')->first())->content ?? 'yes';
    @endphp

    @if($testShow =='yes')
    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>{{ $homepage->where('name', 'test_head')->first()->content ?? 'Testimonials' }} </h2>
        <p>{{ $homepage->where('name', 'test_description')->first()->content ?? 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit' }}</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row g-5">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="testimonial-item">
              <img src="{{ asset($homepage->where('name', 'test_image1')->first()->image_path ?? 'img/testimonials/testimonials-1.jpg')}}" class="img-fluid testimonial-img">
              <h3> {{ $homepage->where('name', 'test_name1')->first()->content ?? 'Saul Goodman' }} </h3>
              <h4> {{ $homepage->where('name', 'test_role1')->first()->content ?? 'Ceo & Founder' }}</h4>
              <div class="stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p>
                <i class="bi bi-quote quote-icon-left"></i>
                <span> {{ $homepage->where('name', 'test_state1')->first()->content ?? 'Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper' }} </span>
                <i class="bi bi-quote quote-icon-right"></i>
              </p>
            </div>
          </div><!-- End testimonial item -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="testimonial-item">
              <img src="{{ asset($homepage->where('name', 'test_image2')->first()->image_path ?? 'img/testimonials/testimonials-2.jpg ')}}" class="img-fluid testimonial-img">
              <h3> {{ $homepage->where('name', 'test_name2')->first()->content ?? 'Sara Wilsson' }}</h3>
              <h4>{{ $homepage->where('name', 'test_role2')->first()->content ?? 'Designer' }}</h4>
              <div class="stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p>
                <i class="bi bi-quote quote-icon-left"></i>
                <span>{{ $homepage->where('name', 'test_state2')->first()->content ?? 'Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.' }} </span>
                <i class="bi bi-quote quote-icon-right"></i>
              </p>
            </div>
          </div><!-- End testimonial item -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="testimonial-item">
              <img src="{{ asset($homepage->where('name', 'test_image3')->first()->image_path ?? 'img/testimonials/testimonials-3.jpg ')}}" class="img-fluid testimonial-img">
              <h3> {{ $homepage->where('name', 'test_name3')->first()->content ?? 'Jena Karlis' }}</h3>
              <h4>{{ $homepage->where('name', 'test_role3')->first()->content ?? 'Store Owner' }}</h4>
              <div class="stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p>
                <i class="bi bi-quote quote-icon-left"></i>
                <span>{{ $homepage->where('name', 'test_state3')->first()->content ?? 'Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.' }} </span>
                <i class="bi bi-quote quote-icon-right"></i>
              </p>
            </div>
          </div><!-- End testimonial item -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
            <div class="testimonial-item">
              <img src="{{ asset($homepage->where('name', 'test_image4')->first()->image_path ?? 'img/testimonials/testimonials-4.jpg ')}}" class="img-fluid testimonial-img">
              <h3>{{ $homepage->where('name', 'test_name4')->first()->content ?? 'Matt Brandon' }} </h3>
              <h4>{{ $homepage->where('name', 'test_role4')->first()->content ?? 'Freelancer' }}</h4>
              <div class="stars">
                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
              </div>
              <p>
                <i class="bi bi-quote quote-icon-left"></i>
                <span>{{ $homepage->where('name', 'test_state4')->first()->content ?? 'Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.' }} </span>
                <i class="bi bi-quote quote-icon-right"></i>
              </p>
            </div>
          </div><!-- End testimonial item -->

        </div>

      </div>

    </section><!-- /Testimonials Section -->
    @endif
    <!-- Stats Section -->
    <section id="stats" class="stats section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Clients</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Projects</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours Of Support</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
              <p>Workers</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>{!! nl2br(e($homepage->where('name', 'service_title')->first()->content ?? 'Services')) !!}</h2>
        <p>{!! nl2br(e($homepage->where('name', 'service_description')->first()->content ?? 'Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit')) !!}</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-activity"></i>
              </div>
              <div>
                <h3>{!! nl2br(e($homepage->where('name', 'services_title1')->first()->content ?? 'Nesciunt Mete')) !!}</h3>
                <p>{!! nl2br(e($homepage->where('name', 'services_desc1')->first()->content ?? 'Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus dolores iure perferendis tempore et consequatur.')) !!} </p>
                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-diagram-3"></i>
              </div>
              <div>
                <h3>{!! nl2br(e($homepage->where('name', 'services_title2')->first()->content ?? 'Eosle Commodi')) !!}</h3>
                <p>{!! nl2br(e($homepage->where('name', 'services_desc2')->first()->content ?? 'Ut autem aut autem non a. Sint sint sit facilis nam iusto sint. Libero corrupti neque eum hic non ut nesciunt dolorem.')) !!} </p>
                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-easel"></i>
              </div>
              <div>
                <h3>{!! nl2br(e($homepage->where('name', 'services_title3')->first()->content ?? 'Ledo Markt')) !!}</h3>
                <p>{!! nl2br(e($homepage->where('name', 'services_desc3')->first()->content ?? 'Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id voluptas adipisci eos earum corrupti.')) !!} </p>
                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-card d-flex">
              <div class="icon flex-shrink-0">
                <i class="bi bi-clipboard-data"></i>
              </div>
              <div>
                <h3>{!! nl2br(e($homepage->where('name', 'services_title4')->first()->content ?? 'Asperiores Commodit')) !!}</h3>
                <p>{!! nl2br(e($homepage->where('name', 'services_desc4')->first()->content ?? 'Non et temporibus minus omnis sed dolor esse consequatur. Cupiditate sed error ea fuga sit provident adipisci neque.')) !!}</p>
                <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div><!-- End Service Card -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Faq Section -->
    <section class="faq-9 faq section light-background" id="faq">

      <div class="container">
        <div class="row">

          <div class="col-lg-5" data-aos="fade-up">
            <h2 class="faq-title">{!! nl2br(e($homepage->where('name', 'quest_title')->first()->content ?? 'Have a question? Check out the FAQ')) !!}</h2>
            <p class="faq-description">{!! nl2br(e($homepage->where('name', 'quest_description')->first()->content ?? 'Maecenas tempus tellus eget condimentum rhoncus sem quam semper libero sit amet adipiscing sem neque sed ipsum.')) !!}</p>
            <div class="faq-arrow d-none d-lg-block" data-aos="fade-up" data-aos-delay="200">
              <svg class="faq-arrow" width="200" height="211" viewBox="0 0 200 211" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M198.804 194.488C189.279 189.596 179.529 185.52 169.407 182.07L169.384 182.049C169.227 181.994 169.07 181.939 168.912 181.884C166.669 181.139 165.906 184.546 167.669 185.615C174.053 189.473 182.761 191.837 189.146 195.695C156.603 195.912 119.781 196.591 91.266 179.049C62.5221 161.368 48.1094 130.695 56.934 98.891C84.5539 98.7247 112.556 84.0176 129.508 62.667C136.396 53.9724 146.193 35.1448 129.773 30.2717C114.292 25.6624 93.7109 41.8875 83.1971 51.3147C70.1109 63.039 59.63 78.433 54.2039 95.0087C52.1221 94.9842 50.0776 94.8683 48.0703 94.6608C30.1803 92.8027 11.2197 83.6338 5.44902 65.1074C-1.88449 41.5699 14.4994 19.0183 27.9202 1.56641C28.6411 0.625793 27.2862 -0.561638 26.5419 0.358501C13.4588 16.4098 -0.221091 34.5242 0.896608 56.5659C1.8218 74.6941 14.221 87.9401 30.4121 94.2058C37.7076 97.0203 45.3454 98.5003 53.0334 98.8449C47.8679 117.532 49.2961 137.487 60.7729 155.283C87.7615 197.081 139.616 201.147 184.786 201.155L174.332 206.827C172.119 208.033 174.345 211.287 176.537 210.105C182.06 207.125 187.582 204.122 193.084 201.144C193.346 201.147 195.161 199.887 195.423 199.868C197.08 198.548 193.084 201.144 195.528 199.81C196.688 199.192 197.846 198.552 199.006 197.935C200.397 197.167 200.007 195.087 198.804 194.488ZM60.8213 88.0427C67.6894 72.648 78.8538 59.1566 92.1207 49.0388C98.8475 43.9065 106.334 39.2953 114.188 36.1439C117.295 34.8947 120.798 33.6609 124.168 33.635C134.365 33.5511 136.354 42.9911 132.638 51.031C120.47 77.4222 86.8639 93.9837 58.0983 94.9666C58.8971 92.6666 59.783 90.3603 60.8213 88.0427Z" fill="currentColor"></path>
              </svg>
            </div>
          </div>

          <div class="col-lg-7" data-aos="fade-up" data-aos-delay="300">
            <div class="faq-container">

              <div class="faq-item">
                <h3> {!! nl2br(e($homepage->where('name', 'quest_number1')->first()->content ?? 'Non consectetur a erat nam at lectus urna duis?')) !!}</h3>
                <div class="faq-content">
                  <p> {!! nl2br(e($homepage->where('name', 'answer_number1')->first()->content ?? 'Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.')) !!}</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>{!! nl2br(e($homepage->where('name', 'quest_number2')->first()->content ?? 'Feugiat scelerisque varius morbi enim nunc faucibus?')) !!} </h3>
                <div class="faq-content">
                  <p> {!! nl2br(e($homepage->where('name', 'answer_number2')->first()->content ?? 'Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.')) !!} </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>{!! nl2br(e($homepage->where('name', 'quest_number3')->first()->content ?? 'Dolor sit amet consectetur adipiscing elit pellentesque?')) !!} </h3>
                <div class="faq-content">
                  <p>{!! nl2br(e($homepage->where('name', 'answer_number3')->first()->content ?? 'Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis')) !!}</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>{!! nl2br(e($homepage->where('name', 'quest_number4')->first()->content ?? 'Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?')) !!} </h3>
                <div class="faq-content">
                  <p>{!! nl2br(e($homepage->where('name', 'answer_number4')->first()->content ?? 'Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.')) !!} </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>{!! nl2br(e($homepage->where('name', 'quest_number5')->first()->content ?? 'Tempus quam pellentesque nec nam aliquam sem et tortor?')) !!} </h3>
                <div class="faq-content">
                  <p>{!! nl2br(e($homepage->where('name', 'answer_number5')->first()->content ?? 'Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in')) !!} </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>{!! nl2br(e($homepage->where('name', 'quest_number6')->first()->content ?? 'Perspiciatis quod quo quos nulla quo illum ullam?')) !!} </h3>
                <div class="faq-content">
                  <p>{!! nl2br(e($homepage->where('name', 'answer_number6')->first()->content ?? 'Enim ea facilis quaerat voluptas quidem et dolorem. Quis et consequatur non sed in suscipit sequi. Distinctio ipsam dolore et.')) !!} </p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>
          </div>

        </div>
      </div>
    </section><!-- /Faq Section -->

    <!-- Call To Action 2 Section -->
    <section id="call-to-action-2" class="call-to-action-2 section dark-background">

      <div class="container">
        <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-10">
            <div class="text-center">
              <h3>{!! nl2br(e($homepage->where('name', 'quest_number6')->first()->content ?? 'Call To Action')) !!}</h3>
              <p>{!! nl2br(e($homepage->where('name', 'quest_number6')->first()->content ?? 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')) !!} </p>
              <a class="cta-btn" href="#">Call To Action</a>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Call To Action 2 Section -->

    <section class="section-container contact-us mt-5 mb-5" id="contact">
      <div class="container">
        <div class="row">

          <div class="col-md-6">
            <div class="info-box" data-aos="fade-up" data-aos-delay="200">
              <h3>{!! nl2br(e($homepage->where('name', 'contact_title')->first()->content ?? 'Contact Info')) !!} </h3>
              <p>{!! nl2br(e($homepage->where('name', 'contact_description')->first()->content ?? 'Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante ipsum primis.')) !!} </p>

              <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="content">
                  <h4>Our Location</h4>
                  <p>{!! nl2br(e($homepage->where('name', 'location_ad')->first()->content ?? 'A108 Adam Street
                 New York, NY 535022 ')) !!} </p>
                </div>
              </div>
              @php
                $showPhone = optional($homepage->where('name', 'show_phone')->first())->content ?? 'yes';
             @endphp

            @if($showPhone == 'yes')
              <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="content">
                  <h4>Phone Number</h4>
                  <p>{!! nl2br(e($homepage->where('name', 'phone_num1')->first()->content ?? '+1 5589 55488 55')) !!} </p>
                  <p>{!! nl2br(e($homepage->where('name', 'phone_num2')->first()->content ?? '+1 6678 254445 41')) !!}</p>
                </div>
              </div>
              @endif
              <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                <div class="icon-box">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="content">
                  <h4>Email Address</h4>
                  <p>{!! nl2br(e($homepage->where('name', 'contact_email')->first()->content ?? 'candidmarketing@gmail.com
                  candid@gmail.com')) !!}
                  </p>
                </div>
              </div>

              <img src="{{ asset('images/icon-contact-us.png') }}" alt="Your Image">

            </div>
          </div>

          <div class="col-md-6">
            <div class="contact-form" data-aos="fade-up" data-aos-delay="300">

              <div class="contact-form-header">
                <h3 class="mb-2">{!! nl2br(e($homepage->where('name', 'get_title')->first()->content ?? 'Get In Touch')) !!}</h3>
                <p>{!! nl2br(e($homepage->where('name', 'touch_desc')->first()->content ?? 'Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante ipsum primis.')) !!}</p>
              </div>

              <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">

                  <div class="col-md-12">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                  </div>

                  <div class="col-md-12 ">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                  </div>

                  <div class="col-12">
                    <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                  </div>

                  <div class="col-12">
                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                  </div>

                  <div class="col-12 text-center">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your message has been sent. Thank you!</div>

                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;" >Send Message</button>
                  </div>

                </div>
              </form>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main>

  <footer id="footer" class="footer">

      <div class="container footer-top">
        <div class="row">
          <div class="col-lg-5 col-md-9 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename"><img class="sitename" src="{{ asset($homepage->where('name', 'footer_title')->first()->image_path ?? 'imgs/docme_new_logo.png') }}" alt="Favicon Image" /></span>
            </a>
            <div class="footer-contact pt-3">
              <p><strong>{!! nl2br(e($homepage->where('name', 'footer_add')->first()->content ?? 'A108 Adam Street
              New York, NY 535022')) !!}</strong></p>
              <p class="mt-3"><strong>Phone:</strong> <span>{!! nl2br(e($homepage->where('name', 'footer_phone')->first()->content ?? '+1 5589 55488 55')) !!}</span></p>
              <p><strong>Email:</strong> <span>{!! nl2br(e($homepage->where('name', 'footer_email')->first()->content ?? 'candidmarketing@gmail.com')) !!}</span></p>
            </div>

            <div class="mt-4"><strong>Follow us on</strong></div><br>
            <div class="social-links d-flex">
              <a href=""><i class="bi bi-twitter-x"></i></a>
              <a href=""><i class="bi bi-facebook"></i></a>
              <a href=""><i class="bi bi-instagram"></i></a>
              <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Terms of service</a></li>
              <li><a href="#">Privacy policy</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="container post-footer">
        <div class="row">
          <div class="col-md-6">
          <div class="post-footer-copyright">
            <label>© 2024 Copyright. Software by Candid Marketing</label>
          </div>
          </div>
          <div class="col-md-6">
            <div class="post-footer-links">
              <a href="">Users term of Service</a>
              <a href="">Terms and Conditions</a>
              <a href="">Privacy Policy</a>
            </div>
          </div>
        </div>
      </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/glightbox/3.3.0/js/glightbox.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.1.5/dist/purecounter_vanilla.js"></script>
<script src="https://cdn.jsdelivr.net/gh/johndoe/php-email-form@latest/validate.js"></script>

  <!-- Main JS File -->
  <script src="{{ asset('js/landing.js') }}"></script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const sections = document.querySelectorAll("section[id]");
      const navLinks = document.querySelectorAll("#navmenu ul li a");

      // Manually map section IDs to nav hrefs
      const idToNavMap = {
        "features-cards": "features",  // section ID → nav href target
        "features-2": "features",
        "call-to-action": "features",
        "clients":"features",
        "testimonials":"features",
        "stats":"features",
        "about": "about",
        "services": "services",
        "pricing": "pricing",
        "call-to-action-2": "pricing",
        "faq": "pricing",
        "contact": "contact"
      };

      const observerOptions = {
        threshold: 0.5
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const sectionId = entry.target.getAttribute("id");
            const navTarget = idToNavMap[sectionId] || sectionId;

            // Remove all 'active' classes
            navLinks.forEach(link => link.classList.remove("active"));

            // Highlight matching nav link
            const currentLink = document.querySelector(`#navmenu a[href="#${navTarget}"]`);
            if (currentLink) currentLink.classList.add("active");

            // Update the URL hash (optional)
            history.replaceState(null, null, `#${navTarget}`);
          }
        });
      }, observerOptions);

      sections.forEach(section => observer.observe(section));

      const faqItems = document.querySelectorAll(".faq-item");

        faqItems.forEach((item) => {
        item.addEventListener("click", function () {
            // If it's already active, remove it
            if (item.classList.contains("faq-active")) {
            item.classList.remove("faq-active");
            } else {
            // Close all other FAQs
            faqItems.forEach((el) => el.classList.remove("faq-active"));
            // Open current one
            item.classList.add("faq-active");
            }
        });
        });

    });
  </script>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll("#navmenu a");

    navLinks.forEach(link => {
      link.addEventListener("click", function () {
        navLinks.forEach(l => l.classList.remove("active"));
        this.classList.add("active");
      });
    });
  });
  </script>

</body>

</html>
