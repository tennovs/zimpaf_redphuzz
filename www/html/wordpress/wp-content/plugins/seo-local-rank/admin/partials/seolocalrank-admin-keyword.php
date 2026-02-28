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
            <a class="nav-tab" href="<?= admin_url('admin.php?page=seolocalrank-project-domain&domainId='.$keyword["project_domain_id"].'&domainName='.'&domainName='.$domain["name"])?>" title="<?= esc_html_e("Keywords list", 'seolocalrank' )?>"><?= esc_html_e("Keywords list", 'seolocalrank' )?></a>
            <a class="nav-tab nav-tab-active slr-active" href="#" title="<?= esc_html_e("Keyword stats", 'seolocalrank' )?>"><?= esc_html_e("Keyword stats", 'seolocalrank' )?></a>
            <a class="nav-tab" href="<?= admin_url('admin.php?page=seolocalrank-add-keyword&domainId='.$domain["id"].'&domainName='.$domain["name"])?>" title="<?= esc_html_e("Add keyword", 'seolocalrank' )?>"><?= esc_html_e("Add keyword", 'seolocalrank' )?></a>
        </h2>
        
        <div class="slr-box">
            <div class="slr-alert slr-critical" style="display:<?=esc_html($this->displayError) ?>">
                <h3 class="slr-key-status"><?= esc_html($this->error)?></h3>
            </div>


            <div class="slr-boxes keywords" style="margin-top: 30px;">

                <div class="slr-box">

                    <h2 style="padding:1.5rem 1.5rem 1.5rem 1.5rem"><span class="slr-circle-position"><?= $keyword["rank"] > 0 ? esc_html($keyword["rank"]) : esc_html('+100') ?></span><?= esc_html($domain["name"])?>, <?= esc_html($keyword["keyword"]) ?> (<?= esc_html($keyword["province"])?>)</h1>

                </div>  

                <div class="slr-box" style="padding-top: 0.5rem;">
                    <h3> <?= esc_html_e('Position of your keyword in the selected location over time', 'seolocalrank' )?></h3>
                    <div id="chart" style="width: 96%; height: 400px; margin: 0 auto;margin-bottom: 20px;"></div>
                </div>

                <div class="slr-box">
                    <h3><?= esc_html_e('Best page', 'seolocalrank' )?></h3>


                    <p>
                        <a href="<?= esc_html($keyword["url"])?>" target="_blank"><?=esc_html( $keyword["url"])?></a>
                    </p>    
                </div>

                <div class="slr-box">
                    <h3><?= esc_html_e('Best positions', 'seolocalrank' )?></h3>


                    <table class="wp-list-table widefat fixed striped pages">
                        <thead>
                          <tr>
                            <th scope="col" class="table-th text-center" style="width:10%"><?= _e("position", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th"><?= esc_html_e("domain", 'seolocalrank' )?></th>
                            <th scope="col" class="table-th"><?= esc_html_e("url", 'seolocalrank' )?></th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($keyword["competition"] as $competition){
                            ?>
                            <tr class="keyword_row" id="<?= esc_html($keyword["id"])?>">
                                <td class="text-center rank_position"><?= esc_html($competition["position"])?></td>
                                <td><?= esc_html($competition["domain"])?></td>
                                <td >
                                    <a href="<?= esc_html($competition["url"]) ?>" target="_blank"><?= esc_html($competition["url"])?></a>

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

<script type="text/javascript">
    jQuery(document).ready( function(){
        getKeywordHistory(<?= esc_html($domain["id"])?>,<?= esc_html($keyword["keyword_province_id"])?>,'<?= esc_html($keyword["keyword"])?>');
        
       
    });
</script>    
    