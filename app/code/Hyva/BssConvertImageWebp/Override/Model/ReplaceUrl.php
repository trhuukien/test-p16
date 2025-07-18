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
 * @copyright  Copyright (c) 2024-2024 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Hyva\BssConvertImageWebp\Override\Model;

class ReplaceUrl extends \Bss\ConvertImageWebp\Model\ReplaceUrl
{
    /**
     * Handle replace url webp in category page
     *
     * @param string $url
     * @param string|int|null $idCategory
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException|\Magento\Framework\Exception\NoSuchEntityException
     */
    public function handleCategoryPage($url, $idCategory = null)
    {
        if ($this->config->isEnableCategoryPage()) {
//            $idCategory = $idCategory ?: $this->request->getParam("id");
            if (array_key_exists('cat', $this->request->getParams())) {
                $idCategory = $idCategory ?: $this->request->getParam("cat");
            } else {
                $idCategory = $idCategory ?: $this->request->getParam("id");
            }
            $ignoreIdsCategory = $this->config->getIgnoreCategoryIds();
            $ignoreNameImage = $this->checkIgnoreNameImage($this->config->getIgnoreImagesCategory(), $url);
            if (($idCategory && $ignoreIdsCategory && in_array($idCategory, $ignoreIdsCategory)) || $ignoreNameImage) {
                return $url;
            }
            return $this->convert->convertUrlFolder($url);
        }
        return $url;
    }
}
