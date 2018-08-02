<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="js">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $title;?> </title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <!-- Favicon -->
    <link rel="icon" href="<?php echo base_url();?>assets/img/favicon.png" sizes="32x32" type="image/png">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/css/style-responsive.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/css/default.css" rel="stylesheet" id="theme"/>
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/amcharts/export.css" type="text/css" media="all"/>
    <link href="<?php echo base_url();?>assets/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/switchery/switchery.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/css/timepicker.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap-editable/bootstrap-editable.css">
    <!-- ================== END PAGE LEVEL STYLE ================== -->
    <link href="<?php echo base_url();?>assets/plugins/toastr/toastr.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="<?php echo base_url();?>assets/plugins/custombox/custombox.min.css" rel="stylesheet"/>
</head>

<body class="">
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
<div id="pvr-container" class="fade fixed_sidebar fixed_header">
    <!-- begin #header -->
    <div id="header" class="header navbar navbar-default navbar-fixed-top">
        <!-- begin container-fluid -->
        <div class="container-fluid">
            <!-- begin mobile sidebar expand / collapse button -->
            <div class="row">
            <div class="navbar-header">
                <a href="<?php echo base_url(); ?>index.php/dashboard" class="navbar-brand f-w-500">
                    <img class="w-in-22" src="<?php echo base_url();?>assets/img/logo.png" alt="logo"> <span class="m-l-8">
                    Madhumitra</span></a>
                <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <!-- end mobile sidebar expand / collapse button -->

            <!-- begin navbar-collapse -->
            <div class="collapse navbar-collapse pull-left" id="top-navbar">
                <ul class="nav navbar-nav">
                    <li data-click="sidebar-minify">
                        <a href="javascript:void(0)">
                            <i class="material-icons">menu</i>
                        </a>
                    </li>
                    <li class="page_heading">
                        <h4><?php if ($title)
                            { 
                                echo $title; 
                            }
                        else
                            { 
                                echo 'Madhumitra'; 
                            }
                            ?>
                        </h4>
                    </li>
                </ul>
            </div>
            <!-- end navbar-collapse -->

            <!-- begin header navigation right -->
            <ul class="nav navbar-nav navbar-right hidden-xs">
                
                <li class="dropdown navbar-user">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="material-icons m-r-5">account_box</i>
                        <span class="hidden-xs"><?php echo ucfirst($this->session->userdata['USER_NAME']);?></span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu animated fadeInDown">
                        <li class="arrow"></li>
                        <!-- <li>
                            <a href="javascript:void(0)">
                                <span class="badge badge-success pull-right m-t-3">2</span>
                                <i class="material-icons">mail_outline</i> Message
                            </a>
                        </li> -->
                        <li>

                            <a href="<?php echo base_url();?>index.php/user/editUser/<?php echo $this->session->userdata['USER_ID'];?>">
                                <i class="material-icons">account_circle</i> Profile
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url();?>index.php/user/userSettings">
                                <i class="material-icons">settings</i> Change Password
                            </a>
                        </li>
                        <li class="divider"></li>
                        <!-- <li>
                            <a href="javascript:void(0)">
                                <i class="material-icons">lock_outline</i> Lock Screen
                            </a>
                        </li> -->
                        <li>
                            <a href="<?php echo base_url();?>index.php/login/logout">
                                <i class="material-icons">power_settings_new</i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- end header navigation right -->
        </div>
        </div>
        <!-- end container-fluid -->
    </div>
    <!-- end #header -->