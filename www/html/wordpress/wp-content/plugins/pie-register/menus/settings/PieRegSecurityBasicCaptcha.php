<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$piereg = PieReg_Base::get_pr_global_options();
?>

<div class="captcha-settings">
    <h3>
        <?php _e("reCAPTCHA Settings",'pie-register'); ?>
    </h3>
    <div class="recaptcha-settings">
        <div class="fields">
            <p>
                <?php _e("Click <a href='https://www.google.com/recaptcha/admin' target='_blank'>here</a> to get reCAPTCHA keys for your site.",'pie-register') ?>
            </p>
            <p id="piereg_reCAPTCHA_Public_Key_error" style="display:none;color:#F00;">
                <strong>
                    <?php _e("Error : Invalid Re-Captcha keys",'pie-register') ?>
                </strong>
            </p>
        </div>
        <div class="fields">
            <div class="flt_lft width_full">
                <label for="piereg_recaptcha_type">
                    <?php _e("reCAPTCHA Type",'pie-register') ?>
                </label>
                <select class="piereg_recaptcha_type" name="piereg_recaptcha_type" id="piereg_recaptcha_type">
                    <option <?php selected(isset($piereg['piereg_recaptcha_type']) && $piereg['piereg_recaptcha_type'] == 'v2', true, true); ?> value="v2">v2</option>
                    <option <?php selected(isset($piereg['piereg_recaptcha_type']) && $piereg['piereg_recaptcha_type'] == 'v3', true, true); ?> value="v3">v3</option>
                </select>
            </div>
        </div>
        <div class="fields">
            <div class="flt_lft width_full">
                <label for="piereg_reCAPTCHA_Public_Key">
                    <?php _e("reCAPTCHA Site Key v2",'pie-register') ?>
                </label>
                <input type="text" id="piereg_reCAPTCHA_Public_Key" name="captcha_publc" class="input_fields" value="<?php echo esc_attr($piereg['captcha_publc'])?>" />
            </div>
            <span class="quotation">
                <?php _e("Required only if you decide to use the reCAPTCHA field. Sign up for a free account to get the key.",'pie-register') ?>
            </span>
        </div>
        <div class="fields">
            <div class="flt_lft width_full">
                <label for="piereg_reCAPTCHA_Private_Key">
                    <?php _e("reCAPTCHA Secret Key v2",'pie-register') ?>
                </label>
                <input type="text" id="piereg_reCAPTCHA_Private_Key" name="captcha_private" class="input_fields" value="<?php echo esc_attr($piereg['captcha_private'])?>" />
            </div>
            <span class="quotation">
                <?php _e("Required only if you decide to use the reCAPTCHA field. Sign up for a free account to get the key.",'pie-register') ?>
            </span>
        </div>
        <div class="fields">
            <div class="flt_lft width_full">
                <label for="piereg_reCAPTCHA_Public_Key_v3">
                    <?php _e("reCAPTCHA Site Key v3",'pie-register') ?>
                </label>
                <input type="text" id="piereg_reCAPTCHA_Public_Key_v3" name="captcha_publc_v3" class="input_fields" value="<?php echo esc_attr($piereg['captcha_publc_v3'])?>" />
            </div>
            <span class="quotation">
                <?php _e("Required only if you decide to use the reCAPTCHA field. Sign up for a free account to get the key.",'pie-register') ?>
            </span>
        </div>
        <div class="fields">
            <div class="flt_lft width_full">
                <label for="piereg_reCAPTCHA_Private_Key_v3">
                    <?php _e("reCAPTCHA Secret Key v3",'pie-register') ?>
                </label>
                <input type="text" id="piereg_reCAPTCHA_Private_Key_v3" name="captcha_private_v3" class="input_fields" value="<?php echo esc_attr($piereg['captcha_private_v3'])?>" />
            </div>
            <span class="quotation">
                <?php _e("Required only if you decide to use the reCAPTCHA field. Sign up for a free account to get the key.",'pie-register') ?>
            </span>
        </div>
        <div class="fields">
            <div class="flt_lft width_full">
                <label for="piereg_recaptcha_language">
                    <?php _e("reCAPTCHA Language",'pie-register') ?>
                </label>
                <div>
                    <select name="piereg_recaptcha_language" id="piereg_recaptcha_language">
                        <option value="ar"
                            <?php selected(($piereg['piereg_recaptcha_language']=="ar"), true, true); ?>>
                            <?php _e("Arabic","pie-register"); ?>
                        </option>
                        <option value="zh-HK"
                            <?php selected(($piereg['piereg_recaptcha_language']=="zh-HK"), true, true); ?>>
                            <?php _e("Chinese (Hong Kong)","pie-register"); ?>
                        </option>
                        <option value="zh-CN"
                            <?php selected(($piereg['piereg_recaptcha_language']=="zh-CN"), true, true); ?>>
                            <?php _e("Chinese (Simplified)","pie-register"); ?>
                        </option>
                        <option value="zh-TW"
                            <?php selected(($piereg['piereg_recaptcha_language']=="zh-TW"), true, true); ?>>
                            <?php _e("Chinese (Traditional)","pie-register"); ?>
                        </option>
                        <option value="en"
                            <?php selected(($piereg['piereg_recaptcha_language']=="en"), true, true); ?>>
                            <?php _e("English (US)","pie-register"); ?>
                        </option>
                        <option value="fr"
                            <?php selected(($piereg['piereg_recaptcha_language']=="fr"), true, true); ?>>
                            <?php _e("French","pie-register"); ?>
                        </option>
                        <option value="fr-CA"
                            <?php selected(($piereg['piereg_recaptcha_language']=="fr-CA"), true, true); ?>>
                            <?php _e("French (Canadian)","pie-register"); ?>
                        </option>
                        <option value="de"
                            <?php selected(($piereg['piereg_recaptcha_language']=="de"), true, true); ?>>
                            <?php _e("German","pie-register"); ?>
                        </option>
                        <option value="de-AT"
                            <?php selected(($piereg['piereg_recaptcha_language']=="de-AT"), true, true); ?>>
                            <?php _e("German (Austria)","pie-register"); ?>
                        </option>
                        <option value="de-CH"
                            <?php selected(($piereg['piereg_recaptcha_language']=="de-CH"), true, true); ?>>
                            <?php _e("German (Switzerland)","pie-register"); ?>
                        </option>
                        <option value="pl"
                            <?php selected(($piereg['piereg_recaptcha_language']=="pl"), true, true); ?>>
                            <?php _e("Polish","pie-register"); ?>
                        </option>
                        <option value="pt"
                            <?php selected(($piereg['piereg_recaptcha_language']=="pt"), true, true); ?>>
                            <?php _e("Portuguese","pie-register"); ?>
                        </option>
                        <option value="pt-BR"
                            <?php selected(($piereg['piereg_recaptcha_language']=="pt-BR"), true, true); ?>>
                            <?php _e("Portuguese (Brazil)","pie-register"); ?>
                        </option>
                        <option value="pt-PT"
                            <?php selected(($piereg['piereg_recaptcha_language']=="pt-PT"), true, true); ?>>
                            <?php _e("Portuguese (Portugal)","pie-register"); ?>
                        </option>
                        <option value="ru"
                            <?php selected(($piereg['piereg_recaptcha_language']=="ru"), true, true); ?>>
                            <?php _e("Russian","pie-register"); ?>
                        </option>
                        <option value="es"
                            <?php selected(($piereg['piereg_recaptcha_language']=="es"), true, true); ?>>
                            <?php _e("Spanish","pie-register"); ?>
                        </option>
                        <option value="es-419"
                            <?php selected(($piereg['piereg_recaptcha_language']=="es-419"), true, true); ?>>
                            <?php _e("Spanish (Latin America)","pie-register"); ?>
                        </option>
                        <option value="tr"
                            <?php selected(($piereg['piereg_recaptcha_language']=="tr"), true, true); ?>>
                            <?php _e("Turkish","pie-register"); ?>
                        </option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>