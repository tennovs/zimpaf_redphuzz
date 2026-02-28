<?php
/**
 * Created by G5Theme.
 * User: Kaga
 * Date: 21/12/2016
 * Time: 9:35 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$currency = ere_get_option('currency_sign');
if (empty($currency)) {
	$currency = esc_html__('$', 'essential-real-estate');
}

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'mortgage-calculator');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'mortgage-calculator', ERE_PLUGIN_URL . 'public/templates/widgets/mortgage-calculator/assets/js/mortgage-calculator' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

wp_localize_script(ERE_PLUGIN_PREFIX . 'mortgage-calculator', 'ere_mc_vars',
	array(
		'ajax_url' => ERE_AJAX_URL,
		'currency' => esc_html($currency),
		'loan_amount_text' => esc_html__('Loan Amount','essential-real-estate'),
		'years_text' => esc_html__('Year','essential-real-estate'),
		'monthly_text' => esc_html__('Monthly','essential-real-estate'),
		'bi_weekly_text' => esc_html__('Bi Weekly','essential-real-estate'),
		'weekly_text' => esc_html__('Weekly','essential-real-estate'),
	)
);

?>
<div class="ere-mortgage-wrap">
	<div class="ere-mortgage-form">
		<div class="form-group mc-item">
			<span class="title-mc-item"><?php esc_html_e('Sale Price', 'essential-real-estate'); ?></span>
			<input class="form-control" id="mc_sale_price" type="text" placeholder="<?php echo esc_attr($currency) ?>">
		</div>
		<div class="form-group mc-item">
			<span class="title-mc-item"><?php esc_html_e('Down Payment', 'essential-real-estate'); ?></span>
			<input class="form-control" id="mc_down_payment" type="text" placeholder="<?php echo esc_attr($currency) ?>">
		</div>
		<div class="form-group mc-item">
			<span class="title-mc-item"><?php esc_html_e('Term[Years]', 'essential-real-estate'); ?></span>
			<input class="form-control" id="mc_term_years" type="text"
				   placeholder="<?php esc_attr_e('Year', 'essential-real-estate'); ?>">
		</div>
		<div class="form-group mc-item">
			<span class="title-mc-item"><?php esc_html_e('Interest Rate in %', 'essential-real-estate'); ?></span>
			<input class="form-control" id="mc_interest_rate" type="text"
				   placeholder="<?php esc_attr_e('%', 'essential-real-estate'); ?>">
		</div>
		<button type="button" id="btn_mc"
				class="btn btn-md btn-dark btn-classic btn-block"><?php esc_html_e('Calculate', 'essential-real-estate'); ?></button>
		<div class="mc-output">
		</div>
	</div>
</div>