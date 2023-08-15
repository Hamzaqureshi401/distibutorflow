@include('layouts.common_header')
      <!-- Google font-->
      <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
      <!-- Required Fremwork -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
      <!-- themify-icons line icon -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/font-awesome/css/font-awesome.min.css') }}">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css') }}" />
      <!-- ico font -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
      <!-- Style.css -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.mCustomScrollbar.css') }}">
      <style>
         .header {
         width: 100%;
         height: 80px;
         border-bottom: 1px solid black;
         display: flex;
         align-items: center;
         }
         .logo {
         margin-left: 40px;
         height: 100%;
         width: 122px;
         display: flex;
         justify-content: center;
         align-items: center;
         }
         .oval-container {
         position: relative;
         width: 100%;
         height: 150px;
         overflow: visible;
         }
         .oval {
         position: absolute;
         top: 0;
         left: 0;
         width: 200%;
         height: 100%;
         border: 0.1px solid black;
         border-radius: 60%;
         overflow: visible;
         }
         .planet-container {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         }
         .planet {
         position: absolute;
         top: 50%;
         left: 100%;
         transform-origin: top left;
         width: 10px;
         height: 10px;
         background-color: #a5a5a5;
         border-radius: 50%;
         }
         .planet-left {
         position: absolute;
         top: 50%;
         left: 100%;
         transform-origin: top left;
         width: 12px;
         height: 12px;
         background-color: #a5a5a5;
         border-radius: 50%;
         }
         .rectangle-container {
   /* Add the desired background image */
  background-image: url("{{ asset('gif/distributor-animation-image_1.gif') }}");
   
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   
   width: 100%;
   height: 400px;
   min-height: 200px; /* Adjust this value as desired */
   display: flex;
   justify-content: center;
   align-items: center;
}

@media (max-width: 768px) {
  .rectangle-container {
    height: auto;
  }
}

 .route-img{
   /* Add the desired background image */
   background-image: url("{{ asset('gif/bike-location.gif') }}");
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   position: relative;
   width: 100%;
   height: 400px;
   display: flex;
   justify-content: center;
   align-items: center;
}

.android-app-img{
   /* Add the desired background image */
   background-image: url("{{ asset('gif/andrid-anim.gif') }}");
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   position: relative;
   width: 100%;
   height: 400px;
   display: flex;
   justify-content: center;
   align-items: center;
}
 .record-img{
   /* Add the desired background image */
   background-image: url("{{ asset('gif/record-anim.gif') }}");
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   position: relative;
   width: 100%;
   height: 400px;
   display: flex;
   justify-content: center;
   align-items: center;
}
.pos-img{
   /* Add the desired background image */
   background-image: url("{{ asset('gif/pos.gif') }}");
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   position: relative;
   width: 100%;
   height: 400px;
   display: flex;
   justify-content: center;
   align-items: center;
}
.D-img{
   /* Add the desired background image */
   background-image: url("{{ asset('gif/Only D logo.png') }}");
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   position: relative;
   width: 100%;
   height: 100px;
   display: flex;
   justify-content: center;
   align-items: center;
}
.text-img{
   /* Add the desired background image */
   background-image: url("{{ asset('gif/logo text.png') }}");
   background-size: contain;
   background-position: center;
   background-repeat: no-repeat;
   position: relative;
   width: 100%;
   height: 100px;
   display: flex;
   justify-content: center;
   align-items: center;
}


         /*.rectangle-planet-container {
         position: absolute;
         top: 50%;*/
