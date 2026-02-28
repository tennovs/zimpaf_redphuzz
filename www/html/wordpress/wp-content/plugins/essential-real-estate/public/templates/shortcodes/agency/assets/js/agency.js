(function ($) {
	"use strict";
	$(document).ready(function () {
		$('.sort-by', '.agency-action .sort-agency').on('click', function(event) {
			var $this = $(this);
			event.preventDefault();
			if(!$this.next('ul').hasClass('active')) {
				$this.next('ul').addClass('active');
				$this.addClass('active');
			} else {
				$this.next('ul').removeClass('active');
				$this.removeClass('active');
			}
			return false;
		});
		$('li', '.agency-action .sort-agency').each(function() {
			var $this = $(this);
			if(window.location.href.indexOf("sortby="+$this.children().data('sortby')) > -1) {
				$this.addClass('active');
				$this.closest('ul').prev('span').html($this.children().html());
			}
			$this.on('click', 'a', function(event){
				$(this).closest('ul').removeClass('active');
				if($(this).parent().hasClass('active')) {
					event.preventDefault();
					return false;
				} else {
					$(this).closest('ul').prev('span').html($(this).html());
				}
			});
		});
		$(document).on('click', function (e) {
			if ($(e.target).closest('.sort-agency').length == 0) {
				$('ul', '.sort-agency').each(function () {
					var $this = $(this);
					if ($this.hasClass('active')) {
						$this.removeClass('active');
						$this.prev('span').removeClass('active');
					}
				});
			}
		});
	});
})(jQuery);