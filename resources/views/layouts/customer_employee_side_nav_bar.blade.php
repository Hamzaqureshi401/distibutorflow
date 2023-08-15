<div class="pcoded-main-container">
<div class="pcoded-wrapper">
<nav class="pcoded-navbar">
<div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
<div class="pcoded-inner-navbar main-menu">
   <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Layout</div>
   <ul class="pcoded-item pcoded-left-item">
      <!-- <li class="">
         <a href="{{ route('admin.home') }}">
         <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
         <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
         <span class="pcoded-mcaret"></span>
         </a>
      </li> -->

     <li class="pcoded-hasmenu {{ Nav::hasSegment('order') }}">
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
               <a href="{{ route('Get.Pos.Uncnfirmed.Sale') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Unconfirmed Sales</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @if(Auth::user()->role == 7)
             <li class=" ">
               <a href="{{ route('Get.PosManager.Sale') }}">
               <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
               <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">Manager Confirmed Sales</span>
               <span class="pcoded-mcaret"></span>
               </a>
            </li>
            @endif
   </ul>
   </li>
            @if(Auth::user()->role == 7)
      <li class="pcoded-hasmenu ">
         <a href="javascript:void(0)">
         <span class="pcoded-micon"><i class="ti-receipt"></i></span>
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
         @endif
   
   </ul>
<!-- Paid History End -->

   
</div>
</nav>