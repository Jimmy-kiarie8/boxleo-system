@extends('admin.landingpage.layout')


@section('title', 'Best Inventory Management Software and Stock Control System | Logixsaas')
@section('description',
    'We’re Building Powerfull end-to-end Order Fulfillment and warehouse management Software and Connecting The Global Supply
    Chain',)
@section('og:title', 'Best Inventory Management Software | Logixsaas')
@section('og:description',
    'We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The Global
    Supply Chain',)
@section('twitter:title', 'Best Inventory Management Software | Logixsaas')
@section('twitter:description',
    'We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The
    Global Supply Chain',)
@section('url', 'about')

@section('content')
    <a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>



    <div id="main_content">

        @include('admin.landing.inc.header')
        <section class="page-banner">
            <div class="container">
                <div class="page-title-wrapper">
                    <h1 class="page-title">Inventory control system</h1>

                    <ul class="bradcurmed">
                        <li><a href="/" rel="">Home</a></li>
                        <li>Inventory management</li>
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
                                    Stock control at its finest</h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;     font-size: 25px;">
                                    Arm yourself with a powerful <br>
                                    inventory management software
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Logixsaas will manage stock across multiple locations and warehouses flawlessly. Do you have
                                multiple stores? Check inventory levels and easily transfer products from one warehouse to
                                the next to avoid out-of-stocks and excess inventory.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="about-thumb wow pixFadeRight" data-wow-delay="0.6s"
                            style="visibility: visible; animation-delay: 0.6s; animation-name: pixFadeRight;">
                            <img src="{{ asset('/home/analytic.jpg') }}" alt="multi-channel inventory management">
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
                                    Multi-channel inventory management</h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;font-size: 25px;">
                                    Integrates with the best ecommerce platforms including <br>
                                    Shopify and WooCommerce.
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Selling online, on marketplaces and on social media? Logixsaas automatically adjusts your
                                inventory levels, with zero manual effort on your part. You might have dozens of shops, each
                                with their own ordering processes. Logixsaas makes things easier by serving as a central,
                                cloud-based system, so you and your team can see what you’ve got.
                            </p>

                        </div>
                    </div>


                    <div class="col-lg-6" style="margin-top: 10px ">
                        <div class="about-content">
                            <div class="section-title">
                                <h3 class="sub-title wow pixFadeUp" style="visibility: visible; animation-name: pixFadeUp;">
                                    Easily count your inventory </h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;     font-size: 25px;">
                                    You never have to worry about how to do an inventory count <br>
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Whether you’re counting all your products in one sitting or cycle counting through your
                                catalog, Vend has tools to help you track, record, and reconcile inventory quickly and
                                accurately.
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
						Why you choose Our Inventory Software
					</h2>
					<p class="wow fadeInUp" data-wow-delay="0.5" style="visibility: visible; animation-name: fadeInUp;">
						Logixsaas is more than an inventory management software system. We can help your business succeed, grow, and thrive in all areas.
					</p>
				</div><!-- /.section-title -->

				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.3s" style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_5.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Connect to your E-commerce site</a></h3>
								<p>
									Selling items online? Logixsaas inventory system connects with Shopify and WooCommerce so you can manage your online sales from one platform. Logixsaas also provides an easy to integrate API to connect with anyother E-commerce platform or fulfillment/delivery software, so you can send your orders directly to customers.
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
								<h3 class="pixsass-icon-box-title"><a href="#">Distributed inventory</a></h3>

								<p>
                                    Strategically split your inventory across our locations. Distributed inventory helps you stay competitive by offering fast shipping to customers. When an order is placed, Logixsaas algorithm automatically selects the warehouse that will give you the quickest turnaround. This strategy has helped our customers bring savings to their bottom line.
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
								<h3 class="pixsass-icon-box-title"><a href="#">Easy onboarding</a></h3>
								<p>We’ve onboarded thousands of ecommerce merchants and helped some of the fastest-growing brands scale effortlessly. Our implementation team sets you up from configuring your business options, to syncing your online store. Logixsaas integrates with all major ecommerce platforms and marketplaces including Shopify and Woocommerce</p>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_3.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Inventory management</a></h3>
								<p>Wee empower merchants to manage their inventory with the right tools and guidance. It’s easy to view the status of inventory and quantity on hand across locations at any point in time. Our inventory management software helps you set reminders to proactively restore inventory with reorder notifications, make inventory transfer requests and more.</p>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_1.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">Reporting and analytics</a></h3>
								<p> To provide you with visibility into your operations and performance, Logixsaas free inventory analytics tool is packed with charts to help you with everything from year-end reporting, to better supply chain decision-making. Data can be a competitive advantage, and our reports show an analysis of which fulfillment centers you should stock.</p>
							</div>
						</div>
					</div>


					<div class="col-lg-4 col-md-6">
						<div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
							<div class="saaspik-icon-box-icon">
								<img src="{{ asset('home/logixsaas_inventory_6.png') }}" alt="">
							</div>
							<div class="pixsass-icon-box-content">
								<h3 class="pixsass-icon-box-title"><a href="#">End-to-end customer experience</a></h3>
								<p>With Logixsaas inventory management system, you can strategically split your inventory across your locations to get your products from point A to point B more quickly and affordably. Storing inventory near your customers helps reduce the shipping zones and costs associated with shipping orders to faraway destinations.</p>
							</div>
						</div>
					</div>
				</div><!-- /.row -->
			</div><!-- /.container -->
		</section>

        @include('admin.landing.inc.footer')


    </div>
@endsection
