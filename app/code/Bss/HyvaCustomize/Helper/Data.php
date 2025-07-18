<?php
namespace Bss\HyvaCustomize\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Data extends AbstractHelper
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Constructor
     *
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->productRepository = $productRepository;
    }

    /**
     * Get all product IDs of a category sorted by position.
     *
     * @param Category $category
     * @return array
     */
    public function getCategoryProductIds($category)
    {
        return $category->getProductCollection()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSort('position', 'asc')
            ->getAllIds();
    }

    /**
     * Get the first category associated with a product.
     * If product->getCategory() returns null, fallback to category collection.
     *
     * @param Product $product
     * @return Category|null
     */
    private function getProductCategory(Product $product)
    {
        $category = $product->getCategory();
        if (!$category) {
            $categories = $product->getCategoryCollection();
            foreach ($categories as $cat) {
                return $cat;
            }
        }
        return $category;
    }

    /**
     * Get the previous product in the same category based on position.
     *
     * @param Product $product
     * @return Product|false
     */
    public function getPrevProduct(Product $product)
    {
        $category = $this->getProductCategory($product);
        if (!$category) {
            return false;
        }

        $productIds = $this->getCategoryProductIds($category);
        $position = array_search($product->getId(), $productIds);

        if ($position !== false && isset($productIds[$position - 1])) {
            try {
                return $this->productRepository->getById($productIds[$position - 1]);
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Get the next product in the same category based on position.
     *
     * @param Product $product
     * @return Product|false
     */
    public function getNextProduct(Product $product)
    {
        $category = $this->getProductCategory($product);
        if (!$category) {
            return false;
        }

        $productIds = $this->getCategoryProductIds($category);
        $position = array_search($product->getId(), $productIds);

        if ($position !== false && isset($productIds[$position + 1])) {
            try {
                return $this->productRepository->getById($productIds[$position + 1]);
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }
}
