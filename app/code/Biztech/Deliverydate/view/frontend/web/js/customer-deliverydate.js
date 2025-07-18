define(
        ['jquery',
            'uiComponent',
            'ko',
            'moment',
            'mageUtils',
            'Magento_Ui/js/modal/alert',
            'mousewheel',
            'mage/url',
            'jquery/ui',
            'jquery/jquery-ui-timepicker-addon',
            'mageUtils',
            'mage/calendar',
            'mage/translate'
        ],
        function ($, Component, ko, moment, utils, alert, mousewheel, getUrl) {
            'use strict';

            $(document).ready(function () {

                // custom-dropdown js start
                $(document).on('click', '#slot_dropdown_selected .selected', function (event) {
                    if ($('ul.timeslot_drp li').length >= 1) { //Check condition for no timeslot available
                        jQuery("#slot_dropdown_selected .options").toggleClass("open_option");
                    }
                });

                /* Assign selected timeslot data from dropdown */
                $(document).on('click', '.calandertimeslot', function (ele) {
                    var text = $(this).html();
                    $('#shipping_arrival_slot').val(text);
                    $("#slot_dropdown_selected .selected a span").html(text);
                    $("#slot_dropdown_selected .options").toggleClass("open_option");
                });
                // custom-dropdown js end

                $(document).on('change', '#date-picker', function (ele) {
                    if ($('#orderetimeslot').val()) {
                        var selected_Date = $('#date-picker').val();
                        $.ajax({
                            type: 'POST',
                            url: getUrl.build('deliverydate/index/calenderslot'),
                            data: {selectedDate: selected_Date},
                            dataType: 'json',
                            success: function (data) {
                                if (data['timeslot'] == 'empty') {
                                    jQuery(".timeslot_drp").empty();
                                    jQuery(".calender-comment-label").css('display', 'block');
                                    jQuery(".datetimeslot").css('display', 'block');
                                    jQuery("#slot_dropdown_selected .selected a span").html("No Timeslot available");
                                    return true;
                                }
                                var slot = $.map(data['timeslot'], function (el) {
                                    return el['start_time'] + " - " + el['end_time'] + " = " + el['disable_value']
                                });
                                if (slot.length == 0) {
                                    jQuery(".timeslot_drp").empty();
                                    jQuery(".calender-comment-label").css('display', 'block');
                                    jQuery(".datetimeslot").css('display', 'block');
                                    jQuery("#slot_dropdown_selected .selected a span").html("No Timeslot available");
                                    return true;
                                }
                                var self = this;
                                self.calandertimeslot = slot;


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
                        });
                    }
                });

                $(document).on('click', '#update-deliverydate', function (event) {
                    if (!$('#date-picker').val()) {
                        event.preventDefault();
                        alert({
                            title: $.mage.__('Attention'),
                            content: $.mage.__('Please Select Delivery Date'),
                            actions: {
                                always: function () {}
                            }
                        });
                    } else if ((jQuery('#shipping_arrival_slot').val() == "" || jQuery('#shipping_arrival_slot').val() == 'undefined') && $('#date-picker').val()) {
                        event.preventDefault();
                        alert({
                            title: $.mage.__('Attention'),
                            content: $.mage.__('Please Select  A Timeslot'),
                            actions: {
                                always: function () {}
                            }
                        });
                    } else {
                        var selected_Date = $('#date-picker').val();
                        var selected_Timeslot = jQuery('#shipping_arrival_slot').val();
                        var order_id = $('#orderid').val();
                        var order_Date = $('#orderedate').val();
                        var order_Timeslot = $('#orderetimeslot').val();
                        if (order_Timeslot == '' || order_Timeslot == null) {
                            selected_Timeslot = null;
                            order_Timeslot = null;
                        }
                        $.ajax({
                            type: 'POST',
                            url: getUrl.build('deliverydate/index/updatedeliverydate'),
                            data: {selectedDate: selected_Date, selectedTimeslot: selected_Timeslot, orderId: order_id, ordereDate: order_Date, ordereTimeslot: order_Timeslot},
                            dataType: 'json',
                            success: function (data) {
                                window.location.reload();
                            }
                        });
                    }
                });
            });
            return Component.extend({
                //calandertimeslot: ko.observableArray(['Select date to show timeSlot']),
                defaults: {
                    template: 'Biztech_Deliverydate/customer/view/deliverydate',
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
                            var disableddates = window.deliverydateConfig.general.disabledDates;
                            var dayOffs = window.deliverydateConfig.general.dayOffs;
                            var dday = true;

                            if (dayOffs != '' && dayOffs != null && dayOffs != undefined) {
                                var d = dayOffs.split(',');

                                $.each(d, function (index, val) {
                                    if (day == val) {
                                        dday = false;
                                    }
                                });
                            }
                            return [window.deliverydateConfig.general.disabledDates.indexOf(currentdate) == -1 && dday];
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

                    this.isMandatory = false;

                    this.isRtl = (window.deliverydateConfig.calendar.options.isRTL) ? "datepicker-rtl" : "";
                    this.useSlot = true;
                    if ($('#orderetimeslot').val() == null || $('#orderetimeslot').val() == '') {
                        this.useSlot = false;
                    }
                    this.deliverydateLabel = window.deliverydateConfig.calendar.options.deliverydateLabel;
                    this.timeslotTableLabel = window.deliverydateConfig.timeslot.timeslotTableLabel;

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

                loadScrollBooster: function () {
                    jQuery('.table-checkout-delivery-method').mousewheel(function (e, delta) {
                        this.scrollLeft -= (delta * 40);
                        e.preventDefault();
                    });
                },

                isRequired: function () {
                    return "";
                }
            });
        }
);