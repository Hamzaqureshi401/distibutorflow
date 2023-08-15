@extends('layouts.app')
@push('styles')
<style>
  

.results tr[visible='false'],
.no-result{
  display:none;
}

.results tr[visible='true']{
  display:table-row;
}
.check-all{
  background-color: #59e0c5;
}

</style>
@endpush
@section('title') Add User @endsection

@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Add User</h5>
            <p class="text-muted m-b-10 text-center">Select User Type</p>
           <!--  <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Products</a>
               </li>
                <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li>
            </ul>
            <div class="card-header">
               <i class="fa fa-table"></i> Products List
               <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Product</a>
            </div> -->
         </div>
      </div>
<div class="row">
  <div class="col-md-6 m-auto">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-user"></i> Enter User Details
      </div>
      <div class="card-body">
        <form id="myform">
          {{ csrf_field() }}
          <div class="form-group">
            <label>User Role</label>
            <select class="form-control" name="type" id="u-type">
              <option value="1">Seller</option>
              <option value="2">Manager</option>
            </select>
          </div>
          <div class="form-group">
            <label>Name <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="text" placeholder="Enter Name" name="name" required>
          </div>
          <div class="form-group">
            <label>Email <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" id="email" type="email" placeholder="Enter Email" name="email" required>
            
            <div><b style="color: red;" id="user-not-unique"></b></div>
             @if($errors->has('email'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
            @endif
          </div>
          <div class="form-group">
            <label>Password <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="password" placeholder="Enter Password" name="password" required>
          </div>
          <div class="form-group">
            <label>Phone <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="number" placeholder="Enter Phone Number" name="phone" required>
          </div>
          @include('employeeAttandence.employee_attandence_settings')
         
          <div id="has_pin"></div>
          <div id="custom-allow" style="display: none;">
           {{-- @foreach($customers as $c)
              <input type="hidden" class="form-control c-id" value="{{ $c->id }}">
            @endforeach --}}
          </div>
          
          <div class="form-group d-none r-d-n">
            <label>Allow him to create order <span style="opacity: 0.5; font-style: italic;">(Will have own customer)</span></label>
            <input type="checkbox" data-toggle="toggle" data-onstyle="success" data-size="xs" name="itself_order_taker">
          </div>

        
         <button type="submit" id="button" class="btn btn-primary btn-block">Create User</button>
        </form>
      </div>
  </div>
</div>
</div>

@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
  {{--  $('table').dataTable();  --}}
} );
 

var total = 0;
    $(function () {
        $('form').on('submit', function (e) {
          e.preventDefault();
          $('#user-not-unique').html('');
          var btn = document.getElementById('button');
          btn.disabled = true;
          btn.innerText = 'User Saving Wait..';
          var mail = $('#email').val();
          var data = finduser(mail);
        });

      });

    function finduser(argument) {
      
      $.ajax({
            type: 'post',
            url: '{{ route('find.user') }}',
            data:{
              _token: "{{ csrf_token() }}", 'email': argument},
            success: function (data) {
             
              if (data == 0){
                submitdata();
              }else{
                $('#user-not-unique').html('This User Is Already Taken!');
                var btn = document.getElementById('button');
                btn.disabled = false;
                  setTimeout(function(){
                   btn.innerText = 'Try Again to Create User';
               }, 5000);
                btn.innerText = 'Please resolve error!';
              }
              
              }
          });

      }

      function submitdata(){

         $('#button').addClass('btn-success');
          $.ajax({
            type: 'post',
            url: '{{ route('Store.User') }}',
            data: $('form').serialize(),
            success: function (data) {
              console.log(data);
              var nType = "success";
              var title = "Success ";
              var msg = data.message;
              notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
              $('#myform')[0].reset();
               var btn = document.getElementById('button');
                btn.disabled = false;
                total = total + 1;
                btn.innerText = 'Again Save New User (Totoal Saved '+total+')';
                $('.ot_options').css('display','none');
                //$('#button').addClass('btn-primary');
              }
          });
      }
</script>
@endpush