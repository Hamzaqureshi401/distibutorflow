@extends('layouts.app')
@section('title') All Areas @endsection
@section('content')
<?php error_reporting(0) ?>
<div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">All Areas</h5>
            <p class="text-muted m-b-10 text-center">User For Product And Customer</p>
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
        <i class="fa fa-table"></i> Area List
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-custom-th table-datatable" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Customer Linked</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($areas as $a)
              <tr>
                <td>{{ $loop->index + 1  }}</td>
                <td>{{ $a->name }}</td>
                <td>{{ sizeof($a->findcustomer($a->id)) }}</td>
                <td>
                 
                  @if(Auth::user()->role < 3)
                   <a href="{{ route('edit.area' , $a->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit">Edit Area</i></a>
                   @if(sizeof($a->findcustomer($a->id)) == 0)
                  <a href="{{ route('delete.area' , $a->id) }}" class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i>Delete Area</a>
                  @else 
                   <a class="btn btn-sm btn-danger delete-btn waves-effect" data-type="inverse" data-animation-in="animated rotateInDownRight" style="color: white;" data-animation-out="animated rotateOutUpRight" disabled="">
                              <i class="fa fa-warning"></i>
                           Can't Delete<br>
                            Customers Linked!
                           </a>
                  @endif
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