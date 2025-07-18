<?php

namespace Biztech\Deliverydatepro\Block\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Category;
use Biztech\Deliverydate\Block\Deliverydate;
use Magento\Framework\Locale\Resolver;


class View extends \Magento\Catalog\Block\Product\View
{

    /**
     * @var Biztech\Deliverydate\Helper/Data;
     */
    const GENERAL_LOCALE_CODE = "general/locale/code";
    public $deliveryDateHelper;
    public $basicHelper;
    public $customerSession;
    protected $deliverydate;
    private $localeResolver;
    protected $dictionary;
    protected $storeManager;
    protected $scopeConfig;
    protected $_scopeConfig;

    /**
     * @param \Magento\Catalog\Block\Product\Context              $context
     * @param \Magento\Framework\Url\EncoderInterface             $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface            $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils               $string
     * @param \Magento\Catalog\Helper\Product                     $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface           $localeFormat
     * @param \Magento\Customer\Model\Session                     $customerSession
     * @param ProductRepositoryInterface                          $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface   $priceCurrency
     * @param \Biztech\Deliverydate\Helper\Data                   $helper
     * @param array                                               $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Biztech\Deliverydatepro\Helper\Data $helper,
        \Biztech\Deliverydate\Helper\Data $basicHelper,
        Deliverydate $deliverydate,
        Resolver $localeResolver,
        \Magento\Framework\App\Language\Dictionary $dictionary,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->deliveryDateHelper = $helper;
        $this->basicHelper = $basicHelper;
        $this->deliverydate = $deliverydate;
        $this->localeResolver = $localeResolver;
        $this->dictionary = $dictionary;
        $this->storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    public function getDeliveryDateConfig()
    {
        $expectedDeliveryDays = $this->deliveryDateHelper->getProductLevelConfig();
        return $expectedDeliveryDays;
    }

    public function isEnableAtProductPage()
    {
        $isCustomerApplicable = $this->deliveryDateHelper->
        checkCurrentUserAllowed($this->customerSession->getCustomer()->getGroupId());
        $isAppliableWithCategory = $this->deliveryDateHelper->
        isAppliableWithCategory($this->getProduct());
        return ($isAppliableWithCategory && $isCustomerApplicable && $this->basicHelper->
        isEnableAtProductPage() && $this->getProduct()->getTypeId() !== \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL && $this->getProduct()->getTypeId() !== 'downloadable');
    }

    public function isEnableAtCheckoutPage()
    {
        $display = false;
        $isCustomerApplicable = $this->deliveryDateHelper->
        checkCurrentUserAllowed($this->customerSession->getCustomer()->getGroupId());
        if ($this->basicHelper->isEnable() && $this->basicHelper->
        getOnWhichPage() == 1 && $isCustomerApplicable) {
            $display = true;
        }
        return $display;


    }

    public function deliveryDateFormat()
    {
        return $this->deliverydate->getDeliveryDateFormat();
    }

    public function getCurrentLocale()
    {
        $currentLocaleCode = $this->localeResolver->getLocale(); // fr_CA
        $languageCode = strstr($currentLocaleCode, '_', true);
        return $languageCode;
    }

    public function getTranslatedDate($deliveryDate)
	{
        $locale = $this->localeResolver->getLocale(); // e.
        $timestamp = strtotime($deliveryDate);  // Adjust if your format differs

        // Create an IntlDateFormatter for the current locale
        $formatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::FULL, // Full format for day and month names
            \IntlDateFormatter::NONE, // No time formatting
            null,                     // Use the default timezone
            \IntlDateFormatter::GREGORIAN, // Use Gregorian calendar
            'EEEE d MMMM'  // Format: Full day name, day, full month name
        );

        // Return the date formatted into the locale language
        $transDate = $formatter->format($timestamp);
		return $transDate;
	}
    
    
}
