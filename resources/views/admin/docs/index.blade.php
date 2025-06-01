<!DOCTYPE html>
<html lang="en">

<head>
    <title>Logixsaas — Inventory Management | Online Inventory Software | Warehose management | Order fulfillment
    </title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Bootstrap 4 Template For Software Startups">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="shortcut icon" href="/favicons/favicon.ico" type="image/vnd.microsoft.icon">


    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- FontAwesome JS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    {{-- <script defer src="{{ asset('documentation/fontawesome/js/all.min.js') }}"></script> --}}

    <!-- Theme CSS -->
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.2/styles/atom-one-dark.min.css">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('documentation/css/theme.css') }}">

</head>

<body class="docs-page">
    <header class="header fixed-top">
        <div class="branding docs-branding">
            <div class="container-fluid position-relative py-2">
                <div class="docs-logo-wrapper">
                    <button id="docs-sidebar-toggler" class="docs-sidebar-toggler docs-sidebar-visible mr-2 d-xl-none"
                        type="button">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="site-logo"><a class="navbar-brand" href="/">
                            {{-- <img src="{{ asset('home/logo.png') }}" alt="site logo" class="main-logo" > --}}
                            <img class="logo-icon mr-2" style="width: 50px" src="{{ asset('home/logo.png') }}"
                                alt="Logixsaas"><span class="logo-text">Docs<span class="text-alt"></a></div>
                </div>
                <!--//docs-logo-wrapper-->
                <div class="docs-top-utilities d-flex justify-content-end align-items-center">
                    <div class="top-search-box d-none d-lg-flex">
                        <form class="search-form">
                            <input type="text" placeholder="Search the docs..." name="search"
                                class="form-control search-input">
                            <button type="submit" class="btn search-btn" value="Search"><i
                                    class="fa fa-search"></i></button>
                        </form>
                    </div>

                    <ul class="social-list list-inline mx-md-3 mx-lg-5 mb-0 d-none d-lg-flex">
                        <li class="list-inline-item"><a href="#" target="_blank"><i
                                    class="fa fa-facebook fa-fw"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-twitter fa-fw"
                                    target="_blank"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fa fa-instagram fa-fw"
                                    target="_blank"></i></a></li>
                    </ul>
                    <!--//social-list-->
                </div>
                <!--//docs-top-utilities-->
            </div>
            <!--//container-->
        </div>
        <!--//branding-->
    </header>
    <!--//header-->

    <div class="docs-wrapper" style="overflow: hidden;">
        <div id="docs-sidebar" class="docs-sidebar">
            <div class="top-search-box d-lg-none p-3">
                <form class="search-form">
                    <input type="text" placeholder="Search the docs..." name="search"
                        class="form-control search-input">
                    <button type="submit" class="btn search-btn" value="Search"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <nav id="docs-nav" class="docs-nav navbar">
                <ul class="section-items list-unstyled nav flex-column pb-3">
                    <li class="nav-item section-title"><a class="nav-link scrollto active" href="#section-1"><span
                                class="theme-icon-holder mr-2"><i class="fa fa-map-signs"></i></span>Introduction</a>
                    </li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-1-1">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-1-2">Users & Roles</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-1-3">Organization</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-1-4">Setting</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-1-5">Services</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-1-6">Automations</a></li>
                    <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-2"><span
                                class="theme-icon-holder mr-2"><i class="fa fa-arrow-down"></i></span>Processing</a>
                    </li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-2-1">Vendors</a></li>
                    {{-- <li class="nav-item"><a class="nav-link scrollto" href="#item-2-2">Sale Orders</a></li> --}}
                    {{-- <li class="nav-item"><a class="nav-link scrollto" href="#item-2-3">Section Item 2.3</a></li> --}}
                    <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-3"><span
                                class="theme-icon-holder mr-2"><i class="fa fa-shopping-cart"></i></span>Sale
                            Orders</a>
                    </li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-3-1">Create Sale Orders</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-3-2">Sale Order details</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-3-3">Order details</a></li>
                    <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-4">
                            <span class="theme-icon-holder mr-2"><i class="fa fa-cogs"></i></span>Order Dispatch</a>
                    </li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-4-1">Update Delivery & Returns</a>
                    </li>

                    {{-- <li class="nav-item"><a class="nav-link scrollto" href="#item-4-2">Section Item 4.2</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-4-3">Section Item 4.3</a></li>
                    <li class="nav-item section-title mt-3"><a class="nav-link scrollto" href="#section-5"><span
                                class="theme-icon-holder mr-2"><i class="fa fa-tools"></i></span>Utilities</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-5-1">Section Item 5.1</a></li>
                    <li class="nav-item"><a class="nav-link scrollto" href="#item-9-3">Section Item 9.3</a></li> --}}
                </ul>

            </nav>
            <!--//docs-nav-->
        </div>
        <!--//docs-sidebar-->
        <div class="docs-content">
            <div class="container">
                <article class="docs-article" id="section-1">
                    <header class="docs-header">
                        <h1 class="docs-heading">Introduction <span class="docs-time">Last updated: October 24 2022</span>
                        </h1>
                        <section class="docs-intro">
                            <p>Welcome to Logixsaas, an online cloud based logistic application which helps you manage
                                your organization’s order fulfillment with ease. This page will guide you through the
                                process of creating an account, setting up your organization and also get you acquainted
                                with the app.</p>
                        </section>
                        <!--//docs-intro-->

                        <h5>New Signup:</h5>
                        <p>If you’re new to Logixsaas, you’ll need to first sign up for our free 10-day trial period
                            during which you can test out our product and use all the features included in the
                            Professional plan of Logixsaas. You can upgrade to any of the paid plans during or after
                            the trial period.</p>


                        <h5>To sign up for Logixsaas:</h5>
                        <p>Go to <a class="theme-link" href="/" target="_blank">Website</a></p>
                        <p>Click the <b>Free Trial</b> button in the top right corner of the page.
                        </p>
                        <div class="docs-code-block">
                            <img src="{{ asset('documentation/images/signup.png') }}" alt="" style="height: 700px; width: 65vw;">
                        </div>

                        <p>Fill the form then click next</p>

                        <img src="{{ asset('documentation/images/signform.png') }}" alt=""
                            style="height: 600px;" />

                        <p>Choose a subdomain</p>
                        <img src="{{ asset('documentation/images/domain.png') }}" alt=""
                            style="height: 600px;" />

                        <!--//docs-code-block-->

                        <h3>Set up your organization details.</h3>
                        <p>When you sign up for Logixsaas, you’ll be redirected to a page. Click go to app and you
                            will be redirected to the organization details page where you’ll need to enter basic
                            information about your business.</p>

                    </header>


                    <section class="docs-section" id="item-1-1">
                        <h2 class="section-heading">Dashboard</h2>
                        <p>The Dashboard is the home page and it’s the first thing you’ll see when you log into your
                            Logixsaas account. It gives you a clear picture of your company’s sales and stock
                            summary i.e., the sales orders you have generated so far, top selling items, stock purchased
                            from your vendor for the selected period and more.</p>
                        <img src="{{ asset('home/dashboard.png') }}" alt=""
                            style="height: 500px;width: 65vw" />
                        <hr>
                        <img src="{{ asset('home/dashboard2.png') }}" alt=""
                            style="height: 500px;width: 65vw;" />
                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-1-2">
                        <h2 class="section-heading">Users & Roles</h2>
                        <p>Bring in your workforce, delegate tasks, analyze and ensure that your objectives are met.
                            Logixsaas being an online application, allows multiple users to access it from any
                            location.
                            <br>
                            You as the admin, can do the following operations with Users.
                        </p>


                        <h5>Predefined User Roles</h5>
                        Users can access the modules based on their role. The following predefined roles are currently
                        available:

                        <li><b>Super Admin</b>: has unrestricted access to all the modules.</li>
                        <p>As for other roles, you can create your own user roles by defining access levels &
                            permissions within Logixsaas.</p>
                        <img src="{{ asset('documentation/images/roles.png') }}" alt=""
                            style="height: 600px;width: 65vw" />

                        <h5>Creating User Roles</h5>
                        <p>To create a custom user role,</p>
                        <ul>
                            <li>Click on the User&Roles from the side menu.</li>
                            <li>Go to User Roles.</li>
                            <li>Then click Add role</li>
                            <li>Enter role name</li>
                            <li>Select permissions</li>
                            <li>Finaly save</li>
                        </ul>
                        <p>Assign permision to roles</p>
                        <img src="{{ asset('documentation/images/roles2.png') }}" alt=""
                            style="height: 350px;width: 65vw" />

                        <br>


                        <h5>Adding Users</h5>
                        <h6>To add new users:</h6>
                        <img src="{{ asset('documentation/images/user.png') }}" alt=""
                            style="height: 350px;width: 65vw" />
                        <p>To create a custom user,</p>
                        <ul>
                            <li>Click on the User&Roles from the side menu.</li>
                            <li>Go to User.</li>
                            <li>Then click New user</li>
                            <li>Enter user details</li>
                            <li>Select a role</li>
                            <li>Finaly save</li>
                        </ul>
                        <img src="{{ asset('documentation/images/user2.png') }}" alt=""
                            style="height: 350px;width: 65vw" />

                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-1-3">
                        <h2 class="section-heading">Company</h2>
                        <p>Under the company module, the Company profile module will allow you to maintain and update
                            your company information.</p>
                        <h5>Company Profile</h5>
                        <img src="{{ asset('documentation/images/company.png') }}" alt=""
                            style="height: 350px;width: 65vw" />
                        <div class="my-4">
                            <ul>
                                <li>Click on the Company the top side bar.</li>
                                <li>Then company.</li>
                                <li>To edit the company details, click on the pencil on the top right coner of the card.
                                </li>
                                <li>To edit the company logo, hover you cursor over the circular avatar, then click the
                                    image icon, then update the logo</li>
                            </ul>
                            <img src="{{ asset('documentation/images/company2.png') }}" alt=""
                                style="height: 350px;width: 65vw" />
                        </div>
                        <h5>APIs connection:</h5>
                        <p>You can connect you application to different apis</p>
                        <img src="{{ asset('documentation/images/apiconnection.png') }}" alt=""
                            style="height: 600px;width: 65vw" />
                        <div class="my-4">
                            <p>To connect:</p>
                            <ul>
                                <li>On the company card, click API connection.</li>
                                <li>You will see different APIs to connect to</li>
                            </ul>
                            <h5>Google Sheets</h5>
                            <p>You will need to generate a credential.json file ...</p>

                            <h5>Woocommerce</h5>
                            <p>You will need to generate a credential.json file ...</p>

                            <h5>Shopify</h5>
                            <p>You will need to generate a credential.json file ...</p>

                            <h4>SMS Api</h4>
                            <h5>Twilio</h5>
                            <p>You will need to generate a credential.json file ...</p>


                            <h5>Africas Talking</h5>
                            <p>You will need to generate a credential.json file ...</p>
                        </div>

                    </section>

                    <section class="docs-section" id="item-1-4">
                        <h2 class="section-heading">Setting</h2>
                        <p>The Settings page is where you customize Logixsaas to match your business requirements.
                            Here, you can add more information or update the existing information of your organization.
                            To access Settings, click the settings.</p>


                        <h5>Custom view:</h5>
                        <p>This is where you customize how you want you orders displayed.</p>
                        <img src="{{ asset('documentation/images/custom.png') }}" alt=""
                            style="height: 350px;width: 65vw" />

                        <h5>Create a custom view</h5>
                        <ul>
                            <li>Click Create Custom</li>
                            <li>You will be redirected to a new page</li>
                            <li>Enter a name for the view</li>
                            <li>Define the criteria. This is where you define the filters. (e.g if you only want someone
                                to only view delivered orders you can define the criteria as, <b>`When` `Delivery
                                    status` `is` `Delivered`</b>)</li>
                            <li>You can add more criterias.</li>
                            <li>Then drag and drop the columns you want displayed from <b>Available Columns</b> to
                                <b>Selected Columns</b>
                            </li>
                            <li>Then save</li>
                            <li>After you save, you can now select the filter from the order page</li>
                        </ul>
                        <img src="{{ asset('documentation/images/custom2.png') }}" alt=""
                            style="height: 350px;width: 65vw" />


                        <h5>Warehouse</h5>
                        <p>This is where you setup your warehouse</p>
                        <ul>
                            <li>Go to <b>Settings>Warehouse</b></li>
                            <li>You can view, Create or edit the warehouse</li>
                        </ul>
                        <img src="{{ asset('documentation/images/warehouse.png') }}" alt=""
                            style="height: 350px;width: 65vw" />
                        <br>
                        <img src="{{ asset('documentation/images/warehouse2.png') }}" alt=""
                            style="height: 350px;width: 65vw" />



                        <h5>Riders</h5>
                        <p>This is where you setup your riders</p>
                        <ul>
                            <li>Go to <b>Settings>Rider</b></li>
                            <li>You can view, Create or edit the Riders</li>
                        </ul>
                        {{-- <img src="{{ asset('documentation/images/rider.png') }}" alt="" style="height: 350px;width: 65vw" /> --}}
                        {{-- <img src="{{ asset('documentation/images/rider2.png') }}" alt="" style="height: 350px;width: 65vw" /> --}}


                        <h5>Status</h5>
                        <img src="{{ asset('documentation/images/status.png') }}" alt=""
                            style="height: 350px;width: 65vw" />
                        <p>This is where you setup your custom order status</p>
                        <ul>
                            <li>Go to <b>Settings>Rider</b></li>
                            <li>You can view, Create or edit the Status</li>
                        </ul>
                    </section>
                    <!--//section-->
                    <section class="docs-section" id="item-1-5">
                        <h2 class="section-heading">Services</h2>
                        <li>Go to <b>Services>Services</b> </li>
                        <p>This is where you define the services you offer, define the charges and define when the
                            charges should be applied.</p>
                        <h5>Zones</h5>

                        <li>Go to <b>Services>Zones</b> </li>

                        <p>This are the zones/cities where you do you fulfillment. You can define as many zones as you
                            want and make one to be the default zone.</p>


                    </section>
                    <!--//section-->
                    <section class="docs-section" id="item-1-6">
                        <h2 class="section-heading">Automations</h2>
                        <p>Automation allows you to create a set of rules for modules of Logixsaas based on which
                            appropriate actions would be performed. A good example would be the case where an e-mail/sms
                            is sent automatically to the customer when a Sales Order is scheduled.</p>

                        <h6>Setting up a new email template</h6>
                        <p>Go to <b>Automation>Email template</b></p>
                        <p>Click <b>Add mail template</b></p>
                        <p>Fill the form</b></p>
                        <p>Create a template from the Editor and save</b></p>

                        <img src="{{ asset('documentation/images/mail.png') }}" alt=""
                            style="height: 600px;width: 65vw" />

                        <h6>Setting up a new sms template</h6>
                        <p>Go to <b>Automation>SMS template</b></p>
                        <p>Click <b>Add mail template</b></p>
                        <p>Fill the form</b></p>
                        <p>Create a template from the textarea and save</b></p>
                        <img src="{{ asset('documentation/images/sms.png') }}" alt=""
                            style="height: 600px;width: 65vw" />



                        <h6>Automation</h6>
                        <p>Go to <b>Automation>Automation</b></p>

                        <ul>
                            <li>Click Create Automate</li>
                            <li>You will be redirected to a new page</li>
                            <li>Enter the name and choose the module</li>
                            <li>Choose when the action will be performed from the radio boxes</li>
                            <li>Define the criteria. This is where you define the filters. (e.g if you only want someone
                                to only view delivered orders you can define the criteria as, <b>`When` `Delivery
                                    status` `is` `Scheduled`</b>)</li>
                            <li>You can add more criterias.</li>
                            <li>Choose the action to be performed when the criterias are met</li>
                            <li>Then save</li>
                        </ul>
                        <img src="{{ asset('documentation/images/automation.png') }}" alt=""
                            style="height: 600px;width: 65vw" />
                    </section>
                    <!--//section-->

                </article>

                <article class="docs-article" id="section-2">
                    <header class="docs-header">
                        <h1 class="docs-heading">Processing</h1>
                        <section class="docs-intro">
                            <p></p>
                        </section>
                        <!--//docs-intro-->
                    </header>
                    <section class="docs-section" id="item-2-1">
                        <h2 class="section-heading">Vendors</h2>
                        <p>As a business or a freelancer, you’ll have a lot of customers to buy products or services
                            from you. As customers are important to any business, it is essential to save their contact
                            information to create transactions quickly and do business efficiently in the long term. The
                            Customers/Vendors module in Logixsaas is where you save the details of your customers
                            and vendors. You can save the details of your customers such as name, email address, phone
                            numbers, billing and shipping addresses, website information and other details in this
                            module. Apart from saving your customer’s information, you can send emails, view customer
                            statements, and create sales transactions for the customer in the contact details page
                            itself. In this document, we’ll learn more about how to make the most of this module.</p>

                        <img src="{{ asset('documentation/images/vendor.png') }}" alt=""
                            style="height: 600px;width: 65vw" />
                        <h5>Creating a Vendor</h5>
                        <p>There are multiple ways in which you can add new vendors in Logixsaas:</p>

                        <h6>1. Create a single Vendor from the Vendors Module</h6>
                        <ul>
                            <li>Click <b>Vendors</b> on the side bar</li>
                            <li>Click <b>New Vendor</b></li>
                            <li>Fill the vendor details</li>
                            <li>You can select the services that you offer that vendor and save</li>
                        </ul>
                        <img src="{{ asset('documentation/images/vendor2.png') }}" alt=""
                            style="height: 600px;width: 65vw" />

                        <h6>2. Upload Vendors from the Vendors Module with an excel file</h6>
                        <ul>
                            <li>Click <b>Vendors</b> on the side bar</li>
                            <li>Click <b>Upload button</b></li>
                            <li>Click the download icon ( <i class="fa fa-download"></i> ) to download the excel file
                            </li>
                            <li>Enter the vendors details on the excel file and upload it</li>
                        </ul>
                    </section>
                    <!--//section-->

                    {{-- <section class="docs-section" id="item-2-2">
                        <h2 class="section-heading">Sale Orders</h2>
                        <p>You can create from this options</p>

                        <img src="{{ asset('documentation/images/orders.png') }}" alt="" style="height: 200px;" /> 

                        <h6>1. Create one order</h6>
                        <ul>
                            <li>Click +New Order</li>
                            <li>You will be redirected to a new page</li>
                            <li>Enter order details and save</li>
                        </ul>

                        <h6>2. Upload orders using a csv/xlsx file</h6>
                        <ul>
                            <li>Click Import orders from Excel</li>
                            <li>Download the template from the download icon  (<i class="fa fa-download"></i> ) </li>
                            <li>Select a vendor and warehouse and click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>


                        <h6>3. Upload orders from Googlesheets</h6>
                        <ul>
                            <li>Click Import orders from Googlesheets</li>
                            <li>Select a vendor, warehouse and the dates between which you want to import orders</li>
                            <li>Enter the sheet name and then worksheet and click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>

                        <h6>4. Import orders from a connected Woocommerce site</h6>
                        <ul>
                            <li>Click Import orders from Woocommerce</li>
                            <li>Select a vendor, warehouse and the dates between which you want to import orders and click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>
                        <h6>5. Import orders from a connected Shopify site</h6>
                        <ul>
                            <li>Click Import orders from Shopify</li>
                            <li>Select a vendor, warehouse and the dates between which you want to import orders and click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>
                        <img src="{{ asset('home/orders.png') }}" alt="" style="height: 600px;width: 65vw" /> 
                    </section> --}}
                    <!--//section-->

                    {{-- <section class="docs-section" id="item-2-3">
                        <h2 class="section-heading">Section Item 2.3</h2>
                        <p>Vivamus efficitur fringilla ullamcorper. Cras condimentum condimentum mauris, vitae facilisis
                            leo. Aliquam sagittis purus nisi, at commodo augue convallis id. Sed interdum turpis quis
                            felis bibendum imperdiet. Mauris pellentesque urna eu leo gravida iaculis. In fringilla odio
                            in felis ultricies porttitor. Donec at purus libero. Vestibulum libero orci, commodo nec
                            arcu sit amet, commodo sollicitudin est. Vestibulum ultricies malesuada tempor.</p>
                    </section> --}}
                    <!--//section-->
                </article>
                <!--//docs-article-->


                <article class="docs-article" id="section-3">
                    <header class="docs-header">
                        <h1 class="docs-heading">Sale Orders</h1>
                        <section class="docs-intro">
                            <p>Section intro goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                finibus condimentum nisl id vulputate. Praesent aliquet varius eros interdum suscipit.
                                Donec eu purus sed nibh convallis bibendum quis vitae turpis. Duis vestibulum diam
                                lorem, vitae dapibus nibh facilisis a. Fusce in malesuada odio.</p>
                        </section>
                        <!--//docs-intro-->
                    </header>
                    <section class="docs-section" id="item-3-1">

                        <h2 class="section-heading">Sale Orders</h2>
                        <p>You can create from this options</p>

                        <img src="{{ asset('documentation/images/orders.png') }}" alt=""
                            style="height: 600px;" />

                        <h6>1. Create one order</h6>
                        <ul>
                            <li>Click +New Order</li>
                            <li>You will be redirected to a new page</li>
                            <li>Enter order details and save</li>
                        </ul>

                        <h6>2. Upload orders using a csv/xlsx file</h6>
                        <ul>
                            <li>Click Import orders from Excel</li>
                            <li>Download the template from the download icon (<i class="fa fa-download"></i> ) </li>
                            <li>Select a vendor and warehouse and click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>


                        <h6>3. Upload orders from Googlesheets</h6>
                        <ul>
                            <li>Click Import orders from Googlesheets</li>
                            <li>Select a vendor, warehouse and the dates between which you want to import orders</li>
                            <li>Enter the sheet name and then worksheet and click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>

                        <h6>4. Import orders from a connected Woocommerce site</h6>
                        <ul>
                            <li>Click Import orders from Woocommerce</li>
                            <li>Select a vendor, warehouse and the dates between which you want to import orders and
                                click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>
                        <h6>5. Import orders from a connected Shopify site</h6>
                        <ul>
                            <li>Click Import orders from Shopify</li>
                            <li>Select a vendor, warehouse and the dates between which you want to import orders and
                                click next</li>
                            <li>Confirm the order details and import</li>
                        </ul>
                        <img src="{{ asset('home/orders.png') }}" alt=""
                            style="height: 600px;width: 65vw" />
                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-3-2">
                        <h2 class="section-heading">Managing your Sales Orders</h2>
                        <p>Now that you know how to create sales orders, lets now focus on managing and getting the best
                            out of your sales orders.</p>

                        <h5>Status of a Sales Order</h5>
                        <p>A Sales Order status indicates the stage of progress and the current condition of the order.
                        </p>
                        <p>You can update the sale orders status by scrolling the sale order table to the right.You will
                            see the buttons which you can hover over and see what the button does</p>
                        <img src="{{ asset('documentation/images/orderupdate.png') }}" alt=""
                            style="height: 600px;width: 65vw" />

                        <ul>
                            <li> Click <i class="fa fa-pencil"></i> to edit the order </li>
                            <li> Click <i class="fa fa-file-upload"></i> to update order status </li>
                            <li> Click <i class="fa fa-eye"></i> to see order details </li>
                            <li> Click <i class="fa fa-print"></i> to download the waybill </li>
                            <li> Click <i class="fa fa-trash"></i> to delete the order </li>
                        </ul>

                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-3-3">
                        <h2 class="section-heading">Order details</h2>
                        <p>You can click the <i class="fa fa-eye"></i> icon to see the order details</p>
                        <p>You will see order update history, invoices and charges.</p>
                        <img src="{{ asset('documentation/images/orderdetails.png') }}" alt=""
                            style="height: 600px;width: 65vw" />
                    </section>
                    <!--//section-->
                </article>
                <!--//docs-article-->

                <article class="docs-article" id="section-4">
                    <header class="docs-header">
                        <h1 class="docs-heading">Order Dispatch</h1>
                        <p><b>Orders>Dispatch</b></p>
                        <section class="docs-intro">
                            <p>You can scan and dispatch orders from this module.</p>

                            <h6>How it works</h6>
                            <p>Choose: </p>
                            <p><b>Zone from:</b> where the percel is being dispatched from</p>
                            <p><b>Zone to:</b> where the percel is being sent to</p>
                            <p><b>Rider:</b> The rider/driver to deliver the percel</p>

                            <p>You can scan/enter order numbers one by one or input many order numbers separated by a
                                comma or a new line. Finaly click Dispatch button</p>
                            <img src="{{ asset('documentation/images/scan.png') }}" alt=""
                                style="height: 300px;" />
                        </section>
                        <!--//docs-intro-->
                    </header>
                    <section class="docs-section" id="item-4-1">
                        <h2 class="section-heading">Update Delivery & Returns</h2>

                        <p>You can scan and dispatch orders from this module.</p>

                        <h6>How it works</h6>
                        <p>Choose: </p>
                        <p><b>Status:</b> Choose delivered or Returned</p>

                        <p>You can scan/enter order numbers one by one or input many order numbers separated by a comma
                            or a new line. Finaly click update button</p>
                        <img src="{{ asset('documentation/images/scan2.png') }}" alt=""
                            style="height: 300px;" />
                    </section>
                    <!--//section-->

                    {{-- <section class="docs-section" id="item-4-2">
                        <h2 class="section-heading">Section Item 4.2</h2>
                        <p>Vivamus efficitur fringilla ullamcorper. Cras condimentum condimentum mauris, vitae facilisis
                            leo. Aliquam sagittis purus nisi, at commodo augue convallis id. Sed interdum turpis quis
                            felis bibendum imperdiet. Mauris pellentesque urna eu leo gravida iaculis. In fringilla odio
                            in felis ultricies porttitor. Donec at purus libero. Vestibulum libero orci, commodo nec
                            arcu sit amet, commodo sollicitudin est. Vestibulum ultricies malesuada tempor.</p>
                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-4-3">
                        <h2 class="section-heading">Section Item 4.3</h2>
                        <p>Vivamus efficitur fringilla ullamcorper. Cras condimentum condimentum mauris, vitae facilisis
                            leo. Aliquam sagittis purus nisi, at commodo augue convallis id. Sed interdum turpis quis
                            felis bibendum imperdiet. Mauris pellentesque urna eu leo gravida iaculis. In fringilla odio
                            in felis ultricies porttitor. Donec at purus libero. Vestibulum libero orci, commodo nec
                            arcu sit amet, commodo sollicitudin est. Vestibulum ultricies malesuada tempor.</p>
                    </section> --}}
                    <!--//section-->
                </article>
                <!--//docs-article-->

                {{-- <article class="docs-article" id="section-5">
                    <header class="docs-header">
                        <h1 class="docs-heading">Utilities</h1>
                        <section class="docs-intro">
                            <p>Section intro goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque
                                finibus condimentum nisl id vulputate. Praesent aliquet varius eros interdum suscipit.
                                Donec eu purus sed nibh convallis bibendum quis vitae turpis. Duis vestibulum diam
                                lorem, vitae dapibus nibh facilisis a. Fusce in malesuada odio.</p>
                        </section>
                        <!--//docs-intro-->
                    </header>
                    <section class="docs-section" id="item-5-1">
                        <h2 class="section-heading">Section Item 5.1</h2>
                        <p>Vivamus efficitur fringilla ullamcorper. Cras condimentum condimentum mauris, vitae facilisis
                            leo. Aliquam sagittis purus nisi, at commodo augue convallis id. Sed interdum turpis quis
                            felis bibendum imperdiet. Mauris pellentesque urna eu leo gravida iaculis. In fringilla odio
                            in felis ultricies porttitor. Donec at purus libero. Vestibulum libero orci, commodo nec
                            arcu sit amet, commodo sollicitudin est. Vestibulum ultricies malesuada tempor.</p>
                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-5-2">
                        <h2 class="section-heading">Section Item 5.2</h2>
                        <p>Vivamus efficitur fringilla ullamcorper. Cras condimentum condimentum mauris, vitae facilisis
                            leo. Aliquam sagittis purus nisi, at commodo augue convallis id. Sed interdum turpis quis
                            felis bibendum imperdiet. Mauris pellentesque urna eu leo gravida iaculis. In fringilla odio
                            in felis ultricies porttitor. Donec at purus libero. Vestibulum libero orci, commodo nec
                            arcu sit amet, commodo sollicitudin est. Vestibulum ultricies malesuada tempor.</p>
                    </section>
                    <!--//section-->

                    <section class="docs-section" id="item-5-3">
                        <h2 class="section-heading">Section Item 5.3</h2>
                        <p>Vivamus efficitur fringilla ullamcorper. Cras condimentum condimentum mauris, vitae facilisis
                            leo. Aliquam sagittis purus nisi, at commodo augue convallis id. Sed interdum turpis quis
                            felis bibendum imperdiet. Mauris pellentesque urna eu leo gravida iaculis. In fringilla odio
                            in felis ultricies porttitor. Donec at purus libero. Vestibulum libero orci, commodo nec
                            arcu sit amet, commodo sollicitudin est. Vestibulum ultricies malesuada tempor.</p>
                    </section>
                    <!--//section-->
                </article> --}}
                <!--//docs-article-->
                <footer class="footer">
                    <div class="container text-center py-5">
                        <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
                        {{-- <small class="copyright">Designed with <i class="fa fa-heart" style="color: #fb866a;"></i> by <a
                                class="theme-link" href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying
                                Riley</a> for developers</small> --}}

                        <small class="copyright">© 2019 - {{ Carbon\Carbon::now()->year }} All Rights Reserved 
                            <a href="/">Logixsaas</a>
                        </small>
                        <ul class="social-list list-unstyled pt-4 mb-0">
                            <li class="list-inline-item"><a href="#"><i class="fa fa-facebook fa-fw"
                                        target="_blank"></i></a></li>
                            <li class="list-inline-item"><a href="#"><i class="fa fa-twitter fa-fw"
                                        target="_blank"></i></a></li>
                            <li class="list-inline-item"><a href="#"><i class="fa fa-instagram fa-fw"
                                        target="_blank"></i></a></li>
                        </ul>
                        <!--//social-list-->
                    </div>
                </footer>
            </div>
        </div>
    </div>
    <!--//docs-wrapper-->



    <!-- Javascript -->
    <script src="{{ asset('documentation/plugins/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('"docs/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('documentation/plugins/bootstrap/js/bootstrap.min.js') }}"></script>


    <!-- Page Specific JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.8/highlight.min.js"></script>
    <script src="{{ asset('documentation/js/highlight-custom.js') }}"></script>
    <script src="{{ asset('documentation/plugins/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('documentation/plugins/lightbox/dist/ekko-lightbox.min.js') }}"></script>
    <script src="{{ asset('documentation/js/docs.js') }}"></script>

</body>

</html>