/*         left: -5px; */
         /* Adjust the initial position of the planet within the container */
         /*transform: translate(-50%, -50%);
         }
         .rectangle-planet {
         position: absolute;
         top: 0;
         left: 0;
         transform-origin: top left;
         width: 10px;
         height: 10px;
         background-color: red;
         border-radius: 50%;
         }*/
         h1 {
         font-weight: normal;
         }
         .container-fluid {
         padding-right: 0;
         padding-left: 0;
         }
         .responsive-text {
         font-size: 26px;
         line-height: 34px;
/*         max-width: 455px;*/
         /*   height: 357px;*/
         }
         .brd {
         border: 1px solid #cdc4c4;
         /* Additional border styling properties can be added here */
         }
         .border-right {
         border-right: 1px solid #cdc4c4;
         /* Additional border styling properties can be added here */
         }
         
         @media (max-width: 992px) {
         .responsive-text {
         font-size: 20px;
         line-height: 28px;
         max-width: 100%;
         padding: 0 20px;
         border-right: none; /* Remove border-right */
         /* Add border-bottom */
         }
         .border-right {
         border-right: none;
         border-bottom: 1px solid #333333;
         /* Additional border styling properties can be added here */
         }
         /*.margin-left-right{
         margin-left: 50px; 
         margin-right: 50px;
         }*/
         }
      </style>
   </head>
   <body>
      <div class="fixed-button">
         <a href="https://codedthemes.com/item/gradient-able-admin-template" target="_blank" class="btn btn-md btn-primary">
         <i class="fa fa-shopping-cart" aria-hidden="true"></i> Need Help?
         </a>
      </div>
      <!-- Pre-loader start -->
      <div class="theme-loader">
         <div class="loader-track">
            <div class="loader-bar"></div>
         </div>
      </div>
      <!-- Pre-loader end -->
      <div id="pcoded" class="pcoded">
         <div class="pcoded-overlay-box"></div>
         <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header">
               <div class="navbar-wrapper">
                  <div class="navbar-logo">
                     <!-- <a class="mobile-menu" id="mobile-collapse" href="#!">
                     <i class="ti-menu"></i>
                     </a> -->
                     <!-- <div class="mobile-search">
                        <div class="header-search">
                           <div class="main-search morphsearch-search">
                              <div class="input-group">
                                 <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                                 <input type="text" class="form-control" placeholder="Enter Keyword">
                                 <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                              </div>
                           </div>
                        </div>
                     </div> -->
                     <a href="">
                     <img class="img-fluid" src="{{ asset('gif/distributor full white logo.png') }}" style="max-height: 40px;" alt="Theme-Logo" />
                     </a>
                     <a class="mobile-options cls" href='/login'>
                     <i class="ti-user"> Login</i>
                     </a>
                  </div>
                  <div class="navbar-container container-fluid">
                     <!-- <ul class="nav-left">
                        <li>
                           <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                        </li>
                        <li class="header-search">
                           <div class="main-search morphsearch-search">
                              <div class="input-group">
                                 <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                                 <input type="text" class="form-control">
                                 <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                              </div>
                           </div>
                        </li>
                        <li>
                           <a href="#!" onclick="javascript:toggleFullScreen()">
                           <i class="ti-fullscreen"></i>
                           </a>
                        </li>
                     </ul> -->
                     <ul class="nav-right">
                        <!-- <li class="header-notification">
                           <a href="#!">
                           <i class="ti-bell"></i>
                           <span class="badge bg-c-pink"></span>
                           </a>
                           <ul class="show-notification">
                              <li>
                                 <h6>Notifications</h6>
                                 <label class="label label-danger">New</label>
                              </li>
                              <li>
                                 <div class="media">
                                    <img class="d-flex align-self-center img-radius" src="assets/images/avatar-2.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                       <h5 class="notification-user">John Doe</h5>
                                       <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                       <span class="notification-time">30 minutes ago</span>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="media">
                                    <img class="d-flex align-self-center img-radius" src="assets/images/avatar-4.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                       <h5 class="notification-user">Joseph William</h5>
                                       <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                       <span class="notification-time">30 minutes ago</span>
                                    </div>
                                 </div>
                              </li>
                              <li>
                                 <div class="media">
                                    <img class="d-flex align-self-center img-radius" src="assets/images/avatar-3.jpg" alt="Generic placeholder image">
                                    <div class="media-body">
                                       <h5 class="notification-user">Sara Soudein</h5>
                                       <p class="notification-msg">Lorem ipsum dolor sit amet, consectetuer elit.</p>
                                       <span class="notification-time">30 minutes ago</span>
                                    </div>
                                 </div>
                              </li>
                           </ul>
                        </li> -->
                        <li class="user-profile">
                           <a class="btn btn-dark" href='/login'>
                     <i class="ti-user" > Login</i>
                     </a>
                           <!-- <a href="#!">
                           <img src="assets/images/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                           <span>John Doe</span>
                           <i class="ti-angle-down"></i>
                           </a> -->
                           <!-- <ul class="show-notification profile-notification">
                              <li>
                                 <a href="#!">
                                 <i class="ti-settings"></i> Settings
                                 </a>
                              </li>
                              <li>
                                 <a href="user-profile.html">
                                 <i class="ti-user"></i> Profile
                                 </a>
                              </li>
                              <li>
                                 <a href="auth-lock-screen.html">
                                 <i class="ti-lock"></i> Lock Screen
                                 </a>
                              </li>
                              <li>
                                 <a href="auth-normal-sign-in.html">
                                 <i class="ti-layout-sidebar-left"></i> Logout
                                 </a>
                              </li>
                           </ul> -->
                        </li>
                     </ul>
                  </div>
               </div>
            </nav>
            <div class="pcoded-main-container">
               <div class="pcoded-wrapper">
                  <div class="">
                     <div class="pcoded-inner-content">
                        <div class="">
                           <!-- card removed -->
                           <div class="">
                              <!-- card block removed -->
                              <div class="main-body">
                                 <div class="page-wrapper">
                                    <!-- Page-header start -->
                                    <div class="page-header card" style="z-index: 2; background: #f6f7fb; box-shadow:none">
                                       <div class="card-block">
                                          <div class="">
                                             <div class="text-center">
                                                <h1>Spread Your Business</h1>
                                                <h1><span style="display: block;">Over The World</span></h1>
                                                <p><span style="display: block;">Distributorflow can manage your customer distribution and employees</span> over the internet on anywhere.</p>

                                                <div class="button-container text-center">
                                                   <a href="http://demo.distributorflow.com" class="btn btn-dark" target="_blank">
                                                   <i class="ti-blackboard mr-2"></i> Go for Demo
                                                   </a>
                                                </div>
                                                <a style="color: blue;" href="/signUp">Sign up to Create Account?</a>
                                                <!-- <div class="button-container text-center">
                                                   <a href="http://demo.distributorflow.com" class="btn btn-primary" target="_blank">
                                                   <i class="ti-blackboard mr-2"></i> Create Account
                                                   </a>
                                                </div> -->
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- Page-header end -->
                                    <div class="page-body" style="z-index: 1;">
                                       <div class=" ">
                                          <!-- card removed -->
                                          <div class="">
                                             <!-- card block removed -->
                                             <div class="row">
                                                <!-- <div class="col-sm-12"> -->
                                                <!-- <div> -->
                                                   <div class="rectangle-container">
                                                            <div class="rectangle-planet-container">
                                                               <div class="rectangle-planet"></div>
                                                            </div>
                                                         </div>
                                                
                                                <div class="col-md-12 col-lg-6" style="z-index: 2;">
                                                   <div class="row">
                                                      <div class="col-12">
                                                         
                                                      </div>
                                                   </div>
                                                </div>
                                                
                                             <div class="row bg-dark margin-left-right">
                                                <div class="col-lg-6">
                                                   <div class="accordion-content accordion-desc mt-5">
                                                      <h2 style="color: white;">
                                                         The distribution system serves as the backbone of product delivery.
                                                      </h2>
                                                   </div>
                                                </div>
                                                <div class="col-lg-6">
                                                   <div class="accordion-content accordion-desc mt-5">
                                                      <p style="color: white; font-size: 18px;">
                                                         The distribution system plays a crucial role in the efficient and timely delivery of products from manufacturers to consumers. It involves a complex network of processes, infrastructure, and intermediaries that ensure products reach their intended destinations. In this article, we will delve into the intricacies of the distribution system, exploring its components and highlighting its significance in the modern economy.
                                                      </p>
                                                      <p class="mt-5" style="color: white; font-size: 20px">Why use a Distributor?</p>
                                                      <p  style="color: white; font-size: 18px">In today's fast-paced business landscape, the use of a distribution system is essential for organizations seeking to thrive and succeed. It enables efficient supply chain management, expands market reach, taps into expertise and resources, offers scalability and flexibility, and enhances customer service. By embracing a well-designed distribution system, businesses can streamline operations, improve profitability, and establish a competitive advantage in the marketplace.</p>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="row mt-5 text-center">
                                                <div class="col-lg-12">
                                                   <h5><span style="color: #4ca0ff;">Why Distributorflow?</span></h5>
                                                </div>
                                                <div class="col-lg-4 mt-5">
                                                </div>
                                                <div class="col-lg-4 mt-5">
                                                   <p class="responsive-text">Distributorflow empowers users to create, run, and access their own distribution system accounts. It provides robust tools for managing employees and customers within the distribution network. With its cutting-edge technology, Distributorflow ensures state-of-the-art security and efficiency in handling distribution operations.</p>
                                                </div>
                                             </div>
                                             <div class="col-lg-4 mt-5">
                                                </div>
                                                
                                             <div class="row text-center mt-5 margin-left-right">
                                                <div class="col-lg-4 brd" >
                                                    <div class="route-img">
                                                            
                                                         </div>
                                                   <h5 class="mt-5"><span style="color: #222; text-decoration: underline;">Route Management</span></h5>
                                                   <p class=" mt-5"><span >Our distributor utilizes the Google Maps API to display multiple shops on a map, allowing users to efficiently manage their routes. By leveraging this functionality, users can plan and optimize their journey, saving time and effort. The integration of Google Maps API enhances the distributor's service by providing a convenient and user-friendly solution for route management.</span></p>
                                                </div>
                                                <div class="col-lg-4 brd">
                                                    <div class="android-app-img">
                                                            
                                                         </div>
                                                   <h5 class="mt-5"><span style="color: #222; text-decoration: underline;">Android App Services</span></h5>
                                                   <p class=" mt-5"><span >Our distributor offers an Android app that is readily available for download on the Play Store. With the app, users can access our services conveniently from their mobile devices. It provides a seamless and user-friendly experience, enabling customers to easily place orders, track deliveries, and access other essential features on the go.</span></p>
                                                </div>
                                                <div class="col-lg-4 brd">
                                                    <div class="record-img">
                                                       
                                                    </div>
                                                   <h5 class="mt-5"><span style="color: #222; text-decoration: underline;">Record Management</span></h5>
                                                   <p class=" mt-5"><span >Distributorflow offers a comprehensive customer balance management system. With this feature, customers can easily track and manage their account balances, payments, and invoices. The system provides real-time updates and transparency, allowing customers to stay informed about their financial interactions with the distributor. Efficient balance management enhances customer satisfaction and promotes a smooth and reliable business relationship.</span></p>
                                                </div>
                                                <div class="col-lg-12">
                                           <div class="row">
                                               <div class="col-lg-4 offset-lg-4 brd">
                                                   <div class="pos-img">
                                                       <!-- Add image content here -->
                                                   </div>
                                                   <h5 class="mt-5"><span style="color: #222; text-decoration: underline;">We Offer Pos</span></h5>
                                                   <p class="mt-5"><span> DistributorFlow offers a powerful Point of Sale (POS) system with multi-vendor support. This feature enables distributors to manage multiple vendors within a single platform, facilitating seamless inventory management, streamlined order processing, and efficient tracking of sales. The POS system empowers distributors with real-time insights and comprehensive reports, enhancing their operational efficiency and providing a competitive edge in the market.</span></p>
                                               </div>
                                           </div>
                                       </div>

                                                   </div>
                                             <div class="row text-center mt-5 margin-left-right">
                                                <div class="col-lg-12">
                                                   <h1 class=" mt-5"><span >Demo And Account</span></h1>
                                                </div>
                                                <div class="col-lg-12">
                                                   <h5 class=""><span >Experience Our Free Demo and Create Your Own Free Account</span></h5>
                                                </div>
                                                <div class="col-lg-6 border-right mt-5 ">
                                                    <!-- <div class="D-img text-center">
                                                <img src="Only D logo.png" alt="D logo" class="centered-img" style="width: 70px ;">
                                             </div> -->
                                             <div class="D-img">
                                                       
                                                    </div>
                                                   <p class=" mt-5" style="font-size: 16px;"><span >Experience our services with a free demo! Take advantage of our offer to explore a hands-on demonstration of our distributor platform. Discover the features, functionalities, and benefits our solution provides, all at no cost. Get a firsthand experience to see how our platform can streamline your operations and enhance your business processes.</span></p>
                                                   <div class="button-container text-center">
                                                   <a href="http://demo.distributorflow.com" class="btn btn-dark" target="_blank">
                                                   <i class="ti-blackboard mr-2"></i> Go for Demo
                                                   </a>
                                                </div>
                                                   <div class="mt-5"></div>
                                                </div>
                                                <div class="col-lg-6 mt-5">
                                                   <!-- <div class="D-img text-center">
                                                <img src="logo text.png" alt="D logo" class="centered-img" style="max-width: 400px ; height: 100px;" >
                                             </div> -->
                                              <div class="text-img">
                                                       
                                                    </div>
                                                   <p class=" mt-5  mb-3" style="font-size: 16px;"><span >Create your own free account or become a distributor today! Sign up for our platform and unlock a world of opportunities. With a free account, you can access our features and services, while distributors can join our network and expand their business reach. Don't miss out on the chance to join our thriving community and start making a difference.</span></p>
                                                   <!-- <div class="button-container text-center mt-5">
                                                      <a href="http://demo.distributorflow.co" target="_blank" class="btn btn-success">
                                                      <i class="fas fa-desktop mr-2"></i> Create Account
                                                      </a>
                                                   </div> -->
                                                   <div class="button-container text-center">
                                                   <a href="/signUp" class="btn btn-success" target="_blank">
                                                   <i class="ti-user mr-2"></i> Create Distributor Account
                                                   </a>
                                                </div>
                                                   <div class="mt-5"></div>
                                                </div>
                                             </div>
                                             <div class="row mt-5 bg-c-blue" style="color: white;">
                                                <div class="col-lg-6">
                                                   <h5 class="text-center mt-5"><span >Distributor You Can Trust </span></h5>
                                                </div>
                                                <div class="col-lg-6">
                                                   <p class=" mt-5 margin-left-right"><span >Distributor is a platform that currently offers its valuable services to users at no cost. We take pride in providing an accessible and convenient solution for individuals and businesses alike without any charges.

