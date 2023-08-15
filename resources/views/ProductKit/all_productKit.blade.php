@extends('layouts.app')
@section('title') All Products @endsection
@section('content')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
    .active-this  {
    background:#C6F9F5;
}
</style>
@endpush
<?php error_reporting(0) ?>
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">All Products Kit</h5>
            <p class="text-muted m-b-10 text-center">Defines User Products Kit</p>
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Products Kit</a>
               </li>
               <!--  <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li> -->
            </ul>
            <div class="card-header">
               <i class="fa fa-table"></i> Products Kit List 
              
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card mb-3">
               <div class="card-header">
                  <i class="fa fa-table"></i> Products List <br>@if($findadminname != null)<b>You Can Link Product with {{ $findadminname }}</b> @endif
               </div>
               <div class="card">
                  <div class="card-header">
                     <!--  <h5>Hover table</h5>
                        <span>use class <code>table-hover</code> inside table element</span> -->
                     <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                           <li><i class="fa fa-chevron-left"></i></li>
                           <li><i class="fa fa-window-maximize full-card"></i></li>
                           <li><i class="fa fa-minus minimize-card"></i></li>
                           <li><i class="fa fa-refresh reload-card"></i></li>
                           <li><i class="fa fa-times close-card"></i></li>
                        </ul>
                     </div>
                  </div>
                  <div class="card-block table-border-style">
                     <div class="table-responsive">
                        <table class="table table-bordered table-datatable table-custom-th table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 <th>ID</th>
                                 <th>Category</th>
                                 <th>Name</th>
                                 <th>Price</th>
                                 <th>Ben</th>
                                 <th>Img</th>
                                 <th>Action</th>
                                 
                              </tr>
                           </thead>
                           <tbody>
                              @foreach($ItemKits as $ItemKit)
                              <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $ItemKit->category->name }}</td>
                                <td>{{ $ItemKit->name }}</td>
                                <td>{{ $ItemKit->price }}</td>
                                 <td>{{ $ItemKit->a_benefit }}</td> 
                                <td ><img 
                                       src="{{ asset($ItemKit->img) }}" 
                                       alt="Snow" 
                                       style="max-width:5%; max-height:5% ;"
                                       ></td>
                                <td>  <a 
                                    href="javascript:;"
                                    data-toggle="modal" 
                                    data-target="#order-detail-popup" 
                                    class="btn btn-sm btn-success view-details" 
                                    id="{{ $ItemKit->id }}">
                                    <i class="fa fa-eye"> Kit Detail
                                    </i>
                                </a>
                                <a 
                                    href="javascript:;"
                                    data-toggle="modal" 
                                    data-target="#order-detail-popup" 
                                    class="btn btn-sm btn-success edit-kit" 
                                    id="{{ $ItemKit->id }}">
                                    <i class="fa fa-eye">Edit Kit
                                    </i>
                                </a>
                            </td>
                              </tr>
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<div class="modal fade" id="order-detail-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Kit Detail <small></small></h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">    
    $(".select2").select2({
         placeholder: "Select a product",
            allowClear: true,
            theme: "classic"
        });

    $('.view-details').click(function(){
    var param = $(this).attr('id');
    $.get('{{ route("product.KitDetail") }}/' + param , function(success){
      $("#order-detail-popup .modal-body").show();
      $('#order-detail-popup .modal-body').html(success);
    });
    });

     $('.edit-kit').click(function(){
    var param = $(this).attr('id');
    $.get('{{ route("edit.Kit") }}/' + param , function(success){
      $("#order-detail-popup .modal-body").show();
      $('#order-detail-popup .modal-body').html(success);

       $(document).ready( function () {
       $("#myForm").bind("submit", function (evt) {
          evt.preventDefault();
       var route = '/updateProductKit'; //@json($route);
       var formData = new FormData(this);
       console.log(formData);
          $.ajax({
            type: 'post',
            url: route,
            data: formData,
            success: function (data) {
              toastr.success(data.message);
               var btn = document.getElementById('button');
                btn.disabled = false;
                $('#exampleModal .close').click();
                 

              },
               error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error Please Ask for Administration';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg);
                var er = "Error Occured!";
                toastr.error(msg);
                $('#exampleModal .close').click();
                
            },
              cache: false,
              contentType: false,
              processData: false
          });
        });
      });
    });
    });

   $(document).ready(function(){
   $(document).on('change', '.allow_to_all_customer',function () {
        let allow_status = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('allow.to.all.customer') }}',
            data: {'allow_status': allow_status, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            }
        });
    });
   });
   
   $(document).ready(function(){
   $(document).on('change', '.js-switch1',function () {
        let allow_status = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('allow.update.status') }}',
            data: {'allow_status': allow_status, 'user_id': userId},
            success: function (data) {
             toastr.success(data.message);
            }
        });
    });
   });
   
   $(document).ready(function(){
    $(document).on('change', '.js-switch2',function () {
        let show_status = $(this).prop('checked') === true ? 1 : 0;
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('show.update.status1') }}',
            data: {'show_status': show_status, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            }
        });
    });
   });
   
   function linkproduct(button){
    var user_product = $(button).data('rowid');
     var maping_product = $(button).closest("td").find('select.productid').val();
     $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('link.product') }}',
            data: {'user_product': user_product, 'maping_product': maping_product },
             success: function (data) {
                 if(data.success == true){
                     
                         toastr.success(data.message);
                 } else {
                         toastr.error(data.message);
                 }
            },
            error: function(err){
            toastr.error(data.message);
            }
        });
     
   }
   function deletelinkproduct(button){
   var user_product = $(button).data('id');
   $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('delete.link.product') }}',
            data: {'user_product': user_product },
             success: function (data) {
                 if(data.success == true){
                     
                         toastr.success(data.message);
                 } else {
                         toastr.error(data.message);
                 }
            },
            error: function(err){
            toastr.error(data.message);
            }
        });
       
   }

   function deleteproduct(id){
   
   $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('delete.product') }}' +'/' + id,
            
             success: function (data) {
                 if(data.success == true){                     
                          var nType = "success";
                          var title = "Success ";
                          var msg = data.message;
                          console.log(data.message);
                          notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
                          $('#d-'+id).closest('tr').remove();
                 } else {
                       var nType = "danger";
                       var title = "Failed ";
                       var msg = data.message;
                       console.log(data.message);
                       notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
                 }
              },
            error: function(data){
            toastr.error("Something Went wrong");
            }
        });
       
   }
   $(".productid").change(function(e) {
   e.preventDefault();
   e.stopPropagation();
   var button = $(this).next("button");
   if (this.value != "NULL") {
    button.prop("disabled", false)
   };
   });
