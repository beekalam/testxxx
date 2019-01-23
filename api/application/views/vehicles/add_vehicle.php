<div class="row">
    <div class="col-xs-12">

        <div class="portlet box blue-hoki">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i>
                    <span class="caption-subject bold uppercase">تعریف خودرو جدید</span>
                </div>
                <div class="actions">
                    <a href="<?php echo base_url('ui/vehicles/index'); ?>" class="btn btn-default btn-sm">
                        <i class="icon-action-undo"></i>
                    </a>
                </div>
            </div>

            <div class="portlet-body form">
                <form action="<?php echo base_url('ui/vehicles/add_vehicle'); ?>"
                      method="post"
                      enctype="multipart/form-data"
                      class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label for="wallet_discount" class="control-label col-xs-4">نوع خودرو</label>
                            <div class="col-xs-8">
                                <input type="text"
                                       name="vehicle_brand"
                                       class="form-control"/>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="wallet_discount" class="control-label col-xs-4">مدل</label>
                            <div class="col-xs-8">
                                <input type="text"
                                       name="vehicle_model"
                                       class="form-control"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="car_image" class="control-label col-xs-4">تصویر</label>
                            <div class="col-xs-8">
                                <input type="file"
                                       name="car_image"
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

