@extends('admin.landingpage.layout')


@section('title', 'Customer Engagement Platform enables inbound &amp; outbound calls through a secure, cloud-based | Logixsaas')
@section('description',
    'We build a powerfull end-to-end call center software that connects your business to yoyr customers.')
@section('og:title', 'About Us | Logixsaas')
@section('og:description',
    'We build a powerfull end-to-end call center software that connects your business to yoyr customers.')
@section('twitter:title', 'About Us | Logixsaas')
@section('twitter:description',
    'We build a powerfull end-to-end call center software that connects your business to yoyr customers.')
@section('url', 'customer-service-system')

@section('content')   
 {{-- <div class="page-loader"> --}}
   

    <div id="home-version-10" class="home-version-10" data-style="default"><a href="#main_content"
        data-type="section-switch" class="return-to-top"><i class="fa fa-chevron-up"></i></a>
   
    <div id="main_content">
        @include('admin.landing.inc.header')
        
        <section class="banner banner-ten" data-bg-image="{{ asset('/images/call/hosting-overlay.png') }}">
            <div class="container h-100">
                <div class="row align-items-center h-100">
                    <div class="col-lg-6">
                        <div class="banner-content-wrapper-ten">
                            <h1 class="banner-title wow fadeInUp" data-wow-delay="0.3s">The Best Customer Service Software
                                <br>In East africa.</h1>
                            <p class="description wow fadeInUp" data-wow-delay="0.5s">Queue calls, no missed calls, lower wait times, and always available to support <br>Delight your customers with fast answers by using our advanced voice capabilities.</p>
                            <div class="button-container"><a href="#"
                                    class="banner-btn pix-btn btn-six btn-circle wow fadeInUp"
                                    data-wow-delay="0.7s">Learn More</a> <a href="#"
                                    class="banner-btn pix-btn btn-outline btn-six btn-circle wow fadeInUp"
                                    data-wow-delay="0.7s">Get started</a></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="banner-six-promo-image text-right"><img src="{{ asset('/images/call/hosting.png') }}"
                                class="wow pixZoomIn" alt="saaspik"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="domain" data-bg-image="{{ asset('/images/call/domain.jpg') }}">
            <div class="container">
                <div class="domain-heading text-center">
                    <h3 class="sub-title wow fadeInUp"><span>Ksh3.4 per minute</span> Contact center</h3>
                    <h2 class="title wow fadeInUp" data-wow-delay="0.3s">Set up your contact center in just a <br>few hours Starting now</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s">Create an international contact center for your business that’s affordable and scalable. Handle any call scenario with our advanced inbound routing.</p>
                </div>
                
                <ul class="domain-price wow fadeInUp" data-wow-delay="0.9s">
                    <li class="chip">#EFFORTLESS PERFORMANCE</li>
                    <li class="chip">#SMART CONTACT CENTER</li>
                    <li class="chip">#REMOTE-READY</li>
                </ul>
            </div>
        </section>
        <section class="support-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="support-image ml--70"><img src="{{ asset('/images/call/support.png') }}" class="wow fadeInLeft"
                                alt="Support"></div>
                    </div>
                    <div class="col-md-7">
                        <div class="support-content">
                            <div class="section-title style-seven">
                                <h2 class="title wow fadeInUp" data-wow-delay="0.3s">24/7 Online Customer Support</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.5s">Empower remote support and sales teams to collaborate in real-time and resolve complex issues. Virtual leaders can monitor call volumes and service attainment while keeping track of meaningful conversations.</p>
                                <ul class="list-items color-eight wow pixFadeUp" data-wow-delay="0.6s">
                                    <li>Quick Access</li>
                                    <li>Easily Manage</li>
                                    <li>7/24h Support</li>
                                </ul><a href="#" class="pix-btn btn-circle btn-six wow fadeInUp"
                                    data-wow-delay="0.7s">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-120 align-items-center">
                    <div class="col-md-7">
                        <div class="support-content pr-90">
                            <div class="section-title style-seven">
                                <h2 class="title wow fadeInUp" data-wow-delay="0.3s">Be Smart. Stay Smart. Back It Up.
                                </h2>
                                <p class="wow fadeInUp" data-wow-delay="0.5s">Learn everything that happened in a call by using call recordings, post-call transcripts, and call-lifecycle information. Monitor agent performance and keep a pulse on your customer satisfaction with live dashboards.</p>
                                <p class="wow fadeInUp" data-wow-delay="0.6s">Get back to doing what you do best –
                                    <span>running your business</span> and making money. And speaking of money, here’s
                                    the best part – we offer free call storage of upto <span>10GB</span>.</p>
                                <h5 class="subtitle wow fadeInUp" data-wow-delay="0.7s">Resources on-demand</h5>
                                <p class="wow fadeInUp" data-wow-delay="0.8s">Our contact centers don’t only focus on phone calls, but also on many solutions like omni-channel support. This means you can manage all communication from one platform, creating a seamless customer experience.
                                </p><a
                                    href="#" class="pix-btn btn-circle btn-six mt-17 wow fadeInUp"
                                    data-wow-delay="0.9s">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="support-image"><img src="{{ asset('/images/call/support2.png') }}" class="wow fadeInRight"
                                alt="Support"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="feature-twelve">
            <div class="container">
                <div class="section-title style-seven text-center">
                    <h2 class="title wow fadeInUp" data-wow-delay="0.2s">Powerful Contact Center</h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5">Improve NPS, reduce cost, and increase efficiency with our cloud-based call center system <br> that meet your customer and employee needs.</p>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-twelve wow fadeInRight" data-wow-delay="0.3s">
                            <div class="saaspik-icon-box-icon">
                                <i class="fa-solid fa-check-to-slot" style="color: #37eb69;"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Meaningful conversations.</a></h3>
                                <p>While on call with the customer, you could look at their conversation history, or their ticket history with your call center. You can add notes and associate calls with new or existing contacts, with just a one click.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-twelve wow fadeInRight" data-wow-delay="0.5s">
                            <div class="saaspik-icon-box-icon color--two"><i class="fa-solid fa-user-check" style="color: #b01dfd"></i></div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Smart automations.</a></h3>
                                <p>Enhance your customer service by intelligently routing calls to the agents most qualified for each enquiry. Handle large volumes of transactions, create self-service IVR and automatically log all interactions within our dashboard for a seamless customer experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-twelve wow fadeInRight" data-wow-delay="0.7s">
                            <div class="saaspik-icon-box-icon color--three">
                                <i class="fa-sharp fa-solid fa-phone-volume" style="color: #b01dfd"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Interactive voice response (IVR).</a></h3>
                                <p>Use our IVRs to route calls based on the choices made by the caller. Through these choices, you can determine if the caller wants to contact the billing department, the technical support team, or simply wants to talk to a human operator.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-twelve wow fadeInRight" data-wow-delay="0.7s">
                            <div class="saaspik-icon-box-icon color--four">
                                <i class="fa-solid fa-microphone" style="color: #f83b63;"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Recording and monitoring.</a></h3>
                                <p>Call quality monitoring is more than just recording and monitoring random calls. To measure call quality, you'll want to monitor and analyze strategic customer service calls and scrutinize their efficiency and effectiveness, with a constant goal of converting every caller into a satisfied customer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-twelve wow fadeInRight" data-wow-delay="0.7s">
                            <div class="saaspik-icon-box-icon color--five">
                                <i class="fa-sharp fa-solid fa-gears" style="color: #595dfd;"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Integrations.</a></h3>
                                <p>Integrate with our API which provides REST API for every event happening on the our platform such as calling, recording, transfering or reporting. Using these, you can integrate Call Center Studio to various other software. </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-twelve wow fadeInRight" data-wow-delay="0.7s">
                            <div class="saaspik-icon-box-icon color--six">
                                <i class="fa-solid fa-phone" style="color: #10ccc5"></i>
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Make the right call</a></h3>
                                <p>Call conversations with customers are more complicated than other modes of communication because they require immediate response. There's no time to draft a reply. Our cloud based call center software can help you out. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- <section class="pricing-hosting">
            <div class="container">
                <div class="section-title text-center">
                    <h2 class="title">Choose The Best Plan For Your Business</h2>
                    <p>Keep relevant and timely information at-hand with all-in-one user-friendly interface! <br /> Tailor your customer service.</p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pricing-table style-three wow pixFadeLeft" data-wow-delay="0.5s">
                            <div class="pricing-header pricing-amount">
                                <h3 class="price-title">Economy Pack</h3>
                                <h2 class="price">$ 10<span> Agent//Month</span></h2>
                                <p>Create an impact for your clientele by providing the best.</p>
                            </div>
                            <ul class="price-feture">
                                <li>Local phone number</li>
                                <li>Save to contact</li>
                                <li>VOIP Support</li>
                                <li>Call comments</li>
                                <li>Call queue</li>
                                <li>Call transfer</li>
                                <li>Call hold</li>
                                <li>Call Monitoring</li>
                                <li>Routing Automation</li>
                            </ul>
                            <div class="action text-left"><a href="#" class="pix-btn btn-outline-two">Contact sales</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="pricing-table style-three wow pixFadeLeft" data-wow-delay="0.7s">
                            <div class="pricing-header pricing-amount">
                                <h3 class="price-title">Business Pack</h3>
                                <h2 class="price">$ 15<span> Agent//Month</span></h2>
                                <p>Create an impact for your clientele by providing the best.</p>
                            </div>
                            <ul class="price-feture">
                                <li>Local phone number</li>
                                <li>VOIP Support</li>
                                <li>Call comments</li>
                                <li>Call queue</li>
                                <li>Free call recording(Upto 10GB)</li>
                                <li>Call transfer</li>
                                <li>Call hold</li>
                                <li>Call Monitoring</li>
                                <li>Routing Automation</li>
                                <li>SMS integration</li>
                                <li>API integration</li>
                                <li>Webhooks</li>
                            </ul>
                            <div class="action text-left"><a href="#" class="pix-btn btn-outline-two">Contact sales</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="pricing-table style-three wow pixFadeLeft" data-wow-delay="0.9s">
                            <div class="pricing-header pricing-amount">
                                <h3 class="price-title">Executive Pack</h3>
                                <h2 class="price">$ 20<span> Agent//Month</span></h2>
                                <p>Create an impact for your clientele by providing the best.</p>
                            </div>
                            <ul class="price-feture">
                                <li>Local phone number</li>
                                <li>VOIP Support</li>
                                <li>Call comments</li>
                                <li>Call queue</li>
                                <li>Free call recording(Upto 17GB)</li>
                                <li>Call recording</li>
                                <li>Call transfer</li>
                                <li>Call hold</li>
                                <li>Call Monitoring</li>
                                <li>Routing Automation</li>
                                <li>Routing Automation</li>
                                <li>SMS integration</li>
                                <li>API integration</li>
                                <li>Webhooks</li>
                            </ul>
                            <div class="action text-left"><a href="#" class="pix-btn btn-outline-two">Contact sales</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
        <section class="call-to-action-hosting">
            <div class="container">
                <div class="action-content style-hosting text-center">
                    <div class="section-title style-seven color-light">
                        <h2 class="title wow fadeInUp">We don’t compromise with the<br>best Hosting Solution</h2>
                    </div>
                </div>
                <div class="action-button-container text-center"><a href="#"
                        class="pix-btn btn-light btn-six btn-circle wow fadeInUp" data-wow-delay="0.3s">Get Started</a>
                    <a href="#" class="pix-btn btn-light btn-six btn-outline btn-circle wow fadeInUp"
                        data-wow-delay="0.3s">Know More</a></div>
            </div>
        </section>

        @include('admin.landing.inc.footer')

    </div>
</div>

@endsection
