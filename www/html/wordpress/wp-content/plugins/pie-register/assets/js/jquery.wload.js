/* AuthorďĽšwanrenya@gmail.com
 * Github:https://github.com/onfuns/jquery.Wload.js
 * DateďĽš2017-7-1
 */

;(function(w,f){
    'use strict'
    f(jQuery)
  }(window,function($){
    if(!$){
      throw new Error('jquery is must be imported')
    }
    function Wload(options){
      this.defaultOpt = {
        text:'',
        time:'',
        el:''
      }
      this.opt = $.extend({},this.defaultOpt,options)
    }
    Wload.prototype.hide = function(obj){
      if(obj.time){
        var _this = this
        setTimeout(function(){
          $(_this.opt.el).find('.w_load_body').remove()
          $(_this.opt.el).removeClass('w_load_body_parent')
        },obj.time)
      }else{
        $(this.opt.el).find('.w_load_body').remove()
        $(this.opt.el).removeClass('w_load_body_parent')
      }
    }
    Wload.prototype.bindEvent = function(){}
    Wload.prototype.creatDom = function(){
      var dom = $(this.opt.el)
      var div = $('<div class="w_load_body"></div>')
      div.append('<div class="w_load_content"><span class="w_load_icon"></span><span class="w_load_content_text">'+ this.opt.text || '' +'</span></div>')
      dom.addClass('w_load_body_parent').append(div)
      var con = dom.find('.w_load_content')
      con.css({
        'margin-left':-con.outerWidth()/2,
        'margin-top':-con.outerHeight()/2
      })
    }
    $.fn.Wload = function(options,other){
      var dom = $(this)
      var opt = options
      if(typeof opt == 'object'){
        opt = opt.el ? opt : $.extend({},opt,{el:dom})
      }else{
        if(typeof other == 'object'){
          opt = $.extend({},{el:dom},other)
        }else{
          opt = {el:dom}
        }
      }
  
      var W = new Wload(opt)
      if(options == 'hide'){
        W['hide'](other)
        return
      }
      W.creatDom()
    }
  }))