<!DOCTYPE html>
<html class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Login | Energy Smart</title>
    <meta content="Admin Dashboard" name="description">
    <meta content="ThemeDesign" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo __HOST__; ?>/assets/scss/style.css">
    <link href="<?php echo __HOST__; ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.9.32/css/materialdesignicons.min.css">

</head>


<body style="overflow: visible; background: #fafafa;">

<!-- Begin page -->
<div class="accountbg"></div>
<div class="wrapper-page">

    <div class="card">
        <div class="card-block">

            <h3 class="text-center mt-0 m-b-15">
                <a href="index.html" class="logo logo-admin"><img src="<?php echo __HOST__; ?>/assets/images/logo.png" alt="logo"></a>
            </h3>

            <h4 class="text-muted text-center font-18"><b>Sign In</b></h4>

            <div class="p-3">
                <form class="form-horizontal m-t-20 m-t-md" action="" method="post">

                    <div class="form-group row">
                        <div class="col-12 spec-log-cl">
                            <input class="form-control" type="text" name="login" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 spec-log-cl">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 spec-log-cl">
                            <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                                <input type="checkbox" class="custom-control-input custom control indicator" id="rem" name="rem_sys">
                                <span class="custom-control-description" style="font-weight:normal;">Remember me</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-20">
                        <div class="col-12 spec-log-cl">
                            <button class="btn btn-block waves-effect waves-light spec-log-but" type="submit" style="color:white;">Log In</button>
                        </div>
                    </div>
                </form>
                <?php extract($data); ?>
                <?php if($login_status=="access_granted") { ?>
                    <p style="color:green">Success</p>
                <?php } elseif($login_status=="access_denied") { ?>
                    <p style="color:red" class="text-center">Wrong login or password</p>
                <?php } ?>
            </div>

        </div>
    </div>
</div>



<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/tether.min.js"></script><!-- Tether for Bootstrap -->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/modernizr.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<!-- App js -->
<script src="assets/js/app.js"></script>


</body></html>