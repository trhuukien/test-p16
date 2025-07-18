<?php
/**
 * Anowave Magento 2 Google Tag Manager Enhanced Ecommerce (UA) Tracking
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * http://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2024 Anowave (http://www.anowave.com/)
 * @license  	http://www.anowave.com/license-agreement/
 */

namespace Anowave\Ec4\Plugin\Api\Measurement;

use Magento\Framework\App\Response\Http;

class Protocol
{
    /**
     * @var \Anowave\Ec4\Helper\Data
     */
    protected $helper = null;
    
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager = null;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;
    
    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;
    
    /**
     * @var \Anowave\Ec\Logger\Logger
     */
    protected $logger;
    
    /**
     * Put in debug mode
     * 
     * @var string
     */
    private $debug = false;
    
    /**
     * Constructor 
     * 
     * @param \Anowave\Ec4\Helper\Data $helper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Anowave\Ec\Logger\Logger $logger
     */
    public function __construct
    (
        \Anowave\Ec4\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\State $state,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Anowave\Ec\Logger\Logger $logger
    )
    {
        /**
         * Set helper
         *
         * @var \Anowave\Ec4\Helper\Data $_helper
         */
        $this->helper = $helper;
        
        /**
         * Set message manager
         *
         * @var \Magento\Framework\Message\ManagerInterface $_messageManager
         */
        $this->messageManager = $messageManager;
        
        /**
         * Set scope config 
         * 
         * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
         */
        $this->scopeConfig = $scopeConfig;
        
        /**
         * Set state
         * 
         * @var \Magento\Framework\App\State $state
         */
        $this->state = $state;
        
        /**
         * 
         * @var \Magento\Sales\Model\OrderFactory $orderFactory
         */
        $this->orderFactory = $orderFactory;
        
        /**
         * Set logger 
         * 
         * @var \Anowave\Ec4\Plugin\Api\Measurement\Protocol $logger
         */
        $this->logger = $logger;
    }
    
