@include('layouts.common_header')

     <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/notification/notification.css') }}">
    <!-- Google font--><link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/font-awesome/css/font-awesome.min.css')}}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <style>
  .error {
    font-weight: bold;
    color: red;
  }
</style>

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
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <div class="signup-card card-block auth-body mr-auto ml-auto">
                        <form class="md-float-material">
                            {{ csrf_field() }}
                            <div class="text-center">
                                <!-- <img src="{{ asset('images/distributor logo.png') }}" alt="logo.png"> -->
                            </div>
                            <div class="auth-box">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center txt-primary">Sign up</h3>
                                         <img src="{{ asset('images/distributor logo.png') }}" alt="logo.png" style="max-width: 30%">
                                    </div>
                                </div>
                                <hr/>
                               <div class="input-group mb-3 bg-primary d-flex align-items-center justify-content-center">
                                  <span class="input-group-text mx-3"><i class="feather ti-user"></i></span>
                                  <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <span class="error" id="nameError"></span>

                                <div class="input-group mb-3 bg-primary d-flex align-items-center justify-content-center">
                                  <span class="input-group-text mx-3"><i class="feather ti-email"></i></span>
                                  <input type="text" id="email" name="email" class="form-control" placeholder="Email" required>
                                  </div>
                                  <span class="error" id="emailError"></span>
                                
                                 <div><b style="color: red;" id="user-not-unique"></b></div>
                                 @if($errors->has('email'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('email') }}
                                </div>
                                @endif
                              
                                <div class="input-group mb-3 bg-primary d-flex align-items-center justify-content-center">
                                  <span class="input-group-text mx-3"><i class="feather ti-lock"></i></span>
                                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                                  </div>
                                  <span class="error" id="passwordError"></span>
                                
                                <div class="input-group mb-3 bg-primary d-flex align-items-center justify-content-center">
                                  <span class="input-group-text mx-3"><i class="feather ti-lock"></i></span>
                                  <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
                                  </div>
                                  <span class="error" id="cpasswordError"></span>
                                
                                <div class="input-group mb-3 bg-primary d-flex align-items-center justify-content-center">
                                  <span class="input-group-text mx-3"><i class="feather ti-more-alt"></i></span>
                                  <input type="text" name="pin" class="form-control" placeholder="PinCode" required>
                                  </div>
                                  <span class="error" id="pinError"></span>
                                
                                <div class="input-group mb-3 bg-primary d-flex align-items-center justify-content-center">
                                  <span class="input-group-text mx-3"><i class="feather ti-mobile"></i></span>
                                  <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                                  </div>
                                  <span class="error" id="phoneError"></span>
                                

                               <!--  <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Your Email Address">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Choose Password">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Confirm Password">
                                    <span class="md-line"></span>
                                </div> -->
                                <div class="row m-t-25 text-left">
                                    <div class="col-md-12">
                                        <div class="checkbox-fade fade-in-primary">
                                            <label>
                                                <input type="checkbox" name="terms" value="agree" >
                                                <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">I read and accept <a href="#">Terms &amp; Conditions.</a></span>
                                            </label>
                                        </div>
                                        <span class="error" id="termsError"></span>
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <div class="checkbox-fade fade-in-primary">
                                            <label>
                                                <input type="checkbox" value="">
                                                <span class="cr"><i class="cr-icon icofont icofont-ui-check txt-primary"></i></span>
                                                <span class="text-inverse">Send me the <a href="#!">Newsletter</a> weekly.</span>
                                            </label>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" id="button" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Sign up now.</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span style="color: black;">Already Have An Account </span> <a href="/login" style="color: blue">Login</a>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-10">
                                        <p class="text-inverse text-left m-b-0">Thank you and enjoy our website.</p>
                                        <p class="text-inverse text-left"><b>Your Authentication Team</b></p>
                                    </div>
                                    <div class="col-md-2">
                                        <!-- <img src="{{ asset('images/distributor logo.png') }}" alt="small-logo.png"> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>

    <script type="text/javascript" src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/popper.js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="{{ asset('assets/js/jquery-slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="{{ asset('assets/js/modernizr/modernizr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/modernizr/css-scrollbars.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/pages/icon-modal.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/bootstrap-growl.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/script.js')}}"></script>
    <script src="{{ asset('assets/js/pcoded.min.js')}}"></script>
    <script src="{{ asset('assets/js/vartical-demo.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>


    <script type="text/javascript" src="{{ asset('assets/js/common-pages.js') }}"></script>
    <script type="text/javascript">

        var btn = '';

        $(function () {
        $('form').on('submit', function (e) {
          e.preventDefault();
          $('.error').empty();
          $('#user-not-unique').html('');
          btn = document.getElementById('button');
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
                $('.error').empty();
                $('#user-not-unique').html('This User Is Already Taken!');
                btn = document.getElementById('button');
                btn.disabled = false;
                  setTimeout(function(){
                   btn.innerText = 'Try Again to Register';
               }, 5000);
                btn.innerText = 'Please resolve error!';
              }
              
              }
          });

      }

      function submitdata() {
  $('#button').addClass('btn-success');
  var btn = document.getElementById('button'); // Assuming you have a button with id="button"

  $.ajax({
    type: 'post',
    url: '{{ route('register.user') }}',
    data: $('form').serialize(),
    success: function(data) {
      console.log(data);
      var nType = "success";
      var title = "Success ";
      var msg = data.message;

      if (msg == "Password Did Not Matched!" || msg == "Something Went Wrong!") {
        var nType = "error";
        var title = "Failed ";
        if(msg == 'Password Did Not Matched!'){
            $('#passwordError').html(msg);
            $('#cpasswordError').html(msg);
        }
      } else if (msg == "User Added!") {
        notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut, title, msg);
        window.location.href = "/login";
      }

      btn.disabled = false;
      setTimeout(function() {
        btn.innerText = 'Try Again to Register';
      }, 5000);
      btn.innerText = 'Please resolve error!';

      notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut, title, msg);
    },
    error: function(xhr, status, error) {
      // This function will handle errors returned by the server
      var errors = xhr.responseJSON.errors;
      if (errors) {
        // Display validation errors
        displayErrors(errors);
      } else {
        // Handle other types of errors
        console.log("An error occurred:", status, error);
      }

      btn.disabled = false;
      setTimeout(function() {
        btn.innerText = 'Try Again to Register';
      }, 5000);
      btn.innerText = 'Please resolve error!';
    }
  });
}

