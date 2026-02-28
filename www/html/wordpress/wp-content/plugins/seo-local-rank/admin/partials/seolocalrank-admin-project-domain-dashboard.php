<?php

/**
 * The dashboard project functionality of the plugin.
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


function printSnippets($keyword)
{
    
    if($keyword["knowledge_card_snp"] == 1 )
    {
        echo '<i class="far fa-clone p2" data-toggle="tooltip" data-placement="top" title="Knowledge Card"></i>';
    }
    
    if($keyword["featured_snp"] == 1 )
    {
        
        echo '<i class="fas fa-puzzle-piece p2" data-toggle="tooltip" data-placement="top" title="Features Snippet"></i>';
    }
    
    if($keyword["maps_local_snp"] == 1 )
    {
       
        echo '<i class="fas fa-map-marker-alt p2" data-toggle="tooltip" data-placement="top" title="Local Map"></i>';
    }
    
    if($keyword["ads_top_snp"] == 1 )
    {
       
        echo '<i class="fas fa-dollar-sign p2" data-toggle="tooltip" data-placement="top" title="Google Ads"></i>';
    }
    
    if($keyword["shopping_snp"] == 1 )
    {
        echo '<i class="fas fa-shopping-cart p2" data-toggle="tooltip" data-placement="top" title="Shopping"></i>';
    }
    
    if($keyword["news_snp"] == 1 )
    {
        echo '<i class="far fa-newspaper p2" data-toggle="tooltip" data-placement="top" title="News"></i>';
    }
    
    if($keyword["image_pack_snp"] == 1 )
    {
        echo '<i class="far fa-images p2" data-toggle="tooltip" data-placement="top" title="Images Search"></i>';
    }
    
    if($keyword["video_carousel_snp"] == 1 )
    {
        echo '<i class="fab fa-youtube p2" data-toggle="tooltip" data-placement="top" title="Video Carousel"></i>';
    }
    
    
}


?>
<div id="slr-plugin-container">
<style>
    
    .p2{
        padding: 2px;
    }
    .sidenav {
  height: 100%; /* 100% Full-height */
  width: 0; /* 0 width - change this with JavaScript */
  position: fixed; /* Stay in place */
  z-index: 1; /* Stay on top */
  
  right: 0;
  
  overflow-x: hidden; /* Disable horizontal scroll */
  padding-top: 20px; /* Place content 60px from the top */
  transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
  
  top: 0px;
    
    border-left: 1px solid #ddd;
    background: #f8f8f8;
    z-index: 300;
}

/* The navigation menu links */
.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

/* When you mouse over the navigation links, change their color */
.sidenav a:hover {
  color: #f1f1f1;
}

/* Position and style the close button (top right corner) */
.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

/* Style page content - use this if you want to push the page content to the right when you open the side navigation */
#main {
  transition: margin-left .5s;
  padding: 20px;
}

.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 0px;
    line-height: 1.42857143;
    vertical-align: middle;
    border-top: 1px solid #ddd;
    font-size: 12px;
}

table.dataTable thead>tr>th {
    padding-left: 10px;
    padding-right: 10px;
    font-size: 12px;
}

.choose-screen button.selected{
    opacity: 1;
}

