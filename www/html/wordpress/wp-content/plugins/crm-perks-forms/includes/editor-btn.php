<?php

class cfx_editor_btn {

	public function __construct() {
	add_action( 'media_buttons', array( $this, 'media_button' ), 15 );

	}

	function media_button( $editor_id ) {

		// Provide the ability to conditionally disable the button, so it can be
		// disabled for custom fields or front-end use such as bbPress. We default
		// to only showing within the admin panel.
		if ( ! apply_filters( 'crmperks_forms_display_media_button', is_admin(), $editor_id ) ) {
			return;
		}

		// Setup the icon - currently using a dashicon
		$icon = '<img style="height: 18px; margin-top: -3px" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNy4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMjIyLjMzMXB4IiBoZWlnaHQ9IjI1NS43NzhweCIgdmlld0JveD0iMCAwIDIyMi4zMzEgMjU1Ljc3OCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjIyLjMzMSAyNTUuNzc4Ig0KCSB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxwYXRoIGZpbGw9IiM5OTk5OTkiIGQ9Ik0yMjEuNTEsMTkyLjU0NGwtMTExLjE2NSw2My4yMzRMMCwxOTEuMTIzTDAuODIxLDYzLjIzNEwxMTEuOTg2LDBsMTEwLjM0NSw2NC42NTVMMjIxLjUxLDE5Mi41NDR6DQoJIE0xNjEuNzg2LDE2Mi4xMzljLTMsMS4xNDMtNC45NjksMi4xOTgtOC4wMDEsMy4xNjRjLTMuMDMyLDAuOTY4LTUuODU5LDEuODI1LTguOTc3LDIuNTcxYy0zLjEyMSwwLjc0OC02LjExNiwxLjMxOC05LjIzNSwxLjcxNA0KCWMtMy4xMjEsMC4zOTYtNi4wMjQsMC41OTMtOC44MzYsMC41OTNjLTUuODAxLDAtMTEuMDQzLTAuOTQzLTE1Ljc4OS0yLjgzNGMtNC43NDYtMS44ODktOC43OTctNC42MTQtMTIuMTc5LTguMTc0DQoJYy0zLjM4NC0zLjU2LTUuOTkxLTcuODg4LTcuODM2LTEyLjk4NmMtMS44NDYtNS4wOTYtMi43NjUtMTAuODU0LTIuNzY1LTE3LjI3MWMwLTYuMTUxLDAuODU5LTExLjc5OSwyLjU3My0xNi45NDENCglzNC4xOTctOS41MTMsNy40NS0xMy4xMThjMy4yNS0zLjYwMyw3LjI1MS02LjQxNSwxMS45OTgtOC40MzhjNC43NDYtMi4wMjEsMTAuMTA2LTMuMDMyLDE2LjA4NC0zLjAzMg0KCWM1LjcxMiwwLDExLjA4MSwwLjc0OCwxNy40NTUsMi4yNDFjNi4zNzIsMS40OTYsMTEuMDU5LDMuNjA1LDE3LjA1OSw2LjMyOFY2NC43MTJjLTUtMS40MDUtOS40MTMtMi41NzEtMTUuMzQ1LTMuNDk0DQoJcy0xMi45MTMtMS4zODQtMjEuNjE0LTEuMzg0Yy0xMC42MzUsMC0yMC4xMTQsMS42Ny0yOC43Nyw1LjAxYy04LjY1OCwzLjM0MS0xNS45NzksOC4wODctMjIuMTMsMTQuMjM4DQoJYy02LjE1Myw2LjE1My0xMC44OCwxMy41NzktMTQuMjYyLDIyLjI4Yy0zLjM4NCw4LjcwMS01LjA1NSwxOC40MTQtNS4wNTUsMjkuMTM2YzAsMTEuMDc0LDEuNTcsMjAuODMsNC42OTEsMjkuMjY4DQoJYzMuMTE5LDguNDM4LDcuNjI5LDE1LjUxMywxMy41MTgsMjEuMjI2YzUuODg3LDUuNzE0LDEzLjA1NCwxMC4wMiwyMS40OTIsMTIuOTJzMTcuOTc0LDQuMzUxLDI4LjYxLDQuMzUxDQoJYzcuMzgzLDAsMTMuOTU1LTAuNjE2LDIwLjcyNC0xLjg0NmM2Ljc2Ny0xLjIzLDEyLjE0Mi0zLjExOSwxOS4xNDItNS42NjlWMTYyLjEzOXoiLz4NCjwvc3ZnPg0K" />';

		printf( '<a href="#" class="button cfx_insert_form_btn" data-editor="%s" title="%s">%s %s</a>',
			esc_attr( $editor_id ),
			'Add Form',
			$icon,
		'CRM Perks'
		);
	add_action( 'admin_footer', array( $this, 'shortcode_modal' ) );
	}

