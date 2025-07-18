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

namespace Anowave\Ec4\Plugin;

class Random
{
    /**
     * After get cart update event 
     * 
     * @param \Anowave\Ec\Helper\Data $helper
     * @param string $result
     * @return string
     */
    public function afterGetCID(\Anowave\Ec\Model\Random $random, $result)
    {
        return bin2hex(random_bytes(32));
    }
}