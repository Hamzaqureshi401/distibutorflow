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

<!-- Product -->
@php 
$assign_order = App\Models\Seller::where('seller_id' , Auth::id())->pluck('assign_order')->first();
@endphp
@if ($assign_order == 1)
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-package"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Products</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('products.stock') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Product Stock</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
@endif
        
<!-- Product End -->

<!-- Customers -->

<li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-package"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Attandence</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('mark.att') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Mark/Unmark Attandence</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('Get.Attendence.Record' , Auth::id()) }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Attandence Record</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('get.Employee.Sellary' , Auth::id()) }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Sellary Record</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>

      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-id-badge"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Customers</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">          
            <li class=" ">
               <a href="{{ route('call.customers') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Call To Customers</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
   
<!-- Customer End -->


      <!-- Invoices -->
@if ($assign_order == 1)
       <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Invoices</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">                       
            <li class=" ">
               <a href="{{ route('manage.stock') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add/Remove Stock</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

             <li class=" ">
               <a href="{{ route('unApproved.Stock.Invoices') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Un Approved Invoices</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

             <li class=" ">
               <a href="{{ route('Get.Stock.Invoices' , 'unapproved') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Get stock invoices</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>            
         </ul>
      </li>

 @endif
      <!-- Invoice End -->

       <li class="pcoded-hasmenu {{ Nav::hasSegment('order') }}">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Orders</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         <!-- Admin Controll -->           
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
            @if ($assign_order == 1)
             <li class=" ">
               <a href="{{ route('processed.orders.seller') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Processed Orders</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>  
            @endif         
         </ul>
      </li>

      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-user"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Account</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
           
            <li class=" ">
               <a href="{{ route('all.sellers') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Account</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
         </ul>
      </li>
 
      
   
      <!-- order End -->

   </ul>
<!-- Paid History End -->

   
</div>
</nav>