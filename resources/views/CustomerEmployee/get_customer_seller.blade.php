@extends('layouts.app')
@section('content')
<!-- Main-body start -->
      <!-- Page-header start -->
      <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Sellrs List</h5>
            <p class="text-muted m-b-10 text-center">My Seller</p>
         </div>
      </div>
      <!-- Page-header end -->
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
               <table class="table table-hover table-datatable">
                  <thead>
                     <tr class="text-center">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($GetCustomerSeller as $user)
                     <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td> <a href="{{ route('Get.Attendence.Record' , $user->id) }}" class="btn btn-sm btn-primary">
                              <i class="fa fa-edit"></i>
                           Get Attandence Record
                           </a>
                           <a href="{{ route('Edit.Customer.Employee' , $user->id) }}" class="btn btn-sm btn-primary">
                              <i class="fa fa-edit"></i>
                           Edit Seller
                           </a>
                           <a href="{{ route('delete.Customer.Employee' , $user->id) }}" class="btn btn-sm btn-danger">
                                                         <i class="fa fa-trash"></i>
                                                      Delete Seller
                                                      </a>

                           </a>
                           <a href="{{ route('get.Employee.Sellary' , $user->id) }}" class="btn btn-sm btn-primary">
                              <i class="fa fa-edit"></i>
                           Get Employee Sellary
                           </a>
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <!-- Hover table card end -->
   </div>
</div>

@endsection
@push('scripts')
<script type="text/javascript">
</script>
@endpush