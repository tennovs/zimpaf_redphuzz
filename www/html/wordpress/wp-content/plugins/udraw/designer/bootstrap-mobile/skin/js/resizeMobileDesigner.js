var d0F={'b':function(F,u7){return F===u7;}
,'I3':function(F,u7){return F==u7;}
,'p':function(F,u7){return F==u7;}
,'Q':function(F,u7){return F==u7;}
,'W3':function(F,u7){return F<u7;}
,'D':function(F,u7){return F===u7;}
,'v3':function(F,u7){return F<u7;}
,'k':function(F,u7){return F==u7;}
,'Z':function(F,u7){return F-u7;}
,'N3':function(F,u7){return F==u7;}
,'E3':function(F,u7){return F==u7;}
,'T':function(F,u7){return F==u7;}
,'l':function(F,u7){return F==u7;}
,'I':function(F,u7){return F==u7;}
,'H':function(F,u7){return F-u7;}
,'H3':function(F,u7){return F<u7;}
,'K':function(F,u7){return F==u7;}
,'y3':function(F,u7){return F==u7;}
,'J3':function(F,u7){return F>u7;}
,'n3':function(F,u7){return F>u7;}
,'O3':function(F,u7){return F!=u7;}
,'M':function(F,u7){return F==u7;}
,'U3':function(F,u7){return F<u7;}
,'V':function(F,u7){return F==u7;}
,'f3':function(F,u7){return F>u7;}
,'e':function(F,u7){return F===u7;}
,'i3':function(F,u7){return F<u7;}
,'T3':function(F,u7){return F/u7;}
,'B':function(F,u7){return F==u7;}
,'o3':function(F,u7){return F/u7;}
,'r':function(F,u7){return F==u7;}
,'s3':function(F,u7,Y4){return F/u7/Y4;}
,'c3':function(F,u7){return F>u7;}
}
;(function($){RacadDesigner["mobile"]={resizeDesigner:function(){fabric.Object.prototype.cornerSize=12;jQuery('h5[data-i18n="[html]text.or"]').parent().css('padding',0);jQuery('.jQimage-upload-btn').parent().css('margin-top','-5px');jQuery('.inner-image-container')["css"]('height','40%');jQuery('#images-list .element-btn')["css"]('padding','3px');}
}
;RacadDesigner["updateModalSideBar"]=function(){return ;}
;RacadDesigner["togglePages"]=function(){return ;}
;RacadDesigner["moveFloatToolbar"]=function(F){var u7=RacadDesigner.canvas["getAbsoluteCoords"](F,jQuery('.float-toolbar')),Y4=F["getBoundingRect"]();jQuery('.float-toolbar')["css"]('left',u7["left"]+(d0F["Z"]((jQuery('.float-toolbar').width()/2),(Y4.width/2))));jQuery('.float-toolbar')["css"]('top',u7.top+Y4.height+15);}
;RacadDesigner.canvas["on"]("object:selected",function(F){var u7=F["target"];if((u7&&u7["hasOwnProperty"]('racad_properties')&&u7["racad_properties"]["isLabelled"])||d0F["V"](u7["type"],'group')){$('[data-udraw="textArea"]')["hide"]();}
else{$('[data-udraw="textArea"]')["show"]();}
jQuery('[data-udraw="designerColourContainer"]')["show"]();if(d0F["p"](u7["type"],'i-text')||d0F["K"](u7["type"],'text')||d0F["M"](u7["type"],'group')||d0F["r"](u7["type"],'textbox')){jQuery('#text-toolbox')["show"]();}
else{jQuery('#text-toolbox')["hide"]();}
if(d0F["Q"](u7["type"],'image')){jQuery('[data-udraw="replaceImage"]')["show"]();jQuery('[data-udraw="designerColourContainer"]')["hide"]();}
else{jQuery('[data-udraw="replaceImage"]')["hide"]();}
if(d0F["l"](u7["type"],'path-group')){jQuery('[data-udraw="designerColourContainer"]')["hide"]();}
if(d0F["D"](u7["type"],'text')||d0F["b"](u7["type"],'i-text')||d0F["e"](u7["type"],'textbox')){jQuery('li.element-btn a.text')["trigger"]('click');}
else{jQuery('li.element-btn a.layers')["trigger"]('click');}
RacadDesigner["moveFloatToolbar"](u7);jQuery('.float-toolbar')["show"]();}
);RacadDesigner.canvas["on"]("object:modified",function(F){var u7=F["target"];RacadDesigner["moveFloatToolbar"](u7);}
);RacadDesigner.canvas["on"]("object:added",function(F){var u7=F["target"];RacadDesigner["moveFloatToolbar"](u7);jQuery('.float-toolbar')["show"]();}
);RacadDesigner.canvas["on"]("object:moving",function(F){var u7=F["target"];RacadDesigner["moveFloatToolbar"](u7);}
);RacadDesigner.canvas["on"]("object:rotating",function(F){var u7=F["target"];RacadDesigner["moveFloatToolbar"](u7);}
);RacadDesigner.canvas["on"]("object:scaling",function(F){var u7=F["target"];RacadDesigner["moveFloatToolbar"](u7);}
);RacadDesigner.canvas["on"]("selection:cleared",function(F){jQuery('.float-toolbar')["hide"]();}
);var f=RacadDesigner["UpdateUIElements"];RacadDesigner["UpdateUIElements"]=function(){f();var F=RacadDesigner.canvas["getActiveObject"](),u7=RacadDesigner.canvas["getActiveGroup"]();if(F){if(d0F["B"](F["type"],'i-text')||d0F["k"](F["type"],'text')||d0F["I"](F["type"],'textbox')||(d0F["T"](F["type"],'group')&&F["hasOwnProperty"]('racad_properties')&&F["racad_properties"]["isAdvancedText"])||u7){RacadDesigner["Text"]["UpdateFontStylesUI"]();}
}
}
;jQuery('#copy-paste-btn')["click"](function(){RacadDesigner["copyObject"]();RacadDesigner["pasteObject"]();}
);jQuery('[data-udraw="toolboxClose"]')["click"](function(){jQuery(this).parent().modal('hide');}
);RacadDesigner["resizeElementButtons"]=function(){var u7=d0F["H"]((jQuery('#elements-list').width()/6),10),Y4=u7+10;jQuery('#elements-list .element-btn').width(u7);jQuery('#elements-list .element-btn').height(Y4);jQuery('#elements-list .element-btn a i')["each"](function(){var F=(d0F["o3"](u7,20));if(d0F["U3"](F,1.25)){F=1.25;jQuery('#elements-list .element-btn a span')["hide"]();}
else{jQuery('#elements-list .element-btn a span')["show"]();if(d0F["c3"](F,5)){F=5;}
}
jQuery(this)["css"]('font-size',F+'em');}
);jQuery('#elements-list .element-btn a span')["each"](function(){var F=(d0F["s3"](u7,20,2));if(d0F["f3"](F,2)){F=2;}
jQuery(this)["css"]('font-size',F+'em');}
);if(d0F["v3"](jQuery(window).width(),655)){jQuery('.actions-list div')["hide"]();}
else{jQuery('.actions-list div')["show"]();}
}
;var C=RacadDesigner["Align"]["AlignObjects"];RacadDesigner["Align"]["AlignObjects"]=function(F){C(F);var u7=RacadDesigner.canvas["getActiveObject"]();if(RacadDesigner.canvas["getActiveGroup"]()){u7=RacadDesigner.canvas["getActiveGroup"]();}
if(d0F["O3"](u7,null)){RacadDesigner["moveFloatToolbar"](u7);}
}
;jQuery('#udraw-bootstrap')["on"]('udraw-image-collection-loaded',function(F){var u7=F["subDirectory"],Y4=F["categoryContainer"];if(d0F["y3"](Y4,'[data-udraw="uDrawClipartFolderContainer"]')&&d0F["n3"](u7.length,0)){jQuery('[data-udraw="uDrawClipartFolderContainer"]')["hide"]();}
}
);jQuery('[data-udraw="layerLabelsModal"]')["on"]("focus","textarea.labelLayersInput",function(){jQuery('[data-udraw="textArea"')["hide"]();var u7=jQuery(this)["attr"]('id')["replace"]('-input',''),Y4=new Array();RacadDesigner.canvas["getObjects"]()["forEach"](function(F){if(F["hasOwnProperty"]('racad_properties')&&d0F["E3"](F["racad_properties"]["isLabelled"],u7)){Y4["push"](F);}
}
);if(d0F["J3"](Y4.length,0)){var U=new Array();for(var V1=0;d0F["i3"](V1,Y4.length);V1++){U["push"](Y4[V1].top);}
var z1=Math["max"]["apply"](null,U),h1=Y4[0];for(var H0=0;d0F["W3"](H0,Y4.length);H0++){if(d0F["N3"](Y4[H0].top,z1)){var F6=function(F){h1=F[H0];}
;F6(Y4);break;}
}
RacadDesigner["moveFloatToolbar"](h1);jQuery('.float-toolbar')["show"]();}
}
);RacadDesigner["changeDisplayOrientation"]=function(F){if(d0F["I3"](F,'rtl')){$('div[data-udraw="uDrawBootstrap"]')["css"]({'direction':F,'text-align':'right'}
);$('#udraw-bootstrap .input-group input.form-control:first-child')["css"]({'border-top-left-radius':0,'border-bottom-left-radius':0,'border-top-right-radius':'4px','border-bottom-right-radius':'4px'}
);$('#udraw-bootstrap span.input-group-addon:last-child')["css"]({'border-top-left-radius':'4px','border-bottom-left-radius':'4px','border-top-right-radius':0,'border-bottom-right-radius':0,'border-right':0,'border-left':'1px solid #ccc'}
);$('#udraw-bootstrap .modal')["css"]('text-align','right');$('[data-udraw="versionContainer"], .modal-header-btn-container')["css"]('float','left');$('.dropdown-menu')["css"]('text-align','right');$('ul.left-dropdown')["each"](function(){$(this)["removeClass"]('left-dropdown')["addClass"]('right-dropdown');}
);$('input[name="product-size"]')["next"]()["css"]('unicode-bidi','bidi-override');$('.dropdown-menu:not(.right-dropdown)')["css"]('right',0);}
}
;RacadDesigner["center_designer"]=function($){var u7=$('[data-udraw="canvasWrapper"] table').width(),Y4=$('[data-udraw="canvasContainer"]').width(),U=d0F["T3"]((Y4-u7),2);if(d0F["H3"](U,0)){U=0;}
$('[data-udraw="canvasWrapper"]')["css"]('margin-left',U+'px');}
;jQuery('[data-udraw="uDrawBootstrap"]')["on"]('udraw-canvas-scaled',function(){RacadDesigner["center_designer"](jQuery);}
);}
)(window["jQuery"]);