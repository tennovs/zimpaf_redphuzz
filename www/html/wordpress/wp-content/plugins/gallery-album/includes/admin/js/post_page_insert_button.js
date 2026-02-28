(function($) {
	tinymce.PluginManager.add('wpdevart_gallery', function( editor, url ) {
		var sh_tag = 'wpdevart_gallery';
		//helper functions 
		function getAttr(s, n) {
			n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
			return n ?  window.decodeURIComponent(n[1]) : '';
		};

		function html( cls, data ) {
			var placeholder = url.replace('/js','/images') + '/aparat_big.png';
			data = window.encodeURIComponent( data );			
			return '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-wpda_gall-attr="' + data + '" data-mce-resize="false" data-mce-placeholder="1" />';
		}

		function replaceShortcodes( content ) {
			return content.replace( /\[wpdevart_gallery([^\]]*)\]/g, function( all,attr) {
				return html( 'wp-wpdevart_gallery', attr );
			});
		}

		function restoreShortcodes( content ) {
			return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
				var data = getAttr( image, 'data-wpda_gall-attr' );				
				if ( data ) {
					return '<p>[' + sh_tag + data + ']</p>';
				}
				return match;
			});
		}
		//add popup
		editor.addCommand('wpdevart_gallery_popup', function() {
			//setup defaults
	
            var   xxx0 = {};
			var   xxx1 = {};		  
			xxx0 = {
				title: "WpDevArt Gallery", 
				file:  document.location.origin+ajaxurl + '/?action=wpdevart_gallery_post_page_content',   
				width: 600, 
				height: 600,   
				id : 'my-custom-wpdialog',
				inline: 1          					
			};
		   xxx1={
				editor: editor,  
				jquery: $,  								
				plugin_url : url
				//php_version: php_version   
			};		  
			editor.windowManager.open( xxx0,  xxx1);			
		});

		//add button
		editor.addButton('wpdevart_gallery', {
			image : url.replace('/js','/images') + '/aparat.png',			
			//icon: 'wpdevart_gallery',
			tooltip: 'Wpdevart Gallery',
			onclick: function() {	
				editor.execCommand('wpdevart_gallery_popup','',{});
			}
		});

		//replace from shortcode to an image placeholder
		editor.on('BeforeSetcontent', function(e){ 
			e.content = replaceShortcodes( e.content );
		});

		//replace from image placeholder to shortcode
		editor.on('GetContent', function(e){
			e.content = restoreShortcodes(e.content);
		});

		//open popup on placeholder on click
		editor.on('Click',function(e) {
			var cls  = e.target.className.indexOf('wp-wpdevart_gallery');
			if ( e.target.nodeName == 'IMG' && e.target.className.indexOf('wp-wpdevart_gallery') > -1 ) {
				var title = e.target.attributes['data-wpda_gall-attr'].value;
				title = window.decodeURIComponent(title);
				var xxx0 = {};
				var xxx1 = {};			  
				xxx0 ={ord :getAttr(title,'ord'),
					title: "WpDevArt Gallery", 
					file:  document.location.origin+ajaxurl + '/?action=wpdevart_gallery_post_page_content&gallery_id='+getAttr(title,'gallery_id')+'&album_id='+getAttr(title,'album_id')+'&theme_id='+getAttr(title,'theme_id')+'&order_type='+getAttr(title,'order_type')+'&lost_ids='+getAttr(title,'lost_ids')+'&popup_albums_themes='+getAttr(title,'popup_albums_themes')+'&popup_themeid='+getAttr(title,'popup_themeid'),    
					width: 600, 
					height: 600,   
					id : 'wpdevart_gallery_popup_dialog',
					inline: 1          					
				};
				xxx1={
					editor: editor,  
					jquery: $,  										
					plugin_url : url
					//php_version: php_version   
				};			  
				editor.windowManager.open( xxx0,  xxx1);
			}
		});
	});
})(jQuery);