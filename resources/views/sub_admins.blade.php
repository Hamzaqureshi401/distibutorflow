@extends('layouts.app')
@section('title') Sub Admins @endsection
@section('content')
<?php error_reporting(0) ?>
<div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">All Sub Admin</h5>
            <p class="text-muted m-b-10 text-center">All Company owner</p>
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
  <div class="col-md-12">
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i> Sub Admins List</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-datatable" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>T.Sell</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sub_admins as $sub_admin)
              <tr>
                <td>{{ $loop->index + 1  }}</td>
                <td>{{ $sub_admin->name }}</td>
                <td>{{ $sub_admin->email }}</td>
                <td>{{ $sub_admin->phone }}</td>
                <td>{{ $sub_admin->invoices->sum('subtotal') }}</td>
                <td>
                  <a href="{{ route('edit.subadmin' , $sub_admin->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>Edit Sub Admin</a>
                  <a href="{{ route('delete.subadmin' , $sub_admin->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i>Delete Sub Admin</a>
                  <ul style="display: inline-table;padding-left: 0px;list-style: none;">
                    <li class="dropdown">
                      <a class="dropdown-toggle btn-sm btn-info" id="more" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px;padding-bottom: 7px;text-decoration: none;">
                        more
                      </a>
                      <div class="dropdown-menu" aria-labelledby="more">
                        <a class="dropdown-item" href="{{ route('subadmin.sellers' , $sub_admin->id) }}">Sellers</a>
                        <a class="dropdown-item" href="{{ route('subadmin.customers' , $sub_admin->id) }}">Customers</a>
                        <a class="dropdown-item" href="{{ route('subadmin.invoices' , $sub_admin->id) }}">Invoices</a>
                        <a class="dropdown-item" href="{{ route('subadmin.orders' , $sub_admin->id) }}">Order</a>
                        <a class="dropdown-item" href="{{ route('subadmin.unconfirmedorders' , $sub_admin->id) }}">Unconfirmed Order</a>
                        <a class="dropdown-item" href="{{ route('subadmin.sellerconfirmedorders' , $sub_admin->id) }}">Seller confirmed Order</a>
                        <a class="dropdown-item" href="{{ route('subadmin.sellerconfirmedorders' , $sub_admin->id) }}">Seller confirmed Order</a>
                        <a class="dropdown-item" href="{{ route('subadmin.subadminadminconfirmedorder' , $sub_admin->id) }}">Admin confirmed Order</a>
                      </div>
                    </li>     
                  </ul> 
                  @if($sub_admin->is_blocked == 1)
                  <a href="{{ route('unblock.admin' , [$sub_admin->id , 'unblock']) }}" class="btn btn-sm btn-success approve-btn">Unblock</a>
                  @else
                  <a href="{{ route('unblock.admin' , [$sub_admin->id , 'block']) }}" class="btn btn-sm btn-warning approve-btn">block</a>
                  @endif
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
@endsection