.choose-screen button{
    opacity: 0.5;
}
/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}

  #modal-kw-stats .modal-dialog
    {
        width: 80%;
        margin: 50px auto;
        max-width: none;
    }
    
    @media only screen and (max-width: 980px) {
        #modal-kw-stats .modal-dialog
        {
            width: 94%;
            margin: 50px auto;
        }
    }
    
    .modal-open .modal {
    overflow-x: hidden;
    overflow-y: auto;
    z-index: 30000;
}
</style>



    
   <div class="page-header">
        <h1><?php echo esc_html($domain["name"])?></h1>
    </div>
    
    
              
                <?php if(isset($coupon)){?>
        <div class="slr-alert slr-critical" style="background-color:#d4389ab8;text-align: center;">
                <h2 class="slr-key-status" style="color:white; font-weight:300">
                    <?php 
                        echo esc_html_e(round($coupon["discount"],0)."% ");
                        echo esc_html_e("OFF DISCOUNT FOREVER", 'seolocalrank');
                    ?></h2>
            
                <h2 class="slr-key-status" style="color:white; font-weight:bold">
                    <?php 
                        
                        echo esc_html_e("COUPON: ", 'seolocalrank');
                        echo esc_html_e($coupon["code"]);
                    ?></h2> 
            <a href="<?php echo esc_html_e($coupon_link) ?>" target="_blank">
                <button style="background: white;border: 1px solid white;color: #D4389A;text-decoration: none;padding: 20px;border-radius: 10px;width: 200px;font-size: 15px;font-weight: bold;margin: 0 auto;cursor:pointer;">
                    <?php echo esc_html_e("APPLY COUPON", 'seolocalrank') ?>
                </button>
            </a>
            
            <p style="text-align:center;color:white">
            <?php echo esc_html_e("Ends in", 'seolocalrank');?>
            <?php
                $hours_remaining = round($coupon["seconds_remaining"]/3600);
                $minutes_remaining = round($coupon["seconds_remaining"]/60);
                
                if($hours_remaining > 0)
                {
                    echo esc_html_e($hours_remaining);
                    echo esc_html_e(" hours", 'seolocalrank');
                }
                else if($minutes_remaining > 0)
                {
                    echo esc_html_e($minutes_remaining);
                    echo esc_html_e(" minutes", 'seolocalrank');
                }
                else
                {
                    echo esc_html_e($seconds_remaining);
                    echo esc_html_e(" secs", 'seolocalrank');
                   
                }
            ?>
            
        </p>
        </div>
        
        <?php }?>
                
         
        <div class="slr-higher-new" id="main">
        
        <!-- keywords position summary -->
        <div class="row" style="overflow: hidden;">
            <div class="col-sm-3">
                <div class="slr-top-keywords slr-bg-success slr-text-center slr-p5">
                    <div class="slr-num-keywords slr-bg-white slr-pt20 slr-pb20"><?php echo esc_html($tops["top1"]) ?> <?php echo esc_html_e("Keywords", 'seolocalrank' )?></div>
                    <div class="slr-mt5"><strong><?php echo esc_html_e("Top 1-3", 'seolocalrank' )?></strong></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="slr-top-keywords slr-bg-warning slr-text-center slr-p5">
                    <div class="slr-num-keywords slr-bg-white slr-pt20 slr-pb20"><?php echo esc_html($tops["top4"]) ?> <?php echo esc_html_e("Keywords", 'seolocalrank' )?></div>
                    <div class="slr-mt5"><strong><?php echo esc_html_e("Top 4-10", 'seolocalrank' )?></strong></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="slr-top-keywords slr-bg-danger slr-text-center slr-p5">
                    <div class="slr-num-keywords slr-bg-white slr-pt20 slr-pb20"><?php echo esc_html($tops["top11"]) ?> <?php echo esc_html_e("Keywords", 'seolocalrank' )?></div>
                    <div class="slr-mt5"><strong><?php echo esc_html_e("Top 11-100", 'seolocalrank' )?></strong></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="slr-top-keywords slr-bg-dark slr-text-center slr-p5">
                    <div class="slr-num-keywords slr-bg-white slr-pt20 slr-pb20"><?php echo esc_html($tops["top101"]) ?> <?php echo esc_html_e("Keywords", 'seolocalrank' )?></div>
                    <div class="slr-mt5"><strong><?php echo esc_html_e("Top +100", 'seolocalrank' )?></strong></div>
                </div>
            </div>
        </div>
        <!-- END keywords position summary -->
        
        
        <!-- Tracking Keywords list row -->
        <div class="slr-row" style="overflow: hidden;">
          
            <!-- Keywords Table -->
            <div class="slr-col-12" id="kw-table-container">
                <div class="slr-btn-group">
                    <button type="button" onclick="openNav()" class="slr-btn slr-btn-sm slr-btn-success slr-btn-block" id="toggle_sidemenu_r">
                    <span class="fa fa-plus" aria-hidden="true"></span> <?php echo esc_html_e("Add Keywords", 'seolocalrank' )?> </button>
                </div>
                
                <table class="table table-hover" style="margin-bottom: 0px !important;margin-top: 0px !important;">
                    <thead class="slr-table-head">
                      <tr class="slr-tr-head">
                        <th class="slr"><?php echo esc_html_e("Keyword", 'seolocalrank' )?></th>
                        <th class="hidden-xs" >
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Location where the search is executed", 'seolocalrank' )?>"><?php echo esc_html_e("Location", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center" >
                            <span ><?php echo esc_html_e("Device", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Current rank", 'seolocalrank' )?>"><?php echo esc_html_e("Position", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Change from previous position", 'seolocalrank' )?>"><?php echo esc_html_e("Change", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Best ever rank in TrueRanker", 'seolocalrank' )?>"><?php echo esc_html_e("Best", 'seolocalrank' )?>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Date of last rank check", 'seolocalrank' )?>"><?php echo esc_html_e("Date", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Aproximate number of Google Searches in one month in the country location", 'seolocalrank' )?>"><?php echo esc_html_e("Volume", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Average cost per click in Google Ads", 'seolocalrank' )?>"><?php echo esc_html_e("CPC", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Estimated visits per month for your current position based on search volume in your country", 'seolocalrank' )?>"><?php echo esc_html_e("EV", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                            <span data-toggle="tooltip" title="<?php echo esc_html_e("Snippets found in Search result for your keyword", 'seolocalrank' )?>"><?php echo esc_html_e("Snippets", 'seolocalrank' )?></span>
                        </th>
                        <th class="slr-text-center">
                         
                            <?php echo esc_html_e("Actions", 'seolocalrank' )?>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php foreach($keywords as $keyword){
                                $rank_class="slr-dark";
                                if($keyword["rank"] >= 1 && $keyword["rank"]< 4)
                                {
                                    $rank_class="slr-success";
                                }
                                else if($keyword["rank"] >= 4 && $keyword["rank"]< 11)
                                {
                                    $rank_class="slr-warning";
                                }
                                else if($keyword["rank"] >= 11 && $keyword["rank"]< 101)
                                {
                                    $rank_class="slr-danger";
                                }
                                    
                         
                         ?>
                            <tr id="<?php echo esc_html($keyword["id"])?>" class="slr_keyword_row" domain-id="<?php echo esc_html($keyword["domain_id"])?>" keyword-province-id="<?php echo esc_html($keyword["keyword_province_id"])?>" keyword-province="<?php echo esc_html($keyword["province"])?>" keyword="<?php echo esc_html($keyword["keyword"])?>" paused="<?php echo (empty($keyword["paused_at"]) ? esc_html('0') : esc_html('1') ) ?>" >
                                <td class="tk-keyword slr-pl10 slr-pr5">
                                    <?php echo (empty($keyword["paused_at"]) ? esc_html('') : '<span  data-toggle="tooltip" data-placement="right" title="'.esc_html("Keywor paused. This keyword is not being tracked. ", 'seolocalrank' ).'"><i class="fas fa-pause pause-icon slr-danger"></i></span>' ) ?>
                                    <?php echo esc_html($keyword["keyword"])?>
                                    <?php
                                    if ($keyword['cannibalization'] == 1) {
                                        echo '<i class="fa fa-bug"></i>';
                                    }
                                    ?>
                                </td>
                                <td class="hidden-xs tk-province"><?php echo esc_html($keyword["province"])?></td>
                                <td class="text-center screen-type">
                                    <?php if($keyword["screen_type"] == 2){?>
                                    <span class="hidden">mobile</span>
                                    <span class="fa fa-mobile-alt" style="color: #4a89dc"></span>
                                    <?php }else{ ?>
                                    
                                    <span class="hidden">desktop</span>
                                    <span class="fa fa-desktop" style="color:#ffb123"></span>
                                    <?php }
                                    ?>
                                </td>
                                <td class="text-center rank">
                                    <?php 
                                        if(isset($keyword["rank"]))
                                        {
                                            if($keyword["rank"] > 0) {
                                                //echo '<span class="slr-hidden">'. esc_html(str_pad($keyword['rank'], 3, '0', STR_PAD_LEFT)).'</span>';
                                                echo '<span class="'.$rank_class.'"><strong>'.esc_html($keyword["rank"]).'</strong></span>';
                                            }
                                            else {
                                                echo '<span class="'.$rank_class.'"><strong>+101</strong></span>';
                                            }
                                        }
                                        else {
                                            //echo esc_html('<span class="slr-hidden">0</span>');
                                            echo esc_html_e("Pending", 'seolocalrank' );
                                        }
                                    ?>
                                </td>
                                <td class="text-center rank-change" >
                                    <?php
                                    if ($keyword['better_rank'] === 0 || empty($keyword["previous_rank"])) {
                                        $change_icon = 'circle';

                                        //echo '<span class="slr-hidden">'.esc_html(100).'</span>';
                                        echo '<span><i class="fa fa-'.esc_html( $change_icon).'"></i></span>';
                                    }
                                    else {
                                        if ($keyword['better_rank'] === 1) {
                                            $change_icon = 'chevron-up';
                                            $change_color = 'slr-bg-success';
                                        }
                                        else {
                                            $change_icon = 'chevron-down';
                                            $change_color = 'slr-bg-danger';
                                        }

                                        //echo '<span class="slr-hidden">'.esc_html(str_pad(($keyword['change'] * ($keyword['better_rank'] < 0 ? -1 : 1) + 100), 3, '0', STR_PAD_LEFT)).'</span>';
                                        echo '<span class="'. esc_html($change_color).'"><i class="fa fa-'. esc_html($change_icon).'"></i> '. esc_html($keyword['change']).'</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-center best-rank">
                                      <?php 
                                      if (isset($keyword["best_rank"])){
                                          if ($keyword["best_rank"] > 0) {
                                              //echo '<span class="slr-hidden">'.esc_html(str_pad($keyword['best_rank'], 3, '0', STR_PAD_LEFT)).'</span>';
                                              echo esc_html($keyword["best_rank"]);
                                          }
                                          else {
                                              echo '+101';
                                          }
                                      }
                                      else {
                                          //echo '<span class="slr-hidden">0</span>';
                                            echo esc_html_e("Pending", 'seolocalrank' );
                                      }
                                      ?>
                                </td>
                                <td class="text-center hidden-xs hidden-sm last_search">
                                    <?php 
                                        if(isset($keyword["last_search"])) {
                                            //echo '<span class="slr-hidden">'.esc_html($keyword['updated_at']).'</span>';
                                            echo esc_html($keyword["last_search"]);
                                        }
                                        else {
                                            //echo '<span class="slr-hidden">0</span>';
                                        echo esc_html_e("Pending", 'seolocalrank' );                                        }
                                    ?>


                                </td>
                                <td class="text-center">
                                    <?php 
                                        if($_SESSION["slr_user"]["plan"]["adwords_data"])
                                        {
                                            if (is_null($keyword['volume']) || $keyword['volume'] === '') { // No se ha calculado
                                            
                                                echo '<span data-toggle="tooltip" data-placement="top" title="'.esc_html("The number of searches may take several hours to appear for new keywords", 'seolocalrank' ).'"><i class="fa fa-hourglass-half"></i></span>';
                                            }
                                            else
                                            {
                                                echo esc_html($keyword["volume"]);
                                            }
                                        }
                                        else 
                                        {
                                            echo '<i class="fas fa-lock"></i>';
                                        }
                                    ?>
                                    
                                </td>

                                <td class="text-center">
                                    <?php 
                                        if($_SESSION["slr_user"]["plan"]["adwords_data"])
                                        {
                                            if (is_null($keyword['cpc']) || $keyword['cpc'] === '') { // No se ha calculado
                                                echo '<span data-toggle="tooltip" data-placement="top" title="'.esc_html("CPC may take several hours to appear for new keywords", 'seolocalrank' ).'"><i class="fa fa-hourglass-half"></i></span>';
  
                                            }
                                            else
                                            {
                                                echo esc_html(str_replace('.',',',round($keyword["cpc"],2).'€'));
                                            }
                                        }
                                        else 
                                        {
                                            echo '<i class="fas fa-lock"></i>';
                                        }
                                    ?>
                                </td>
                                
                                <td class="text-center">
                                    <?php 
                                        if($_SESSION["slr_user"]["plan"]["adwords_data"])
                                        {
                                            if (is_null($keyword['volume']) || $keyword['volume'] === '') {
                                                echo '<span data-toggle="tooltip" data-placement="top" title="'.esc_html("VE may take several hours to appear for new keywords", 'seolocalrank' ).'"><i class="fa fa-hourglass-half"></i></span>';

                                            }
                                            else
                                            {
                                                echo esc_html($keyword["estimated_visits"]);
                                            }
                                        }
                                        else 
                                        {
                                            echo '<i class="fas fa-lock"></i>';
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php printSnippets($keyword); ?>
                                </td>
                                <td class="text-center">
                                     <button style="border: none;background: transparent;" class="tk-update-button"> <i class="fas fa-sync-alt pr5"></i></button> 
                                    <button style="border: none;background: transparent;" class="tk-delete-button"><i class="fas fa-trash-alt"></i></button>
                                    <?php echo (empty($keyword["paused_at"]) ? esc_html('') : esc_html(' <button style="border: none;background: transparent;" class="tk-activate-button"><i class="fas fa-play"></i></button>') ) ?>          
                                </td>
                                <!--<td>
                                    <span class="fa fa-refresh update-keyword-button"></span>
                                </td>-->
                            </tr>
                        <?php } ?>    
                        
                        
                        
                        
                    </tbody>
                </table>
                
            </div>  
            <!-- END Keyword Table -->
    </div>        
</div>

</div>
<!-- Add KW -->
<div id="mySidenav" class="sidenav">
    <h5 class="slr-title-divider slr-text-muted slr-mb20 slr-fs15" style="margin-top: 25px;"> 
        <span class="fa fa-plus" aria-hidden="true"></span> <?php echo esc_html_e("Add keywords", 'seolocalrank' )?> <span class="pull-right">
        <span class="slr-pull-right">
            <i class="fas fa-times slr-cursor" id="close-add-keyword" onclick="closeNav()"></i>
            
        </span>
    </h5>   
    
    <div id="add-keyword-form-box">
        <div style="margin-left: 10px;">
            <h4 style="font-size: 15px;"><?php echo esc_html_e("Add the keywords you want to track in your project", 'seolocalrank' )?></h4>
            <input name="keyword" placeholder="<?php echo esc_html_e("Keyword", 'seolocalrank' )?>" type="text" id="keyword" value="" class="regular-text">
            <p class="slr_description keyword_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?php echo esc_html_e("Enter a valid keyword please", 'seolocalrank' )?></p>    
        </div>

        <div style="margin-left: 10px;" >
            <h4 style="font-size: 15px;"><?php echo esc_html_e("Where do you want to track your keyword?", 'seolocalrank' )?></h4>
                <select name="cities" class="select2-province form-control" multiple style="width:90%;" data-placeholder="<?php echo esc_html_e("Choose cities", 'seolocalrank' )?>" data-availablekeywords="<?php echo esc_html($available_keywords)?>">
                </select>

                <p class="description cities_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?php echo esc_html_e("Choose the citie or the cities where you want tracking the keyword", 'seolocalrank' )?></p>      
                <p class="description" id="timezone-description" style="padding:0px;"><?php echo esc_html_e("Enter the name of a country, state, city, etc", 'seolocalrank' )?></p>
                <p class="description available_keywords_error" id="timezone-description" style="padding:0px;color:red;display:none;"><?php echo esc_html_e('By adding these combinations you would be using the maximum number of keywords that your plan supports. Get a superior plan and add more keywords.', 'seolocalrank' )?></p>
        </div>
        
        <div style="margin-left: 10px;" >
            <h4 style="font-size: 15px;"><?php echo esc_html_e("On what type of device do you want to track the results?", 'seolocalrank' )?></h4>
            <div class="btn-group choose-screen">
                <button type="button" class="btn btn-default selected" id="desktop-screen-button">
                <i class="fa fa-desktop mr-3"></i> <?php echo esc_html_e("Desktop", 'seolocalrank' )?> </button>
                <button type="button" class="btn btn-default dark" id="mobile-screen-button" style="border-radius: 0px 4px 4px 0px;">
                <i class="fa fa-mobile-alt mr-3"></i> <?php echo esc_html_e("Mobile", 'seolocalrank' )?> </button>
                <input type="hidden" id="screen_type" value="1">
            </div>
        </div>

        <div class="alert alert-info text-center" style="background-color:#cdcdcd; margin: 10px;padding: 10px;color: #FFF;border: 1px solid #ccc;">
            <i class="fa fa-info pr10"></i>
            <?php echo esc_html_e("You can add multiple locations, creating combinations between the keyword and all of them", 'seolocalrank' )?> 
        </div>
        
         

        <div class="slr-text-center">
            <input type="hidden" id="project-domain-id" name="project-domain-id" value="<?php echo esc_html($domainId)?>"/>
            <button type="button" id="send-new-keyworsd-button" class="slr-btn slr-btn-rounded slr-btn-success slr-btn-block slr-mb-10" style="width: 55%;margin: 0 auto;">
                <?php echo esc_html_e("Add keywords", 'seolocalrank' )?> 
            </button>
        </div>

        <div class="slr-text-center">
            <div data-chart-id="1" class="slr-legend-item slr-btn slr-btn-default slr-btn-sm" style="margin: 0 auto;margin-top: 10px;font-size: 10px;text-align: center"><span id="available_keywords_left"><?php echo esc_html($available_keywords)?></span> <?php echo esc_html_e("Keywords left", 'seolocalrank' )?></div>
        </div>
    </div>
    
    <div class="slr-box" id="loader-box">
                 <img class="loader" src="<?php echo esc_html($this->loader)?>"/>
                 <p><?php echo _e("We are calculating the position of the keywords you just entered", 'seolocalrank' )?></p>
                 <p><?php echo _e("This process may take a few minutes", 'seolocalrank' )?></p>
                 
            </div>
    
    
</div>

<!-- End Add KW -->

<!-- KW stats -->
<div class="modal fade" id="modal-kw-stats" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            
            <div>
                <h4 class="" style="font-weight: 300;margin-top: 0px;color: #666;"><?php echo esc_html_e("Keyword", 'seolocalrank' )?>: <strong class="color-black" id="tk-keyword"></strong></h4>
                <h4 class="" style="font-weight: 300;margin-top: 0px;color: #666;"><?php echo esc_html_e("Position", 'seolocalrank' )?>: <strong class="color-black" id="tk-keyword-position"></strong></h4>
            </div>
            
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

        </div>
        <div class="modal-body">
                <div id="stats-data">
                    <div class="row" style="margin-top:7px;">
                        <div class="col-xs-12">
                            <h5 class="" style="margin-top: 10px;"><?php echo esc_html_e("Position history", 'seolocalrank' )?></h5>


                            <select class="form-control" id="tk-days" style="max-width: 100%;width: 100%;">
                                <option value="0"><?php echo esc_html_e("Last data", 'seolocalrank' )?></option>
                                <option value="7"><?php echo esc_html_e("Last 7 days", 'seolocalrank' )?></option>
                                <option value="28"><?php echo esc_html_e("Last 28 days", 'seolocalrank' )?></option>
                                <option value="90"><?php echo esc_html_e("Last 90 days", 'seolocalrank' )?></option>
                                <option value="180"><?php echo esc_html_e("Last 180 days", 'seolocalrank' )?></option>
                            </select>

                        </div>
                    </div>
                    
                    <div class="row" style="margin-top:20px;">
                        <div class="col-xs-12 col-sm-12 col-md-8">

                             <div id="chart" style="height: 350px; margin: 0 auto;margin-bottom: 20px;background-color:#f9f9f9"></div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-4">
                             <div class="panel" id="search_stats" style="height:350px;">
                                <div class="panel-body pn" style="padding:0px;">
                                    <table id="slr-google-data-table" class="table table-hover" style="border: 1px solid #ccc;margin-bottom:0px;" >
                                           <thead class="slr-table-head">
                                               <tr>
                                                   <th class="slr-text-center"><?php echo esc_html_e("Volume", 'seolocalrank' )?></th>
                                                   <th class="slr-text-center"><?php echo esc_html_e("CPC", 'seolocalrank' )?></th>
                                                   <th class="slr-text-center"><?php echo esc_html_e("Dificulty", 'seolocalrank' )?></th>
                                                   <th class="slr-text-center"><?php echo esc_html_e("VE", 'seolocalrank' )?></th>

                                               </tr>
                                           </thead>
                                           
                                           <tbody id="google_data_table">

                                           </tbody>
                                       </table>
                                        <table id="" class="table table-hover" style="border: 1px solid #ccc;" >
                                            <thead class="slr-table-head">
                                               <tr>
                                                   <th class="slr-text-center" style="padding:8px;"><?php echo esc_html_e("Search trend", 'seolocalrank' )?></th>
                                               </tr>
                                            </thead>
                                            <td>
                                                <div id="google-trends" style="height:220px">

                                                </div>
                                            </td>
                                        </table>
                                        
                                        
                                        

                                </div>
                             </div>
                         </div>
                    </div>
                 
                
                 
            
                 
                 <div class="slr-row" style="padding-bottom: 20px;">
                    <div class="slr-col-12">
                        
                        <h5 class="" style="font-weight: 500;margin-top: 10px;"><?php echo esc_html_e("Google's top 10 for your keyword", 'seolocalrank' )?></h5>
                        
                                <table id="slr-main-competitors-table" class="table table-hover slr-fs10" style="border: 1px solid #ccc;">
                                    <thead class="slr-table-head">
                                        <tr>
                                         
                                            <th class="slr-text-center"><?php echo esc_html_e("Position", 'seolocalrank' )?></th>
                                             <th><?php echo esc_html_e("URL", 'seolocalrank' )?></th>

                                        </tr>
                                    </thead>
                                    <tbody id="main_competitors_table">
                                       
                                    </tbody>
                                </table>
                         
                        
                    </div>
                </div> 

                </div>        
                <div id="stats-loader" style="height: 300px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="loader" style="padding: 0px;position: absolute;top: 90px;text-align: center;">
                            <img class="loader" src="https://trueranker.com/img/loader.gif">
                            <p id="loader-message"><?php echo esc_html_e("We are getting your keyword data, one moment please ...", 'seolocalrank' )?></p>
                        </div>

                    </div>
                </div>   
           
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo esc_html_e("Close", 'seolocalrank' )?></button>
        </div>
      </div>
    </div>
</div>
<!-- END KW stats -->




<script type="text/javascript">

/* Set the width of the side navigation to 250px */
function openNav() {
  document.getElementById("mySidenav").style.width = "40%";
  document.getElementById("slr-plugin-container").style.opacity = "0.5";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("slr-plugin-container").style.opacity = "1";
}


    
jQuery(document).ready( function(){
    initSelectProvince();
    
        jQuery('#send-new-keyworsd-button').click(function(){
       sendAddKeywordForm(); 
    });
    
    jQuery('[data-toggle="tooltip"]').tooltip();
    
    //clean wp alerts
    cleanWpAlerts();
});


</script>