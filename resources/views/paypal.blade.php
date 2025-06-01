@extends('layouts.home')

@section('content')

<div class="container mb-5 mt-5">

    {{-- {{ $subscriptions }} --}}



    {{-- <div class="pricing card-deck flex-column flex-md-row mb-3">
        @foreach ($subscriptions as $subscription)
        @if ($subscription->subscription == 'Professional')
        <div class="card card-pricing popular shadow text-center px-3 mb-4">

            @else
            <div class="card card-pricing text-center px-3 mb-4">

                @endif
                <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm">{{ $subscription->subscription }}</span>
    <div class="bg-transparent card-header pt-4 border-0">
        <h1 class="h1 font-weight-normal text-primary text-center mb-0" data-pricing-value="15">$<span
                class="price">{{ $subscription->subscription_amount }}</span><span class="h6 text-muted ml-2">/ per
                month</span></h1>
    </div>
    <div class="card-body pt-0">
        <div>{!! $subscription->features !!}</div>
        <button type="button" class="btn btn-outline-secondary mb-3">Subscribe now</button>
    </div>
</div>

@endforeach

</div>
</div>
<div class="text-muted mt-5 mb-5 text-center small">by : <a class="text-muted" target="_blank"
        href="http://totoprayogo.com">totoprayogo.com</a></div> --}}

<section class="pricing py-5">
    <div class="container">
        <div class="row">
            <!-- Free Tier -->
            <div class="col-lg-4">
                <div class="card mb-5 mb-lg-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">Free</h5>
                        <h6 class="card-price text-center">$0<span class="period">/month</span></h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Single User</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>5GB Storage</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Public Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Community Access</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Unlimited
                                Private Projects</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Dedicated
                                Phone Support</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Free Subdomain
                            </li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Monthly Status
                                Reports</li>
                        </ul>
                        <a href="#" class="btn btn-block btn-primary text-uppercase">Button</a>
                    </div>
                </div>
            </div>
            <!-- Plus Tier -->
            <div class="col-lg-4">
                <div class="card mb-5 mb-lg-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">Plus</h5>
                        <h6 class="card-price text-center">$9<span class="period">/month</span></h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>5 Users</strong></li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>50GB Storage</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Public Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Community Access</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Private Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Dedicated Phone Support</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Free Subdomain</li>
                            <li class="text-muted"><span class="fa-li"><i class="fas fa-times"></i></span>Monthly Status
                                Reports</li>
                        </ul>
                        <a href="#" class="btn btn-block btn-primary text-uppercase">Button</a>
                    </div>
                </div>
            </div>
            <!-- Pro Tier -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-muted text-uppercase text-center">Pro</h5>
                        <h6 class="card-price text-center">$49<span class="period">/month</span></h6>
                        <hr>
                        <ul class="fa-ul">
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>Unlimited Users</strong>
                            </li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>150GB Storage</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Public Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Community Access</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Unlimited Private Projects</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Dedicated Phone Support</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><strong>Unlimited</strong> Free
                                Subdomains</li>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span>Monthly Status Reports</li>
                        </ul>
                        <a href="#" class="btn btn-block btn-primary text-uppercase">Button</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
