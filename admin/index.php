<?php  include("../server/connection.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo  $sitename ?> - Login </title>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo/favicon.png"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="<?php  echo $domain ?>admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo $domain ?>admin/asset/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo $domain ?>admin/asset/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="<?php  echo $domain ?>admin/asset/css/forms/theme-checkbox-radio.css">
    <link rel="stylesheet" type="text/css" href="<?php  echo $domain ?>admin/asset/css/forms/switches.css">
    <link href="<?php  echo $domain ?>admin/asset/css/pages/error/style-400.css" rel="stylesheet" type="text/css" />
    <meta name="description" content="Boostkore helps you grow your social media presence with fast, secure, and affordable boosting services for Instagram, Facebook, TikTok, YouTube, and more.">



    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php  echo $domain ?>admin/asset/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo $domain ?>admin/plugins/animate/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo $domain ?>admin/plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php  echo $domain ?>admin/asset/css/elements/alert.css">
    <script src="<?php  echo $domain ?>admin/plugins/sweetalerts/promise-polyfill.js"></script>
    <link href="<?php  echo $domain ?>admin/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo $domain ?>admin/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo $domain ?>admin/asset/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="<?php  echo $domain ?>admin/asset/js/libs/jquery-3.1.1.min.js"></script>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- jQuery (required by Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <!-- END THEME GLOBAL STYLES -->
    <title>Pin</title>
    <style>
        
        button{
            margin:3px;
        }
        button{
            display: inline-block;
            border:1px solid #0a3bff;
            color: #0022ff;
            border-radius: 30px;
            -webkit-border-radius: 30px;
            -moz-border-radius: 30px;
            font-family: Verdana;
            width: auto;
            height: auto;
            font-size: 16px;
            padding: 10px 17px;
            background-color: #FCFAF9;
        }
        button:hover, button:active{
            border:1px solid #FFFFFF;
            color: #FFFDFC;
            background-color: #FC0000;
        }

        input[type=text], textarea {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            padding: 3px 0px 3px 3px;
            margin: 5px 1px 3px 0px;
            border: 1px solid #DDDDDD;
        }

        input[type=text]:focus, textarea:focus {
            box-shadow: 0 0 5px rgba(250, 0, 0, 1);
            padding: 3px 0px 3px 3px;
            margin: 5px 1px 3px 0px;
            border: 1px solid rgba(250, 0, 0, 1);
        }
    </style>
</head>

<div class="form-container outer">
    <div class="form-form">
        <div class="form-form-wrap">
            <div class="form-container">
                <div class="form-content">

                    <h1 class="">Sign In</h1>
                   
                    <p class="">Log in to your account to continue.</p>
                 <!--   <img src="./<?php  echo $domain ?>admin/asset/settings/favicon.png" class="navbar-logo" alt="logo" width="20%"> -->

                    <form class="text-left" method="POST" id="form">
                        <div class="form">
                            
                            <div id="username-field" class="field-wrapper input">
                                <label for="username">Email</label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                <input id="username" name="email" type="email" class="form-control" placeholder="Account Email" required>
                            </div>

                            <div id="password-field" class="field-wrapper input mb-2">
                                <div class="d-flex justify-content-between">
                                    <label for="password">PASSWORD</label>
                                    <a href="register.php" class="forgot-pass-link">Create New Account</a>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </div>
                            <div class="d-sm-flex justify-content-between">
                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-primary" name="login" value="">Log In</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                            
                            toastr.options = {

                                "closeButton": true,
                                "progressBar": true,
                                "positionClass": "toast-bottom-right", // other options: toast-bottom-left, toast-bottom-right, toast-top-left, toast-top-full-width, toast-bottom-full-width, toast-top-center, toast-bottom-center
                                "timeOut": "2000" // milliseconds before toast disappears
                            }

                            // toastr.success('Login successful!');
                            // toastr.error('Invalid username or password.');
                            // toastr.info('Please enter your details.');
                            // toastr.warning('Your session will expire soon.');

                            let form = document.getElementById('form');
                            form.addEventListener('submit', (event) => {
                                event.preventDefault();
                                let formData = new FormData(form);

                                fetch('<?php echo $domain; ?>server/api/admin/login.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok: ' + response.statusText);
                                    }
                                    return response.json(); // Or .json() if your API sends JSON
                                })
                                .then(data => {

                                    console.log(data);
                                    if (data.status == 'success') {
                                        toastr.success(data.message);
                                        setTimeout(() => {
                                                
                                             location.href = '<?php echo $domain; ?>admin/dashboard/';
                                        }, 3000);

                                    }

                                    if (data.status == 'error') {
                                        toastr.error(data.message);

                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    toastr.error('An error occurred while processing your request.');
                                });
                            });



                    </script>


                </div>
            </div>
        </div>
    </div>
</div>


<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?php  echo $domain ?>admin/bootstrap/js/popper.min.js"></script>
<script src="<?php  echo $domain ?>admin/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php  echo $domain ?>admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?php  echo $domain ?>admin/asset/js/app.js"></script>

<!-- END GLOBAL MANDATORY SCRIPTS -->
<script src="<?php  echo $domain ?>admin/asset/js/authentication/form-2.js"></script>
<script src="<?php  echo $domain ?>admin/plugins/highlight/highlight.pack.js"></script>
<script src="<?php  echo $domain ?>admin/asset/js/custom.js"></script>
<!-- END GLOBAL MANDATORY STYLES -->
<script src="<?php  echo $domain ?>admin/plugins/notification/snackbar/snackbar.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!--  BEGIN CUSTOM SCRIPTS FILE  -->
<script src="<?php  echo $domain ?>admin/asset/js/components/notification/custom-snackbar.js"></script>
<!--  END CUSTOM SCRIPTS FILE  -->

<!-- BEGIN THEME GLOBAL STYLE -->
<script src="<?php  echo $domain ?>admin/asset/js/scrollspyNav.js"></script>
<script src="<?php  echo $domain ?>admin/plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="<?php  echo $domain ?>admin/plugins/sweetalerts/custom-sweetalert.js"></script>
<!-- END THEME GLOBAL STYLE -->
<script>
    $(function() {
        $('#datepicker').keypress(function(event) {
            event.preventDefault();
            return false;
        });
    });
</script>


</body>
</html>
