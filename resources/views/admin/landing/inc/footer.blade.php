
    <footer id="footer" class="footer-five">
        <div class="container">
            <div class="footer-inner wow pixFadeUp">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="widget footer-widget">
                            <h3 class="widget-title">About us</h3>
                            <ul class="footer-menu">
                                <li><a href="/pricing">Features</a></li>
                                <li><a href="/about">About Us</a></li>
                                <li><a href="/contact">Get In Touch</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="widget footer-widget">
                            <h3 class="widget-title">Products</h3>
                            <ul class="footer-menu">
                                <li><a href="inventory-management-software">Inventory management</a></li>
                                <li><a href="warehouse-management-software">Warehouse management</a></li>
                                <li><a href="order-fulfillment-software">Order fulfillment</a></li>
                                <li><a href="customer-service-system">Contact center</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="widget footer-widget">
                            <h3 class="widget-title">Integrations</h3>
                            <ul class="footer-menu">
                                <li><a href="/shopify">Shopify</a></li>
                                <li><a href="woocommerce">Woocommerce</a></li>
                                <li><a href="/sms">Sms</a></li>
                                <li><a href="#">API</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="widget footer-widget">
                            <h3 class="widget-title">Our Contacts</h3>
                            {{-- <address>19 Belmont Ave Unit #122 Brooklyn,NY 11212</address> --}}

                            <ul class="footer-menu">
                                {{-- <li><a href="tel:+19176727279" style="color: #797687">+19176727179</a> </li> --}}
                                {{-- <li><a href="tel:+19176727279" style="color: #797687">+19176727279</a> </li> --}}
                                <li>
                                    <a href="mailto:support@logixsaas.com" style="color: #797687"> 
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                    Email us</a></li>
                            </ul>
                            <ul class="footer-social-link">
                                <li><a href="https://www.facebook.com/L-webdesigns-101013955983577" target="_blank"><i class="mdi mdi-facebook"></i></a></li>
                                <li><a href="#" target="_blank"><i class="mdi mdi-twitter"></i></a></li>
                                <li><a href="#" target="_blank"><i class="mdi mdi-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="site-info">
                <div class="copyright">
                    <p>© 2019 - {{ Carbon\Carbon::now()->year }} All Rights Reserved
                        {{-- Design by --}}
                        {{-- <a href="http://www.pixelsigns.art" target="_blank">PixelSigns</a> --}}
                    </p>
                </div>
                <ul class="site-info-menu">
                    <li><a href="/privacy">Privacy & Policy.</a></li>
                    {{-- <li><a href="#">Faq.</a></li> --}}
                    {{-- <li><a href="#">Terms.</a></li> --}}
                </ul>
            </div>
        </div>
    </footer>