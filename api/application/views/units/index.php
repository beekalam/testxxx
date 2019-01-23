<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>

        <div class="actions">
            <a href="<?php echo base_url('ui/products/index'); ?>" class="btn btn-default btn-sm">
                <i class="icon-action-undo"></i>
            </a>
            <a href="javascript:;" class="btn btn-default btn-sm" data-toggle="modal" data-target="#add_unit_modal">
                <i class="fa fa-plus"></i> افزودن
            </a>
        </div>
    </div>

    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>ID</th>
                <th>نام واحد</th>
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

<?php
$content = <<<HTML
<form>
    <div class="form-group">
        <label for="new-password" class="col-form-label">نام واحد</label>
        <input type="text" name="unit_name" id="unit_name" class="form-control" required="required"/>
    </div>
</form>
HTML;

$this->load->view("_partials/_bs_modal", array("header"    => "افزودن واحد",
                                               "id"        => "add_unit_modal",
                                               "content"   => $content,
                                               "submit_id" => "add_new_unit"));
?>
<script>
    $(document).ready(function()
    {

        $("#add_new_unit").click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('ui/units/add_unit'); ?>',
                data: {'unit_name': $("#unit_name").val()},
                success: function (data) {
                    $("#add_unit_modal").modal("hide");
                    data = JSON.parse(data);
                    if (data["success"]) {
                        swal({
                            type: 'success',
                            title: 'عملیات با موفقیت انجام شد.',
                            showConfirmButton: false,
                        });
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000)
                    } else {
                        swal({
                            type: 'error',
                            title: 'خطا در انجام عملیات.',
                            text: data['error'],
                        });
                    }
                }
            }); // end ajax
        }); // end click

    });
</script>

<script>
    $(document).ready(function () {
        var opt           = build_datatable_init();
        opt["ajax"]       = "<?php echo base_url('ui/units/units_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "id",
                "render": function (data, type, row, meta) {
                    // return row["firstname"] + " " + row['lastname'];
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "unit_name",
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
