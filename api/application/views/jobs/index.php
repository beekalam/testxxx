<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>
        <div class="actions">
            <a class="btn btn-default btn-sm trigger" href="<?php echo base_url('ui/jobs/careers'); ?>">
                <i class="fa fa-plus"></i>لیست مشاغل
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نام</th>
                <th>نوع آگهی</th>
                <th>شماره تماس</th>
                <th>رزومه کارجو</th>
                <th>رشته کارجو</th>
                <th>مدرک کارجو</th>
                <th>تعداد مورد نیاز کارفرما</th>
                <th>ساعت کاری کارفرما</th>
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

<script id="person-type-template" type="text/x-handlebars-template">
    <table class="table table-responsive table-hover table-stripped">
        <thead>
        <th>سمت</th>
        <th>نام شرکت</th>
        </thead>
        <tbody>
        <tr>
            <td>{{person_semat}}</td>
            <td>{{company_name}}</td>
        </tr>
        </tbody>
    </table>
</script>

<script id="karfarma-template" type="text/x-handlebars-template">
    <table class="table table-responsive table-hover table-stripped">
        <thead>
        <th>شماره ثبت</th>
        <th>image</th>
        </thead>
        <tbody>
        <tr>
            <td>{{certification_no}}</td>
            <td>{{certification_image}}</td>
        </tr>
        </tbody>
    </table>
</script>


<script>
    $(document).ready(function () {
        var opt           = build_datatable_init();
        opt["ajax"]       = "<?php echo base_url('ui/jobs/jobs_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "firstname",
                "render": function (data, type, row, meta) {
                    return row["firstname"] + " " + row['lastname'];
                }
            },
            {
                "targets": 1,
                "data": "ad_type",
                "render": function (data, type, row, meta) {
                    return data == 'employee_ad' ? 'کارجو' : 'کارفرما';
                }
            },
            {
                "targets": 2,
                "data": "job_contact_number",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 3,
                "data": "employee_resume",
                "render": function (data, type, row, meta) {
                    if (row['ad_type'] == 'employee_ad')
                        return chop_str(data,10);
                    else
                        return "----";
                }
            },
            {
                "targets": 4,
                "data": "major",
                "render": function (data, type, row, meta) {
                    if (row['ad_type'] == 'employee_ad')
                        return data;
                    else
                        return "----";
                }
            },
            {
                "targets": 5,
                "data": "degree",
                "render": function (data, type, row, meta) {
                    if (row['ad_type'] == 'employee_ad')
                        return data;
                    else
                        return "----";
                }
            },
            {
                "targets": 6,
                "data": "employer_num_people",
                "render": function (data, type, row, meta) {
                    if (row['ad_type'] == 'employer_ad')
                        return data;
                    else
                        return "----";
                }
            },
            {
                "targets": 7,
                "data": "employer_job_hours",
                "render": function (data, type, row, meta) {
                    if (row['ad_type'] == 'employer_ad')
                        return data;
                    else
                        return "----";
                }
            },
            {
                "targets": 8,
                "data": "id",
                "render": function (data, type, row, meta) {
                    console.log( row);
                    var ret = '';
                    if (row['admin_approved'] == '0') {
                        ret += "<button class='btn btn-sm btn-circle btn-default' onclick='confirm_ad({0})'>{1}</button>".format(data, "تایید آگهی");
                    }
                    ret += "<button class='btn btn-sm btn-circle btn-warning' onclick='delete_ad({0})'>{1}</button>".format(data, "حذف");
                    return ret;
                }
            }
        ];

        $("#table-reservations").DataTable(opt);

    });

    function confirm_ad(ad_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/jobs/confirm_job_ad'); ?>",
            "reload_on_success" : true,
            "title" : "تایید آگهی",
            "html": "مطمئن هستید؟",
            "to_send":{
                'id':ad_id
            }
        };
        ask_user_confirm(dlg);
    }

    function delete_ad(ad_id){
        var dlg = {
            "url": "<?php echo base_url('ui/jobs/delete_job_ad'); ?>",
            "reload_on_success" : true,
            "title" : "حذف آگهی",
            "html": "مطمئن هستید؟",
            "to_send":{
                'id':ad_id
            }
        };
        ask_user_confirm(dlg);
    }

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
    // })

</script>
