@extends('admin.landingpage.layout')


@section('title', 'Best Order Fulfillment Software and Real-time Tracking System | Logixsaas')
@section('description',
    'We’re Building Powerfull end-to-end Order Fulfillment and warehouse management Software and Connecting The Global Supply
    Chain',)
@section('og:title', 'Best Order Fulfillment Software | Logixsaas')
@section('og:description',
    'We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The Global
    Supply Chain',)
@section('twitter:title', 'Best Order Fulfillment Software | Logixsaas')
@section('twitter:description',
    'We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The
    Global Supply Chain',)
@section('url', 'order-fulfillment-software')

@section('content')
    <a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>



    <div id="main_content">

        @include('admin.landing.inc.header')
        <section class="page-banner">
            <div class="container">
                <div class="page-title-wrapper">
                    <h1 class="page-title">Order Fulfillment System</h1>

                    <ul class="bradcurmed">
                        <li><a href="/" rel="">Home</a></li>
                        <li>Order Fulfillment</li>
                    </ul>
                </div>
            </div>


            <svg class="circle" data-parallax="{&quot;x&quot; : -200}" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" width="950px" height="950px"
                style="transform:translate3d(-200px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(-200px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
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
        <section class="about">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="about-content">
                            <div class="section-title">
                                <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                                    A state-of the art order fulfillment solution
                                </h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;     font-size: 25px;">
                                    Professsional order fulfillment and <br>
                                    inventory management with Logixsaas.

                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Get started with an order fulfillment system for your small or medium business with Logixsaas. We help you to automate workflow, fulfill orders faster, and improve customer satisfaction.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s"
                            style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                            <img src="{{ asset('/home/analytic.jpg') }}" alt="multi-channel order management">
                        </div>
                    </div>



                    <div class="col-lg-6">
                        <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s"
                            style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                            <img src="{{ asset('/home/ecommerce-integration.jpg') }}" alt="e-commerce integration">
                        </div>
                    </div>
                    <div class="col-lg-6" style="margin-top: 10px ">
                        <div class="about-content">
                            <div class="section-title">
                                <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                                    Professional Dashboard</h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;font-size: 25px;">
                                    Analytics, charts <br>
                                    and insights
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Have a quick overview ofl your orders in one dashboard. Filter orders  by status i.e pending, confirmed, dispatched, returned or delivered.
                            </p>

                        </div>
                    </div>


                    <div class="col-lg-6" style="margin-top: 10px ">
                        <div class="about-content">
                            <div class="section-title">
                                <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                                    Automation </h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;     font-size: 25px;">
                                    You never have to worry about how to do an inventory count <br>
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Automatically convert an order into a packing slip in one click. The information is imported automatically and you can print or send an email to the customer email. Create rules to trigger sending SMSs and emails to your customer. Create custom reminders (eg. Get an email when stock is running low).
                            </p>

                            <div class="singiture wow pixFadeUp" data-wow-delay="0.5s"
                                style="visibility: visible; animation-delay: 0.5s; animation-name: pixFadeUp;">
                                <h4>
                                    Max Conversion
                                </h4>

                                <img src="{{ asset('/home/sign.png') }}" class="wow pixFadeUp" data-wow-delay="0.6s"
                                    alt="Max Conversion"
                                    style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeUp;">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s"
                            style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                            <img src="{{ asset('/home/inventory-count.jpg') }}" alt="inventory-count">
                        </div>
                    </div>



                </div>
            </div>
        </section>

        <section class="featured-ten">
			<div class="container">
				<div class="section-title style-six text-center">

					<h2 class="title wow fadeInUp" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
						Why You Should Choose Our Order Fulfillment Software
					</h2>
					<p class="wow fadeInUp" data-wow-delay="0.5" style="visibility: visible; animation-name: fadeInUp;">
						Logixsaas is more than an order management software. We can help your business succeed, grow, and thrive in all areas. Extend the functionalites of your business apps and softwares in minutes.
					</p>
				</div><!-- /.section-title -->

				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_5.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Real-time tracking</a></h3>
								<p>
									With a user-friendly customer web tracking page, customers can Easily place track orders online, view the status of their order on map interface with real-time location of delivery agent, View the on-going discounts and special offers, pay online for the order, and rate your order fulfillment service.
								</p>
							</div>
						</div>
					</div>




					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_1.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Notification</a></h3>
								<p> Keep your customers updated throughout the delivery lifecycle by sending them real-time SMSs at various stages. Send your customers tracking links through emails and SMSs for real-time location tracking. An order fulfillment system for tracking your agents in real-time & allows customers to chat with the agent.
                                </p>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_6.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Route optimization</a></h3>
								<p>Plan the routes you want to optimize, assign to a delivery agent based on their availability and location using our route optimization app. Create a single delivery for one pickup and multiple drop-offs. Plan and optimize your routes for efficient order fulfillment with the help of delivery route planner system.
                                    </p>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_3.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Client portal</a></h3>
								<p>Logixsaas web portal will allow your clients to directly create orders in the dashboard. Provide your ecommerce clients with a web portal to create a pick and drop order, track orders, and get notified when the order is completed. These are customizable which can be used as per your order fulfillment requirement.
                                </p>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_4.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Delivery app</a></h3>

								<p>
                                    Save time and cost by auto-assigning orders to free and closest agents using our route optimization system. Navigate the agent through the most optimized route to make a doorstep delivery for every customer in the least possible time. Track your agents using our admin-maps interface, filter and view their movement for a given time period, view the exact location an order was delivered and more.
								</p>

							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_2.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Admin dashboard</a></h3>
								<p> Easily manage your deliveries from one admin screen. Monitor number of deliveries, Returns, Ratings & Reviews of your customers,  and cancelled orders. Get advanced analytic reports and insights of your order fulfillment processes to help you streamline your processes. Track your delivery agents in real time within the admin dashboard maps and send notification to a specific or all agents for better workforce productivity.</p>
							</div>
						</div>
					</div>
				</div><!-- /.row -->
			</div><!-- /.container -->
		</section>

        @include('admin.landing.inc.footer')


    </div>
@endsection
