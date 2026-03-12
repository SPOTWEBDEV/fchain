<?php

include('../../server/connection.php');
include('../../server/auth/admin/index.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo  $sitename ?>- Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../source/assets/img/favicon.ico" />
    <link href="../source/assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="../source/assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="../source/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/structure.css" rel="stylesheet" type="text/css" class="structure" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="../source/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="../source/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css"
        class="dashboard-analytics" />
    <link rel="stylesheet" type="text/css" href="../source/plugins/dropify/dropify.min.css">
    <link href="../source/assets/css/users/account-setting.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/components/custom-modal.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../source/plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../source/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="../source/assets/css/forms/switches.css">
    <link rel="stylesheet" type="text/css" href="../source/plugins/table/datatable/dt-global_style.css">
    <link rel="stylesheet" href="../source/assets/css/card/displayCard.css">

    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="../source/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="../source/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="../source/assets/css/components/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <script src="../source/plugins/sweetalerts/promise-polyfill.js"></script>
    <script src="../source/assets/js/libs/jquery-3.1.1.min.js"></script>

</head>

<body class="dashboard-analytics">

    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    <?php include("../includes/navbar.php")  ?>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <?php include("../includes/sidebar.php")  ?>
        <!--  END SIDEBAR  -->





        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="page-header">
                    <div class="page-title">
                        <h3>Admin Analytics</h3>
                    </div>
                </div>



                <div class="row layout-top-spacing">

                    <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-one">
                            <div class="widget-heading">
                                <h6 class="">Users Statistics</h6>
                            </div>


                            <?php

                            $total_user = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM `users`"));

                            ?>

                            <div class="w-chart">
                                <div class="w-chart-section">
                                    <div class="w-detail">
                                        <p class="w-title">Total Users</p>
                                        <p class="w-stats"><?php echo $total_user; ?></p>
                                    </div>
                                    <div class="w-chart-render-one">
                                        <div id="total-users"></div>
                                    </div>
                                </div>


                                <?php

                                $total_approved_deposit = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM `deposit` WHERE `status`='approved'"));

                                ?>

                                <div class="w-chart-section">
                                    <div class="w-detail">
                                        <p class="w-title">Total Approved deposit</p>
                                        <p class="w-stats"><?php echo $total_approved_deposit ?></p>
                                    </div>
                                    <div class="w-chart-render-one">
                                        <div id="paid-visits"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value"><?php 

                                         $deposit = mysqli_fetch_assoc(mysqli_query($connection, "SELECT  sum(amount) as amount FROM `deposit` WHERE `status`='approved'"));

                                         echo $deposit['amount'];
                                        
                                        ?></h6>
                                        <p class="">Total Deposit Card </p>
                                        
                                    </div>
                                    <div class="">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-home">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-secondary" role="progressbar"
                                        style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value"><?php 

                                         $deposit = mysqli_fetch_assoc(mysqli_query($connection, "SELECT  sum(profit) as profit FROM `user_orders`"));

                                         echo '$' . $deposit['profit'];
                                        
                                        ?></h6>
                                        <p class="">Total Money Made </p>
                                        
                                    </div>
                                    <div class="">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-home">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-secondary" role="progressbar"
                                        style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value"><?php 

                                         $deposit = mysqli_fetch_assoc(mysqli_query($connection, "SELECT  sum(third_party_charge) as third_party_charge  FROM `user_orders`"));

                                         echo '$' . $deposit['third_party_charge'];
                                        
                                        ?></h6>
                                        <p class="">Total Money Expense </p>
                                        
                                    </div>
                                    <div class="">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-home">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-secondary" role="progressbar"
                                        style="width: 57%" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

                <div class="footer-wrapper">
                    <div class="footer-section f-section-1">
                        <p class="">Copyright © 2022 <a target="_blank" href="/"><?php echo  $sitename ?></a>, All rights
                            reserved.</p>
                    </div>
                    <div class="footer-section f-section-2">
                        <p class=""><?php echo  $sitename ?> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg></p>
                    </div>
                </div>
            </div>
            <!--  END CONTENT AREA  -->


        </div>
        <!-- END MAIN CONTAINER -->

        <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
        <script src="../source/bootstrap/js/popper.min.js"></script>
        <script src="../source/bootstrap/js/bootstrap.min.js"></script>
        <script src="../source/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="../source/assets/js/app.js"></script>
        <script>
            $(document).ready(function() {
                App.init();
            });
        </script>
        <script src="../source/assets/js/custom.js"></script>
        <!-- END GLOBAL MANDATORY SCRIPTS -->

        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
        <script src="../source/plugins/apex/apexcharts.min.js"></script>
        <script src="../source/assets/js/dashboard/dash_1.js"></script>
        <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

        <script src="../source/plugins/dropify/dropify.min.js"></script>
        <script src="../source/plugins/blockui/jquery.blockUI.min.js"></script>
        <!-- <script src="plugins/tagInput/tags-input.js"></script> -->
        <script src="../source/assets/js/users/account-settings.js"></script>

        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="../source/plugins/highlight/highlight.pack.js"></script>
        <script src="../source/plugins/table/datatable/datatables.js"></script>
        <script src="../source/plugins/select2/select2.min.js"></script>
        <script src="../source/plugins/select2/custom-select2.js"></script>

        <script src="../source/plugins/sweetalerts/sweetalert2.min.js"></script>
        <script src="../source/plugins/sweetalerts/custom-sweetalert.js"></script>
        <script>
            var ss = $(".basic").select2({
                tags: true,
            });
        </script>

        <script>
            $('input').attr('autocomplete', 'off');
        </script>
        <script>
            $('#default-ordering').DataTable({
                "oLanguage": {
                    "oPaginate": {
                        "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    "sInfo": "Showing page _PAGE_ of _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                },
                // "order": [[ 3, "desc" ]],
                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 7,
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass(' pagination-style-13 pagination-bordered mb-5');
                }
            });
        </script>

        <script>
            $(".edit-crypto").click(function(e) {
                e.preventDefault();
                $("#crypto_name").val($(this).data('name'));
                $("#wallet_address").val($(this).data('wallet-address'));
                $("#crypto_id").val($(this).data('id'));
                $(".show-modal").click();
            });
        </script>

        <script>
            function toast(msg, type) {
                return swal({
                    type: type,
                    title: type,
                    text: msg,
                    padding: "2em"
                });
            }

            $(".delete-crypto-currency").on('click', function(e) {
                e.preventDefault();
                let crypto_id = $(this).data('id');

                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    padding: '2em'
                }).then(function(result) {
                    if (result.value) {

                        $.ajax({
                            url: 'https://santaaccessfinance.netadmin/crypto-currrency.php',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                'delete_crypto_currency': '',
                                'crypto_currency_id': crypto_id
                            },
                            timeout: 45000,
                            success: function(data) {
                                console.log(data);


                                if (data.error == 1) {
                                    toast(data.msg, 'success');
                                } else {
                                    toast(data.msg, 'error');
                                }

                                setTimeout(function() {
                                    window.location.href = 'https://santaaccessfinance.netadmin/crypto-currrency.php';
                                }, 1000)
                            },
                            error: function(er) {
                                // console.log(er.responseText);
                                toast('error network', 'error');
                            }
                        });

                    }
                })

            });
        </script>


        <!-- END PAGE LEVEL SCRIPTS -->

</body>

</html>