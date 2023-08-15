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
<!-- Category -->
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-layers-alt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Categories</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('all.categories') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Category</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
<!-- Category End -->

<!-- Product -->

@if(Auth::user()->role < 3 || Auth::user()->role == 3 && $result['assign_order'] == 1) 
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-package"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Products</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
             @if(Auth::user()->role < 3 )
            <li class=" ">
               <a href="{{ route('add.product') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Product</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

            <li class=" ">
               <a href="{{ route('all.products') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Products</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @endif
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
        
<!-- Product Kit  End -->

@if(Auth::user()->role < 3 ) 
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-package"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Product Kit</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            
            <li class=" ">
               <a href="{{ route('add.productkit') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Product Kit</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

            <li class=" ">
               <a href="{{ route('all.productkit') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Products Kit</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
           
            
         </ul>
      </li>
       @endif


<!-- Customers -->

 @if(Auth::user()->role < 3 || Auth::user()->role == 5 && $customer_itself == 0) 
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
             @if(Auth::user()->role == 5 || Auth::user()->role < 3)
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

            <li class=" ">
               <a href="{{ route('getProduct.For.DefualtOrder') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Set Defualt Order</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
             @endif
         </ul>
      </li>
   
<!-- Customer End -->

<!-- Order taker -->
@if(Auth::user()->role == 5) 
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-view-list-alt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">My Earnings</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('all.ot') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Earning</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
@endif
<!-- Order Taker End -->

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
       @endif

<!-- Area End -->

<!-- Employees -->

 @if(Auth::user()->role < 3) 
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-user"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Employees</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
           
            <li class=" ">
               <a href="{{ route('add.user') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Employee</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @if(Auth::user()->role == 1)
            <li class=" ">
               <a href="{{ route('all.users') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Sub Admin</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @endif
            @if(Auth::user()->role < 3)
            <li class=" ">
               <a href="{{ route('all.ot') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Order Taker</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @endif
            <li class=" ">
               <a href="{{ route('all.sellers') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">All Selers</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @elseif (Auth::user()->role == 3)

                     <!-- Self Employee -->
               <li class="pcoded-hasmenu">
                  <a href="javascript:void(0)">
                  <span class="pcoded-micon"><i class="ti-view-list-alt"></i></span>
                  <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Employee</span>
                  <span class="pcoded-mcaret"></span>
                  </a>
                  <ul class="pcoded-submenu">
                     <li class=" ">
                        <a href="{{ route('all.sellers') }}">
                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                        <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Profile</span>
                        <span class="pcoded-mcaret"></span>
                        </a>
                     </li>
                  </ul>
               </li>
         <!-- Self Employee End -->

            
         </ul>
      </li>
   @endif      
   </ul>
   <!-- Employees End -->

      <!-- Invoices -->

  @if(Auth::user()->role !=5 )
       <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Invoices</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">

          @if(Auth::user()->role < 4)
           
            <li class=" ">
               <a href="{{ route('add.invoice') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">New Invoice</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
            <li class=" ">
               <a href="{{ route('manage.stock') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add/Remove Stock</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
           
            <li class=" ">
               <a href="{{ route('invoices') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Approved Invoices</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

             <li class=" ">
               <a href="{{ route('invoices' , 'unapproved') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Un Approved Invoices</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

             <li class=" ">
               <a href="{{ route('unApproved.Stock.Invoices') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Un Approved Stock Invoices</span>
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
             @else

             <li class=" ">
               <a href="{{ route('create.order') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Send Invoice</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>

             <li class=" ">
               <a href="{{  route('customer.invoices' , Auth::user()->customer_id) }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Invoices</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @endif
             
         </ul>
      </li>

      @endif

      <!-- Invoice End -->

        <!-- Invoices -->

  @if(Auth::user()->role < 4 || Auth::user()->role == 5)
       <li class="pcoded-hasmenu {{ Nav::hasSegment('order') }}">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Orders</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         <!-- Admin Controll -->
         
         @if(Auth::user()->role < 3 )          
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

             <li class=" ">
               <a href="{{ route('processed.orders.seller') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Processed Orders</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @endif

             

            <!-- Seller Controll -->

         @if(Auth::user()->role == 3 )          
            
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
            @if ($result['assign_order'] == 1)
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
      @endif

       <!-- Customer Controll -->

         @if(Auth::user()->role == 5 )   

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
           
           
            
           
             
         </ul>
      </li>
      @endif
      @endif
   </ul>
   <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-stats-up"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Pos</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('pos.Sale') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Pos</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            <li class=" ">
               <a href="{{ route('get.Order.Taker.Pos.Orders') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Get Pos Sale</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>

      <!-- order End -->

      <!-- Sell Record -->
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-stats-up"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Sell Records</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('sell.record') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Get Sell Records</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
<!-- Sell Record End -->

<!-- Paid History -->
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-layout-cta-btn-right"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Paid History</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('paid.history') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Get Paid History</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
<!-- Paid History End -->

<!-- Pos -->
      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-settings"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Settings</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
            <li class=" ">
               <a href="{{ route('receipt.Settings') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Receipt Settings</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>
<!-- Paid History End -->

   
</div>
</nav>