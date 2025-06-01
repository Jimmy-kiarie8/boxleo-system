@extends('admin.landingpage.layout')


@section('title', 'About Us | Logixsaas')
@section('description', 'We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The Global Supply Chain')
@section('og:title','About Us | Logixsaas')
@section('og:description',"We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The Global Supply Chain")
@section('twitter:title','About Us | Logixsaas')
@section('twitter:description',"We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The Global Supply Chain")
@section('url', 'about') 

@section('content')
<a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>



<div id="main_content">


    <!--=========================-->
    <!--=        Navbar         =-->
    <!--=========================-->
    {{-- @include('admin.landing.inc.mobileheader')   --}}
    @include('admin.landing.inc.header')  

    <!--==========================-->
    <!--=         Banner         =-->
    <!--==========================-->
    <section class="page-banner">
        <div class="container">
            <div class="page-title-wrapper">
                <h1 class="page-title">About Us</h1>

                <ul class="bradcurmed">
                    <li><a href="/" rel="">Home</a></li>
                    <li>About Us</li>
                </ul>
            </div>
            <!-- /.page-title-wrapper -->
        </div>
        <!-- /.container -->


        <svg class="circle" data-parallax="{&quot;x&quot; : -200}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="950px" height="950px" style="transform:translate3d(-200px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(-200px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
            <path fill-rule="evenodd" stroke="rgb(250, 112, 112)" stroke-width="100px" stroke-linecap="butt" stroke-linejoin="miter" opacity="0.051" fill="none" d="M450.000,50.000 C670.914,50.000 850.000,229.086 850.000,450.000 C850.000,670.914 670.914,850.000 450.000,850.000 C229.086,850.000 50.000,670.914 50.000,450.000 C50.000,229.086 229.086,50.000 450.000,50.000 Z"></path>
        </svg>

        <ul class="animate-ball">
            <li class="ball"></li>
            <li class="ball"></li>
            <li class="ball"></li>
            <li class="ball"></li>
            <li class="ball"></li>
        </ul>
    </section>
    <!-- /.page-banner -->

    <!--=========================-->
    <!--=         About         =-->
    <!--=========================-->
    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="about-content">
                        <div class="section-title">
                            <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">Logixsaas</h3>
                            <h2 class="title wow pixFadeUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                                A wide range of business<br>
                                and Saas services
                            </h2>
                        </div>
                        <!-- /.section-title -->

                        <p class="description wow pixFadeUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                            With Logixsaas you can start your logistic business from scratch. We can provide a new way and technology to companies of all sizes to create new relationships and extend relational operations with new technology.
                        </p>

                        <div class="singiture wow pixFadeUp" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeUp;">
                            <h4>
                                Max Conversion
                            </h4>

                            <img src="{{ asset('/home/sign.png') }}" class="wow pixFadeUp" data-wow-delay="0.6s" alt="sign" style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeUp;">
                        </div>
                        <!-- /.singiture -->
                    </div>
                    <!-- /.about-content -->
                </div>
                <!-- /.col-lg-7 -->

                <div class="col-lg-5">
                    <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                        <img src="{{ asset('/home/analytic.jpg') }}" alt="about">
                    </div>
                    <!-- /.about-thumb -->
                </div>
                <!-- /.col-lg-5 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /.about -->

    <!--===========================-->
    <!--=         Feature         =-->
    <!--===========================-->
    <section class="featured-four-ab">
        <div class="container">
            <div class="section-title text-center">
                <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">Our service</h3>
                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">Why you choose Our Software</h2>
            </div><!-- /.section-title -->

            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/19.png')}}" alt="">
                        </div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Engage Your Customers</a></h3>
                            <p>
                                Customer support portal provides a simple and clean interface for your customer support team to pull customer information in one click. This interface provides customer shipment history, current status and support tickets to answer customers queries faster and better. The support team can also directly attend customer chat request and manage multiple chats with a customer at one place.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>

                            <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                                <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                            </svg>
                        </div>
                    </div><!-- /.pixsass-box style-four -->
                </div><!-- /.col-lg-4 col-md-6 -->

                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/20.png')}}" alt="">
                        </div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Shipment Management</a></h3>

                            <p>
                                Managing shipments is a critical time-consuming process in the logistics industry. Shipping logistics management involves organisation, management of trailers across the carrier's offices, execution, and control of the transportation of your shipped items. Waybill-software can control these work process and increase the productivity and efficiency of the company's systems.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>
                        </div>

                        <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                            <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                        </svg>
                    </div><!-- /.pixsass-box style-four -->
                </div><!-- /.col-lg-4 col-md-6 -->

                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.9s" style="visibility: visible; animation-delay: 0.9s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/18.png')}}" alt="">
                        </div>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Shipment Status Updates</a></h3>
                            <p>
                                You need a service that alerts you to changes, status updates, and other details, keeping you current on where your product is in the shipping process. Here is where Waybill software's Shipment Status Updates feature comes in handy.The Barcode scanner interface allows your staff to scan the shipments that are ready to update all at once and then they can select the result and update the status with one click
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>
                        </div>

                        <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                            <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                        </svg>
                    </div><!-- /.pixsass-box style-four -->
                </div><!-- /.col-lg-4 col-md-6 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.featured -->

    <!--===========================-->
    <!--=         Count Up        =-->
    <!--===========================-->
    <section class="countup">
        <div class="bg-map" data-bg-image="media/background/map2.png" style="background-image: url(&quot;media/background/map2.png&quot;);">

        </div>
        <div class="container">
            <div class="section-title text-center">
                <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">Fun Facts</h3>
                <h2 class="title wow pixFadeUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: pixFadeUp;">
                    We Always try to Understand<br>
                    Users expectation
                </h2>
            </div>
            <!-- /.section-title -->

            <div class="countup-wrapper">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact wow pixFadeUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                            <div class="counter">
                                <h4 class="count" data-counter="14">14</h4>
                                <span>K+</span>
                            </div>
                            <!-- /.counter -->

                            <p>Total Products</p>
                        </div>
                        <!-- /.fun-fact -->
                    </div>
                    <!-- /.col-lg-3 col-md-6 col-sm-6 -->

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact color-two wow pixFadeUp" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeUp;">
                            <div class="counter">
                                <h4 class="count" data-counter="1">1</h4>
                                <span>M+</span>
                            </div>
                            <!-- /.counter -->
                            <p>Total Orders</p>
                        </div>
                        <!-- /.fun-fact -->
                    </div>
                    <!-- /.col-lg-3 col-md-6 col-sm-6 -->

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact color-three wow pixFadeUp" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: pixFadeUp;">
                            <div class="counter">
                                <h4 class="count" data-counter="1000">1000</h4>
                                <span>+</span>
                            </div>
                            <!-- /.counter -->
                            <p>Total users</p>
                        </div>
                        <!-- /.fun-fact -->
                    </div>
                    <!-- /.col-lg-3 col-md-6 col-sm-6 -->

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="fun-fact color-four wow pixFadeUp" data-wow-delay="0.9s" style="visibility: visible; animation-delay: 0.9s; animation-name: pixFadeUp;">
                            <div class="counter">
                                <h4 class="count" data-counter="100">100</h4>
                                <span>+</span>
                            </div>
                            <!-- /.counter -->
                            <p>Total clients</p>
                        </div>
                        <!-- /.fun-fact -->
                    </div>
                    <!-- /.col-lg-3 col-md-6 col-sm-6 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.countup-wrapper -->

            <div class="button-container text-center wow pixFadeUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                <a href="#" class="pix-btn btn-outline">See All Review</a>
            </div>
            <!-- /.button-container -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /.countup -->

    <!--==================================-->
    <!--=         Call To Action         =-->
    <!--==================================-->
    <section class="call-to-action">
        <div class="overlay-bg"><img src="{{ asset('home/ellipse.png') }}" alt="bg"></div>
        <div class="container">
            <div class="action-content text-center wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                <h2 class="title">
                    We are optimists who<br>
                    love to work together
                </h2>

                <p>
                    Cloud based logistics software bundle to manage your logistics business from anywhere. The ease & simplicity of Logixsaas make logistics simple. One platform to manage all your logistics process. Complete customization allow you to create comprehensive solution to your logistics business.
                </p>

                <a href="#" class="pix-btn btn-light">Free Consultation</a>
            </div>
            <!-- /.action-content -->
        </div>
        <!-- /.container -->

        <div class="scroll-circle">
            <img src="{{ asset('home/circle13.png') }}" data-parallax="{&quot;y&quot; : -130}" alt="circle" style="transform:translate3d(0px, -130px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, -130px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
        </div>
        <!-- /.scroll-circle -->
    </section>
    <!-- /.call-to-action -->

    <!--=========================-->
    <!--=        Footer         =-->
    <!--=========================-->
    @include('admin.landing.inc.footer')  


</div>
@endsection
