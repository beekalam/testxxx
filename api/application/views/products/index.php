<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>
<script src="<?php echo base_url('assets/loading-overlay/loadingoverlay.min.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>
        <div class="actions">
<!--            <a href="--><?php //echo base_url('ui/units/index'); ?><!--" class="btn btn-default btn-sm">-->
<!--                <i class="fa fa-pencil"></i> مدیریت واحد ها-->
<!--            </a>-->
            <button class="btn btn-default btn-sm trigger" onclick="loadNewBetModal()">
                <i class="fa fa-plus"></i> افزودن
            </button>
        </div>
    </div>
    <div class="portlet-body">


        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>خدمت</th>
                <th>توضیح</th>
<!--                <th>نام واحد</th>-->
                <th>دسته</th>
<!--                <th>فروشنده</th>-->
<!--                <th>قیمت واحد</th>-->
                <th>عملیات</th>
                <!--<th>نام آگهی دهنده</th>-->
                <!--<th>ایمیل</th>-->
                <!--<th>استان</th>-->
                <!--<th>actions</th>-->
                <!--<th>is_employer</th>-->
                <!--<th>emp details</th>-->
                <!--<th>created_at</th>-->
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="modal fade" id="add_update_product" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title persian-font" id="modalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="new_bet_modal_content">
                <iframe src=""
                        frameborder="0" style="width:100%" height="600px"
                        id="modal_iframe">
                </iframe>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>-->
                <!--<button type="submit" class="btn btn-primary">ثبت</button>-->
            </div>
        </div>
    </div>
</div>

<div id="category_select" style="display:none;">

    <select id="main_cat_select" style="width:100%;margin-bottom: 5px;">
        <option value="">انتخاب کنید</option>
        <?php echo build_options_from_array($main_cats, "id", "cat_name"); ?>
    </select>

    <select id="sub_cat_select" style="width:100%;margin-bottom: 5px;">
        <option value="">انتخاب کنید</option>
    </select>

    <!--<button onClick="changeC"-->
</div>

