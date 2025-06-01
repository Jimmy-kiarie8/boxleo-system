@extends('admin.landingpage.layout')



@section('title', 'FAQs about Logixsaas | Order fulfillment Software Integration')
@section('description', 'Frequently asked questions about the Logixsaas order fulfillment software, WMS, and ecommerce order management system.')
@section('keywords', 'Order fulfillment software, warehouse management, inventory management, process automation, SMS integration, ecommerce platform integrations, API integrations, delivery app, reporting, Logixsaas, order management, cloud-based software, e-commerce')
@section('og:title','FAQs about Logixsaas | Logixsaas + Order fulfillment Software Integration')
@section('og:description',"Frequently asked questions about the Logixsaas order fulfillment software, WMS, and ecommerce order management system.")
@section('twitter:title','FAQs about Logixsaas | Logixsaas + Order fulfillment Software Integration')
@section('twitter:description',"Frequently asked questions about the Logixsaas order fulfillment software, WMS, and ecommerce order management system.")
@section('url', 'faqs')


@section('content')
	<a href="#main_content" data-type="section-switch" class="return-to-top">
		<i class="fa fa-chevron-up"></i>
	</a>

	<div class="page-loader">
		<div class="loader">
			<!-- Loader -->
			<div class="blobs">
				<div class="blob-center"></div>
				<div class="blob"></div>
				<div class="blob"></div>
				<div class="blob"></div>
				<div class="blob"></div>
				<div class="blob"></div>
				<div class="blob"></div>
			</div>
			<svg xmlns="http://www.w3.org/2000/svg" version="1.1">
				<defs>
					<filter id="goo">
						<feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
						<feColorMatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
						<feBlend in="SourceGraphic" in2="goo" />
					</filter>
				</defs>
			</svg>

		</div>
	</div><!-- /.page-loader -->

	<div id="main_content">

    @include('admin.landing.inc.header')  


        
		<section class="page-banner">
			<div class="container">
				<div class="page-title-wrapper">
					<h1 class="page-title">Questions & Asked</h1>

					<ul class="bradcurmed">
						<li><a href="#" rel="noopener noreferrer">Home</a></li>
						<li>Faq's</li>
					</ul>
				</div>
				<!-- /.page-title-wrapper -->
			</div>
			<!-- /.container -->

			<svg class="circle" data-parallax='{"x" : -200}' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="950px" height="950px">
				<path fill-rule="evenodd" stroke="rgb(250, 112, 112)" stroke-width="100px" stroke-linecap="butt" stroke-linejoin="miter" opacity="0.051" fill="none" d="M450.000,50.000 C670.914,50.000 850.000,229.086 850.000,450.000 C850.000,670.914 670.914,850.000 450.000,850.000 C229.086,850.000 50.000,670.914 50.000,450.000 C50.000,229.086 229.086,50.000 450.000,50.000 Z" />
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
		<section class="faqs">
			<div class="container">
				<div class="tabs-wrapper">
					<ul class="nav faq-tabs" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="design-tab" data-toggle="tab" href="#design" role="tab" aria-controls="design" aria-selected="true">FAQs</a>
						</li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade show active" id="design" role="tabpanel" aria-labelledby="design-tab">
							<div id="accordionsing" class="faq faq-two pixFade">
								<div class="card">
									<div class="card-header" id="heading10">
										<h5>
											<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse00" aria-expanded="false" aria-controls="collapse00">
												What is Logixsaas?
											</button>
										</h5>
									</div>
									<div id="collapse00" class="collapse" aria-labelledby="heading10" data-parent="#accordionsing">
										<div class="card-body">
											<p>
												Logixsaas is a cloud-based order fulfillment software, WMS, and ecommerce order management system that helps businesses manage and process orders from their customers. It includes features such as warehouse management, inventory management, process automation, SMS integration, ecommerce platform integrations, and API integrations.
											</p>
										</div>
									</div>
								</div>
								<div class="card active">
									<div class="card-header" id="heading20">
										<h5>
											<button class="btn btn-link" data-toggle="collapse" data-target="#collapse10" aria-expanded="true" aria-controls="collapse10">
												What are the key features of Logixsaas?
											</button>
										</h5>
									</div>
									<div id="collapse10" class="collapse show" aria-labelledby="heading20" data-parent="#accordionsing">
										<div class="card-body">
											<p>
												The key features of Logixsaas include warehouse management, inventory management, process automation, SMS integration, ecommerce platform integrations, API integrations, and a delivery app. It also includes reporting capabilities, allowing businesses to track and analyze their performance.


											</p>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header" id="heading30">
										<h5>
											<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse20" aria-expanded="false" aria-controls="collapse20">
												How does Logixsaas help with warehouse management?

											</button>
										</h5>
									</div>
									<div id="collapse20" class="collapse" aria-labelledby="heading30" data-parent="#accordionsing">
										<div class="card-body">
											<p>
												Logixsaas includes a warehouse management feature that helps businesses manage their warehouses and inventory. This feature includes features such as inventory tracking, order fulfillment, and shipping management. It also allows businesses to automate many of the manual tasks involved in warehouse management, saving time and reducing costs.


											</p>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header" id="heading40">
										<h5>
											<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse30" aria-expanded="false" aria-controls="collapse30">
												How does Logixsaas integrate with ecommerce platforms?

											</button>
										</h5>
									</div>
									<div id="collapse30" class="collapse" aria-labelledby="heading40" data-parent="#accordionsing">
										<div class="card-body">
											<p>
                                                Logixsaas can integrate with popular ecommerce platforms such as Shopify, Amazon, and Magento. This allows businesses to manage their orders and inventory across multiple platforms, providing a seamless experience for customers.

											</p>
										</div>
									</div>
								</div>
								<div class="card">
									<div class="card-header" id="heading42">
										<h5>
											<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse32" aria-expanded="false" aria-controls="collapse32">
												How does Logixsaas help with inventory management?

											</button>
										</h5>
									</div>
									<div id="collapse32" class="collapse" aria-labelledby="heading42" data-parent="#accordionsing">
										<div class="card-body">
											<p>
                                                Logixsaas includes an inventory management feature that helps businesses track and manage their inventory. This feature allows businesses to track stock levels, set reorder points, and manage stock transfers between warehouses. It also includes tools for forecasting and planning, helping businesses avoid stock-outs and excess inventory.

											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.tabs-wrapper -->
				<div class="button-container text-center mt-60">
					<a href="/pricing" class="pix-btn btn-outline">Get stated</a>
				</div>
				<!-- /.button-container -->


				<div class="faq-forms">
					<div class="section-title text-center">
						<h3 class="sub-title">Question form</h3>

						<h2 class="title">Do you have any Question?</h2>
					</div>
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
				</div>
				<!-- /.faq-forms -->
			</div>
			<!-- /.container -->
		</section>

		<!-- /.newsletter -->
    @include('admin.landing.inc.footer')  
</div><!-- /#site -->

@endsection
