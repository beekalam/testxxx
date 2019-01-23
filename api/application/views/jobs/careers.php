<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>
        <div class="actions">
            <a class="btn btn-default btn-sm trigger" href="<?php echo base_url('ui/jobs/career_category'); ?>">
                <i class="fa fa-plus"></i>لیست دسته بندی مشاغل
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نام شغل</th>
                <th>دسته شغل</th>
                <th>actions</th>
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
        opt["ajax"]       = "<?php echo base_url('ui/jobs/careers_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "career",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "career_category",
                "render": function (data, type, row, meta) {
                    return "<a href='javascript:;' onclick='changeCareerCategory({0})'>{1}</a>".format(row['id'], data);
                }
            },
            {
                "targets": 2,
                "data": "id",
                "render": function (data, type, row, meta) {
//                    return "<button class='btn btn-primary btn-circle' onclick='deleteCareer({0})'>حذف</button>";
                    return data;
                }
            }
        ];

        $("#table-reservations").DataTable(opt);

    });

    function changeCareerCategory(career_id) {
        swal({
            title: 'انتخاب دسته',
            input: 'select',
            inputOptions: {
                <?php
                foreach ($career_category as $cat)
                    echo "'{$cat['id']}':'{$cat['career_category']}'," . PHP_EOL;
                ?>
            },
            inputPlaceholder: 'انتخاب دسته',
            showCancelButton: true
        }).then(function (data) {
            console.log(data);
            if (data.dismiss && (data.dismiss == 'cancel' || data.dismiss == 'overlay')) {
                return;
            }

            $.ajax({
                type: "POST",
                url: '<?php echo base_url('ui/jobs/update_career_category'); ?>',
                data: {"career_id": career_id, "category_id": data.value},
                ajax: false,
                success: function (data) {
                    data = JSON.parse(data);
                    if (data["success"]) {
                        swal({
                            type: 'success',
                            title: 'عملیات با موفقیت انجام شد.',
                            showConfirmButton: true
                        }).then(function () {
                            window.location.reload();
                        });
                    } else {
                        swal({
                            type: 'error',
                            title: 'خطا در انجام عملیات.',
                            text: ''
                        });
                    }
                }
            });

        });
    }

</script>
