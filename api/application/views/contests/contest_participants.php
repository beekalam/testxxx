<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>
        <div class="actions">
            <!--<button class="btn btn-default btn-sm trigger" onclick="loadNewBetModal()">-->
            <!--    <i class="fa fa-plus"></i>xxx-->
            <!--</button>-->
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>نام</th>
                <th>نام مسابقه</th>
                <th>تاریخ پایان</th>
                <th>actions</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="add_update_contest_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
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
                        id="add_update_contest_iframe">
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
        $("#add_update_contest_iframe").attr("src", "");
        $("#add_update_contest_modal").modal("hide");
    }

    function loadNewBetModal() {
        $("#add_update_contest_iframe").attr("src", "<?php echo base_url('ui/contests/add_update_contest?action=new'); ?>");
        $("#add_update_contest_modal").modal();
    }

    $(document).ready(function () {
        var opt = build_datatable_init();
        <?php if(isset($contest_id)): ?>
        opt["ajax"] = "<?php echo base_url("ui/contests/contest_participants_list?contest_id=$contest_id"); ?>";
        <?php else: ?>
        opt["ajax"] = "<?php echo base_url('ui/contests/contest_participants_list'); ?>";
        <?php endif; ?>
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "firstname",
                "render": function (data, type, row, meta) {
                    return row["firstname"] + " " + row["lastname"];
                }
            },
            {
                "targets": 1,
                "data": "title",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 2,
                "data": "end_date_fa",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 3,
                "data": "id",
                "render": function (data, type, row, meta) {
                    return data;
                }
            }
        ];

        $("#table-reservations").DataTable(opt);

    });

    function confirm_ad(ad_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/jobs/confirm_job_ad'); ?>",
            "reload_on_success": true,
            "title": "تایید آگهی",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': ad_id
            }
        };
        ask_user_confirm(dlg);
    }

    function delete_ad(ad_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/jobs/delete_job_ad'); ?>",
            "reload_on_success": true,
            "title": "حذف آگهی",
            "html": "مطمئن هستید؟",
            "to_send": {
                'id': ad_id
            }
        };
        ask_user_confirm(dlg);
    }


</script>
