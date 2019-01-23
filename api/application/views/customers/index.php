<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">مشتری ها
           </span>
        </div>
        <div class="actions"></div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نام</th>
                <th>موبایل</th>
                <th>کد ملی</th>
                <th>email</th>
                <th>address</th>
                <!--<th>نوع شخصیت</th>-->
                <!--<th>سمت</th>-->
                <!--<th>province</th>-->
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

        $("#table-reservations").DataTable({
            "searching": true,
            "sorting": true,
            "ordering": true,
            "pageLength": 5,
            "bScrollInfinite": true,
            serverSide: true,
            scrollY: 300,
            scroller: {
                loadingIndicator: true
            },
            // "order": [[8, "desc"]],
            "ajax": "<?php echo base_url('ui/customers/customers_list'); ?>",
            "columnDefs": [
                {
                    "targets": 0,
                    "data": "first_name",
                    "render": function (data, type, row, meta) {
                        return row["firstname"] + " " + row['lastname'];
                    }
                },
                {
                    "targets": 1,
                    "data": "mobile",
                    "render": function (data, type, row, meta) {
                        // fa_data = data == 'yes' ? 'موفق' : 'ناموفق';
                        // klass   = data == 'yes' ? 'btn btn-success btn-circle' : 'btn btn-warning btn-circle';
//                        return '<button class="{0}" type="button">{1}</button>'.format(klass, fa_data);
                        return data;
                    }
                },
                {
                    "targets": 2,
                    "data": "national_code",
                    "render": function (data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "targets": 3,
                    "data": "address",
                    "render": function (data, type, row, meta) {
                        // return number_format(data);
                        return data;
                    }
                },
                {
                    "targets": 4,
                    "data": "email",
                    "render": function (data, type, row, meta) {
                        return data;
                    }
                },
                // {
                //     "targets": 5,
                //     "data": "person_type",
                //     "render": function (data, type, row, meta) {
                //         var title = row['person_type'] == 'haghighi' ? "حقیقی" : "حقوقی";
                //         var btn   = "<button class='btn btn-sm btn-circle person_type_btn' " +
                //             "data-person-semat='{0}' data-company-name='{1}'>{2}</button>";
                //
                //         return btn.format(row['person_semat'], row['company_name'] || "", title);
                //     }
                // },
                // {
                //     "targets": 6,
                //     "data": "id",
                //     "render": function (data, type, row, meta) {
                //         // console.log(row);
                //         if (row['person_semat'])
                //             return row['person_semat'];
                //         return '';
                //     }
                // },
                // {
                //     "targets": 7,
                //     "data": "province_name",
                //     "render": function (data, type, row, meta) {
                //         return data;
                //     }
                // },
                // {
                //     "targets": 8,
                //     "data": "is_employer",
                //     "render": function (data, type, row, meta) {
                //         // console.log(data);
                //         var title      = data == "yes" ? "کارفرما" : "کارجو";
                //         var badge_type = data == 'yes' ? "badge-secondary" : "badge-primary";
                //         var klass      = data == 'yes' ? 'karfarma_details' : '';
                //         var badge      = "<button class='badge {0} {1} '" +
                //             " data-certification-no='{2}' data-certification-image='{3}'>{4}</button>";
                //         return badge.format(badge_type,klass, row['certification_no'], row['certification_image'], title);
                //     }
                // },
                // {
                //     "targets": 9,
                //     "data": "employer_details_id",
                //     "render": function (data, type, row, meta) {
                //         return data;
                //     }
                // },
                // {
                //     "targets": 10,
                //     "data": "created_at",
                //     "render": function (data, type, row, meta) {
                //         return data;
                //     }
                // }
            ],
            "language": {
                "sEmptyTable": "هیچ داده ای در جدول وجود ندارد",
                "sInfo": "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                "sInfoEmpty": "نمایش 0 تا 0 از 0 رکورد",
                "sInfoFiltered": "(فیلتر شده از _MAX_ رکورد)",
                "sInfoPostFix": "",
                "sInfoThousands": ",",
                "sLengthMenu": "نمایش _MENU_ رکورد",
                "sLoadingRecords": "در حال بارگزاری...",
                "sProcessing": "در حال پردازش...",
                "sSearch": "جستجو:",
                "sZeroRecords": "رکوردی با این مشخصات پیدا نشد",
                "oPaginate": {
                    "sFirst": "ابتدا",
                    "sLast": "انتها",
                    "sNext": "بعدی",
                    "sPrevious": "قبلی"
                },
                "oAria": {
                    "sSortAscending": ": فعال سازی نمایش به صورت صعودی",
                    "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                }
            }
        });

    });
    //
    // $('body').on('click', '.person_type_btn', function () {
    //     var self = $(this);
    //     var view = {
    //         'person_semat': self.attr("data-person-semat") || "--",
    //         'company_name': self.attr("data-company-name") || "--"
    //     };
    //     console.log(view);
    //
    //     var content = compile_handlebar("person-type-template", view);
    //     console.log(content);
    //
    //     build_bootstrap_modal({"placementId": "modal-holder", "content": content});
    // });
    //
    // $('body').on('click', '.karfarma_details', function () {
    //     var self = $(this);
    //     var view = {
    //         'certification_no': self.attr('data-certification-no'),
    //         'certification_image': self.attr('data-certification-image')
    //     };
    //     console.log(view);
    //     var content = compile_handlebar("karfarma-template", view);
    //     // console.log(content);
    //     build_bootstrap_modal({"placementId": "modal-holder", "content": content});
    // })

</script>
