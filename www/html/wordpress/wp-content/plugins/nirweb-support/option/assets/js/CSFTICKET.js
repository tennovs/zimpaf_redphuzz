/**
 *
 * -----------------------------------------------------------
 *
 * Codestar Framework
 * A Simple and Lightweight WordPress Option Framework
 *
 * -----------------------------------------------------------
 *
 */
;(function( $, window, document, undefined ) {
  'use strict';

  //
  // Constants
  //
  var CSFTICKET   = CSFTICKET || {};

  CSFTICKET.funcs = {};

  CSFTICKET.vars  = {
    onloaded: false,
    $body: $('body'),
    $window: $(window),
    $document: $(document),
    $form_warning: null,
    is_confirm: false,
    form_modified: false,
    code_themes: [],
    is_rtl: $('body').hasClass('rtl'),
  };

  //
  // Helper Functions
  //
  CSFTICKET.helper = {

    //
    // Generate UID
    //
    uid: function( prefix ) {
      return ( prefix || '' ) + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function( str ) {
      return (str+'').replace(/(\[|\-|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function( $selector, field_id ) {

      var checks = [];
      var regex  = new RegExp('('+ CSFTICKET.helper.preg_quote(field_id) +')\\[(\\d+)\\]', 'g');

      $selector.find(':radio').each(function() {
        if ( this.checked || this.orginal_checked ) {
          this.orginal_checked = true;
        }
      });

      $selector.each( function( index ) {
        $(this).find(':input').each(function() {
          this.name = this.name.replace(regex, field_id +'['+ index +']');
          if ( this.orginal_checked ) {
            this.checked = true;
          }
        });
      });

    },

    //
    // Debounce
    //
    debounce: function( callback, threshold, immediate ) {
      var timeout;
      return function() {
        var context = this, args = arguments;
        var later = function() {
          timeout = null;
          if ( !immediate ) {
            callback.apply(context, args);
          }
        };
        var callNow = ( immediate && !timeout );
        clearTimeout( timeout );
        timeout = setTimeout( later, threshold );
        if ( callNow ) {
          callback.apply(context, args);
        }
      };
    },

    //
    // Get a cookie
    //
    get_cookie: function( name ) {

      var e, b, cookie = document.cookie, p = name + '=';

      if ( ! cookie ) {
        return;
      }

      b = cookie.indexOf( '; ' + p );

      if ( b === -1 ) {
        b = cookie.indexOf(p);

        if ( b !== 0 ) {
          return null;
        }
      } else {
        b += 2;
      }

      e = cookie.indexOf( ';', b );

      if ( e === -1 ) {
        e = cookie.length;
      }

      return decodeURIComponent( cookie.substring( b + p.length, e ) );

    },

    //
    // Set a cookie
    //
    set_cookie: function( name, value, expires, path, domain, secure ) {

      var d = new Date();

      if ( typeof( expires ) === 'object' && expires.toGMTString ) {
        expires = expires.toGMTString();
      } else if ( parseInt( expires, 10 ) ) {
        d.setTime( d.getTime() + ( parseInt( expires, 10 ) * 1000 ) );
        expires = d.toGMTString();
      } else {
        expires = '';
      }

      document.cookie = name + '=' + encodeURIComponent( value ) +
        ( expires ? '; expires=' + expires : '' ) +
        ( path    ? '; path=' + path       : '' ) +
        ( domain  ? '; domain=' + domain   : '' ) +
        ( secure  ? '; secure'             : '' );

    },

    //
    // Remove a cookie
    //
    remove_cookie: function( name, path, domain, secure ) {
      CSFTICKET.helper.set_cookie( name, '', -1000, path, domain, secure );
    },

  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.CSFTICKET_clone = function() {

    var base   = $.fn.clone.apply(this, arguments),
        clone  = this.find('select').add(this.filter('select')),
        cloned = base.find('select').add(base.filter('select'));

    for( var i = 0; i < clone.length; ++i ) {
      for( var j = 0; j < clone[i].options.length; ++j ) {

        if ( clone[i].options[j].selected === true ) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    this.find(':radio').each( function() {
      this.orginal_checked = this.checked;
    });

    return base;

  };

  //
  // Expand All Options
  //
  $.fn.CSFTICKET_expand_all = function() {
    return this.each( function() {
      $(this).on('click', function( e ) {

        e.preventDefault();
        $('.CSFTICKET-wrapper').toggleClass('CSFTICKET-show-all');
        $('.CSFTICKET-section').CSFTICKET_reload_script();
        $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.CSFTICKET_nav_options = function() {
    return this.each( function() {

      var $nav    = $(this),
          $links  = $nav.find('a'),
          $hidden = $nav.closest('.CSFTICKET').find('.CSFTICKET-section-id'),
          $last_section;

      $(window).on('hashchange CSFTICKET.hashchange', function() {

        var hash  = window.location.hash.match(new RegExp('tab=([^&]*)'));
        var slug  = hash ? hash[1] : $links.first().attr('href').replace('#tab=', '');
        var $link = $('#CSFTICKET-tab-link-'+ slug);

        if ( $link.length > 0 ) {

          $link.closest('.CSFTICKET-tab-depth-0').addClass('CSFTICKET-tab-active').siblings().removeClass('CSFTICKET-tab-active');
          $links.removeClass('CSFTICKET-section-active');
          $link.addClass('CSFTICKET-section-active');

          if ( $last_section !== undefined ) {
            $last_section.hide();
          }

          var $section = $('#CSFTICKET-section-'+slug);
          $section.show();
          $section.CSFTICKET_reload_script();

          $hidden.val(slug);

          $last_section = $section;

        }

      }).trigger('CSFTICKET.hashchange');

    });
  };

  //
  // Metabox Tabs
  //
  $.fn.CSFTICKET_nav_metabox = function() {
    return this.each( function() {

      var $nav      = $(this),
          $links    = $nav.find('a'),
          unique_id = $nav.data('unique'),
          post_id   = $('#post_ID').val() || 'global',
          $last_section,
          $last_link;

      $links.on('click', function( e ) {

        e.preventDefault();

        var $link      = $(this),
            section_id = $link.data('section');

        if ( $last_link !== undefined ) {
          $last_link.removeClass('CSFTICKET-section-active');
        }

        if ( $last_section !== undefined ) {
          $last_section.hide();
        }

        $link.addClass('CSFTICKET-section-active');

        var $section = $('#CSFTICKET-section-'+section_id);
        $section.show();
        $section.CSFTICKET_reload_script();

        CSFTICKET.helper.set_cookie('CSFTICKET-last-metabox-tab-'+ post_id +'-'+ unique_id, section_id);

        $last_section = $section;
        $last_link    = $link;

      });

      var get_cookie = CSFTICKET.helper.get_cookie('CSFTICKET-last-metabox-tab-'+ post_id +'-'+ unique_id);

      if ( get_cookie ) {
        $nav.find('a[data-section="'+ get_cookie +'"]').trigger('click');
      } else {
        $links.first('a').trigger('click');
      }

    });
  };

  //
  // Metabox Page Templates Listener
  //
  $.fn.CSFTICKET_page_templates = function() {
    if ( this.length ) {

      $(document).on('change', '.editor-page-attributes__template select, #page_template', function() {

        var maybe_value = $(this).val() || 'default';

        $('.CSFTICKET-page-templates').removeClass('CSFTICKET-show').addClass('CSFTICKET-hide');
        $('.CSFTICKET-page-'+maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g,'-')).removeClass('CSFTICKET-hide').addClass('CSFTICKET-show');

      });

    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.CSFTICKET_post_formats = function() {
    if ( this.length ) {

      $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function() {

        var maybe_value = $(this).val() || 'default';

        // Fallback for classic editor version
        maybe_value = ( maybe_value === '0' ) ? 'default' : maybe_value;

        $('.CSFTICKET-post-formats').removeClass('CSFTICKET-show').addClass('CSFTICKET-hide');
        $('.CSFTICKET-post-format-'+maybe_value).removeClass('CSFTICKET-hide').addClass('CSFTICKET-show');

      });

    }
  };

  //
  // Search
  //
  $.fn.CSFTICKET_search = function() {
    return this.each( function() {

      var $this  = $(this),
          $input = $this.find('input');

      $input.on('change keyup', function() {

        var value    = $(this).val(),
            $wrapper = $('.CSFTICKET-wrapper'),
            $section = $wrapper.find('.CSFTICKET-section'),
            $fields  = $section.find('> .CSFTICKET-field:not(.hidden)'),
            $titles  = $fields.find('> .CSFTICKET-title, .CSFTICKET-search-tags');

        if ( value.length > 3 ) {

          $fields.addClass('CSFTICKET-hidden');
          $wrapper.addClass('CSFTICKET-search-all');

          $titles.each( function() {

            var $title = $(this);

            if ( $title.text().match( new RegExp('.*?' + value + '.*?', 'i') ) ) {

              var $field = $title.closest('.CSFTICKET-field');

              $field.removeClass('CSFTICKET-hidden');
              $field.parent().CSFTICKET_reload_script();

            }

          });

        } else {

          $fields.removeClass('CSFTICKET-hidden');
          $wrapper.removeClass('CSFTICKET-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.CSFTICKET_sticky = function() {
    return this.each( function() {

      var $this     = $(this),
          $window   = $(window),
          $inner    = $this.find('.CSFTICKET-header-inner'),
          padding   = parseInt( $inner.css('padding-left') ) + parseInt( $inner.css('padding-right') ),
          offset    = 32,
          scrollTop = 0,
          lastTop   = 0,
          ticking   = false,
          stickyUpdate = function() {

            var offsetTop = $this.offset().top,
                stickyTop = Math.max(offset, offsetTop - scrollTop ),
                winWidth  = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

            if ( stickyTop <= offset && winWidth > 782 ) {
              $inner.css({width: $this.outerWidth()-padding});
              $this.css({height: $this.outerHeight()}).addClass( 'CSFTICKET-sticky' );
            } else {
              $inner.removeAttr('style');
              $this.removeAttr('style').removeClass( 'CSFTICKET-sticky' );
            }

          },
          requestTick = function() {

            if ( !ticking ) {
              requestAnimationFrame( function() {
                stickyUpdate();
                ticking = false;
              });
            }

            ticking = true;

          },
          onSticky  = function() {

            scrollTop = $window.scrollTop();
            requestTick();

          };

      $window.on( 'scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.CSFTICKET_dependency = function() {
    return this.each( function() {

      var $this     = $(this),
          ruleset   = $.CSFTICKET_deps.createRuleset(),
          depends   = [],
          is_global = false;

      $this.children('[data-controller]').each( function() {

        var $field      = $(this),
            controllers = $field.data('controller').split('|'),
            conditions  = $field.data('condition').split('|'),
            values      = $field.data('value').toString().split('|'),
            rules       = ruleset;

        if ( $field.data('depend-global') ) {
          is_global = true;
        }

        $.each(controllers, function( index, depend_id ) {

          var value     = values[index] || '',
              condition = conditions[index] || conditions[0];

          rules = rules.createRule('[data-depend-id="'+ depend_id +'"]', condition, value);

          rules.include($field);

          depends.push(depend_id);

        });

      });

      if ( depends.length ) {

        if ( is_global ) {
          $.CSFTICKET_deps.enable(CSFTICKET.vars.$body, ruleset, depends);
        } else {
          $.CSFTICKET_deps.enable($this, ruleset, depends);
        }

      }

    });
  };

  //
  // Field: accordion
  //
  $.fn.CSFTICKET_field_accordion = function() {
    return this.each( function() {

      var $titles = $(this).find('.CSFTICKET-accordion-title');

      $titles.on('click', function() {

        var $title   = $(this),
            $icon    = $title.find('.CSFTICKET-accordion-icon'),
            $content = $title.next();

        if ( $icon.hasClass('fa-angle-right') ) {
          $icon.removeClass('fa-angle-right').addClass('fa-angle-down');
        } else {
          $icon.removeClass('fa-angle-down').addClass('fa-angle-right');
        }

        if ( !$content.data( 'opened' ) ) {

          $content.CSFTICKET_reload_script();
          $content.data( 'opened', true );

        }

        $content.toggleClass('CSFTICKET-accordion-open');

      });

    });
  };

  //
  // Field: backup
  //
  $.fn.CSFTICKET_field_backup = function() {
    return this.each( function() {

      if ( window.wp.customize === undefined ) { return; }

      var base    = this,
          $this   = $(this),
          $body   = $('body'),
          $import = $this.find('.CSFTICKET-import'),
          $reset  = $this.find('.CSFTICKET-reset');

      base.notification = function( message_text ) {

        if ( wp.customize.notifications && wp.customize.OverlayNotification ) {

          // clear if there is any saved data.
          if ( !wp.customize.state('saved').get() ) {
            wp.customize.state('changesetStatus').set('trash');
            wp.customize.each( function( setting ) { setting._dirty = false; });
            wp.customize.state('saved').set(true);
          }

          // then show a notification overlay
          wp.customize.notifications.add( new wp.customize.OverlayNotification('CSFTICKET_field_backup_notification', {
            type: 'info',
            message: message_text,
            loading: true
          }));

        }

      };

      $reset.on('click', function( e ) {

        e.preventDefault();

        if ( CSFTICKET.vars.is_confirm ) {

          base.notification( window.CSFTICKET_vars.i18n.reset_notification );

          window.wp.ajax.post('CSFTICKET-reset', {
            unique: $reset.data('unique'),
            nonce: $reset.data('nonce')
          })
          .done( function( response ) {
            window.location.reload(true);
          })
          .fail( function( response ) {
            alert( response.error );
            wp.customize.notifications.remove('CSFTICKET_field_backup_notification');
          });

        }

      });

      $import.on('click', function( e ) {

        e.preventDefault();

        if ( CSFTICKET.vars.is_confirm ) {

          base.notification( window.CSFTICKET_vars.i18n.import_notification );

          window.wp.ajax.post( 'CSFTICKET-import', {
            unique: $import.data('unique'),
            nonce: $import.data('nonce'),
            data: $this.find('.CSFTICKET-import-data').val()
          }).done( function( response ) {
            window.location.reload(true);
          }).fail( function( response ) {
            alert( response.error );
            wp.customize.notifications.remove('CSFTICKET_field_backup_notification');
          });

        }

      });

    });
  };

  //
  // Field: background
  //
  $.fn.CSFTICKET_field_background = function() {
    return this.each( function() {
      $(this).find('.CSFTICKET--background-image').CSFTICKET_reload_script();
    });
  };

  //
  // Field: code_editor
  //
  $.fn.CSFTICKET_field_code_editor = function() {
    return this.each( function() {

      if ( typeof CodeMirror !== 'function' ) { return; }

      var $this       = $(this),
          $textarea   = $this.find('textarea'),
          $inited     = $this.find('.CodeMirror'),
          data_editor = $textarea.data('editor');

      if ( $inited.length ) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ( $this.is(':visible') ) {

          var code_editor = CodeMirror.fromTextArea( $textarea[0], data_editor );

          // load code-mirror theme css.
          if ( data_editor.theme !== 'default' && CSFTICKET.vars.code_themes.indexOf(data_editor.theme) === -1 ) {

            var $cssLink = $('<link>');

            $('#CSFTICKET-codemirror-css').after( $cssLink );

            $cssLink.attr({
              rel: 'stylesheet',
              id: 'CSFTICKET-codemirror-'+ data_editor.theme +'-css',
              href: data_editor.cdnURL +'/theme/'+ data_editor.theme +'.min.css',
              type: 'text/css',
              media: 'all'
            });

            CSFTICKET.vars.code_themes.push(data_editor.theme);

          }

          CodeMirror.modeURL = data_editor.cdnURL +'/mode/%N/%N.min.js';
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on( 'change', function( editor, event ) {
            $textarea.val( code_editor.getValue() ).trigger('change');
          });

          clearInterval(interval);

        }
      });

    });
  };

  //
  // Field: date
  //
  $.fn.CSFTICKET_field_date = function() {
    return this.each( function() {

      var $this    = $(this),
          $inputs  = $this.find('input'),
          settings = $this.find('.CSFTICKET-date-settings').data('settings'),
          wrapper  = '<div class="CSFTICKET-datepicker-wrapper"></div>',
          $datepicker;

      var defaults = {
        showAnim: '',
        beforeShow: function(input, inst) {
          $(inst.dpDiv).addClass('CSFTICKET-datepicker-wrapper');
        },
        onClose: function( input, inst ) {
          $(inst.dpDiv).removeClass('CSFTICKET-datepicker-wrapper');
        },
      };

      settings = $.extend({}, settings, defaults);

      if ( $inputs.length === 2 ) {

        settings = $.extend({}, settings, {
          onSelect: function( selectedDate ) {

            var $this  = $(this),
                $from  = $inputs.first(),
                option = ( $inputs.first().attr('id') === $(this).attr('id') ) ? 'minDate' : 'maxDate',
                date   = $.datepicker.parseDate( settings.dateFormat, selectedDate );

            $inputs.not(this).datepicker('option', option, date );

          }
        });

      }

      $inputs.each( function(){

        var $input = $(this);

        if ( $input.hasClass('hasDatepicker') ) {
          $input.removeAttr('id').removeClass('hasDatepicker');
        }

        $input.datepicker(settings);

      });

    });
  };

  //
  // Field: fieldset
  //
  $.fn.CSFTICKET_field_fieldset = function() {
    return this.each( function() {
      $(this).find('.CSFTICKET-fieldset-content').CSFTICKET_reload_script();
    });
  };

  //
  // Field: gallery
  //
  $.fn.CSFTICKET_field_gallery = function() {
    return this.each( function() {

      var $this  = $(this),
          $edit  = $this.find('.CSFTICKET-edit-gallery'),
          $clear = $this.find('.CSFTICKET-clear-gallery'),
          $list  = $this.find('ul'),
          $input = $this.find('input'),
          $img   = $this.find('img'),
          wp_media_frame;

      $this.on('click', '.CSFTICKET-button, .CSFTICKET-edit-gallery', function( e ) {

        var $el   = $(this),
            ids   = $input.val(),
            what  = ( $el.hasClass('CSFTICKET-edit-gallery') ) ? 'edit' : 'add',
            state = ( what === 'add' && !ids.length ) ? 'gallery' : 'gallery-edit';

        e.preventDefault();

        if ( typeof window.wp === 'undefined' || ! window.wp.media || ! window.wp.media.gallery ) { return; }

         // Open media with state
        if ( state === 'gallery' ) {

          wp_media_frame = window.wp.media({
            library: {
              type: 'image'
            },
            frame: 'post',
            state: 'gallery',
            multiple: true
          });

          wp_media_frame.open();

        } else {

          wp_media_frame = window.wp.media.gallery.edit( '[gallery ids="'+ ids +'"]' );

          if ( what === 'add' ) {
            wp_media_frame.setState('gallery-library');
          }

        }

        // Media Update
        wp_media_frame.on( 'update', function( selection ) {

          $list.empty();

          var selectedIds = selection.models.map( function( attachment ) {

            var item  = attachment.toJSON();
            var thumb = ( item.sizes && item.sizes.thumbnail && item.sizes.thumbnail.url ) ? item.sizes.thumbnail.url : item.url;

            $list.append('<li><img src="'+ thumb +'"></li>');

            return item.id;

          });

          $input.val( selectedIds.join( ',' ) ).trigger('change');
          $clear.removeClass('hidden');
          $edit.removeClass('hidden');

        });

      });

      $clear.on('click', function( e ) {
        e.preventDefault();
        $list.empty();
        $input.val('').trigger('change');
        $clear.addClass('hidden');
        $edit.addClass('hidden');
      });

    });

  };

  //
  // Field: group
  //
  $.fn.CSFTICKET_field_group = function() {
    return this.each( function() {

      var $this     = $(this),
          $fieldset = $this.children('.CSFTICKET-fieldset'),
          $group    = $fieldset.length ? $fieldset : $this,
          $wrapper  = $group.children('.CSFTICKET-cloneable-wrapper'),
          $hidden   = $group.children('.CSFTICKET-cloneable-hidden'),
          $max      = $group.children('.CSFTICKET-cloneable-max'),
          $min      = $group.children('.CSFTICKET-cloneable-min'),
          field_id  = $wrapper.data('field-id'),
          unique_id = $wrapper.data('unique-id'),
          is_number = Boolean( Number( $wrapper.data('title-number') ) ),
          max       = parseInt( $wrapper.data('max') ),
          min       = parseInt( $wrapper.data('min') );

      // clear accordion arrows if multi-instance
      if ( $wrapper.hasClass('ui-accordion') ) {
        $wrapper.find('.ui-accordion-header-icon').remove();
      }

      var update_title_numbers = function( $selector ) {
        $selector.find('.CSFTICKET-cloneable-title-number').each( function( index ) {
          $(this).html( ( $(this).closest('.CSFTICKET-cloneable-item').index()+1 ) + '.' );
        });
      };

      $wrapper.accordion({
        header: '> .CSFTICKET-cloneable-item > .CSFTICKET-cloneable-title',
        collapsible : true,
        active: false,
        animate: false,
        heightStyle: 'content',
        icons: {
          'header': 'CSFTICKET-cloneable-header-icon fas fa-angle-right',
          'activeHeader': 'CSFTICKET-cloneable-header-icon fas fa-angle-down'
        },
        activate: function( event, ui ) {

          var $panel  = ui.newPanel;
          var $header = ui.newHeader;

          if ( $panel.length && !$panel.data( 'opened' ) ) {

            var $fields = $panel.children();
            var $first  = $fields.first().find(':input').first();
            var $title  = $header.find('.CSFTICKET-cloneable-value');

            $first.on('change keyup', function( event ) {
              $title.text($first.val());
            });

            $panel.CSFTICKET_reload_script();
            $panel.data( 'opened', true );
            $panel.data( 'retry', false );

          } else if ( $panel.data( 'retry' ) ) {

            $panel.CSFTICKET_reload_script_retry();
            $panel.data( 'retry', false );

          }

        }
      });

      $wrapper.sortable({
        axis: 'y',
        handle: '.CSFTICKET-cloneable-title,.CSFTICKET-cloneable-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        start: function( event, ui ) {

          $wrapper.accordion({ active:false });
          $wrapper.sortable('refreshPositions');
          ui.item.children('.CSFTICKET-cloneable-content').data('retry', true);

        },
        update: function( event, ui ) {

          CSFTICKET.helper.name_nested_replace( $wrapper.children('.CSFTICKET-cloneable-item'), field_id );
          $wrapper.CSFTICKET_customizer_refresh();

          if ( is_number ) {
            update_title_numbers($wrapper);
          }

        },
      });

      $group.children('.CSFTICKET-cloneable-add').on('click', function( e ) {

        e.preventDefault();

        var count = $wrapper.children('.CSFTICKET-cloneable-item').length;

        $min.hide();

        if ( max && (count+1) > max ) {
          $max.show();
          return;
        }

        var new_field_id = unique_id + field_id + '['+ count +']';

        var $cloned_item = $hidden.CSFTICKET_clone(true);

        $cloned_item.removeClass('CSFTICKET-cloneable-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each( function() {
          this.name = new_field_id + this.name.replace( ( this.name.startsWith('_nonce') ? '_nonce' : unique_id ), '');
        });

        $cloned_item.find('.CSFTICKET-data-wrapper').each( function(){
          $(this).attr('data-unique-id', new_field_id );
        });

        $wrapper.append($cloned_item);
        $wrapper.accordion('refresh');
        $wrapper.accordion({active: count});
        $wrapper.CSFTICKET_customizer_refresh();
        $wrapper.CSFTICKET_customizer_listen({closest: true});

        if ( is_number ) {
          update_title_numbers($wrapper);
        }

      });

      var event_clone = function( e ) {

        e.preventDefault();

        var count = $wrapper.children('.CSFTICKET-cloneable-item').length;

        $min.hide();

        if ( max && (count+1) > max ) {
          $max.show();
          return;
        }

        var $this           = $(this),
            $parent         = $this.parent().parent(),
            $cloned_helper  = $parent.children('.CSFTICKET-cloneable-helper').CSFTICKET_clone(true),
            $cloned_title   = $parent.children('.CSFTICKET-cloneable-title').CSFTICKET_clone(),
            $cloned_content = $parent.children('.CSFTICKET-cloneable-content').CSFTICKET_clone(),
            cloned_regex    = new RegExp('('+ CSFTICKET.helper.preg_quote(field_id) +')\\[(\\d+)\\]', 'g');

        $cloned_content.find('.CSFTICKET-data-wrapper').each( function(){
          var $this = $(this);
          $this.attr('data-unique-id', $this.attr('data-unique-id').replace(cloned_regex, field_id +'['+ ($parent.index()+1) +']') );
        });

        var $cloned = $('<div class="CSFTICKET-cloneable-item" />');

        $cloned.append($cloned_helper);
        $cloned.append($cloned_title);
        $cloned.append($cloned_content);

        $wrapper.children().eq($parent.index()).after($cloned);

        CSFTICKET.helper.name_nested_replace( $wrapper.children('.CSFTICKET-cloneable-item'), field_id );

        $wrapper.accordion('refresh');
        $wrapper.CSFTICKET_customizer_refresh();
        $wrapper.CSFTICKET_customizer_listen({closest: true});

        if ( is_number ) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.CSFTICKET-cloneable-item').children('.CSFTICKET-cloneable-helper').on('click', '.CSFTICKET-cloneable-clone', event_clone);
      $group.children('.CSFTICKET-cloneable-hidden').children('.CSFTICKET-cloneable-helper').on('click', '.CSFTICKET-cloneable-clone', event_clone);

      var event_remove = function( e ) {

        e.preventDefault();

        var count = $wrapper.children('.CSFTICKET-cloneable-item').length;

        $max.hide();
        $min.hide();

        if ( min && (count-1) < min ) {
          $min.show();
          return;
        }

        $(this).closest('.CSFTICKET-cloneable-item').remove();

        CSFTICKET.helper.name_nested_replace( $wrapper.children('.CSFTICKET-cloneable-item'), field_id );

        $wrapper.CSFTICKET_customizer_refresh();

        if ( is_number ) {
          update_title_numbers($wrapper);
        }

      };

      $wrapper.children('.CSFTICKET-cloneable-item').children('.CSFTICKET-cloneable-helper').on('click', '.CSFTICKET-cloneable-remove', event_remove);
      $group.children('.CSFTICKET-cloneable-hidden').children('.CSFTICKET-cloneable-helper').on('click', '.CSFTICKET-cloneable-remove', event_remove);

    });
  };

  //
  // Field: icon
  //
  $.fn.CSFTICKET_field_icon = function() {
    return this.each( function() {

      var $this = $(this);

      $this.on('click', '.CSFTICKET-icon-add', function( e ) {

        e.preventDefault();

        var $button = $(this);
        var $modal  = $('#CSFTICKET-modal-icon');

        $modal.show();

        CSFTICKET.vars.$icon_target = $this;

        if ( !CSFTICKET.vars.icon_modal_loaded ) {

          $modal.find('.CSFTICKET-modal-loading').show();

          window.wp.ajax.post( 'CSFTICKET-get-icons', {
            nonce: $button.data('nonce')
          }).done( function( response ) {

            $modal.find('.CSFTICKET-modal-loading').hide();

            CSFTICKET.vars.icon_modal_loaded = true;

            var $load = $modal.find('.CSFTICKET-modal-load').html( response.content );

            $load.on('click', 'i', function( e ) {

              e.preventDefault();

              var icon = $(this).attr('title');

              CSFTICKET.vars.$icon_target.find('i').removeAttr('class').addClass(icon);
              CSFTICKET.vars.$icon_target.find('input').val(icon).trigger('change');
              CSFTICKET.vars.$icon_target.find('.CSFTICKET-icon-preview').removeClass('hidden');
              CSFTICKET.vars.$icon_target.find('.CSFTICKET-icon-remove').removeClass('hidden');

              $modal.hide();

            });

            $modal.on('change keyup', '.CSFTICKET-icon-search', function() {

              var value  = $(this).val(),
                  $icons = $load.find('i');

              $icons.each( function() {

                var $elem = $(this);

                if ( $elem.attr('title').search( new RegExp( value, 'i' ) ) < 0 ) {
                  $elem.hide();
                } else {
                  $elem.show();
                }

              });

            });

            $modal.on('click', '.CSFTICKET-modal-close, .CSFTICKET-modal-overlay', function() {
              $modal.hide();
            });

          }).fail( function( response ) {
            $modal.find('.CSFTICKET-modal-loading').hide();
            $modal.find('.CSFTICKET-modal-load').html( response.error );
            $modal.on('click', function() { $modal.hide(); });
          });
        }

      });

      $this.on('click', '.CSFTICKET-icon-remove', function( e ) {
        e.preventDefault();
        $this.find('.CSFTICKET-icon-preview').addClass('hidden');
        $this.find('input').val('').trigger('change');
        $(this).addClass('hidden');
      });

    });
  };

  //
  // Field: map
  //
  $.fn.CSFTICKET_field_map = function() {
    return this.each( function() {

      if ( typeof L === 'undefined' ) { return; }

      var $this         = $(this),
          $map          = $this.find('.CSFTICKET--map-osm'),
          $search_input = $this.find('.CSFTICKET--map-search input'),
          $latitude     = $this.find('.CSFTICKET--latitude'),
          $longitude    = $this.find('.CSFTICKET--longitude'),
          $zoom         = $this.find('.CSFTICKET--zoom'),
          map_data      = $map.data( 'map' );

      var mapInit = L.map( $map.get(0), map_data);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(mapInit);

      var mapMarker = L.marker(map_data.center,{draggable: true}).addTo(mapInit);

      var update_latlng = function( data ) {
        $latitude.val( data.lat );
        $longitude.val( data.lng );
        $zoom.val( mapInit.getZoom() );
      };

      mapInit.on( 'click', function ( data ) {
        mapMarker.setLatLng( data.latlng );
        update_latlng( data.latlng );
      });

      mapInit.on( 'zoom', function () {
        update_latlng( mapMarker.getLatLng() );
      });

      mapMarker.on( 'drag', function () {
        update_latlng( mapMarker.getLatLng() );
      });

      if ( ! $search_input.length ) {
        $search_input = $( '[data-depend-id="'+ $this.find('.CSFTICKET--address-field').data( 'address-field' ) +'"]' );
      }

      var cache = {};

      $search_input.autocomplete({
        source: function ( request, response ) {

          var term = request.term;

          if ( term in cache ) {
            response( cache[term] );
            return;
          }

          $.get( 'https://nominatim.openstreetmap.org/search', {
            format: 'json',
            q: term,
          }, function( results ) {

            var data;

            if ( results.length ) {
              data = results.map( function( item ) {
                return {
                  value: item.display_name,
                  label: item.display_name,
                  lat: item.lat,
                  lon: item.lon
                };
              }, 'json');
            } else {
              data = [{
                value: 'no-data',
                label: 'No Results.'
              }];
            }

            cache[term] = data;
            response(data);

          });

        },
        select: function ( event, ui ) {

          if ( ui.item.value === 'no-data' ) { return false; }

          var latLng = L.latLng( ui.item.lat, ui.item.lon );

          mapInit.panTo( latLng );
          mapMarker.setLatLng( latLng );
          update_latlng( latLng );

        },
        create: function (event, ui) {
          $(this).autocomplete('widget').addClass('CSFTICKET-map-ui-autocomplate');
        }
      });

      var input_update_latlng = function() {

        var latLng = L.latLng( $latitude.val(), $longitude.val() );

        mapInit.panTo( latLng );
        mapMarker.setLatLng( latLng );

      };

      $latitude.on('change', input_update_latlng );
      $longitude.on('change', input_update_latlng );

    });
  };

  //
  // Field: media
  //
  $.fn.CSFTICKET_field_media = function() {
    return this.each( function() {

      var $this            = $(this),
          $upload_button   = $this.find('.CSFTICKET--button'),
          $remove_button   = $this.find('.CSFTICKET--remove'),
          $library         = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
          $auto_attributes = ( $this.hasClass('CSFTICKET-assign-field-background') ) ? $this.closest('.CSFTICKET-field-background').find('.CSFTICKET--auto-attributes') : false,
          wp_media_frame;

      $upload_button.on('click', function( e ) {

        e.preventDefault();

        if ( typeof window.wp === 'undefined' || ! window.wp.media || ! window.wp.media.gallery ) {
          return;
        }

        if ( wp_media_frame ) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          }
        });

        wp_media_frame.on( 'select', function() {

          var thumbnail;
          var attributes   = wp_media_frame.state().get('selection').first().attributes;
          var preview_size = $upload_button.data('preview-size') || 'thumbnail';

          if ( $library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1 ) {
            return;
          }

          $this.find('.CSFTICKET--id').val( attributes.id );
          $this.find('.CSFTICKET--width').val( attributes.width );
          $this.find('.CSFTICKET--height').val( attributes.height );
          $this.find('.CSFTICKET--alt').val( attributes.alt );
          $this.find('.CSFTICKET--title').val( attributes.title );
          $this.find('.CSFTICKET--description').val( attributes.description );

          if ( typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.thumbnail !== 'undefined' && preview_size === 'thumbnail' ) {
            thumbnail = attributes.sizes.thumbnail.url;
          } else if ( typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.full !== 'undefined' ) {
            thumbnail = attributes.sizes.full.url;
          } else {
            thumbnail = attributes.icon;
          }

          if ( $auto_attributes ) {
            $auto_attributes.removeClass('CSFTICKET--attributes-hidden');
          }

          $remove_button.removeClass('hidden');

          $this.find('.CSFTICKET--preview').removeClass('hidden');
          $this.find('.CSFTICKET--src').attr('src', thumbnail);
          $this.find('.CSFTICKET--thumbnail').val( thumbnail );
          $this.find('.CSFTICKET--url').val( attributes.url ).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function( e ) {

        e.preventDefault();

        if ( $auto_attributes ) {
          $auto_attributes.addClass('CSFTICKET--attributes-hidden');
        }

        $remove_button.addClass('hidden');
        $this.find('input').val('');
        $this.find('.CSFTICKET--preview').addClass('hidden');
        $this.find('.CSFTICKET--url').trigger('change');

      });

    });

  };

  //
  // Field: repeater
  //
  $.fn.CSFTICKET_field_repeater = function() {
    return this.each( function() {

      var $this     = $(this),
          $fieldset = $this.children('.CSFTICKET-fieldset'),
          $repeater = $fieldset.length ? $fieldset : $this,
          $wrapper  = $repeater.children('.CSFTICKET-repeater-wrapper'),
          $hidden   = $repeater.children('.CSFTICKET-repeater-hidden'),
          $max      = $repeater.children('.CSFTICKET-repeater-max'),
          $min      = $repeater.children('.CSFTICKET-repeater-min'),
          field_id  = $wrapper.data('field-id'),
          unique_id = $wrapper.data('unique-id'),
          max       = parseInt( $wrapper.data('max') ),
          min       = parseInt( $wrapper.data('min') );


      $wrapper.children('.CSFTICKET-repeater-item').children('.CSFTICKET-repeater-content').CSFTICKET_reload_script();

      $wrapper.sortable({
        axis: 'y',
        handle: '.CSFTICKET-repeater-sort',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function( event, ui ) {

          CSFTICKET.helper.name_nested_replace( $wrapper.children('.CSFTICKET-repeater-item'), field_id );
          $wrapper.CSFTICKET_customizer_refresh();
          ui.item.CSFTICKET_reload_script_retry();

        }
      });

      $repeater.children('.CSFTICKET-repeater-add').on('click', function( e ) {

        e.preventDefault();

        var count = $wrapper.children('.CSFTICKET-repeater-item').length;

        $min.hide();

        if ( max && (count+1) > max ) {
          $max.show();
          return;
        }

        var new_field_id = unique_id + field_id + '['+ count +']';

        var $cloned_item = $hidden.CSFTICKET_clone(true);

        $cloned_item.removeClass('CSFTICKET-repeater-hidden');

        $cloned_item.find(':input[name!="_pseudo"]').each( function() {
          this.name = new_field_id + this.name.replace( ( this.name.startsWith('_nonce') ? '_nonce' : unique_id ), '');
        });

        $cloned_item.find('.CSFTICKET-data-wrapper').each( function(){
          $(this).attr('data-unique-id', new_field_id );
        });

        $wrapper.append($cloned_item);
        $cloned_item.children('.CSFTICKET-repeater-content').CSFTICKET_reload_script();
        $wrapper.CSFTICKET_customizer_refresh();
        $wrapper.CSFTICKET_customizer_listen({closest: true});

      });

      var event_clone = function( e ) {

        e.preventDefault();

        var count = $wrapper.children('.CSFTICKET-repeater-item').length;

        $min.hide();

        if ( max && (count+1) > max ) {
          $max.show();
          return;
        }

        var $this           = $(this),
            $parent         = $this.parent().parent().parent(),
            $cloned_content = $parent.children('.CSFTICKET-repeater-content').CSFTICKET_clone(),
            $cloned_helper  = $parent.children('.CSFTICKET-repeater-helper').CSFTICKET_clone(true),
            cloned_regex    = new RegExp('('+ CSFTICKET.helper.preg_quote(field_id) +')\\[(\\d+)\\]', 'g');

        $cloned_content.find('.CSFTICKET-data-wrapper').each( function(){
          var $this = $(this);
          $this.attr('data-unique-id', $this.attr('data-unique-id').replace(cloned_regex, field_id +'['+ ($parent.index()+1) +']') );
        });

        var $cloned = $('<div class="CSFTICKET-repeater-item" />');

        $cloned.append($cloned_content);
        $cloned.append($cloned_helper);

        $wrapper.children().eq($parent.index()).after($cloned);

        $cloned.children('.CSFTICKET-repeater-content').CSFTICKET_reload_script();

        CSFTICKET.helper.name_nested_replace( $wrapper.children('.CSFTICKET-repeater-item'), field_id );

        $wrapper.CSFTICKET_customizer_refresh();
        $wrapper.CSFTICKET_customizer_listen({closest: true});

      };

      $wrapper.children('.CSFTICKET-repeater-item').children('.CSFTICKET-repeater-helper').on('click', '.CSFTICKET-repeater-clone', event_clone);
      $repeater.children('.CSFTICKET-repeater-hidden').children('.CSFTICKET-repeater-helper').on('click', '.CSFTICKET-repeater-clone', event_clone);

      var event_remove = function( e ) {

        e.preventDefault();

        var count = $wrapper.children('.CSFTICKET-repeater-item').length;

        $max.hide();
        $min.hide();

        if ( min && (count-1) < min ) {
          $min.show();
          return;
        }

        $(this).closest('.CSFTICKET-repeater-item').remove();

        CSFTICKET.helper.name_nested_replace( $wrapper.children('.CSFTICKET-repeater-item'), field_id );

        $wrapper.CSFTICKET_customizer_refresh();

      };

      $wrapper.children('.CSFTICKET-repeater-item').children('.CSFTICKET-repeater-helper').on('click', '.CSFTICKET-repeater-remove', event_remove);
      $repeater.children('.CSFTICKET-repeater-hidden').children('.CSFTICKET-repeater-helper').on('click', '.CSFTICKET-repeater-remove', event_remove);

    });
  };

  //
  // Field: slider
  //
  $.fn.CSFTICKET_field_slider = function() {
    return this.each( function() {

      var $this   = $(this),
          $input  = $this.find('input'),
          $slider = $this.find('.CSFTICKET-slider-ui'),
          data    = $input.data(),
          value   = $input.val() || 0;

      if ( $slider.hasClass('ui-slider') ) {
        $slider.empty();
      }

      $slider.slider({
        range: 'min',
        value: value,
        min: data.min,
        max: data.max,
        step: data.step,
        slide: function( e, o ) {
          $input.val( o.value ).trigger('change');
        }
      });

      $input.on('keyup', function() {
        $slider.slider('value', $input.val());
      });

    });
  };

  //
  // Field: sortable
  //
  $.fn.CSFTICKET_field_sortable = function() {
    return this.each( function() {

      var $sortable = $(this).find('.CSFTICKET--sortable');

      $sortable.sortable({
        axis: 'y',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function( event, ui ) {
          $sortable.CSFTICKET_customizer_refresh();
        }
      });

      $sortable.find('.CSFTICKET--sortable-content').CSFTICKET_reload_script();

    });
  };

  //
  // Field: sorter
  //
  $.fn.CSFTICKET_field_sorter = function() {
    return this.each( function() {

      var $this         = $(this),
          $enabled      = $this.find('.CSFTICKET-enabled'),
          $has_disabled = $this.find('.CSFTICKET-disabled'),
          $disabled     = ( $has_disabled.length ) ? $has_disabled : false;

      $enabled.sortable({
        connectWith: $disabled,
        placeholder: 'ui-sortable-placeholder',
        update: function( event, ui ) {

          var $el = ui.item.find('input');

          if ( ui.item.parent().hasClass('CSFTICKET-enabled') ) {
            $el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
          } else {
            $el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
          }

          $this.CSFTICKET_customizer_refresh();

        }
      });

      if ( $disabled ) {

        $disabled.sortable({
          connectWith: $enabled,
          placeholder: 'ui-sortable-placeholder',
          update: function( event, ui ) {
            $this.CSFTICKET_customizer_refresh();
          }
        });

      }

    });
  };

  //
  // Field: spinner
  //
  $.fn.CSFTICKET_field_spinner = function() {
    return this.each( function() {

      var $this   = $(this),
          $input  = $this.find('input'),
          $inited = $this.find('.ui-spinner-button'),
          $unit   = $input.data('unit');

      if ( $inited.length ) {
        $inited.remove();
      }

      $input.spinner({
        max: $input.data('max') || 100,
        min: $input.data('min') || 0,
        step: $input.data('step') || 1,
        create: function( event, ui ) {
          if ( $unit.length ) {
            $this.find('.ui-spinner-up').after('<span class="ui-button-text-only CSFTICKET--unit">'+ $unit +'</span>');
          }
        },
        spin: function (event, ui ) {
          $input.val(ui.value).trigger('change');
        }
      });

    });
  };

  //
  // Field: switcher
  //
  $.fn.CSFTICKET_field_switcher = function() {
    return this.each( function() {

      var $switcher = $(this).find('.CSFTICKET--switcher');

      $switcher.on('click', function() {

        var value  = 0;
        var $input = $switcher.find('input');

        if ( $switcher.hasClass('CSFTICKET--active') ) {
          $switcher.removeClass('CSFTICKET--active');
        } else {
          value = 1;
          $switcher.addClass('CSFTICKET--active');
        }

        $input.val(value).trigger('change');

      });

    });
  };

  //
  // Field: tabbed
  //
  $.fn.CSFTICKET_field_tabbed = function() {
    return this.each( function() {

      var $this     = $(this),
          $links    = $this.find('.CSFTICKET-tabbed-nav a'),
          $sections = $this.find('.CSFTICKET-tabbed-section');

      $sections.eq(0).CSFTICKET_reload_script();

      $links.on( 'click', function( e ) {

       e.preventDefault();

        var $link    = $(this),
            index    = $link.index(),
            $section = $sections.eq(index);

        $link.addClass('CSFTICKET-tabbed-active').siblings().removeClass('CSFTICKET-tabbed-active');
        $section.CSFTICKET_reload_script();
        $section.removeClass('hidden').siblings().addClass('hidden');

      });

    });
  };

  //
  // Field: typography
  //
  $.fn.CSFTICKET_field_typography = function() {
    return this.each(function () {

      var base          = this;
      var $this         = $(this);
      var loaded_fonts  = [];
      var webfonts      = CSFTICKET_typography_json.webfonts;
      var googlestyles  = CSFTICKET_typography_json.googlestyles;
      var defaultstyles = CSFTICKET_typography_json.defaultstyles;

      //
      //
      // Sanitize google font subset
      base.sanitize_subset = function( subset ) {
        subset = subset.replace('-ext', ' Extended');
        subset = subset.charAt(0).toUpperCase() + subset.slice(1);
        return subset;
      };

      //
      //
      // Sanitize google font styles (weight and style)
      base.sanitize_style = function( style ) {
        return googlestyles[style] ? googlestyles[style] : style;
      };

      //
      //
      // Load google font
      base.load_google_font = function( font_family, weight, style ) {

        if ( font_family && typeof WebFont === 'object' ) {

          weight = weight ? weight.replace('normal', '') : '';
          style  = style ? style.replace('normal', '') : '';

          if ( weight || style ) {
            font_family = font_family +':'+ weight + style;
          }

          if ( loaded_fonts.indexOf( font_family ) === -1 ) {
            WebFont.load({ google: { families: [font_family] } });
          }

          loaded_fonts.push( font_family );

        }

      };

      //
      //
      // Append select options
      base.append_select_options = function( $select, options, condition, type, is_multi ) {

        $select.find('option').not(':first').remove();

        var opts = '';

        $.each( options, function( key, value ) {

          var selected;
          var name = value;

          // is_multi
          if ( is_multi ) {
            selected = ( condition && condition.indexOf(value) !== -1 ) ? ' selected' : '';
          } else {
            selected = ( condition && condition === value ) ? ' selected' : '';
          }

          if ( type === 'subset' ) {
            name = base.sanitize_subset( value );
          } else if ( type === 'style' ){
            name = base.sanitize_style( value );
          }

          opts += '<option value="'+ value +'"'+ selected +'>'+ name +'</option>';

        });

        $select.append(opts).trigger('CSFTICKET.change').trigger('chosen:updated');

      };

      base.init = function () {

        //
        //
        // Constants
        var selected_styles  = [];
        var $typography      = $this.find('.CSFTICKET--typography');
        var $type            = $this.find('.CSFTICKET--type');
        var $styles          = $this.find('.CSFTICKET--block-font-style');
        var unit             = $typography.data('unit');
        var line_height_unit = $typography.data('line-height-unit');
        var exclude_fonts    = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

        //
        //
        // Chosen init
        if ( $this.find('.CSFTICKET--chosen').length ) {

          var $chosen_selects = $this.find('select');

          $chosen_selects.each( function(){

            var $chosen_select = $(this),
                $chosen_inited = $chosen_select.parent().find('.chosen-container');

            if ( $chosen_inited.length ) {
              $chosen_inited.remove();
            }

            $chosen_select.chosen({
              allow_single_deselect: true,
              disable_search_threshold: 15,
              width: '100%'
            });

          });

        }

        //
        //
        // Font family select
        var $font_family_select = $this.find('.CSFTICKET--font-family');
        var first_font_family   = $font_family_select.val();

        // Clear default font family select options
        $font_family_select.find('option').not(':first-child').remove();

        var opts = '';

        $.each(webfonts, function( type, group ) {

          // Check for exclude fonts
          if ( exclude_fonts && exclude_fonts.indexOf(type) !== -1 ) { return; }

          opts += '<optgroup label="' + group.label + '">';

          $.each(group.fonts, function( key, value ) {

            // use key if value is object
            value = ( typeof value === 'object' ) ? key : value;
            var selected = ( value === first_font_family ) ? ' selected' : '';
            opts += '<option value="'+ value +'" data-type="'+ type +'"'+ selected +'>'+ value +'</option>';

          });

          opts += '</optgroup>';

        });

        // Append google font select options
        $font_family_select.append(opts).trigger('chosen:updated');

        //
        //
        // Font style select
        var $font_style_block = $this.find('.CSFTICKET--block-font-style');

        if ( $font_style_block.length ) {

          var $font_style_select = $this.find('.CSFTICKET--font-style-select');
          var first_style_value  = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '' ) : '';

          //
          // Font Style on on change listener
          $font_style_select.on('change CSFTICKET.change', function( event ) {

            var style_value = $font_style_select.val();

            // set a default value
            if ( !style_value && selected_styles && selected_styles.indexOf('normal') === -1 ) {
              style_value = selected_styles[0];
            }

            // set font weight, for eg. replacing 800italic to 800
            var font_normal = ( style_value && style_value !== 'italic' && style_value === 'normal' ) ? 'normal' : '';
            var font_weight = ( style_value && style_value !== 'italic' && style_value !== 'normal' ) ? style_value.replace('italic', '') : font_normal;
            var font_style  = ( style_value && style_value.substr(-6) === 'italic' ) ? 'italic' : '';

            $this.find('.CSFTICKET--font-weight').val( font_weight );
            $this.find('.CSFTICKET--font-style').val( font_style );

          });

          //
          //
          // Extra font style select
          var $extra_font_style_block = $this.find('.CSFTICKET--block-extra-styles');

          if ( $extra_font_style_block.length ) {
            var $extra_font_style_select = $this.find('.CSFTICKET--extra-styles');
            var first_extra_style_value  = $extra_font_style_select.val();
          }

        }

        //
        //
        // Subsets select
        var $subset_block = $this.find('.CSFTICKET--block-subset');
        if ( $subset_block.length ) {
          var $subset_select = $this.find('.CSFTICKET--subset');
          var first_subset_select_value = $subset_select.val();
          var subset_multi_select = $subset_select.data('multiple') || false;
        }

        //
        //
        // Backup font family
        var $backup_font_family_block = $this.find('.CSFTICKET--block-backup-font-family');

        //
        //
        // Font Family on Change Listener
        $font_family_select.on('change CSFTICKET.change', function( event ) {

          // Hide subsets on change
          if ( $subset_block.length ) {
            $subset_block.addClass('hidden');
          }

          // Hide extra font style on change
          if ( $extra_font_style_block.length ) {
            $extra_font_style_block.addClass('hidden');
          }

          // Hide backup font family on change
          if ( $backup_font_family_block.length ) {
            $backup_font_family_block.addClass('hidden');
          }

          var $selected = $font_family_select.find(':selected');
          var value     = $selected.val();
          var type      = $selected.data('type');

          if ( type && value ) {

            // Show backup fonts if font type google or custom
            if ( ( type === 'google' || type === 'custom' ) && $backup_font_family_block.length ) {
              $backup_font_family_block.removeClass('hidden');
            }

            // Appending font style select options
            if ( $font_style_block.length ) {

              // set styles for multi and normal style selectors
              var styles = defaultstyles;

              // Custom or gogle font styles
              if ( type === 'google' && webfonts[type].fonts[value][0] ) {
                styles = webfonts[type].fonts[value][0];
              } else if ( type === 'custom' && webfonts[type].fonts[value] ) {
                styles = webfonts[type].fonts[value];
              }

              selected_styles = styles;

              // Set selected style value for avoid load errors
              var set_auto_style  = ( styles.indexOf('normal') !== -1 ) ? 'normal' : styles[0];
              var set_style_value = ( first_style_value && styles.indexOf(first_style_value) !== -1 ) ? first_style_value : set_auto_style;

              // Append style select options
              base.append_select_options( $font_style_select, styles, set_style_value, 'style' );

              // Clear first value
              first_style_value = false;

              // Show style select after appended
              $font_style_block.removeClass('hidden');

              // Appending extra font style select options
              if ( type === 'google' && $extra_font_style_block.length && styles.length > 1 ) {

                // Append extra-style select options
                base.append_select_options( $extra_font_style_select, styles, first_extra_style_value, 'style', true );

                // Clear first value
                first_extra_style_value = false;

                // Show style select after appended
                $extra_font_style_block.removeClass('hidden');

              }

            }

            // Appending google fonts subsets select options
            if ( type === 'google' && $subset_block.length && webfonts[type].fonts[value][1] ) {

              var subsets          = webfonts[type].fonts[value][1];
              var set_auto_subset  = ( subsets.length < 2 && subsets[0] !== 'latin' ) ? subsets[0] : '';
              var set_subset_value = ( first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1 ) ? first_subset_select_value : set_auto_subset;

              // check for multiple subset select
              set_subset_value = ( subset_multi_select && first_subset_select_value ) ? first_subset_select_value : set_subset_value;

              base.append_select_options( $subset_select, subsets, set_subset_value, 'subset', subset_multi_select );

              first_subset_select_value = false;

              $subset_block.removeClass('hidden');

            }

          } else {

            // Clear Styles
            $styles.find(':input').val('');

            // Clear subsets options if type and value empty
            if ( $subset_block.length ) {
              $subset_select.find('option').not(':first-child').remove();
              $subset_select.trigger('chosen:updated');
            }

            // Clear font styles options if type and value empty
            if ( $font_style_block.length ) {
              $font_style_select.find('option').not(':first-child').remove();
              $font_style_select.trigger('chosen:updated');
            }

          }

          // Update font type input value
          $type.val(type);

        }).trigger('CSFTICKET.change');

        //
        //
        // Preview
        var $preview_block = $this.find('.CSFTICKET--block-preview');

        if ( $preview_block.length ) {

          var $preview = $this.find('.CSFTICKET--preview');

          // Set preview styles on change
          $this.on('change', CSFTICKET.helper.debounce( function( event ) {

            $preview_block.removeClass('hidden');

            var font_family       = $font_family_select.val(),
                font_weight       = $this.find('.CSFTICKET--font-weight').val(),
                font_style        = $this.find('.CSFTICKET--font-style').val(),
                font_size         = $this.find('.CSFTICKET--font-size').val(),
                font_variant      = $this.find('.CSFTICKET--font-variant').val(),
                line_height       = $this.find('.CSFTICKET--line-height').val(),
                text_align        = $this.find('.CSFTICKET--text-align').val(),
                text_transform    = $this.find('.CSFTICKET--text-transform').val(),
                text_decoration   = $this.find('.CSFTICKET--text-decoration').val(),
                text_color        = $this.find('.CSFTICKET--color').val(),
                word_spacing      = $this.find('.CSFTICKET--word-spacing').val(),
                letter_spacing    = $this.find('.CSFTICKET--letter-spacing').val(),
                custom_style      = $this.find('.CSFTICKET--custom-style').val(),
                type              = $this.find('.CSFTICKET--type').val();

            if ( type === 'google' ) {
              base.load_google_font(font_family, font_weight, font_style);
            }

            var properties = {};

            if ( font_family     ) { properties.fontFamily     = font_family;                    }
            if ( font_weight     ) { properties.fontWeight     = font_weight;                    }
            if ( font_style      ) { properties.fontStyle      = font_style;                     }
            if ( font_variant    ) { properties.fontVariant    = font_variant;                   }
            if ( font_size       ) { properties.fontSize       = font_size + unit;               }
            if ( line_height     ) { properties.lineHeight     = line_height + line_height_unit; }
            if ( letter_spacing  ) { properties.letterSpacing  = letter_spacing + unit;          }
            if ( word_spacing    ) { properties.wordSpacing    = word_spacing + unit;            }
            if ( text_align      ) { properties.textAlign      = text_align;                     }
            if ( text_transform  ) { properties.textTransform  = text_transform;                 }
            if ( text_decoration ) { properties.textDecoration = text_decoration;                }
            if ( text_color      ) { properties.color          = text_color;                     }

            $preview.removeAttr('style');

            // Customs style attribute
            if ( custom_style ) { $preview.attr('style', custom_style); }

            $preview.css(properties);

          }, 100 ) );

          // Preview black and white backgrounds trigger
          $preview_block.on('click', function() {

            $preview.toggleClass('CSFTICKET--black-background');

            var $toggle = $preview_block.find('.CSFTICKET--toggle');

            if ( $toggle.hasClass('fa-toggle-off') ) {
              $toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
            } else {
              $toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
            }

          });

          if ( !$preview_block.hasClass('hidden') ) {
            $this.trigger('change');
          }

        }

      };

      base.init();

    });
  };

  //
  // Field: upload
  //
  $.fn.CSFTICKET_field_upload = function() {
    return this.each( function() {

      var $this          = $(this),
          $input         = $this.find('input'),
          $upload_button = $this.find('.CSFTICKET--button'),
          $remove_button = $this.find('.CSFTICKET--remove'),
          $library       = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
          wp_media_frame;

      $input.on('change', function( e ) {
        if ( $input.val() ) {
          $remove_button.removeClass('hidden');
        } else {
          $remove_button.addClass('hidden');
        }
      });

      $upload_button.on('click', function( e ) {

        e.preventDefault();

        if ( typeof window.wp === 'undefined' || ! window.wp.media || ! window.wp.media.gallery ) {
          return;
        }

        if ( wp_media_frame ) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          },
        });

        wp_media_frame.on( 'select', function() {

          var attributes = wp_media_frame.state().get('selection').first().attributes;

          if ( $library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1 ) {
            return;
          }

          $input.val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function( e ) {
        e.preventDefault();
        $input.val('').trigger('change');
      });

    });

  };

  //
  // Field: wp_editor
  //
  $.fn.CSFTICKET_field_wp_editor = function() {
    return this.each( function() {

      if ( typeof window.wp.editor === 'undefined' || typeof window.tinyMCEPreInit === 'undefined' || typeof window.tinyMCEPreInit.mceInit.CSFTICKET_wp_editor === 'undefined' ) {
        return;
      }

      var $this     = $(this),
          $editor   = $this.find('.CSFTICKET-wp-editor'),
          $textarea = $this.find('textarea');

      // If there is wp-editor remove it for avoid dupliated wp-editor conflicts.
      var $has_wp_editor = $this.find('.wp-editor-wrap').length || $this.find('.mce-container').length;

      if ( $has_wp_editor ) {
        $editor.empty();
        $editor.append($textarea);
        $textarea.css('display', '');
      }

      // Generate a unique id
      var uid = CSFTICKET.helper.uid('CSFTICKET-editor-');

      $textarea.attr('id', uid);

      // Get default editor settings
      var default_editor_settings = {
        tinymce: window.tinyMCEPreInit.mceInit.CSFTICKET_wp_editor,
        quicktags: window.tinyMCEPreInit.qtInit.CSFTICKET_wp_editor
      };

      // Get default editor settings
      var field_editor_settings = $editor.data('editor-settings');

      // Add on change event handle
      var editor_on_change = function( editor ) {
        editor.on('change', CSFTICKET.helper.debounce( function() {
          editor.save();
          $textarea.trigger('change');
        }, 250 ) );
      };

      // Callback for old wp editor
      var wpEditor = wp.oldEditor ? wp.oldEditor : wp.editor;

      if ( wpEditor && wpEditor.hasOwnProperty('autop') ) {
        wp.editor.autop = wpEditor.autop;
        wp.editor.removep = wpEditor.removep;
        wp.editor.initialize = wpEditor.initialize;
      }

      // Extend editor selector and on change event handler
      default_editor_settings.tinymce = $.extend( {}, default_editor_settings.tinymce, { selector: '#'+ uid, setup: editor_on_change } );

      // Override editor tinymce settings
      if ( field_editor_settings.tinymce === false ) {
        default_editor_settings.tinymce = false;
        $editor.addClass('CSFTICKET-no-tinymce');
      }

      // Override editor quicktags settings
      if ( field_editor_settings.quicktags === false ) {
        default_editor_settings.quicktags = false;
        $editor.addClass('CSFTICKET-no-quicktags');
      }

      // Wait until :visible
      var interval = setInterval(function () {
        if ( $this.is(':visible') ) {
          window.wp.editor.initialize(uid, default_editor_settings);
          clearInterval(interval);
        }
      });

      // Add Media buttons
      if ( field_editor_settings.media_buttons && window.CSFTICKET_media_buttons ) {

        var $editor_buttons = $editor.find('.wp-media-buttons');

        if ( $editor_buttons.length ) {

          $editor_buttons.find('.CSFTICKET-shortcode-button').data('editor-id', uid);

        } else {

          var $media_buttons = $(window.CSFTICKET_media_buttons);

          $media_buttons.find('.CSFTICKET-shortcode-button').data('editor-id', uid);

          $editor.prepend( $media_buttons );

        }

      }

    });

  };

  //
  // Confirm
  //
  $.fn.CSFTICKET_confirm = function() {
    return this.each( function() {
      $(this).on('click', function( e ) {

        var confirm_text   = $(this).data('confirm') || window.CSFTICKET_vars.i18n.confirm;
        var confirm_answer = confirm( confirm_text );

        if ( confirm_answer ) {
          CSFTICKET.vars.is_confirm = true;
        } else {
          e.preventDefault();
          return false;
        }

      });
    });
  };

  $.fn.serializeObject = function(){

    var obj = {};

    $.each( this.serializeArray(), function(i,o){
      var n = o.name,
        v = o.value;

        obj[n] = obj[n] === undefined ? v
          : $.isArray( obj[n] ) ? obj[n].concat( v )
          : [ obj[n], v ];
    });

    return obj;

  };

  //
  // Options Save
  //
  $.fn.CSFTICKET_save = function() {
    return this.each( function() {

      var $this    = $(this),
          $buttons = $('.CSFTICKET-save'),
          $panel   = $('.CSFTICKET-options'),
          flooding = false,
          timeout;

      $this.on('click', function( e ) {

        if ( !flooding ) {

          var $text  = $this.data('save'),
              $value = $this.val();

          $buttons.attr('value', $text);

          if ( $this.hasClass('CSFTICKET-save-ajax') ) {

            e.preventDefault();

            $panel.addClass('CSFTICKET-saving');
            $buttons.prop('disabled', true);

            window.wp.ajax.post( 'CSFTICKET_'+ $panel.data('unique') +'_ajax_save', {
              data: $('#CSFTICKET-form').serializeJSONCSFTICKET()
            })
            .done( function( response ) {

              // clear errors
              $('.CSFTICKET-error').remove();

              if ( Object.keys( response.errors ).length ) {

                var error_icon = '<i class="CSFTICKET-label-error CSFTICKET-error">!</i>';

                $.each(response.errors, function( key, error_message ) {

                  var $field = $('[data-depend-id="'+ key +'"]'),
                      $link  = $('#CSFTICKET-tab-link-'+ ($field.closest('.CSFTICKET-section').index()+1)),
                      $tab   = $link.closest('.CSFTICKET-tab-depth-0');

                  $field.closest('.CSFTICKET-fieldset').append( '<p class="CSFTICKET-text-error CSFTICKET-error">'+ error_message +'</p>' );

                  if ( !$link.find('.CSFTICKET-error').length ) {
                    $link.append( error_icon );
                  }

                  if ( !$tab.find('.CSFTICKET-arrow .CSFTICKET-error').length ) {
                    $tab.find('.CSFTICKET-arrow').append( error_icon );
                  }

                });

              }

              $panel.removeClass('CSFTICKET-saving');
              $buttons.prop('disabled', false).attr('value', $value);
              flooding = false;

              CSFTICKET.vars.form_modified = false;
              CSFTICKET.vars.$form_warning.hide();

              clearTimeout(timeout);

              var $result_success = $('.CSFTICKET-form-success');
              $result_success.empty().append(response.notice).fadeIn('fast', function() {
                timeout = setTimeout( function() {
                  $result_success.fadeOut('fast');
                }, 1000);
              });

            })
            .fail( function( response ) {
              alert( response.error );
            });

          } else {

            CSFTICKET.vars.form_modified = false;

          }

        }

        flooding = true;

      });

    });
  };

  //
  // Option Framework
  //
  $.fn.CSFTICKET_options = function() {
    return this.each( function() {

      var $this         = $(this),
          $content      = $this.find('.CSFTICKET-content'),
          $form_success = $this.find('.CSFTICKET-form-success'),
          $form_warning = $this.find('.CSFTICKET-form-warning'),
          $save_button  = $this.find('.CSFTICKET-header .CSFTICKET-save');

      CSFTICKET.vars.$form_warning = $form_warning;

      // Shows a message white leaving theme options without saving
      if ( $form_warning.length ) {

        window.onbeforeunload = function() {
          return ( CSFTICKET.vars.form_modified && CSFTICKET.vars.is_confirm === false ) ? true : undefined;
        };

        $content.on('change keypress', ':input', function() {
          if ( !CSFTICKET.vars.form_modified ) {
            $form_success.hide();
            $form_warning.fadeIn('fast');
            CSFTICKET.vars.form_modified = true;
          }
        });

      }

      if ( $form_success.hasClass('CSFTICKET-form-show') ) {
        setTimeout( function() {
          $form_success.fadeOut('fast');
        }, 1000);
      }

      $(document).keydown(function (event) {
        if ( ( event.ctrlKey || event.metaKey ) && event.which === 83 ) {
          $save_button.trigger('click');
          event.preventDefault();
          return false;
        }
      });

    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.CSFTICKET_taxonomy = function() {
    return this.each( function() {

      var $this = $(this),
          $form = $this.parents('form');

      if ( $form.attr('id') === 'addtag' ) {

        var $submit = $form.find('#submit'),
            $cloned = $this.find('.CSFTICKET-field').CSFTICKET_clone();

        $submit.on( 'click', function() {

          if ( !$form.find('.form-required').hasClass('form-invalid') ) {

            $this.data('inited', false);

            $this.empty();

            $this.html( $cloned );

            $cloned = $cloned.CSFTICKET_clone();

            $this.CSFTICKET_reload_script();

          }

        });

      }

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.CSFTICKET_shortcode = function() {

    var base = this;

    base.shortcode_parse = function( serialize, key ) {

      var shortcode = '';

      $.each(serialize, function( shortcode_key, shortcode_values ) {

        key = ( key ) ? key : shortcode_key;

        shortcode += '[' + key;

        $.each(shortcode_values, function( shortcode_tag, shortcode_value ) {

          if ( shortcode_tag === 'content' ) {

            shortcode += ']';
            shortcode += shortcode_value;
            shortcode += '[/'+ key +'';

          } else {

            shortcode += base.shortcode_tags( shortcode_tag, shortcode_value );

          }

        });

        shortcode += ']';

      });

      return shortcode;

    };

    base.shortcode_tags = function( shortcode_tag, shortcode_value ) {

      var shortcode = '';

      if ( shortcode_value !== '' ) {

        if ( typeof shortcode_value === 'object' && !$.isArray( shortcode_value ) ) {

          $.each(shortcode_value, function( sub_shortcode_tag, sub_shortcode_value ) {

            // sanitize spesific key/value
            switch( sub_shortcode_tag ) {

              case 'background-image':
                sub_shortcode_value = ( sub_shortcode_value.url  ) ? sub_shortcode_value.url : '';
              break;

            }

            if ( sub_shortcode_value !== '' ) {
              shortcode += ' ' + sub_shortcode_tag.replace('-', '_') + '="' + sub_shortcode_value.toString() + '"';
            }

          });

        } else {

          shortcode += ' ' + shortcode_tag.replace('-', '_') + '="' + shortcode_value.toString() + '"';

        }

      }

      return shortcode;

    };

    base.insertAtChars = function( _this, currentValue ) {

      var obj = ( typeof _this[0].name !== 'undefined' ) ? _this[0] : _this;

      if ( obj.value.length && typeof obj.selectionStart !== 'undefined' ) {
        obj.focus();
        return obj.value.substring( 0, obj.selectionStart ) + currentValue + obj.value.substring( obj.selectionEnd, obj.value.length );
      } else {
        obj.focus();
        return currentValue;
      }

    };

    base.send_to_editor = function( html, editor_id ) {

      var tinymce_editor;

      if ( typeof tinymce !== 'undefined' ) {
        tinymce_editor = tinymce.get( editor_id );
      }

      if ( tinymce_editor && !tinymce_editor.isHidden() ) {
        tinymce_editor.execCommand( 'mceInsertContent', false, html );
      } else {
        var $editor = $('#'+editor_id);
        $editor.val( base.insertAtChars( $editor, html ) ).trigger('change');
      }

    };

    return this.each( function() {

      var $modal   = $(this),
          $load    = $modal.find('.CSFTICKET-modal-load'),
          $content = $modal.find('.CSFTICKET-modal-content'),
          $insert  = $modal.find('.CSFTICKET-modal-insert'),
          $loading = $modal.find('.CSFTICKET-modal-loading'),
          $select  = $modal.find('select'),
          modal_id = $modal.data('modal-id'),
          nonce    = $modal.data('nonce'),
          editor_id,
          target_id,
          gutenberg_id,
          sc_key,
          sc_name,
          sc_view,
          sc_group,
          $cloned,
          $button;

      $(document).on('click', '.CSFTICKET-shortcode-button[data-modal-id="'+ modal_id +'"]', function( e ) {

        e.preventDefault();

        $button      = $(this);
        editor_id    = $button.data('editor-id')    || false;
        target_id    = $button.data('target-id')    || false;
        gutenberg_id = $button.data('gutenberg-id') || false;

        $modal.show();

        // single usage trigger first shortcode
        if ( $modal.hasClass('CSFTICKET-shortcode-single') && sc_name === undefined ) {
          $select.trigger('change');
        }

      });

      $select.on( 'change', function() {

        var $option   = $(this);
        var $selected = $option.find(':selected');

        sc_key   = $option.val();
        sc_name  = $selected.data('shortcode');
        sc_view  = $selected.data('view') || 'normal';
        sc_group = $selected.data('group') || sc_name;

        $load.empty();

        if ( sc_key ) {

          $loading.show();

          window.wp.ajax.post( 'CSFTICKET-get-shortcode-'+ modal_id, {
            shortcode_key: sc_key,
            nonce: nonce
          })
          .done( function( response ) {

            $loading.hide();

            var $appended = $(response.content).appendTo($load);

            $insert.parent().removeClass('hidden');

            $cloned = $appended.find('.CSFTICKET--repeat-shortcode').CSFTICKET_clone();

            $appended.CSFTICKET_reload_script();
            $appended.find('.CSFTICKET-fields').CSFTICKET_reload_script();

          });

        } else {

          $insert.parent().addClass('hidden');

        }

      });

      $insert.on('click', function( e ) {

        e.preventDefault();

        if ( $insert.prop('disabled') || $insert.attr('disabled') ) { return; }

        var shortcode = '';
        var serialize = $modal.find('.CSFTICKET-field:not(.hidden)').find(':input:not(.ignore)').serializeObjectCSFTICKET();

        switch ( sc_view ) {

          case 'contents':
            var contentsObj = ( sc_name ) ? serialize[sc_name] : serialize;
            $.each(contentsObj, function( sc_key, sc_value ) {
              var sc_tag = ( sc_name ) ? sc_name : sc_key;
              shortcode += '['+ sc_tag +']'+ sc_value +'[/'+ sc_tag +']';
            });
          break;

          case 'group':

            shortcode += '[' + sc_name;
            $.each(serialize[sc_name], function( sc_key, sc_value ) {
              shortcode += base.shortcode_tags( sc_key, sc_value );
            });
            shortcode += ']';
            shortcode += base.shortcode_parse( serialize[sc_group], sc_group );
            shortcode += '[/' + sc_name + ']';

          break;

          case 'repeater':
            shortcode += base.shortcode_parse( serialize[sc_group], sc_group );
          break;

          default:
            shortcode += base.shortcode_parse( serialize );
          break;

        }

        shortcode = ( shortcode === '' ) ? '['+ sc_name +']' : shortcode;

        if ( gutenberg_id ) {

          var content = window.CSFTICKET_gutenberg_props.attributes.hasOwnProperty('shortcode') ? window.CSFTICKET_gutenberg_props.attributes.shortcode : '';
          window.CSFTICKET_gutenberg_props.setAttributes({shortcode: content + shortcode});

        } else if ( editor_id ) {

          base.send_to_editor( shortcode, editor_id );

        } else {

          var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
          $textarea.val( base.insertAtChars( $textarea, shortcode ) ).trigger('change');

        }

        $modal.hide();

      });

      $modal.on('click', '.CSFTICKET--repeat-button', function( e ) {

        e.preventDefault();

        var $repeatable = $modal.find('.CSFTICKET--repeatable');
        var $new_clone  = $cloned.CSFTICKET_clone();
        var $remove_btn = $new_clone.find('.CSFTICKET-repeat-remove');

        var $appended = $new_clone.appendTo( $repeatable );

        $new_clone.find('.CSFTICKET-fields').CSFTICKET_reload_script();

        CSFTICKET.helper.name_nested_replace( $modal.find('.CSFTICKET--repeat-shortcode'), sc_group );

        $remove_btn.on('click', function() {

          $new_clone.remove();

          CSFTICKET.helper.name_nested_replace( $modal.find('.CSFTICKET--repeat-shortcode'), sc_group );

        });

      });

      $modal.on('click', '.CSFTICKET-modal-close, .CSFTICKET-modal-overlay', function() {
        $modal.hide();
      });

    });
  };

  //
  // WP Color Picker
  //
  if ( typeof Color === 'function' ) {

    Color.prototype.toString = function() {

      if ( this._alpha < 1 ) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt( this._color, 10 ).toString( 16 );

      if ( this.error ) { return ''; }

      if ( hex.length < 6 ) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  CSFTICKET.funcs.parse_color = function( color ) {

    var value = color.replace(/\s+/g, ''),
        trans = ( value.indexOf('rgba') !== -1 ) ? parseFloat( value.replace(/^.*,(.+)\)/, '$1') * 100 ) : 100,
        rgba  = ( trans < 100 ) ? true : false;

    return { value: value, transparent: trans, rgba: rgba };

  };

  $.fn.CSFTICKET_color = function() {
    return this.each( function() {

      var $input        = $(this),
          picker_color  = CSFTICKET.funcs.parse_color( $input.val() ),
          palette_color = window.CSFTICKET_vars.color_palette.length ? window.CSFTICKET_vars.color_palette : true,
          $container;

      // Destroy and Reinit
      if ( $input.hasClass('wp-color-picker') ) {
        $input.closest('.wp-picker-container').after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function( event, ui ) {

          var ui_color_value = ui.color.toString();

          $container.removeClass('CSFTICKET--transparent-active');
          $container.find('.CSFTICKET--transparent-offset').css('background-color', ui_color_value);
          $input.val(ui_color_value).trigger('change');

        },
        create: function() {

          $container = $input.closest('.wp-picker-container');

          var a8cIris = $input.data('a8cIris'),
              $transparent_wrap = $('<div class="CSFTICKET--transparent-wrap">' +
                                '<div class="CSFTICKET--transparent-slider"></div>' +
                                '<div class="CSFTICKET--transparent-offset"></div>' +
                                '<div class="CSFTICKET--transparent-text"></div>' +
                                '<div class="CSFTICKET--transparent-button">transparent <i class="fas fa-toggle-off"></i></div>' +
                                '</div>').appendTo( $container.find('.wp-picker-holder') ),
              $transparent_slider = $transparent_wrap.find('.CSFTICKET--transparent-slider'),
              $transparent_text   = $transparent_wrap.find('.CSFTICKET--transparent-text'),
              $transparent_offset = $transparent_wrap.find('.CSFTICKET--transparent-offset'),
              $transparent_button = $transparent_wrap.find('.CSFTICKET--transparent-button');

          if ( $input.val() === 'transparent' ) {
            $container.addClass('CSFTICKET--transparent-active');
          }

          $transparent_button.on('click', function() {
            if ( $input.val() !== 'transparent' ) {
              $input.val('transparent').trigger('change').removeClass('iris-error');
              $container.addClass('CSFTICKET--transparent-active');
            } else {
              $input.val( a8cIris._color.toString() ).trigger('change');
              $container.removeClass('CSFTICKET--transparent-active');
            }
          });

          $transparent_slider.slider({
            value: picker_color.transparent,
            step: 1,
            min: 0,
            max: 100,
            slide: function( event, ui ) {

              var slide_value = parseFloat( ui.value / 100 );
              a8cIris._color._alpha = slide_value;
              $input.wpColorPicker( 'color', a8cIris._color.toString() );
              $transparent_text.text( ( slide_value === 1 || slide_value === 0 ? '' : slide_value ) );

            },
            create: function() {

              var slide_value = parseFloat( picker_color.transparent / 100 ),
                  text_value  = slide_value < 1 ? slide_value : '';

              $transparent_text.text(text_value);
              $transparent_offset.css('background-color', picker_color.value);

              $container.on('click', '.wp-picker-clear', function() {

                a8cIris._color._alpha = 1;
                $transparent_text.text('');
                $transparent_slider.slider('option', 'value', 100);
                $container.removeClass('CSFTICKET--transparent-active');
                $input.trigger('change');

              });

              $container.on('click', '.wp-picker-default', function() {

                var default_color = CSFTICKET.funcs.parse_color( $input.data('default-color') ),
                    default_value = parseFloat( default_color.transparent / 100 ),
                    default_text  = default_value < 1 ? default_value : '';

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider('option', 'value', default_color.transparent);

              });

            }
          });
        }
      });

    });
  };

  //
  // ChosenJS
  //
  $.fn.CSFTICKET_chosen = function() {
    return this.each( function() {

      var $this       = $(this),
          $inited     = $this.parent().find('.chosen-container'),
          is_sortable = $this.hasClass('CSFTICKET-chosen-sortable') || false,
          is_ajax     = $this.hasClass('CSFTICKET-chosen-ajax') || false,
          is_multiple = $this.attr('multiple') || false,
          set_width   = is_multiple ? '100%' : 'auto',
          set_options = $.extend({
            allow_single_deselect: true,
            disable_search_threshold: 10,
            width: set_width,
            no_results_text: window.CSFTICKET_vars.i18n.no_results_text,
          }, $this.data('chosen-settings'));

      if ( $inited.length ) {
        $inited.remove();
      }

      // Chosen ajax
      if ( is_ajax ) {

        var set_ajax_options = $.extend({
          data: {
            type: 'post',
            nonce: '',
          },
          allow_single_deselect: true,
          disable_search_threshold: -1,
          width: '100%',
          min_length: 3,
          type_delay: 500,
          typing_text: window.CSFTICKET_vars.i18n.typing_text,
          searching_text: window.CSFTICKET_vars.i18n.searching_text,
          no_results_text: window.CSFTICKET_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

        $this.CSFTICKETAjaxChosen(set_ajax_options);

      } else {

        $this.chosen(set_options);

      }

      // Chosen keep options order
      if ( is_multiple ) {

        var $hidden_select = $this.parent().find('.CSFTICKET-hidden-select');
        var $hidden_value  = $hidden_select.val() || [];

        $this.on('change', function(obj, result) {

          if ( result && result.selected ) {
            $hidden_select.append( '<option value="'+ result.selected +'" selected="selected">'+ result.selected +'</option>' );
          } else if ( result && result.deselected ) {
            $hidden_select.find('option[value="'+ result.deselected +'"]').remove();
          }

          // Force customize refresh
          if ( $hidden_select.children().length === 0 && window.wp.customize !== undefined ) {
            window.wp.customize.control( $hidden_select.data('customize-setting-link') ).setting.set('');
          }

          $hidden_select.trigger('change');

        });

        // Chosen order abstract
        $this.CSFTICKETChosenOrder($hidden_value, true);

      }

      // Chosen sortable
      if ( is_sortable ) {

        var $chosen_container = $this.parent().find('.chosen-container');
        var $chosen_choices   = $chosen_container.find('.chosen-choices');

        $chosen_choices.bind('mousedown', function( event ) {
          if ( $(event.target).is('span') ) {
            event.stopPropagation();
          }
        });

        $chosen_choices.sortable({
          items: 'li:not(.search-field)',
          helper: 'orginal',
          cursor: 'move',
          placeholder: 'search-choice-placeholder',
          start: function(e,ui) {
            ui.placeholder.width( ui.item.innerWidth() );
            ui.placeholder.height( ui.item.innerHeight() );
          },
          update: function( e, ui ) {

            var select_options = '';
            var chosen_object  = $this.data('chosen');
            var $prev_select   = $this.parent().find('.CSFTICKET-hidden-select');

            $chosen_choices.find('.search-choice-close').each( function() {
              var option_array_index = $(this).data('option-array-index');
              $.each(chosen_object.results_data, function(index, data) {
                if ( data.array_index === option_array_index ){
                  select_options += '<option value="'+ data.value +'" selected>'+ data.value +'</option>';
                }
              });
            });

            $prev_select.children().remove();
            $prev_select.append(select_options);
            $prev_select.trigger('change');

          }
        });

      }

    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.CSFTICKET_checkbox = function() {
    return this.each( function() {

      var $this     = $(this),
          $input    = $this.find('.CSFTICKET--input'),
          $checkbox = $this.find('.CSFTICKET--checkbox');

      $checkbox.on('click', function() {
        $input.val( Number( $checkbox.prop('checked') ) ).trigger('change');
      });

    });
  };

  //
  // Siblings
  //
  $.fn.CSFTICKET_siblings = function() {
    return this.each( function() {

      var $this     = $(this),
          $siblings = $this.find('.CSFTICKET--sibling'),
          multiple  = $this.data('multiple') || false;

      $siblings.on('click', function() {

        var $sibling = $(this);

        if ( multiple ) {

          if ( $sibling.hasClass('CSFTICKET--active') ) {
            $sibling.removeClass('CSFTICKET--active');
            $sibling.find('input').prop('checked', false).trigger('change');
          } else {
            $sibling.addClass('CSFTICKET--active');
            $sibling.find('input').prop('checked', true).trigger('change');
          }

        } else {

          $this.find('input').prop('checked', false);
          $sibling.find('input').prop('checked', true).trigger('change');
          $sibling.addClass('CSFTICKET--active').siblings().removeClass('CSFTICKET--active');

        }

      });

    });
  };

  //
  // Help Tooltip
  //
  $.fn.CSFTICKET_help = function() {
    return this.each( function() {

      var $this = $(this),
          $tooltip,
          offset_left;

      $this.on({
        mouseenter: function() {

          $tooltip = $( '<div class="CSFTICKET-tooltip"></div>' ).html( $this.find('.CSFTICKET-help-text').html() ).appendTo('body');
          offset_left = ( CSFTICKET.vars.is_rtl ) ? ( $this.offset().left + 24 ) : ( $this.offset().left - $tooltip.outerWidth() );

          $tooltip.css({
            top: $this.offset().top - ( ( $tooltip.outerHeight() / 2 ) - 14 ),
            left: offset_left,
          });

        },
        mouseleave: function() {

          if ( $tooltip !== undefined ) {
            $tooltip.remove();
          }

        }

      });

    });
  };

  //
  // Customize Refresh
  //
  $.fn.CSFTICKET_customizer_refresh = function() {
    return this.each( function() {

      var $this    = $(this),
          $complex = $this.closest('.CSFTICKET-customize-complex');

      if ( $complex.length ) {

        var $input  = $complex.find(':input'),
            $unique = $complex.data('unique-id'),
            $option = $complex.data('option-id'),
            obj     = $input.serializeObjectCSFTICKET(),
            data    = ( !$.isEmptyObject(obj) ) ? obj[$unique][$option] : '',
            control = window.wp.customize.control($unique +'['+ $option +']');

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set( data );

      } else {

        $this.find(':input').first().trigger('change');

      }

      $(document).trigger('CSFTICKET-customizer-refresh', $this);

    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.CSFTICKET_customizer_listen = function( options ) {

    var settings = $.extend({
      closest: false,
    }, options );

    return this.each( function() {

      if ( window.wp.customize === undefined ) { return; }

      var $this     = ( settings.closest ) ? $(this).closest('.CSFTICKET-customize-complex') : $(this),
          $input    = $this.find(':input'),
          unique_id = $this.data('unique-id'),
          option_id = $this.data('option-id');

      if ( unique_id === undefined ) { return; }

      $input.on('change keyup', CSFTICKET.helper.debounce( function() {

        var obj = $this.find(':input').serializeObjectCSFTICKET();
        var val = ( !$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id] ) ? obj[unique_id][option_id] : '';

        window.wp.customize.control( unique_id +'['+ option_id +']' ).setting.set( val );

      }, 250 ) );

    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on('expanded', '.control-section', function() {

    var $this  = $(this);

    if ( $this.hasClass('open') && !$this.data('inited') ) {

      var $fields  = $this.find('.CSFTICKET-customize-field');
      var $complex = $this.find('.CSFTICKET-customize-complex');

      if ( $fields.length ) {
        $this.CSFTICKET_dependency();
        $fields.CSFTICKET_reload_script({dependency: false});
        $complex.CSFTICKET_customizer_listen();
      }

      $this.data('inited', true);

    }

  });

  //
  // Window on resize
  //
  CSFTICKET.vars.$window.on('resize CSFTICKET.resize', CSFTICKET.helper.debounce( function( event ) {

    var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? CSFTICKET.vars.$window.width() : window.innerWidth;

    if ( window_width <= 782 && !CSFTICKET.vars.onloaded ) {
      $('.CSFTICKET-section').CSFTICKET_reload_script();
      CSFTICKET.vars.onloaded  = true;
    }

  }, 200)).trigger('CSFTICKET.resize');

  //
  // Widgets Framework
  //
  $.fn.CSFTICKET_widgets = function() {
    if ( this.length ) {

      $(document).on('widget-added widget-updated', function( event, $widget ) {
        $widget.find('.CSFTICKET-fields').CSFTICKET_reload_script();
      });

      $('.widgets-sortables, .control-section-sidebar').on('sortstop', function( event, ui ) {
        ui.item.find('.CSFTICKET-fields').CSFTICKET_reload_script_retry();
      });

      $(document).on('click', '.widget-top', function( event ) {
        $(this).parent().find('.CSFTICKET-fields').CSFTICKET_reload_script();
      });

    }
  };

  //
  // Retry Plugins
  //
  $.fn.CSFTICKET_reload_script_retry = function() {
    return this.each( function() {

      var $this = $(this);

      if ( $this.data('inited') ) {
        $this.children('.CSFTICKET-field-wp_editor').CSFTICKET_field_wp_editor();
      }

    });
  };

  //
  // Reload Plugins
  //
  $.fn.CSFTICKET_reload_script = function( options ) {

    var settings = $.extend({
      dependency: true,
    }, options );

    return this.each( function() {

      var $this = $(this);

      // Avoid for conflicts
      if ( !$this.data('inited') ) {

        // Field plugins
        $this.children('.CSFTICKET-field-accordion').CSFTICKET_field_accordion();
        $this.children('.CSFTICKET-field-backup').CSFTICKET_field_backup();
        $this.children('.CSFTICKET-field-background').CSFTICKET_field_background();
        $this.children('.CSFTICKET-field-code_editor').CSFTICKET_field_code_editor();
        $this.children('.CSFTICKET-field-date').CSFTICKET_field_date();
        $this.children('.CSFTICKET-field-fieldset').CSFTICKET_field_fieldset();
        $this.children('.CSFTICKET-field-gallery').CSFTICKET_field_gallery();
        $this.children('.CSFTICKET-field-group').CSFTICKET_field_group();
        $this.children('.CSFTICKET-field-icon').CSFTICKET_field_icon();
        $this.children('.CSFTICKET-field-media').CSFTICKET_field_media();
        $this.children('.CSFTICKET-field-map').CSFTICKET_field_map();
        $this.children('.CSFTICKET-field-repeater').CSFTICKET_field_repeater();
        $this.children('.CSFTICKET-field-slider').CSFTICKET_field_slider();
        $this.children('.CSFTICKET-field-sortable').CSFTICKET_field_sortable();
        $this.children('.CSFTICKET-field-sorter').CSFTICKET_field_sorter();
        $this.children('.CSFTICKET-field-spinner').CSFTICKET_field_spinner();
        $this.children('.CSFTICKET-field-switcher').CSFTICKET_field_switcher();
        $this.children('.CSFTICKET-field-tabbed').CSFTICKET_field_tabbed();
        $this.children('.CSFTICKET-field-typography').CSFTICKET_field_typography();
        $this.children('.CSFTICKET-field-upload').CSFTICKET_field_upload();
        $this.children('.CSFTICKET-field-wp_editor').CSFTICKET_field_wp_editor();

        // Field colors
        $this.children('.CSFTICKET-field-border').find('.CSFTICKET-color').CSFTICKET_color();
        $this.children('.CSFTICKET-field-background').find('.CSFTICKET-color').CSFTICKET_color();
        $this.children('.CSFTICKET-field-color').find('.CSFTICKET-color').CSFTICKET_color();
        $this.children('.CSFTICKET-field-color_group').find('.CSFTICKET-color').CSFTICKET_color();
        $this.children('.CSFTICKET-field-link_color').find('.CSFTICKET-color').CSFTICKET_color();
        $this.children('.CSFTICKET-field-typography').find('.CSFTICKET-color').CSFTICKET_color();

        // Field chosenjs
        $this.children('.CSFTICKET-field-select').find('.CSFTICKET-chosen').CSFTICKET_chosen();

        // Field Checkbox
        $this.children('.CSFTICKET-field-checkbox').find('.CSFTICKET-checkbox').CSFTICKET_checkbox();

        // Field Siblings
        $this.children('.CSFTICKET-field-button_set').find('.CSFTICKET-siblings').CSFTICKET_siblings();
        $this.children('.CSFTICKET-field-image_select').find('.CSFTICKET-siblings').CSFTICKET_siblings();
        $this.children('.CSFTICKET-field-palette').find('.CSFTICKET-siblings').CSFTICKET_siblings();

        // Help Tooptip
        $this.children('.CSFTICKET-field').find('.CSFTICKET-help').CSFTICKET_help();

        if ( settings.dependency ) {
          $this.CSFTICKET_dependency();
        }

        $this.data('inited', true);

        $(document).trigger('CSFTICKET-reload-script', $this);

      }

    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready( function() {

    $('.CSFTICKET-save').CSFTICKET_save();
    $('.CSFTICKET-options').CSFTICKET_options();
    $('.CSFTICKET-sticky-header').CSFTICKET_sticky();
    $('.CSFTICKET-nav-options').CSFTICKET_nav_options();
    $('.CSFTICKET-nav-metabox').CSFTICKET_nav_metabox();
    $('.CSFTICKET-taxonomy').CSFTICKET_taxonomy();
    $('.CSFTICKET-page-templates').CSFTICKET_page_templates();
    $('.CSFTICKET-post-formats').CSFTICKET_post_formats();
    $('.CSFTICKET-shortcode').CSFTICKET_shortcode();
    $('.CSFTICKET-search').CSFTICKET_search();
    $('.CSFTICKET-confirm').CSFTICKET_confirm();
    $('.CSFTICKET-expand-all').CSFTICKET_expand_all();
    $('.CSFTICKET-onload').CSFTICKET_reload_script();
    $('.widget').CSFTICKET_widgets();

  });

})( jQuery, window, document );
