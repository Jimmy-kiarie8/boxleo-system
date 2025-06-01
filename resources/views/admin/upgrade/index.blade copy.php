@extends('admin.landingpage.layout')

@section('content')
<div id="admin">
    {{-- @include('admin.landingpage.pricing') --}}
    <section class="pricing-two">
        <div class="container">
            <div class="section-title color-two text-center">
                <h3 class="sub-title wow pixFadeUp">Pricing Plan</h3>
                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s">No Hidden Charges! Choose<br>your Plan.</h2>
            </div>
            <nav class="pricing-tab color-two wow pixFadeUp" data-wow-delay="0.4s"><span
                    class="monthly_tab_title tab-btn">Monthly </span><span class="pricing-tab-switcher"></span>
                <span class="annual_tab_title tab-btn">Annual</span></nav>
            <div class="row advanced-pricing-table">
                @foreach ($plans as $key => $plan)
                <div class="col-lg-4">
                    @if ($key == 0)
                    <div class="pricing-table style-two price-two wow pixFadeLeft" data-wow-delay="0.5s">
                        @elseif($key ==1)
                        <div class="pricing-table color-two style-two price-two featured wow pixFadeLeft"
                            data-wow-delay="0.7s">

                            <div class="trend">
                                <p>Popular</p>
                            </div>
                            @else
                            <div class="pricing-table color-three style-two price-two wow pixFadeLeft"
                                data-wow-delay="0.9s">
                                @endif
                                <div class="pricing-header pricing-amount" style="text-align: center">
                                    <div class="annual_price">
                                        <h2 class="price">$ {{ $plan->amount * 12 * 98/100 }}</h2>
                                        <p>per year</p>
                                    </div>
                                    <div class="monthly_price">
                                        <h2 class="price">$ {{ $plan->amount }}</h2>
                                        <p>per month</p>
                                    </div>
                                    <h3 class="price-title">{{ $plan->plan }} Plan</h3>
                                </div>
                                <ul class="price-feture">
                                    <li class=""><i class="mdi mdi-check-circle"></i>
                                        Users <b>{{ $plan->users }}</b></li>
                                    <li> <i class="mdi mdi-check-circle"></i> Client portal <b>{{ $plan->portals }}</b>
                                    </li>
                                    <li> <i class="mdi mdi-check-circle"></i> Warehouses <b>{{ $plan->warehouses }}</b>
                                    </li>
                                    <li> <i class="mdi mdi-check-circle"></i> countries <b>{{ $plan->countries }}</b>
                                    </li>
                                    <li> <i class="mdi mdi-check-circle"></i> Shopify integrations
                                        <b>{{ $plan->shopify_integrations }}</b></li>
                                    <li> <i class="mdi mdi-check-circle"></i> Wordpress integrations
                                        <b>{{ $plan->wordpress_integrations }}</b></li>
                                    <li> <i class="mdi mdi-check-circle"></i> Api integrations
                                        <b>{{ $plan->api_integrations }}</b></li>
                                    <li> <i class="mdi mdi-check-circle"></i> Automations
                                        <b>{{ $plan->automations }}</b>
                                    </li>
                                    <li> <i class="mdi mdi-check-circle"></i> Sms <b>{{ $plan->sms }}</b></li>
                                    <li> <i class="mdi mdi-check-circle"></i> Email <b>{{ $plan->email }}</b></li>
                                    <li> <i class="mdi mdi-check-circle"></i> Shipping Lables <b>{{ $plan->lables }}</b>
                                    </li>
                                    <li> <i class="mdi mdi-check-circle"></i> Tracking <b>{{ $plan->tracking }}</b></li>
                                    <li><i class="mdi mdi-close-circle"></i> Hotline Support 24/7</li>
                                    <li><i class="mdi mdi-close-circle"></i> No Updates</li>
                                </ul>
                                {{-- <div class="action text-left"><a href="/upgradeplan?plan={{ $plan->id }}?domain={{ $domain }}"
                                class="pix-btn btn-outline-two">Upgrade</a>
                            </div> --}}

                            <my-paypal :plan="{{ $plan }}" />

                        </div>
                    </div>
                    @endforeach
                </div>
    </section>
</div>
@endsection
