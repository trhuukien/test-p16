define(
        [
            'jquery',
            'Magento_Checkout/js/view/summary/abstract-total',
            'Magento_Checkout/js/model/quote',
            "Magento_Checkout/js/model/totals",
        ],
        function ($, Component, quote, totals) {
            "use strict";
            return Component.extend({
                defaults: {
                    template: 'Biztech_Deliverydate/checkout/summary/deliverycharges'
                },
                isDeliveryChargesDisplayed: function () {

//                    var price = $('#delivery_charges').val();
//                    if (price == null || price == '') {
//                        $.each(quote.getTotals()._latestValue.total_segments, function (index, val) {
//                            if (val.code == 'delivery_charges') {
//                                price = val.value;
//                            }
//                        });
//                    }
//                    return ((window.deliverydateConfig.general.enabled && price != 0 && price != null) || (price != 0 && window.deliverydateConfig.general.applyAdditionalCharge && price != null));
                    var price = 0;
                    if (this.totals() && totals.getSegment("delivery_charges")) {
                        price = totals.getSegment("delivery_charges").value;
                    }
                    return (
                            ((price != 0 && window.deliverydateConfig.general.enabled && price != null) ||
                                    (price != 0 && window.deliverydateConfig.general.applyAdditionalCharge && price != null)
                                    )
                            );
                },
                getDeliveryCharges: function () {
//                    var price = $('#delivery_charges').val();
//                    if (price == null || price == '') {
//                        $.each(quote.getTotals()._latestValue.total_segments, function (index, val) {
//                            if (val.code == 'delivery_charges') {
//                                price = val.value;
//                            }
//                        });
//                    }
                    var price = 0;
                    if (this.totals() && totals.getSegment("delivery_charges")) {
                        price = totals.getSegment("delivery_charges").value;
                    }
                    return this.getFormattedPrice(price);
                },
                totals: quote.getTotals(),
            });
        }
);