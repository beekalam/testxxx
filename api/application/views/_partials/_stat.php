<?php
if (isset($show_progress) && $show_progress == true)
    $style_progress = '';
else
    $style_progress = 'display:none !important';


if (!isset($header_class)) {
    $header_class = "font-green-sharp";
}

if(!isset($icon)){
    $icon = 'icon-pie-chart';
}

?>

<div class="dashboard-stat2">

    <div class="display">
        <div class="number">
            <h3 class="<?php echo $header_class; ?>">
                <span data-counter="counterup" data-value="<?php echo $number; ?>">
                    <?php echo $number; ?>
                </span>
                <small class="<?php echo $header_class; ?>"></small>
            </h3>
            <small><?php echo $title; ?></small>
        </div>
        <div class="icon">
            <i class="<?php echo $icon; ?>"></i>
        </div>
    </div>

    <div class="progress-info" style="<?php echo $style_progress; ?>">
        <div class="progress">
            <span style="width: 76%;" class="progress-bar progress-bar-success green-sharp">
                <span class="sr-only">76% progress</span>
            </span>
        </div>
        <div class="status">
            <div class="status-title"> progress</div>
            <div class="status-number"> 76%</div>
        </div>
    </div>

</div>