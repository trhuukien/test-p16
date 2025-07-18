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

define(['uiComponent','Magento_Customer/js/customer-data'], function (Component, customerData) 
{
    'use strict';

    return target => 
    {
        return target.extend(
        {
        	initialize: function () 
        	{
                this._super();
                
                this.wishlist().items.forEach(item => 
                {
                	let items = 
                	[
                		{
                			item_id: 	item.product_sku,
                			item_name: 	item.product_name,
                			quantity: 	1
            			}
                	];
                	
                	let params = JSON.parse(item.delete_item_params);
                	
                	params['dataLayer'] = 
                    {
                		event: 'remove_from_wishlist',
                		items: items
                    }
                	
                	item.delete_item_params = JSON.stringify(params);
                });
                
                setTimeout(() => 
                {
                	document.querySelectorAll('[id=wishlist-sidebar] .delete').forEach(icon => 
            		{
            			icon.addEventListener('click', event => 
            			{
            				if (event.target.dataset)
                			{
                				let post = JSON.parse(event.target.dataset.post);

                				if (post.hasOwnProperty('dataLayer'))
                				{
                					if ('undefined' !== typeof AEC)
                					{
                						AEC.Cookie.wishlistRemove(
        								{
        									event: post.dataLayer.event,
        									ecommerce: 
        									{
        										items: post.dataLayer.items
        									}
        								}).push(dataLayer);
                					}
                					
                				}
                				
                			}
            			});
            		});
                	
                },1000);
            }
        });
    };
});