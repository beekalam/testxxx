<script src="<?php echo base_url('assets/handlebars-v4.0.11.js'); ?>"></script>

<div class="portlet box blue-hoki">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-plus"></i>
            <span class="caption-subject bold uppercase">
           </span>
        </div>
        <div class="actions">
            <button class="btn btn-default btn-sm trigger" onclick="loadNewBetModal()">
                <i class="fa fa-plus"></i>افزودن مسابقه
            </button>
        </div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <table class="table table-responsive table-hover table-striped" id="table-reservations">
                <thead>
                <th>عنوان</th>
                <th>توضیحات</th>
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
        $("#add_update_contest_iframe").attr("src","");
        $("#add_update_contest_modal").modal("hide");
    }

    function loadNewBetModal() {
        $("#add_update_contest_iframe").attr("src", "<?php echo base_url('ui/contests/add_update_contest?action=new'); ?>");
        $("#add_update_contest_modal").modal();
    }

    $(document).ready(function () {
        var opt           = build_datatable_init();
        opt["ajax"]       = "<?php echo base_url('ui/contests/contest_list'); ?>";
        opt["columnDefs"] = [
            {
                "targets": 0,
                "data": "title",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 1,
                "data": "description",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 2,
                "data": "end_date",
                "render": function (data, type, row, meta) {
                    return data;
                }
            },
            {
                "targets": 3,
                "data": "id",
                "render": function (data, type, row, meta) {
                    var  url = "<?php echo base_url('ui/contests/contest_participants?contest_id='); ?>" +data;
                    var ret = "<a class='btn btn-sm btn-circle btn-defaut' href='{0}'>شرکت کنندگان</a>".format(url);
                    return ret;
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
