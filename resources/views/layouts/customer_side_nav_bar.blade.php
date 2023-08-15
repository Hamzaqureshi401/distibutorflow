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

      <li class="pcoded-hasmenu">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-package"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Products</span>
         <span class="pcoded-mcaret"></span>
         </a>
         <ul class="pcoded-submenu">
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
           <!--  <li class=" ">
               <a href="{{ route('products.stock') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Product Stock</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li> -->
             <li class=" ">
               <a href="{{ route('get.customer.product.stock') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Product Stock</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
             <li class=" ">
               <a href="{{ route('Get.Customer.Stock.Invoices') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext text-center" data-i18n="nav.basic-components.alert">Stock Add Remove Invoices</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         </ul>
      </li>        
<!-- Product End -->



 
        <li class="pcoded-hasmenu {{ Nav::hasSegment('order') }}">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Orders</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         
            <li class=" ">
               <a href="{{ route('create.order.by.customer') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Send Order</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
            <li class=" ">
               <a href="{{ route('customer.invoices' , Auth::user()->customer_id) }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Purchasings</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
         
      
   
      <!-- order End -->

   </ul>
   </li>
       <li class="pcoded-hasmenu ">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Pos</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         
            <li class=" ">
               <a href="{{ route('Add.Pos.Sale') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Create Sale</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
            <li class=" ">
               <a href="{{ route('Get.Pos.Uncnfirmed.Sale' , Auth::user()->customer_id) }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Unconfirmed Sales</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            <li class=" ">
               <a href="{{ route('Get.PosManager.Sale') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Manager Confirmed Sales</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
   </ul>
   </li>

      <li class="pcoded-hasmenu ">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-money"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Cash Receivings</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         
            <li class=" ">
               <a href="{{ route('Get.Customer.Cash.Receivings') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Cash Receivings</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
   </ul>
   </li>

        <li class="pcoded-hasmenu ">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-user"></i></span>
         <span class="pcoded-mtext"  data-i18n="nav.basic-components.main">Employee</span>
         <span class="pcoded-mcaret"></span>
         </a>
        <ul class="pcoded-submenu">
         
         
            <li class=" ">
               <a href="{{ route('Add.Customer.Employee') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Add Employee</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
             <li class=" ">
               <a href="{{ route('Get.Customer.Seller') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Seller</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
             <li class=" ">
               <a href="{{ route('Get.Customer.Manager') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">My Manager</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            
   </ul>
   </li>
 
   </ul>
<!-- Paid History End -->

   
</div>
</nav>