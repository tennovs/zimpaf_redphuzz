/**
 * @name Multi-step form - WIP
 * @description Prototype for basic multi-step form
 * @deps jQuery, jQuery Validate
 */
(function ($) {
    'use strict';
    /**
     * @name Multi-step form - WIP
     * @description Prototype for basic multi-step form
     * @deps jQuery, jQuery Validate
     */

    var app = {

        init: function(){
            this.cacheDOM();
            this.setupAria();
            this.nextButton();
            this.prevButton();
            this.validateForm();
            this.editForm();
            this.killEnterKey();
            this.handleStepClicks();
        },

        cacheDOM: function(){
            this.$formParent = $(".ere-property-multi-step");
            this.$form = this.$formParent.find("form");
            this.$formStepParents = this.$form.find("fieldset");
            this.$nextButton = this.$form.find(".ere-btn-next");
            this.$prevButton = this.$form.find(".ere-btn-prev");
            this.$editButton = this.$form.find(".ere-btn-edit");
            this.$stepsParent = $(".ere-steps");
            this.$steps = this.$stepsParent.find("button");
        },

        htmlClasses: {
            activeClass: "active",
            hiddenClass: "hidden",
            visibleClass: "visible",
            editFormClass: "edit-form",
            animatedVisibleClass: "animated fadeIn",
            animatedHiddenClass: "animated fadeOut",
            animatingClass: "animating"
        },

        setupAria: function(){

            // set first parent to visible
            this.$formStepParents.eq(0).attr("aria-hidden",false);

            // set all other parents to hidden
            this.$formStepParents.not(":first").attr("aria-hidden",true);

            // handle aria-expanded on next/prev buttons
            app.handleAriaExpanded();

        },

        nextButton: function(){

            this.$nextButton.on("click", function(e){

                e.preventDefault();

                // grab current step and next step parent
                var $this = $(this),
                    currentParent = $this.closest("fieldset"),
                    nextParent = currentParent.next();

                // if the form is valid hide current step
                // trigger next step
                if(app.checkForValidForm()){
                    currentParent.removeClass(app.htmlClasses.visibleClass);
                    app.showNextStep(currentParent, nextParent);
                }

            });
        },

        prevButton: function(){

            this.$prevButton.on("click", function(e){

                e.preventDefault();

                // grab current step parent and previous parent
                var $this = $(this),
                    currentParent = $this.closest("fieldset"),
                    prevParent = currentParent.prev();

                // hide current step and show previous step
                // no need to validate form here
                currentParent.removeClass(app.htmlClasses.visibleClass);
                app.showPrevStep(currentParent, prevParent);

            });
        },

        showNextStep: function(currentParent,nextParent){

            // hide previous parent
            currentParent
                .addClass(app.htmlClasses.hiddenClass)
                .attr("aria-hidden",true);

            // show next parent
            nextParent
                .removeClass(app.htmlClasses.hiddenClass)
                .addClass(app.htmlClasses.visibleClass)
                .attr("aria-hidden",false);

            // focus first input on next parent
            nextParent.focus();

            // activate appropriate step
            app.handleState(nextParent.index());

            // handle aria-expanded on next/prev buttons
            app.handleAriaExpanded();

        },

        showPrevStep: function(currentParent,prevParent){

            // hide previous parent
            currentParent
                .addClass(app.htmlClasses.hiddenClass)
                .attr("aria-hidden",true);

            // show next parent
            prevParent
                .removeClass(app.htmlClasses.hiddenClass)
                .addClass(app.htmlClasses.visibleClass)
                .attr("aria-hidden",false);

            // send focus to first input on next parent
            prevParent.focus();

            // activate appropriate step
            app.handleState(prevParent.index());

            // handle aria-expanded on next/prev buttons
            app.handleAriaExpanded();

        },

        handleAriaExpanded: function(){

            /*
             Loop each next/prev button
             Check to see if the parent it controls is visible
             Handle aria-expanded on buttons
             */
            $.each(this.$nextButton, function(idx,item){
                var controls = $(item).attr("aria-controls");
                if($("#"+controls).attr("aria-hidden") == "true"){
                    $(item).attr("aria-expanded",false);
                }else{
                    $(item).attr("aria-expanded",true);
                }
            });

            $.each(this.$prevButton, function(idx,item){
                var controls = $(item).attr("aria-controls");
                if($("#"+controls).attr("aria-hidden") == "true"){
                    $(item).attr("aria-expanded",false);
                }else{
                    $(item).attr("aria-expanded",true);
                }
            });

        },
        checkFieldRequired: function (field_required) {
            return (field_required == 1);
        },
        validateForm: function(){
            // jquery validate form validation
            var property_title = ere_property_steps_vars.property_title,
                property_price_short = ere_property_steps_vars.property_price,
                property_type = ere_property_steps_vars.property_type,
                property_status = ere_property_steps_vars.property_status,
                property_label = ere_property_steps_vars.property_label,
                property_price_prefix = ere_property_steps_vars.property_price_prefix,
                property_price_postfix = ere_property_steps_vars.property_price_postfix,
                property_rooms = ere_property_steps_vars.property_rooms,
                property_bedrooms = ere_property_steps_vars.property_bedrooms,
                property_bathrooms = ere_property_steps_vars.property_bathrooms,
                property_size = ere_property_steps_vars.property_size,
                property_land = ere_property_steps_vars.property_land,
                property_garage = ere_property_steps_vars.property_garage,
                property_year = ere_property_steps_vars.property_year,
                property_address = ere_property_steps_vars.property_address,
                property_country = ere_property_steps_vars.property_country,
                state = ere_property_steps_vars.state,
                city = ere_property_steps_vars.city,
                neighborhood = ere_property_steps_vars.neighborhood,
                postal_code = ere_property_steps_vars.postal_code;

            this.$form.validate({
                ignore: ":hidden", // any children of hidden desc are ignored
                errorElement: "div", // wrap error elements in span not label
                invalidHandler: function(event, validator){ // add aria-invalid to el with error
                    $.each(validator.errorList, function(idx,item){
                        if(idx === 0){
                            $(item.element).focus(); // send focus to first el with error
                        }
                        $(item.element).attr("aria-invalid",true); // add invalid aria
                    })
                },
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        elem.parent().find('.select2-container').addClass(errorClass).removeClass(validClass);
                    } else {
                        elem.addClass(errorClass).removeClass(validClass);
                        //elem.closest('.form-group').addClass(errorClass);
                    }
                },
                unhighlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        elem.parent().find('.select2-container').removeClass(errorClass).addClass(validClass);
                    } else {
                        elem.removeClass(errorClass).addClass(validClass);
                        //elem.closest('.form-group').removeClass(errorClass).addClass(validClass);
                    }
                },
                rules: {
                    property_title: {
                        required: this.checkFieldRequired(property_title)
                    },
                    property_price_short: {
                        required: this.checkFieldRequired(property_price_short),
                       // number: true
                    },
                    'property_type[]': {
                        required: this.checkFieldRequired(property_type)
                    },
                    'property_status[]': {
                        required: this.checkFieldRequired(property_status)
                    },
                    'property_label[]': {
                        required: this.checkFieldRequired(property_label)
                    },
                    property_price_prefix: {
                        required: this.checkFieldRequired(property_price_prefix)
                    },
                    property_price_postfix: {
                        required: this.checkFieldRequired(property_price_postfix)
                    },
                    property_size: {
                        required: this.checkFieldRequired(property_size),
                        number: true
                    },
                    property_land: {
                        required: this.checkFieldRequired(property_land),
                        number: true
                    },
                    property_rooms: {
                        required: this.checkFieldRequired(property_rooms),
                        number: true
                    },
                    property_bedrooms: {
                        required: this.checkFieldRequired(property_bedrooms),
                        number: true
                    },
                    property_bathrooms: {
                        required: this.checkFieldRequired(property_bathrooms),
                        number: true
                    },
                    property_garage: {
                        required: this.checkFieldRequired(property_garage),
                        number: true
                    },
                    property_year: {
                        required: this.checkFieldRequired(property_year),
                        number: true
                    },
                    property_map_address: {
                        required: this.checkFieldRequired(property_address)
                    },
                    property_country : {
                        required: this.checkFieldRequired(property_country)
                    },
                    administrative_area_level_1: {
                        required: this.checkFieldRequired(state)
                    },
                    locality: {
                        required: this.checkFieldRequired(city)
                    },
                    neighborhood: {
                        required: this.checkFieldRequired(neighborhood)
                    },
                    postal_code: {
                        required: this.checkFieldRequired(postal_code)
                    }
                },
                messages: {
                    property_title: "",
                    property_des: "",
                    property_price_short: "",
                    property_rooms: "",
                    property_bedrooms: "",
                    property_bathrooms: "",
                    property_size: "",
                    property_map_address: "",
                    'property_type[]': "",
                    'property_status[]': "",
                    'property_label[]': "",
                    property_price_prefix: "",
                    property_price_postfix: "",
                    property_land: "",
                    property_garage: "",
                    property_year: "",
                    property_country: '',
                    administrative_area_level_1: '',
                    locality : '',
                    neighborhood: '',
                    postal_code : ''
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
            $(document).on('select2:select select2:unselect','#property_type,#property_status,#property_label', function (arg) {
                var elem = $(arg.target);
                if (elem.val() === null) {
                    elem.parent().find('.select2-container').addClass('error');
                } else {
                    elem.parent().find('.select2-container').removeClass('error');
                }
            });

            $('[data-toggle="ere-property-additional-field-required"]').each(function () {
                var name = $(this).attr('data-name');
                if (name === undefined) {
                    name = $(this).attr('name');
                }
                var $element = $('[name="'+ name +'"]');
                if ($element.length > 0) {
                    $element.rules('add',{
                        required: true,
                        messages: {
                            required: '',
                        }
                    });
                }
            });
        },

        checkForValidForm: function(){
            if(this.$form.valid()){
                return true;
            }
        },

        handleState: function(step){

            this.$steps.eq(step).prevAll().removeAttr("disabled");
            this.$steps.eq(step).addClass(app.htmlClasses.activeClass);

            // restart scenario
            if(step === 0){
                this.$steps
                    .removeClass(app.htmlClasses.activeClass)
                    .attr("disabled","disabled");
                this.$steps.eq(0).addClass(app.htmlClasses.activeClass)
            }

        },

        editForm: function(){
            var $formParent = this.$formParent,
                $formStepParents = this.$formStepParents,
                $stepsParent = this.$stepsParent;

            this.$editButton.on("click",function(){
                $formParent.toggleClass(app.htmlClasses.editFormClass);
                $formStepParents.attr("aria-hidden",false);
                $formStepParents.eq(0).find("input").eq(0).focus();
                app.handleAriaExpanded();
            });
        },

        killEnterKey: function(){
            $(document).on("keypress", ":input:not(textarea,button)", function(event) {
                return event.keyCode != 13;
            });
        },

        handleStepClicks: function(){

            var $stepTriggers = this.$steps,
                $stepParents = this.$formStepParents;

            $stepTriggers.on("click", function(e){

                e.preventDefault();

                var btnClickedIndex = $(this).index();

                // kill active state for items after step trigger
                $stepTriggers.nextAll()
                    .removeClass(app.htmlClasses.activeClass)
                    .attr("disabled",true);

                // activate button clicked
                $(this)
                    .addClass(app.htmlClasses.activeClass)
                    .attr("disabled",false);

                // hide all step parents
                $stepParents
                    .removeClass(app.htmlClasses.visibleClass)
                    .addClass(app.htmlClasses.hiddenClass)
                    .attr("aria-hidden",true);

                // show step that matches index of button
                $stepParents.eq(btnClickedIndex)
                    .removeClass(app.htmlClasses.hiddenClass)
                    .addClass(app.htmlClasses.visibleClass)
                    .attr("aria-hidden",false)
                    .focus();

            });
        }
    };
    if ($(".ere-property-multi-step").length > 0) {
        app.init();
    }
})(jQuery);