Please note that while our services are free at the moment, we are continuously evolving and improving our offerings. In the future, we may introduce premium features and paid plans to enhance the platform further and meet the growing needs of our users.

Rest assured, we are committed to ensuring a seamless experience for all our users, and any changes to our pricing model will be communicated transparently and well in advance. We value your trust and will always strive to provide you with the best possible services and options.</span></p>
                                                   <p class=" mt-5 margin-left-right">A Project By: Qureshi Sons</p>
                                                   <p class="mt-5"></p>
                                                </div>
                                             </div>
                                             <div class="row bg-dark" style="color: white;">
                                                <div class="col-lg-4">
                                                   <h5 class="text-center mt-5"><span >Revolutionizing Operations for Distributors</span></h5>
                                                </div>
                                                <div class="col-lg-4">
                                                   <p class=" mt-5"><span >DistributorFlow, a project by Qureshi Sons, is a revolutionary platform designed to streamline distributor operations. With advanced features and cutting-edge technology, it empowers distributors to efficiently manage their inventory, orders, and deliveries. Qureshi Sons brings their expertise and experience to deliver a comprehensive solution that enhances productivity and drives business growth.</span></p>
                                                   <p class="mt-5"></p>
                                                   <p class="mt-5"></p>
                                                </div>
                                                <div class="col-lg-4 text-center">
                                                   <p class="mt-5 "><a href="" style="color: white; text-decoration: underline;">Help Center</a>
                                                   </p>
                                                   <p class=""><a href="" style="color: white; text-decoration: underline;">Terms of Service</a>
                                                   <p class=""><a href="" style="color: white; text-decoration: underline;">Data Collection Policy</a>
                                                   <p class=""><a href="" style="color: white; text-decoration: underline;">Privacy Policy</a>
                                                   </p>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="styleSelector">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>
      <!-- Warning Section Starts -->
      <!-- Older IE warning message -->
      <!--[if lt IE 9]>
      <div class="ie-warning">
         <h1>Warning!!</h1>
         <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers
            to access this website.
         </p>
         <div class="iew-container">
            <ul class="iew-download">
               <li>
                  <a href="http://www.google.com/chrome/">
                     <img src="assets/images/browser/chrome.png" alt="Chrome">
                     <div>Chrome</div>
                  </a>
               </li>
               <li>
                  <a href="https://www.mozilla.org/en-US/firefox/new/">
                     <img src="assets/images/browser/firefox.png" alt="Firefox">
                     <div>Firefox</div>
                  </a>
               </li>
               <li>
                  <a href="http://www.opera.com">
                     <img src="assets/images/browser/opera.png" alt="Opera">
                     <div>Opera</div>
                  </a>
               </li>
               <li>
                  <a href="https://www.apple.com/safari/">
                     <img src="assets/images/browser/safari.png" alt="Safari">
                     <div>Safari</div>
                  </a>
               </li>
               <li>
                  <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                     <img src="assets/images/browser/ie.png" alt="">
                     <div>IE (9 & above)</div>
                  </a>
               </li>
            </ul>
         </div>
         <p>Sorry for the inconvenience!</p>
      </div>
      <![endif]-->
      <!-- Warning Section Ends -->
      <!-- Required Jquery -->
      <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/popper.js/popper.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
      <!-- jquery slimscroll js -->
      <script type="text/javascript" src="{{ asset('assets/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
      <!-- modernizr js -->
      <script type="text/javascript" src="{{ asset('assets/js/modernizr/modernizr.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/modernizr/css-scrollbars.js') }}"></script>
      <!-- Custom js -->
      <script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>
      <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
      <script src="{{ asset('assets/js/vartical-demo.js') }}"></script>
      <script src="{{ asset('assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
      <script>
         const ovals = document.querySelectorAll('.oval');
         const planetContainers = document.querySelectorAll('.planet-container');
         const planets = document.querySelectorAll('.planet');
         const planetLefts = document.querySelectorAll('.planet-left');
         
         const rotationSpeed = 1; // Adjust the rotation speed as desired
         const leftRotationSpeed = 2; // Adjust the rotation speed as desired
         
         ovals.forEach((oval, index) => {
           const ovalWidth = parseFloat(getComputedStyle(oval).width);
           const ovalHeight = parseFloat(getComputedStyle(oval).height);
           const radiusX = ovalWidth / 2;
           const radiusY = ovalHeight / 2;
         
           setInterval(() => {
             const angle = (Date.now() * rotationSpeed) / 1000;
             const x = radiusX * Math.cos(angle) - planets[index].offsetWidth / 2;
             const y = radiusY * Math.sin(angle) - planets[index].offsetHeight / 2;
         
             planets[index].style.transform = `translate(${x}px, ${y}px)`;
           }, 10);
         
           setInterval(() => {
             const leftAngle = (Date.now() * leftRotationSpeed) / 1000;
             const x = radiusX * Math.cos(leftAngle) - planetLefts[index].offsetWidth / 2;
             const y = radiusY * Math.sin(leftAngle) - planetLefts[index].offsetHeight / 2;
         
             planetLefts[index].style.transform = `translate(${x}px, ${y}px)`;
           }, 20);
         });
         
         
         //                   var container = document.querySelector('.rectangle-container');
         // var planetContainer = document.querySelector('.rectangle-planet-container');
         // var planet = document.querySelector('.rectangle-planet');
         
         // var containerRect = container.getBoundingClientRect();
         // var containerWidth = containerRect.width;
         // var containerHeight = containerRect.height;
         
         // var currentPosition = 0;
         // var speed = 5; // Adjust the speed of movement
         
         // function animatePlanet() {
         //    currentPosition += speed;
         
         //    if (currentPosition >= (containerWidth + containerHeight) * 2) {
         //       currentPosition = 0;
         //    }
         
         //    if (currentPosition < containerWidth) {
         //       planetContainer.style.left = currentPosition + 'px';
         //       planetContainer.style.top = '0';
         //    } else if (currentPosition < containerWidth + containerHeight) {
         //       planetContainer.style.left = containerWidth + 'px';
         //       planetContainer.style.top = (currentPosition - containerWidth) + 'px';
         //    } else if (currentPosition < containerWidth * 2 + containerHeight) {
         //       planetContainer.style.left = (containerWidth - (currentPosition - containerWidth - containerHeight)) + 'px';
         //       planetContainer.style.top = containerHeight + 'px';
         //    } else {
         //       planetContainer.style.left = '0';
         //       planetContainer.style.top = (containerHeight - (currentPosition - (containerWidth * 2 + containerHeight))) + 'px';
         //    }
         
         //    requestAnimationFrame(animatePlanet);
         // }
         
         // animatePlanet();
         
         
      </script>
   </body>
</html>