	function shortcode_modal() {
	$forms=cfx_form::get_forms();
 
    	?>
		<div id="cfx_form_model_bg" style="display: none"></div>
		<div id="cfx_form_model_div" style="display: none">
			<form id="cfx_form_model_body" tabindex="-1">
				<div id="cfx_form_model_title">
				Insert CRM Perks Form
		<button type="button" id="cfx_form_model_close"><span class="screen-reader-text">Close</span></button>
				</div>
                <div id="cfx_form_model_inner">
                
                <div style="font-size: 16px; padding: 10px 0;">Select Form</div>
                <select id="cfx_form_id" style="width: 100%; margin-top: 14px;">
               <?php  foreach( $forms as $v){
                   echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
               } ?>
                </select>
                </div>
				
				<div class="submitbox">
					<div id="cfx_form_model_cancel">
						<a class="submitdelete deletion" href="#">Cancel</a>
					</div>
					<?php if ( ! empty( $forms ) ) : ?>
					<div id="cfx_form_model_update">
						<button class="button button-primary" id="cfx_form_model_submit">Add Form</button>
					</div>
					<?php endif; ?>
				</div>
			</form>
		</div>
		<style style="text/css">
			#cfx_form_model_div {
				display: none;
				background-color: #fff;
				-webkit-box-shadow: 0 3px 6px rgba( 0, 0, 0, 0.3 );
				box-shadow: 0 3px 6px rgba( 0, 0, 0, 0.3 );
				width: 500px;
				height: 220px;
				overflow: hidden;
				margin-left: -250px;
				margin-top: -125px;
				position: fixed;
				top: 50%;
				left: 50%;
				z-index: 100105;
				-webkit-transition: height 0.2s, margin-top 0.2s;
				transition: height 0.2s, margin-top 0.2s;
			}
			#cfx_form_model_bg {
				display: none;
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				min-height: 360px;
				background: #000;
				opacity: 0.7;
				filter: alpha(opacity=70);
				z-index: 100100;
			}
            #cfx_form_model_inner {
    padding: 0 16px 50px;
}
			#cfx_form_model_body {
				position: relative;
				height: 100%;
			}
			#cfx_form_model_title {
				background: #fcfcfc;
				border-bottom: 1px solid #dfdfdf;
				height: 36px;
				font-size: 18px;
				font-weight: 600;
				line-height: 36px;
				padding: 0 36px 0 16px;
				top: 0;
				right: 0;
				left: 0;
			}
			#cfx_form_model_close {
				color: #666;
				padding: 0;
				position: absolute;
				top: 0;
				right: 0;
				width: 36px;
				height: 36px;
				text-align: center;
				background: none;
				border: none;
				cursor: pointer;
			}
			#cfx_form_model_close:before {
				font: normal 20px/36px 'dashicons';
				vertical-align: top;
				speak: none;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				width: 36px;
				height: 36px;
				content: '\f158';
			}
			#cfx_form_model_close:hover,
			#cfx_form_model_close:focus {
				color: #2ea2cc;
			}
			#cfx_form_model_close:focus {
				outline: none;
				-webkit-box-shadow:
					0 0 0 1px #5b9dd9,
					0 0 2px 1px rgba(30, 140, 190, .8);
				box-shadow:
					0 0 0 1px #5b9dd9,
					0 0 2px 1px rgba(30, 140, 190, .8);
			}
		
		
			#cfx_form_model_body .submitbox {
				padding: 8px 16px;
				background: #fcfcfc;
				border-top: 1px solid #dfdfdf;
				position: absolute;
				bottom: 0;
				left: 0;
				right: 0;
			}
			#cfx_form_model_cancel {
				line-height: 25px;
				float: left;
			}
			#cfx_form_model_update {
				line-height: 23px;
				float: right;
			}
			#cfx_form_model_submit {
				float: right;
				margin-bottom: 0;
			}
			@media screen and ( max-width: 782px ) {
				#cfx_form_model_div {
					height: 280px;
					margin-top: -140px;
				}
				#cfx_form_model_body-inner {
					padding: 0 16px 60px;
				}
				#cfx_form_model_cancel {
					line-height: 32px;
				}
			}
			@media screen and ( max-width: 520px ) {
				#cfx_form_model_div {
					width: auto;
					margin-left: 0;
					left: 10px;
					right: 10px;
					max-width: 500px;
				}
			}
			@media screen and ( max-height: 520px ) {
				#cfx_form_model_div {
					-webkit-transition: none;
					transition: none;
				}
			}
			@media screen and ( max-height: 290px ) {
				#cfx_form_model_div {
					height: auto;
					margin-top: 0;
					top: 10px;
					bottom: 10px;
				}
				#cfx_form_model_inner {
					overflow: auto;
					height: -webkit-calc(100% - 92px);
					height: calc(100% - 92px);
					padding-bottom: 2px;
				}
			}
		</style>
        <script type="text/javascript">
    (function($){ 
        // Close modal
        var close_win = function() {
            $('#cfx_form_model_bg, #cfx_form_model_div').css('display','none');
            $( document.body ).removeClass( 'modal-open' );
        };
        // Open modal when media button is clicked
        $(document).on('click', '.cfx_insert_form_btn', function(e) { 
            e.preventDefault();
            $('#cfx_form_model_bg, #cfx_form_model_div').css('display','block');
            $( document.body ).addClass( 'modal-open' );
        });
        // Close modal on close or cancel links
        $(document).on('click', '#cfx_form_model_close, #cfx_form_model_cancel a', function(e) {
            e.preventDefault();
            close_win();
        });
        // Insert shortcode into TinyMCE
        $(document).on('click', '#cfx_form_model_submit', function(e) {
            e.preventDefault();
            var shortcode;
            shortcode = '[crmperks-forms id="' + $('#cfx_form_id').val() + '"';
            shortcode = shortcode+']';
            wp.media.editor.insert(shortcode);
            close_win();
        });

}(jQuery));
        </script>
		<?php
	}

}

new cfx_editor_btn();
