/**
 * Anowave Magento 2 Google Tag Manager Enhanced Ecommerce (UA) Tracking
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * https://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2024 Anowave (https://www.anowave.com/)
 * @license  	https://www.anowave.com/license-agreement/
 */

define(['mage/utils/wrapper', 'Magento_Checkout/js/model/quote'], function(wrapper, quote)
{
    'use strict';

    return function(paymentMethod) 
    {
        return wrapper.wrap(paymentMethod, function (originalAction, method) 
        {
        	if ('undefined' !== typeof dataLayer && 'undefined' !== typeof AEC && 'undefined' !== typeof AEC.Const && 'undefined' !== typeof AEC.Checkout.getData())	
        	{
	        	(function(dataLayer, paymentMethod)
	    		{
	    			/**
	        		 * Empty default payment method by default
	        		 */
	        		var method = '';
	        		
	        		if (paymentMethod && paymentMethod.hasOwnProperty('title'))
	        		{
	        			/**
	        			 * Set payment method
	        			 */
	        			method = paymentMethod.title;
	        		}
	        		else 
	        		{
	        			if (paymentMethod)
	        			{
		        			/**
		        			 * By default send payment method as code
		        			 */
		        			method = paymentMethod.method;
		        			
		        			/**
		        			 * Try to map payment method to user-friendly text representation
		        			 */
		        			if (paymentMethod.hasOwnProperty('method'))
		        			{
		        				let label = document.querySelectorAll('label[for="' + paymentMethod.method + '"]');
		    	        		
		    	        		if (label.length)
		    	        		{
		    	        			label.forEach(element => 
		    	        			{
		    	        				let span = element.querySelector('span[data-bind="text: getTitle()"]');
		    	        				
		    	        				if (span)
		    	        				{
		    	        					method = span.innerHTML;
		    	        				}
		    	        			})
		    	        		}
		        			}
	        			}
	        		}

	        		if ('undefined' !== typeof AEC.Const.CHECKOUT_STEP_PAYMENT)
	        		{
	        			AEC.Checkout.stepOption(AEC.Const.CHECKOUT_STEP_PAYMENT, method);
	        		}
	        		
	        		if ("undefined" !== typeof fbq)
	        		{
	        			var content_ids = [], content_length = AEC.Checkout.getPayload().ecommerce.items.length;

	        			for (var i = 0, l = AEC.Checkout.getPayload().ecommerce.items.length; i < l; i++)
	        			{
	        				content_ids.push(AEC.Checkout.getPayload().ecommerce.items[i].item_id);
	        			}
	        			
	        			(function(callback)
	        			{
	        				if (AEC.Const.COOKIE_DIRECTIVE)
	        				{
	        					AEC.CookieConsent.queue(callback).process();
	        				}
	        				else 
	        				{
	        					callback.apply(window,[]);
	        				}
	        			})
	        			(
	        				(function(content_ids, content_length)
	        				{
	        					return function()
	        					{
	        						fbq("track", "AddPaymentInfo", 
	        						{
	        							value:			AEC.Checkout.getData().total,
	        							content_name: 	'checkout',
	        							content_ids:	content_ids,
	        							num_items:		content_length,
	        							currency: 		AEC.currencyCode,
	        							content_type:	(content_ids.length > 1) ? 'product group' : 'product'
	        						}, 
	        						{ eventID: AEC.UUID.generate({ event: 'AddPaymentInfo'}) });
	        					}
	        				})(content_ids,content_length)
	        			);
	        		}
	
	    		})(dataLayer, method);
        	}
        	
            return originalAction(method);
        });
    };
});