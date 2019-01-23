<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>

        <div class="actions">
            <!--            <a href="-->
            <?php //echo base_url('ui/resellers/index'); ?><!--" class="btn btn-default btn-sm">-->
            <!--                <i class="icon-action-undo"></i>-->
            <!--            </a>-->
            <!--            <a href="javascript:;" class="btn btn-default btn-sm">-->
            <!--                <i class="fa fa-plus"></i> افزودن-->
            <!--            </a>-->
        </div>

    </div>

    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نظر دهنده</th>
                <th>متن نظر</th>
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
        opt["ajax"]       = "<?php echo base_url('ui/comment/comments_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "name",
                "render": function (data, type, row, meta) {
                    return row['name'] + " " + row['family'];
                }
            },
            {
                "targets": 1,
                "data": "comment",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 2,
                "data": "id",
                "render": function (data, type, row, meta) {
                    return '<button class="btn btn-default btn-circle">delete</button>';
                    // var btn = '';
                    // if (row['status'] == '0') {
                    //     btn = '<button class="btn btn-default btn-circle"  onclick="approveComment(\'' + row['id'] + '\')">';
                    //     btn += "تایید";
                    //     btn += "</button>";
                    // }
                    // btn += '<button class="btn btn-error btn-circle"  onclick="deleteComment(\'' + row['id'] + '\')">';
                    // btn += "حذف";
                    // btn += "</button>";
                    //
                    // return btn;
                }
            },
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

    function approveComment(comment_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/comment/approve_comment'); ?>",
            "reload_on_success": true,
            "title": "تایید",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': comment_id
            }
        };
        ask_user_confirm(dlg);
    }


    function deleteComment(comment_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/comment/delete_comment'); ?>",
            "reload_on_success": true,
            "title": "تایید",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': comment_id
            }
        };
        ask_user_confirm(dlg);
    }


</script>
