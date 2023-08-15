@extends('layouts.app')
@section('title') All Products @endsection
@section('content')
<?php error_reporting(0) ?>
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Stock Of Products</h5>
            <p class="text-muted m-b-10 text-center">Stock Details</p>
            <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All stock Products</a>
               </li>
               <!--  <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li> -->
            </ul>
         </div>
      </div>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Products List <br>@if($findadminname != null)<b>You Can Link Product with {{ $findadminname }}</b> @endif
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Stock</th>
                @if($findadminname != null)
                <th>Link</th>
                @endif
              
              </tr>
            </thead>
            <tbody>
              @foreach($products as $product)
              <tr>
                <td>{{ $product->name }} <b>Has Link with</b> {{ $product->productname($product->link_product) }} </td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->remaining_stock }}</td>
                
                
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://kit.fontawesome.com/yourcode.js"></script>
<script type="text/javascript">
   // swith controll 1
  let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
elems.forEach(function(html) {
    let switchery = new Switchery(html,  { size: 'small' });
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
            console.log(data.message);
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


</script>
<script>
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
$(".productid").change(function(e) {
  e.preventDefault();
  e.stopPropagation();
  var button = $(this).next("button");
  if (this.value != "NULL") {
    button.prop("disabled", false)
  };
});
</script>
@endsection