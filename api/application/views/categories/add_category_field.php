<div class="row">
    <div class="col-xs-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-plus"></i>
                    <span class="caption-subject bold uppercase">فیلد ها ----
                        <?php echo $category['cat_name']; ?>
                    </span>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body">
                <div class="form-body">
                    <div class="row">
                        <form method="POST" action="<?php echo base_url('ui/category/store_category_fields'); ?>">

                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="english_name" class="col-form-label">نام لاتین</label>
                                    <input type="text" class="form-control" id="english_name" name="english_name">
                                    <span id="vorodi-description-error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="label" class="col-form-label">مت فارسی</label>
                                    <input type="text" class="form-control" id="label" name="label">
                                    <span id="vorodi-description-error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="field_format" class="col-form-label">نوع فیلد</label>
                                    <select id='field_format' name='field_format' class="form-control">
                                        <option value="">انتخاب کنید</option>
                                        <?php echo $category_field_formats; ?>
                                    </select>
                                    <span id="vorodi-description-error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="default_value" class="col-form-label">متن پیش فرض </label>
                                    <input type="text" class="form-control" id="default" name="default">
                                    <span id="vorodi-description-error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-xs-6" id="div_multi" style="display: none;">
                                <div class="form-group">
                                    <label for="default_value" class="col-form-label">مقادیر </label>
                                    <input type="text" name="multi_field_values" multiple="multiple"
                                            id="multi_field_values">
                                    <span id="vorodi-description-error" class="text-danger"></span>
                                </div>
                            </div>

                    </div>
                    <div class="form-actions">
                        <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>" />
                        <button type="submit" class="btn btn-primary" id="add-new-duration">ثبت</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .bootstrap-tagsinput{
        width: 500px;
    }
</style>
<script>
    $(document).ready(function () {
        $("#field_format").change(function () {
            var val = $("#field_format").val();
            console.log( val);
            if(val == 'option' || val == 'select' || val == 'checkbox'){
                $("#div_multi").show();
            }else{
                $("#div_multi").hide();
            }
        });

        $("#multi_field_values").tagsinput({});
    });
</script>