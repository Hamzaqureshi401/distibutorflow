           <!-- social statustic end -->
            <!-- users visite and profile start -->
            <a href="{{ route('get.profile') }}">
            <div class="col-md-4">
               <div class="card user-card">
                  <div class="card-header">
                     <h5>Profile</h5>
                  </div>
                  <div class="card-block">
                     <div class="usre-image">
                         @if (empty($profile['profile']->img))
                            <img src="{{ asset('assets/images/2b1ba07a56167735eb206ef0088fa358f90aeb69.png') }}" class="img-radius" alt="user avatar">
                            @else
                            <img src="{{ asset($profile['profile']->img) }}" class="img-radius" alt="user avatar">
                            @endif
                     </div>
                     <h6 class="f-w-600 m-t-25 m-b-10">{{ $profile['user']->name }}</h6>
                     <p class="text-muted">{{ $profile['role'] }} | {{ $profile['profile']->gender ?? 'Others'}} | Born {{ $profile['profile']->date_of_birth ?? 'Not Born Yet!'}}</p>
                     <hr/>
                     <p class="text-muted m-t-15">Profile Completed </p>
                     <ul class="list-unstyled activity-leval">
                        <li class="active"></li>
                        <li class="active"></li>
                        <li class="active"></li>
                        <li></li>
                        <li></li>
                     </ul>
                     <div class="bg-c-blue counter-block m-t-10 p-20">
                        <div class="row">
                           <div class="col-4">
                              <i class="ti-comments"></i>
                              <p>0</p>
                           </div>
                           <div class="col-4">
                              <i class="ti-user"></i>
                              <p>0</p>
                           </div>
                           <div class="col-4">
                              <i class="ti-bag"></i>
                              <p>0</p>
                           </div>
                        </div>
                     </div>
                     <p class="m-t-15 text-muted">{{ $profile['profile']->about ?? 'Not Updated!' }}</p>
                     <hr/>
                     <div class="row justify-content-center user-social-link">
                        <div class="col-auto"><a href="#!"><i class="fa fa-facebook text-facebook"></i></a></div>
                        <div class="col-auto"><a href="#!"><i class="fa fa-twitter text-twitter"></i></a></div>
                        <div class="col-auto"><a href="#!"><i class="fa fa-dribbble text-dribbble"></i></a></div>
                     </div>
                  </div>
               </div>
            </div>
            </a>
            <div class="col-md-8">
               <div class="card">
                  <div class="card-header">
                     <h5>Activity Feed This part is in production!</h5>
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
                  <div class="card-block">
                     <ul class="feed-blog">
                        <li class="active-feed">
                           <div class="feed-user-img">
                              <img src="assets/images/avatar-3.jpg" class="img-radius " alt="User-Profile-Image">
                           </div>
                           <h6><span class="label label-danger">File</span> This part is in production! <small class="text-muted">2 hours ago</small></h6>
                           <p class="m-b-15 m-t-15">hii <b> @everone</b>This part is in production!</p>
                           <div class="row">
                              <div class="col-auto text-center">
                                 <img src="assets/images/blog/blog-r-1.jpg" alt="img" class="img-fluid img-100">
                                 <h6 class="m-t-15 m-b-0">Old Scooter</h6>
                                 <p class="text-muted m-b-0"><small>PNG-100KB</small></p>
                              </div>
                              <div class="col-auto text-center">
                                 <img src="assets/images/blog/blog-r-2.jpg" alt="img" class="img-fluid img-100">
                                 <h6 class="m-t-15 m-b-0">Wall Art</h6>
                                 <p class="text-muted m-b-0"><small>PNG-150KB</small></p>
                              </div>
                              <div class="col-auto text-center">
                                 <img src="assets/images/blog/blog-r-3.jpg" alt="img" class="img-fluid img-100">
                                 <h6 class="m-t-15 m-b-0">Microphone</h6>
                                 <p class="text-muted m-b-0"><small>PNG-150KB</small></p>
                              </div>
                           </div>
                        </li>
                        <li class="diactive-feed">
                           <div class="feed-user-img">
                             @if (empty($profile['profile']->img))
                            <img src="{{ asset('assets/images/2b1ba07a56167735eb206ef0088fa358f90aeb69.png') }}" class="img-radius" alt="user avatar">
                            @else
                            <img src="{{ asset($profile['profile']->img) }}" class="img-radius" alt="user avatar">
                            @endif
                           </div>
                           <h6><span class="label label-success">Task</span>Sarah marked the Pending Review: <span class="text-c-green"> Trash Can Icon Design</span><small class="text-muted">2 hours ago</small></h6>
                        </li>
                        <li class="diactive-feed">
                           <div class="feed-user-img">
                              <img src="assets/images/avatar-2.jpg" class="img-radius " alt="User-Profile-Image">
                           </div>
                           <h6><span class="label label-primary">comment</span> abc posted a task:  <span class="text-c-green">Design a new Homepage</span>  <small class="text-muted">6 hours ago</small></h6>
                           <p class="m-b-15 m-t-15"hii <b> @everone</b> Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </li>
                        <li class="active-feed">
                           <div class="feed-user-img">
                              <img src="assets/images/avatar-3.jpg" class="img-radius " alt="User-Profile-Image">
                           </div>
                           <h6><span class="label label-warning">Task</span>Sarah marked : <span class="text-c-green"> do Icon Design</span><small class="text-muted">10 hours ago</small></h6>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <!-- users visite and profile end -->
         </div>
      </div>
      <div id="styleSelector">
      </div>