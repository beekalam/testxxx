<link rel="stylesheet" href="<?php echo base_url('assets/pwt.datepicker/persian-datepicker.css'); ?>">
<script src="<?php echo base_url(); ?>assets/pwt.datepicker/persian-date.js"></script>
<script src="<?php echo base_url(); ?>assets/pwt.datepicker/persian-datepicker.js"></script>

<?php if (isset($success_submit)): ?>
    <div class="row">
        <div class="alert alert-success" role="alert">
            مسابقه با موفقیت ثبت شد.
            <button class='btn btn-info' onclick="parent.closeIFrame()">بستن</button>
            <?php exit; ?>
        </div>
    </div>
<?php endif; ?>

<form method="POST"
      action="<?php echo base_url('ui/contests/add_update_contest'); ?>">

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
        <label for="description">تاریخ پایان</label>
        <input type="text" id='end_date' name='end_date'
              data-parsley-required="true"
              value="<?php echo set_value('description'); ?>"
              class="form-control">
    </div>

    <button type="submit">ثبت</button>
</form>

<script>
    (function () {
        $("#end_date").persianDatepicker({
            minDate: "<?php echo tomorrow_unix(); ?>",
            observer: false,
            autoClose: true,
            format: 'YYYY/MM/DD'
        });
    })();
</script>