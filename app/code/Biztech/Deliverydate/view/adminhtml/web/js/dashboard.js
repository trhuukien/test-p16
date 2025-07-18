require([
    'jquery', 'calendar', 'mage/storage', 'Magento_Ui/js/modal/modal', 'Biztech_Deliverydate/js/fullLoader', 'mage/validation/url'],
        function ($, calendar, storage, modal, fullLoader, urlRedirect) {
            $(document).on('click', '.delivery-orders', function (event) {
                event.preventDefault();
                var url = $(this).data('url');
                fullLoader.startLoader();

                return storage.get(url).done(function (transport) {
                    var resultModal = $('#popup_modal').html(transport);
                    var options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        height: '100px',
                        width: '100px',
                        title: 'Orders',
                        content: transport
                    };
                    fullLoader.stopLoader(true);
                    var popup = modal(options, $('#popup_modal'));
                    resultModal.modal('openModal');
                }).fail(function () {
                    var resultModal = $('#popup_modal').html('<p>Unkown Error</p>');
                    var options = {
                        type: 'popup',
                        responsive: true,
                        innerScroll: true,
                        height: '100px',
                        width: '100px',
                        title: 'Orders',
                        content: transport
                    };
                    fullLoader.stopLoader(true);
                    var popup = modal(options, $('#popup_modal'));
                    resultModal.modal('openModal');
                });

            });
            $(document).on('click', '#grid_tab_deliverydate_orders', function (event)
            {
                jQuery('#Todays_Delivery_Date_Orders').addClass("hideloader");
            });

            $(document).on('click', '#deliverysearch', function (event) {
                event.preventDefault();

                var url = $(this).data('url');
                var params = {
                    delivery_from: $('#delivery_from').val(),
                    delivery_to: $('#delivery_to').val()
                };

                fullLoader.startLoader();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: params,
                })
                        .done(function (transport) {
                            jQuery('#Todays_Delivery_Date_Orders').addClass("hideloader");
                            $('#Todays_Delivery_Date_Orders').html(transport);
                            fullLoader.stopLoader(true);

                        })
                        .fail(function () {
                            jQuery('#Todays_Delivery_Date_Orders').addClass("hideloader");
                            $('#Todays_Delivery_Date_Orders').html('<p>An error has occurred</p>');
                            fullLoader.stopLoader(true);
                        });
            });

            $(document).on('click', '#delivery-chart', function (event) {
                event.preventDefault();
                var url = $(this).data('url');
                var title = "Hello";
                var delivery_from = $('#delivery_from').val();
                var delivery_to = $('#delivery_to').val();
                url = url + '?delivery_from=' + delivery_from + '&delivery_to=' + delivery_to;
                var left = (screen.width / 2);
                var top = (screen.height / 2);
                jQuery('#Todays_Delivery_Date_Orders').addClass("hideloader");
                //    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, top=' + top + ', left=' + left);
                return window.open(url);
            });

            $(document).on('click', '#delivery-export', function (event) {
                event.preventDefault();
                var url = $(this).data('url');
                var delivery_from = jQuery('#delivery_from').val();
                var delivery_to = jQuery('#delivery_to').val();
                var params = {
                    delivery_from: delivery_from,
                    delivery_to: delivery_to
                };

                var path = url + '?delivery_from=' + delivery_from + '&delivery_to=' + delivery_to;
                return urlRedirect.redirect(path);

            });

            $(document).on('click', '.cal-nav', function (event) {
                event.preventDefault();

                fullLoader.startLoader();

                var url = $(this).data('url');

                return storage.get(url).done(function (transport) {
                    $('#result').html(transport);
                    fullLoader.stopLoader(true);
                }).fail(function () {
                    $('#result').html('transport');
                    fullLoader.stopLoader(true);
                });
            });

            $(document).on('blur', '#deliverydate_deliverydate_configuration_deliverytime_day_diff', function (event) {
                var OrderProcessingTime = $("#deliverydate_deliverydate_configuration_deliverytime_day_diff").val();
                var enableCutOff = $("#deliverydate_deliverydate_configuration_enable_cutoff").val();
                if (OrderProcessingTime == "0" || OrderProcessingTime == "") {
                    if ($("#row_deliverydate_deliverydate_configuration_enable_cutoff").css('display') == 'none') {
                        $("#row_deliverydate_deliverydate_configuration_enable_cutoff").removeAttr("style");
                        $("#deliverydate_deliverydate_configuration_enable_cutoff").removeAttr("style");

                        if (enableCutOff != "0") {
                            $("#row_deliverydate_deliverydate_configuration_productwise_cutoff").removeAttr("style");
                            $("#deliverydate_deliverydate_configuration_productwise_cutoff").removeAttr("style");

                            $("#row_deliverydate_deliverydate_configuration_end_same_day_delivery").removeAttr("style");
                            $("#deliverydate_deliverydate_configuration_end_same_day_delivery").removeAttr("style");

                            $("#row_deliverydate_deliverydate_configuration_cut_offtime").removeAttr("style");
                            jQuery("#row_deliverydate_deliverydate_configuration_cut_offtime > td ").find("select").each(function () {
                                jQuery(this).removeAttr("style");
                                jQuery(this).width("60px");

                            });
                        }
                    }
                } else {
                    $("#row_deliverydate_deliverydate_configuration_enable_cutoff").css('display', 'none');
                    $("#deliverydate_deliverydate_configuration_enable_cutoff").css('display', 'none');

                    $("#row_deliverydate_deliverydate_configuration_productwise_cutoff").css('display', 'none');
                    $("#deliverydate_deliverydate_configuration_productwise_cutoff").css('display', 'none');

                    $("#row_deliverydate_deliverydate_configuration_end_same_day_delivery").css('display', 'none');
                    $("#deliverydate_deliverydate_configuration_end_same_day_delivery").css('display', 'none');

                    $("#row_deliverydate_deliverydate_configuration_cut_offtime").css('display', 'none');
                    jQuery("#row_deliverydate_deliverydate_configuration_cut_offtime > td ").find("select").each(function () {
                        jQuery(this).css('display', 'none');
                    });
                }

            });
            $(document).ready(function () {
                setTimeout(function () {
                    $("#deliverydate_deliverydate_configuration_deliverytime_day_diff").trigger('blur');
                }, 1000);
            });
        });
