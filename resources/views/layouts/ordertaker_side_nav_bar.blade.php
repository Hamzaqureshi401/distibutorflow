<div class="pcoded-main-container">
<div class="pcoded-wrapper">
<nav class="pcoded-navbar">
<div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
<div class="pcoded-inner-navbar main-menu">
   <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Layout</div>
   <ul class="pcoded-item pcoded-left-item">
      <li class="">
         <a href="{{ route('admin.home') }}">
         <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
         <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
         <span class="pcoded-mcaret"></span>
         </a>
      </li>

<!-- Customers -->

     <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-id-badge"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Customers</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
           
            <li class=" ">
               <a href="{{ route('add.customer') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Customer</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            <li class=" ">
               <a href="{{ route('all.customers') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Customers</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
           
            <li class=" ">
               <a href="{{ route('call.customers') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Call To Customers</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

             <li class=" ">
               <a href="{{ route('get.new.cstmr.location') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Get New Cstmr Location</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            <!--  <li class=" ">
               <a href="{{ route('getProduct.For.DefualtOrder') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Set Defualt Order</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li> -->
         </ul>
      </li>
    
<!-- Customer End -->

<!-- Area -->

      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-direction-alt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Area</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            
            <li class=" ">
               <a href="{{ route('add.area') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Area</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

            <li class=" ">
               <a href="{{ route('list.area') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Area</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
   
         </ul>
      </li>

<!-- Area End -->

 
        <li class="pcoded-hasmenu {{ Nav::hasSegment('order') }}">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Orders</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         
            <li class=" ">
               <a href="{{ route('create.order') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">New Order</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
            <li class=" ">
               <a href="{{ route('unconfirmed.orders') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Unconfirmed Orders</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
           
            <li class=" ">
               <a href="{{ route('confirmed.orders.seller') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Seller Confirmed Orders</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>


      
   
      <!-- order End -->

   </ul>
   </ul>
<!-- Paid History End -->

   
</div>
</nav>