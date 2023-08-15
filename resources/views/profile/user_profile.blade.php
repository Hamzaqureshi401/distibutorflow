@extends('layouts.app')
@extends('profile.css.user_profile')
@section('content')

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div id="content" class="content content-full-width">
            <!-- begin profile -->
            <div class="profile">
               <div class="profile-header">
                  <!-- BEGIN profile-header-cover -->
                  <div class="profile-header-cover"></div>
                  <!-- END profile-header-cover -->
                  <!-- BEGIN profile-header-content -->
                  <div class="profile-header-content">
                     <!-- BEGIN profile-header-img -->
                     <div class="profile-header-img">
                          @if (empty($profile['profile']->img))
                            <img src="{{ asset('assets/images/2b1ba07a56167735eb206ef0088fa358f90aeb69.png') }}" alt="user avatar">
                            @else
                            <img src="{{ asset($profile['profile']->img) }}" class="profile-header-img" alt="user avatar">
                            @endif
                     </div>
                     <!-- END profile-header-img -->
                     <!-- BEGIN profile-header-info -->
                     <div class="profile-header-info">
                        <h4 class="m-t-10 m-b-5">{{ $profile['user']->name }}</h4>
                        <p class="m-b-10">{{ $profile['role'] }}</p>
                        <a href="{{ route('edit.profile') }}" class="btn btn-sm btn-info mb-2">Edit Profile</a>
                     </div>
                     <!-- END profile-header-info -->
                  </div>
                  <!-- END profile-header-content -->
                  <!-- BEGIN profile-header-tab -->
                  <!-- <ul class="profile-header-tab nav nav-tabs">
                     <li class="nav-item"><a href="#profile-post" class="nav-link active show" data-toggle="tab">POSTS</a></li>
                     <li class="nav-item"><a href="#profile-about" class="nav-link" data-toggle="tab">ABOUT</a></li>
                     <li class="nav-item"><a href="#profile-photos" class="nav-link" data-toggle="tab">PHOTOS</a></li>
                     <li class="nav-item"><a href="#profile-videos" class="nav-link" data-toggle="tab">VIDEOS</a></li>
                     <li class="nav-item"><a href="#profile-friends" class="nav-link" data-toggle="tab">FRIENDS</a></li>
                  </ul> -->
                  <!-- END profile-header-tab -->
               </div>
            </div>
           <!--  Codded Added -->
           <br>
            <div class="card bg-c-blue order-card ">
                     <!-- BEGIN profile-header-img -->
                     <div class="text-center"><h1>Thsi Section Is still in progress</h1><br>
                       
                     </div>
                     <!-- END profile-header-img -->
                     <!-- BEGIN profile-header-info -->
                   
                     <!-- END profile-header-info -->
                  </div>
                  <!-- Code Added End -->
            <!-- end profile -->
            <!-- begin profile-content -->
<!-- This code is deleted -->
            <!-- end profile-content -->
         </div>
      </div>
   </div>
</div>
@endsection
