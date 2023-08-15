@include('layouts.common_header')

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/pages/notification/notification.css') }}" />
        <!-- Google font-->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet" />
        <!-- Required Fremwork -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap/css/bootstrap.min.css') }}" />
        <!-- themify-icons line icon -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/themify-icons/themify-icons.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/font-awesome/css/font-awesome.min.css')}}" />
        <!-- ico font -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}" />
        <!-- ico font -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/icon/icofont/css/icofont.css') }}" />
        <!-- Style.css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
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
                            <!-- resources/views/auth/forgot-password.blade.php -->
                            <form method="POST" class="md-float-material" id="reset-password-form" action="{{ route('password.email') }}">
                                {{ csrf_field() }}
                                <div class="auth-box">
                                    <div class="row m-b-20">
                                        <div class="col-md-12">
                                            <h3 class="text-left txt-primary">Forgot Password</h3>

                                            <img src="{{ asset('images/distributor logo.png') }}" alt="logo.png" style="max-width: 30%;" />
                                        </div>
                                    </div>
                                    <hr />

                                    <div class="input-group">
                                        <!-- <label for="email">Email</label> -->
                                        <input type="email" id="email" class="form-control" placeholder="Your Email Address" name="email" />
                                        <span class="md-line"></span>
                                    </div>
                                    <div><b style="color: red;" class="wrong"></b></div>
                                    <div><b style="color: green;" class="scss"></b></div>

                                    <div class="row m-t-30">
                                        <div class="col-md-12">
                                            <button type="submit" id="reset-password-button" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20 verify-user">Send Password Reset Link</button>
                                        </div>
                                    </div>
                                    <hr />
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

        <script>
            $(document).ready(function () {
                $("#reset-password-button").click(function () {
                    // Disable the button to prevent multiple submissions
                    $(this).prop("disabled", true);
                    $(".scss").html("");
                    $(".wrong").html("");

                    // Get the form data
                    var formData = $("#reset-password-form").serialize();

                    // Send the AJAX request
                    $.ajax({
                        url: $("#reset-password-form").attr("action"),
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        success: function (response) {
                            if (response.message) {
                                // Password reset link email sent successfully
                                // You can perform any additional actions here
                                // For example, show a success message
                                $(".scss").html(response.message);
                                // alert('Password reset link email sent successfully.');

                                window.location.href = "/login"; // Redirect to login page
                            } else {
                                // Handle errors
                                // For example, display an error message
                                alert("An error occurred. Please try again.");
                                $("#reset-password-button").prop("disabled", false); // Enable the button again
                            }
                        },
                        error: function (error) {
                            console.log("An error occurred:", error);
                            if (error.responseJSON && error.responseJSON.message) {
                                const errorMessage = error.responseJSON.errors.email[0];
                                $(".wrong").html(errorMessage);
                            } else {
                                console.log("Error message not available.");
                                $(".wrong").html("Error message not available.");
                            }
                            $("#reset-password-button").prop("disabled", false); // Enable the button again
                        },
                    });
                });
            });
        </script>
    </body>
</html>
