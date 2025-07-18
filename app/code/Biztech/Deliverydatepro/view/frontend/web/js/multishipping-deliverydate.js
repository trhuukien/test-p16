define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/form/element/abstract',
    'calendar',
    'mageUtils',
    'moment',
    'ko',
    'mousewheel',
    'slick',
    'mage/url',
    'jquery/ui',
    'jquery/jquery-ui-timepicker-addon',
    'mage/translate',
], function ($, alert, Component, calendar, utils, moment, ko, mousewheel, slick, getUrl) {

    jQuery(document).ready(function () {

        //Start: show hide on shipping method on page load
        $(function () {
            $("input:radio[name^=shipping_method]").change();
        });
        //End: show hide on shipping method on page load

        //Start: show hide on shipping method selection
        jQuery(document).on('change', 'input:radio[name^=shipping_method]', function (event) {
            if ($(this).is(':checked')) {
                if (window.deliverydateConfig.general.enableForShipping == 1 && window.deliverydateConfig.general.enableShippingMethods != undefined) {
                    var shippingMethodList = window.deliverydateConfig.general.enableShippingMethods;
                    var selectedShippingMethod = this.value;
                    var addressId = this.id.split(this.value)[0].split('_');
                    addressId = addressId[addressId.length - 2];
                    if (shippingMethodList.indexOf(selectedShippingMethod) > -1) {
                        jQuery(".deliverydate-wrapper_" + addressId).css('display', 'block');
                    } else {
                        jQuery(".deliverydate-wrapper_" + addressId).css('display', 'none');
                    }
                }
            }
        });
        //End: show hide on shipping method selection


        var currencySymbol = window.deliverydateConfig.general.currencySymbol;

        // custom-dropdown open on click for date selection
        $(document).on('click', '.dds_drop_down_date .selected', function (event) {
            var id = this.parentElement.id;
            jQuery("#" + this.parentElement.id + " .options").toggleClass("open_option");
        });

        /* Assign selected Delivery date calendar data from dropdown */
        $(document).on('click', '.shipping_arrival_date_calendar', function (ele) {
            var id = this.parentElement.id;
            var addressId = id.split('_');
            addressId = addressId[addressId.length - 1];
            if (this.name.indexOf(addressId) > -1) {
            } else {
                jQuery(this).attr('name', this.name + '[' + addressId + ']');
            }
        });

        /* Assign selected Delivery date calendar data from dropdown */
        $(document).on('click', '.calanderdate', function (ele) {
            var id = this.parentElement.id;
            var addressId = id.split('_');
            addressId = addressId[addressId.length - 1];
            $('#shipping_arrival_date' + '_' + addressId).val(jQuery(this).attr('value'));
            var text = jQuery(this).html();
            $("#dds_dropdown_selected_" + addressId + " .selected a span").html(text);
            $("#dds_dropdown_selected_" + addressId + " .options").toggleClass("open_option");
//            if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
            var selectedDate = jQuery(this).attr('value');
            calendarTimeslot(selectedDate, addressId);
//            }
        });

        /* Display selected Delivery date calendar data from radio button */
        $(document).on('click', '.delivery-date-slot', function (event) {
            if ($(this).is(':checked'))
            {
                var addressId = jQuery(this).attr('id').split('_');
                addressId = addressId[addressId.length - 1];
                $('#shipping_arrival_date' + '_' + addressId).val(jQuery(this).attr('value'));
//                if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                var selectedDate = $(this).val();
                calendarTimeslot(selectedDate, addressId);
//                }
            }
        });

        /* Delivery date calendar data in swatch type button */
        $(document).on('click', '.calendar_date_button', function (event) {
            var addressId = jQuery(this).attr('id').split('_');
            addressId = addressId[addressId.length - 1];
            $('#shipping_arrival_date_' + addressId).val(jQuery(this).attr('value'));
            $('.calendar_date_button ' + addressId).not(this).removeClass('active');
            $(this).addClass('active');
//            if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
            var selectedDate = jQuery(this).attr('value');
            calendarTimeslot(selectedDate, addressId);
//            }
        });

        // custom-dropdown open on click for timeslot selection
        $(document).on('click', '.dds_drop_down_slot .selected', function (event) {
            var id = this.parentElement.id;
            var addressId = id.split('_');
            addressId = addressId[addressId.length - 1];
            if ($('ul.timeslot_drp_' + addressId + ' li').length >= 1) { //Check condition for no timeslot available
                jQuery("#" + this.parentElement.id + " .options").toggleClass("open_option");
            }
        });

        /* Assign selected timeslot data from dropdown */
        $(document).on('click', '.calandertimeslot', function (ele) {
            var text = $(this).html();
            var slotData = text.split('|');
            var addressId = jQuery(this).attr('id').split('_');
            addressId = addressId[addressId.length - 1];
            $('#shipping_arrival_slot_' + addressId).val(slotData[0]);
            if (slotData[1]) {
                if (slotData[1].indexOf(currencySymbol) > -1) {
                    var deliveryCharge = slotData[1].replace(' ' + currencySymbol, '');
                    $('#delivery_charges_' + addressId).val(deliveryCharge);
                } else {
                    $('#delivery_charges_' + addressId).val(slotData[1]);
                }
            } else {
                jQuery('#delivery_charges_' + addressId).val('');
            }
            $("#slot_dropdown_selected_" + addressId + " .selected a span").html(text);
            $("#slot_dropdown_selected_" + addressId + " .options").toggleClass("open_option");
        });

        /* Assign selected timeslot data from radio */
        $(document).on('click', '.arrival_slot_time', function (event) {
            if ($(this).is(':checked'))
            {
                var addressId = jQuery(this).attr('id').split('_');
                addressId = addressId[addressId.length - 1];
                var slotData = $(this).val().split('|');
                $('#shipping_arrival_slot_' + addressId).val(slotData[0]);
                if (slotData[1]) {
                    if (slotData[1].indexOf(currencySymbol) > -1) {
                        var deliveryCharge = slotData[1].replace(' ' + currencySymbol, '');
                        $('#delivery_charges_' + addressId).val(deliveryCharge);
                    } else {
                        $('#delivery_charges_' + addressId).val(slotData[1]);
                    }
                } else {
                    jQuery('#delivery_charges_' + addressId).val('');
                }
            }
        });


        /* Delivery date calendar data in swatch type button */
        $(document).on('click', '.arrival_slot_time_button', function (event) {
            var addressId = jQuery(this).attr('id').split('_');
            addressId = addressId[addressId.length - 1];
            var slotValue = this.firstChild.innerHTML;
            var slotData = slotValue.split('|');
            $('#shipping_arrival_slot_' + addressId).val(slotData[0]);
            if (slotData[1]) {
                if (slotData[1].indexOf(currencySymbol) > -1) {
                    var deliveryCharge = slotData[1].replace(' ' + currencySymbol, '');
                    $('#delivery_charges_' + addressId).val(deliveryCharge);
                } else {
                    $('#delivery_charges_' + addressId).val(slotData[1]);
                }
            } else {
                jQuery('#delivery_charges_' + addressId).val('');
            }
            $('.arrival_slot_time_button ' + addressId).not(this).removeClass('active');
            $(this).addClass('active');
        });

        $(document).on('click', '.delivery-slot', function (event) {
            var elmId = this.id;
            var date = elmId.split("_");
            var addressId = date[date.length - 1];
            var slotData = jQuery(this).nextAll('label').html();
            slotData = jQuery.parseHTML(slotData);
            var className = slotData[slotData.length - 1].className;

            if (className == 'price') {
                var price = slotData[slotData.length - 1].innerHTML
                if (price.indexOf(currencySymbol) > -1) {
                    var deliveryCharge = price.replace(currencySymbol, '');
                    $('#delivery_charges_' + addressId).val(deliveryCharge);
                } else {
                    $('#delivery_charges_' + addressId).val(price);
                }
            } else {
                jQuery('#delivery_charges_' + addressId).val('');
            }

            $('#shipping_arrival_date_' + addressId).val(date[0]);
            $('#shipping_arrival_slot_' + addressId).val(this.value);
            if (window.deliverydateConfig.general.per_week_day_charge != undefined && window.deliverydateConfig.general.enable_same_day_charge == 2) {
                var dateValue = new Date(date[0].replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).getDay();
                $("#delivery_charges_day_" + addressId).fadeOut();
                getDayPrice(dateValue, addressId);
            }
            if (window.deliverydateConfig.general.enable_same_day_charge == 1) {
                var selectedDate = new Date(date[0].replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
                today = new Date();
                var visiblesamedayCharges = (selectedDate.getDate() == today.getDate() &&
                        selectedDate.getMonth() == today.getMonth() &&
                        selectedDate.getFullYear() == today.getFullYear());
                if (visiblesamedayCharges && window.deliverydateConfig.general.sameDayCharges && window.deliverydateConfig.general.enable_same_day_charge == 1) {
                    $("#delivery_charges_day_" + addressId + " span").html($.mage.__('Additional delivery charges are ' + currencySymbol + window.deliverydateConfig.general.sameDayCharges));
                    $("#delivery_charges_day_" + addressId).fadeIn();
                } else {
                    $("#delivery_charges_day_" + addressId).fadeOut();
                }
            }
        });

        function getDayPrice(selected_Day, addressId) {
            var perWeekDayCharge = window.deliverydateConfig.general.per_week_day_charge;
            $.each(perWeekDayCharge, function (index, val) {
                if (val['day'] == selected_Day) {
                    $("#delivery_charges_day_" + addressId + " span").html($.mage.__('Additional delivery charges are ' + currencySymbol + val['price']));
                    $("#delivery_charges_day_" + addressId).fadeIn();
                    return false;
                }
            });
        }

        function calendarTimeslot(selected_Date, addressId) {
            if (window.deliverydateConfig.general.per_week_day_charge != undefined && window.deliverydateConfig.general.enable_same_day_charge == 2) {
                var dateValue = new Date(selected_Date.replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).getDay();
                $("#delivery_charges_day_" + addressId).fadeOut();
                getDayPrice(dateValue, addressId);
            }
            if (window.deliverydateConfig.general.enable_same_day_charge == 1) {
                var selectedDate = new Date(selected_Date.replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
                today = new Date();
                var visiblesamedayCharges = (selectedDate.getDate() == today.getDate() &&
                        selectedDate.getMonth() == today.getMonth() &&
                        selectedDate.getFullYear() == today.getFullYear());
                if (visiblesamedayCharges && window.deliverydateConfig.general.sameDayCharges && window.deliverydateConfig.general.enable_same_day_charge == 1) {
                    $("#delivery_charges_day_" + addressId + " span").html($.mage.__('Additional delivery charges are ' + currencySymbol + window.deliverydateConfig.general.sameDayCharges));
                    $("#delivery_charges_day_" + addressId).fadeIn();
                } else {
                    $("#delivery_charges_day_" + addressId).fadeOut();
                }
            }

            if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                $.ajax({
                    type: 'POST',
                    url: getUrl.build('deliverydate/index/calenderslot'),
                    data: {selectedDate: selected_Date},
                    dataType: 'json',
                    success: function (data) {
                        var timeDisplayMode = window.deliverydateConfig.calendar.options.timeDisplayMode;

                        if (data['timeslot'] == 'empty') {
                            if (timeDisplayMode == 'dropdown') {
                                jQuery(".timeslot_drp_" + addressId).empty();
                                jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                jQuery(".datetimeslot_" + addressId).css('display', 'block');
                                jQuery("#slot_dropdown_selected_" + addressId + " .selected a span").html("No Timeslot available");
                            }
                            if (timeDisplayMode == 'radio') {
                                jQuery(".timeslot_radio_" + addressId).empty();
                                jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                $(".timeslot_radio_" + addressId).append("No Timeslot available");
                                if (!$(".timeslot_radio_" + addressId).hasClass("no_slot_available"))
                                    $(".timeslot_radio_" + addressId).addClass("no_slot_available");

                            }
                            if (timeDisplayMode == 'button') {
                                jQuery(".timeslot_button_" + addressId).empty();
                                jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                $(".timeslot_button_" + addressId).append("No Timeslot available");
                                if (!$(".timeslot_button_" + addressId).hasClass("no_slot_available"))
                                    $(".timeslot_button_" + addressId).addClass("no_slot_available");
                            }
                            return true;
                        }

                        var slot = $.map(data['timeslot'], function (el) {
                            if (el['price'] != 0 && el['price'] != null) {
                                return el['start_time'] + " - " + el['end_time'] + ' | ' + el['price'] + " = " + el['disable_value']
                            } else {
                                return el['start_time'] + " - " + el['end_time'] + " = " + el['disable_value']
                            }
                        });
                        var self = this;
                        self.calandertimeslot = slot;

                        if (timeDisplayMode == 'radio') {
                            jQuery(".timeslot_radio_" + addressId).empty();
                            if (!$(".timeslot_radio_" + addressId).hasClass("available_slot_radio"))
                                $(".timeslot_radio_" + addressId).addClass("available_slot_radio");
                            $(".timeslot_radio_" + addressId).removeClass("no_slot_available");
                            jQuery(".timeslot_label_" + addressId).css('display', 'block');
                            var i = 0;
                            $.each(slot, function (index, value) {
                                var slot_value = value.split(" = ");
                                var labelname = 'timeSlot_' + i;
                                if (slot_value[1] == "true") {
                                    var create = $('<div class="delivery-date-cell disable_radio"><input type="radio" id="' + labelname + '_' + addressId + '"  class="arrival_slot_time" name="shipping_arrival_slot[' + addressId + ']" value="' + slot_value[0] + '" disabled="' + slot_value[1] + '"><label for="' + labelname + '_' + addressId + '" >' + slot_value[0] + '</label></div>');
                                } else {
                                    var create = $('<div class="delivery-date-cell"><input type="radio" id="' + labelname + '_' + addressId + '" class="arrival_slot_time" name="shipping_arrival_slot[' + addressId + ']" value="' + slot_value[0] + '"><label for="' + labelname + '_' + addressId + '" >' + slot_value[0] + '</label></div>');
                                }
                                $(".timeslot_radio_" + addressId).append(create);
                                i++;
                            });
                        }
                        if (timeDisplayMode == 'button') {
                            jQuery(".timeslot_button_" + addressId).empty();
                            if (!$(".timeslot_button_" + addressId).hasClass("available_slot"))
                                $(".timeslot_button_" + addressId).addClass("available_slot");
                            $(".timeslot_button_" + addressId).removeClass("no_slot_available");
                            jQuery(".timeslot_label_" + addressId).css('display', 'block');
                            var i = 0;
                            $.each(slot, function (index, value) {
                                var slot_value = value.split(" = ");
                                var labelname = 'timeSlot_' + i;
                                if (slot_value[1] == "true") {
                                    var create = $('<div class="delivery-date-cell disable_button"><div class="data-item arrival_slot_time_button button_disable" id="' + labelname + '_' + addressId + '"><label for="' + labelname + '_' + addressId + '">' + slot_value[0] + '</label></div>');
                                } else {
                                    var create = $('<div class="delivery-date-cell"><div class="data-item arrival_slot_time_button ' + addressId + '" id="' + labelname + '_' + addressId + '"  value="' + slot_value[0] + '"><label for="' + labelname + '_' + addressId + '">' + slot_value[0] + '</label></div>');
                                }
                                $(".timeslot_button_" + addressId).append(create);
                                i++;
                            });

                            $('.timeslot_button_' + addressId).removeClass('slick-initialized slick-slider slick-dotted');
                            if (!$(".timeslot_button_" + addressId).hasClass("time_slider"))
                                $(".timeslot_button_" + addressId).addClass("time_slider");
                            jQuery(document).ready(function () {
                                jQuery("#date_time_container").removeClass("date_time_container");
                            });
                            jQuery(".timeslot_button_" + addressId).slick({
                                dots: false,
                                infinite: false,
                                centerMode: false,
                                slidesToShow: 3, slidesToScroll: 1,
                                responsive: [
                                    {
                                        breakpoint: 1100,
                                        settings: {
                                            slidesToShow: 2,
                                            slidesToScroll: 2,
                                            infinite: false,
                                            dots: false
                                        }
                                    },
                                    {
                                        breakpoint: 991,
                                        settings: {
                                            slidesToShow: 1,
                                            slidesToScroll: 1
                                        }
                                    },
                                    {
                                        breakpoint: 600,
                                        settings: {
                                            slidesToShow: 2,
                                            slidesToScroll: 2
                                        }
                                    },
                                    {
                                        breakpoint: 480,
                                        settings: {
                                            slidesToShow: 1,
                                            slidesToScroll: 1
                                        }
                                    }
                                ]
                            });
                        }
                        if (timeDisplayMode == 'dropdown') {
                            jQuery(".timeslot_drp_" + addressId).empty();
                            jQuery(".timeslot_label_" + addressId).css('display', 'block');
                            jQuery(".datetimeslot_" + addressId).css('display', 'block');
                            var i = 0;
                            $.each(slot, function (index, value) {
                                var slot_value = value.split(" = ");
                                var labelname = 'timeSlot_' + i;
                                if (slot_value[1] == "true") {
                                    var create = $('<li class="disable_opt" id="' + labelname + '"><span class="value">' + slot_value[0] + '</span></li>');
                                } else {
                                    var create = $('<li id="' + labelname + '"><span class="value calandertimeslot" id="calandertimeslot_' + addressId + '" value="' + slot_value[0] + '">' + slot_value[0] + '</span></li>');
                                }
                                $(".timeslot_drp_" + addressId).append(create);
                                i++;
                            });
                        }
                    }
                });
            }
        }

        setTimeout(addClassButton, 1000);
        function addClassButton() {
            var calendarMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
            var timeSlotMode = window.deliverydateConfig.calendar.options.timeDisplayMode;
            if (calendarMode == 'button' || timeSlotMode == 'button') {
                if ($(".dds_container").hasClass("date_time_container"))
                    $(".dds_container").removeClass("date_time_container");
                if (!$(".multishipping-dds").hasClass("slider"))
                    $(".multishipping-dds").addClass("slider");
            }
        }

        var dateDisplayMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
        if (dateDisplayMode == 'button') {
            if (!$(".available_date_button").hasClass("date_slider"))
                $(".available_date_button").addClass("date_slider");
            jQuery(document).ready(function () {
                jQuery(".dds_container").removeClass("date_time_container");
            });
            jQuery(".available_date_button").not('.slick-initialized').slick({
                dots: false,
                infinite: false,
                centerMode: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1100,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2, infinite: false,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }

        $(document).on('click', '#dds-submit-validation', function (event) {
            if (window.deliverydateConfig.general.isMandatory === 1) {

                if (window.deliverydateConfig.general.enabledMethod == 2 && deliverydateConfig.general.enableMultishipping) {
                    var timeSlotInput = true;
                    var i = 0;
                    $("input:hidden.shipping_arrival_date_hidden").each(function () {
                        var displayValidation = false;
                        if (window.deliverydateConfig.general.enableForShipping == 1 && window.deliverydateConfig.general.enableShippingMethods != undefined) {
                            var addressId = jQuery(this).attr('name').split('[');
                            addressId = addressId[1].split(']');
                            addressId = addressId[0];
                            var selectedShippingMethod = jQuery("input[type='radio'][name='shipping_method[" + addressId + "]']:checked").val();
                            var shippingMethodList = window.deliverydateConfig.general.enableShippingMethods;

                            if (shippingMethodList.indexOf(selectedShippingMethod) > -1) {
                                displayValidation = true;
                            }
                        }
                        i++;
                        var dateValue = jQuery(this).val();
                        if (!dateValue && displayValidation == true) {
                            timeSlotInput = false;
                            alert({
                                title: $.mage.__('Attention'),
                                content: $.mage.__('Please Select A Timeslot For Address ' + i),
                                actions: {
                                    always: function () {}
                                }
                            });
                            event.preventDefault();
                            return false;
                        }
                    });

                    if (window.deliverydateConfig.general.comment_required == "1" && window.deliverydateConfig.general.add_comment == "1") {
                        var i = 0;
                        $("textarea.delivery-date-comment").each(function () {
                            var displayValidation = false;
                            if (window.deliverydateConfig.general.enableForShipping == 1 && window.deliverydateConfig.general.enableShippingMethods != undefined) {
                                var addressId = jQuery(this).attr('name').split('[');
                                addressId = addressId[1].split(']');
                                addressId = addressId[0];
                                var selectedShippingMethod = jQuery("input[type='radio'][name='shipping_method[" + addressId + "]']:checked").val();
                                var shippingMethodList = window.deliverydateConfig.general.enableShippingMethods;

                                if (shippingMethodList.indexOf(selectedShippingMethod) > -1) {
                                    displayValidation = true;
                                }
                            }
                            i++;
                            var commetData = jQuery(this).val();
                            if (!commetData && timeSlotInput == true && displayValidation == true) {
                                alert({
                                    title: $.mage.__('Attention'),
                                    content: $.mage.__('Please Add A Comment For Address ' + i),
                                    actions: {
                                        always: function () {}
                                    }
                                });
                                event.preventDefault();
                                return false;
                            }
                        });
                    }
                }
                if (window.deliverydateConfig.general.enabledMethod == 1 && deliverydateConfig.general.enableMultishipping) {
                    var calendarMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
                    var timeSlotMode = window.deliverydateConfig.calendar.options.timeDisplayMode;
                    var calendarInput = true;
                    var timeSlotInput = true;

                    var i = 0;
                    $("input:hidden.shipping_arrival_date_hidden").each(function () {
                        var displayValidation = false;
                        if (window.deliverydateConfig.general.enableForShipping == 1 && window.deliverydateConfig.general.enableShippingMethods != undefined) {
                            var addressId = jQuery(this).attr('name').split('[');
                            addressId = addressId[1].split(']');
                            addressId = addressId[0];
                            var selectedShippingMethod = jQuery("input[type='radio'][name='shipping_method[" + addressId + "]']:checked").val();
                            var shippingMethodList = window.deliverydateConfig.general.enableShippingMethods;

                            if (shippingMethodList.indexOf(selectedShippingMethod) > -1) {
                                displayValidation = true;
                            }
                        }
                        i++;
                        var dateValue = jQuery(this).val();
                        if (!dateValue && displayValidation == true) {
                            calendarInput = false;
                            alert({
                                title: $.mage.__('Attention'),
                                content: $.mage.__('Please Select Delivery Date For Address ' + i),
                                actions: {
                                    always: function () {}
                                }
                            });
                            event.preventDefault();
                            return false;
                        }
                    });

                    var j = 0;
                    $("input:hidden.shipping_arrival_slot_hidden").each(function () {
                        var displayValidation = false;
                        if (window.deliverydateConfig.general.enableForShipping == 1 && window.deliverydateConfig.general.enableShippingMethods != undefined) {
                            var addressId = jQuery(this).attr('name').split('[');
                            addressId = addressId[1].split(']');
                            addressId = addressId[0];
                            var selectedShippingMethod = jQuery("input[type='radio'][name='shipping_method[" + addressId + "]']:checked").val();
                            var shippingMethodList = window.deliverydateConfig.general.enableShippingMethods;

                            if (shippingMethodList.indexOf(selectedShippingMethod) > -1) {
                                displayValidation = true;
                            }
                        }
                        j++;
                        var timeValue = jQuery(this).val();
                        if (!timeValue && calendarInput == true && displayValidation == true) {
                            timeSlotInput = false;
                            alert({
                                title: $.mage.__('Attention'),
                                content: $.mage.__('Please Select A Timeslot For Address ' + j),
                                actions: {
                                    always: function () {}
                                }
                            });
                            event.preventDefault();
                            return false;
                        }
                    });

                    if (window.deliverydateConfig.general.comment_required == "1" && window.deliverydateConfig.general.add_comment == "1") {
                        var i = 0;
                        $("textarea.delivery-date-comment").each(function () {
                            var displayValidation = false;
                            if (window.deliverydateConfig.general.enableForShipping == 1 && window.deliverydateConfig.general.enableShippingMethods != undefined) {
                                var addressId = jQuery(this).attr('name').split('[');
                                addressId = addressId[1].split(']');
                                addressId = addressId[0];
                                var selectedShippingMethod = jQuery("input[type='radio'][name='shipping_method[" + addressId + "]']:checked").val();
                                var shippingMethodList = window.deliverydateConfig.general.enableShippingMethods;

                                if (shippingMethodList.indexOf(selectedShippingMethod) > -1) {
                                    displayValidation = true;
                                }
                            }
                            i++;
                            var commetData = jQuery(this).val();
                            if ((!commetData && calendarInput == true && timeSlotInput == true && displayValidation == true) || (!commetData && calendarInput == true && window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == 0 && displayValidation == true)) {
                                alert({
                                    title: $.mage.__('Attention'),
                                    content: $.mage.__('Please Add A Comment For Address ' + i),
                                    actions: {
                                        always: function () {}
                                    }
                                });
                                event.preventDefault();
                                return false;
                            }
                        });
                    }
                }
            }
        });

        $(document).on('click', '.delivery-date-callme', function (event) {
            if (this.checked) {
                if (!jQuery(this).next().hasClass("before_delivery"))
                    jQuery(this).next().addClass('before_delivery');
            } else {
                jQuery(this).next().removeClass('before_delivery');
            }
        });

    });

    return Component.extend({
        defaults: {
            template: 'Biztech_Deliverydatepro/Checkout/multishipping/deliverydate',
            options: {
                buttonImage: window.deliverydateConfig.calendar.options.buttonImage,
                buttonImageOnly: true,
                buttonText: window.deliverydateConfig.calendar.options.buttonText,
                showsTime: window.deliverydateConfig.calendar.options.showsTime,
                showOn: "both",
                //dateFormat: window.deliverydateConfig.general.dateFormat,
                dateFormat: "dd/M/yy",
                timeFormat: window.deliverydateConfig.general.timeFormat,
                pickerTimeFormat: window.deliverydateConfig.general.timeFormat,
                hideIfNoPrevNext: true,
                closeText: $.mage.__('Done'),
                currentText: $.mage.__('Today'),
                isRTL: window.deliverydateConfig.calendar.options.isRTL,
                showAnim: window.deliverydateConfig.calendar.options.showAnim,
                showButtonPanel: window.deliverydateConfig.calendar.options.showButtonPanel,
                changeMonth: false,
                changeYear: false,
                dayNamesShort: [$.mage.__('Sun'), $.mage.__('Mon'), $.mage.__('Tue'), $.mage.__('Wed'), $.mage.__('Thu'), $.mage.__('Fri'), $.mage.__('Sat')],
                dayNamesMin: [$.mage.__('Sun'), $.mage.__('Mon'), $.mage.__('Tue'), $.mage.__('Wed'), $.mage.__('Thu'), $.mage.__('Fri'), $.mage.__('Sat')],
                beforeShowDay: function (date) {
                    var currentdate = jQuery.datepicker.formatDate('dd-mm-yy', date);
                    var day = date.getDay();
//                    var disableddates = window.deliverydateConfig.general.disabledDates;
                    var disableddates = $.map(window.deliverydateConfig.general.disabledDates, function (n, i) {
                        return [n];
                    });

                    var dayOffs = window.deliverydateConfig.general.dayOffs;
                    var dday = true;

                    var addressId = this.parentElement.getAttribute('id').split('_');
                    addressId = addressId[addressId.length - 1];
                    if (window.deliverydateConfig.calendar.options.deliveryOption == 2) {
                        todayDate = new Date();
                        var ordProcessTime = window.deliverydateConfig.calendar.options.ordProcessTime;
                        for (i = 0; i < ordProcessTime[addressId]; i++) {

//                            todayDate.setDate((todayDate.getDate()) + i);
//                            var datestr = todayDate.getDate() + "/" + (todayDate.getMonth() + 1) + "/" + todayDate.getFullYear();


                            var dd = ((todayDate.getDate() + i) < 10 ? '0' : '')
                                    + (todayDate.getDate() + i);

                            var mm = ((todayDate.getMonth() + 1) < 10 ? '0' : '')
                                    + (todayDate.getMonth() + 1);

                            var y = todayDate.getFullYear();
                            disableddates.push(dd + '-' + mm + '-' + y);
                        }
                    }

                    if (dayOffs != '' && dayOffs != null && dayOffs != undefined) {
                        var d = dayOffs.split(',');

                        $.each(d, function (index, val) {
                            if (day == val) {
                                dday = false;
                            }
                        });
                    }
                    return [disableddates.indexOf(currentdate) == -1 && dday];
                },
                onSelect: function () {
                    selectedDate = $(this).datepicker('getDate');
                    today = new Date()
                    this.visiblesamedayCharges = (selectedDate.getDate() == today.getDate() &&
                            selectedDate.getMonth() == today.getMonth() &&
                            selectedDate.getFullYear() == today.getFullYear());

                    var addressId = jQuery(this).attr('name').split('[');
                    addressId = addressId[1].split(']');
                    addressId = addressId[0];
                    var currencySymbol = window.deliverydateConfig.general.currencySymbol;
                    if (this.visiblesamedayCharges && window.deliverydateConfig.general.sameDayCharges && window.deliverydateConfig.general.enable_same_day_charge == 1) {
                        $("#delivery_charges_day_" + addressId + " span").html($.mage.__('Additional delivery charges are ' + currencySymbol + window.deliverydateConfig.general.sameDayCharges));
                        $("#delivery_charges_day_" + addressId).fadeIn();
                    } else {
                        $("#delivery_charges_day_" + addressId).fadeOut();
                    }
                    $('#shipping_arrival_date_' + addressId).val(jQuery.datepicker.formatDate('dd-mm-yy', selectedDate));
                    if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                        var selected_Date = jQuery.datepicker.formatDate('dd-mm-yy', selectedDate);
                        if (window.deliverydateConfig.general.per_week_day_charge != undefined && window.deliverydateConfig.general.enable_same_day_charge == 2) {
                            var dateValue = new Date(selected_Date.replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).getDay();
                            $("#delivery_charges_day_" + addressId).fadeOut();
                            var perWeekDayCharge = window.deliverydateConfig.general.per_week_day_charge;
                            $.each(perWeekDayCharge, function (index, val) {
                                if (val['day'] == dateValue) {
                                    $("#delivery_charges_day_" + addressId + " span").html($.mage.__('Additional delivery charges are ' + window.deliverydateConfig.general.currencySymbol + val['price']));
                                    $("#delivery_charges_day_" + addressId).fadeIn();
                                    return false;
                                }
                            });
                        }

                        $.ajax({
                            type: 'POST',
                            url: getUrl.build('deliverydate/index/calenderslot'),
                            data: {selectedDate: selected_Date},
                            dataType: 'json',
                            success: function (data) {
                                var timeDisplayMode = window.deliverydateConfig.calendar.options.timeDisplayMode;

                                if (data['timeslot'] == 'empty') {
                                    if (timeDisplayMode == 'dropdown') {
                                        jQuery(".timeslot_drp_" + addressId).empty();
                                        jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                        jQuery(".datetimeslot_" + addressId).css('display', 'block');
                                        jQuery("#slot_dropdown_selected_" + addressId + " .selected a span").html("No Timeslot available");
                                    }
                                    if (timeDisplayMode == 'radio') {
                                        jQuery(".timeslot_radio_" + addressId).empty();
                                        jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                        $(".timeslot_radio_" + addressId).append("No Timeslot available");
                                        if (!$(".timeslot_radio_" + addressId).hasClass("no_slot_available"))
                                            $(".timeslot_radio_" + addressId).addClass("no_slot_available");

                                    }
                                    if (timeDisplayMode == 'button') {
                                        jQuery(".timeslot_button_" + addressId).empty();
                                        jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                        $(".timeslot_button_" + addressId).append("No Timeslot available");
                                        if (!$(".timeslot_button_" + addressId).hasClass("no_slot_available"))
                                            $(".timeslot_button_" + addressId).addClass("no_slot_available");
                                    }
                                    return true;
                                }

                                var slot = $.map(data['timeslot'], function (el) {
                                    if (el['price'] != 0 && el['price'] != null) {
                                        return el['start_time'] + " - " + el['end_time'] + ' | ' + el['price'] + " = " + el['disable_value']
                                    } else {
                                        return el['start_time'] + " - " + el['end_time'] + " = " + el['disable_value']
                                    }
                                });
                                var self = this;
                                self.calandertimeslot = slot;

                                if (timeDisplayMode == 'radio') {
                                    jQuery(".timeslot_radio_" + addressId).empty();
                                    if (!$(".timeslot_radio_" + addressId).hasClass("available_slot_radio"))
                                        $(".timeslot_radio_" + addressId).addClass("available_slot_radio");
                                    $(".timeslot_radio_" + addressId).removeClass("no_slot_available");
                                    jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                    var i = 0;
                                    $.each(slot, function (index, value) {
                                        var slot_value = value.split(" = ");
                                        var labelname = 'timeSlot_' + i;
                                        if (slot_value[1] == "true") {
                                            var create = $('<div class="delivery-date-cell disable_radio"><input type="radio" id="' + labelname + '_' + addressId + '"  class="arrival_slot_time" name="shipping_arrival_slot" value="' + slot_value[0] + '" disabled="' + slot_value[1] + '"><label for="' + labelname + '_' + addressId + '" >' + slot_value[0] + '</label></div>');
                                        } else {
                                            var create = $('<div class="delivery-date-cell"><input type="radio" id="' + labelname + '_' + addressId + '" class="arrival_slot_time" name="shipping_arrival_slot[' + addressId + ']" value="' + slot_value[0] + '"><label for="' + labelname + '_' + addressId + '" >' + slot_value[0] + '</label></div>');
                                        }
                                        $(".timeslot_radio_" + addressId).append(create);
                                        i++;
                                    });
                                }
                                if (timeDisplayMode == 'button') {
                                    jQuery(".timeslot_button_" + addressId).empty();
                                    if (!$(".timeslot_button_" + addressId).hasClass("available_slot"))
                                        $(".timeslot_button_" + addressId).addClass("available_slot");
                                    $(".timeslot_button_" + addressId).removeClass("no_slot_available");
                                    jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                    var i = 0;
                                    $.each(slot, function (index, value) {
                                        var slot_value = value.split(" = ");
                                        var labelname = 'timeSlot_' + i;
                                        if (slot_value[1] == "true") {
                                            var create = $('<div class="delivery-date-cell disable_button"><div class="data-item arrival_slot_time_button button_disable" id="' + labelname + '_' + addressId + '"><label for="' + labelname + '_' + addressId + '">' + slot_value[0] + '</label></div>');
                                        } else {
                                            var create = $('<div class="delivery-date-cell"><div class="data-item arrival_slot_time_button ' + addressId + '" id="' + labelname + '_' + addressId + '"  value="' + slot_value[0] + '"><label for="' + labelname + '_' + addressId + '">' + slot_value[0] + '</label></div>');
                                        }
                                        $(".timeslot_button_" + addressId).append(create);
                                        i++;
                                    });

                                    $('.timeslot_button_' + addressId).removeClass('slick-initialized slick-slider slick-dotted');
                                    if (!$(".timeslot_button_" + addressId).hasClass("time_slider"))
                                        $(".timeslot_button_" + addressId).addClass("time_slider");
                                    jQuery(document).ready(function () {
                                        jQuery("#date_time_container").removeClass("date_time_container");
                                    });
                                    jQuery(".timeslot_button_" + addressId).slick({
                                        dots: false,
                                        infinite: false,
                                        centerMode: false,
                                        slidesToShow: 3, slidesToScroll: 1,
                                        responsive: [
                                            {
                                                breakpoint: 1100,
                                                settings: {
                                                    slidesToShow: 2,
                                                    slidesToScroll: 2,
                                                    infinite: false,
                                                    dots: false
                                                }
                                            },
                                            {
                                                breakpoint: 991,
                                                settings: {
                                                    slidesToShow: 1,
                                                    slidesToScroll: 1
                                                }
                                            },
                                            {
                                                breakpoint: 600,
                                                settings: {
                                                    slidesToShow: 2,
                                                    slidesToScroll: 2
                                                }
                                            },
                                            {
                                                breakpoint: 480,
                                                settings: {
                                                    slidesToShow: 1,
                                                    slidesToScroll: 1
                                                }
                                            }
                                        ]
                                    });
                                }
                                if (timeDisplayMode == 'dropdown') {
                                    jQuery(".timeslot_drp_" + addressId).empty();
                                    jQuery(".timeslot_label_" + addressId).css('display', 'block');
                                    jQuery(".datetimeslot_" + addressId).css('display', 'block');
                                    var i = 0;
                                    $.each(slot, function (index, value) {
                                        var slot_value = value.split(" = ");
                                        var labelname = 'timeSlot_' + i;
                                        if (slot_value[1] == "true") {
                                            var create = $('<li class="disable_opt" id="' + labelname + '"><span class="value">' + slot_value[0] + '</span></li>');
                                        } else {
                                            var create = $('<li id="' + labelname + '"><span class="value calandertimeslot" id="calandertimeslot_' + addressId + '" value="' + slot_value[0] + '">' + slot_value[0] + '</span></li>');
                                        }
                                        $(".timeslot_drp_" + addressId).append(create);
                                        i++;
                                    });
                                }
                            }
                        });
                    }
                },
            },
            shiftedValue: '',
            timeOffset: 0,
            validationParams: {
                dateFormat: '${ $.outputDateFormat }'
            },
            outputDateFormat: 'MM/dd/y',
            inputDateFormat: 'y-MM-dd',
            pickerDateTimeFormat: '',
            pickerDefaultDateFormat: 'MM/dd/y',
            // ICU Date Format
            pickerDefaultTimeFormat: 'h:mm a',
            // ICU Time Format
            elementTmpl: 'ui/form/element/date',
            listens: {
                'value': 'onValueChange',
                'shiftedValue': 'onShiftedValueChange'
            },
        },
        getMinDate: function () {

            if (this.options.showsTime && window.deliverydateConfig.calendar.options.interval !== 0) {
                var now = new Date((new Date()).getTime() + 3600 * window.deliverydateConfig.calendar.options.interval * 1000)
            } else {
                var now = new Date();
            }

            var dayDiff = window.deliverydateConfig.general.dayDiff;
            if (dayDiff > 0) {
                now.setDate(now.getDate() + dayDiff);
            }

            var currentdate = jQuery.datepicker.formatDate('dd-mm-yy', now);
            var dayoffset = 0;

            $.each(window.deliverydateConfig.general.disabledDates, function (index, val) {
                if (val == currentdate) {
                    now.setDate(now.getDate() + 1);
                    currentdate = jQuery.datepicker.formatDate('dd-mm-yy', now);
                }
            });
            return now;
        },
        isSelected: ko.computed(function () {
            return true;
        }
        ),
        initialize: function () {
            this._super();
            $('body').loader('show');

            this.isMandatory = false;

            this.isRtl = (window.deliverydateConfig.calendar.options.isRTL) ? "datepicker-rtl" : "";
//            this.useSlot = true;
//            if ($('#orderetimeslot').val() == null || $('#orderetimeslot').val() == '') {
//                this.useSlot = false;
//            }
//            this.deliverydateLabel = window.deliverydateConfig.calendar.options.deliverydateLabel;
//            this.timeslotTableLabel = window.deliverydateConfig.timeslot.timeslotTableLabel;

            this.checkslot = ko.observable(true);
            $('body').loader('hide');
        },
        initConfig: function () {
            this._super();
            $
            if (!this.options.dateFormat) {
                this.options.dateFormat = this.pickerDefaultDateFormat;
            }

            if (!this.options.timeFormat) {
                this.options.timeFormat = this.pickerDefaultTimeFormat;
            }

            if (!this.options.minDate) {
                this.options.minDate = this.getMinDate();
            }

            if (!this.options.buttonText) {
                this.options.buttonText = 'Select Date';
            }
            if (window.deliverydateConfig.calendar.options.maxDate !== 0 && window.deliverydateConfig.calendar.options.maxDate !== "" && window.deliverydateConfig.calendar.options.maxDate != undefined) {
                this.options.maxDate = "+" + window.deliverydateConfig.calendar.options.maxDate - 1 + "d";
            }
            this.options.showWeek = false;
            /*if (!this.options.defaultDate) {
             this.options.defaultDate = this.getDefaultDate()
             }*/

            this.prepareDateTimeFormats();
            return this;
        },
        initObservable: function () {
            return this._super().observe(['shiftedValue']);
        },
        onValueChange: function (value) {
            var dateFormat,
                    shiftedValue;

            if (value) {
                if (this.options.showsTime) {
                    shiftedValue = moment.utc(value).add(this.timeOffset, 'seconds');
                } else {
                    dateFormat = this.shiftedValue() ? this.outputDateFormat : this.inputDateFormat;

                    shiftedValue = moment(value, dateFormat);
                }

                shiftedValue = shiftedValue.format(this.pickerDateTimeFormat);
            } else {
                shiftedValue = '';
            }
            if (shiftedValue !== this.shiftedValue()) {
                this.shiftedValue(shiftedValue);
            }
        },
        /**
         * Prepares and sets date/time value that will be sent
         * to the server.
         *
         * @param {String} shiftedValue
         */
        onShiftedValueChange: function (shiftedValue) {
            var value;

            if (shiftedValue) {
                if (this.options.showsTime) {
                    value = moment.utc(shiftedValue, this.pickerDateTimeFormat);
                    value = value.subtract(this.timeOffset, 'seconds').toISOString();
                } else {
                    value = moment(shiftedValue, this.pickerDateTimeFormat);
                    value = value.format(this.outputDateFormat);
                }
            } else {
                value = '';
            }
            /*if (value !== this.value()) {
             this.value(value);
             }*/
        },
        prepareDateTimeFormats: function () {
            this.pickerDateTimeFormat = this.options.dateFormat;
            if (this.options.showsTime) {
                this.pickerDateTimeFormat += ' ' + this.options.timeFormat;
            }
            this.pickerDateTimeFormat = utils.normalizeDate(this.pickerDateTimeFormat);
            if (this.dateFormat) {
                this.inputDateFormat = this.dateFormat;
            }
            this.inputDateFormat = utils.normalizeDate(this.inputDateFormat);
            this.outputDateFormat = utils.normalizeDate(this.outputDateFormat);
            this.validationParams.dateFormat = this.outputDateFormat;
        },
    });
});
