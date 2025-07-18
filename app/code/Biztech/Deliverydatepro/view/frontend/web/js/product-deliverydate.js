define(
        ['Magento_Ui/js/form/element/abstract',
            'calendar',
            'mageUtils',
            'moment',
            'ko',
            'jquery',
            'mousewheel',
            'slick',
            'mage/url',
            'Magento_Customer/js/customer-data',
            'jquery/ui',
            'jquery/jquery-ui-timepicker-addon', 'mage/translate',
        ],
        function (Component, calendar, utils, moment,ko, $, mousewheel, slick, getUrl, customerData) {
            'use strict';

            $(document).ready(function () {
                var currencySymbol = window.deliverydateConfig.general.currencySymbol;
                setTimeout(addClassButton, 1000);
                function addClassButton() {
                    var calendarMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
                    var timeSlotMode = window.deliverydateConfig.calendar.options.timeDisplayMode;
                    if (calendarMode == 'button' || timeSlotMode == 'button') {
                        if ($("#date_time_container").hasClass("date_time_container"))
                            $("#date_time_container").removeClass("date_time_container");
                    }
                }
                // custom-dropdown js start
                $(document).on('click', '#dds_dropdown_selected .selected', function (event) {
                    jQuery("#dds_dropdown_selected .options").toggleClass("open_option");
                });
                $(document).on('click', '#slot_dropdown_selected .selected', function (event) {
                    if ($('ul.timeslot_drp li').length >= 1) { //Check condition for no timeslot available
                        jQuery("#slot_dropdown_selected .options").toggleClass("open_option");
                    }
                });

                // custom-dropdown js end

                $(document).on('change', '#date-picker', function (ele) {
                    $('#shipping_arrival_date').val($('#date-picker').val());
                    if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                        var selectedDate = $('#date-picker').val();
                        calendarTimeslot(selectedDate);
                    }
                    getDayPrice(selectedDate);
                });
                $(document).on('click', '.delivery-slot', function (event) {
                    $('body').loader('show');

                    var res = this.id.split('_');
                    $('#shipping_arrival_date').val(res[0]);
                    $('#shipping_arrival_slot').val($(this).val());
                    $('#delivery_charges').val(res[2]);
                    getDayPrice(res[0]);
                    $('body').loader('hide');
                });
                $(document).on('click', '#delivery_date_callme', function (event) {
                    if ($('#delivery_date_callme').is(":checked")) {
                        $('#call_me_text').addClass('active');
                    } else {
                        $('#call_me_text').removeClass('active');
                    }
                });

                /* Bind Delivery date calendar data in dropdown */
                /* setTimeout(BindCalendardata, 1000);
                 
                 function BindCalendardata() {
                 var days = window.deliverydateConfig.calendar.options.getavailableDays;
                 $("#calanderdate option").remove();
                 $('#calanderdate')
                 .append($("<option></option>")
                 .attr("value", '')
                 .text("-- Select Delivery Date --"));
                 $.each(days, function (index, value) {
                 if (value.disable_value == 'true') {
                 $('#calanderdate')
                 .append($("<option></option>")
                 .attr("value", value.value)
                 .attr("disabled", true)
                 .text(value.value));
                 } else {
                 $('#calanderdate')
                 .append($("<option></option>")
                 .attr("value", value.value)
                 .text(value.value));
                 }
                 });
                 } */
                /* Bind Delivery date calendar data in dropdown */

                /* Assign selected Delivery date calendar data from dropdown */
                $(document).on('click', '.calanderdate', function (ele) {
                    $('#shipping_arrival_date').val(this.value);
                    var text = $(this).html();
                    $("#dds_dropdown_selected .selected a span").html(text);
                    $("#dds_dropdown_selected .options").toggleClass("open_option");
                    if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                        var selectedDate = this.value;
                        calendarTimeslot(selectedDate);
                    }
                    getDayPrice(selectedDate);
                });

                /* Delivery date calendar data in swatch type button */
                $(document).on('click', '.calendar_date_button', function (event) {
                    $('#shipping_arrival_date').val(this.value);
                    $('.calendar_date_button').not(this).removeClass('active');
                    $(this).addClass('active');
                    if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                        var selectedDate = this.value;
                        calendarTimeslot(selectedDate);
                    }
                    getDayPrice(selectedDate);
                });

                /* Display selected Delivery date calendar data from radio button */
                $(document).on('click', '.delivery-date-slot', function (event) {
                    if ($(this).is(':checked'))
                    {
                        if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                            var selectedDate = $(this).val();
                            calendarTimeslot(selectedDate);
                        }
                        getDayPrice(selectedDate);
                    }
                });

                /* Assign selected timeslot data from radio */
                $(document).on('click', '.arrival_slot_time', function (event) {
                    if ($(this).is(':checked'))
                    {
                        var slotData = $(this).val().split('|');
                        $('#shipping_arrival_slot').val(slotData[0]);
                        $('#delivery_charges').val(slotData[1]);
                    }
                });

                /* Delivery date calendar data in swatch type button */
                $(document).on('click', '.arrival_slot_time_button', function (event) {
                    var slotValue = this.firstChild.innerHTML;
                    var slotData = slotValue.split('|');
                    $('#shipping_arrival_slot').val(slotData[0]);
                    $('#delivery_charges').val(slotData[1]);
                    $('.arrival_slot_time_button').not(this).removeClass('active');
                    $(this).addClass('active');
                });


                /* Assign selected timeslot data from dropdown */
                $(document).on('click', '.calandertimeslot', function (ele) {
                    var text = $(this).html();
                    var slotData = text.split('|');
                    $('#shipping_arrival_slot').val(slotData[0]);
                    $('#delivery_charges').val(slotData[1]);
                    $("#slot_dropdown_selected .selected a span").html(text);
                    $("#slot_dropdown_selected .options").toggleClass("open_option");
                });
                /* Assign selected Delivery date calendar data from dropdown */

                $(document).on('click', '#product-addtocart-button', function (event) {

                    if (window.deliverydateConfig.general.isMandatory === 1) {

                        if (window.deliverydateConfig.templateConfig.enabledMethod == 2 && deliverydateConfig.general.enabled) {

                            if (!$('.delivery-slot').is(':checked')) {
                                event.preventDefault();
                                alert({
                                    title: $.mage.__('Attention'),
                                    content: $.mage.__('Please Select A Timeslot'),
                                    actions: {
                                        always: function () {}
                                    }
                                });
                            }

                            if (window.deliverydateConfig.general.comment_required == "1" && window.deliverydateConfig.general.add_comment == "1") {
                                var commetData = jQuery("#shipping_arrival_comments").val();
                                if (!commetData && $('.delivery-slot').is(':checked')) {
                                    alert({
                                        title: $.mage.__('Attention'),
                                        content: $.mage.__('Please add a comment'),
                                        actions: {
                                            always: function () {}
                                        }
                                    });
                                    event.preventDefault();
                                }
                            }
                        }
                        if (window.deliverydateConfig.templateConfig.enabledMethod == 1 && deliverydateConfig.general.enabled) {
                            var calendarMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
                            var timeSlotMode = window.deliverydateConfig.calendar.options.timeDisplayMode;
                            var calendarInput = true;
                            var timeSlotInput = true;
                            if (calendarMode == 'radio') {
                                if (!jQuery('.delivery-date-slot').is(':checked')) {
                                    calendarInput = false;
                                }
                            }
                            if (calendarMode == 'dropdown') {
                                if (jQuery('#shipping_arrival_date').val() == "" || jQuery('#shipping_arrival_date').val() == undefined) {
                                    calendarInput = false;
                                }
                            }
                            if (calendarMode == 'calendar') {
                                if (jQuery('#date-picker').val() == "" || jQuery('#date-picker').val() == undefined) {
                                    calendarInput = false;
                                }
                            }
                            if (calendarMode == 'button' && !$('.calendar_date_button').hasClass("active")) {
                                calendarInput = false;
                            }
                            if (timeSlotMode == 'button' && !$('.arrival_slot_time_button').hasClass("active")) {
                                timeSlotInput = false;
                            }
                            if (timeSlotMode == 'dropdown') {
                                if (jQuery('#shipping_arrival_slot').val() == "" || jQuery('#shipping_arrival_slot').val() == undefined) {
                                    timeSlotInput = false;
                                }
                            }
                            if (timeSlotMode == 'radio') {
                                if (jQuery('.arrival_slot_time').length > 0) {
                                    if (!jQuery('.arrival_slot_time').is(':checked')) {
                                        timeSlotInput = false;
                                    }
                                }
                            }
                            if (calendarInput == false) {
                                event.preventDefault();
                                alert({
                                    title: $.mage.__('Attention'),
                                    content: $.mage.__('Please Select Delivery Date'),
                                    actions: {
                                        always: function () {}
                                    }
                                });
                            }
                            if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                                if (timeSlotInput == false && calendarInput == true) {
                                    event.preventDefault();
                                    alert({
                                        title: $.mage.__('Attention'),
                                        content: $.mage.__('Please Select A Timeslot'),
                                        actions: {
                                            always: function () {}
                                        }
                                    });
                                }
                            }

                            if (window.deliverydateConfig.general.comment_required == "1" && window.deliverydateConfig.general.add_comment == "1") {
                                var commetData = jQuery("#shipping_arrival_comments").val();
                                if ((!commetData && calendarInput == true && timeSlotInput == true) || (!commetData && calendarInput == true && window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == 0)) {
                                    alert({
                                        title: $.mage.__('Attention'),
                                        content: $.mage.__('Please add a comment'),
                                        actions: {
                                            always: function () {}
                                        }
                                    });
                                    event.preventDefault();
                                }
                            }
                        }
                    }
                });

                function getDayPrice(dateValue) {
                    if (window.deliverydateConfig.general.applyAdditionalCharge == 1) {
                        if (window.deliverydateConfig.general.ProductWiseCharges > 0 && window.deliverydateConfig.general.allowProductWiseCharge == 1) {
                            jQuery(".additional_charge").css('display', 'block');
                        } else {
                            jQuery(".additional_charge").css('display', 'none');
                        }
                        if (window.deliverydateConfig.general.per_week_day_charge != undefined && window.deliverydateConfig.general.enable_same_day_charge == 2) {
                            var selected_Day = new Date(dateValue.replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3")).getDay();
                            var perWeekDayCharge = window.deliverydateConfig.general.per_week_day_charge;
                            $.each(perWeekDayCharge, function (index, val) {
                                if (val['day'] == selected_Day) {
                                    var productCharge = 0;
                                    if (window.deliverydateConfig.general.ProductWiseCharges > 0 && window.deliverydateConfig.general.allowProductWiseCharge == 1) {
                                        productCharge = parseFloat(window.deliverydateConfig.general.ProductWiseCharges);
                                    }
                                    var finalPrice = productCharge + parseFloat(val['price']);
                                    jQuery(".additional_charge").css('display', 'block');
                                    jQuery(".additional_charge_value").html(currencySymbol + finalPrice);
                                    return false;
                                }
                            });
                        }
                        if (window.deliverydateConfig.general.enable_same_day_charge == 1) {
                            var selectedDate = new Date(dateValue.replace(/(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
                            var today = new Date();
                            var visiblesamedayCharges = (selectedDate.getDate() == today.getDate() &&
                                    selectedDate.getMonth() == today.getMonth() &&
                                    selectedDate.getFullYear() == today.getFullYear());
                            if (visiblesamedayCharges && window.deliverydateConfig.general.sameDayCharges && window.deliverydateConfig.general.enable_same_day_charge == 1) {
                                var productCharge = 0;
                                if (window.deliverydateConfig.general.ProductWiseCharges > 0 && window.deliverydateConfig.general.allowProductWiseCharge == 1) {
                                    productCharge = parseFloat(window.deliverydateConfig.general.ProductWiseCharges);
                                }
                                var finalPrice = productCharge + window.deliverydateConfig.general.sameDayCharges;
                                jQuery(".additional_charge").css('display', 'block');
                                jQuery(".additional_charge_value").html(currencySymbol + finalPrice);
                            }
                        }
                    }
                }
                setTimeout(function () {
                    if (window.deliverydateConfig.general.applyAdditionalCharge == 1 && window.deliverydateConfig.general.allowProductWiseCharge == 1 && window.deliverydateConfig.general.ProductWiseCharges > 0) {
                        jQuery(".additional_charge").css('display', 'block');
                    }
                }, 1000);

                function calendarTimeslot(selected_Date) {
                    $.ajax({
                        type: 'POST',
                        url: getUrl.build('deliverydate/index/calenderslot'),
                        data: {selectedDate: selected_Date},
                        dataType: 'json',
                        success: function (data) {
                            var timeDisplayMode = window.deliverydateConfig.calendar.options.timeDisplayMode;

                            if (data['timeslot'] == 'empty') {
                                if (timeDisplayMode == 'dropdown') {
                                    jQuery(".timeslot_drp").empty();
                                    jQuery(".calender-comment-label").css('display', 'block');
                                    jQuery(".datetimeslot").css('display', 'block');
                                    jQuery("#slot_dropdown_selected .selected a span").html("No Timeslot available");
                                }
                                if (timeDisplayMode == 'radio') {
                                    jQuery(".timeslot_radio").empty();
                                    jQuery(".calender-comment-label").css('display', 'block');
                                    $(".timeslot_radio").append("No Timeslot available");
                                    if (!$(".timeslot_radio").hasClass("no_slot_available"))
                                        $(".timeslot_radio").addClass("no_slot_available");

                                }
                                if (timeDisplayMode == 'button') {
                                    jQuery(".timeslot_button").empty();
                                    jQuery(".calender-comment-label").css('display', 'block');
                                    $(".timeslot_button").append("No Timeslot available");
                                    if (!$(".timeslot_button").hasClass("no_slot_available"))
                                        $(".timeslot_button").addClass("no_slot_available");
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
                                jQuery(".timeslot_radio").empty();
                                if (!$(".timeslot_radio").hasClass("available_slot_radio"))
                                    $(".timeslot_radio").addClass("available_slot_radio");
                                $(".timeslot_radio").removeClass("no_slot_available");
                                jQuery(".calender-comment-label").css('display', 'block');
                                var i = 0;
                                $.each(slot, function (index, value) {
                                    var slot_value = value.split(" = ");
                                    var labelname = 'timeSlot_' + i;
                                    if (slot_value[1] == "true") {
                                        var create = $('<div class="delivery-date-cell disable_radio"><input type="radio" id="' + labelname + '" class="arrival_slot_time" name="shipping_arrival_slot" value="' + slot_value[0] + '" disabled="' + slot_value[1] + '"><label for="' + labelname + '">' + slot_value[0] + '</label></div>');
                                    } else {
                                        var create = $('<div class="delivery-date-cell"><input type="radio" id="' + labelname + '" class="arrival_slot_time" name="shipping_arrival_slot" value="' + slot_value[0] + '"><label for="' + labelname + '">' + slot_value[0] + '</label></div>');
                                    }
                                    $(".timeslot_radio").append(create);
                                    i++;
                                });
                            }
                            if (timeDisplayMode == 'button') {
                                jQuery(".timeslot_button").empty();
                                if (!$(".timeslot_button").hasClass("available_slot"))
                                    $(".timeslot_button").addClass("available_slot");
                                $(".timeslot_button").removeClass("no_slot_available");
                                jQuery(".calender-comment-label").css('display', 'block');
                                var i = 0;
                                $.each(slot, function (index, value) {
                                    var slot_value = value.split(" = ");
                                    var labelname = 'timeSlot_' + i;
                                    if (slot_value[1] == "true") {
                                        var create = $('<div class="delivery-date-cell disable_button"><div class="data-item arrival_slot_time_button button_disable" id="' + labelname + '"><label for="' + labelname + '">' + slot_value[0] + '</label></div>');
                                    } else {
                                        var create = $('<div class="delivery-date-cell"><div class="data-item arrival_slot_time_button" id="' + labelname + '"  value="' + slot_value[0] + '"><label for="' + labelname + '">' + slot_value[0] + '</label></div>');
                                    }
                                    $(".timeslot_button").append(create);
                                    i++;
                                });
                                $('.timeslot_button').removeClass('slick-initialized slick-slider slick-dotted');
                                if (!$(".timeslot_button").hasClass("time_slider"))
                                    $(".timeslot_button").addClass("time_slider");
                                jQuery(document).ready(function () {
                                    jQuery("#date_time_container").removeClass("date_time_container");
                                });
                                jQuery(".timeslot_button").slick({
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
                                jQuery(".timeslot_drp").empty();
                                jQuery(".calender-comment-label").css('display', 'block');
                                jQuery(".datetimeslot").css('display', 'block');
                                var i = 0;
                                $.each(slot, function (index, value) {
                                    var slot_value = value.split(" = ");
                                    var labelname = 'timeSlot_' + i;
                                    if (slot_value[1] == "true") {
                                        var create = $('<li class="disable_opt" id="' + labelname + '"><span class="value">' + slot_value[0] + '</span></li>');
                                    } else {
                                        var create = $('<li id="' + labelname + '"><span class="value calandertimeslot" value="' + slot_value[0] + '">' + slot_value[0] + '</span></li>');
                                    }
                                    $(".timeslot_drp").append(create);
                                    i++;
                                });
                            }
                        }
                    });
                }
            });
            return Component.extend({
                //calanderdate: ko.observableArray(['-- Select Delivery Date --']),
                defaults: {
                    template: window.deliverydateConfig.templateConfig.template,
                    options: {
                        buttonImage: window.deliverydateConfig.calendar.options.buttonImage,
                        buttonImageOnly: true,
                        buttonText: window.deliverydateConfig.calendar.options.buttonText,
                        showsTime: window.deliverydateConfig.calendar.options.showsTime,
                        showOn: "both",
                        dateFormat: window.deliverydateConfig.general.dateFormat,
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
                            var disableddates = window.deliverydateConfig.general.disabledDates;
                            var dayOffs = window.deliverydateConfig.general.dayOffs;
                            var dday = true;
                            var holiday = true;

                            if (dayOffs != '' && dayOffs != null && dayOffs != undefined) {
                                var d = dayOffs.split(',');

                                $.each(d, function (index, val) {
                                    if (day == val) {
                                        dday = false;
                                    }
                                });
                            }
                            if (window.deliverydateConfig.calendar.options.annualRepeatHoliday != undefined) {
                                var annualRepeatHoliday = window.deliverydateConfig.calendar.options.annualRepeatHoliday;
                                var month = date.getMonth() + 1; // +1 because JS months start at 0
                                var day = date.getDate();
                                if (annualRepeatHoliday.indexOf(day + '-' + month) > -1)
                                    holiday = false;
                            }
                            return [window.deliverydateConfig.general.disabledDates.indexOf(currentdate) == -1 && dday && holiday];
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
                getShowHtml: function () {
                    return window.deliverydateConfig.templateConfig.showHtml;
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

                    this.deliverydateEnabled = window.deliverydateConfig.general.enabled;
                    this.isMandatory = false;

                    this.isRtl = (window.deliverydateConfig.calendar.options.isRTL) ? "datepicker-rtl" : "";
                    var timeSlot = window.deliverydateConfig.timeslot.enabled_timeslots;

                    if (window.deliverydateConfig.general.isMandatory == 1) {
                        this.isMandatory = 'required';
                    }
                    this.calenderView = false;
                    this.deliverydateLabel = window.deliverydateConfig.templateConfig.deliverydateLabel;
                    this.deliverydatecommentLabel = window.deliverydateConfig.templateConfig.deliverydateComments;
                    this.displayHtml = window.deliverydateConfig.templateConfig.displayHtml;

                    var finalTimeSlotArray = $.map(timeSlot, function (el) {
                        return el
                    });

                    this.timeSlots = ko.observableArray(finalTimeSlotArray);

                    var dateSlot = window.deliverydateConfig.calendar.options.getavailableDays;
                    var finalDateSlotArray = $.map(dateSlot, function (el) {
                        return el
                    });
                    this.dateSlot = ko.observableArray(finalDateSlotArray);

                    this.timeslotTableLabel = window.deliverydateConfig.timeslot.timeslotTableLabel;
                    this.deliverydatecallMeLabel = "";
                    if (window.deliverydateConfig.templateConfig.enabledMethod == "1") {
                        this.calenderView = true;
                    }
                    this.callfordelivery = ko.observable(false),
                            this.useCallMe = false;
                    if (window.deliverydateConfig.general.useCallFeature === 1) {
                        this.deliverydatecallMeLabel = window.deliverydateConfig.general.callMeLabel;
                        this.useCallMe = true;
                    }

                    this.checkslot = ko.observable(true);
                    this.dateDisplayMode = window.deliverydateConfig.calendar.options.dateDisplayMode;
                    this.timeDisplayMode = window.deliverydateConfig.calendar.options.timeDisplayMode;
                    this.useSlot = false;
                    if (window.deliverydateConfig.calendar.options.enableTimeSlotForCalendar == '1') {
                        this.useSlot = true;
                        this.deliverydateSlotLabel = window.deliverydateConfig.calendar.options.deliverydateSlotLabel;
                    }
                    this.imageURL = window.deliverydateConfig.calendar.options.imageUrl;

                    if (window.deliverydateConfig.general.applyAdditionalCharge == 1 && window.deliverydateConfig.general.allowProductWiseCharge == 1 && window.deliverydateConfig.general.ProductWiseCharges > 0) {
                        this.additionalChargeLabel = 'Additional Delivery Charge: ';
                        this.additionalCharge = window.deliverydateConfig.general.ProductWiseCharges;
                    } else {
                        this.additionalCharge = 0;
                    }
                    this.displayMessage = false;
                    if (window.deliverydateConfig.general.estimatedMessage != undefined && window.deliverydateConfig.general.estimatedMessage.display == true) {
                        this.displayMessage = true;
                        this.estimatedMessage = window.deliverydateConfig.general.estimatedMessage.message;
                    }
                    this.add_comment = window.deliverydateConfig.general.add_comment;
                    if (this.add_comment == '1') {
                        this.comment_required = window.deliverydateConfig.general.comment_required;
                    }
                    this.enablwOnWhichPage = window.deliverydateConfig.general.enablwOnWhichPage;

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

                loadScrollBooster: function () {
                    jQuery('.table-checkout-delivery-method').mousewheel(function (e, delta) {
                        this.scrollLeft -= (delta * 40);
                        e.preventDefault();
                    });
                },
                addDateSlider: function () {
                    var dateOption = window.deliverydateConfig.calendar.options;
//            if (dateOption.dateDisplayMode == 'button' && dateOption.timeDisplayMode == 'button') {
                    if (dateOption.dateDisplayMode == 'button') {
                        if (!$(".available_date_button").hasClass("date_slider"))
                            $(".available_date_button").addClass("date_slider");
                        jQuery(document).ready(function () {
                            // jQuery(window).resize(function () {
                            //     sliderOwl();
                            // });

                            // jQuery(window).on('load', function () {
                            //     sliderOwl();
                            // });
                            jQuery("#date_time_container").removeClass("date_time_container");
                        });
                        // function sliderOwl() {
                        jQuery(".available_date_button").slick({
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
                        // }

                    }
                },
                isRequired: function () {
                    if (window.deliverydateConfig.general.isMandatory === 1) {
                        return "required";
                    } else {
                        return "";
                    }
                }
            });
        }
);