<script>

    function closeIFrame() {
        $("#modal_iframe").attr("src", "");
        $("#add_update_product").modal("hide");
    }

    function loadNewBetModal() {
        $.LoadingOverlay("show");
        $("#modal_iframe").attr("src", "<?php echo base_url('ui/products/add_update_product?action=new'); ?>");
        $("#add_update_product").modal();
        $.LoadingOverlay("hide");
    }

    $(document).ready(function () {
        var opt           = build_datatable_init();
        opt["ajax"]       = "<?php echo base_url('ui/products/products_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "title",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "description",
                "render": function (data, type, row, meta) {
                    data = data || "--";
                    // return chop_str(data, 100);
                    return "<a href='javascript:;' onClick='changeDescription({0},{1})'>{2}</a>".format(row['id'], '"' + data + '"', chop_str(data, 100));
                }
            },
            // {
            //     "targets": 2,
            //     "data": "unit_name",
            //     "render": function (data, type, row, meta) {
            //         data = data || "--";
            //         return "<a href='javascript:;' onClick='changeUnit({0})'>{1}</a>".format(row['id'], data);
            //     }
            // },
            {
                "targets": 2,
                "data": "main_cat",
                "render": function (data, type, row, meta) {
                    // return data;
                    return "<a href='javascript:;' onClick='changeCategory({0},{1},{2})'>{3}</a>".format(row['id'],
                        row['main_cat_id'], row['sub_cat_id'], data);
                }
            },
            // {
            //     "targets": 3,
            //     "data": "reseller_id",
            //     "render": function (data, type, row, meta) {
            //         var name = row["reseller_firstname"] + " " + row["reseller_lastname"];
            //         name     = name.replace(/null/g, "--");
            //         // console.log( row['reseller_id']);
            //         return "<a href='javascript:;' onClick='changeReseller({0})'>{1}</a>".format(row['id'], name);
            //     }
            // },
            // {
            //     "targets": 5,
            //     "data": "price_per_unit",
            //     "render": function (data, type, row, meta) {
            //         var t = persian_number(number_format(data)) + " ریال";
            //         return "<a href='javascript:;' onclick='changePrice({0},{1})'>{2}</a>".format(row['id'],data,t);
            //     }
            // },
            {
                "targets": 3,
                "data": "id",
                "sortable": false,
                "render": function (data, type, row, meta) {
                    var btn = '<button class="btn btn-default btn-circle"  onclick="delete_product(\'' + data + '\')">';
                    btn += "حذف";
                    btn += "</button>";
                    var url = "<?php echo base_url('ui/products/manage_images?id='); ?>";
                    url += data;
                    btn += "<a class='btn btn-default btn-circle' href='{0}'>تصاویر</a>".format(url);
                    return btn;
                    return data;
                }
            }
        ];

        $("#table-reservations").DataTable(opt);

    });

    function delete_product(product_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/products/delete_product'); ?>",
            "reload_on_success": true,
            "title": "حذف کالا",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': product_id
            }
        };
        ask_user_confirm(dlg);
    }

    function changeReseller(product_id) {

        swal({
            title: 'انتخاب فروشنده',
            input: 'select',
            inputOptions: {
                <?php
                foreach ($resellers as $r) {
                    echo "'{$r['id']}':'{$r['name']}',";
                }
                ?>
            },
            inputPlaceholder: 'انتخاب کنید',
            showCancelButton: true,
            inputValidator: function (value) {
                return new Promise(function (resolve) {
                    if (value === '') {
                        resolve('یک فروشنده انتخاب کنید.');
                    } else {
                        // resolve(value);
                        var dlg = {
                            "url": "<?php echo base_url('ui/products/change_reseller'); ?>",
                            "reload_on_success": true,
                            "title": "تغییر فروشنده",
                            "html": "مطمئن هستید؟",
                            "to_send": {
                                'reseller_id': value,
                                'product_id': product_id
                            }
                        };
                        ask_user_confirm(dlg);
                    }
                })
            }
        });

    }

    function changeUnit(product_id) {

        swal({
            title: 'انتخاب واحد',
            input: 'select',
            inputOptions: {
                <?php
                foreach ($units as $r) {
                    echo "'{$r['id']}':'{$r['unit_name']}',";
                }
                ?>
            },
            inputPlaceholder: 'انتخاب کنید',
            showCancelButton: true,
            inputValidator: function (value) {
                return new Promise(function (resolve) {
                    if (value === '') {
                        resolve('یک واحد انتخاب کنید.');
                    } else {
                        // resolve(value);
                        var dlg = {
                            "url": "<?php echo base_url('ui/products/change_unit'); ?>",
                            "reload_on_success": true,
                            "title": "تغییر واحد",
                            "html": "مطمئن هستید؟",
                            "to_send": {
                                'unit_id': value,
                                'product_id': product_id
                            }
                        };
                        ask_user_confirm(dlg);
                    }
                })
            }
        });

    }

    function changeDescription(product_id, current_description) {
        swal({
            title: 'تغییر توضیحات',
            input: 'textarea',
            inputValue: current_description,
            inputPlaceholder: 'Type your message here',
            showCancelButton: true,
            inputValidator: function (value) {
                return new Promise(function (resolve) {
                    if (value === '') {
                        resolve('مقدار نامعتبر');
                    } else {
                        // resolve(value);
                        var dlg = {
                            "url": "<?php echo base_url('ui/products/change_description'); ?>",
                            "reload_on_success": true,
                            "title": "تغییر توضیحات",
                            "html": "مطمئن هستید؟",
                            "to_send": {
                                'description': value,
                                'product_id': product_id
                            }
                        };
                        ask_user_confirm(dlg);
                    }
                })
            }
        });
    }

    function changePrice(product_id, current_value){
        swal({
            title: 'تغییر قیمت',
            input: 'number',
            inputValue: current_value,
            inputPlaceholder: 'Type your message here',
            showCancelButton: true,
            inputValidator: function (value) {
                return new Promise(function (resolve) {
                    if (value === '') {
                        resolve('مقدار نامعتبر');
                    } else {
                        // resolve(value);
                        var dlg = {
                            "url": "<?php echo base_url('ui/products/change_price'); ?>",
                            "reload_on_success": true,
                            "title": "تغییر قیمت",
                            "html": "مطمئن هستید؟",
                            "to_send": {
                                'price_per_unit': value,
                                'product_id': product_id
                            }
                        };
                        ask_user_confirm(dlg);
                    }
                })
            }
        });
    }

    function changeCategory(product_id, main_cat_id, sub_cat_id) {
        swal({
            title: 'تغییر دسته',
            html: $("#category_select").html(),
            focusConfirm: false,
            showLoaderOnConfirm: true,
            preConfirm: function () {
                var sub_cat_id = $('.swal2-content #sub_cat_select').val();
                if (sub_cat_id.trim() != "") {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url('ui/products/change_category'); ?>',
                        data: {'product_id': product_id, 'sub_cat_id': sub_cat_id},
                        success: function (data) {
                            data = JSON.parse(data);
                            if (data["success"]) {
                                swal.insertQueueStep("عملیات با موفقیت انجام شد.");
                                window.location.reload();
                            } else {

                            }
                        }
                    });
                }
            }
        });

    }

    $(document).on("change", ".swal2-content #main_cat_select", function () {
        var url = "<?php echo base_url("ui/category/subcategories?build_options=t&cat_id="); ?>";
        url += $(this).val();
        $(".swal2-content #sub_cat_select").LoadingOverlay("show");
        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {
                console.log("in change main_cat_select");
                console.log(data);
                $(".swal2-content #sub_cat_select").find('option').remove();
                $(".swal2-content #sub_cat_select").LoadingOverlay("hide");
                data = JSON.parse(data);
                if (data["success"]) {
                    $(".swal2-content #sub_cat_select").append("<option value=''>انتخاب کنید</option>");
                    $.each(data["data"], function (key, value) {
                        console.log(key);
                        $(".swal2-content #sub_cat_select").append(value);
                    });
                } else {

                }
            }
        });
    });


</script>
