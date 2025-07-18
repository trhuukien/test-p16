<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Hyva_BssConvertImageWebp
 * @author     Extension Team
 * @copyright  Copyright (c) 2022-2023 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Hyva\BssConvertImageWebp\Override\Plugin;

use Bss\ConvertImageWebp\Model\Config;
use Bss\ConvertImageWebp\Model\Convert;
use Bss\ConvertImageWebp\Model\ReplaceUrl;
use Magento\Store\Model\StoreManagerInterface;

class Block extends \Bss\ConvertImageWebp\Plugin\Block
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Convert
     */
    protected $convert;

    /**
     * @var ReplaceUrl
     */
    protected $replaceUrl;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Block constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Convert $convert
     * @param ReplaceUrl $replaceUrl
     * @param Config $config
     */
    public function __construct(StoreManagerInterface $storeManager, Convert $convert, ReplaceUrl $replaceUrl, Config $config)
    {
        parent::__construct($storeManager, $convert, $replaceUrl, $config);
    }
    /**
     * Replace url image to url image webp
     *
     * @param \Magento\Framework\View\Element\AbstractBlock $block
     * @param string $html
     * @return array|string|string[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterToHtml(\Magento\Framework\View\Element\AbstractBlock $block, $html)
    {
        $currentFullAction = $block->getRequest()->getFullActionName();

<<<<<<< HEAD
        if ($currentFullAction == 'cms_page_view') {
=======
        if ($currentFullAction == 'cms_page_view' || $currentFullAction =='cms_index_index') {
>>>>>>> a4d3a9c (bm)
            return parent::afterToHtml($block, $html);
        } else {
            if ($html!= null && strpos($html, "pagebuilder") !== false || // Check img in page-builder.
                ($html!= null && $currentFullAction == 'catalog_product_view' && strpos($html, "wysiwyg") !== false) // Check img in product page and it's insert image in BE
            ) {
<<<<<<< HEAD
                return parent::afterToHtml($block, $html);
=======
                // return parent::afterToHtml($block, $html);
>>>>>>> a4d3a9c (bm)
            } else {
                return $html;
            }
        }
    }
}
