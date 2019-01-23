<script src="<?php echo base_url('assets/loading-overlay/loadingoverlay.min.js'); ?>"></script>

<?php if (isset($success_submit)): ?>
    <div class="row">
        <div class="alert alert-success" role="alert">
            <?php echo $msg; ?>
            <button class='btn btn-info' onclick="parent.closeIFrame()">بستن</button>
            <?php exit; ?>
        </div>
    </div>
<?php endif; ?>

<form method="POST"
      action="<?php echo base_url('ui/products/add_update_product'); ?>">

    <?php if (!empty(validation_errors())): ?>
        <div class="alert alert-danger">
            <?php echo validation_errors(); ?>
        </div>
    <?php endif; ?>

    <div class="form-group col-xs-12">
        <label for="title">عنوان</label>
        <input type="text" id='title' name='title'
               data-parsley-required="true"
               value="<?php echo set_value('title'); ?>"
               class="form-control"/>
    </div>

    <div class="form-group col-xs-12">
        <label for="description">توضیحات</label>
        <textarea type="text" id='description' name='description'
                  data-parsley-required="true"
                  value="<?php echo set_value('description'); ?>"
                  class="form-control"></textarea>
    </div>

    <div class="form-group col-xs-6">
        <label for="main_cat">انتخاب دسته اصلی</label>
        <select  id='main_cat' name='main_cat' class="form-control" >
            <option value="">انتخاب کنید</option>
            <?php foreach($main_cats as $c): ?>
                <option value="<?php echo $c['id']; ?>">
                    <?php echo $c['cat_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-xs-6">
        <label for="sub_cat">انتخاب زیر دسته</label>
        <select  id='sub_cat' name='sub_cat' class="form-control">

        </select>
    </div>



    <!---->
    <!--<div class="form-group col-xs-6">-->
    <!--    <label for="text_to_show_b">متن طرف b</label>-->
    <!--    <input type="text" id='text_to_show_b'-->
    <!--           data-parsley-required="true"-->
    <!--           value="--><?php //echo set_value('text_to_show_b'); ?><!--"-->
    <!--           name='text_to_show_b' class="form-control"/>-->
    <!--</div>-->
    <!---->
    <!--<div class="form-group col-xs-6">-->
    <!--    <label for="image_to_show_a">عکس طرف a</label>-->
    <!--    <input type="file" id='image_to_show_a'-->
    <!--           name='image_to_show_a' class="form-control"/>-->
    <!--</div>-->
    <!---->
    <!--<div class="form-group col-xs-6">-->
    <!--    <label for="image_to_show_b">عکس طرف b</label>-->
    <!--    <input type="file" id='image_to_show_b'-->
    <!--           name='image_to_show_b' class="form-control"/>-->
    <!--</div>-->
    <!---->
    <!--<div class="form-group">-->
    <!--    <label for="tag">دسته بندی</label>-->
    <!--    <input type="text" id='tag' name='tag' class="form-control"/>-->
    <!--</div>-->
    <!---->
    <!--<div class="form-group">-->
    <!--    <label for="bet_options">نتایج</label>-->
    <!--    <input type="text" id='bet_options' data-role="tagsinput"-->
    <!--           name='bet_options' class="form-control"/>-->
    <!--</div>-->
    <center>
        <button type="submit" class="btn btn-success">ثبت</button>
    </center>
</form>

<script>
    $(document).on("change","#main_cat",function(){
        var url = "<?php echo base_url("ui/category/subcategories?build_options=t&cat_id="); ?>";
        url += $(this).val();
        $("#sub_cat").LoadingOverlay("show");
        $.ajax({
                type: "GET",
                url: url,
                success: function (data) {
                    $("#sub_cat").find('option').remove();
                    $("#sub_cat").LoadingOverlay("hide");
        			data = JSON.parse(data);
        			if(data["success"]){
                        $("#sub_cat").append("<option value=''>انتخاب کنید</option>");
                        $.each(data["data"],function(key,value){
                           $("#sub_cat").append(value);
                        });
        			}else{

        			}
                }
        });
    })
</script>