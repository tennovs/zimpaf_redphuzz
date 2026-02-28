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
    <div class="slr_header">
        <image src="<?= esc_html($this->logo) ?>" />
    </div>
        
    
    <div class="slr-higher">	
        
      
        
        <div class="slr-alert slr-critical" style="display:<?=esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?= esc_html($this->error)?></h3>
        </div>
        
        <div class="slr-box"> 
         <div class="slr-boxes keywords" style="margin-top: 30px;">
            <div class="slr-box">

                <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><?= esc_html_e("Help", 'seolocalrank' )?></h2>

            </div> 
        
            <div class="slr-box" id="contact-form-box">

                <h4><?= esc_html_e("If you need help you can contact with us from next form", 'seolocalrank' )?></h4>
                
                <div style="padding:1.5rem 1.5rem 1.5rem 1.5rem">
                    <table class="form-table" >
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?= esc_html_e("Subject", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <input name="subject" type="text" id="subject" value="" class="regular-text" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">
                                    <p class="description subject_error"  style="padding:0px;color:red;display:none;"><?= esc_html_e("Enter a valid subject please", 'seolocalrank' )?></p>    
                                    <p class="description"  style="padding:0px;"><?= esc_html_e("Enter the subject of your question", 'seolocalrank' )?></p>

                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?= esc_html_e("Message", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <textarea name="message" type="text" id="message" value="" class="regular-text" style="height:200px;text-align: left;background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;" ></textarea>
                                    <p class="description message_error"  style="padding:0px;color:red;display:none;"><?= esc_html_e("Enter a valid message please", 'seolocalrank' )?></p>    
                                    <p class="description" style="padding:0px;"><?= esc_html_e("Describe your doubt or your problem", 'seolocalrank' )?></p>

                                </td>
                            </tr>
                           
                          
                        </tbody>
                    </table>

                </div>    
                <p class="submit"><input type="submit" name="submit-contact" id="submit-contact" class="button button-primary" value="<?= esc_html_e("Send", 'seolocalrank' )?>"></p>
                
                
            </div>
             
             
            <div class="slr-box" id="loader-box">
                 <img class="loader" src="<?= esc_html($this->loader)?>"/>
                 <p><?= esc_html_e("We are sending your form", 'seolocalrank' )?></p>
                 <p><?= esc_html_e("This process may take a few seconds", 'seolocalrank' )?></p>
                 
            </div>
             
            <div class="slr-box" id="contact-send-success-box" style="color: #32b732;text-align: center;display: none;">
                <h3><?= esc_html_e("Form sent success", 'seolocalrank' )?></h3>
                    
                <p><?= esc_html_e("We will contact you as soon as possible by answering your question to the email","seolocalrank") ?> <?= esc_html($_SESSION["slr_user"]["email"])?>.<br>
                <?= esc_html_e("The response usually takes less than 24 hours.", 'seolocalrank' )?></p>        

            </div>
         </div>    
            
               
        </div>
 
          
       
    </div>    
    
</div>

<script type="text/javascript">
    
    jQuery(document).ready( function(){
        
        jQuery('#submit-contact').click(function(){
           sendContactForm(); 
        });
    });
</script>    