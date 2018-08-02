<!DOCTYPE html>
<html lang="en" class="js">
<head>
    <meta charset="utf-8"/>
    <title>Madhumitra | Forgot Password</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url();?>assets/img/favicon.png" sizes="32x32" type="image/png">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/css/essential.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/animate_css/animate.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/plugins/page_login/css/demo.css"/>
    <!-- ================== END PAGE LEVEL STYLE ================== -->
    <style>
     .error{
      color:red;
     }
    </style>
</head>
<body class="pvr_authentication theme-orange" style="overflow: auto;">
<!-- begin #page-loader -->
<div class="preloader">
    <div class="loading">
        <h2>
            Loading...
        </h2>
        <span class="progress"></span>
    </div>
</div>
<!-- end #page-loader -->

<!-- begin #pvr-container -->
<main>
    <!-- begin #intro -->
    <div class="content content--intro">
        <div class="frame">
            <header class="pvr__header">
                <h1 class="pvr__header__title">Madhumitra</h1>
            </header>
        </div>
        <div class="content__inner">
            <h1 class="content__title">Madhumitra</h1>
            <h3 class="content__subtitle"></h3>
            <a href="javascript:void(0)" class="enter">enter</a>
        </div>
        <div class="shape-wrap">
            <svg class="shape" width="100%" height="100vh" preserveAspectRatio="none" viewBox="0 0 1440 800" >
                <path d="M -44,-50 C -52.71,28.52 15.86,8.186 184,14.69 383.3,22.39 462.5,12.58 638,14 835.5,15.6 987,6.4 1194,13.86 1661,30.68 1652,-36.74 1582,-140.1 1512,-243.5 15.88,-589.5 -44,-50 Z" pathdata:id="M -44,-50 C -137.1,117.4 67.86,445.5 236,452 435.3,459.7 500.5,242.6 676,244 873.5,245.6 957,522.4 1154,594 1593,753.7 1793,226.3 1582,-126 1371,-478.3 219.8,-524.2 -44,-50 Z"></path>
            </svg>
        </div>
    </div>
    <!-- end #intro -->

    <!-- begin #login -->
    <div class="content content--fixed" style="background-image: url('http://via.placeholder.com/1920x1080');">
        <div class="content__inner">
            <div class="auth animated fadeInDownBig">
                <div class="pvr_card pvr_registration">
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="header">
                                    <div class="logo m-t-15">
                                        <img class="w-in-22" src="<?php echo base_url();?>assets/img/logo.png" alt="logo">
                                    </div>
                                    <h1 class="text-white">Madhumitra</h1>
                                </div>
                            </div>
                            <form class="col-lg-12" id="forget_password" action="<?php echo base_url();?>index.php/login/getPassword" method="POST">
                                <h5 class="title f-s-20 f-w-500">Forgot Password?</h5>
                                <div class="form-group-pvr form-float">
                                    <div class="form-line-pvr">
                                        <input type="email" name="email_id" class="form-control" value="<?php echo set_value('email_id') ?>">
                                        <label class="form-label">Enter Registered Email Id</label>
                                    </div>
                                    <span class="error"><?php echo form_error('email_id'); ?></span>
                                </div>
                            <div class="col-lg-12 m-t-10">
                                <button type="submit" class="btn btn-success waves-effect btnforget">Request Password</button>
                            </div>
                             </form>
                            <div class="col-lg-12 m-t-20">
                                <a class="" href="<?php echo base_url();?>index.php/login">Sign in?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end #login -->
</main>
<!-- end #pvr-container -->

<!-- ================== BEGIN BASE JS ================== -->
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery/jquery-migrate-1.4.1.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="<?php echo base_url();?>assets/crossbrowserjs/html5shiv.js"></script>
<script src="<?php echo base_url();?>assets/crossbrowserjs/respond.min.js"></script>
<script src="<?php echo base_url();?>assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="<?php echo base_url();?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-cookie/jquery.cookie.js"></script>
<script src="<?php echo base_url();?>assets/plugins/jquery-cookie/js.cookie.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo base_url();?>assets/plugins/page_login/js/imagesloaded.pkgd.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/page_login/js/charming.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/page_login/js/anime.min.js"></script>
<script src="<?php echo base_url();?>assets/js/pvr_ess_registration.js"></script>
<script src="<?php echo base_url();?>assets/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">
toastr.options = {
      "closeButton": false,
      "positionClass": "toast-top-center",
      "timeOut": 2000
  }

<?php if($this->session->flashdata('success')){ ?>
    toastr.success("<?php echo $this->session->flashdata('success'); ?>");
<?php }else if($this->session->flashdata('error')){  ?>
    toastr.error("<?php echo $this->session->flashdata('error'); ?>");
<?php } ?>

</script>
<!-- ================== END PAGE LEVEL JS ================== -->
</body>
</html>
