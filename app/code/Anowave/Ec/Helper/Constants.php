<?php
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

namespace Anowave\Ec\Helper;

use Anowave\Package\Helper\Package;

class Constants extends \Anowave\Package\Helper\Package
{
    /**
     * Begin checkout event
     * 
     * @var string
     */
    const EVENT_BEGIN_CHECKOUT = 'begin_checkout';
    /**
     * Add to cart event
     * 
     * @var string
     */
    const EVENT_ADD_TO_CART = 'add_to_cart';
    
    /**
     * Add to wishlist event
     * 
     * @var string
     */
    const EVENT_ADD_TO_WISHLIST = 'add_to_wishlist';
    
    /**
     * Add to compare event 
     * 
     * @var string
     */
    const EVENT_ADD_TO_COMPARE = 'add_to_compare';
    
    /**
     * Add to cart swatch 
     * 
     * @var string
     */
    const EVENT_ADD_TO_CART_SWATCH = 'add_to_cart_swatch';
    
    /**
     * Remove from cart event
     * 
     * @var string
     */
    const EVENT_REMOVE_FROM_CART = 'remove_from_cart';
    
    /**
     * Remove from cart event
     *
     * @var string
     */
    const EVENT_REMOVE_FROM_WISHLIST = 'remove_from_wishlist';
    
    /**
     * Product click event 
     * 
     * @var string
     */
    const EVENT_PRODUCT_CLICK = 'select_item';
    
    /**
     * Checkout event
     * 
     * @var string
     */
    const EVENT_CHECKOUT = 'checkout';
    
    /**
     * Checkout option event
     * 
     * @var string
     */
    const EVENT_CHECKOUT_OPTION = 'checkoutOption';
    
    /**
     * List grid mode event 
     * 
     * @var string
     */
    const EVENT_SWITCH_MODE = 'switchMode';
    
    /**
     * Promotion click event
     */
    const EVENT_PROMOTION_CLICK = 'promotionClick';
    
    /**
     * Promotion view event 
     * 
     * @var string
     */
    const EVENT_PROMOTION_VIEW_NON_INTERACTIVE = 'promoViewNonInteractive';
    
    /**
     * Widget view event
     * 
     * @var string
     */
    const EVENT_WIDGET_VIEW_NON_INTERACTIVE = 'widgetViewNonInteractive';
    
    /**
     * Remarketing tag event 
     * 
     * @var string
     */
    const EVENT_FIRE_REMARKETING_TAG = 'fireRemarketingTag';
    
    /**
     * Impression event 
     * 
     * @var string
     */
    const EVENT_IMPRESSION = 'impression';
    
    /**
     * Purchase event
     *
     * @var string
     */
    const EVENT_PURCHASE = 'purchase';
    
    /**
     * Conversion event
     *
     * @var string
     */
    const EVENT_CONVERSION = 'conversion';
    
    /**
     * View item event 
     * 
     * @var string
     */
    const EVENT_VIEW_ITEM = 'view_item';
    
    /**
     * Virtual variant view event
     *
     * @var string
     */
    const EVENT_VIRTUAL_VARIANT_VIEW = 'view_item';
    
    /**
     * Performance event
     *
     * @var string
     */
    const EVENT_PERFORMANCE = 'performance';
    
    /**
     * Social int event
     *
     * @var string
     */
    const EVENT_SOCIAL_INT = 'socialInt';
    
    /**
     * Track time event
     *
     * @var string
     */
    const EVENT_TRACK_TIME = 'trackTime';
    
    
	/**
	 * Checkout shipping step 
	 * 
	 * @var integer
	 */
	const CHECKOUT_STEP_SHIPPING = 1;
	
	/**
	 * Checkout payment step 
	 * 
	 * @var integer
	 */
	const CHECKOUT_STEP_PAYMENT  = 2;
	
	/**
	 * Checkout place order step 
	 * 
	 * @var integer
	 */
	const CHECKOUT_STEP_ORDER = 3;
	
	/**
	 * Checkout shipping step
	 *
	 * @var integer
	 */
	const MULTI_CHECKOUT_STEP_CART = 0;
	
	/**
	 * Checkout shipping step
	 *
	 * @var integer
	 */
	const MULTI_CHECKOUT_STEP_LOGIN = 1;
	
	/**
	 * Checkout shipping step
	 *
	 * @var integer
	 */
	const MULTI_CHECKOUT_STEP_ADDRESSES = 2;
	
	/**
	 * Checkout shipping step
	 *
	 * @var integer
	 */
	const MULTI_CHECKOUT_STEP_SHIPPING = 3;
	
	/**
	 * Checkout shipping step
	 *
	 * @var integer
	 */
	const MULTI_CHECKOUT_STEP_BILLING = 4;
	
	/**
	 * Checkout shipping step
	 *
	 * @var integer
	 */
	const MULTI_CHECKOUT_STEP_OVERVIEW = 5;
	
	/*
	 * Related products list name/category
	 */
	const LIST_RELATED 	= 'Related products';
	
	/*
	 * Cross sells list name/category
	 */
	const LIST_CROSS_SELL = 'Cross Sells';
	
	/*
	 * Upsells products list name/category
	 */
	const LIST_UP_SELL 	= 'Up Sells';
	
	/**
	 * Wishlist category
	 * 
	 * @var string
	 */
	const LIST_WISHLIST = 'Wishlist';
	
	/**
	 * Impression item selector
	 * 
	 * @var string
	 */
	const XPATH_LIST_SELECTOR = '//ol[contains(@class, "products")]/li';
	
	/**
	 * Impression item click selector 
	 * 
	 * @var string
	 */
	const XPATH_LIST_CLICK_SELECTOR = 'div/a';
	
