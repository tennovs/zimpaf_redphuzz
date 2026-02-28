// Utility check if object creation is allowed (constructor)
var appPath = "https://live.goepower.com/";
if (typeof Object.create !== 'function') {
    Object.create = function (obj) {
        function F() { };
        F.prototype = obj;
        return new F();
    };
}
(function ($, window, document, undefined) {
    var Xmpie = {
        init: function (options, elem) {
            var self = this;
            window.Xmpie = self;
            self.options = $.extend({}, $.fn.xmpieProduct.options, options);

            self.submitButton = options.submitButton; // $('#btnPrev');
            self.priceTag = options.priceTag;
            self.qtyTag = options.qtyTag;

            self.slider = options.slider;
            self.loader = options.loader;
            self.elem = elem;
            self.$elem = $(elem);
            self.xmpieHandler = options.xmpieHandler;

            self.productId = options.productId;

            self.xjobid = "";                            // Used for calling preview            

            // self.container = $('#xmpieFields');       // div contains all fields
            self.bindEvents();
        },

        bindEvents: function () {
            var self = this;
            $(window).on('load', $.proxy(self.getControls, self));
            self.submitButton.on('click', $.proxy(self.preview, self));
        },

        getControls: function (e) {
            var self = this;
            e.preventDefault();

            $.ajax({
                url: self.xmpieHandler,      // 'Handler/XmpieProviderHandler.ashx',
                type: "POST",
                //contentType: "application/json; charset=utf-8",
                datatype: "jsonp",
                data: {
                    pun: self.productId,   // 'e555bd81-bc5a-421e-84db-78ed386f97d8'     //ProductID 5156
                    action: "provider"
                },
                beforeSend: function (xhr) {
                    //loader.css('display', 'inline-block');
                }
            })
                .success(function (data, textStatus, jqXHR) {
                    Xmpie.displayControls(data, self);
                    jQuery('#pdf-xmpie-inputs').trigger({
                        type: 'xmpie_fields_loaded'
                    });
                    try {
                        setTimeout(function () { __process_pdf_form_completed(); }, 500);
                    } catch (error) {
                    }
                })
                .fail(function (response) {
                    console.log("Failed: " + response);
                });


        },
        displayControls: function (data, self) {
            // Display Step Tabs if more than 1
            self.displayTabs(data.Steps, self);            
            var sActive = "";
            var uploads = [];
            var pickers = [];
            var $tabContent = self.displayTabsContent(data);
            for (var i = 0; i < data.Steps.length; i++) {
                var step = data.Steps[i];
                if (i == 0) { sActive = " active" } else { sActive = "" }
                var panel = $("<div></div>").attr("id", "tab_" + step.Name.replace(" ", "_"))
                                            .attr("role", "tabpanel")
                                            .addClass("tab-pane fade in" + sActive)                
                for (var j = 0; j < step.Dials.length; j++) {                    
                    var dial = step.Dials[j];
                    if (!step.Template) { if (dial.UIControlTypeID != 13 && dial.UIControlTypeID != 24) { panel.append(self.displayLabel(dial.FriendlyName)); } }
                    switch (dial.UIControlTypeID) {
                        case 1:                                 // Multiline
                            var tmp = self.displayMultiline(dial, self);
                            if (!step.Template) {  panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                            break;
                        case 2:                                 //Radio Button List
                            var tmp = self.displayRadioButtonList(dial, self, dial.ValueOptionsXML);
                            if (!step.Template) {  panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                            break;
                        case 3:                                 // Textbox
                            var tmp = self.displayTextbox(dial, self);
                            if (!step.Template) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                            break;
                        case 4:                                 // Select
                        case 5:                                 // Popup
                        case 21:                                // Image Dropdown
                        case 14:                                // Upload
                            uploads.push(dial);                 // save dials with upload for late binding
                            var tmp1 = self.displayAssetUpload(dial);
                            var tmp2 = self.displayAssetImage(dial);
                            var upload_btn_html = '<a href="#" class="btn btn-success" id="fileuploader_'+ dial.StepID +'_'+ dial.DialID + '_trigger">Upload File</a></br>';
                            if (!step.Template ) {
                                panel.append(tmp1);
                                panel.append(upload_btn_html);
                                panel.append(tmp2);
                            }
                            else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp1[0].outerHTML + upload_btn_html + tmp2[0].outerHTML);
                            }                            
                            break;
                        case 6:                                 // Dropdown
                            var tmp = self.displayDropdown(dial, self, dial.ValueOptionsXML);
                            if (!step.Template ) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                            break;
                        //case 13, 24:                          //Hidden Field
                        case 16:                                // Textbox with Attributes        
                            pickers.push(dial);                 // save dials with pickers for late binding
                            var tmp = self.displayTextboxWithAttr(dial, data.AllFonts, self);
                            if (!step.Template ) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }                            
                            break;
                        case 17:
                            pickers.push(dial);
                            var tmp = self.displayStyleEditor(dial, data.AllFonts, self);
                            if (!step.Template) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            } 
                            break;
                        case 22:                                // Dropdown from File
                            var tmp = self.displayDropdown(dial, self, dial.ListFileLocation);
                            if (!step.Template ) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                            break;
                        case 23:                                // epower Service
                            var inputID = "_inputQty";
                            var inputType = "textbox"
                            if (data.QtyDisplayMethod != "textbox") { inputID = "_dropQty"; inputType = "dropdown"; }
                            var tmp = self.displayService(dial, self, dial.ValueOptionsXML, inputID, inputType);
                            if (!step.Template ) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                            break;
                        default:
                            var tmp = self.displayTextbox(dial, self);
                            if (!step.Template ) { panel.append(tmp); } else {
                                step.Template = step.Template.replace("{" + dial.uProduceDialName + "}", tmp[0].outerHTML);
                            }
                    }                    
                }

                if (step.Template) { panel.append(step.Template) };

                $tabContent.append(panel);
            }
            self.$elem.append($tabContent);
            // activate uploads if exist
            for (var k = 0; k < uploads.length; k++) {
                self.assignUpload(uploads[k], self);
            }            
            // activate color pickers
            for (var k = 0; k < pickers.length; k++)
            {
                self.assignPicker(pickers[k], self);
            }
            // display Quantity Panel
            self.displayQtyPanel(data, self);
        },

        displayQtyPanel: function (data, self) {
            var prices = [];
            for (var i = 0; i < data.Prices.length; i++) {
                prices.push(
                    {
                        "Break": data.Prices[i].PriceBreak,
                        "Price": data.Prices[i].Price
                    });
            }
            if (data.QtyDisplayMethod == "textbox") {
                if (data.Prices.length > 0 && data.Prices[0].hasOwnProperty('PriceBreak')) {
                    self.qtyTag.append($("<input></input>").val(data.Prices[0].PriceBreak)
                                                           .attr("id", "_inputQty")
                                                           .attr("type", "number")
                                                           .attr("min", "1")
                                                           .attr("pattern", "\d+")
                                                           .attr("step", "1")
                                                           .attr("data-setupprice", data.SetupPrice)
                                                           .attr("data-prices", JSON.stringify(prices))
                                                           .attr("onchange", "updatePrice('_inputQty', 'textbox', '" + self.priceTag + "')"));
                    updatePrice("_inputQty", "textbox", self.priceTag);
                }
            } else {
                //Dropdown
                var dp = $("<select></select>").attr("id", "_dropQty").attr("onchange", "updatePrice('_dropQty', 'dropdown', '" + self.priceTag + "')");
                for (var i = 0; i < data.Prices.length; i++) {
                    dp.append($("<option></option>").attr("value", data.Prices[i].PriceBreak)
                                                    .attr("data-setupprice", data.SetupPrice)
                                                    .attr("data-price", data.Prices[i].Price)
                                                    .text(data.Prices[i].PriceBreak));
                }
                self.qtyTag.append(dp);
                self.priceTag.text(data.Prices[0].PriceBreak * data.Prices[0].Price);
                updatePrice('_dropQty', "dropdown", self.priceTag);
            }
        },



        sleep: function (milliseconds) {
            var start = new Date().getTime();
            for (var i = 0; i < 1e7; i++) {
                if ((new Date().getTime() - start) > milliseconds) {
                    break;
                }
            }
        },

        displayTabs: function (steps, self) {
            // add tabs if there are more than 1 step
            if (steps.length > 1) {                
                var tabDiv = $("<div></div>").attr("role", "tabpanel").addClass("ep-field-tab-container");
                var tabUl = $("<ul></ul>").attr("role", "tablist").addClass("nav nav-tabs ep-field-tab");
                var sActive = "";
                for (var i = 0 ; i < steps.length; i++) {
                    if (i == 0) { sActive = " active" } else { sActive = "" }
                    tabUl.append($("<li></li>").addClass("ep-page-tab" + sActive)
                                               .append($("<a></a>").attr("href", "#tab_" + steps[i].Name.replace(" ", "_"))
                                                                   .attr("data-toggle", "tab")
                                                                   .attr("aria-controls", "#tab_" + steps[i].Name.replace(" ", "_"))
                                                                   .text(steps[i].Name)));
                }
                self.$elem.append(tabDiv.append(tabUl));
            }
        },

        displayTabsContent: function (data) {
            var $tabContent = $("<div></div>").addClass("tab-content ep-field-tab-content");
            if (data.TabPageWidth > 0) { $tabContent.css("width", data.TabPageWidth); }
            if (data.TabPageHeight > 0) { $tabContent.css("min-height", data.TabPageHeight); }
            return $tabContent;
        },

        openTab: function (title, self) {
            
        },

        closeTab: function (title, self) {
            
        },

        displayLabel: function (title) {
            return ("<div>" + title + "</div>");
        },
        
        displayTextbox: function (dial, self) {
            var _input = $("<input />").addClass("form-control ep-xmpie-field")
                                  .attr("value", dial.DefaultValue)
                                  .attr("data-stepid", dial.StepID)
                                  .attr("data-dialid", dial.DialID)
                                  .attr("data-fieldtype", "textbox")
                                  .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                  .css("width", self.getControlWidth(dial.ControlWidth));
            if (dial.UIControlTypeID == 13 || dial.UIControlTypeID == 24) {
                _input.css("display", "none");
            }
            return _input;
        },

        displayMultiline: function (dial, self) {
            var controlHeight = "60px";
            if (dial.ControlHeight > 0) { controlHeight = self.getControlHeight(dial.ControlHeight); }
            return ($("<textarea></textarea").addClass("form-control ep-xmpie-field")
                                             .attr("data-stepid", dial.StepID)
                                             .attr("data-dialid", dial.DialID)
                                             .attr("data-fieldtype", "multiline")
                                             .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                             .css("width", self.getControlWidth(dial.ControlWidth))
                                             .css("height", controlHeight)
                                             .val(dial.DefaultValue));
        },
        
        displayRadioButtonList: function (dial, self, values) {
            var options = jQuery.parseJSON(values);
            var _radioForm = $("<form></form").addClass("ep-xmpie-field")
                                             .attr("data-stepid", dial.StepID)
                                             .attr("data-dialid", dial.DialID)
                                             .attr("data-fieldtype", "radio")
                                             .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                             .css("width", self.getControlWidth(dial.ControlWidth))
                                             .css("height", self.getControlHeight(dial.ControlHeight))
                                             .val(dial.DefaultValue);
            if (typeof options == 'object' && options != null && options.xml.add.length > 0) {
                $.each(options.xml.add, function (index, value) {
                    var _option = $("<input>").attr("value", value["@value"]).attr("type", "radio").attr("id", value["@key"]).attr("name", dial.uProduceDialName);
                    var _label = $("<label>"+value["@key"]+"</label>").attr("for",value["@key"]);
                    if (value["@key"] == dial.DefaultValue) { _option.attr("checked", true) }
                    _radioForm.append(_option);
                    _radioForm.append(_label);
                    _radioForm.append($("</br>"));
                });
            }
            return _radioForm;
        },

        displayDropdown: function (dial, self, values) {
            var drpData = jQuery.parseJSON(values);
            var _select = $("<select></select>").addClass("form-control ep-xmpie-field")
                                                .attr("data-stepid", dial.StepID)
                                                .attr("data-dialid", dial.DialID)
                                                .attr("data-fieldtype", "dropdown")
                                                .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                                .css("width", self.getControlWidth(dial.ControlWidth));
            if (typeof drpData == 'object' && drpData != null && drpData.xml.add.length > 0) {
                $.each(drpData.xml.add, function (index, value) {
                    var _option = $("<option></option>").attr("value", value["@value"]).text(value["@key"]);
                    if (value["@key"] == dial.DefaultValue) { _option.attr("selected", "true") }
                    _select.append(_option);
                })
            }
            return _select;
        },

        displayService: function (dial, self, values, inputID, inputType) {                         // Service Dropdown
            var drpData = jQuery.parseJSON(values);
            var _select = $("<select></select>").addClass("form-control ep-xmpie-field ep-xmpie-service")
                                                .attr("data-stepid", dial.StepID)
                                                .attr("data-dialid", dial.DialID)
                                                .attr("data-fieldtype", "dropdown")
                                                .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                                .css("width", self.getControlWidth(dial.ControlWidth))
                                                .attr("onchange", "updatePrice('" + inputID + "','" + inputType + "','" + self.priceTag + "')");
            if (drpData.xml.add.length > 0) {
                $.each(drpData.xml.add, function (index, value) {
                    var _option = $("<option></option>").attr("value", value["@value"]).text(value["@key"]).attr("data-servicedetaildid", value["@servicedetaildid"]).attr("data-prices", value["@prices"]);
                    if (value["@key"] == dial.DefaultValue) { _option.attr("selected", "true") }
                    _select.append(_option);
                })
            }
            return _select;
        },

        displayAssetUpload: function (dial) {
            return $("<input type='file'>").attr("id", "fileuploader_" + dial.StepID + "_" + dial.DialID).css('display', 'none')
                                   .html("<i class='fa fa-upload'></i> Upload File");            
        },

        displayAssetImage: function (dial)
        {
            return $("<img/>").attr("data-stepid", dial.StepID)
                              .attr("data-dialid", dial.DialID)
                              .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                              .attr("data-fieldtype", "image")
                              .attr("data-filename", "")
                              .addClass("ep-xmpie-field")
                              .css("display", "none");
        },

        displayTextboxWithAttr: function (dial, allFonts, self) {
            var jsonAttr = JSON.parse(dial.AttributeXML);
            // Main Div Wrapper
            var $main = $("<div></div>").addClass("ep-xmpie-field")
                                        .attr("data-stepid", dial.StepID)
                                        .attr("data-dialid", dial.DialID)
                                        .attr("data-fieldtype", "textboxattr")
                                        .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                        .css("width", self.getControlWidth(dial.ControlWidth));


            // Font Names Dropdown
            var $select = $("<select></select>").attr("id", "AttrFont_" + dial.StepID + "_" + dial.DialID)
                                                .addClass("form-control")
                                                .css({ width: "60%", display: "inline" });
            for (i = 0; i < allFonts.length; i++) {
                $option = $("<option></option>").attr("value", allFonts[i]).text(allFonts[i].replace("|", " "));
                //                                   0 - FontFamily                        1 - FontStyle
                if (allFonts[i] == jsonAttr.xml.add[0]["@value"] + "|" + jsonAttr.xml.add[1]["@value"]) {
                    $option.attr("selected", "true");
                }
                $select.append($option);
            }
            $main.append($select);

            // Font Sizes Dropdown - 2 Font Sizes
            var _sizes = jsonAttr.xml.add[2]["@value"];
            if (_sizes == "") { _sizes = "12"; }
            var arrSizes = _sizes.split(",");

            var $selectSize = $("<select></select>").attr("id", "AttrSize_" + dial.StepID + "_" + dial.DialID)
                                                    .addClass("form-control")
                                                    .css({ width: "25%", display: "inline" });;
            for (i = 0; i < arrSizes.length; i++) {
                $optionSize = $("<option></option>").attr("value", arrSizes[i]).text(arrSizes[i]);
                $selectSize.append($optionSize);
            }
            $main.append($selectSize);

            // Color 
            var arrDefColor = jsonAttr.xml.add[5]["@value"].split(',');
            var _rgbColor = RGB2Color(arrDefColor[0], arrDefColor[1], arrDefColor[2]);

            var $colorPicker = $("<input></input>").attr("id", "ColorPicker_" + dial.StepID + "_" + dial.DialID)
                                                   .attr("type", "text")
                                                   .addClass("form-control")
                                                   .css({
                                                       width: "15%",
                                                       cursor: "pointer",
                                                       display: "inline",
                                                       color: "#" + _rgbColor,
                                                       "background-color": "#" + _rgbColor
                                                   })
                                                   .val(_rgbColor)
                                                   .attr("data-rgb", [arrDefColor[0], arrDefColor[1], arrDefColor[2]])
                                                   .attr("data-cmyk", rgb2cmyk(arrDefColor[0], arrDefColor[1], arrDefColor[2]));
            $main.append($colorPicker);

            // Input Textbox
            $main.append($("<input />").attr("id", "AttrText_" + dial.StepID + "_" + dial.DialID)
                                       .addClass("form-control")
                                       .val(dial.DefaultValue));

            //
            $main.append($("<div></div>").addClass("clearfix"));
            return $main;

        },
        
        displayStyleEditor: function (dial, allFonts, self) {
            var jsonAttr = JSON.parse(dial.AttributeXML);
            // Main Div Wrapper
            var $main = $("<div></div>").addClass("ep-xmpie-field")
                                        .attr("data-stepid", dial.StepID)
                                        .attr("data-dialid", dial.DialID)
                                        .attr("data-fieldtype", "styledisplayattr")
                                        .attr("id", "Input_" + dial.StepID + "_" + dial.DialID)
                                        .css("width", self.getControlWidth(dial.ControlWidth));

            //Set Default values
            var selectedFont = "";
            var selectedFontSize = 12;
            var fontStyle = "";
            var allFontSizes = new Array();
            var coloursArray = new Array();
            for (var i = 0; i < jsonAttr.xml.add.length; i++) {
                var currentAttr = jsonAttr.xml.add[i];
                var currentKey = currentAttr['@key'];
                var currentValue = currentAttr['@value'];
                if (currentKey == 'FontFamily') { selectedFont = currentValue; }
                if (currentKey == 'FontStyle') { fontStyle = currentValue; }
                if (currentKey == 'FontSize') { selectedFontSize = currentValue; }
                if (currentKey == 'FontSizes') { allFontSizes = currentValue.split(","); }
                if (currentKey == 'Colour') { coloursArray = currentValue.split(","); }
            }
            
            // Font Names Dropdown
            var $select = $("<select></select>").attr("id", "AttrFont_" + dial.StepID + "_" + dial.DialID)
                                                .addClass("form-control")
                                                .css({ width: "60%", display: "inline" });
            for (i = 0; i < allFonts.length; i++) {
                var fontName = 
                $option = $("<option></option>").attr("value", allFonts[i]).text(allFonts[i].replace("|", " "));
                
                if (allFonts[i] == selectedFont + "|" + fontStyle) {
                    $option.attr("selected", "true");
                }
                $select.append($option);
            }
            $main.append($select);

            // Font Sizes Dropdown - 2 Font Sizes
            if (allFontSizes.length == 0) { allFontSizes.push(12); }
            
            var $selectSize = $("<select></select>").attr("id", "AttrSize_" + dial.StepID + "_" + dial.DialID)
                                                    .addClass("form-control")
                                                    .css({ width: "25%", display: "inline" });;
            for (i = 0; i < allFontSizes.length; i++) {
                $optionSize = $("<option></option>").attr("value", allFontSizes[i]).text(allFontSizes[i]);
                if (selectedFontSize == allFontSizes[i]){
                    $optionSize.attr("selected", true);
                }
                $selectSize.append($optionSize);
            }
            $main.append($selectSize);

            // Color 
            var _rgbColor = RGB2Color(coloursArray[0], coloursArray[1], coloursArray[2]);

            var $colorPicker = $("<input></input>").attr("id", "ColorPicker_" + dial.StepID + "_" + dial.DialID)
                                                   .attr("type", "text")
                                                   .addClass("form-control")
                                                   .css({
                                                       width: "15%",
                                                       cursor: "pointer",
                                                       display: "inline",
                                                       color: "#" + _rgbColor,
                                                       "background-color": "#" + _rgbColor
                                                   })
                                                   .val(_rgbColor)
                                                   .attr("data-rgb", [coloursArray[0], coloursArray[1], coloursArray[2]])
                                                   .attr("data-cmyk", rgb2cmyk(coloursArray[0], coloursArray[1], coloursArray[2]))
                                                   .attr("value", '#' + _rgbColor);
            $main.append($colorPicker);
            
            $main.append($("<div></div>").addClass("clearfix"));
            return $main;
        },

        assignUpload: function (dial, self) {
            jQuery("#fileuploader_" + dial.StepID + "_" + dial.DialID).fileupload({
                url: self.xmpieHandler,                                            //
                method: "POST",
                datatype: "jsonp",
                autoUpload: true,
                sequentialUploads: true,
                multipart: true,  // Enable adding additional data
                formData:  {
                        action: "upload",
                        sourceid: dial.AssetUploadSourceID,                        // uProduce assetSourceID
                        dialid: dial.DialID,
                        stepid: dial.StepID
                },
                done: function (files, data, xhr) {
                    var file = data.fileInput[0].files[0];
                    data.result.Url = data.result.Url.replace('http://http://', 'http://');
                    self.autoUploadImageForXmpie(dial.DialID, dial.StepID, data.result.FileNameWithoutExt + "", data.result.Url, data.result.AssetID, dial.thumbnails,file);
                    jQuery('#fileuploader_'+ dial.StepID +'_'+ dial.DialID + '_trigger').html('Replace Image');
                },
                fail: function (files, status, errMsg) {
                    jQuery("#status").html("<font color='red'>Upload has Failed</font>");
                }
            });
            jQuery('#fileuploader_'+ dial.StepID +'_'+ dial.DialID + '_trigger').on('click', function(){
                jQuery('#fileuploader_'+ dial.StepID +'_'+ dial.DialID).trigger('click');
                return false;
            });
        },

        assignPicker: function(dial, self)
        {
            var palette = false;
            if (use_colour_palette) {
                palette = ['#C00000','#FF0000','#FFC000','#FFFF00','#92D050','#00B050','#00B0F0','#0070C0','#002060','#7030A0','#000000'];
            }
            var colour_picker = $("#ColorPicker_" + dial.StepID + "_" + dial.DialID);
            colour_picker.wpColorPicker({
                color: true,
                palettes: palette,
                hide: true,
                mode: 'hsl',
                width: 200,
                change: function (event, ui) {
                    colour_picker.val('');
                    colour_picker.css({
                        color : ui.color.toString(),
                        background: ui.color.toString()
                    });
                    var rgbArray = hexToRgb(ui.color.toString());
                    var cmykArray = rgb2cmyk(rgbArray[0],rgbArray[1],rgbArray[2]);
                    colour_picker.data('rgb', rgbArray);
                    colour_picker.data('cmyk', cmykArray);
                }
            });
            var parent_container = colour_picker.parents('.wp-picker-container');
            var open_button = parent_container.find('.wp-color-result');
            open_button.on('click',function(){
                colour_picker.hide();
                parent_container.find('.wp-picker-clear').hide();
            });
            if (use_colour_palette) {
                open_button.on('click',function(){
                    parent_container.find('.iris-picker-inner').hide();
                    parent_container.find('.iris-picker.iris-border').height(55);
                    jQuery('#pdf-xmpie-product-ui .iris-picker .iris-palette').css({
                        'margin-left': '0!important',
                        'margin': '1px!important',
                        'width': '20px',
                        'height': '20px'
                    });
                });
            }
        },
        getControlWidth: function (width) {
            if (width > 0) { return width + "px"; } else { return "100%"; }
        },
        getControlHeight: function (height) {
            if (height > 0) { return height + "px"; } else { return "100%"; }
        },

        autoUploadImageForXmpie: function (dialid, stepid, fileName, thsrc, assetID, useThumbnails) {
            $("#Input_" + stepid + "_" + dialid).attr("src", thsrc)
                                               .attr("data-filename", fileName)
                                               .attr("data-assetId", assetID)
                                               .attr("title", fileName)
                                               .attr("style", "display:block;");;
            //$("#Delete_" + stepid + "_" + dialid).attr("style", "display:inline;");
            //$("#ImageLink_" + stepid + "_" + dialid).attr("data-file", fileName);
        },

        // Preview Related
        getEntries: function (callback) {
            // Returns Save Object Entries
            var entries = [];
            //self.$elem.find($(".ep-xmpie-field")).each(function (i, obj) {
            $(".ep-xmpie-field").each(function (i, obj) {
                var $this = $(this);
                var sVal = "";
                switch ($this.attr("data-fieldtype")) {
                    case "image":
                        sVal = $this.attr("data-assetid");        // data("assetid") does not work as it changes
                        if (sVal == undefined | sVal == "") {
                            sVal = $this.attr("data-filename");
                        }
                        else {
                            sVal += "::::" + $this.attr("data-filename");
                        }
                        break;
                    case "textboxattr":
                        var _text = $this.find('input:first').val();
                        var _fName = $this.find('select:first').val();
                        var _fSize = $this.find('select:last').val();
                        var _fColor = $this.find('input[data-cmyk]').data("cmyk");
                        sVal = _text + "$$$$|" + _fName + "|" + _fSize + "|[" + _fColor + "]";
                        console.log(sVal);
                        break;
                    case "styledisplayattr":
                        var _fName = $this.find('select:first').val();
                        var _fSize = $this.find('select:last').val();
                        var _fColor = $this.find('input[data-rgb]').data("rgb");
                        sVal = "|" + _fName + "|" + _fSize + "|" + _fColor;
                        console.log(sVal);
                        break;
                    case "radio":
                        sVal = $('input:checked', this).val();
                        break;
                    default:    //textbox, dropdown ..
                        sVal = $this.val();
                        break;
                }
                entries.push({ DialID: $(this).data("dialid"), StepID: $(this).data("stepid"), Value: sVal });
            })
            entries.push({ DialID: 0, StepID: 0, Value: "" });
            if (typeof callback === 'function') {
                callback(entries);
            }
        },

        getImages: function (src, count) {
            //var s = "<div class='touchcarousel minimal-light' id='carousel-image-and-text' style='overflow: visible;' >";
            var s = "<div class='touchcarousel-wrapper'>";
            s += "<ul class='touchcarousel-container'>";
            for (i = 1; i <= count; i++) {
                s += "<li class='touchcarousel-item'>";
                s += "<img class='main_image' width='720' height='720' src='" + src + "_" + i + ".png' alt='' />";
                s += "</li>";
            }
            s += "</ul>";  // touchcarousel-container

            s += "<a class='arrow-holder left disabled' href='#'><span class='arrow-icon left'></span></a>";
            s += "<a class='arrow-holder right' href='#'><span class='arrow-icon right'></span></a>";
            s += "<div class='scrollbar-holder'><div class='scrollbar dark' style='left: 0px; width: 545px; opacity: 0;'></div></div>";
            s += "</div>"; // Wrapper
            //s += "</div>"; // sti_slider
            return s;
        },
        preview: function (event) {
            var self = this;
            if (typeof event != 'undefined') {
                event.preventDefault();
            }
            self.getEntries(function(json_entries){
                var entries = JSON.stringify(json_entries);
                $.ajax({
                    url: self.xmpieHandler,              // 'Handler/XmpiePreviewHandler.ashx',
                    type: "POST",
                    datatype: "jsonp",
                    data: {
                        pun: self.productId,
                        action: "preview",
                        xjobid: self.xjobid,
                        size: -1,
                        entries: entries
                    },
                    beforeSend: function (xhr) {
                        self.loader.css('display', 'inline-block');
                    }
                }).success(function (data, textStatus, jqXHR) {
                    if (typeof data == 'string') {
                        alert('There was an error processing your request');
                        return;
                    }
                    if (data.status == "wait") {
                        //PreviewOutput(data.xjobid, pid, cuid, wid, widOrg, isProof, isModal, jsonEntry)
                        // Sleep for few seconds
                        self.sleep(3000);
                        self.xjobid = data.xjobid;
                        self.preview();
                    }
                    if (data.status == "success") {
                        self.loader.css('display', 'none');

                        if (data.count > 0) {

                            self.xjobid = "";     // Reset Job when done
                            data.src = data.src.replace('http://','https://');
                            __process_xmpie_preview_success(data);
                        }
                        return "";
                    }                       
                }).fail(function (response) {
                    console.log("Failed: " + response.statusText);
                }).done(function (data) {
                    if (console && console.log) {

                    }
                });
            });
        }
    };

    $.fn.xmpieProduct = function (options) {
        return this.each(function () {
            var xmpie = Object.create(Xmpie);
            xmpie.init(options, this);
            $.data(this, 'xmpieProduct', xmpie);
        });
    };

    $.fn.xmpieProduct.options = {
        productId: 'e555bd81-bc5a-421e-84db-78ed386f97d8',
        slider: $("#divImageContainer"),                                // div for Output
        loader: $("#divImageLoading"),                                  // div for Loader image
        priceTag: $("#spanPrice"),                                      // Price Display Tag
        qtyTag: $("#qtyPanel"),                                         // Quantity Panel Tag
        submitButton: $("#btnPrev"),                                    // Submit button selector
        xmpieHandler: "/CS_Handlers/Remote/XmpieRemoteHandler.ashx"
    };


    //Xmpie.init();

})(jQuery, window, document);