    /**
     * After purchase 
     * 
     * @param \Anowave\Ec\Model\Api\Measurement\Protocol $interceptor
     * @param callable $proceed
     * @param \Magento\Sales\Model\Order $order
     * @param string $reverse
     * @return array
     */
    public function aroundPurchase(\Anowave\Ec\Model\Api\Measurement\Protocol $interceptor, callable $proceed, \Magento\Sales\Model\Order $order, $reverse = false)
    { 
        /**
         * Set measurement id
         * 
         * @var string $measurement_id
         */
        $measurement_id = $this->getOrderMeasurementId($order);
        
        /**
         * Set measurement API secret 
         * 
         * @var string $measurement_api_secret
         */
        $measurement_api_secret = $this->getOrderMeasurementApiSecret($order);
        
        /**
         * Get client id 
         * 
         * @var Ambiguous $cid
         */
        $cid = $interceptor->getCID();
        
        $payload = function(array $events = []) use ($measurement_id, $measurement_api_secret, $reverse, $cid)
        {
            return
            [
                'client_id' => $cid,
                'events'    => $events
            ];
        };
        
        
        if ($measurement_id && $measurement_api_secret)
        {
            /**
             * Get Google Session ID
             * 
             * @var Ambiguous $gsid
             */
            $gsid = $interceptor->getGSID($measurement_id);
            
            
            /**
             * Items
             * 
             * @var array $items
             */
            $items = [];
            
            /**
             * Default start position
             *
             * @var int
             */
            $index = 1;
            
            /**
             * List name
             * 
             * @var \Magento\Framework\Phrase $list
             */
            $list = __('Admin orders');
            
            /**
             * Loop products
             */
            foreach ($interceptor->getProducts($order) as $product)
            {
                if (isset($product['list']))
                {
                    $list = $product['list'];
                }
                
                $item = 
                [
                    'index'             =>          $index,
                    'item_id'           => (string) @$product['id'],
                    'item_list_id'      => (string)  $list,
                    'item_list_name'    => (string)  $list,
                    'item_name'         => (string) @$product['name'],
                    'item_brand'        => (string) @$product['brand'],
                    'price'             => (float)  @$product['price'],
                    'quantity'          => (int)    @$product['quantity']
                ];

                if (isset($product['variant']))
                {
                    $item['item_variant'] = $product['variant'];
                }
                
                /**
                 * Check if reverse and reverse quantity
                 */
                
                if ($reverse)
                {
                    $item['quantity'] *= -1;
                    $item['price']    *= -1;
                }
                
                $categories = explode(chr(47), (string) @$product['category']);
                
                if ($categories)
                {
                    $category = array_shift($categories);
                    
                    if ($category)
                    {
                        $item['item_category'] = $category;
                    }
                    
                    foreach ($categories as $index => $category)
                    {
                        $key = $index + 2;
                        
                        $item["item_category{$index}"] = $category;
                    }
                }
                
                
                $items[] = $item;
                
                $index++;
            }

            $data = $payload
            (
                [
                    [
                        'name' => 'purchase',
                        'params' => 
                        [
                            'currency'       =>         $this->helper->getBaseHelper()->getCurrency(),
                            'transaction_id' =>         $order->getIncrementId(),
                            'value'          =>         $this->helper->getBaseHelper()->getRevenue($order),
                            'shipping'	     => (float) $order->getShippingInclTax(),
                            'tax'	         => (float) $order->getTaxAmount(),
                            'affiliation'    =>         $this->helper->getBaseHelper()->escape
                            (
                                $order->getStore()->getName()
                            ),
                            'items' => $items,
                            'traffic_type' => $this->state->getAreaCode()
                        ]
                    ]
                ]
            );
            
            /**
             * Set Google Session ID
             */
            $data['events'][0]['params']['session_id'] = $gsid;

            if ($reverse)
            {
                $data['events'][0]['params']['value']    *= -1;
                $data['events'][0]['params']['shipping'] *= -1;
                $data['events'][0]['params']['tax']      *= -1;
            }
            
            $analytics = curl_init
            (
                $this->getGateway($measurement_id, $measurement_api_secret)
            );
            
            curl_setopt($analytics, CURLOPT_HEADER, 		0);
            curl_setopt($analytics, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($analytics, CURLOPT_POST, 			1);
            curl_setopt($analytics, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($analytics, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($analytics, CURLOPT_USERAGENT,		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            curl_setopt($analytics, CURLOPT_POSTFIELDS,     mb_convert_encoding(json_encode($data, JSON_UNESCAPED_UNICODE), 'UTF-8', mb_list_encodings()));
            
            try
            {
                $response = curl_exec($analytics);
                
                if ($this->helper->getBaseHelper()->useDebugMode())
                {
                    $this->messageManager->addNoticeMessage(json_encode($data, JSON_PRETTY_PRINT));
                }
                
                if (!curl_error($analytics))
                {
                    $this->logger->info("Tracked {$order->getIncrementId()} transaction to {$measurement_id} successfully.");
                    
                    return $interceptor;
                }
            }
            catch (\Exception $e) 
            {
                $this->logger->info($e->getMessage());
            }
        }
        
        return $proceed($order, $reverse);
    }
    
    /**
     * Around fallback 
     * 
     * @param \Anowave\Ec\Model\Api\Measurement\Protocol $interceptor
     * @param callable $proceed
     * @param array $params
     * @return \Anowave\Ec\Model\Api\Measurement\Protocol|unknown
     */
    public function aroundFallback(\Anowave\Ec\Model\Api\Measurement\Protocol $interceptor, callable $proceed, array $params = [])
    {
        if (isset($params['data']) && is_array($params['data']) && count($params['data']) > 0)
        {
            $data = reset($params['data']);
            
            switch ($data['event'])
            {
                case \Anowave\Ec4\Model\Api::GA4_EVENT_VIEW_ITEM:
                    
                    return $this->track($interceptor, \Anowave\Ec4\Model\Api::GA4_EVENT_VIEW_ITEM, $data['ecommerce']);
                    
                case \Anowave\Ec4\Model\Api::GA4_EVENT_VIEW_ITEM_LIST:
                    
                    return $this->track($interceptor, \Anowave\Ec4\Model\Api::GA4_EVENT_VIEW_ITEM_LIST, $data['ecommerce']);
                    
                    break;
                    
                case \Anowave\Ec4\Model\Api::GA4_EVENT_PURCHASE:
                    
                    $order = $this->orderFactory->create()->loadByIncrementId($data['ecommerce']['purchase']['transaction_id']);
                    
                    if ($order && $order->getId())
                    {
                        return $interceptor->purchase($order);
                    }
                    
                    break;
                    
            }
        }
        
        return $proceed($params);
    }
    
    /**
     * Track event 
     * 
     * @param string $event
     * @param array $payload
     */
    public function track(\Anowave\Ec\Model\Api\Measurement\Protocol $interceptor, string $event = '', array $params = [])
    {
        $measurement_id         = $this->getOrderMeasurementId();
        $measurement_api_secret = $this->getOrderMeasurementApiSecret();
        
        /**
         * Get client id
         *
         * @var Ambiguous $cid
         */
        $cid = $interceptor->getCID();
        
        $payload = function(array $events = []) use ($measurement_id, $measurement_api_secret, $cid)
        {
            return
            [
                'client_id' => $cid,
                'events'    => $events
            ];
        };
        
        if (!$params || !isset($params['items']))
        {
            $this->logger->info('Missing items[] array from parameters for event: ' . $event);
            
            return false;
        }
        
        if ($measurement_id && $measurement_api_secret)
        {
            $args = 
            [
                'name' => $event,
                'params' =>
                [
                    'items'    => $params['items']
                ]
            ];
            
            if (isset($params['currency']))
            {
                $args['params']['currency'] = $params['currency'];
            }
            
            if (isset($params['value']))
            {
                $args['params']['value'] = $params['value'];
            }
            
            $data = $payload([$args]);
            
            $analytics = curl_init
            (
                $this->getGateway($measurement_id, $measurement_api_secret)
            );
            
            curl_setopt($analytics, CURLOPT_HEADER, 		0);
            curl_setopt($analytics, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($analytics, CURLOPT_POST, 			1);
            curl_setopt($analytics, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($analytics, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($analytics, CURLOPT_USERAGENT,		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            curl_setopt($analytics, CURLOPT_POSTFIELDS,     mb_convert_encoding(json_encode($data, JSON_UNESCAPED_UNICODE), 'UTF-8', mb_list_encodings()));
            
            try
            {
                $response = curl_exec($analytics);
                
                if ($this->debug)
                {
                    $this->logger->info($response);
                }
                
                if ($this->helper->getBaseHelper()->useDebugMode())
                {
                    $this->messageManager->addNoticeMessage(json_encode($data, JSON_PRETTY_PRINT));
                }

                if (!curl_error($analytics) && $response)
                {
                    return true;
                }
            }
            catch (\Exception $e) 
            {
                $this->logger->info($e->getMessage());
            }
        }
        
        return false;
    }
    
    /**
     * Get Measurement ID
     *
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    public function afterGetUA(\Anowave\Ec\Model\Api\Measurement\Protocol $interceptor, $result, \Magento\Sales\Model\Order $order = null)
    {
        $measurement_id = null;
        
        if ($order && $order->getId())
        {
            return trim
            (
                (string) $this->scopeConfig->getValue($this->helper->getMeasurementIdConfig(), \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStore())
            );
        }
        
        return trim
        (
            (string) $this->helper->getConfig($this->helper->getMeasurementIdConfig())
        );
    }

    /**
     * Get measurement ID from order
     *
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    protected function getOrderMeasurementId(\Magento\Sales\Model\Order $order = null) : string
    {
        if ($order)
        {
            return trim
            (
                (string) $this->scopeConfig->getValue($this->helper->getMeasurementIdConfig(), \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStore())
            );
        }
        
        return trim
        (
            (string) $this->helper->getConfig($this->helper->getMeasurementIdConfig(), \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
        );
    }
    
    /**
     * Get measurement secret from order
     *
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    protected function getOrderMeasurementApiSecret(\Magento\Sales\Model\Order $order = null) : string
    {
        if ($order)
        {
            return trim
            (
                (string) $this->scopeConfig->getValue('ec/api/google_gtm_ua4_measurement_api_secret', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $order->getStore())
            );
        }
        
        return trim
        (
            (string) $this->scopeConfig->getValue('ec/api/google_gtm_ua4_measurement_api_secret', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
        );
    }
    
    /**
     * Get measurement protocol gateway 
     * 
     * @param sring $measurement_id
     * @param string $measurement_api_secret
     * @return string
     */
    private function getGateway(string $measurement_id = '', string $measurement_api_secret = '') : string
    {
        return sprintf("https://www.google-analytics.com/%smp/collect?measurement_id=%s&api_secret=%s", ($this->debug ? 'debug/' : ''), $measurement_id, $measurement_api_secret);
    }
}