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
    
    <h1><?= esc_html_e('Welcome to SEO Local Rank!!', 'seolocalrank' )?></h1>
    <?= esc_html_e('You can track your websites keywords and view the positions of this in Google.', 'seolocalrank' )?>
    
     
   
    
    <div class="slr-higher">	
        
         <h2 class="nav-tab-wrapper">
            <a class="nav-tab" id="kw_list" href="<?= admin_url('admin.php?page=seolocalrank-project-domain&domainId='.esc_html($domainId).'&domainName='.esc_html($domainName))?>" title="<?= esc_html_e("Keywords list", 'seolocalrank' )?>"><?= esc_html_e("Keywords list", 'seolocalrank' )?></a>
            <a class="nav-tab nav-tab-active slr-active" href="#" title="<?= esc_html_e("Add keyword", 'seolocalrank' )?>"><?= esc_html_e("Add keyword", 'seolocalrank' )?></a>
        </h2>
        <div class="slr-alert slr-critical" style="display:<?=esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?= esc_html($this->error)?></h3>
        </div>
        
          <div class="slr-box"> 
         <div class="slr-boxes keywords" style="margin-top: 30px;">
            <div class="slr-box">

                <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><?= esc_html($domainName)?></h2>

            </div> 
        
            <div class="slr-box" id="add-keyword-form-box">

                <h4><?= _e("Add new keyword at domain", 'seolocalrank' )?> <?= esc_html($domainName) ?></h4>
                
                <div style="padding:1.5rem 1.5rem 1.5rem 1.5rem">
                    <table class="form-table" >
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="blogname"><?= esc_html_e("Keyword", 'seolocalrank' )?></label>
                                </th>
                                <td>
                                    <input name="keyword" type="text" id="keyword" value="" class="regular-text" style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;">
                                     <p class="description keyword_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?= esc_html_e("Enter a valid keyword please", 'seolocalrank' )?></p>    

                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="timezone_string"><?= esc_html_e("Country", 'seolocalrank' )?></label>
                                </th>
                                <td>

                                    <select class="select_country" id="country" name="country" aria-describedby="timezone-description" style="width:320px;">
                                        <option value="0"><?= esc_html_e("Choose country",'seolocalrank') ?></option>
                                        <?php
                                            foreach ($countries as $country){

                                        ?>
                                            <option value="<?= esc_html($country["id"]) ?>"><?= esc_html($country["name"]) ?></option>
                                        <?php   
                                            }

                                        ?>
                                    </select>
                                    <p class="description country_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?= _e("Choose a country please", 'seolocalrank' )?></p>    
                                    <p class="description" id="timezone-description" style="padding:0px;"><?= esc_html_e("Choose the country of the city where you want to track the keywords", 'seolocalrank' )?></p>
                                </td>

                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="timezone_string"><?= esc_html_e("Cities", 'seolocalrank' )?></label>
                                </th>
                                <td>

                                    <select name="cities" class="select2-province form-control" multiple style="width:100%;" data-placeholder="<?= esc_html_e("Choose cities", 'seolocalrank' )?>" data-availablekeywords="<?= esc_html($availableKeywords)?>" disabled>
                                    </select>
                                    
                                    <p class="description cities_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?= esc_html_e("Choose the citie or the cities where you want tracking the keyword", 'seolocalrank' )?></p>      
                                    <p class="description" id="timezone-description" style="padding:0px;"><?= esc_html_e("Choose the cities where you want to track the keywords", 'seolocalrank' )?></p>
                                    <p class="description choose_country_error" id="timezone-description" style="padding:0px;color:red"><?= esc_html_e("Before you need to choose a country", 'seolocalrank' )?></p>
                                    <p class="description available_keywords_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?= esc_html_e('You are currently using all the keywords that your plan supports. Get a superior plan and add more keywords.', 'seolocalrank' )?></p>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                </div>    
                <input type="hidden" id="project-domain-id" name="project-domain-id" value="<?= esc_html($domainId)?>"/>
                <p class="submit"><input type="submit" name="submit" id="submit-add-keyword" class="button button-primary" value="<?= _e("Add keyword", 'seolocalrank' )?>"></p>
                
                


               
                    
                
                

            </div>
             
             
            <div class="slr-box" id="loader-box">
                 <img class="loader" src="<?= esc_html($this->loader)?>"/>
                 <p><?= _e("We are calculating the position of the keywords you just entered", 'seolocalrank' )?></p>
                 <p><?= _e("This process may take a few minutes", 'seolocalrank' )?></p>
                 
            </div>
         </div>    
            
               
        </div>
 
          
       
    </div>    
    
</div>

<script type="text/javascript">
    
    jQuery(document).ready( function(){
        initSelectProvince();
        jQuery('#submit-add-keyword').click(function(){
           sendAddKeywordForm(); 
        });
    });
</script>    
    