function hexToRgb(hexStr){
    // note: hexStr should be #rrggbb
    var hex = parseInt(hexStr.substring(1), 16);
    var r = (hex & 0xff0000) >> 16;
    var g = (hex & 0x00ff00) >> 8;
    var b = hex & 0x0000ff;
    return [r, g, b];
}

function rgbToHex(r, g, b) {
    return componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function RGB2Color(r, g, b) {
    return this.byte2Hex(r) + this.byte2Hex(g) + this.byte2Hex(b);
}
function byte2Hex(n) {
    var nybHexString = "0123456789ABCDEF";
    return String(nybHexString.substr((n >> 4) & 0x0F, 1)) + nybHexString.substr(n & 0x0F, 1);
}

function rgb2cmyk(r, g, b) {
    var computedC = 0;
    var computedM = 0;
    var computedY = 0;
    var computedK = 0;

    //remove spaces from input RGB values, convert to int
    var r = parseInt(('' + r).replace(/\s/g, ''), 10);
    var g = parseInt(('' + g).replace(/\s/g, ''), 10);
    var b = parseInt(('' + b).replace(/\s/g, ''), 10);

    if (r == null || g == null || b == null ||
        isNaN(r) || isNaN(g) || isNaN(b)) {
        //alert('Please enter numeric RGB values!');
        return;
    }
    if (r < 0 || g < 0 || b < 0 || r > 255 || g > 255 || b > 255) {
        //alert('RGB values must be in the range 0 to 255.');
        return;
    }

    // BLACK
    if (r == 0 && g == 0 && b == 0) {
        computedK = 1;
        return [0, 0, 0, 1];
    }

    computedC = 1 - (r / 255);
    computedM = 1 - (g / 255);
    computedY = 1 - (b / 255);

    var minCMY = Math.min(computedC,
                 Math.min(computedM, computedY));
    computedC = (computedC - minCMY) / (1 - minCMY);
    computedM = (computedM - minCMY) / (1 - minCMY);
    computedY = (computedY - minCMY) / (1 - minCMY);
    computedK = minCMY;

    return [computedC, computedM, computedY, computedK];
}



function currencyFormat(num) {    
    return "$" + num.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function updatePrice(qtyID, ctrlType, priceTagElement) {
    var totalPrice = 0;
    if (ctrlType == "textbox") {      //textbox
        var ctrl = jQuery("#" + qtyID);
        var qty = parseInt(ctrl.val());
        //var prices = JSON.parse(ctrl.data("prices"));
        var setupprice = ctrl.data("setupprice");
        var prices = ctrl.data("prices");
        var price = 0;
        if (prices != undefined && prices.length > 0) {
            price = prices[0].Price; 
            for (var i = 0; i < prices.length; i++) {
                if (qty < prices[i].Break) {
                    break;
                }
                price = prices[i].Price;
            }
        }
        totalPrice = qty * price + setupprice + getServicePrice(qty);
    } else { //dropdown    
        var ctrl = jQuery("#" + qtyID).find(":selected");
        var qty = ctrl.val();
        var setupprice = ctrl.data("setupprice");
        var price = ctrl.data("price");
        totalPrice = qty * price + setupprice + getServicePrice(qty);
    }
    jQuery(priceTagElement).text(currencyFormat(totalPrice));
}

function getServicePrice(qty)
{
    var servicePrice = 0;
    jQuery(".ep-xmpie-service").each(function (index, obj) {
        
        var $option = jQuery(obj).find(":selected");
        var prices = $option.data("prices");
        var price = 0;
        if (prices != undefined && prices.length > 0) { 
            price = prices[0].price;
            for (var i = 0; i < prices.length; i++) {
                if (qty < prices[i].break) {
                    break;
                }
                price = prices[i].price;
            }
        }
        servicePrice += price * qty;
    });

    return servicePrice;

}