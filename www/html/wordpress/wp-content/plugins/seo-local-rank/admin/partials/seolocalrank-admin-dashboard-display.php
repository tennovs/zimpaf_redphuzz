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
    
   <!-- <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active" href="" title="Módulos">Módulos</a>
            <a class="nav-tab" href="" title="Ayuda">Ayuda</a>
            <a class="nav-tab" href="" title="Asistente de configuración">Asistente de configuración</a>
            <a class="nav-tab" href="" title="Importar y exportar">Importar y exportar</a>
    </h2>-->
   
    <div class="slr-box">
        <h2><?= esc_html_e('Projects', 'seolocalrank' )?></h2>
        <p>
            <?= esc_html_e('Here is the list of your projects. Within a project you can add several domains.', 'seolocalrank' )?><br>
            <?= esc_html_e('For example, you can have a project called clothing store and within the domains of various clothing stores.', 'seolocalrank' )?>
        </p>
        
        <div class="slr-alert slr-critical" style="display:<?=esc_html($this->displayError) ?>">
            <h3 class="slr-key-status"><?= esc_html($this->error) ?></h3>
        </div>
    </div>        
    
    <div class="slr-lower">	
        
        
       
         <div class="slr-box">    
            
            <div class="slr-align-right">
                <form name="slr_add_project" action="https://seolocalrank.com/login" method="POST" target="_blank">

                    <input type="submit" class="slr-button slr-is-primary" value="<?= esc_html_e('Add new project', 'seolocalrank' )?>">
                </form>	
                
                <!--<button id="my-button">Click me</button>

                    
                <p id="txtMessage">Nothing yet</p>-->
                </div>
            
              <div class="slr-boxes projects"
             <?php 
                foreach ($projects as $project)
                {
            ?>
                    <div class="slr-box">
                        <h4><?= esc_html($project["name"]) ?></h4>
                        <p><?= esc_html($project["num_domains"]) ?> <?= esc_html_e('domains', 'seolocalrank' )?></p>
                        
                       <div class="slr-align-right">
                            <a href="<?= admin_url('admin.php?page=seolocalrank-project&projectId='.esc_html($project["id"])).'&projectName='.esc_html($project["name"]);?>">
                                <button class="slr-button"><?= esc_html_e('View project domains', 'seolocalrank' )?></button>  
                            </a>
                       </div>   
                        
                    </div>
            <?php
                }
            ?>
        </div>
            
               
        </div>
        
        
      
        
        
        
      
       
    </div>    
    
</div>


