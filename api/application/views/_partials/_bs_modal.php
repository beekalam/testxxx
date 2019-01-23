<?php
$content   = isset($content) ? $content : "";
$id        = isset($id) ? $id : uniqid();
$header    = isset($header) ? $header : "";
$submit_id = isset($submit_id) ? $submit_id : "";
?>

<div class="modal fade" id="<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title persian-font" id="modalLabel"><?php echo $header; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo $content; ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="<?php echo $submit_id; ?>">ثبت</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>