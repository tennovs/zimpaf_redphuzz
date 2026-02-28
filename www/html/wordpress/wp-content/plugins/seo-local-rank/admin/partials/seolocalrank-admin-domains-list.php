<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="slr-plugin-container">
    <div class="slr_header">
        <image src="<?= esc_html($this->logo) ?>" />
    </div>
    
   
   
    <div class="slr-higher">	
        
         <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active slr-active" href="#" title="<?= esc_html_e("Domains list", 'seolocalrank' )?>"><?= esc_html_e("Domains list", 'seolocalrank' )?></a>
            <a class="nav-tab" href="<?= admin_url('admin.php?page=seolocalrank-add-domain')?>" title="<?= esc_html_e("Add domain", 'seolocalrank' )?>"><?= esc_html_e("Add domain", 'seolocalrank' )?></a>
        </h2>
        
        <div class="slr-alert slr-critical" style="display:<?=esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?= esc_html($this->error)?></h3>
        </div>
        
          
        
        <div class="slr-box">

            <div class="slr-boxes keywords" style="margin-top: 30px;">
                
                <div class="slr-box">

                    <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><?= esc_html_e('Domains List', 'seolocalrank' )?></h2>

                </div> 
                
                <div class="slr-box">
                
                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                          <tr>
                            <th scope="col" class="table-th"><?= esc_html_e("Domain", 'seolocalrank' )?></th>
                            
                            <th scope="col" class="table-th text-center"><?= esc_html_e("Tracking keywords number", 'seolocalrank' )?></th>
                            
                            <th scope="col"></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($domains as $domain){
                            ?>
                            <tr class="domain_row" project_domain_id="<?= esc_html(domain["project_domain_id"])?>" id="<?= esc_html($domain["project_domain_id"])?>">
                                <td><a href="<?= admin_url('admin.php?page=seolocalrank-project-domain&domainId='.esc_html($domain["project_domain_id"]).'&domainName='.esc_html($domain["name"]))?>"><?= esc_html($domain["name"])?></a></td>
                                
                                <td class="text-center"><?= esc_html($domain["num_keywords"])?></td>
                            
                                <td style="text-align:right;">
                                   <span class="trash submitdelete">Eliminar </span>
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


