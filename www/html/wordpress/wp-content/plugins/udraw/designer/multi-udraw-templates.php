<?php
$uDraw = new uDraw();
$templateId = $uDraw->get_udraw_template_ids($post->ID);

if (count($templateId) > 1) {
    $uDrawTemplate = new uDrawTemplates();
    $templates = implode(",", $templateId);
    $multiTemplates = $uDrawTemplate->get_templates_id($templates);
?>
<style>
    .template_display_item {
        display:inline-block;
        height: 265px;
    }
    .template_display_item img {
        -webkit-transition: all 0.5s ease; /* Safari and Chrome */
        -moz-transition: all 0.5s ease; /* Firefox */
        -ms-transition: all 0.5s ease; /* IE 9 */
        -o-transition: all 0.5s ease; /* Opera */
        transition: all 0.5s ease;
    }

    .template_display_item:hover img {
        -webkit-transform:scale(1.15); /* Safari and Chrome */
        -moz-transform:scale(1.15); /* Firefox */
        -ms-transform:scale(1.15); /* IE 9 */
        -o-transform:scale(1.15); /* Opera */
         transform:scale(1.15);
    }
    div.multi_template_container{
        display: none;
        z-index: 9999;
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        padding: 15px;
        background: rgba(0,0,0,0.8);
    }
    a.udraw_multitemplate img {
        max-height: 250px !important;
        max-width: 250px !important;
    }
    #multi_template_display a.udraw_multitemplate p {
        color: #fff;
        text-align:center;
        font-size: 15px;
    }
</style>
<div class="multi_template_container">
    <div>
        <span style="font-size: 20px; color: #fff;"><?php _e('Please select a template', 'udraw'); ?></span>
    </div>
    <?php if ($displayOptionsFirst) { ?>
    <button id="multi_template_display_btn" type="button" class="button btn-primary" style="display:none;" >Back to Options</button>
    <?php } ?>
    <div id="multi_template_display" class="row" style="margin-top: 15px;" >
        <?php foreach($multiTemplates as $template) { ?>
        <div class="col-md-3 template_display_item">
            <a href="#" class="udraw_multitemplate" data-template-name="<?php echo $template->name; ?>" data-template-id="<?php echo $template->id; ?>" onclick="displayDesigner('<?= $template->design ?>')">
                <img style="max-width:300px" src="<?= $template->preview ?>"/>
                <p class="template_name"><?php echo $template->name; ?></p>
            </a>
        </div>
        <?php } ?>
    </div>
</div>
<?php      
}
?>