@extends('admin.landingpage.layout')


@section('title', 'Best Warehouse Management system | Stock Control System | WMS | Logixsaas')
@section('description',
    'We’re Building Powerfull end-to-end Order Fulfillment and warehouse management Software and Connecting The Global Supply
    Chain')
@section('og:title', 'Best Warehouse management system | Logixsaas')
@section('og:description',
    'We’re Building Powerfull end-to-end Order Fulfillment Software and Connecting The Global
    Supply Chain')
@section('twitter:title', 'Best Warehouse management system | Logixsaas')
@section('twitter:description',
    'We’re Building Powerfull end-to-end Order Fulfillment and warehouse management Software and Connecting The Global Supply
    Chain')
@section('url', 'warehouse-management-software')

@section('content')
    <a href="#main_content" data-type="section-switch" class="return-to-top"><i class="mdi mdi-chevron-up"></i></a>



    <div id="main_content">

        @include('admin.landing.inc.header')
        <section class="page-banner">
            <div class="container">
                <div class="page-title-wrapper">
                    <h1 class="page-title">Warehouse management system</h1>

                    <ul class="bradcurmed">
                        <li><a href="/" rel="">Home</a></li>
                        <li>WMS</li>
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
                                    warehouse management software
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                We provide scalable and configurable cloud-based warehouse management system that helps you
                                manage your orders and inventory easily on one platform which helps you provide better
                                customer service and control operational costs.

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
                                    Manage Multiple Warehouses Centrally</h3>
                                <h2 class="title wow pixFadeUp" data-wow-delay="0.3s"
                                    style="visibility: visible; animation-delay: 0.3s; animation-name: pixFadeUp;font-size: 25px;">
                                    What Is Warehouse Management?
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Warehouse management encompasses the principles and processes involved in running the
                                day-to-day operations of a warehouse. At a high level, this includes receiving and
                                organizing warehouse space, scheduling labor, managing inventory and fulfilling orders. We
                                provide an effective warehouse management system that optimizes and integrates each of your
                                processes to ensure all aspects of your warehouse operations work together to increase
                                productivity and keep costs low.
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
                                    Benefits of a Warehouse Management System<br>
                                </h2>
                            </div>

                            <p class="description wow pixFadeUp" data-wow-delay="0.4s"
                                style="visibility: visible; animation-delay: 0.4s; animation-name: pixFadeUp;">
                                Warehouse operations are generally invisible to customers, but they play a vital
                                behind-the-scenes role in ensuring on-time delivery. To achieve this goal, our warehouse
                                management system ensures all your warehouse's processes run as efficiently and accurately
                                as possible. Our warehouse management system optimizes the use of your warehouse space to
                                maximize inventory storage; making inventory easy for staff to find; ensuring adequate
                                staffing; efficiently fulfilling orders; and coordinating communication with suppliers and
                                transportation companies so materials arrive and orders ship on time.

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

                    <h2 class="title wow fadeInUp" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                        Why You Should Choose Our warehouse management System
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5" style="visibility: visible; animation-name: fadeInUp;">
                        Our Warehouse management system (WMS System) is a tool that helps you manage receiving and put-away
                        of inventory, picking, packing and shipping of orders. Get notification when a product restock level
                        is reached for timely inventory replenishment to help with the planning and management of available
                        resources and maximize productivity.

                    </p>
                </div><!-- /.section-title -->
                <div class="row">

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.5s"
                            style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInRight;">
                            <div class="saaspik-icon-box-icon">
                                <img src="{{ asset('home/logixsaas_inventory_4.png') }}" alt="">
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Receiving and putaway</a></h3>

                                <p>
                                    Putaway is a processes that happen between receiving goods from your vendors and having
                                    them all put away into their assigned warehouse space. Using Logixsaas warehouse
                                    management system simplifies the process of storing products, reduces the risk of
                                    misplacing or losing products, and keeps your warehouse clean and organized.

                                </p>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s"
                            style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
                            <div class="saaspik-icon-box-icon">
                                <img src="{{ asset('home/logixsaas_inventory_3.png') }}" alt="">
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Inventory management</a></h3>
                                <p>Wee empower merchants to manage their inventory with the right tools and guidance. It’s
                                    easy to view the status of inventory and quantity on hand across locations at any point
                                    in time. Our inventory management software helps you set reminders to proactively
                                    restore inventory with reorder notifications, make inventory transfer requests and more.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.3s"
                            style="visibility: visible; animation-delay: 0.3s; animation-name: fadeInRight;">
                            <div class="saaspik-icon-box-icon">
                                <img src="{{ asset('home/logixsaas_inventory_5.png') }}" alt="">
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Inventory tracking</a></h3>
                                <p>
                                    Use our advanced tracking and automatic identification and data capture(AIDC) systems,
                                    including mobile apps and barcode scanners to make sure that goods can be found easily
                                    when they need to picked, packed or transfered. Our inventory management software
                                    provides a dashboard that lets you track real-time inventory levels of each SKU for
                                    better inventory control across your stores. This way, you will know how much products
                                    are ready to be shipped, or if you need to send stock elsewhere.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s"
                            style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
                            <div class="saaspik-icon-box-icon">
                                <img src="{{ asset('home/logixsaas_inventory_2.png') }}" alt="">
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Picking and packing goods </a></h3>
                                <p>Picking is one of the most important steps of the order fulfillment process. With
                                    Logixsaas WMS, you can generate a picking list. Each picker must follow the picking list
                                    to pick the right items and optimize the route so they don’t waste time or take
                                    unnecessary steps. Once the SKUs have been picked for anorders, the orders can be handed
                                    off to a packer and the picker can get started on the next batch.
                                </p>
                            </div>
                        </div>
                    </div>




                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s"
                            style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
                            <div class="saaspik-icon-box-icon">
                                <img src="{{ asset('home/logixsaas_inventory_1.png') }}" alt="">
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Shipping</a></h3>
                                <p>Shipping is the process of dispatching your orders to their respective customers. We help
                                    you pack items to be shipped earlier to avoid cluttering in the staging areas which may
                                    lead to late deliveries and confusion. Once orders are in the agents hands, you can send
                                    order tracking URL to the customer for real-time tracking .</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-4 col-md-6">
                        <div class="saaspik-icon-box-wrapper style-eleven wow fadeInRight" data-wow-delay="0.7s"
                            style="visibility: visible; animation-delay: 0.7s; animation-name: fadeInRight;">
                            <div class="saaspik-icon-box-icon">
                                <img src="{{ asset('home/logixsaas_inventory_6.png') }}" alt="">
                            </div>
                            <div class="pixsass-icon-box-content">
                                <h3 class="pixsass-icon-box-title"><a href="#">Reporting and insights</a>
                                </h3>
                                <p>By using our warehouse management system, you will have insight into the entire order
                                    management process. You can track your inventory flow, including when inventory is being
                                    received, stowed or put away, picked, packed, shipped, transferred ,and any other
                                    movement. Logixsaas is incredibly an easy-to-use software which gives you insight into
                                    how your inventory and fulfillments are performing every day
                                </p>
                            </div>
                        </div>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section>

        @include('admin.landing.inc.footer')


    </div>
@endsection
