<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<a class="compare-property" href="javascript:void(0)"
   data-property-id="<?php the_ID() ?>" data-toggle="tooltip"
   title="<?php esc_attr_e('Compare', 'essential-real-estate') ?>">
	<i class="fa fa-plus"></i>
</a>