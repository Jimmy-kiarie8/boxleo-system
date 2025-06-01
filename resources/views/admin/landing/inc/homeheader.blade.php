<div class="page-loader">
    <div class="loader">
        <div class="blobs">
            <div class="blob-center"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
        </div><svg xmlns="{{ asset('/home/loader.svg') }}" version="1.1">
            <defs>
                <filter id="goo">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                    <feColorMatrix in="blur" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo" />
                    <feBlend in="SourceGraphic" in2="goo" />
                </filter>
            </defs>
        </svg>
    </div>
</div>


<header class="site-header header-dark header_trans-fixed pix-header-fixed" data-top="992"
   >
    {{-- <header class="site-header header-two header_trans-fixed" data-top="992"> --}}
    <div class="container" id="{{ Request::is('/') ? 'home_page' : '' }}">
        <div class="header-inner">
            <div class="site-mobile-logo"><a href="/" class="logo">
                    {{-- <img src="{{ asset('home/logo.png') }}" alt="site logo" class="main-logo" style="width: 120px;"> --}}
                    <img src="{{ asset('home/logo.png') }}" alt="site logo" class="sticky-logo" style="width: 120px">
                </a>
            </div>
            <div class="toggle-menu"><span class="bar"></span> <span class="bar"></span> <span class="bar"></span>
            </div>
            <nav class="site-nav nav-two">
                <div class="close-menu"><i class="mdi mdi-close"></i></div>
                <div class="site-logo"><a href="/" class="logo">
                    <img src="{{ asset('home/logo.jpg') }}" alt="site logo" class="main-logo"  style="width: 150px; border-radius: 20px;"> 
                    <img src="{{ asset('home/logo.png') }}" alt="Logixsaas" class="sticky-logo" style="width: 150px;"></a></div>
                <div class="menu-wrapper" data-top="992">
                    <ul class="site-main-menu">
                        <li><a href="/" class="{{ Request::is('/') ? 'current_page' : '' }}">Home</a></li>
                        <li><a href="about" class="{{ Request::is('about') ? 'current_page' : '' }}">About</a></li>
                        <li><a href="pricing" class="{{ Request::is('pricing') ? 'current_page' : '' }}">Pricing</a>
                        </li>
                        <li><a href="contact" class="{{ Request::is('contact') ? 'current_page' : '' }}">Contact</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Integrations</a>
                            <ul class="sub-menu">
                                <li><a href="/shopify">Shopify</a></li>
                                <li><a href="/woocommerce">Woocommerce</a></li>
                                <li><a href="/sms">SMS</a></li>
                                <li><a href="#">Googlesheets</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Solutions</a>
                            <ul class="sub-menu">
                                <li><a href="/inventory-management-software">Inventory management</a></li>
                                <li><a href="order-fulfillment-software">Order Fulfillment</a></li>
                                <li><a href="warehouse-management-software">Warehouse management</a></li>
                                <li><a href="delivery-app">Delivery app</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Resources</a>
                            <ul class="sub-menu">
                                <li><a href="/faqs">FAQs</a></li>
                                <li><a href="/documentations" target="_blank">Documentation</a></li>
                                <li><a href="#">Support</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="nav-right"><a href="/accounts?plan=1" class="nav-btn">Free Trial</a></div>
                </div>
            </nav>
        </div>
    </div>
</header>
