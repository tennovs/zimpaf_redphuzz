<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://seolocalrank.com
 * @since      1.0.0
 *
 * @package    seolocalrank
 * @subpackage seolocalrank/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    seolocalrank
 * @subpackage seolocalrank/admin
 * @author     Optimizza <proyectos@optimizza.com>
 */


?>



<div id="slr-plugin-container">
    
    <img src="<?php echo esc_html("https://app.trueranker.com/new-landing/img/tr-logo.png") ?>" />

    <div class="slr_header" style="border: 1px solid #ccc;">
        <h2><?php echo esc_html_e('TrueRanker', 'seolocalrank' )?></h2>
    </div>
    
    <div class="slr-higher">	
        
        <div class="slr-alert slr-critical" style="display:<?php echo esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?php echo esc_html($this->error)?></h3>
        </div>
        
        
        <div class="slr-box"> 
            <p>
            <?php echo esc_html_e('Welcome to TrueRanker. With this plugin you can know in what position your website is with your keywords in the cities of the world you want. ', 'seolocalrank' )?>
            </p>
            <p>
            <?php echo esc_html_e('You can track your website daily to find out if you win or lose positions on Google. ', 'seolocalrank')?><br>
            </p>
            <p>
            <?php echo esc_html_e('Also know who are your main competitors in each city, study them and improve your strategy to overcome them.', 'seolocalrank')?>
            </p>    
            <p>
                <?php echo esc_html_e('True Ranker is the only plugin that gives you your real Google position in each city. We do not use any type of proxy server. We have thousands of devices and servers all over the world that are in charge of doing this kind of queries so that our artificial intelligence algorithm gives you the exact results.', 'seolocalrank' )?>
            </p>

        </div>
        
        
        
        <div class="slr-boxes">
            <div class="slr-box" id="get-api-key">
                <h3><?php echo esc_html_e('1. Activate TrueRanker', 'seolocalrank' )?></h3>
                

                <p>
                    <?php echo esc_html_e('Enter your email to start.', 'seolocalrank' )?><br>
                </p>
               
                
                 <div id="get-api-key-form">
                    
                    <p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;padding-bottom: 5px;">
                        <input placeholder="<?php echo esc_html_e('Enter your email', 'seolocalrank' )?>" id="email" name="email" type="email" value="<?php echo esc_attr($admin_email)?>" class="regular-text code" style="max-width: 500px;flex-grow: 1; margin-right: 1rem;">
                        <input type="submit" name="submit" id="submit-start" class="slr-button slr-is-primary" value="<?php echo esc_html_e('Start True Ranker', 'seolocalrank' )?>">
                    </p>
                    <p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;padding-bottom: 5px;margin-top: 5px;">
                        <input type="checkbox" name="terms" id="terms" style="    margin-top: 5px;margin-right: 3px;" /><?php echo esc_html_e('You agree to TrueRanker', 'seolocalrank') ?> <a href="<?php echo esc_html($privacy_url) ?>" target="_blank" style="margin-left:5px;"><?php echo esc_html_e('Terms of Service and Privacy Policy', 'seolocalrank') ?></a>
                       
                    </p>
                    <p class="description email_error"  style="color:red;visibility: hidden;"><?php echo esc_html_e("Enter a valid email please", 'seolocalrank' )?></p>   
                     <p class="description terms_error"  style="color:red;visibility: hidden;"><?php echo esc_html_e("You should accept the Terms and privacy policy of TrueRanker", 'seolocalrank' )?></p>  
                    <p class="description email_success"  style="color:green;display: none;"></p>   

                </div> 
                
                <div style="text-align:center;">
                    <img class="loader" style="display:none;" src="<?php esc_html($this->loader)?>"/>
                </div>  
                
                
            </div>
            <div class="slr-box" id="send-api-key" style="display:none;">
                <h3><?php echo esc_html_e('2. Enter your personal API key', 'seolocalrank' )?></h3>
                <p>
                    <?php echo esc_html_e('Copy the API key that we have sent to your email and paste it below.', 'seolocalrank' )?>
                    
                </p>
                
                <div class="">
                    <form action="#" method="POST">
                            <p style="width: 100%; display: flex; flex-wrap: nowrap; box-sizing: border-box;">
                                <input placeholder="<?php echo esc_html_e('Copy here your API key', 'seolocalrank' )?>" id="key" name="key" type="text" size="15" value="" class="regular-text code" style="max-width: 500px;flex-grow: 1; margin-right: 1rem;">
                                <input type="submit" name="submit" id="submit" class="slr-button slr-is-primary" value="<?php echo esc_html_e('Check API key', 'seolocalrank' )?>">
                            </p>
                    </form>
                </div>    
            </div>
            
           
        </div>
    </div>    
    
</div>

<script type="text/javascript">
    
    jQuery(document).ready( function(){
        initSelectProvince();
        jQuery('#submit-start').click(function(){
           startSlr();
        });
        
        //clean wp alerts
        cleanWpAlerts();
        
    });
</script>    