// Function to display errors
function displayErrors(errors) {
  // Clear previous error messages
  for (const field in errors) {
    const errorContainer = $(`#${field}Error`);
    errorContainer.text(errors[field][0]); // Display the first error message for each field
  }
}


      function notify(from, align, icon, type, animIn, animOut ,title , msg){
            $.growl({
            icon: icon,
            title: title,
            message: msg,
            url: ''
            },{
            element: 'body',
            type: type,
            allow_dismiss: true,
            placement: {
                from: from,
                align: align
            },
            offset: {
                x: 30,
                y: 30
            },
            spacing: 10,
            z_index: 999999,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: false,
            animate: {
                enter: animIn,
                exit: animOut
            },
            icon_type: 'class',
            template: '<div data-growl="container" class="alert" role="alert">' +
            '<button type="button" class="close" data-growl="dismiss">' +
            '<span aria-hidden="true">&times;</span>' +
            '<span class="sr-only">Close</span>' +
            '</button>' +
            '<span data-growl="icon"></span>' +
            '<span data-growl="title"></span>' +
            '<span data-growl="message"></span>' +
            '<a href="#" data-growl="url"></a>' +
            '</div>'
            });
            };
            
            
            var nFrom = "bottom";
            var nAlign = "right";
            var nIcons = "" ;
            var nAnimIn = "animated rotateInDownRight";
            var nAnimOut = "animated rotateOutUpRight";
            
            @if(session('success'))
            
            var nType = "success";
            var title = "Success ";
            var msg = "{{session('success')}}";
            
            notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg)
            
            @elseif(session('error'))
            
            var nType = "danger";
            var title = "Failed ! ";
            var msg = "{{session('error')}}";
            notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut , title , msg)
            @endif
            
            var $window = $(window);
            var nav = $('.fixed-button');
            $window.scroll(function(){
            if ($window.scrollTop() >= 200) {
            nav.addClass('active');
            }
            else {
            nav.removeClass('active');
            }
            });
            
    </script>
</body>

</html>
