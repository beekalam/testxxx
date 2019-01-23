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
                <i class="fa fa-plus"></i>بازگشت
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نام دسته</th>
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
        opt["ajax"]       = "<?php echo base_url('ui/jobs/career_categories_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "career_category",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "id",
                "render": function (data, type, row, meta) {
                    return "<button class='btn btn-sm btn-primary btn-circle' onclick='editCareerCategory({0},\"{1}\")'>Edit</buton>".format(data, row['career_category']);
                }
            }
        ];

        $("#table-reservations").DataTable(opt);

    });


    function editCareerCategory(category_id, category_name) {

        swal({
            title: '',
            input: 'text',
            inputValue: category_name,
            showCancelButton: true,
            inputValidator: function (value) {
                return !value && 'نام دسته را وارد کنید.'
            }
        }).then(function (data) {

            console.log(    data);
            if(data.dismiss && (data.dismiss == 'cancel' || data.dismiss == 'overlay')){
                return;
            }
            
            $.ajax({
                type: "POST",
                url: '<?php echo base_url('ui/jobs/update_career_category_text'); ?>',
                data: {"id": category_id, "career_category": data.value},
                ajax: false,
                success: function (data) {
                    data = JSON.parse(data);
                    if (data["success"]) {
                        swal({
                            type: 'success',
                            title: 'عملیات با موفقیت انجام شد.',
                            showConfirmButton: true
                        }).then(function(){
                            window.location.reload();
                        })
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
