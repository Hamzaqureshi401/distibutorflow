@include('layouts.common_header')

      <!-- Google font-->
      <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
         --><!-- Required Fremwork -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
      <!-- themify-icons line icon -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/font-awesome/css/font-awesome.min.css') }}">
      <!-- ico font -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
      <!-- Notification.css -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/notification/notification.css') }}">
      <!-- Animate.css -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css/css/animate.css') }}">
      <!-- Style.css -->
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.mCustomScrollbar.css') }}">
      <link href="{{ asset('assets/css/toastr.min.css') }}" rel="stylesheet">
      <!-- <link rel="stylesheet" type="text/css" href=" {{ asset('assets/css/1.11.3dataTables.css') }} "> -->
      <link href="{{ asset('assets/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/bootstrap4-toggle.min.css') }}" rel="stylesheet">

      <style type="text/css">
         .loader{
    position:fixed;
    left:0px;
    top:0px;
    width:100%;
    height:100%;
    z-index:9999;
    background:url("{{ asset('images/OnlyDlogo.png') }}") 50% 50% no-repeat #f9f9f9;
    opacity:1
}
      </style>
      @stack('styles')
   </head>
   <body>
      <body>
         <div class="loader"></div>
  
         <!--  <div class="fixed-button">
            <a href="https://codedthemes.com/item/gradient-able-admin-template" target="_blank" class="btn btn-md btn-primary">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i> Upgrade To Pro
            </a>
            </div> -->
         <!-- Pre-loader start -->
         <div class="fixed-button">
            <a href="" data-toggle="modal" data-target="#exampleModalPopovers" class="btn btn-md btn-info btn-out-dashed">
            <i class="fas fa-comments" aria-hidden="true"></i> Need Help?
            </a>
         </div>
         <div class="theme-loader">
            <div class="loader-track">
               <div class="loader-bar"></div>
            </div>
         </div>
         <!-- Pre-loader end -->
         <div id="pcoded" class="pcoded">
         <div class="pcoded-overlay-box"></div>
         <div class="pcoded-container navbar-wrapper">
         @include('layouts.header')
         @if (Auth::user()->role < 3)
         @include('layouts.side_nav_bar')
         @elseif(Auth::user()->role == 3)
         @include('layouts.seller_side_nav_bar')
         @elseif(Auth::user()->role == 4)
         @include('layouts.customer_side_nav_bar')
         @elseif(Auth::user()->role == 7 || Auth::user()->role == 6)
         @include('layouts.customer_employee_side_nav_bar')
         @else
         @include('layouts.ordertaker_side_nav_bar')
         @endif
         <div class="pcoded-content">
         <div class="pcoded-inner-content">
         <div class="main-body">
         <div class="page-wrapper">
         <div class="modal fade" id="cash_processing" tabindex="-1" role="dialog" aria-labelledby="cash_processing-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title">Order Cash Processing</h5>
                     <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">×</span>
                     </button>
                  </div>
                  <div class="modal-body">
                     <form class="submit_cash_processing" onsubmit="disableButton();" >
                        {{ csrf_field() }}
                        <div class="form-group">
                           @if(Auth::user()->role < 3)
                           <label>Pay Amount </label><input class="form-control r-amount totalCal" id="p-amount" type="number" name="cash_processing" class="form-control" value="0" required="">
                           @endif
                           <label>Add Expense</label><input class="form-control r-amount totalCal" type="number" id="a-expence" name="expenses" class="form-control" value="0" required="">
                           <label>Remaining</label><input class="form-control r-amount" type="number" id="g-remain" class="form-control" value="0" readonly="">
                           <label style="color: red;">Add Comments <span style=" font-style: italic; color: red;"></span></label>
                           <textarea class="form-control" rows="4" placeholder="Enter Comments" id="comments" name="comments" maxlength = "200"></textarea>
                        </div>
                        <button class="btn btn-primary btn-block cash_handle call-processing" id = "btnhandle">Continue</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
         <div id="exampleModalPopovers" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalPopoversLabel">Modal Title</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <h5>Tooltips in a Button</h5>
                     <p>This <a href="#!" role="button" class="btn  btn-secondary tooltip-test" data-bs-toggle="tooltip" title="Button Tooltip" data-container="#exampleModalPopovers">button</a>
                        triggers a popover on click.
                     </p>
                     <hr/>
                     <h5>Tooltips in a modal</h5>
                     <p><a href="#!" class="tooltip-test" data-bs-toggle="tooltip" title="Tooltip" data-container="#exampleModalPopovers">This link</a> and <a href="#!" class="tooltip-test" data-bs-toggle="tooltip" title="Tooltip" data-container="#exampleModalPopovers">that
                        link</a> have tooltips on hover.
                     </p>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn  btn-primary">Save changes</button>
                  </div>
               </div>
            </div>
         </div>
         <!-- add content -->
         @yield('content')
         <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.js') }}"></script>
         <script type="text/javascript" src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
         <script type="text/javascript" src="{{ asset('assets/js/popper.js/popper.min.js') }}"></script>
         <script type="text/javascript" src="{{ asset('assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
         <!-- jquery slimscroll js -->
         <script type="text/javascript" src="{{ asset('assets/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
         <!-- modernizr js -->
         <script type="text/javascript" src="{{ asset('assets/js/modernizr/modernizr.js') }}"></script>
         <!-- am chart -->
         <script src="{{ asset('assets/pages/widget/amchart/amcharts.min.js') }}"></script>
         <script src="{{ asset('assets/pages/widget/amchart/serial.min.js') }}"></script>
         <!-- Chart js -->
         <script type="text/javascript" src="{{ asset('assets/js/chart.js/Chart.js') }}"></script>
         <!-- Todo js -->
         <script type="text/javascript " src="{{ asset('assets/pages/todo/todo.js') }} "></script>
         <!-- notification js -->
         <script type="text/javascript" src="{{ asset('assets/js/bootstrap-growl.min.js') }}"></script>
         <!-- Custom js -->
         <script type="text/javascript" src="{{ asset('assets/js/script.js') }}"></script>
         <script type="text/javascript " src="{{ asset('assets/js/SmoothScroll.js') }}"></script>
         <script src="{{ asset('assets/js/pcoded.min.js') }}"></script>
         <script src="{{ asset('assets/js/vartical-demo.js') }}"></script>
         <script src="{{ asset('assets/js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
         <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
         <script src="{{ asset('assets/datatables/jquery.dataTables.js') }}"></script>
         <script src="{{ asset('assets/datatables/dataTables.bootstrap4.js') }}"></script>
         <script src="{{ asset('assets/js/bootstrap4-toggle.min.js') }}"></script>
         <script type="text/javascript">
            $(document).ready(function() {
             $('.table-datatable').DataTable({
                 lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
             });
              $('.customer-table_length select').val('-1').change();
         });

            function disableButton() {
                 
                 $('.call-processing').disabled = true;
                 $('.call-processing').html('Processing Wait');
            
                 var originalText = $("#button").text(),
             i  = 0;
            setInterval(function() {
            
             $("#button").append(".");
             i++;
            
             if(i == 4)
             {
                 $("#button").html(originalText);
                 i = 0;
             }
            
            }, 500);
             }
             var id = "";
            
             $('.cash_processing').on('click',function(e){
            e.preventDefault();
            id = $(this).data('id');
            //console.log('yes');
            $('#cash_processing').modal('show');
            //$('.submit_cash_processing').attr('action', $(this).data('route'));
            });
            $('.cash_handle').click(function(e){
            $("#cash_processing .close").click();
            e.preventDefault();
            e.stopImmediatePropagation();
            var seller = $('#seller').val();
            if (seller == null){
               seller = id;
            }
            var amount = $('#p-amount').val();
            var expence = $('#a-expence').val();
            var comment = $('#comments').val();
            $("#p-amount").val('');
            $("#a-expence").val('');
            $("#comments").val('');
            var btn = document.getElementById('btnhandle');
            btn.innerText = 'Submitting';
            $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('set.cash.processing') }}',
            data: {'amount': amount, 'expence': expence, 'comment': comment , 'seller': seller},
            success: function( data ) {
                console.log( data );
                 toastr.success(data.message);
                var btn = document.getElementById('btnhandle');
                btn.innerText = 'Add!';
            }
            });
            
            return false;
            });
            
            $(function() { 
            $(".totalCal").keyup(function() { 
            $(document).on("keyup", calculate);
            }); //Run it once
            });
            
            // $(document).ready(calculate);   
            // $(document).on("keyup", calculate);
            var old_cash = 0;
            function calculate() {
            
            $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('get.cash.remaining') }}',
            data: {},
            success: function( data ) {
                console.log( data );
                 old_cash = data.data;
             }
            });
            console.log(1);
            var sum = 0;
            $(".totalCal").each(function(){
            sum += +$(this).val();
            });
            document.getElementById('g-remain').value =old_cash - sum;
            }
            
            
            
            $('ul li a').on('click', function(){
             $(this).parent().addClass('active').siblings().removeClass('active');
            });
            
            
            function notify(from, align, icon, type, animIn, animOut ,title , msg){
            $.growl({
            icon: icon,
            title: title,
            message: msg,
            url: ''
            },{
            element: 'body',
            type: type,
            allow_dismiss: true,
            placement: {
                from: from,
                align: align
            },
            offset: {
                x: 30,
                y: 30
            },
            spacing: 10,
            z_index: 999999,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: false,
            animate: {
                enter: animIn,
                exit: animOut
            },
            icon_type: 'class',
            template: '<div data-growl="container" class="alert" role="alert">' +
            '<button type="button" class="close" data-growl="dismiss">' +
            '<span aria-hidden="true">&times;</span>' +
            '<span class="sr-only">Close</span>' +
            '</button>' +
            '<span data-growl="icon"></span>' +
            '<span data-growl="title"></span>' +
            '<span data-growl="message"></span>' +
            '<a href="#" data-growl="url"></a>' +
            '</div>'
            });
            };
            
            
            var nFrom = "bottom";
            var nAlign = "right";
            var nIcons = "" ;
            var nAnimIn = "animated rotateInDownRight";
            var nAnimOut = "animated rotateOutUpRight";
            
            @if(session('success'))
            
            var nType = "success";
            var title = "Success ";
            var msg = "{{session('success')}}";
            
            notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg)
            
            @elseif(session('error'))
            
            var nType = "danger";
            var title = "Failed ! ";
            var msg = "{{session('error')}}";
            notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut , title , msg)
            @endif
            
            var $window = $(window);
            var nav = $('.fixed-button');
            $window.scroll(function(){
            if ($window.scrollTop() >= 200) {
            nav.addClass('active');
            }
            else {
            nav.removeClass('active');
            }
            });

            $(window).on("load", function () {
                $(".loader").fadeOut("slow");
            });
                     
            
            
         </script>
         @stack('scripts')
   </body>
</html>