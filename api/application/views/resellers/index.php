<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>

        <div class="actions">
            <a href="<?php echo base_url('ui/resellers/index'); ?>" class="btn btn-default btn-sm">
                 <i class="icon-action-undo"></i>
            </a>
            <a href="javascript:;" class="btn btn-default btn-sm">
                <i class="fa fa-plus"></i> افزودن
            </a>
        </div>

    </div>

    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نام</th>
                <th>نام خانوادگی</th>
                <!--<th>نام واحد</th>-->
                <!--<th>قیمت</th>-->
                <!--<th>شماره</th>-->
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


<script>
    $(document).ready(function () {
        var opt           = build_datatable_init();
        opt["ajax"]       = "<?php echo base_url('ui/resellers/resellers_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "reseller_firstname",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "reseller_lastname",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
//             {
//                 "targets": 2,
//                 "data": "unit_name",
//                 "render": function (data, type, row, meta) {
//                     // console.log( row);
// //                        var btn = '<button class="btn btn-default btn-circle"  onclick="showHotelInfo(\'' + row['hotel_id'] + '\')">';
// //                         btn += "مشخصات هتل";
// //                         btn += "</button>";
// //                         return btn;
//                     return data;
//                 }
//             },
            // {
            //     "targets": 8,
            //     "data": "id",
            //     "render": function (data, type, row, meta) {
            //         var ret = "";
            //         if(row['admin_approved'] == '0')
            //             ret += "<button class='btn btn-default btn-sm btn-circle' onclick='confirm_product({0})'>تایید</button>".format(data);
            //         ret += "<button class='btn btn-warning btn-sm btn-circle' onclick='delete_product({0})'>حذف</button>".format(data);
            //         return ret;
            //     }
            // }
        ];

        $("#table-reservations").DataTable(opt);

    });

    // $('body').on('click', '.person_type_btn', function () {
    //     var self = $(this);
    //     var view = {
    //         'person_semat': self.attr("data-person-semat") || "--",
    //         'company_name': self.attr("data-company-name") || "--"
    //     };
    //
    //     var content = compile_handlebar("person-type-template", view);
    //     build_bootstrap_modal({"placementId": "modal-holder", "content": content});
    // });
    //
    // $('body').on('click', '.karfarma_details', function () {
    //     var self = $(this);
    //     var view = {
    //         'certification_no': self.attr('data-certification-no'),
    //         'certification_image': self.attr('data-certification-image')
    //     };
    //     // console.log(view);
    //     var content = compile_handlebar("karfarma-template", view);
    //     // console.log(content);
    //     build_bootstrap_modal({"placementId": "modal-holder", "content": content});
    // });


</script>
