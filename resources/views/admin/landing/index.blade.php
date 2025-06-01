@extends('admin.landingpage.layout')

@section('title', 'Logixsaas — Inventory Management Software | warehousing management Software (WMS)  | Fulfillment Software')
@section('description', 'Logixsaas is a powerful end-to-end logistics, inventory and warehouse management software platform that will help you fulfil your client orders. Logixsaas will help you manage, track orders and keep track of your inventory levels in your warehouses.')
@section('og:title','Logixsaas — Inventory Software | warehousing(WMS) | Order fulfillment')
@section('og:description',"Logixsaas is a powerful end-to-end logistics, inventory and warehouse management software platform that will help you fulfil your client orders. Logixsaas will help you manage, track orders and keep track of your inventory levels in your warehouses.")
@section('twitter:title','Logixsaas — Inventory Software | warehousing(WMS) | Order fulfillment')
@section('twitter:description',"Logixsaas is a powerful end-to-end logistics, inventory and warehouse management software platform that will help you fulfil your client orders. Logixsaas will help you manage, track orders and keep track of your inventory levels in your warehouses.")
@section('url', '')




@section('content')
<a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>

<div id="main_content" class="home">

    @include('admin.landing.inc.homeheader')  

    <section class="banner banner-two">
        <div class="vector-bg"><img src="{{ asset('home/top shape.png') }}"
                alt="circle"></div>
        <div class="container">
            <div class="banner-content-wrap">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="banner-content">
                            <h1 class="banner-title wow pixFadeUp" data-wow-delay="0.3s">Logistics
                                <span>Management<br>Software</span> for<br>all businesses</h1>
                            <p class="description wow pixFadeUp" data-wow-delay="0.5s" style="color: #797687;">Get Top-Notch Logistics Management Software for your Business & boost your Profits. Inventory management, warehouse management and order fulfillment made easier.</p>
                            <a href="/pricing" class="pxs-btn banner-btn color-two wow pixFadeUp" data-wow-delay="0.6s">Get Started</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="animate-promo-mockup"><img
                                src="{{ asset('home/01.png')}}" class="wow pixFadeDown"
                                alt="mpckup" data-wow-duration="1.5s"> <img
                                src="{{ asset('home/02.png')}}"
                                class="wow pixFadeRight" data-wow-delay="0.3s" data-wow-duration="1s" alt="mpckup">
                            <img src="{{ asset('home/03.png')}}" class="wow pixFadeUp"
                                alt="mpckup" data-wow-duration="1.7s"> <img
                                src="{{ asset('home/04.png')}}"
                                class="wow pixFadeRight" alt="mpckup"> <img
                                src="{{ asset('home/05.png')}}" class="wow pixFadeDown"
                                alt="mpckup" data-wow-duration="2s"> <img
                                src="{{ asset('home/06.png')}}" alt="mpckup"
                                data-wow-delay="0.3s"> <img
                                src="{{ asset('home/07.png')}}" class="wow pixFadeLeft"
                                alt="mpckup" data-wow-delay="0.6s" data-wow-duration="1.5s">
                            <img src="{{ asset('home/cloud_01.png') }}" alt="mpckup">
                            <img src="{{ asset('home/cloud_02.png') }}" alt="mpckup">
                            <img src="{{ asset('home/cloud_03.png') }}" alt="mpckup">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="brand-logo">
        <div class="section-small text-center wow pixFadeUp">
            <h2 class="title" style="color: #fff">We integrate seamlessly with the best online apps</h2>
        </div>
        <div class="container" style="margin-top: 0px;">
            <div class="swiper-container logo-carousel wow pixFadeUp" data-wow-delay="0.3s" id="logo-carousel"
                data-perpage="6"
                data-breakpoints='{"1024": {"slidesPerView": 4}, "768": {"slidesPerView": 4}, "640": {"slidesPerView": 3}}'>
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="margin-left:20px">
                        <div class="brand-logo"><img
                                src="{{ asset('home/shopify.svg')}}"
                                alt="brand"></div>
                    </div>
                    <div class="swiper-slide" style="margin-left:20px">
                        <div class="brand-logo"><img
                                src="{{ asset('home/woocommerce.svg') }}"
                                alt="brand"></div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-logo"><img
                                src="{{ asset('home/google-sheets-blog-banner.png') }}"
                                alt="brand"></div>
                    </div>
                    <div class="swiper-slide" style="margin-left:20px">
                        <div class="brand-logo"><img
                                src="{{ asset('home/twilio-1-285957.png')}}" alt="brand">
                        </div>
                    </div>
                    <div class="swiper-slide" style="margin-left:20px">
                        <div class="brand-logo"><img
                                src="{{ asset('home/africastalking.png') }}"
                                alt="brand"></div>
                    </div>
                    {{-- <div class="swiper-slide">
                            <div class="brand-logo"><img src="https://saaspik.pixelsigns.art/saaspik/media/brand/6.png"
                                    alt="brand"></div>
                        </div> --}}
                </div>
            </div>
        </div>
    </section>
    <section class="featured-two-same">
        <div class="container">
            <div class="section-title color-two text-center">
                <h3 class="sub-title wow pixFadeUp">Our service</h3>
                <h1 class="title wow pixFadeUp" data-wow-delay="0.3s">Why choose Our application</h1>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-two wow pixFadeRight" data-wow-delay="0.5s">
                        <div class="saaspik-icon-box-icon"><img
                                src="{{ asset('home/logixsaas-cloud-platform.png') }}" alt="Cloud Platform"></div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Cloud Platform</a></h3>
                            <p>Cloud based platform allows you to manage your business from any worklace.
                            </p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-two wow pixFadeRight" data-wow-delay="0.6s">
                        <div class="saaspik-icon-box-icon"><img
                                src="{{ asset('home/customization.png') }}" alt="logixsaas-Customization"></div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Customization</a></h3>
                            <p>Customize the platform based on your business needs, flow and operations.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-two wow pixFadeRight" data-wow-delay="0.7s">
                        <div class="saaspik-icon-box-icon"><img
                                src="{{ asset('home/reports.png') }}" alt="logixsaas-reports-automation"></div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Reports automation </a></h3>
                            <p>Packed with charts to help you with everything from year-end reporting, to better supply chain decision-making.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-two wow pixFadeRight" data-wow-delay="0.8s">
                        <div class="saaspik-icon-box-icon"><img
                                src="{{ asset('home/portals.png') }}" alt="logixsaas-customer-portals"></div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Customer portals</a></h3>
                            <p>The customer portal is a secure, cloud-hosted page that lets your customers manage their orders and reports.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-two wow pixFadeRight" data-wow-delay="0.9s">
                        <div class="saaspik-icon-box-icon"><img
                                src="{{ asset('home/integrations.png') }}" alt="logixsaas-integrations"></div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Integrations</a></h3>
                            <p>Integrate with the biggest marketplaces to manage your orders across the globe conveniently, quickly, and without switching tabs.<br>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-two wow pixFadeRight" data-wow-delay="1s">
                        <div class="saaspik-icon-box-icon"><img
                                src="{{ asset('home/effortless.png') }}" alt="logixsaas-effortless"></div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Effortless</a></h3>
                            <p>You and your team can stop worrying about the tech side of things, and focus more on running and growing your business.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-circle wow pixFadeRight"><img
                src="{{ asset('home/circle.png') }}" data-parallax='{"y" : -230}'
                alt="circle"></div>
    </section>
    <section class="revolutionize-two">
        <div class="bg-angle"></div>
        <div class="container">
            <div class="section-title dark-title text-center">
                <h3 class="sub-title wow pixFadeUp">Logixsaas</h3>
                <h4 class="title wow pixFadeUp" data-wow-delay="0.3s">Revolutionize your online<br>business today
                </h4>
            </div>
            <div id="pix-tabs" class="wow pixFadeUp" data-wow-delay="0.5s">
                <ul id="pix-tabs-nav" class="pix-tab-two">
                    <li><a href="#tab1">Quick Access</a></li>
                    <li><a href="#tab2">Easily Manage</a></li>
                    <li><a href="#tab3">Order Processing</a></li>
                    <li><a href="#tab4">7/24h Support</a></li>
                </ul>
                <div id="pix-tabs-content">
                    <div id="tab1" class="content color-two"><img
                            src="{{ asset('home/dashboard.png') }}" alt="logixsaas-dashboard">
                        <div class="shape-shadow"></div>
                    </div>
                    <div id="tab2" class="content color-two"><img
                            src="{{ asset('home/dashboard2.png') }}" alt="logixsaas-dashboard">
                        <div class="shape-shadow"></div>
                    </div>
                    <div id="tab3" class="content color-two"><img
                            src="{{ asset('home/orders.png') }}" alt="logixsaas-dashboard">
                        <div class="shape-shadow"></div>
                    </div>
                    <div id="tab4" class="content color-two"><img
                            src="{{ asset('home/4.jpg') }}" alt="logixsaas-dashboard">
                        <div class="shape-shadow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="editor-design-two">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="editure-feature-image-two">
                        <div class="animaated-elements"><img
                                src="{{ asset('home/01.png') }}" alt="main-bg"
                                class="main-bg wow pixFade"> <img
                                src="{{ asset('home/02.png') }}" alt="main-bg"
                                class="elm-clock wow pixFadeLeft" data-wow-delay="0.5s"> <img
                                src="{{ asset('home/03.png') }}" alt="elm-man"
                                class="elm-man wow pixFadeRight" data-wow-delay="0.7s"> <img
                                src="{{ asset('home/04.png') }}" alt="elm-table"
                                class="elm-table wow pixFadeUp" data-wow-delay="0.1s"> <img
                                src="{{ asset('home/05.png') }}" alt="main-bg"
                                class="elm-sm-vase wow pixFade" data-wow-delay="0.9s"> <img
                                src="{{ asset('home/06.png') }}" alt="elm-vase"
                                class="elm-vase wow pixFadeLeft" data-wow-delay="0.9s">
                            <div class="elm-mass wow pixFadeDown" data-wow-delay="1s"><img
                                    src="{{ asset('home/07.png') }}" alt="massage"
                                    class="mass-img"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="editor-content color-two">
                        <div class="section-title style-two color-two">
                            <h1 class="title wow pixFadeUp" data-wow-delay="0.3s">Who we are</h1>
                            <p class="wow pixFadeUp" data-wow-delay="0.5s">Having attractive showcase has
                                never<br>been easier</p>
                        </div>
                        <div class="description wow pixFadeUp" data-wow-delay="0.7s">
                            <p>Logixsaas is a cloud-based order fulfillment software, WMS, and ecommerce order management system that helps businesses manage and process orders from their customers. It includes features such as warehouse management, inventory management, process automation, SMS integration, ecommerce platform integrations, and API integrations!</p>
                            <a href="/about" class="pix-btn color-two wow pixFadeUp" data-wow-delay="0.9s">Discover More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="genera-informes-two">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 pix-order-two">
                    <div class="genera-informes-content">
                        <div class="section-title style-two">
                            <h1 class="title wow pixFadeUp">Inventory&Warehousing<br>Solutions</h1>
                            <p class="wow pixFadeUp" data-wow-delay="0.3s">Say goodbye to paper works and excel sheets, Start using our logistics tracking software platforms and discover the easy way of managing your business. We have software solutions for all kind of logistics and e-commerce businesses.</p>
                        </div>
                        <ul class="list-items color-two wow pixFadeUp" data-wow-delay="0.4s">
                            <li>Quick Access</li>
                            <li>Easily Manage</li>
                            <li>7/24h Support</li>
                        </ul>
                        <a href="/accounts?plan=1" class="pix-btn btn-outline-two wow pixFadeUp" data-wow-delay="0.5s">Start your freee trial</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="animaated-elements-two"><img
                            src="{{ asset('home/1.png') }}"
                            class="elm-one wow pixFade" alt="informes"> <img
                            src="{{ asset('home/2.png') }}"
                            class="elm-two wow pixFadeDown" alt="informes"> <img
                            src="{{ asset('home/3.png') }}"
                            class="elm-three wow pixFadeDown" alt="informes"> <img
                            src="{{ asset('home/4.png') }}"
                            class="elm-four wow pixFadeRight" alt="informes"></div>
                </div>
            </div>
        </div>
        <div class="scroll-circle wow pixFadeRight"><img
                src="{{ asset('home/circle2.png') }}" data-parallax='{"y" : 180}'
                alt="circle"></div>
    </section>


    @include('admin.landingpage.pricing')


    {{-- <section class="testimonials-two">
        <div class="animate-shape wow pixFadeDown"><img
                src="{{ asset('home/ellipse2.png') }}" data-parallax='{"y" : 230}'
                alt="shape"></div>
        <div class="container">
            <div class="section-title color-two text-center">
                <h3 class="sub-title wow pixFadeUp">Testiimonial</h3>
                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s">What our client say about us</h2>
            </div>
            <div id="testimonial-wrapper" class="wow pixFadeUp" data-wow-delay="0.4s">
                <div class="swiper-container" id="testimonial-two" data-speed="700" data-autoplay="5000"
                    data-perpage="2" data-space="50" data-breakpoints='{"991": {"slidesPerView": 1}}'>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="testimonial-two">
                                <div class="testi-content-inner">
                                    <div class="testimonial-bio">
                                        <div class="avatar"><img
                                                src="{{ asset('home/1.jpg') }}"
                                                alt="testimonial"></div>
                                        <div class="bio-info">
                                            <h3 class="name">Desmond Eagle</h3><span class="job">Web designer</span>
                                        </div>
                                    </div>
                                    <div class="testimonial-content">
                                        <p>Tosser nancy boy super tickety-boo lemon squeezy easy peasy quaint,
                                            hunky-dory baking cakes pear shaped butty do one, it's all gone to pot
                                            chinwag.!</p>
                                    </div>
                                    <ul class="rating">
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                    </ul>
                                    <div class="quote"><img
                                            src="{{ asset('home/quote.png') }}"
                                            alt="quote"></div>
                                </div>
                                <div class="shape-shadow"></div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-two">
                                <div class="testi-content-inner">
                                    <div class="testimonial-bio">
                                        <div class="avatar"><img
                                                src="{{ asset('home/1.jpg') }}"
                                                alt="testimonial"></div>
                                        <div class="bio-info">
                                            <h3 class="name">Desmond Eagle</h3><span class="job">Web designer</span>
                                        </div>
                                    </div>
                                    <div class="testimonial-content">
                                        <p>Tosser nancy boy super tickety-boo lemon squeezy easy peasy quaint,
                                            hunky-dory baking cakes pear shaped butty do one, it's all gone to pot
                                            chinwag.!</p>
                                    </div>
                                    <ul class="rating">
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                    </ul>
                                    <div class="quote"><img
                                            src="{{ asset('home/quote.png') }}"
                                            alt="quote"></div>
                                </div>
                                <div class="shape-shadow"></div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-two">
                                <div class="testi-content-inner">
                                    <div class="testimonial-bio">
                                        <div class="avatar"><img
                                                src="{{ asset('home/1.jpg') }}"
                                                alt="testimonial"></div>
                                        <div class="bio-info">
                                            <h3 class="name">Desmond Eagle</h3><span class="job">Web designer</span>
                                        </div>
                                    </div>
                                    <div class="testimonial-content">
                                        <p>Tosser nancy boy super tickety-boo lemon squeezy easy peasy quaint,
                                            hunky-dory baking cakes pear shaped butty do one, it's all gone to pot
                                            chinwag.!</p>
                                    </div>
                                    <ul class="rating">
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                        <li><i class="fa fa-star"></i></li>
                                    </ul>
                                    <div class="quote"><img
                                            src="{{ asset('home/quote.png') }}"
                                            alt="quote"></div>
                                </div>
                                <div class="shape-shadow"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shape-shadow"></div>
                <div class="slider-nav wow pixFadeUp" data-wow-delay="0.3s">
                    <div id="slide-prev" class="swiper-button-prev"><i class="ei ei-arrow_left"></i></div>
                    <div id="slide-next" class="swiper-button-next"><i class="ei ei-arrow_right"></i></div>
                </div>
            </div>
        </div>
    </section>
    <section class="countup">
        <div class="bg-map" data-bg-image="{{ asset('home/map.png') }}"></div>
        <div class="container">
            <div class="section-title color-two text-center">
                <h3 class="sub-title wow pixFadeUp">Fun Facts</h3>
                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s">We Always try to Understand<br>Users
                    expectation</h2>
            </div>
            <div class="countup-wrapper">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact wow pixFadeUp" data-wow-delay="0.3s">
                            <div class="counter">
                                <h4 class="count" data-counter="14">14</h4><span>K+</span>
                            </div>
                            <p>Total Download</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact color-two wow pixFadeUp" data-wow-delay="0.5s">
                            <div class="counter">
                                <h4 class="count" data-counter="13">13</h4><span>M+</span>
                            </div>
                            <p>Total Download</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact color-three wow pixFadeUp" data-wow-delay="0.7s">
                            <div class="counter">
                                <h4 class="count" data-counter="244">244</h4><span>+</span>
                            </div>
                            <p>Total Download</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact color-four wow pixFadeUp" data-wow-delay="0.9s">
                            <div class="counter">
                                <h4 class="count" data-counter="53">53</h4><span>M+</span>
                            </div>
                            <p>Total Download</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-container text-center wow pixFadeUp" data-wow-delay="1s"><a href="#"
                    class="pix-btn btn-outline-two">See All Review</a></div>
        </div>
        <div class="scroll-circle wow pixFadeRight"><img
                src="{{ asset('home/circle3.png') }}" data-parallax='{"y" : -230}'
                alt="circle"></div>
    </section>
    <section id="blog-grid">
        <div class="container">
            <div class="section-title color-two text-center">
                <h3 class="sub-title wow pixFadeUp">News & Events</h3>
                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s">Check out our blog posts</h2>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <article class="blog-post color-two wow pixFadeLeft" data-wow-delay="0.4s">
                        <div class="feature-image"><a href="#"><img
                                    src="{{ asset('home/1.jpg')}}" alt="blog-thumb"></a>
                        </div>
                        <div class="blog-content">
                            <ul class="post-meta">
                                <li><a href="#">Jun 24, 2019</a></li>
                            </ul>
                            <h3 class="entry-title"><a href="#">An Uncomplicated Guide to Improve Rate By 3X.</a>
                            </h3><a href="#" class="post-author"><img
                                    src="{{ asset('home/auth.jpg')}}"
                                    alt="author">Jackson Pot</a>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="blog-post color-two wow pixFadeLeft" data-wow-delay="0.6s">
                        <div class="feature-image"><a href="#"><img
                                    src="{{ asset('home/2.jpg')}}" alt="blog-thumb"></a>
                        </div>
                        <div class="blog-content">
                            <ul class="post-meta">
                                <li><a href="#">Jun 30, 2019</a></li>
                            </ul>
                            <h3 class="entry-title"><a href="#">The most powerfull chating saas that make you.</a>
                            </h3><a href="#" class="post-author"><img
                                    src="{{ asset('home/auth2.jpg')}}" alt="author">Brian
                                Cumin</a>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6">
                    <article class="blog-post color-two wow pixFadeLeft" data-wow-delay="0.7s">
                        <div class="feature-image"><a href="#"><img
                                    src="{{ asset('home/3.jpg')}}" alt="blog-thumb"></a>
                        </div>
                        <div class="blog-content">
                            <ul class="post-meta">
                                <li><a href="#">July 24, 2019</a></li>
                            </ul>
                            <h3 class="entry-title"><a href="#">Why DIY tools were rejected by the market.</a></h3>
                            <a href="#" class="post-author"><img
                                    src="{{ asset('home/auth3.jpg')}}" alt="author">Hans
                                Down</a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section> --}}

    
    <section class="newsletter" data-bg-image="{{ asset('home/map-bg.jpg') }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="newsletter-content">
                        <h5 class="title wow pixFadeUp" style="color: rgb(132 129 145)">Subscribe now to get company news.</h5>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form action="php/subscribe.php" method="post" class="newsletter-form wow pixFadeUp"
                        data-pixsaas="newsletter-subscribe">
                        <div class="newsletter-inner"><input type="email" name="email" class="form-control"
                                id="newsletter-form-email" placeholder="Enter your Email" required> <button
                                type="submit" name="submit" id="newsletter-submit" class="newsletter-submit"><span
                                    class="btn-text">Subscribe</span> <i class="fas fa-spinner fa-spin"></i></button>
                        </div><input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                        <div class="clearfix"></div>
                        <div class="form-result alert">
                            <div class="content"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="scroll-circle wow fadeInUp"><img
                src="{{ asset('home/circle10.png') }}" alt="circle6"
                data-parallax='{"y" : 50}'></div>
    </section>

    @include('admin.landing.inc.footer')  

</div>
@endsection
