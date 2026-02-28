(function($){
	HCmanage = {
		// init app
		init: function(param){
			this.init_events();
			this.set_init_param(param);
		},

		// events
		events: {
			'click .e_menu_item'    : 'select_tab',
			'click .e_hc_login'     : 'login',
			'click #wp_to_hc'       : 'import',
			'click #save_options'   : 'save_options',
			'click .e_hc_css'       : 'show_css_editor',
			'click .e_hc_css_close' : 'close_css_editor',
			'click #save_css'       : 'create_css'
		},

		// set param app
		set_init_param: function(param){
			for(var prop in param){
				this[prop] = param[prop];
			}
		},

		// init events manage page
		init_events: function(){
			var events = this.events;
			if(events)
				for(var eventstr in events){
					var evn = eventstr.split(' ');
					var funName   = events[eventstr];
					if(typeof this[funName] != 'function')continue;
					this.add_event(this, $('.hc_wrap_box'), evn[0], evn[1], this[funName]);
				}
		},

		// add event page
		add_event: function(self, el, eventName, selector, func){
			if(selector){
				el.on(eventName, selector, function(e,p1,p2,p3){
					return func.call(self, e,p1,p2,p3);
				});
			}else{
				el.on(eventName, function(e,p1,p2,p3){
					return func.call(self, e, p1,p2,p3);
				});
			}
		},

		// event login
		login: function(){
			this.popup(600, 450, 'Auth HyperComments', this.hc_url+'/auth?service=google', this.create_widget(this));
		},

		// create new widget
		create_widget: function(context){
			var date      = new Date();
            var time_zone = -date.getTimezoneOffset()/60;
           
            $.getJSON(context.hc_url+'/api/widget_create?jsoncallback=?',
            {
                site       : context.hc_siteurl,
                title      : (context.hc_blogname.length > 0) ? context.hc_blogname : 'WP blog name',
                plugins    : "comments,rss,login,count_messages,authors,topics,hypercomments,likes,quotes",
                hypertext  : "*",
                limit      : 20,
                template   : "index",
                cluster    : "c1",
                platform   : "wordpress",
                notify_url : context.hc_notify_url,
                time_zone  : time_zone,
                hc_enableParams : true
            },
            function(data) {
                if(data.result == 'success'){
                    context.save_wid(data);
                }else{
                    $('.err_install').text(data.description).animate({top:0}).delay(5000).animate({top:-100});
                }
            });
		},

		// save widget param
		save_wid: function(data){
			data.hc_action = 'save_wid';
			$.get(this.hc_admin_url, data, function(){
				document.location.href = 'edit-comments.php?page=hypercomments';
			});
		},

		// event change tab
		select_tab: function(e){
			var tab = $(e.target).data('tab');
			$('.e_menu_item').removeClass('hc_menu_active');
			$(e.target).addClass('hc_menu_active');
			$('.hc_box').hide();
			$('.e_box_' + tab).show();
		},

		// event import comments to hc
		import: function(){
			var self = this;
			var posts = this.hc_posts.slice();
			$('.hc_load').show();
			var query = function(posts){
				var page = posts[0];
				$('.hc_im_page').text(page);
				$('.hc_f_status').removeClass('hc_ok').removeClass('hc_no');
				posts.shift();
				get_param = {
					url: this.hc_admin_url,
					data:'hc_action=export_comments&post='+page,
					success: function(data) {
							if(data && data.length > 0){
								try{
									$('#import_report').show();
									var packet = JSON.parse(data);
									for(var i=0; i<packet.length; i++){
										if(packet[i].result == 'success'){
											$('.hc_f_status').addClass('hc_ok');
											self.send_notify(packet[i]);
										}else{
											if(packet[i].description == 'Error writing XML'){
												$('.no_folder').animate({top:0}).delay(5000).animate({top:-100});
												$('.hc_load').hide();
											}
											$('.hc_f_status').addClass('hc_no');
										}
										if(posts.length === 0){
											$('.import_hc').animate({top:0}).delay(5000).animate({top:-100});
											$('.hc_load').hide();
										}
									}
								}catch(e){
									$('.no_folder').animate({top:0}).delay(5000).animate({top:-100});
									$('.hc_load').hide();
								}
							}else{
								$('.err_hc_import').animate({top:0}).delay(5000).animate({top:-100});
							}
							if(posts.length > 0) query(posts);
						},
				error: function(data){
							$('.hc_f_status').addClass('hc_no');
							if(posts.length > 0){
								query(posts);
								$('.hc_load').hide();
							}
						}
				};
				$.ajax(get_param);
			};
			query(posts);
		},

		// send nodify about import
		send_notify: function(packet){
			$.getJSON(this.hc_url+'/api/import?response_type=callback&callback=?',packet, function(data){
				if(data.result == 'success')
					$('.hc_im_status').addClass('hc_ok');
				else
					$('.hc_im_status').addClass('hc_no');
			});
		},

		// save options widget
		save_options: function(){
			$('.hc_load_opt').show();
			$('.e_wc_settings').removeClass('hc_valid_err');
			var self = this;
			var obj_opt = {}, validate = [];
			$('.e_wc_settings').map(function(){
				var val;
				var opt = $(this).data('opt');
				if($(this).attr('type') == 'checkbox'){
					val = $(this).is(':checked') ? 1 : 0;
					obj_opt[opt] = val;
				}else if($(this).attr('type') == 'text'){
					val = $(this).val();
					console.log(val);
					validate.push(self.validate_options($(this), opt, val));
					obj_opt[opt] = self.isInt(val) ? parseInt(val, 10) : val;
				}
			});

			if($.inArray(false, validate) != -1){
				$('.err_opt').animate({top:0}).delay(5000).animate({top:-100});
				$('.hc_load_opt').hide();
			}else{
				$.post(this.hc_admin_url+'?hc_action=update_options', {data : JSON.stringify(obj_opt)}, function(response){
					if(response.result == 'success')
						$('.opt_saved').animate({top:0}).delay(5000).animate({top:-100});
						$('.hc_load_opt').hide();
				},'json');
			}
		},

		// validate options
		validate_options: function(el, opt, val){
			var error = false;
			switch(opt){
				case 'hc_append':
					if(val.charAt(0) != '.' && val.charAt(0) != '#')
						error = true;
				break;
				case 'hc_word_limit':
				case 'hc_comments_level':
					if(!this.isInt(val))
						error = true;
				break;
				case 'hc_css':
					if(val.length > 0 && (val.indexOf('http://') !== 0))
						error = true;
				break;
				case 'hc_selector':
					if(val.charAt(0) != '.')error = true;
				break;
				case 'hc_label_counter':
					if(val.length > 0 && val.indexOf('{%COUNT%}') == -1)
						error = true;
				break;
				
			}
			if(error){
				el.addClass('hc_valid_err');
				return false;
			}
			return true;
		},

		// check int
		isInt: function(n){
			var exp = /^\d{1,}$/;
			return exp.test(n);
		},

		// show css editor
		show_css_editor: function(){
			var css = $('.e_hc_css_in').val();
			if(css.length > 0){
				$.post(this.hc_admin_url+'?hc_action=get_css',{css : css}, function(response){
					if(response.result == 'success')
						$('.e_hc_css_content').text(response.css);
				},'json');
			}
			$('.hc_css_background').show();
			$('.hc_css_box').show();
			return false;
		},

		// close editor
		close_css_editor: function(){
			$('.hc_css_background').hide();
			$('.hc_css_box').hide();
		},

		// create new css
		create_css: function(){
			var self = this;
			var css = $('.e_hc_css_content').val();
			$.post(this.hc_admin_url+'?hc_action=save_css',{css : css}, function(response){
				if(response.result == 'success'){
					$('.e_hc_css_in').val(response.css);
					$('#save_options').click();
					self.close_css_editor();
				}else{
					alert('Сan not save the file in the folder UPLOADS');
				}
			},'json');
		},

		// show popup
		popup: function(width, height, name, url, callback){
			var x = (640 - width)/2;
			var y = (480 - height)/2;
			if(screen){
				y = (screen.availHeight - height)/2;
				x = (screen.availWidth - width)/2;
			}
			var w = window.open(url, name , "menubar=0,location=0,toolbar=0,directories=0,scrollbars=0,status=0,resizable=0,width=" + width + ",height=" + height + ',screenX='+x+',screenY='+y+',top='+y+',left='+x);
			w.focus();

			if(callback)
				var interval = setInterval(function(){
					if(!w || w.closed){
						clearInterval(interval);
						callback();
					}
				}, 500);
		}

	};
})(jQueryHC);