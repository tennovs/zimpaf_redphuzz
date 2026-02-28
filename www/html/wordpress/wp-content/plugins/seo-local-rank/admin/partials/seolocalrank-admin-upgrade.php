

<div id="slr-plugin-container">
     <div class="page-header">
        <h1><?php echo esc_html_e("Plans and Pricing", "seolocalrank" )?></h1>
    </div>
    
    <div class="alert alert-success" role="alert">
  <h4 class="alert-heading"><?php echo esc_html_e("Know our plans and prices", "seolocalrank" )?></h4>
  <p><?php echo esc_html_e("In a few seconds you will be redirected to the web platform with a logged in session to know our prices and subscribe to one of our plans if you wish.", "seolocalrank" )?></p>
  <hr>
</div>
    
</div>


<script type="text/javascript">
    
    jQuery(document).ready( function(){
        
        setTimeout(function(){
                    window.location.href = "<?php echo esc_url($pricingUrl) ?>";
                    
                    
                 }, 10000);
        
        /*
        jQuery('.slr-subscribe-button').click(function(){
           var plan_period_id = jQuery(this).parent().attr("id");
           if(plan_period_id > 0)
           {
               getSaleId(plan_period_id);
           }
        });*/

        cleanWpAlerts();
    });
</script>    
    
