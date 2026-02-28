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
    
    <img src="<?php echo esc_html("https://app.trueranker.com/new-landing/img/tr-logo.png") ?>" style="margin-top: 50px;" />
    <div class="slr_header">
       
    </div>
        
    
    <div class="slr-higher">	
        
       
       
        
        <div class="slr-box"> 
         <div class="slr-boxes keywords" style="margin-top: 30px;">
            <div class="slr-box">

                <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><?php echo esc_html_e("Settings", 'seolocalrank' )?></h2>

            </div> 
        
            <div class="slr-box" id="add-domain-form-box">

               
                
                <div style="padding:1.5rem 1.5rem 1.5rem 1.5rem">
                    <table class="form-table" >
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?php echo  esc_html_e("Plugin version", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <input disabled type="text"  value="<?php echo  esc_html_e(SEOLOCALRANK_PLUGIN_NAME_VERSION)?>" class="regular-text" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">    

                                </td>
                            </tr>
                            
                             <tr>
                                <th scope="row">
                                    <label for="blogname"><?php echo esc_html_e("Email account", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <input disabled type="text"  value="<?php echo esc_html($_SESSION["slr_user"]["email"])?>" class="regular-text" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">    
                                    <p class="description" id="timezone-description" style="padding:0px;"><?php echo esc_html_e("Email associated with your True Ranker account", 'seolocalrank' )?></p>

                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?php echo esc_html_e("Current plan", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <input disabled type="text"  value="<?php echo esc_html($_SESSION["slr_user"]["plan"]["name"]).' '.esc_html($_SESSION["slr_user"]["plan"]["max_tracking_keywords"])?> <?php echo esc_html_e("Keywords","seolocalrank")?>" class="regular-text" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">    
                                    <?php if($_SESSION["slr_user"]["plan_expiration_formatted"] != ""){ ?>
                                        <p class="description" id="timezone-description" style="padding:0px;"><?php echo esc_html_e("Your plan will be self-renewed on the following date:", 'seolocalrank' )?> <?php echo esc_html($_SESSION["slr_user"]["plan_expiration_formatted"])?></p>
                                    <?php }?>    
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?php echo esc_html_e("Privacy policy", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                   
                                    <p class="description" id="timezone-description" style="padding:0px;"><a href="<?php echo esc_html($privacy_url) ?>" target="_blank"><?php echo esc_html_e("Visit privacy policy", 'seolocalrank' )?></a></p>
                                    
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?php echo esc_html_e("Sign out from True Ranker", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                   
                                    <p class="description" id="timezone-description" style="padding:0px;"><a href="<?php echo admin_url('admin.php?page=seolocalrank-signout')?>"><?php echo esc_html_e("Sign out", 'seolocalrank' )?></a></p>
                                    
                                </td>
                            </tr>
                           
                          
                        </tbody>
                    </table>

                </div>    
                
                
            </div>
             
             
            
         </div>    
            
               
        </div>
 
          
       
    </div>    
    
</div>

<script type="text/javascript">
    
    jQuery(document).ready( function(){

        cleanWpAlerts();
    });
</script>    