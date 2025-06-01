<section class="pricing-two">
    <div class="container">
        <div class="section-title color-two text-center">
            <h3 class="sub-title wow pixFadeUp">Pricing Plan</h3>
            <h3 class="title wow pixFadeUp" data-wow-delay="0.3s">No Hidden Charges! Choose<br>your Plan.</h3>
        </div>
        <nav class="pricing-tab color-two wow pixFadeUp" data-wow-delay="0.4s"><span
                class="monthly_tab_title tab-btn">Monthly </span><span class="pricing-tab-switcher"></span>
            <span class="annual_tab_title tab-btn">Annual</span>
        </nav>
        <div class="row advanced-pricing-table">
            @foreach ($plans as $key => $plan)
                <div class="col-lg-3">
                    {{-- <div class="col-lg-{{ (int)(12/count($plans)) }}"> --}}
                    @if ($key == 0)
                        <div class="pricing-table style-two price-two wow pixFadeLeft color-one" data-wow-delay="0.5s">
                        @elseif($key ==1)
                            <div class="pricing-table color-four style-two price-two wow pixFadeLeft color-six"
                                data-wow-delay="0.9s">
                            @elseif($key ==2)
                                <div class="pricing-table color-three style-two price-two wow pixFadeLeft"
                                    data-wow-delay="0.9s">
                                @elseif($key ==3)
                                    <div class="pricing-table color-two style-two price-two featured wow pixFadeLeft"
                                        data-wow-delay="0.7s">

                                        <div class="trend">
                                            <p>Popular</p>
                                        </div>
                                    @elseif($key ==4)
                                        <div class="pricing-table color-five style-two price-two wow pixFadeLeft"
                                            data-wow-delay="0.9s">

                                            {{-- @else
                                        <div class="pricing-table color-one style-two price-two wow pixFadeLeft"
                                            data-wow-delay="0.9s"> --}}

                    @endif

                    @include('admin.landingpage.inc.pricing')
                </div>
        </div>

        @endforeach

        {{-- <div class="col-lg-4">
                <div class="pricing-table color-two style-two price-two featured wow pixFadeLeft"
                    data-wow-delay="0.7s">
                    <div class="trend">
                        <p>Popular</p>
                    </div>
                    <div class="pricing-header pricing-amount">
                        <div class="annual_price">
                            <h2 class="price">$80.50</h2>
                        </div>
                        <div class="monthly_price">
                            <h2 class="price">$16.97</h2>
                        </div>
                        <h3 class="price-title">Standard Account</h3>
                        <p>Only for first month</p>
                    </div>
                    <ul class="price-feture">
                        <li class="have">Limited Acess Library</li>
                        <li class="have">Single User</li>
                        <li class="have">eCommerce Store</li>
                        <li class="have">Hotline Support 24/7</li>
                        <li class="not">No Updates</li>
                    </ul>
                    <div class="action text-left"><a href="/app?plan=standard" class="pix-btn btn-outline-two">Try Now</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="pricing-table color-three style-two price-two wow pixFadeLeft"
                    data-wow-delay="0.9s">
                    <div class="pricing-header pricing-amount">
                        <div class="annual_price">
                            <h2 class="price">$180.70</h2>
                        </div>
                        <div class="monthly_price">
                            <h2 class="price">$29.45</h2>
                        </div>
                        <h3 class="price-title">Premium Account</h3>
                        <p>Only for first month</p>
                    </div>
                    <ul class="price-feture">
                        <li class="have">Limited Acess Library</li>
                        <li class="have">Single User</li>
                        <li class="have">eCommerce Store</li>
                        <li class="have">Hotline Support 24/7</li>
                        <li class="have">No Updates</li>
                    </ul>
                    <div class="action text-left"></div><a href="/app?plan=premium" class="pix-btn btn-outline-two">Try Now</a>
                </div>
                </div>
            </div> --}}
    </div>
</section>
