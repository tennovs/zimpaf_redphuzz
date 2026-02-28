(function ($) {
	"use strict";
	$(document).ready(function ($) {
		if (typeof ere_mc_vars !== "undefined") {
			var currency = ere_mc_vars.currency;
			var loan_amount_text=ere_mc_vars.loan_amount_text;
			var years_text=ere_mc_vars.years_text;
			var monthly_text=ere_mc_vars.monthly_text;
			var bi_weekly_text=ere_mc_vars.bi_weekly_text;
			var weekly_text=ere_mc_vars.weekly_text;
			$('#btn_mc').on('click', function (e) {
					e.preventDefault();

					var sale_price = $('#mc_sale_price').val();
					var precent_down = $('#mc_down_payment').val();
					var term_years = parseInt($('#mc_term_years').val(), 10);
					var interest_rate = parseFloat($('#mc_interest_rate').val(), 10) / 100;

					var interest_rate_month = interest_rate / 12;
					var interest_rate_bi_weekly = interest_rate / 26;
					var interest_rate_weekly = interest_rate / 52;

					var number_of_payments_month = term_years * 12;
					var number_of_payments_bi_weekly = term_years * 26;
					var number_of_payments_weekly = term_years * 52;

					var loan_amount = sale_price - precent_down;
					var monthly_payment = parseFloat((loan_amount * interest_rate_month) / (1 - Math.pow(1 + interest_rate_month, -number_of_payments_month))).toFixed(2);
					var bi_weekly_payment = parseFloat((loan_amount * interest_rate_bi_weekly) / (1 - Math.pow(1 + interest_rate_bi_weekly, -number_of_payments_bi_weekly))).toFixed(2);
					var weekly_payment = parseFloat((loan_amount * interest_rate_weekly) / (1 - Math.pow(1 + interest_rate_weekly, -number_of_payments_weekly))).toFixed(2);

					if (monthly_payment === 'NaN') {
						monthly_payment = 0;
					}
					if (bi_weekly_payment === 'NaN') {
						bi_weekly_payment = 0;
					}
					if (weekly_payment === 'NaN') {
						weekly_payment = 0;
					}

					$('.mc-output').html(
						"<p class='mc-loan-amount'><b>"+loan_amount_text+":</b> " + currency + loan_amount + "</p>" +
						"<p class='mc-years'><b>"+ years_text+ ":</b> " + term_years + "</p>" +
						"<p class='mc-monthly'><b>"+ monthly_text +":</b> " + currency + monthly_payment + "</p>" +
						"<p class='mc-bi-weekly'><b>"+ bi_weekly_text+":</b> " + currency + bi_weekly_payment + "</p>" +
						"<p class='mc-weekly'><b>"+ weekly_text+":</b> " + currency + weekly_payment + "</p>"
					);

				});
		}
	});

})(jQuery);
