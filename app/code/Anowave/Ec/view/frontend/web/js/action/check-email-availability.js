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

define(['mage/utils/wrapper'], function(wrapper)
{
    'use strict';

    return function(checkEmailAvailability) 
    {
        return wrapper.wrap(checkEmailAvailability, function (originalAction, deferred, email) 
        {
        	if ('undefined' !== typeof dataLayer)
        	{
        		dataLayer.push(
        		{
        			event: 'checkEmailAvailability'
        		});
        	}
        	
            return originalAction(deferred, email);
        });
    };
});