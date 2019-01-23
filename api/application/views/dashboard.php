<script src="<?php echo base_url('assets/js/mansouri.js') . "?cc=" . uniqid(); ?>"></script>
<?php $view_data = get_defined_vars()['_ci_data']['_ci_vars']; ?>
<!-- BEGIN PAGE HEADER-->
<div class="page-bar"></div>

<div class="row">

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <?php $this->load->view("_partials/_stat", array("title"         => "سفارش های امروز",
                                                         "number"        => $num_orders_created_today,
                                                         "show_progress" => false,
                                                         "icon"          => "icon-user"));
        ?>

    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <?php $this->load->view("_partials/_stat", array("title"         => "پرداختی های امروز",
                                                         "number"        => $num_orders_paied_today,
                                                         "show_progress" => false,
                                                         "header_class"  => "font-red-haze",
                                                         "icon"          => "icon-present"));
        ?>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <?php $this->load->view("_partials/_stat", array("title"         => "تعداد مشتری ها",
                                                         "number"        => $num_customers,
                                                         "show_progress" => false,
                                                         "header_class"  => "font-red-haze",
                                                         "icon"          => "icon-share"));
        ?>
    </div>

    <!---->
    <!--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->
    <!--    <div class="dashboard-stat2 ">-->
    <!--        <div class="display">-->
    <!--            <div class="number">-->
    <!--                <h3 class="font-blue-sharp">-->
    <!--                    <span data-counter="counterup" data-value="567">567</span>-->
    <!--                </h3>-->
    <!--                <small>NEW ORDERS</small>-->
    <!--            </div>-->
    <!--            <div class="icon">-->
    <!--                <i class="icon-basket"></i>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="progress-info">-->
    <!--            <div class="progress">-->
    <!--                                    <span style="width: 45%;" class="progress-bar progress-bar-success blue-sharp">-->
    <!--                                        <span class="sr-only">45% grow</span>-->
    <!--                                    </span>-->
    <!--            </div>-->
    <!--            <div class="status">-->
    <!--                <div class="status-title"> grow</div>-->
    <!--                <div class="status-number"> 45%</div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!---->
    <!--<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">-->
    <!--    <div class="dashboard-stat2 ">-->
    <!--        <div class="display">-->
    <!--            <div class="number">-->
    <!--                <h3 class="font-purple-soft">-->
    <!--                    <span data-counter="counterup" data-value="276">276</span>-->
    <!--                </h3>-->
    <!--                <small>NEW USERS</small>-->
    <!--            </div>-->
    <!--            <div class="icon">-->
    <!--                <i class="icon-user"></i>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="progress-info">-->
    <!--            <div class="progress">-->
    <!--                                    <span style="width: 57%;" class="progress-bar progress-bar-success purple-soft">-->
    <!--                                        <span class="sr-only">56% change</span>-->
    <!--                                    </span>-->
    <!--            </div>-->
    <!--            <div class="status">-->
    <!--                <div class="status-title"> change</div>-->
    <!--                <div class="status-number"> 57%</div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!---->
</div>