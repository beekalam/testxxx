<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue">
    <div class="portlet-title">

        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">لیست خودروها</span>
        </div>

        <div class="actions">
            <a href="<?php echo base_url('ui/vehicles/add_vehicle'); ?>" class="btn btn-default btn-sm">
                <i class="fa fa-plus"></i> افزودن
            </a>
        </div>

    </div>

    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نوع خودرو</th>
                <th>مدل</th>
<!--                <th>actions</th>-->
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        var opt          = build_datatable_init();
        opt["searching"]  = true;
        opt["ordering"]   = true;
        opt["order"]      = [[1, "desc"]];
        opt["ajax"]      = "<?php echo base_url('ui/vehicles/vehicles_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "vehicle_brand",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "vehicle_model",
                "searchable":true,
                "sortable":true,
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            // {
            //     "targets": 2,
            //     "data": "id",
            //     "searchable":true,
            //     "sortable":true,
            //     "render": function (data, type, row, meta) {
            //         return data;
            //     }
            // },

        ];

        $("#table-reservations").DataTable(opt);

    });
</script>
