@include('layouts.common_header')
    <meta name="author" content="codedthemes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset('images/OnlyDlogo32-01.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="fix-menu">
        <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
        </div>
    </div>
    <!-- Pre-loader end -->

    <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <div class="login-card card-block auth-body mr-auto ml-auto">
                        <form class="md-float-material" id="login" method="post" action="{{ route('login') }}"> 
                             {{ csrf_field() }}
                           
                            <div class="auth-box">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-left txt-primary">Sign In</h3>
                                         
                                <img src="{{ asset('images/distributor logo.png') }}" alt="logo.png" style="max-width: 30%">
                            
                                    </div>
                                </div>
                                <hr/>
                                <div class="input-group">
                                    <input type="email" id="exampleInputEmail1" class="form-control" placeholder="Your Email Address" name="email">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input type="password" id="exampleInputPassword1" class="form-control" placeholder="Password" name="password">
                                    <span class="md-line"></span>
                                </div>
                                <div><b style="color: red;" class="wrong"></b></div>
                                <div class="row m-t-25 text-left">
                                    <div class="col-sm-7 col-xs-12">
                                        <div class="checkbox-fade fade-in-primary">
                                        <label>
                                            <input type="checkbox" name="remember" value="1">
                                            <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                            <span class="text-inverse">Remember me</span>
                                        </label>
                                    </div>

                                    </div>
                                    
                                    <div class="col-sm-5 col-xs-12 forgot-phone text-right">
                                        <!-- <a href="auth-reset-password.html" class="text-right f-w-600 text-inverse"> Forgot Your Password?</a> -->
                                    </div>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20 verify-user">Sign in</button>
                                        <a href="/forgot-password" style="color: blue;">Forogt Password?</a>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-10">
                                        <p class="text-inverse text-left m-b-0">Thank you and enjoy our website.</p>
                                        <p class="text-inverse text-left"><b>Qureshi Sons</b></p>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <img src="assets/images/auth/Logo-small-bottom.png" alt="small-logo.png">
                                    </div> -->
                                </div>

                            </div>
                        </form><!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <!-- Required Jquery -->
    <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/popper.js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{ asset('assets/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="{{ asset('assets/js/modernizr/modernizr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/modernizr/css-scrollbars.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/common-pages.js') }}"></script>
    <!--  Hamza added  -->
  <script src="assets/js/toastr.min.js"></script>
    <script type="text/javascript">
      @if(session('success'))
      toastr.success("{{ session('success') }}")
      @elseif(session('error'))
      toastr.error("{{ session('error') }}")
      @endif
       $(document).ready(function(){
        $(document).on('click', '.verify-user', function(e) {
             $('.wrong').html("");
        e.preventDefault();         
        var url = "{{ route('verify.user') }}";
        var email = $('#exampleInputEmail1').val();
        var password = $('#exampleInputPassword1').val();
        $.ajax({
            type: 'get',
            url: url,
            data: {'email' : email , 'password' : password},
            headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        "X-Requested-With": "XMLHttpRequest"
    },
            success: function (data) {
                if (data == 1){
                    $('#login').submit();
                    $('.verify-user').addClass('d-none');
                }else{
                    
                    $('.wrong').html(data.message);
                }
              }
          });
        });
      });
    </script>
</body>

</html>
