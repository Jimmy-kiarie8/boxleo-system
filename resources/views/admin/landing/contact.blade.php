@extends('admin.landingpage.layout')


@section('title', 'Contact Us | Logixsaas')
@section('description', 'We’re here to help. Ask us about the product, our services, and any questions you have.')
@section('og:title', 'Contact Us | Logixsaas')
@section('og:description', 'We’re here to help. Ask us about the product, our services, and any questions you have.')
@section('twitter:title', 'Contact Us | Logixsaas')
@section('twitter:description',
    'We’re here to help. Ask us about the product, our services, and any questions you
    have.',)
@section('url', 'contact')



@section('content')
    <a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>

    <div id="main_content">


        <!--=========================-->
        <!--=        Navbar         =-->
        <!--=========================-->
        @include('admin.landing.inc.header')

        <div id="main_content">

            <!--==========================-->
            <!--=     Banner         =-->
            <!--==========================-->
            <section class="page-banner-contact">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="page-title-wrapper">
                                <div class="page-title-inner">
                                    <h1 class="page-title">Get in touch with Us</h1>
                                    <p>
                                        Our support team are available 24/7 to answer your questions.


                                    </p>
                                </div>
                                <!-- /.page-title-inner -->
                            </div>
                            <!-- /.page-title-wrapper -->
                        </div>
                        <!-- /.col-lg-8 -->

                        <div class="col-lg-4">
                            <div class="animate-element-contact">
                                <img src="{{ asset('home/001.png') }}" alt="" class="wow pixFadeDown"
                                    data-wow-duration="1s"
                                    style="visibility: visible; animation-duration: 1s; animation-name: pixFadeDown;">
                                <img src="{{ asset('home/002.png') }}" alt="" class="wow pixFadeUp"
                                    data-wow-duration="2s"
                                    style="visibility: visible; animation-duration: 2s; animation-name: pixFadeUp;">
                                <img src="{{ asset('home/003.png') }}" alt="" class="wow pixFadeLeft"
                                    data-wow-delay="0.3s" data-wow-duration="2s"
                                    style="visibility: visible; animation-duration: 2s; animation-delay: 0.3s; animation-name: pixFadeLeft;">
                                <img src="{{ asset('home/004.png') }}" alt="man" class="wow pixFadeUp"
                                    data-wow-duration="2s"
                                    style="visibility: visible; animation-duration: 2s; animation-name: pixFadeUp;">
                            </div>
                            <!-- /.animate-element-contact -->
                        </div>
                        <!-- /.col-lg-4 -->
                    </div>
                    <!-- /.row -->

                </div>
                <!-- /.container -->

                <svg class="circle" data-parallax="{&quot;y&quot; : 250}" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" width="950px" height="950px"
                    style="transform:translate3d(0px, 250px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 250px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
                    <path fill-rule="evenodd" stroke="rgb(250, 112, 112)" stroke-width="100px" stroke-linecap="butt"
                        stroke-linejoin="miter" opacity="0.051" fill="none"
                        d="M450.000,50.000 C670.914,50.000 850.000,229.086 850.000,450.000 C850.000,670.914 670.914,850.000 450.000,850.000 C229.086,850.000 50.000,670.914 50.000,450.000 C50.000,229.086 229.086,50.000 450.000,50.000 Z">
                    </path>
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

            <!--===========================-->
            <!--=         Contact         =-->
            <!--===========================-->
            <section class="contactus">
                <div class="container">
                    <div class="row">
                        @if (Session::has('message'))
                            <div class="form-result alert alert-success" style="display: block;margin-bottom: 20px">
                                <div>Thank you for contacting us. We will contact you as soon as posible.</div>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li style="list-style: none;">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-4">
                                <div class="contact-infos">
                                    <div class="contact-info">
                                        {{-- <h3 class="title">Our Location</h3>
                                        <p class="description">
                                            19 Belmont Ave
                                            Unit#122
                                            <br>
                                            Brooklyn,NY 11212
                                        </p> --}}

                                        {{-- <div class="info phone">
                                                <i class="ei ei-icon_phone"></i>
                                                <span>+1 601 978 2212</span>
                                            </div> --}}
                                    </div>
                                    <!-- /.contact-info -->

                                    <div class="contact-info">
                                        <h3 class="title">Say Hello</h3>
                                        {{-- <p class="description">
                                            19 Belmont Ave
                                            Unit#122 <br>
                                            Brooklyn,NY 11212
                                        </p> --}}

                                        <div class="info">
                                            <i class="ei ei-icon_mail_alt"></i>
                                            <a href="mailto:support@logixsaas.com" style="color: #797687"> 
                                                <i class="fa-solid fa-envelope-circle-check"></i>
                                                Email us</a>
                                            {{-- <span>info@logixsaas.com</span> --}}
                                        </div>
                                    </div>
                                    <!-- /.contact-info -->
                                </div>
                                <!-- /.contact-infos -->
                            </div>
                            <!-- /.col-md-4 -->
                            <div class="col-md-8">
                                <div class="contact-froms">
                                    <form action="{{ route('contact') }}" method="POST" class="contact-form">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" name="name" placeholder="Name" required=""
                                                    style="background: #fdfafa; border: 1px solid #efe7e7;margin-bottom: 30px;border-radius: 30px;">
                                            </div>

                                            <div class="col-md-4">
                                                <input type="email" name="email" placeholder="Email" required=""
                                                    style="background: #fdfafa; border: 1px solid #efe7e7;margin-bottom: 30px;border-radius: 30px;">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" name="phone" placeholder="Phone no." required=""
                                                    style="background: #fdfafa; border: 1px solid #efe7e7;margin-bottom: 30px;border-radius: 30px;">
                                            </div>
                                        </div>

                                        <input type="text" name="subject" placeholder="Subject"
                                            style="background: #fdfafa; border: 1px solid #efe7e7;margin-bottom: 30px;border-radius: 30px;">

                                        <textarea name="content" placeholder="Your Comment" required=""
                                            style="background: #fdfafa; border: 1px solid #efe7e7;margin-bottom: 30px;border-radius: 30px;"></textarea>

                                        {!! NoCaptcha::renderJs() !!}
                                        {!! NoCaptcha::display() !!}
                                        <button type="submit" class="pix-btn submit-btn" style="color: #eaeaea !important">
                                            <span class="btn-text">Send Your Massage</span>
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </button>
                                        {{-- <input type="hidden" name="recaptcha_response" id="recaptchaResponse"> --}}
                                    </form>
                                    <!-- /.contact-froms -->
                                </div>
                                <!-- /.faq-froms -->
                            </div>
                            <!-- /.col-md-8 -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.container -->
            </section>
            <!-- /.contactus -->

            <!--========================-->
            <!--=         Map         =-->
            <!--========================-->
            <section id="google-maps">
                <!-- /.google-map -->
            </section><!-- /#google-maps -->


        </div>
        <!--=========================-->
        <!--=        Footer         =-->
        <!--=========================-->
        @include('admin.landing.inc.footer')


    </div>
@endsection
