@extends('admin.landingpage.layout')

@section('title', 'Best Order fulfillment system Plans and Pricing | Inventory and Warehousing management system | Logixsaas')
@section('description', "Sign up for a basic featured Logixsaas trial. It's completely free for 10 days, and you can cancel at any time - no hassle and no questions asked.")

@section('og:title','Best Order fulfillment system Plans and Pricing | Inventory and Warehousing management system | Logixsaas')
@section('og:description',"Sign up for a basic featured Logixsaas trial. It's completely free for 10 days, and you can cancel at any time - no hassle and no questions asked.")
@section('twitter:title','Order fulfillment system Plans and Pricing | Inventory and Warehousing management system | Logixsaas')
@section('twitter:description',"Sign up for a basic featured Logixsaas trial. It's completely free for 10 days, and you can cancel at any time - no hassle and no questions asked.")

@section('url', 'pricing')




@section('content')
<a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>



<div id="main_content">
    @include('admin.landing.inc.header')  

    <section class="page-banner">
        <div class="container">
            <div class="page-title-wrapper">
                <h1 class="page-title">Pricing Table</h1>

                <ul class="bradcurmed">
                    <li><a href="/" rel="noopener noreferrer">Home</a></li>
                    <li> Pricing Table</li>
                </ul>
            </div>
            <!-- /.page-title-wrapper -->
        </div>
        <!-- /.container -->

        <svg class="circle" data-parallax="{&quot;x&quot; : -200}" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="950px" height="950px" style="transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
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

    <!--==============================-->
    <!--=         Pricin One         =-->
    <!--==============================-->
    @include('admin.landingpage.pricing')

    <!-- /.pricing -->

    <!--==================================-->
    <!--=         Call to Action         =-->
    <!--==================================-->
    <section class="call-to-action action-padding">
        <div class="overlay-bg">
            <img src="{{ asset('home/ellipse.png') }}" alt="bg">
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-7">
                    <div class="action-content style-two wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                        <h2 class="title">
                            Getting connected with us
                            & for the update.
                            <br>
                        </h2>
                    </div>
                    <!-- /.action-content -->
                </div>
                <!-- /.col-lg-7 -->

                <div class="col-lg-5 text-right">
                    <a href="/accounts" class="pix-btn btn-light btn-big scroll-btn wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">Get started!</a>
                </div>
                <!-- /.col-lg-5 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /.call-to-action -->

    @include('admin.landing.inc.footer')  


</div>
@endsection
