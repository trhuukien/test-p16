define([
    'jquery',
    "mage/translate",
    'mage/mage',
    "domReady!"
], function ($) {
    'use strict';
    $.widget('mage.bsstooltipCustomOption', {
        options: {
            jsonTooltip:[],
            jsonTooltipValue:[],
            jsonTooltipAttribute:[]
        },

        _init: function () {
            if (this.options.jsonTooltip !== '') {
                this._RenderToolTip();
            } else {
                console.log('Bss fail');
            }
        },

        _create: function () {
            var $widget = this;
        },

        _RenderToolTip: function () {
            var jsonTooltip = this.options.jsonTooltip;
            var jsonOptionTypeTooltip = this.options.jsonTooltipValue;
            var jsonTooltipAttribute = this.options.jsonTooltipAttribute;
            var $widget = this;
            $('.product-custom-option').each(function () {
                var optionid = $(this).attr('name').match(/\d+/)[0];
                var optionTypeId = $(this).attr('value');
                var optionTypeTooltip = jsonOptionTypeTooltip[optionTypeId];
                var tooltip_content = jsonTooltip[optionid];
                var checkTypeInput = $(this).closest(".admin__field-option").find('input').attr('type');
                if (tooltip_content && tooltip_content.trim() != '' && checkTypeInput != 'checkbox') {
                    $(this).closest(".field").find('label').after('<span class="bss-tooltip-content"><span class="bss-tooltip-customoption" option-id="'+ optionid + '"><i class="fa fa-question-circle"></i></span></span>');
                }
                if (optionTypeTooltip && optionTypeTooltip.trim() != '' && checkTypeInput == 'checkbox') {
                    $(this).closest(".field").find('label').after('<span class="bss-tooltip-content"><span class="bss-tooltip-customoption" option-type-id="' + optionTypeId +'"><i class="fa fa-question-circle"></i></span></span>');
                }
            });

            $('.super-attribute-select').each(function () {
                var optionAttribute = $(this).attr('name').match(/\d+/)[0];
                var attributeTooltipContent = jsonTooltipAttribute[optionAttribute];
                if (attributeTooltipContent && attributeTooltipContent.trim() != '') {
                    $(this).closest(".field").find('label').after('<span class="bss-tooltip-content"><span class="bss-tooltip-customoption" attribute-cf="'+ optionAttribute +'"><i class="fa fa-question-circle"></i></span></span>');
                }
            });

            $('.bss-tooltip-customoption').hover(function (event) {
                // event.preventDefault();
                $('.bss-custom-tooltip').remove();
                var tooltip_content = '';
                if (typeof $(this).attr('option-id') !== 'undefined' && $(this).attr('option-id') !== false ) { 
                    tooltip_content = jsonTooltip[$(this).attr('option-id')];
                }
                if (typeof $(this).attr('option-type-id') !== 'undefined' && $(this).attr('option-type-id') !== false ) { 
                    tooltip_content = jsonOptionTypeTooltip[$(this).attr('option-type-id')];
                }
                if (typeof $(this).attr('attribute-cf') !== 'undefined' && $(this).attr('attribute-cf') !== false) { 
                    tooltip_content = jsonTooltipAttribute[$(this).attr('attribute-cf')];
                }
                $('body').prepend('<div class="bss-custom-tooltip"><div class="content-tt">'+ tooltip_content +'</div></div>')
                var $target = $(event.target);
                var posX = $(this).offset().left;
                var posY = $(this).offset().top;
                var crrposition =  posY - $(window).scrollTop() - 10;
                var top = posY - $('.bss-custom-tooltip .content-tt').outerHeight();
                var left =  posX;
                var widthwindow =  $(window).width();
                if (left < 0) {
                    left = 0;
                }
                if ((left + $('.bss-custom-tooltip .content-tt').outerWidth() ) > widthwindow) {
                    left =  posX - $('.bss-custom-tooltip .content-tt').outerWidth()
                }
                if(crrposition < $('.bss-custom-tooltip .content-tt').outerHeight()) {
                    top = parseFloat(top) + parseFloat($('.bss-custom-tooltip .content-tt').outerHeight()) - 20;
                }
                $('.bss-custom-tooltip').show().css({'top':top,'left':left});

            }, function (event) {
                var $target = $(event.target);
            });

            $(".page-wrapper").mouseover(function(event){
                var $target = $(event.target);
                setTimeout(function(){
                    if (!$target.closest('.bss-tooltip-content').length) {
                        $('.bss-custom-tooltip').remove();
                    }
                },10)
            });

        }
    });
    return $.mage.bsstooltipCustomOption;
});
