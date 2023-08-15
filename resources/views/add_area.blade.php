@extends('layouts.app')

@section('title') Add Area @endsection

@section('content')
<!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Add Area</h5>
            <p class="text-muted m-b-10 text-center">Add New Area For Product</p>
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
        Enter Area Details
      </div>
      <div class="card-body">
        <form method="post" action="{{ route('save.area') }}" onsubmit="disableButton();">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Area Name <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control" type="text" placeholder="Enter Area Name" name="name" required="">
            @if($errors->has('name'))
            <div class="alert alert-danger">{{ $errors->first('name') }}</div>
            @endif
          </div>
          <button id="button" type="submit" class="btn btn-primary btn-block call-processing">Create Area</button>
        </form>
      </div>
    </div>
  </div>
@endsection