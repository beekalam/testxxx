<?php if (isset($success_submit)): ?>
    <div class="row">
        <div class="alert alert-success" role="alert">
            پیش بینی با موفقیت ثبت شد.
            <button class='btn btn-info' onclick="parent.closeIFrame()">بستن</button>
            <?php exit; ?>
        </div>
    </div>
<?php endif; ?>

<form method="POST"
      action="<?php echo base_url('ui/bets/add_update_bet'); ?>"
      enctype="multipart/form-data">

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

    <div class="form-group col-xs-12">
        <label for="point">امتیاز</label>
        <input type="number" id='point' name='point'
               data-parsley-required="true"
               value="<?php echo set_value('point'); ?>"
               class="form-control"/>
    </div>

    <div class="form-group col-xs-6">
        <label for="text_to_show_a">متن طرف a</label>
        <input type="text" id='text_to_show_a'
               data-parsley-required="true"
               value="<?php echo set_value('text_to_show_a'); ?>"
               name='text_to_show_a' class="form-control"/>
    </div>

    <div class="form-group col-xs-6">
        <label for="text_to_show_b">متن طرف b</label>
        <input type="text" id='text_to_show_b'
               data-parsley-required="true"
               value="<?php echo set_value('text_to_show_b'); ?>"
               name='text_to_show_b' class="form-control"/>
    </div>

    <div class="form-group col-xs-6">
        <label for="image_to_show_a">عکس طرف a</label>
        <input type="file" id='image_to_show_a'
               name='image_to_show_a' class="form-control"/>
    </div>

    <div class="form-group col-xs-6">
        <label for="image_to_show_b">عکس طرف b</label>
        <input type="file" id='image_to_show_b'
               name='image_to_show_b' class="form-control"/>
    </div>

    <div class="form-group">
        <label for="tag">دسته بندی</label>
        <input type="text" id='tag' name='tag' class="form-control"/>
    </div>

    <div class="form-group">
        <label for="bet_options">نتایج</label>
        <input type="text" id='bet_options' data-role="tagsinput"
               name='bet_options' class="form-control"/>
    </div>

    <button type="submit">ثبت</button>
</form>