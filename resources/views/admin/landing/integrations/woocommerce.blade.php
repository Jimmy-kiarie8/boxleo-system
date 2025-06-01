@extends('admin.landingpage.layout')



@section('title', 'Woocommerce Order Management | Logixsaas + Woocommerce Integration')
@section('description', 'Power up your Woocommerce order management with Logixsaas. Enjoy seamless integration with your clients store. Manage orders, shipping and much more.')
@section('og:title','Woocommerce Order Management | Logixsaas + Woocommerce Integration')
@section('og:description',"Power up your Woocommerce order management with Logixsaas. Enjoy seamless integration with your clients store. Manage orders, shipping and much more.")
@section('twitter:title','Woocommerce Order Management | Logixsaas + Woocommerce Integration')
@section('twitter:description',"Power up your Woocommerce order management with Logixsaas. Enjoy seamless integration with your clients store. Manage orders, shipping and much more.")
@section('url', 'woocommerce')

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
                <h1 class="page-title">Woocommerce</h1>

                <ul class="bradcurmed">
                    <li><a href="/" rel="">Home</a></li>
                    <li>Woocommerce</li>
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
                                Power every part of your business
                            </h2>
                        </div>
                        <!-- /.section-title -->

                        <p class="description wow pixFadeUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                            If you're looking for a warehouse management system, inventory management, order fulfillment, and courier system all in one, then Logixsaas is the solution for you. As an e-commerce business owner, it's essential to have a reliable system to help you manage your inventory, fulfill orders efficiently, and provide reliable shipping to your customers. With Logixsaas, you can do all of these things and more.
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
                        <img src="{{ asset('/home/woocommerce.jpg') }}" alt="Logixsaas woocommerce">
                    </div>
                    <!-- /.about-thumb -->
                </div>
                <!-- /.col-lg-5 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                        <img src="{{ asset('/home/woocommerce2.jpg') }}" alt="Logixsaas woocommerce">
                    </div>
                    <!-- /.about-thumb -->
                </div>
                <!-- /.col-lg-5 -->
                <div class="col-lg-7">
                    <div class="about-content">
                        <div class="section-title">
                            <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">Logixsaas Plugin</h3>
                            <h2 class="title wow pixFadeUp" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;">
                                <a href="https://logixsaas.com/plugins/logixsaas.zip" target="_blank">Click here</a> to download
                            </h2>
                        </div>
                        <!-- /.section-title -->

                        <p class="description wow pixFadeUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                            If you're using WooCommerce as your e-commerce platform, Logixsaas can integrate with it easily. With the <a href="https://logixsaas.com/plugins/logixsaas.zip" target="_blank">Logixsaas WooCommerce plugin</a>, you can transmit orders in real-time to Logixsaas, making it easy to keep track of your orders and inventory. The plugin is easy to install and set up, making it accessible for businesses of all sizes.
                        </p>

                        <!-- /.singiture -->
                    </div>
                    <!-- /.about-content -->
                </div>
                <!-- /.col-lg-7 -->

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
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
                            <img src="{{ asset('home/shopify_analysis.png') }}" alt="Logixsaas woocommerce" style="width: 150px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Tracking&adjusting inventory</a></h3>
                            <p>
                                You can view and adjust inventory levels and also view a product's inventory history. 
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
                            <img src="{{ asset('home/shopify_product.png') }}" alt="Logixsaas woocommerce" style="width: 150px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Importing products</a></h3>

                            <p>
                                You can import products directly from your clients woocommerce account to the system. 
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
                            <img src="{{ asset('home/shopify_orders.png') }}" alt="Logixsaas woocommerce" style="width: 150px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Importing orders</a></h3>
                            <p>
                                You can import and manage orders directly from your clients woocommerce account to the system.
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

    @include('admin.landing.inc.footer')  


</div>
@endsection