	/**
	 * Impression item wishlist selector
	 *
	 * @var string
	 */
	const XPATH_LIST_WISHLIST_SELECTOR = 'div/a';
	
	/**
	 * Impression item add to compare selector
	 *
	 * @var string
	 */
	const XPATH_LIST_COMPARE_SELECTOR = 'div/a';
	
	/**
	 * Cross list impression item selector
	 *
	 * @var string
	 */
	const XPATH_LIST_CROSS_SELECTOR = '//div[contains(@class,"product-items")]/div';
	
	/**
	 * Add to cart button selector
	 * 
	 * @var string
	 */
	const XPATH_CART_SELECTOR = '//button[@id="product-addtocart-button"]';
	
	/**
	 * Add to cart button from categories selector
	 * 
	 * @var string
	 */
	const XPATH_CART_CATEGORY_SELECTOR 	= 'div/div/div/div/div/form/button[contains(@class,"tocart")]';
	
	/**
	 * Remove from cart selector
	 * 
	 * @var string
	 */
	const XPATH_CART_DELETE_SELECTOR = '//a[contains(@class,"action-delete")]|//a[contains(@class,"remove")]';
	
	/**
	 * Widget selector
	 *
	 * @var string
	 */
	const XPATH_LIST_WIDGET_SELECTOR = '//ol[contains(@class,"product-items")]/li';
	
	/**
	 * Widget click selector
	 *
	 * @var string
	 */
	const XPATH_LIST_WIDGET_CLICK_SELECTOR = 'div/a[contains(@class,"product-item-photo")]';
	
	/**
	 * Widget cart add selector
	 *
	 * @var string
	 */
	const XPATH_LIST_WIDGET_CART_SELECTOR = 'div/div/div/div/button[contains(@class,"tocart")]';
	
	/**
	 * Add to wishlist selector
	 * 
	 * @var string
	 */
	const XPATH_ADD_WISHLIST_SELECTOR = '//a[contains(@class,"towishlist")]';
	
	/**
	 * Add to wishlist selector
	 *
	 * @var string
	 */
	const XPATH_ADD_WISHLIST_ITEMS_SELECTOR = '//ol[contains(@class,"product-items")]/li';
	
	/**
	 * Add to wishlist from customer wishlist
	 *
	 * @var string
	 */
	const XPATH_ADD_WISHLIST_ITEMS_ADD_SELECTOR = 'div/div/div/fieldset/div/div/button';
	
	/**
	 * Remove from custromer wishlist
	 *
	 * @var string
	 */
	const XPATH_ADD_WISHLIST_ITEMS_REMOVE_SELECTOR = 'div/div/div/fieldset/div/div/button';
	
	
	/**
	 * Add to wishlist selector
	 * 
	 * @var string
	 */
	const XPATH_ADD_COMPARE_SELECTOR = '//a[contains(@class,"tocompare")]';
	
	/**
	 * Internal search dimension index
	 * 
	 * @var integer
	 */
	const INTERNAL_SEARCH_DEFAULT_DIMENSION = 18;
	
	/**
	 * Internal stock dimension index
	 *
	 * @var integer
	 */
	const INTERNAL_STOCK_DEFAULT_DIMENSION = 10;
	
	/**
	 * Cookie consent granted event
	 * @var string
	 */
	const COOKIE_CONSENT_GRANTED_EVENT = 'cookieConsentGranted';
	
	/**
	 * Cookie consent decline event
	 * @var string
	 */
	const COOKIE_CONSENT_DECLINE_EVENT = 'cookieConsentDeclined';
	
	/**
	 * Cookie consent granted event
	 * @var string
	 */
	const COOKIE_CONSENT_MARKETING_GRANTED_EVENT = 'cookieConsentMarketingGranted';
	
	/**
	 * Cookie consent granted event
	 * @var string
	 */
	const COOKIE_CONSENT_PREFERENCES_GRANTED_EVENT = 'cookieConsentPreferencesGranted';
	
	/**
	 * Cookie consent granted event
	 * @var string
	 */
	const COOKIE_CONSENT_ANALYTICS_GRANTED_EVENT = 'cookieConsentAnalyticsGranted';
	
	/**
	 * Cookie consent ad user data
	 * 
	 * @var string
	 */
	const COOKIE_CONSENT_AD_USER_DATA_EVENT = 'cookieConsentUserdata';
	
	/**
	 * Cookie consent ad user data
	 *
	 * @var string
	 */
	const COOKIE_CONSENT_AD_PERSONALIZATION_EVENT = 'cookieConsentPersonalization';
	
	/**
	 * Cookie consent unique identifier cookie name
	 * 
	 * @var string
	 */
	const COOKIE_CONSENT_UUID = 'cookieUuid';
	
	/**
	 * Redirect to cart event (from categories)
	 * 
	 * @var string
	 */
	const CATALOG_CATEGORY_ADD_TO_CART_REDIRECT_EVENT = 'catalogCategoryAddToCartRedirect';
	
	
	
	/**
	 * Maximum Google Payload size 
	 * 
	 * @var integer
	 */
	const GOOGLE_PAYOAD_SIZE = 8192;
	
	/**
	 * Frontend type order 
	 * 
	 * @var integer
	 */
	const ORDER_TYPE_FRONTEND = 0;
	
	/**
	 * Backend type order
	 * @var integer
	 */
	const ORDER_TYPE_BACKEND = 1;
	
	/**
	 * Placed order flag 
	 * 
	 * @var integer
	 */
	const FLAG_PLACED = 0;
	
	/**
	 * Tracked order flag
	 * @var integer
	 */
	const FLAG_TRACKED = 1;
}