function getrowdata(argument) {
   $.get('{{ route("edit.product") }}/' + argument , function(success){
     // $("#order-detail-popup .modal-body").show();
      $('#order-detail-popup .modal-body').html(success);

       var product_id = ($('.id').val());

       var category_name = $('#c-name').val();

       var name = ($('.name').val());
       var price = ($('.t-price').val());
       var sell_price = ($('.sell-price').val());
       
       var p_price = ($('.p-price').val());
       var c_benefit = ($('.c-ben').val());
       var ot_benefit = ($('.ot-ben').val());
       var a_benefit = ($('.a_ben').val());

       console.log(name , category_name);

       $('#name-'+argument).html(name);
       $('#cat-'+argument).html(category_name);
       //$('#stock-'+argument).html(name);
       $('#price-'+argument).html(price);
       $('#p_price-'+argument).html(p_price);
       $('#s_price-'+argument).html(sell_price);
       $('#a-ben-'+argument).html(a_benefit);
       $('#c-ben-'+argument).html(c_benefit);
       $('#ot-ben-'+argument).html(ot_benefit);
       $('#d-'+argument).addClass('active-this');
       
      
       });
}

$("#exampleModal").prependTo("body");  
   $('.gt-data').click(function(){
    var getEditRoute = @json($getEditRoute);
    console.log(getEditRoute);
   $.get(getEditRoute + '/' + ($(this).data('id')) , function(success){
      $("#exampleModal .modal-body").show();
      $('#exampleModal .modal-body').html(success);
      $(document).ready( function () {
       $("#myForm").bind("submit", function (evt) {
          evt.preventDefault();
       var route = @json($route);
       var formData = new FormData(this);
       console.log(formData);
          $.ajax({
            type: 'post',
            url: route,
            data: formData,
            success: function (data) {
              toastr.success(data.message);
               var btn = document.getElementById('button');
                btn.disabled = false;
                $('#exampleModal .close').click();
                 

              },
               error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error Please Ask for Administration';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg);
                var er = "Error Occured!";
                toastr.error(msg);
                $('#exampleModal .close').click();
                
            },
              cache: false,
              contentType: false,
              processData: false
          });
        });
      });
    });
});

   

</script>
@endpush