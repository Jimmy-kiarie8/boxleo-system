@extends('admin.landingpage.layout')



@section('title', 'Delivery App Real-time tracking | Logixsaas - Android app')
@section('description', 'The Logixsaas delivery app allows businesses to track their deliveries in real-time, send SMS notifications to customers, and accept MPESA payments..')
@section('keywords', 'delivery app, real-time location tracking, SMS integrations, MPESA payment, Logixsaas, logistics')
@section('og:title','Delivery App Real-time tracking | Logixsaas + Android app')
@section('og:description',"The Logixsaas delivery app allows businesses to track their deliveries in real-time, send SMS notifications to customers, and accept MPESA payments..")
@section('twitter:title','Delivery App Real-time tracking | Logixsaas + Android app')
@section('twitter:description',"The Logixsaas delivery app allows businesses to track their deliveries in real-time, send SMS notifications to customers, and accept MPESA payments..")
@section('url', 'delivery-app')


@section('content')
<a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>



<div id="main_content">
    @include('admin.landing.inc.header')  
    <section class="page-banner">
        <div class="container">
            <div class="page-title-wrapper">
                <h1 class="page-title">Delivery App</h1>

                <ul class="bradcurmed">
                    <li><a href="/" rel="">Home</a></li>
                    <li>Delivery App</li>
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

                        <p class="description wow pixFadeUp" data-wow-delay="0.4s" style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                            Have you ever been eagerly awaiting a delivery, only to spend the whole day at home waiting for the package to arrive? Or have you ever had to reschedule a delivery because you were unable to be home at the time it was set to arrive? These issues can be frustrating, but a new type of delivery app may be able to help.
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
            

                <div class="col-lg-5">
                    <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;width: 230px;">
                        <img src="{{ asset('home/app/app1.png') }}" alt="about" style="width: 200px">
                    </div>
                    <!-- /.about-thumb -->
                </div>
                <!-- /.col-lg-5 -->


                <div class="col-lg-6">
                    <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s"
                        style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                        <img src="{{ asset('home/app/app2.png') }}" alt="about" style="width: 200px">
                    </div>
                </div>
                <div class="col-lg-6" style="margin-top: 10px ">
                    <div class="about-content">
                        <div class="section-title">
                            <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                                Why a delivery app</h3>
                            <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;font-size: 25px;">
                                Automate your deliveries
                            </h2>
                        </div>

                        <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                            style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                            As the world becomes more connected and technology continues to advance, the need for efficient and convenient delivery services is greater than ever. That's where a new type of delivery app comes in. With features like real-time location tracking, online proof of delivery, digital signature capture, SMS integration, M-Pesa payment integration, and even real-time delivery updates sent to an admin system, this app is designed to make the delivery process smoother and more efficient for both customers and delivery companies.
                        </p>

                    </div>
                </div>
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

                <p>With real-time location tracking, online proof of delivery, digital signature capture, and SMS integration, this app provides a convenient and efficient way for customers to keep track of their packages and for delivery companies to manage their operations.
                </p>
            </div><!-- /.section-title -->

            <div class="row justify-content-center">
                

                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.6s" style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/app/pod.png') }}" alt="" style="width: 200px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Proof of Delivery (POD)</a></h3>

                            <p>
                                Logixsaas delivery app enables your customers to sign for their delivery digitally. This eliminates the need for a physical signature, making the process more efficient and convenient for both the customer and the delivery company. 
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
                            <img src="{{ asset('home/app/image.png') }}" alt="" style="width: 200px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Image capture</a></h3>
                            <p>
                                Logixsaas app includes the ability to take and upload images of the package and its contents. This can be useful for a variety of reasons, such as providing proof of the condition of the package or its contents in case of any disputes.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>
                        </div>

                        <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                            <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                        </svg>
                    </div><!-- /.pixsass-box style-four -->
                </div>


                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.9s" style="visibility: visible; animation-delay: 0.9s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/app/sms.png') }}" alt="" style="width: 200px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">SMS integration</a></h3>
                            <p>
                                Logixsaas includes SMS integration, allowing for notifications to be sent to customers about the status of their delivery. This keeps customers informed and allows them to make any necessary arrangements in a timely manner.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>
                        </div>

                        <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                            <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                        </svg>
                    </div><!-- /.pixsass-box style-four -->
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/app/location.png') }}" alt="" style="width: 200px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Real-time location tracking</a></h3>
                            <p>
                                One of the key features of the app is its real-time location tracking. This allows customers to see exactly where their package is at any given moment, giving them peace of mind and the ability to plan their day accordingly. No more waiting around for a delivery that may or may not arrive – with this app, you'll know exactly when to expect it.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>

                            <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                                <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                            </svg>
                        </div>
                    </div><!-- /.pixsass-box style-four -->
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.9s" style="visibility: visible; animation-delay: 0.9s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/app/mpesa.png') }}" alt="" style="width: 200px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">M-pesa integration</a></h3>
                            <p>
                                Logixsaas app also includes M-Pesa payment integration. This allows customers to pay for their delivery directly through the app using the popular mobile money platform (STK push). This adds an extra layer of convenience for customers, as they can pay for their delivery without having to worry about carrying cash or credit card.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>
                        </div>

                        <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                            <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                        </svg>
                    </div><!-- /.pixsass-box style-four -->
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="saaspik-icon-box-wrapper style-four wow pixFadeLeft" data-wow-delay="0.9s" style="visibility: visible; animation-delay: 0.9s; animation-name: pixFadeLeft;">
                        <div class="saaspik-icon-box-icon">
                            <img src="{{ asset('home/app/updates.png') }}" alt="" style="width: 200px; margin-top: -30px">
                        </div>
                        <hr>
                        <div class="pixsass-icon-box-content">
                            <h3 class="pixsass-icon-box-title"><a href="#">Real-time system updates</a></h3>
                            <p>
                                In addition to these features, the app also includes real-time delivery updates that are sent to an admin system. This allows delivery companies to track their packages in real-time and make any necessary adjustments to their operations. This helps to ensure that deliveries are made efficiently and on time.
                            </p>

                            <a href="#" class="more-btn"><i class="ei ei-arrow_right"></i></a>
                        </div>

                        <svg class="layer" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="370px" height="270px">
                            <path fill-rule="evenodd" fill="rgb(253, 248, 248)" d="M-0.000,269.999 L-0.000,-0.001 L370.000,-0.001 C370.000,-0.001 347.889,107.879 188.862,112.181 C35.160,116.338 -0.000,269.999 -0.000,269.999 Z"></path>
                        </svg>
                    </div><!-- /.pixsass-box style-four -->
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </section><!-- /.featured -->


    <section class="call-to-action">
        <div class="overlay-bg"><img src="{{ asset('home/ellipse.png') }}" alt="bg"></div>
        <div class="container">
            <div class="action-content text-center wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                <h2 class="title">
                    Take your business to the next level.
                </h2>

                <p>
                    Overall, this delivery app provides a convenient and efficient way for both customers and delivery companies to manage their operations. Real-time tracking, digital signature capture, image capture, SMS integration, and M-Pesa payment integration make the delivery process smoother and more efficient for everyone involved.
                </p>

                <a href="pricing" class="pix-btn btn-light">Get started</a>
            </div>
            <!-- /.action-content -->
        </div>
        <!-- /.container -->

        <div class="scroll-circle">
            <img src="{{ asset('home/circle13.png') }}" data-parallax="{&quot;y&quot; : -130}" alt="circle" style="transform:translate3d(0px, -130px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, -130px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
        </div>
        <!-- /.scroll-circle -->
    </section>

    @include('admin.landing.inc.footer')  


</div>
@endsection
