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

namespace Anowave\Ec\Model;

if (version_compare(phpversion(), '8.0.0', '<'))
{
    class Dom extends \DOMDocument
    {
    	/**
    	 * Creates a new DOMDocument object
    	 * 
    	 * @param $version [optional]
    	 * @param $encoding [optional]
    	 */
    	public function __construct($version = null, $encoding = null)
    	{
    		parent::__construct($version, $encoding);
    	}
    	
    	/**
    	 * Load HTML
    	 * 
    	 * {@inheritDoc}
    	 * @see DOMDocument::loadHTML()
    	 */
    	public function loadHTML($source, $options = null)
    	{
    	    /**
    	     * Convert encoding
    	     */
    	    $source = mb_convert_encoding($source, 'HTML-ENTITIES', 'UTF-8');
    	    
    	    /**
    	     * Enable extenral errors
    	     */
    		if (function_exists('libxml_use_internal_errors'))
    		{
    			libxml_use_internal_errors(true);
    		}
    
    		if (empty($source))
    		{
    			return $source;
    		}
    		
    		return parent::loadHTML($source, $options);
    	}
    	
    	/**
    	 * Save HTML
    	 *
    	 * {@inheritDoc}
    	 * @see DOMDocument::saveHTML()
    	 */
    	public function saveHTML(\DOMNode $node = null) : string
    	{
    	    return html_entity_decode(parent::saveHTML(), ENT_COMPAT, 'UTF-8');
    	}
    }
}
else
{
    class Dom extends \DOMDocument
    {
        /**
         * Convert map 
         * 
         * @var array
         */
        private $map = [0x80, 0x10FFFF, 0, ~0];        
        
        /**
         * Creates a new DOMDocument object
         *
         * @param $version [optional]
         * @param $encoding [optional]
         */
        public function __construct($version = null, $encoding = null)
        {
            parent::__construct($version, $encoding);
        }
        
        /**
         * Load HTML
         *
         * {@inheritDoc}
         * @see DOMDocument::loadHTML()
         * 
         * 
         */
        #[\ReturnTypeWillChange]
        public function loadHTML($source, int $options = 0) 
        {
            /**
             * Encode to UTF-8
             */
            $source = $this->utf($source);
            
            /**
             * Enable extenral errors
             */
            if (function_exists('libxml_use_internal_errors'))
            {
                libxml_use_internal_errors(true);
            }
            
            if (empty($source))
            {
                return $source;
            }
            
            return parent::loadHTML($source, $options);
        }
        
        /**
         * Save HTML 
         * 
         * {@inheritDoc}
         * @see DOMDocument::saveHTML()
         */
        public function saveHTML(\DOMNode $node = null) : string
        {
            return html_entity_decode(parent::saveHTML(), ENT_COMPAT, 'UTF-8');
        }
        
        /**
         * Encode string 
         * 
         * @param string $string
         * @return string
         */
        public function utf($source) : string
        {
            if (version_compare(phpversion(), '8.2.0', '<'))
            {
                return mb_convert_encoding($source, 'HTML-ENTITIES', 'utf-8');
            }

            return htmlspecialchars_decode(mb_encode_numericentity( htmlentities( $source, ENT_COMPAT, 'UTF-8' ), $this->map, 'UTF-8' ));
        }
    }
}