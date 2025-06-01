@if (Request::path() == 'pricing')
    <div class="pricing-header pricing-amount" style="text-align: center">
        <div class="annual_price">
            <h5 class="price">$ {{ ($plan->amount * 12 * 98) / 100 }}</h5>
            <p>per year</p>
        </div>
        <div class="monthly_price">
            <h5 class="price">$ {{ $plan->amount }}</h5>
            <p>per month</p>
        </div>
        <h3 class="price-title">{{ $plan->plan }} Plan</h3>
    </div>
    <ul class="price-feture">
        <li class=""><i class="mdi mdi-check-circle"></i>Orders <b>{{ $plan->orders }}</b></li>
        <li class=""><i class="mdi mdi-check-circle"></i>Users <b>{{ $plan->users }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Client portal <b>{{ $plan->portals }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Warehouses <b>{{ $plan->warehouses }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Operating unit <b>{{ $plan->ou }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Shopify integrations
            <b>{{ $plan->shopify_integrations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Wordpress integrations
            <b>{{ $plan->wordpress_integrations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Api integrations
            <b>{{ $plan->api_integrations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Automations <b>{{ $plan->automations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Sms <b>{{ $plan->sms }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Email <b>{{ $plan->emails }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Shipping Lables <b>{{ $plan->lables }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Tracking <b>{{ $plan->tracking }}</b></li>
        <li><i class="mdi {{ $plan->inventory_management ? 'mdi-check-circle' : 'mdi-close-circle' }}"></i>
            Inventory management</li>
        <li><i class="mdi {{ $plan->warehouse_management ? 'mdi-check-circle' : 'mdi-close-circle' }}"></i>
            Warehouse management</li>
        <li><i class="mdi {{ $plan->packing_list ? 'mdi-check-circle' : 'mdi-close-circle' }}"></i>
            Packing List</li>
    </ul>
    <div class="action text-left"><a href="/accounts?plan={{ $plan->id }}" class="pix-btn btn-outline-two">
            @if ($plan->has_trial)
                Try Now
            @else
                Subscribe
            @endif
        </a>
    </div>
@elseif ($key < 4)
    <div class="pricing-header pricing-amount" style="text-align: center">
        <div class="annual_price">
            <h5 class="price">$ {{ ($plan->amount * 12 * 98) / 100 }}</h5>
            <p>per year</p>
        </div>
        <div class="monthly_price">
            <h5 class="price">$ {{ $plan->amount }}</h5>
            <p>per month</p>
        </div>
        <h3 class="price-title">{{ $plan->plan }} Plan</h3>
    </div>
    <ul class="price-feture">
        <li class=""><i class="mdi mdi-check-circle"></i>Orders <b>{{ $plan->orders }}</b></li>
        <li class=""><i class="mdi mdi-check-circle"></i>Users <b>{{ $plan->users }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Client portal <b>{{ $plan->portals }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Warehouses <b>{{ $plan->warehouses }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Operating unit <b>{{ $plan->ou }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Shopify integrations
            <b>{{ $plan->shopify_integrations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Wordpress integrations
            <b>{{ $plan->wordpress_integrations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Api integrations
            <b>{{ $plan->api_integrations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Automations <b>{{ $plan->automations }}</b>
        </li>
        <li> <i class="mdi mdi-check-circle"></i> Sms <b>{{ $plan->sms }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Email <b>{{ $plan->emails }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Shipping Lables <b>{{ $plan->lables }}</b></li>
        <li> <i class="mdi mdi-check-circle"></i> Tracking <b>{{ $plan->tracking }}</b></li>
        <li><i class="mdi {{ $plan->inventory_management ? 'mdi-check-circle' : 'mdi-close-circle' }}"></i>
            Inventory management</li>
        <li><i class="mdi {{ $plan->warehouse_management ? 'mdi-check-circle' : 'mdi-close-circle' }}"></i>
            Warehouse management</li>
        <li><i class="mdi {{ $plan->packing_list ? 'mdi-check-circle' : 'mdi-close-circle' }}"></i>
            Packing List</li>
    </ul>
    <div class="action text-left"><a href="/accounts?plan={{ $plan->id }}" class="pix-btn btn-outline-two">
            @if ($plan->has_trial)
                Try Now
            @else
                Subscribe
            @endif
        </a>
    </div>
@endif
