<div class="page-bar"></div>
<?php $vm = get_defined_vars()['_ci_data']['_ci_vars']; ?>

<div class="row">

    <div class="col-xs-12 col-md-6">

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i>
                    <span class="caption-subject bold uppercase">اسلایدر</span>
                </div>
                <div class="actions">
                    <button class="btn btn-default btn-sm trigger" onclick="newSliderImage()">
                        <i class="fa fa-plus"></i>افزودن اسلایدر
                    </button>
                </div>
            </div>

            <div class="portlet-body">
                <div class="row">
                    <?php $index = 1; ?>
                    <table class="table table-responsive table-hover table-striped" id="main-products-table">
                        <thead>
                        <th>#</th>
                        <th>توضیحات</th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>
                        <?php foreach ($sliders as $slider): ?>
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td><?php echo $slider['description']; ?></td>
                                <td>
                                    <a href="<?php echo $slider['pic_absolute_path']; ?>">
                                        <img src="<?php echo $slider['pic_absolute_path']; ?>" width="100px"
                                             height="100px"/>
                                    </a>
                                </td>
                                <td>
                                    <input type="hidden" name="slider_id" id="slider_id"
                                           value="<?php echo $slider['id']; ?>">
                                    <a onclick="changeImage('<?php echo $slider['id']; ?>')"
                                       class="btn btn-sm btn-circle btn-default">تغییر تصویر</a>

                                    <a onclick="deleteImage('<?php echo $slider['id']; ?>')"
                                       class="btn btn-sm btn-circle btn-default">حذف تصویر</a>

                                    <a href="javascript:;" data-toggle="modal"
                                       data-target="#slider_<?php echo $slider['id']; ?>"
                                       class="btn btn-sm btn-circle btn-default">تغییر توضیحات</a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    <div class="col-xs-12 col-md-6">

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i>
                    <span class="caption-subject bold uppercase"> تنظیمات کیف پول</span>
                </div>
                <div class="actions">

                </div>
            </div>

            <div class="portlet-body form">
                <form action="<?php echo base_url('ui/settings/update_wallet_discount'); ?>"
                      method="post"
                      class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label for="wallet_discount" class="control-label col-xs-4">درصد تخفیف خرید از کیف
                                پول</label>
                            <div class="col-xs-8">
                                <input type="number" min="0" max="100"
                                       value="<?php echo $wallet_discount; ?>"
                                       name="wallet_discount"
                                       class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-xs-offset-3">
                                <button type="submit" class="btn btn-circle btn-green">ثبت</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>


<?php foreach ($sliders as $slider): ?>

    <div class="modal fade" id="slider_<?php echo $slider['id']; ?>" tabindex="-1" role="dialog"
         aria-labelledby="modalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title persian-font" id="modalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo base_url('ui/settings/change_slider_description'); ?>"
                      method="POST">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="new-password" class="col-form-label">توضیح </label>
                            <textarea name="description" id="description"
                                      class="form-control" style="height:225px;"
                                      required="required"><?php echo $slider['description']; ?></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="slider_id" id="slider_id" value="<?php echo $slider['id']; ?>">
                        <button type="submit" class="btn btn-primary change_slider_description">ثبت</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php endforeach; ?>

<div class="modal fade" id="add_new_cat_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title persian-font" id="modalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="new-password" class="col-form-label">نام دسته</label>
                        <input type="text" name="cat_name" id="cat_name" class="form-control" required="required"/>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                <button type="submit" class="btn btn-primary" id="submit_new_cat">ثبت</button>
            </div>
        </div>
    </div>
</div>


<!-- wallet discount setting -->

