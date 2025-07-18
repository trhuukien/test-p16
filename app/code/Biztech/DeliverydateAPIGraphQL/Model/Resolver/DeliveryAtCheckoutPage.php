<?php

declare(strict_types = 1);

namespace Biztech\DeliverydateAPIGraphQL\Model\Resolver;

use Biztech\Deliverydate\Helper\Data as DeliveryHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Biztech\Deliverydatepro\Helper\Data as proHelper;

/**
 * DeliveryDate resolver for specific product, used for GraphQL request processing
 */
class DeliveryAtCheckoutPage implements ResolverInterface {

    /**
     * @var DeliveryHelper
     */
    private $deliveryHelper;

    /**
     * @param \Magento\Customer\Model\Customer
     */
    private $customer;

    /**
     *
     * @param deliveryHelper $deliveryHelper
     */
    public function __construct(
    DeliveryHelper $deliveryHelper, \Magento\Customer\Model\CustomerFactory $customer, proHelper $proHelper
    ) {
        $this->deliveryHelper = $deliveryHelper;
        $this->proHelper = $proHelper;
        $this->customer = $customer;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
    Field $field, $context, ResolveInfo $info, array $value = null, array $args = null
    ) {

        try {
            $userId = $context->getUserId();
            if ($userId) {
                $groupId = $this->customer->create()->load($userId)->getGroupId();
            } else {
                $groupId = 0;
            }

            $isCustomerApplicable = $this->proHelper->checkCurrentUserAllowed($groupId);

            $enable = ($isCustomerApplicable && (!$this->deliveryHelper->isEnableAtProductPage()));
            $deliveryDateInformation = $this->proHelper->getProductLevelConfig();
            $deliveryDateInformation['templateConfig']['useTemplate'] = $this->deliveryHelper->getConfigValue('deliverydate/deliverydate_front_config/delivery_method') == 1 ? 'calender' : 'timeslot';
            $deliveryDateInformation['general']['enabled'] = ($enable && $this->deliveryHelper->isEnable());
            return $deliveryDateInformation;
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
    }

}
