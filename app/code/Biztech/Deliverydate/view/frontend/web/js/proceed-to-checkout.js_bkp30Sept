/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Customer/js/model/authentication-popup',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/alert',
    'mage/translate',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/url',
    'Magento_Checkout/js/model/quote'
], function ($, authenticationPopup, customerData, alert, $t, fullScreenLoader, getUrl, quote) {
    'use strict';

    return function (config, element) {
        $(element).click(function (event) {

            var cart = customerData.get('cart'),
                    customer = customerData.get('customer');

            event.preventDefault();

            if (!customer().firstname && cart().isGuestCheckoutAllowed === false) {
                authenticationPopup.showModal();

                return false;
            }
            var ignoreValidation = false;
            if (window.deliverydateConfig.general.restrictCartAmount == '1') {
                var totals = quote.totals();
                var totalAmount = (totals ? totals : quote)['grand_total'];
                if (totalAmount >= window.deliverydateConfig.general.cartAmount) {
                    ignoreValidation = true;
                }
            } else {
                ignoreValidation = true;
            }

            if (window.deliverydateConfig.general.enabled !== 0 && ignoreValidation == true && (window.deliverydateConfig.general.cartPageEnabled != undefined && window.deliverydateConfig.general.cartPageEnabled == 1)) {
                var enabledMethod = window.deliverydateConfig.templateConfig.enabledMethod;
                if (enabledMethod == 2) {
                    if (!jQuery('.delivery-slot').is(':checked')) {
                        if (window.deliverydateConfig.general.isMandatory === 1) {
                            alert({
                                title: $t('Attention'),
                                content: $t('Please Select A Timeslot'),
                                actions: {
                                    always: function () {
                                        fullScreenLoader.stopLoader();
                                    }
                                }
                            });
                            event.preventDefault();
                            return;
                        }
                    }
                    if (window.deliverydateConfig.general.comment_required == "1" && window.deliverydateConfig.general.add_comment == "1") {
                        var commetData = jQuery("#shipping_arrival_comments").val();
                        if (!commetData && jQuery('.delivery-slot').is(':checked')) {
                            alert({
                                title: $t('Attention'),
                                content: $t('Please add a comment'),
                                actions: {
                                    always: function () {
                                        fullScreenLoader.stopLoader();
                                    }
                                }
                            });
                            event.preventDefault();
                            return;
                        }
                    }
                }
                if (enabledMethod == 1) {
                    var calendarMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
                    var calendarInput = jQuery("input[name=shipping_arrival_date]").val(); // Default selected mode
                    var timeSlotMode = window.deliverydateConfig.calendar.options.timeDisplayMode;
                    var timeSlotInput = true;

                    if (calendarMode == 'dropdown') {
                        calendarInput = jQuery('#calanderdate').val();
                    }
                    if (calendarMode == 'radio') {
                        if (!jQuery('.delivery-date-slot').is(':checked')) {
                            calendarInput = "";
                        }
                    }
                    if (calendarMode == 'button') {
                        if (!jQuery('.calendar_date_button').hasClass("active")) {
                            calendarInput = "";
                        }
                    }

                    if (timeSlotMode == 'button' && !$('.arrival_slot_time_button').hasClass("active")) {
                        timeSlotInput = false;
                    }
                    if (timeSlotMode == 'radio') {
                        if (jQuery('.arrival_slot_time').length > 0) {
                            if (!jQuery('.arrival_slot_time').is(':checked')) {
                                timeSlotInput = false;
                            }
                        }
                    }
                    if (timeSlotMode == 'dropdown') {
                        if (jQuery('#shipping_arrival_slot').val() == "" || jQuery('#shipping_arrival_slot').val() == undefined) {
                            timeSlotInput = false;
                        }
                    }
                    if (calendarInput == "") {
                        if (window.deliverydateConfig.general.isMandatory === 1) {
                            alert({
                                title: $t('Attention'),
                                content: $t('Please Select Delivery Date'),
                                actions: {
                                    always: function () {
                                        fullScreenLoader.stopLoader();
                                    }
                                }
                            });
                            event.preventDefault();
                            return;
                        }
                    }
                    var enableTimeslot = window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar;
                    if (enableTimeslot == 1) {
                        if (timeSlotInput == false && calendarInput != '') {
                            if (window.deliverydateConfig.general.isMandatory === 1) {
                                alert({
                                    title: $t('Attention'),
                                    content: $t('Please Select A Timeslot'),
                                    actions: {
                                        always: function () {
                                            fullScreenLoader.stopLoader();
                                        }
                                    }
                                });
                                event.preventDefault();
                                return;
                            }
                        }
                    }
                    if (window.deliverydateConfig.general.comment_required == "1" && window.deliverydateConfig.general.add_comment == "1") {
                        var commetData = jQuery("#shipping_arrival_comments").val();
                        if (!commetData && calendarInput != '') {
                            alert({
                                title: $t('Attention'),
                                content: $t('Please add a comment'),
                                actions: {
                                    always: function () {
                                        fullScreenLoader.stopLoader();
                                    }
                                }
                            });
                            event.preventDefault();
                            return;
                        }
                    }
                }
                var shipping_arrival_date = $('#shipping_arrival_date').val();
                var shipping_arrival_slot = $('#shipping_arrival_slot').val();
                var delivery_charges = $('#delivery_charges').val();
                var shipping_arrival_comment = $("#shipping_arrival_comments").val();
                var call_me_before = 0;
                if ($('#delivery_date_callme').is(":checked")) {
                    call_me_before = 1;
                } else {
                    call_me_before = 0;
                }

                $.ajax({
                    type: 'POST',
                    url: getUrl.build('deliverydate/index/cartPageSaveData'),
                    data: {shipping_arrival_date: shipping_arrival_date, shipping_arrival_slot: shipping_arrival_slot, shipping_arrival_comment: shipping_arrival_comment, delivery_charges: delivery_charges, call_me_before: call_me_before},
                    dataType: 'json',
                    success: function (data) {
                        if (data['result'] == 'error') {
                            alert({
                                title: $t('Attention'),
                                content: data['msg'],
                                actions: {
                                    always: function () {
                                        fullScreenLoader.stopLoader();
                                    }
                                }
                            });
                            event.preventDefault();
                            return false;
                        }
                    }
                });
            }

            $(element).attr('disabled', true);
            location.href = config.checkoutUrl;
        });

    };
});
