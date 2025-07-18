var config = {
    "map": {
        "*": {
            "Magento_Checkout/js/model/shipping-save-processor/default": "Biztech_Deliverydate/js/shipping-save-processor-default-override",
            "Magento_Checkout/js/proceed-to-checkout": "Biztech_Deliverydate/js/proceed-to-checkout",
            'calendar': 'mage/calendar'
        }
    },
    'paths': {
        'mousewheel': 'Biztech_Deliverydate/js/jquery.mousewheel.min',
        'slick': 'Biztech_Deliverydate/js/slickslider/slick.min'
    },
    'shim': {
        'mousewheel': {
            exports: 'mousewheel',
            'deps': ['jquery']
        },
        slick: {
            deps: ['jquery']
        }
    }
};