@extends('layouts.app')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<style>
   .chosen-single{
   height: 40px !important;
   line-height: 36px !important;
   }
   .chosen-container-single .chosen-single div{
   top: 9px !important;
   }
   .create-invoice-section{
   display: none;
   }
</style>
@endpush
@section('title') Create Sale @endsection
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center m-auto">Attandence</h5>
      <p class="text-muted m-b-10 text-center">Mark Or Un Mark</p>
      <p class="text-muted m-b-10 text-center" id="sellery">Total sellary = </p>
      <ul class="breadcrumb-title b-t-default p-t-10">
         <li class="breadcrumb-item">
            <div class="d-none in">
               <button class="btn btn-sm btn-primary btn-block  check-in">Check in</button>
            </div>
            <div class="d-none out">
               <button class="btn btn-sm btn-danger btn-block check-out">Check Out</button>
            </div>
         </li>
      </ul>
      <div>
         <a style="color: white; margin-left: 5px;" href="{{ route('Get.Attendence.Record' , Auth::id()) }}" class="btn btn-sm btn-primary pull-right">
         <i class="fa fa-edit"></i>
         Get Attandence
         </a>
         <a style="color: white;" href="{{ route('get.Employee.Sellary' , Auth::id()) }}" class="btn btn-sm btn-primary pull-right">
         <i class="fa fa-edit"></i>
         Get Sellary
         </a>
      </div>
      <p id="demo"></p>
      <div class="d-none">
         <video id="video" width=400 height=400 id="video" controls autoplay></video>
         <p>
            Screenshots : 
         <p>
            <canvas  id="myCanvas" width="400" height="350"></canvas>
      </div>
      <form method="post" id="img-form" multiparts="">
         <meta name="csrf-token" content="{{ csrf_token() }}">
         {{ csrf_field() }}
      </form>
   </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/NoSleep.min.js') }}">
   
</script>

@include('pos.image_handeling')
@endpush