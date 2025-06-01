@extends('admin.landingpage.layout')

@section('title', 'Logixsaas — Manage Inventory | Warehousing management Software | Order fulfillment Software')
@section('description', 'Logixsaas is a powerful end-to-end logistics, inventory and warehouse management software
    platform that will help you fulfil your client orders. Logixsaas will help you manage, track orders and keep track of
    your inventory levels in your warehouses.')
@section('og:title', 'Logixsaas — Inventory Software | warehousing(WMS) | Order fulfillment Software')
@section('og:description', 'Logixsaas is a powerful end-to-end logistics, inventory and warehouse management software
    platform that will help you fulfil your client orders. Logixsaas will help you manage, track orders and keep track of
    your inventory levels in your warehouses.')
@section('twitter:title', 'Logixsaas — Inventory Software | warehousing(WMS) | Order fulfillment')
@section('twitter:description', 'Logixsaas is a powerful end-to-end logistics, inventory and warehouse management
    software platform that will help you fulfil your client orders. Logixsaas will help you manage, track orders and keep
    track of your inventory levels in your warehouses.')
@section('url', '')


@section('content')
    <a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>

    <div id="main_content">

        @include('admin.landing.inc.homeheader')
        <section class="banner banner-four">
            <div class="container">
                <div class="banner-content-wrap-four">
                    <div class="row">
                        <div class="col-lg-8 col-md-7">
                            <div class="banner-content">
                                <h1 class="banner-title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                                    Logistics Management <span>Software for all </span><br>
                                    businesses

                                </h1>

                                <p class="description wow pixFadeUp" data-wow-delay="0.5s"
                                    style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeUp;">
                                    Get Top-Notch Logistics Management Software for your Business & boost your Profits.
                                    Inventory management, warehouse management and order fulfillment made easier.
                                </p>

                                <form action="php/subscribe.php" method="post" class="newsletter-form-banner wow pixFadeUp"
                                    data-wow-delay="0.7s" data-pixsaas="newsletter-subscribe"
                                    style="visibility: visible; animation-delay: 0.7s; animation-name: pixFadeUp;">
                                    <div class="newsletter-inner">
                                        <input type="email" name="email" class="form-control" id="newsletter-form-email"
                                            placeholder="Enter your Email" required="">
                                        <button type="submit" name="submit" id="newsletter-submit"
                                            class="newsletter-submit">
                                            <span>Get Started</span>
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </button>
                                    </div>
                                    <!-- /.newsletter-inner -->

                                    <div class="clearfix"></div>
                                    <div class="form-result alert" style="display: none;">
                                        <div class="content"></div>
                                    </div>
                                    <!-- /.form-result-->
                                </form>

                                <a href="https://www.youtube.com/watch?v=V6KEtjRqWJo"
                                    class="play-btn play-btn-two  popup-video wow pixFadeUp" data-wow-delay="0.8s"
                                    style="visibility: visible; animation-delay: 0.8s; animation-name: pixFadeUp;">
                                    <i class="fa-solid fa-circle-play"></i>
                                    Watch Video
                                </a>

                            </div><!-- /.banner-content -->
                        </div>
                        <!-- /.col-lg-8 -->

                        <div class="col-lg-4 col-md-5">
                            <div class="promo-mockup wow pixFadeUp text-center animated"
                                style="visibility: visible; animation-name: jump;">
                                <img src="{{ asset('home/logixsaas-mobile-dashbouard.png') }}"
                                    class="wow pixZoomIn animated" alt="mpckup"
                                    style="visibility: visible; animation-name: pixZoomIn;">
                            </div><!-- /.promo-mockup -->
                        </div>
                        <!-- /.col-lg-4 col-md-5 -->
                    </div>
                    <!-- /.row -->



                </div><!-- /.banner-content-wrap -->
            </div><!-- /.container -->

            <div class="bg-shape-inner">
                <div class="bottom-shape"><img src="{{ asset('home/logixsaas_banner_5.png') }}" alt="circle"></div>
            </div>
            <!-- /.bg-shape-inner -->
        </section>


        <section class="featured-five">
            <div style="margin: 0 100px">
                <div class="section-title style-three text-center">
                    <h2 class="title wow pixFadeUp" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: pixFadeUp;">
                        Why choose Our software<br>
                        {{-- Love SaaS<span>pik</span>. --}}
                    </h2>
                </div><!-- /.section-title -->

                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-five wow pixFadeRight" data-wow-delay="0.4s"
                            style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeRight;">
                            <div class="saaspik-icon-box-icon">
                                {{-- <i class="icon-rocket"></i> --}}
                                <i class="fa-solid fa-cloud"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Cloud Platform</a></h3>
                                <p>
                                    Cloud based platform allows you to manage your business from any worklace.
                                </p>

                                <a href="#" class="more-btn">Find out More <i
                                        class="fa-solid fa-location-arrow"></i></a>
                            </div>
                        </div><!-- /.pixsass-box style-five -->
                    </div><!-- /.col-lg-4 col-md-6 -->

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-five wow pixFadeRight" data-wow-delay="0.5s"
                            style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeRight;">
                            <div class="saaspik-icon-box-icon">
                                <i class="fa-brands fa-intercom"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Customization</a></h3>

                                <p>
                                    Customize Logixsaas based on your business needs, flow and operations.
                                </p>

                                <a href="#" class="more-btn">Find out More <i
                                        class="fa-solid fa-location-arrow"></i></a>
                            </div>
                        </div><!-- /.pixsass-box style-five -->
                    </div><!-- /.col-lg-4 col-md-6 -->

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-five wow pixFadeRight" data-wow-delay="0.6s"
                            style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                            <div class="saaspik-icon-box-icon">
                                <i class="fa-solid fa-chart-area"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title">
                                    <a href="#">
                                        Reports automation

                                    </a>
                                </h3>
                                <p>
                                    Packed with charts to help you with everything from year-end reporting, to better supply
                                    chain decision-making.
                                </p>

                                <a href="#" class="more-btn">Find out More <i
                                        class="fa-solid fa-location-arrow"></i></a>
                            </div>
                        </div><!-- /.pixsass-box style-five -->
                    </div><!-- /.col-lg-4 col-md-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->

            <div style="margin: 0 100px">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-five wow pixFadeRight" data-wow-delay="0.4s"
                            style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeRight;">
                            <div class="saaspik-icon-box-icon">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Customer portals</a></h3>
                                <p>The customer portal is a secure, cloud-hosted page that lets your customers manage their
                                    orders and reports. </p>

                                <a href="#" class="more-btn">Find out More <i
                                        class="fa-solid fa-location-arrow"></i></a>
                            </div>
                        </div><!-- /.pixsass-box style-five -->
                    </div><!-- /.col-lg-4 col-md-6 -->

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-five wow pixFadeRight" data-wow-delay="0.5s"
                            style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeRight;">
                            <div class="saaspik-icon-box-icon">
                                <i class="fa-solid fa-equals"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Integrations</a></h3>

                                <p>Integrate with the biggest marketplaces to manage your orders across the globe
                                    conveniently, quickly, and without switching tabs.</p>

                                <a href="#" class="more-btn">Find out More <i
                                        class="fa-solid fa-location-arrow"></i></a>
                            </div>
                        </div><!-- /.pixsass-box style-five -->
                    </div><!-- /.col-lg-4 col-md-6 -->

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-five wow pixFadeRight" data-wow-delay="0.6s"
                            style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                            <div class="saaspik-icon-box-icon">
                                <i class="fa-solid fa-person-digging"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title">
                                    <a href="#">Effortless</a>
                                </h3>
                                <p>You and your team can stop worrying about the tech side of things, and focus more on
                                    running and growing your business.</p>

                                <a href="#" class="more-btn">Find out More <i
                                        class="fa-solid fa-location-arrow"></i></a>
                            </div>
                        </div><!-- /.pixsass-box style-five -->
                    </div><!-- /.col-lg-4 col-md-6 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section>

        <section class="editor-design-two">
            <div style="margin: 0 100px">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="editure-feature-image-two">
                            <div class="animaated-elements"><img src="/home/01.png" alt="main-bg"
                                    class="main-bg wow pixFade" style="visibility: visible; animation-name: h;"> <img
                                    src="/home/02.png" alt="main-bg" class="elm-clock wow pixFadeLeft"
                                    data-wow-delay="0.5s"
                                    style="visibility: visible; animation-delay: 0.5s; animation-name: k;"> <img
                                    src="/home/03.png" alt="elm-man" class="elm-man wow pixFadeRight"
                                    data-wow-delay="0.7s"
                                    style="visibility: visible; animation-delay: 0.7s; animation-name: l;"> <img
                                    src="/home/04.png" alt="elm-table" class="elm-table wow pixFadeUp"
                                    data-wow-delay="0.1s"
                                    style="visibility: visible; animation-delay: 0.1s; animation-name: i;"> <img
                                    src="/home/05.png" alt="main-bg" class="elm-sm-vase wow pixFade"
                                    data-wow-delay="0.9s"
                                    style="visibility: visible; animation-delay: 0.9s; animation-name: h;"> <img
                                    src="/home/06.png" alt="elm-vase" class="elm-vase wow pixFadeLeft"
                                    data-wow-delay="0.9s"
                                    style="visibility: visible; animation-delay: 0.9s; animation-name: k;">
                                <div class="elm-mass wow pixFadeDown" data-wow-delay="1s"
                                    style="visibility: visible; animation-delay: 1s; animation-name: j;"><img
                                        src="/home/07.png" alt="massage" class="mass-img"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="editor-content color-two">
                            <div class="section-title style-two color-two">
                                <h4 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: i;">Who we are</h4>
                                <p class="wow pixFadeUp" data-wow-delay="0.5s"
                                    style="visibility: visible; animation-delay: 0.5s; animation-name: i;">Having
                                    attractive showcase has
                                    never<br>been easier</p>
                            </div>
                            <div class="description wow pixFadeUp" data-wow-delay="0.7s"
                                style="visibility: visible; animation-delay: 0.7s; animation-name: i;">
                                <p>Logixsaas is a cloud-based order fulfillment software, WMS, and ecommerce order
                                    management system that helps businesses manage and process orders from their customers.
                                    It includes features such as warehouse management, inventory management, process
                                    automation, SMS integration, ecommerce platform integrations, and API integrations!</p>
                                <a href="/about" class="pix-btn color-two wow pixFadeUp" data-wow-delay="0.9s"
                                    style="visibility: visible; animation-delay: 0.9s; animation-name: i;">Discover
                                    More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="editor-design-two">
            <div style="margin: 0 100px">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="editor-content color-two">
                            <div class="section-title style-two color-two">
                                <h4 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: i;">Why Logixsaas
                                </h4>
                            </div>
                            <div class="description wow pixFadeUp" data-wow-delay="0.7s"
                                style="visibility: visible; animation-delay: 0.7s; animation-name: i;">
                                <p>Logixsaas is a comprehensive logistics management system that offers a range of features
                                    to help businesses streamline their <a href="/order-fulfillment-software">order
                                        fulfillment</a> , courier management, and <a
                                        href="/order-fulfillment-software">warehouse operations</a> . It is a cloud-based
                                    software as a service (SaaS) platform that is designed to be user-friendly and easy to
                                    use, making it an ideal choice for businesses of all sizes.
                                    <br>
                                    One of the key benefits of using Logixsaas is its ability to automate and optimize
                                    various logistics processes, including <a
                                        href="/inventory-management-software">inventory management</a>, order processing,
                                    and shipping. This helps businesses save time and reduce the risk of errors, while also
                                    improving customer satisfaction.

                                </p>
                                <a href="/accounts?plan=1" class="pix-btn color-two wow pixFadeUp" data-wow-delay="0.9s"
                                    style="visibility: visible; animation-delay: 0.9s; animation-name: i;">Get started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="editure-feature-image-two">
                            <div class="animaated-elements"><img src="/home/01.png" alt="main-bg"
                                    class="main-bg wow pixFade" style="visibility: visible; animation-name: h;"> <img src="/home/delivery.png" alt="main-bg"
                                    class="main-bg wow pixFade" style="visibility: visible; animation-name: h;max-width: 46vw;" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="editor-design-two">
            <div style="margin: 0 100px">
                <div class="row">

                    <div class="col-lg-6">
                        <div class="editure-feature-image-two">
                            <div class="animaated-elements"><img src="/home/logistics.png" alt="main-bg"
                                    class="main-bg wow pixFade" style="visibility: visible; animation-name: h;max-width: 46vw;" >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="editor-content color-two">
                            <div class="section-title style-two color-two">
                                <h4 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: i;">Why Logixsaas
                                </h4>
                            </div>
                            <div class="description wow pixFadeUp" data-wow-delay="0.7s"
                                style="visibility: visible; animation-delay: 0.7s; animation-name: i;">
                                <p>Another advantage of Logixsaas is its scalability. As your business grows and evolves,
                                    the system can easily be adjusted to meet your changing needs. This means that you can
                                    start small and then add more features and capabilities as your business grows, without
                                    having to switch to a different platform.
                                    <br>
                                    In addition to its automation and scalability, Logixsaas also offers a range of
                                    reporting and analytics tools that allow businesses to track and monitor their logistics
                                    operations in real-time. This can help businesses identify any bottlenecks or
                                    inefficiencies in their processes, and make any necessary changes to improve efficiency
                                    and productivity.
                                    <br>
                                    Overall, Logixsaas is a powerful and flexible logistics management system that is well-suited for businesses of all sizes. It offers a range of features and capabilities to help businesses streamline their order fulfillment, courier management, and warehouse operations, while also providing valuable insights and analytics to help businesses make informed decisions and improve their logistics operations.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="revolutionize-two">
            <div class="bg-angle"></div>
            <div class="container">
                <div class="section-title dark-title text-center">
                    <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: i;">Logixsaas</h3>
                    <h4 class="title wow pixFadeUp" data-wow-delay="0.3s"
                        style="visibility: visible; animation-delay: 0.3s; animation-name: i;">Revolutionize your
                        fulfillment<br>business today
                    </h4>
                </div>
                <div id="pix-tabs" class="wow pixFadeUp" data-wow-delay="0.5s"
                    style="visibility: visible; animation-delay: 0.5s; animation-name: i;">
                    <ul id="pix-tabs-nav" class="pix-tab-two">
                        <li><a href="#tab1">Quick Access</a></li>
                        <li class="active"><a href="#tab2">Easily Manage</a></li>
                        <li><a href="#tab3">Order Processing</a></li>
                        <li><a href="#tab4">7/24h Support</a></li>
                    </ul>
                    <div id="pix-tabs-content">
                        <div id="tab1" class="content color-two" style="display: none;"><img
                                src="/home/logixsaas-dashboard.png" alt="logixsaas-dashboard">
                            <div class="shape-shadow"></div>
                        </div>
                        <div id="tab2" class="content color-two" style=""><img
                                src="/home/logixsaas-dashboard.png" alt="logixsaas-dashboard">
                            <div class="shape-shadow"></div>
                        </div>
                        <div id="tab3" class="content color-two" style="display: none;"><img
                                src="/home/logixsaas-orders.png" alt="logixsaas-dashboard">
                            <div class="shape-shadow"></div>
                        </div>
                        <div id="tab4" class="content color-two" style="display: none;"><img src="/home/4.jpg"
                                alt="logixsaas-dashboard">
                            <div class="shape-shadow"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="interface">
            <div class="container">
                <div class="interface-toparea">
                    <div class="row">
                        <div class="col-lg-7 pix-order-one">
                            <div class="interface-content pt-7">
                                <div class="interface-title pixFadeUp">
                                    <h2>
                                        Inventory & Warehousing<br>
                                        Solutions
                                    </h2>

                                    <p class="wow pixFadeUp" data-wow-delay="0.3s"
                                        style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                                        Say goodbye to paper works and excel sheets, Start using our inventory and warehouse
                                        management software platform and discover the easy way of managing your business. We
                                        have software solutions for all kind of logistics and e-commerce businesses.
                                    </p>

                                </div>
                                <!-- /.section-title style-two -->

                                <ul class="list-items wow pixFadeUp" data-wow-delay="0.4s"
                                    style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                    <li>Quick Access</li>
                                    <li>Easy setup process</li>
                                    <li>7/24h Support</li>
                                </ul>

                                <a href="/inventory-management-software" class="pix-btn btn-outline-two wow pixFadeUp"
                                    data-wow-delay="0.5s"
                                    style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeUp;">View
                                    More</a>

                            </div>
                            <!-- /.interface-content -->
                        </div>
                        <!-- /.col-lg-6 -->


                        <div class="col-lg-5">
                            <div class="interface-image-wrapper style-one">
                                <div class="image-one wow pixFadeUp"
                                    style="visibility: visible; animation-name: pixFadeUp;">
                                    <img src="{{ asset('home/logixsaas_2.png') }}" data-parallax="{&quot;y&quot; : 50}"
                                        alt="interface"
                                        style="transform:translate3d(0px, 14.75px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 14.75px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
                                </div>

                                <div class="image-two wow pixFadeDown"
                                    style="visibility: visible; animation-name: pixFadeDown;">
                                    <img src="{{ asset('home/logixsaas_1.png') }}" data-parallax="{&quot;y&quot; : -50}"
                                        alt="interface"
                                        style="transform:translate3d(0px, -33.925px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, -33.925px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1);max-width: 300px;">
                                </div>


                                <svg xmlns="http://www.w3.org/2000/svg" class="svgbg-one"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="700px" height="700px">
                                    <path fill-rule="evenodd" opacity="0.102" fill="rgb(251, 201, 82)"
                                        d="M350.000,-0.000 C543.300,-0.000 700.000,156.700 700.000,350.000 C700.000,543.299 543.300,700.000 350.000,700.000 C156.700,700.000 -0.000,543.299 -0.000,350.000 C-0.000,156.700 156.700,-0.000 350.000,-0.000 Z">
                                    </path>
                                </svg>
                            </div>
                            <!-- /.interface-image-wrapper -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.interface-toparea -->

                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-7">
                        <div class="interface-image-wrapper style-two">
                            <div class="image-one wow pixFadeRight"
                                style="visibility: visible; animation-name: pixFadeRight;">
                                <img src="{{ asset('home/28.png') }}" data-parallax="{&quot;x&quot; : 30}"
                                    alt="interface"
                                    style="transform:translate3d(0.029px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0.029px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
                            </div>

                            <div class="image-two wow pixFadeLeft"
                                style="visibility: visible; animation-name: pixFadeLeft;">
                                <img src="{{ asset('home/29.png') }}" data-parallax="{&quot;x&quot; : -30}"
                                    alt="interface"
                                    style="transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
                            </div>


                            <svg xmlns="http://www.w3.org/2000/svg" class="svgbg-two"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="700px" height="700px">
                                <path fill-rule="evenodd" opacity="0.102" fill="rgb(82, 251, 237)"
                                    d="M350.000,-0.000 C543.300,-0.000 700.000,156.700 700.000,350.000 C700.000,543.299 543.300,700.000 350.000,700.000 C156.700,700.000 -0.000,543.299 -0.000,350.000 C-0.000,156.700 156.700,-0.000 350.000,-0.000 Z">
                                </path>
                            </svg>
                        </div>
                        <!-- /.interface-image-wrapper -->
                    </div>
                    <!-- /.col-lg-6 -->

                    <div class="col-lg-5 pix-order-one">
                        <div class="interface-content">

                            <div class="interface-title pixFadeUp">
                                <h2>
                                    Super clean user <br>
                                    interface for easier use.
                                </h2>

                                <p class="wow pixFadeUp md-brn" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                                    Our system is much more than a group of pages connected by links. It’s an interface, a
                                    space where different things — in this case, a person and a company’s or individual’s
                                    web presence — meet, communicate, and affect each other.


                                </p>

                            </div>
                            <!-- /.section-title style-two -->

                            <ul class="list-items list-with-icon wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                <li><i class="fa-solid fa-cloud"></i> Secured Cloud</li>
                                <li><i class="fa-solid fa-user-check"></i> Implement Yourself</li>
                                <li><i class="fa-solid fa-globe"></i> Works on Web</li>
                            </ul>

                            <a href="/accounts?plan=1" class="pix-btn btn-outline-two wow pixFadeUp"
                                data-wow-delay="0.5s"
                                style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeUp;">Get your
                                free trial</a>

                        </div>
                        <!-- /.interface-content -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
                <!-- /.row -->

                <div class="border-wrap">
                    <span class="ball" data-parallax="{&quot;x&quot; : -100}"
                        style="transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>
                    <!-- Generator: Adobe Illustrator 23.0.1, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                    <svg version="1.1" id="animate-border" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 908.5 976.2" style="enable-background:new 0 0 908.5 976.2;" xml:space="preserve">
                        <style type="text/css">
                            .st0 {
                                fill: #FFFFFF;
                            }

                            .st1 {
                                opacity: 5.000000e-02;
                            }

                            .st2 {
                                fill: #1A4A88;
                            }

                            .st3 {
                                opacity: 0.8;
                                fill: #EA7225;
                            }

                            .st4 {
                                fill: #D86928;
                            }

                            .st5 {
                                fill: #EA7225;
                            }

                            .st6 {
                                fill: #F89938;
                            }

                            .st7 {
                                fill: #FDFEFE;
                            }

                            .st8 {
                                opacity: 0.5;
                                fill: #EA7225;
                            }

                            .st9 {
                                opacity: 0.75;
                                fill: #EA7225;
                            }

                            .st10 {
                                fill: none;
                                stroke: #1A4A88;
                                stroke-width: 1.5;
                                stroke-miterlimit: 10;
                            }

                            .st11 {
                                opacity: 0.1;
                            }

                            .st12 {
                                opacity: 0.85;
                                fill: #EA7225;
                            }

                            .st13 {
                                opacity: 0.81;
                                fill: #EA7225;
                            }

                            .st14 {
                                opacity: 0.4;
                                fill: #EA7225;
                            }

                            .st15 {
                                opacity: 0.7;
                                fill: #EA7225;
                            }

                            .st16 {
                                fill: none;
                                stroke: #F0577E;
                                stroke-width: 2;
                                stroke-miterlimit: 10;
                            }

                            .st17 {
                                fill: none;
                                stroke: #F0577E;
                                stroke-width: 2;
                                stroke-miterlimit: 10;
                                stroke-dasharray: 3.9904, 5.9856;
                            }
                        </style>
                        <g>
                            <g>
                                <g>
                                    <path class="st16" d="M8.4,908.7c0,0,0.6,0.4,1.7,1.1"></path>
                                    <path class="st17 path"
                                        d="M15.2,912.9c36.7,22,207.6,117.4,147.2-44.2c-67.5-180.6-137.7-410.5,320-358
                        c457.7,52.5,448.6-207.4,383-331C808.1,71.8,725.1,26.6,704.9,16.8">
                                    </path>
                                    <path class="st16" d="M702.2,15.5c-1.2-0.5-1.8-0.8-1.8-0.8"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>


            </div>
            <!-- /.container -->
        </section>

        <section class="signup-section">
            <div class="bg-shape">
                <img src="{{ asset('home/left-shape.png') }}" data-parallax="{&quot;x&quot;: -130}" alt="shape"
                    class="shape-left"
                    style="transform:translate3d(-122.712px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(-122.712px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
                <img src="{{ asset('home/right_shape.png') }}" data-parallax="{&quot;x&quot;: 130}" alt="shape"
                    class="shape-right"
                    style="transform:translate3d(105.584px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(105.584px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="signup-heading wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                            <h2 class="title">
                                Start managing your<br>
                                business better
                            </h2>

                            <p>
                                try any products free for 10 days
                            </p>
                        </div>
                        <!-- /.signup-heading -->
                    </div>
                    <!-- /.col-md-8 -->

                    <div class="col-md-4">
                        <div class="button-container text-right wow pixFadeUp"
                            style="visibility: visible; animation-name: pixFadeUp;">
                            <a href="/accounts?plan=1" class="pix-btn btn-large btn-light color-two">Signup for free</a>
                        </div>
                        <!-- /.button-container -->
                    </div>
                    <!-- /.col-md-4 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->
        </section>
        {{-- 
    <section class="download">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 pix-order-one">
                    <div class="download-wrapper">
                        <h2 class="title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">Available for your<br> smart phone.</h2>

                        <p class="wow pixFadeUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                            All the power of niche in your pocket. Schedule, publish<br>
                            and monitir your accounts with ease.
                        </p>

                        <div class="app-btn-wrapper">
                            <a href="#" class="app-btn btn-active wow flipInX" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: flipInX;">
                                <i class="fi flaticon-google-play"></i>
                                <span>Google play</span>
                            </a>

                            <a href="#" class="app-btn wow flipInX" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: flipInX;">
                                <i class="fi flaticon-apple-logo"></i>
                                <span>App Store</span>
                            </a>
                        </div>
                        <!-- /.app-btn-wrapper -->
                    </div>
                    <!-- /.download-wrapper -->
                </div>
                <!-- /.col-lg-5 -->

                <div class="col-lg-7">
                    <div class="download-feature-image">
                        <img src="https://saaspik.pixelsigns.art/saaspik/media/download/1.png" alt="" class="image-one wow pixFadeRight" style="visibility: visible; animation-name: pixFadeRight;">
                        <img src="https://saaspik.pixelsigns.art/saaspik/media/download/2.png" alt="" class="image-two wow pixFadeLeft" style="visibility: visible; animation-name: pixFadeLeft;">

                        <svg xmlns="http://www.w3.org/2000/svg" class="circle-svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="500px" height="500px">
                            <path fill-rule="evenodd" opacity="0.102" fill="rgb(251, 138, 82)" d="M250.000,-0.000 C388.071,-0.000 500.000,111.929 500.000,250.000 C500.000,388.071 388.071,500.000 250.000,500.000 C111.929,500.000 -0.000,388.071 -0.000,250.000 C-0.000,111.929 111.929,-0.000 250.000,-0.000 Z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
        @include('admin.landing.inc.footer')

    </div>
@endsection
