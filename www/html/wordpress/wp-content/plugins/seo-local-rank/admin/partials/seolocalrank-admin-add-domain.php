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
        
        <h2 class="nav-tab-wrapper">
            <a id="domains_list" class="nav-tab" href="<?= admin_url('admin.php?page='.$this->domainListPage)?>" title="<?= esc_html_e("Domains list", 'seolocalrank' )?>"><?= _e("Domains list", 'seolocalrank' )?></a>
            <a class="nav-tab nav-tab-active slr-active" href="" title="<?= _e("Add domain", 'seolocalrank' )?>"><?= esc_html_e("Add domain", 'seolocalrank' )?></a>
        </h2>
        
        <div class="slr-alert slr-critical" style="display:<?= esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?= esc_html($this->error)?></h3>
        </div>
        
        <div class="slr-box"> 
         <div class="slr-boxes keywords" style="margin-top: 30px;">
            <div class="slr-box">

                <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><?= esc_html_e("Add domain", 'seolocalrank' )?></h2>

            </div> 
        
            <div class="slr-box" id="add-domain-form-box">

                <h4><?= esc_html_e("add a new domain to track their keywords ", 'seolocalrank' )?></h4>
                
                <div style="padding:1.5rem 1.5rem 1.5rem 1.5rem">
                    <table class="form-table" >
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?= esc_html_e("Domain name", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <input name="domain" type="text" id="domain" value="" class="regular-text" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">
                                    <p class="description domain_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?= esc_html_e("Enter a valid domain please", 'seolocalrank' )?></p>    
                                    <p class="description" id="timezone-description" style="padding:0px;"><?= esc_html_e("Enter the name of your domain. Do not enter before of the name \"http://\" or \"https://\" as it is not necessary for our system.", 'seolocalrank' )?></p>

                                </td>
                            </tr>
                           
                          
                        </tbody>
                    </table>

                </div>    
                <p class="submit"><input type="submit" name="submit" id="submit-add-domain" class="button button-primary" value="<?= esc_html_e("Add domain", 'seolocalrank' )?>"></p>
                
                
            </div>
             
             
            <div class="slr-box" id="loader-box">
                 <img class="loader" src="<?= esc_html($this->loader)?>"/>
                 <p><?= esc_html_e("We are adding the domain at your project", 'seolocalrank' )?></p>
                 <p><?= esc_html_e("This process may take a few minutes", 'seolocalrank' )?></p>
                 
            </div>
         </div>    
            
               
        </div>
 
          
       
    </div>    
    
</div>

<script type="text/javascript">
    
    jQuery(document).ready( function(){
        
        jQuery('#submit-add-domain').click(function(){
           sendAddDomainForm(); 
        });
    });
</script>    
    