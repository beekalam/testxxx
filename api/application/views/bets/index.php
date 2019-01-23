<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo base_url('assets/parsleyjs/parsley.css'); ?>">
<script src="<?php echo base_url('assets/parsleyjs/parsley.js'); ?>"></script>


<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>
        <div class="actions">
            <!--<a href="javascript:;" class="btn btn-default btn-sm">-->
            <!--    <i class="fa fa-pencil"></i> Edit </a>-->
            <button class="btn btn-default btn-sm trigger" onclick="loadNewBetModal()">
                <i class="fa fa-plus"></i> افزودن
            </button>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>عنوان</th>
                <th>توضحیات</th>
                <th>امتیاز</th>
                <th>طرف a</th>
                <th>طرف b</th>
                <th>تاریخ شروع</th>
                <!--<th>قیمت</th>-->
                <!--<th>استان</th>-->
                <th>actions</th>
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



<script id="new_bet_template" type="text/x-handlebars-template">

</script>

<div class="modal fade" id="add_update_bet_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
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
                        id="add_bet_iframe">
                </iframe>
            </div>
            <div class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>-->
                <!--<button type="submit" class="btn btn-primary">ثبت</button>-->
            </div>
        </div>
    </div>
</div>

<script>
    function closeIFrame() {
        $("#add_bet_iframe").attr("src","");
        $("#add_update_bet_modal").modal("hide");
    }

    function loadNewBetModal() {
        $("#add_bet_iframe").attr("src", "<?php echo base_url('ui/bets/add_update_bet?action=new'); ?>");
        $("#add_update_bet_modal").modal();
    }

    $(document).ready(function () {
        // $("#modal").iziModal();

        var opt           = build_datatable_init();
        opt["ajax"]       = "<?php echo base_url('ui/bets/bets_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "bet_title",
                "render": function (data, type, row, meta) {
                    // return row["firstname"] + " " + row['lastname'];
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "bet_description",
                "render": function (data, type, row, meta) {
                    // fa_data = data == 'yes' ? 'موفق' : 'ناموفق';
                    // klass   = data == 'yes' ? 'btn btn-success btn-circle' : 'btn btn-warning btn-circle';
//                        return '<button class="{0}" type="button">{1}</button>'.format(klass, fa_data);
                    return chop_str(data, 100);
                }
            },
            {
                "targets": 2,
                "data": "bet_point",
                "render": function (data, type, row, meta) {
                    // console.log( row);
//                        var btn = '<button class="btn btn-default btn-circle"  onclick="showHotelInfo(\'' + row['hotel_id'] + '\')">';
//                         btn += "مشخصات هتل";
//                         btn += "</button>";
//                         return btn;
                    return data;
                }
            },
            {
                "targets": 3,
                "data": "text_to_show_a",
                "render": function (data, type, row, meta) {
                    // return number_format(data);
                    // return number_format(data);
                    return data;
                }
            },
            {
                "targets": 4,
                "data": "text_to_show_b",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "searchable": false,
                "targets": 5,
                "data": "bet_start_date",
                "render": function (data, type, row, meta) {
                    // return row["firstname"] + " "+ row['lastname'];
                    return data;
                }
            },
            {
                "targets": 6,
                "data": "id",
                "render": function (data, type, row, meta) {
                    var ret = "";
                    // if(row['admin_approved'] == '0')
                    //     ret += "<button class='btn btn-default btn-sm btn-circle' onclick='confirm_product({0})'>تایید</button>".format(data);
                   ret += "<button class='btn btn-warning btn-sm btn-circle' onclick='delete_bet({0})'>حذف</button>".format(data);
                    return ret;
                }
            }
        ];

        $("#table-reservations").DataTable(opt);

    });

    function confirm_product(product_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/products/confirm_product_ad'); ?>",
            "reload_on_success": true,
            "title": "تایید کالا",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': product_id
            }
        };
        ask_user_confirm(dlg);
    }

    function delete_bet(bet_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/bets/delete_bet'); ?>",
            "reload_on_success": true,
            "title": "حذف پیش بینی",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': bet_id
            }
        };
        ask_user_confirm(dlg);
    }

</script>
