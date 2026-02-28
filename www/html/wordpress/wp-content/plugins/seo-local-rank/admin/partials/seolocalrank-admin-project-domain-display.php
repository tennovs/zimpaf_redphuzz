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
    
    <h1><?= esc_html_e('Welcome to True Ranker!!!', 'seolocalrank' )?></h1> 
    <p><?= esc_html_e('You can track your websites keywords and view the positions of this in Google.', 'seolocalrank' )?></p>
    
     
   
    
    <div class="slr-higher">	
        
         <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active slr-active" href="#" title="<?= esc_html_e("Keywords list", 'seolocalrank' )?>"><?= esc_html_e("Keywords list", 'seolocalrank' )?></a>
            <!--<a class="nav-tab" href="#" title="<?= esc_html_e("Keyword stats", 'seolocalrank' )?>"><?= esc_html_e("Keyword stats", 'seolocalrank' )?></a>-->
            <a class="nav-tab" href="<?= admin_url('admin.php?page=seolocalrank-add-keyword&domainId='.$domainId.'&domainName='.$domainName)?>" title="<?= esc_html_e("Add keyword", 'seolocalrank' )?>"><?= esc_html_e("Add keyword", 'seolocalrank' )?></a>
        </h2>
        <div class="slr-alert slr-critical" style="display:<?=esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?= esc_html($this->error)?></h3>
        </div>
        
          
        
        <div class="slr-box">
            
            
            <!--<div class="slr-align-right">
                <form name="slr_add_project" action="https://seolocalrank.com/login" method="POST" target="_blank">

                    <input type="submit" class="slr-button slr-is-primary" value="<?= esc_html_e('Add new keyword', 'seolocalrank' )?>">
                </form>						
            </div>-->
            
            
            
            
            
            <div class="slr-boxes keywords" style="margin-top: 30px;">
                
                <div class="slr-box">

                    <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><a href="<?= admin_url('admin.php?page='.$this->domainListPage)?>"><?= esc_html_e("Domains list", 'seolocalrank' )?></a> / <?= esc_html($domainName)?></h2>

                </div> 
                
                <div class="slr-box">
                
                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                          <tr>
                            <th scope="col" class="table-th"><?= esc_html_e("keyword", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th"><?= esc_html_e("City / Location", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th text-center"><?= esc_html_e("Position", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th"><?= esc_html_e("In first position", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th text-center"><?= esc_html_e("Update at", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th text-center"><?= esc_html_e("Automatic tracking", 'seolocalrank' )?></th>
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($keywords as $keyword){
                            ?>
                            <tr class="keyword_row" id="<?= esc_html($keyword["id"])?>">
                                <td><a href="<?= admin_url('admin.php?page=seolocalrank-keyword&tracking_keyword_id='.esc_html($keyword["id"]))?>"><?= esc_html($keyword["keyword"])?></a></td>
                                <td><?= esc_html($keyword["province"])?></td>
                                
                                <?php if($keyword["rank_calculated"]){ ?>
                                <td class="text-center rank_position" ><?= $keyword["rank"] > 0 ? esc_html($keyword["rank"]) : esc_html('+100') ?></td>
                                <td><a href="<?= count($keyword["competition"]) > 0 ? esc_html($keyword["competition"][0]["url"]) : esc_html(' - ')?>" target="_blank"><?= count($keyword["competition"]) > 0 ? esc_html($keyword["competition"][0]["domain"]) : esc_html(' - ')?></a></td>
                                <td class="text-center last_search"><?= esc_html($keyword["last_search"])?></td>
                                <?php }else{?>
                                <td class="text-center rank_position" ><?= esc_html_e("Pending", 'seolocalrank' )?></td>
                                <td></td>
                                <td class="text-center last_search"></td>
                                <?php }?>
                                <td class="text-center" <?= esc_html($keyword["paused_at"])?>>
                                    <input class="keyword_state" type="checkbox" <?= empty($keyword["paused_at"]) ? esc_html('checked') : esc_html('')?>>
                                </td>
                                <td>
                                   <span class="trash submitupdate">Actualizar | </span><span class="trash submitdelete">Eliminar </span>
                                </td>
                            </tr>
                            <?php   
                                }

                            ?>

                        </tbody>
                    </table>
                </div>        
            
                        
                      
                            
                       
                        
                        
                
            </div>
            
               
        </div>
        
        
        
      
        
        
        
      
       
    </div>    
    
</div>

