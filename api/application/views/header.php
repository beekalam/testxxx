<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" dir="rtl">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
    <title><?php echo isset($title) ? $title : ""; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="#1 selling multi-purpose bootstrap admin theme sold in themeforest marketplace packed with angularjs, material design, rtl support with over thausands of templates and ui elements and plugins to power any type of web applications including saas and admin dashboards. Preview page ofRTL  Theme #2 for statistics, charts, recent events and reports"
          name="description"/>
    <meta content="" name="author"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" /> -->
    <link href="<?php echo base_url() ?>assets/fonts/fontiran/css/fontiran.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/bootstrap/css/bootstrap-rtl.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css"
          rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->


    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="<?php echo base_url() ?>assets/global/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css"
          rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet"
          type="text/css"/>

    <!--<link href="-->
    <?php //echo base_url() ?><!--assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet"-->
    <link href="<?php echo base_url() ?>assets/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="<?php echo base_url() ?>assets/global/css/components-rtl.min.css" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/css/plugins-rtl.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <link href="<?php echo base_url() ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url() ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css"
          rel="stylesheet" type="text/css"/>

    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo base_url() ?>assets/layouts/layout2/css/layout-rtl.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- <link href="<?php echo base_url() ?>assets/layouts/layout2/css/themes/blue-rtl.min.css" rel="stylesheet" type="text/css" id="style_color" /> -->
    <link href="<?php echo base_url() ?>assets/layouts/layout2/css/themes/dark-rtl.min.css" rel="stylesheet"
          type="text/css" id="style_color"/>
    <!-- <link href="<?php echo base_url() ?>assets/layouts/layout2/css/themes/default-rtl.min.css" rel="stylesheet" type="text/css" id="style_color" /> -->

    <!-- <link href="<?php echo base_url() ?>assets/layouts/layout2/css/themes/grey-rtl.min.css" rel="stylesheet" type="text/css" id="style_color" /> -->

    <!-- <link href="<?php echo base_url() ?>assets/layouts/layout2/css/themes/light-rtl.min.css" rel="stylesheet" type="text/css" id="style_color" /> -->

    <link href="<?php echo base_url() ?>assets/layouts/layout2/css/custom-rtl.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
    <script src="<?php echo base_url() ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css'); ?>">
    <script src="<?php echo base_url('assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js'); ?>"></script>
    <style>
        .ltr {
            direction: ltr !important;
        }

        .rtl {
            direction: rtl !important;
        }

        .persian-number {
            font-family: IranSans !important;
        }

        .dataTables_info {
            font-family: IranSans !important;
        }

        .border-radius-5px {
            border-radius: 5px !important;
        }

        .border-radius-10px {
            border-radius: 10px !important;
        }

        .persian-font {
            font-family: IranSans !important;
        }

        /*sweet alert 2 fixes*/
        .swal2-container {
            z-index: 9999 !important;
        }

        .swal2-confirm.btn-success {
            margin-right: 5px !important;
        }

        .swal2-popup {
            width: 42em !important;
            border-radius: 0.3125em !important;
        }

        .swal2-icon.swal2-success .swal2-success-ring {
            border-radius: 50% !important;
        }

        .swal2-popup .swal2-title {
            font-size: 1.875em;
        }

        .swal2-icon {
            border-radius: 50% !important;
        }

        .hidden {
            display: none !important;
        }

    </style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo base_url("ui/dashboard"); ?>">
                <img src="<?php echo base_url() ?>assets/ic_icon.png" alt="logo" class="logo-default"
                     style="margin-top:3px;width:85px;height:62px;"/> </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE ACTIONS -->

        <!-- BEGIN PAGE TOP -->
        <div class="page-top">

            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class below "dropdown-extended" to change the dropdown styte -->
                    <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <img alt="" class="img-circle"
                                 src="<?php echo $_SESSION['profile_img']; ?>"/>
                            <span class="username username-hide-on-mobile">
                                        <?php echo $this->session->userdata('name'); ?>
                                        <?php echo isset($isadmin) ? "(مدیر)" : ""; ?>
                                    </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="<?php
                                echo base_url('users/profile') . "?id=" . $this->session->userdata("userid"); ?>"
                                   style="font-family: BYekan;">
                                    <i class="icon-user"></i> پروفایل</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url('login/logout') ?>" style="font-family: BYekan;">
                                    <i class="icon-key"></i> خروج </a>
                            </li>

                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->

                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<!-- END HEADER & CONTENT DIVIDER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <!-- END SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false"
                data-auto-scroll="true" data-slide-speed="200">
                <li class="nav-item start" id="m-dashboard">
                    <a href="<?php echo base_url('ui/dashboard'); ?>" class="nav-link ">
                        <i class="icon-home"></i>
                        <span class="title">داشبورد</span>
                        <span class="selected"></span>
                    </a>
                </li>

                <?php if (false): ?>
                    <li class="nav-item start" id="m-waiting-orders">
                        <a href="<?php echo base_url('ui/orders/index'); ?>" class="nav-link ">
                            <i class="icon-wallet"></i>
                            <span class="title">خریدهای در انتظار تایید</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (false): ?>
                    <li class="nav-item start" id="m-manage-customers">
                        <a href="<?php echo base_url('ui/customers'); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">مدیریت مشتری ها</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif ?>



                <?php if (false): ?>
                    <li class="nav-item start" id="m-manage-products">
                        <a href="<?php echo base_url('ui/products/index'); ?>" class="nav-link ">
                            <i class="icon-book-open"></i>
                            <span class="title">مدیریت خدمات</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (true): ?>
                    <li class="nav-item start" id="m-manage-categories">
                        <a href="<?php echo base_url('ui/category/index'); ?>" class="nav-link ">
                            <i class="icon-layers"></i>
                            <span class="title">دسته ها</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (false): ?>
                    <li class="nav-item start" id="m-manage-resellers" style="display:none;">
                        <a href="<?php echo base_url('ui/resellers/index'); ?>" class="nav-link ">
                            <i class="icon-users"></i>
                            <span class="title">فروشنده ها</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (true): ?>
                    <li class="nav-item start" id="m-manage-posts">
                        <a href="<?php echo base_url('ui/posts/index'); ?>" class="nav-link ">
                            <i class="icon-basket"></i>
                            <span class="title">پست ها</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (true): ?>
                    <li class="nav-item start" id="m-comments" style="">
                        <a href="<?php echo base_url('ui/comment/index'); ?>" class="nav-link ">
                            <i class="icon-basket"></i>
                            <span class="title">نظرات</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>



                <?php if (false): ?>
                    <li class="nav-item start" id="m-vehicles">
                        <a href="<?php echo base_url('ui/vehicles'); ?>" class="nav-link ">
                            <i class="icon-picture"></i>
                            <span class="title">خودروها</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (false): ?>
                    <li class="nav-item start" id="m-settings">
                        <a href="<?php echo base_url('ui/settings'); ?>" class="nav-link ">
                            <i class="icon-picture"></i>
                            <span class="title">تنظیمات</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->
    <script type="text/javascript">
        <?php if(isset($active_menu)): ?>
        $(document).ready(function () {
            $("#<?php echo $active_menu ?>").addClass('active');
        });
        <?php endif; ?>
    </script>

    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->