<script>
    function addNewCat() {
        $("#add_new_cat_modal").modal();
    }

    $(document).ready(function () {
        $("#main-products-table").dataTable({});

        //$("#submit_new_cat").click(function (e) {
        //    e.preventDefault();
        //    $.ajax({
        //        type: "POST",
        //        url: '<?php //echo base_url('ui/category/add_category'); ?>//',
        //        data: {'cat_name': $("#cat_name").val()},
        //        success: function (data) {
        //            $("#add_new_cat_modal").modal("hide");
        //            data = JSON.parse(data);
        //            if (data["success"]) {
        //                swal({
        //                    type: 'success',
        //                    title: 'عملیات با موفقیت انجام شد.',
        //                    showConfirmButton: false,
        //                });
        //                setTimeout(function () {
        //                    window.location.reload();
        //                }, 2000)
        //            } else {
        //                swal({
        //                    type: 'error',
        //                    title: 'خطا در انجام عملیات.',
        //                    text: data['error'],
        //                });
        //
        //            }
        //        }
        //    });
        //
        //});

    });

    function showImage(image_path) {
        swal({
            title: '',
            text: '',
            imageUrl: image_path,
            imageWidth: 400,
            imageHeight: 200,
            imageAlt: '',
            animation: false
        });
    }

    function deleteImage(slider_id) {
        var dlg = {
            "url": "<?php echo base_url('ui/settings/delete_slider'); ?>",
            "reload_on_success": true,
            "title": "حذف اسلایدر",
            "html": "مطمئن هستید؟",
            "to_send": {
                'slider_id': slider_id
            }
        };
        ask_user_confirm(dlg);
    }

    function changeImage(slider_id) {
        swal({
            title: 'یک تصویر انتخاب کنید.',
            input: 'file',
            inputAttributes: {
                'accept': 'image/*',
                'aria-label': 'Upload your profile picture'
            },
            inputValidator: function (value) {
                return new Promise(function (resolve) {
                    if (value) {
                        var reader    = new FileReader();
                        reader.onload = function (e) {
                            var img = reader.result;
                            swal({
                                title: 'Your uploaded picture',
                                imageUrl: e.target.result,
                                showLoaderOnConfirm: true,
                                showCancelButton: true,
                                confirmButtonText: 'ارسال',
                                cancelButtonText: 'لغو',
                                cancelButtonColor: '#ff5d48',
                                preConfirm: function () {
                                    return new Promise(function (resolve, reject) {
                                        $.ajax({
                                            url: "<?php echo base_url('ui/settings/change_picture'); ?>",
                                            data: {"img": img, "slider_id": slider_id},
                                            type: 'POST',
                                            cache: false
                                        }).done(function (data) {
                                            data = JSON.parse(data);
                                            if (data["success"]) {
                                                window.location.reload();
                                            } else {
                                                // reject(data['error']);
                                            }
                                        });
                                    });
                                }
                            });
                        };
                        reader.readAsDataURL(value);
                    }
                });
            }
        });
    }


    function newSliderImage() {
        swal({
            title: 'یک تصویر انتخاب کنید.',
            input: 'file',
            inputAttributes: {
                'accept': 'image/*',
                'aria-label': 'Upload your profile picture'
            },
            inputValidator: function (value) {
                return new Promise(function (resolve) {
                    if (value) {
                        var reader    = new FileReader();
                        reader.onload = function (e) {
                            var img = reader.result;
                            swal({
                                title: 'Your uploaded picture',
                                imageUrl: e.target.result,
                                showLoaderOnConfirm: true,
                                showCancelButton: true,
                                confirmButtonText: 'ارسال',
                                cancelButtonText: 'لغو',
                                cancelButtonColor: '#ff5d48',
                                preConfirm: function () {
                                    return new Promise(function (resolve, reject) {
                                        $.ajax({
                                            url: "<?php echo base_url('ui/settings/new_slider'); ?>",
                                            data: {"img": img},
                                            type: 'POST',
                                            cache: false
                                        }).done(function (data) {
                                            data = JSON.parse(data);
                                            if (data["success"]) {
                                                window.location.reload();
                                            } else {
                                                // reject(data['error']);
                                            }
                                        });
                                    });
                                }
                            });
                        };
                        reader.readAsDataURL(value);
                    }
                });
            }
        });
    }

</script>
