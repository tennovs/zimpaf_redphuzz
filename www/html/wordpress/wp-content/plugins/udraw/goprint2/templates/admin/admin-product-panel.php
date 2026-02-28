<?php
global $post;

$goprint2_order_route_email = get_post_meta($post->ID, '_udraw_goprint2_order_route_email', true);

?>
<div class="options_group">
    <p class="form-field">
        <label>GoPrint2 Production Routing Email</label>
        <input name="_udraw_goprint2_order_route_email" id="udraw_goprint2_order_route_email" type="text" placeholder="enter a valid email address" style="width: 350px;" value="<?php echo $goprint2_order_route_email; ?>" />
        <br /><br />
        <span class="description">After an order is placed, route the production files to the specified email address.</span>
    </p>

</div>