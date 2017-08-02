<!DOCTYPE html>
<html>
<head>
    <title>Energy Smart</title>
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta charset="UTF-8">
    <meta name="description" content="Energy Smart system" />

    <!--  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>-->
    <link rel="shortcut icon" type="image/x-icon" href="http://www.energysmart.com.au/wp-content/uploads/2015/05/favicon.png">
    <link href="<?php echo __HOST__; ?>/assets/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet" type="text/css"//>
    <!-- <link href="<?php echo __HOST__; ?>/assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet" type="text/css"//> -->
    <link href="<?php echo __HOST__; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/line-icons/simple-line-icons.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/waves/waves.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/switchery/switchery.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/3d-bold-navigation/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/plugins/slidepushmenus/css/component.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?php echo __HOST__; ?>/assets/plugins/datatables/css/jquery.datatables.min.css">
    <!-- Theme Styles -->
    <link href="<?php echo __HOST__; ?>/assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/css/themes/green.css" class="theme-color" rel="stylesheet" type="text/css"/>
    <link href="<?php echo __HOST__; ?>/assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="<?php echo __HOST__; ?>/assets/plugins/bootstrap-datepicker/css/datepicker.css">
    <link rel="stylesheet" href="<?php echo __HOST__; ?>/css/bootstrap-switch.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo __HOST__; ?>/assets/scss/style.css">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
        div.checker {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="<?php echo __HOST__; ?>/css/style.css" type="text/css" />
    <script src="<?php echo __HOST__; ?>/assets/plugins/3d-bold-navigation/js/modernizr.js"></script>
    <script src="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/js/snap.svg-min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="<?php echo $body_class; ?>">
<!-- Javascripts -->
<script src="<?php echo __HOST__; ?>/assets/plugins/jquery/jquery-2.1.4.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/datatables/js/jquery.datatables.min.js"></script>
<div class="overlay"></div>
<form class="search-form" action="#" method="GET">
    <div class="input-group">
        <input type="text" name="search" class="form-control search-input" placeholder="Search...">
        <span class="input-group-btn">
                    <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button"><i class="fa fa-times"></i></button>
                </span>
    </div><!-- Input Group -->
</form><!-- Search Form -->

<div id="wrapper">

    <div class="left side-menu" style="position: fixed;">
        <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
            <i class="ion-close"></i>
        </button>

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <!--<a href="index.html" class="logo">Admiry</a>-->
                <a href="index.html" class="logo"><img src="<?php echo __HOST__; ?>/assets/images/logo.png" height="42" alt="logo"></a>
            </div>
        </div>

        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 724px;"><div class="sidebar-inner slimscrollleft" style="overflow: hidden; width: auto; height: 724px;">

                <div class="user-details">
                    <div class="text-center">
                        <img src="<?php echo __HOST__; ?>/assets/images/fill-factor-icon.png" alt="avatar" class="rounded-circle">
                    </div>
                    <div class="user-info">
                        <h4 class="font-16"><?php echo strstr($_SESSION["user_name"],' ',true); ?></h4>
                        <span class="text-muted user-status"><i class="fa fa-dot-circle-o text-success"></i> Online</span>
                    </div>
                </div>

                <div id="sidebar-menu">
                    <ul>
                        <li>
                            <a href="/admin/dashboard" class="waves-effect">
                                <i class="mdi mdi-view-dashboard"></i>
                                <span> Dashboard </span>
                            </a>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-buffer"></i> <span> Clients </span> </a>
                            <ul class="sub-menu">
                                <li><a href="/clients/">Clients</a>
                                <li><a href="/clients/managers/">Affiliate Managers</a>

                                <li><a href="/clients/covermap">Map of Cover</a>

                                <li><a href="<?php echo __HOST__; ?>/leads_limits/">Clients Caps</a></li>
                                <li><a href="<?php echo __HOST__; ?>/leads_limits/matches">PostCodes Matches</a></li>
                                <li><a href="<?php echo __HOST__; ?>/clients/sms">SMS service</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-cube-outline"></i> <span> Campaigns </span> </a>
                            <ul class="list-unstyled">
                                <li><a href="/campaigns/">Campaigns Settings</a></li>
                                <li><a href="/campaigns/planing">Affiliates Planning</a></li>
                            </ul>
                        </li>

                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-album"></i> <span> Leads </span> </a>
                            <ul class="list-unstyled">
                                <li><a href="<?php echo __HOST__; ?>/leads/">Leads Delivery</a></li>
                                <li><a href="<?php echo __HOST__; ?>/approvals/">Leads Approvals</a></li>
                                <li><a href="<?php echo __HOST__; ?>/reject/">Leads Rejection</a></li>
                                <li><a href="<?php echo __HOST__; ?>/rerouting/">Lead Rerouting</a></li>
                                <li><a href="<?php echo __HOST__; ?>/leads/distribution/">Leads distribution</a></li>
                                <li><a href="<?php echo __HOST__; ?>/penetration/">Penetration</a></li>
                                <li><a href="<?php echo __HOST__; ?>/leads/csvUpload/">Upload Leads from CSV</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="<?php echo __HOST__; ?>/admin_reports/" class="waves-effect"><i class="mdi mdi-clipboard-outline"></i><span> Reports </span></a>
                        </li>

                        <li>
                            <a href="<?php echo __HOST__; ?>/invoice/" class="waves-effect"><i class="mdi mdi-chart-line"></i><span> Invoices </span></a>
                        </li>

                        <li>
                            <a href="<?php echo __HOST__; ?>/terms/" class="waves-effect"><i class="mdi mdi-format-list-bulleted-type"></i><span> Terms </span></a>
                        </li>

                        <li>
                            <a href="<?php echo __HOST__; ?>/settings/" class="waves-effect"><i class="mdi mdi-google-maps"></i><span> Settings </span></a>
                        </li>

                    </ul>
                </div>
                <div class="clearfix"></div>
            </div><div class="slimScrollBar" style="background: rgb(158, 165, 171); width: 10px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 703.592px;"></div><div class="slimScrollRail" style="width: 10px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div> <!-- end sidebarinner -->
    </div>

    <div class="content-page">
        <div class="content">

            <div class="topbar">

                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="<?php echo __HOST__; ?>/assets/images/fill-factor-icon.png" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                <a class="dropdown-item" href="<?php echo __HOST__; ?>/settings/"><i class="mdi mdi-settings m-r-5 text-muted"></i> Settings</a>
                                <a class="dropdown-item" href="/admin/logout"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                            </div>
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="list-inline-item">
                            <button type="button" class="button-menu-mobile open-left waves-effect">
                                <i class="ion-navicon"></i>
                            </button>
                        </li>
                        <li class="hide-phone list-inline-item app-search">
                            <h3 class="page-title custom-page-title">Dashboard</h3>
                        </li>
                    </ul>

                    <div class="clearfix"></div>

                </nav>

            </div>

            <div class="page-content-wrapper">
                <div class="container">
                    <main class="page-content content-wrap container" style="box-shadow:none;">

                        <div class="page-inner">
                            <div id="main-wrapper">
                                <div class="row">

                                    <?php include 'app/views/'.$content_view; ?>

                                </div><!-- Row -->
                            </div><!-- Main Wrapper -->
                        </div><!-- Page Inner -->
                    </main><!-- Page Content -->
                </div>
            </div>
        </div>
        <footer class="footer">
            &copy; 2012-2017 Energy Smart
        </footer>
    </div>
</div>

<script src="<?php echo __HOST__; ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/pace-master/pace.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/jquery-blockui/jquery.blockui.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/switchery/switchery.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/uniform/jquery.uniform.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/js/classie.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/offcanvasmenueffects/js/main.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/waves/waves.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/3d-bold-navigation/js/main.js"></script>
<script src="<?php echo __HOST__; ?>/assets/js/modern.min.js"></script>
<script src="<?php echo __HOST__; ?>/js/bootstrap-switch.min.js"></script>
<script src="<?php echo __HOST__; ?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>

<script src="<?php echo __HOST__; ?>/assets/js/new_design/dashborad.js"></script>

<!-- App js -->
<script src="<?php echo __HOST__; ?>/assets/js/new_design/app.js"></script>

<script>
    // $(document).ready(function(){
    //   $("select, input:radio, input:file").uniform();
    // });
</script>
</body>
</html>
