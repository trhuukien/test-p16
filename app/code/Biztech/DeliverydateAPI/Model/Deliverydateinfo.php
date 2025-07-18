<?php

namespace Biztech\DeliverydateAPI\Model;

use Biztech\Deliverydate\Helper\Data;

class Deliverydateinfo implements \Biztech\DeliverydateAPI\Api\DeliverydateinfoInterface {

    protected $helper;

    /**
     * @param \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @param \Magento\Framework\View\Asset\Repository
     */
    private $_assetRepo;

    public function __construct(
    Data $helper, \Magento\Customer\Model\Session $customerSession, \Magento\Framework\View\Asset\Repository $_assetRepo, \Biztech\Deliverydatepro\Helper\Data $proHelper
    ) {
        $this->helper = $helper;
        $this->customerSession = $customerSession;
        $this->_assetRepo = $_assetRepo;
        $this->proHelper = $proHelper;
    }

    public function getDeliveryDateInfo() {
        $enabledMethod = $this->getHelper()->getDeliveryDateView();
        $deliverydateProEnable = $this->getHelper()->checkModuleStatus('Biztech_Deliverydatepro');
        
        $off_days = [];
        $nonworking_days = $this->getHelper()->getFormattedNonWorkingDays();
        $nonworking_period = $this->getHelper()->getFormattedNonWorkingPeriod();
        $off_days = array_merge((array) $nonworking_days, (array) $nonworking_period);

        $dailyQuota = $this->getHelper()->disableSameDayBasedOnDailyQuota();

        if ($dailyQuota['disable'] === true) {
            $off_days = array_values(array_unique(array_merge($off_days, $dailyQuota['day_to_disable']), SORT_REGULAR));
        }

        $cutoff = $this->getHelper()->disableDayBasedOnCutoff();
        if ($cutoff['status'] === true) {
            $off_days = array_values(array_unique(array_merge($off_days, $cutoff['day_to_disable']), SORT_REGULAR));
        }

        switch ($enabledMethod) {
            case 1:
                $template = 'Biztech_Deliverydate/deliverydate/calendar';
                break;

            case 2:
                $template = 'Biztech_Deliverydate/deliverydate/timeslot';
                break;
            default:
                $template = 'Biztech_Deliverydate/deliverydate/calendar';
                break;
        }

        $deliverydateLabel = $this->getHelper()->getDeliverydatelabel() ? $this->getHelper()->getDeliverydatelabel() : 'Deliverydate Date';
        $deliverydateCommentsLabel = $this->getHelper()->getDeliveryDateCommentsLabel() ? $this->getHelper()->getDeliveryDateCommentsLabel() : 'Deliverydate Comments';
        $timeslotTableLabel = $this->getHelper()->getTableLabel();
        $timeslotTableLabel = $timeslotTableLabel ? $timeslotTableLabel : 'Timeslot\'s';
        $dayOffs = $this->getHelper()->getDayOffs();
        $dayDiff = $this->getHelper()->getDayDiff() ? $this->getHelper()->getDayDiff() : 0;
        $timeDiff = $this->getHelper()->getTimeDiff() ? $this->getHelper()->getTimeDiff() : 0;

        $timeslot = $this->getHelper()->getavailableTimeslot();
        $sameDayCharges = $this->getHelper()->getSameDayCharges();
        $enableTimeSlotForCalendar = $this->getHelper()->getEnableTimeslotMode();

        $config = [
            'templateConfig' => [
                'template' => $template,
                'enabledMethod' => $enabledMethod,
                'deliverydateLabel' => __($deliverydateLabel),
                'deliverydateComments' => __($deliverydateCommentsLabel),
                'showHtml' => (int) $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/show_html'),
                'displayHtml' => nl2br($this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/deliverydate_html')),
            ],
            'general' => [
                'enabled' => (int) $this->enableExtension(),
                'disabledDates' => $off_days,
                'dayOffs' => $dayOffs,
                'dateFormat' => $this->getHelper()->convertDateFormatToJQueryUi($this->getHelper()->getDeliveryDateFormat()),
                'timeFormat' => $this->getHelper()->convertDateFormatToJQueryUi($this->getHelper()->getDeliveryTimeFormat()),
                'dayDiff' => (int) $dayDiff,
                'timeDiff' => $timeDiff,
                'sameDayCharges' => $sameDayCharges,
                'sameDayChargesWithCurruncy' => $this->getHelper()->displaySamedaycharges(),
                'isMandatory' => (int) $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/is_mandatory'),
                'useCallFeature' => (int) $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/show_callme', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'callMeLabel' => $this->getHelper()->getDeliveryDateCallMeLabel(),
                'currencySymbol' => $this->getHelper()->getStoreCurrencySymbol(),
                'deliverydateProEnable' => $deliverydateProEnable,
                'add_comment' => $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/add_comment'),
                'comment_required' => $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/comment_required'),
                'applyAdditionalCharge' => $this->getHelper()->applyAdditionalCharge(),
                'isEnableAtProductPage' => $this->getHelper()->isEnableAtProductPage()
            ],
            'calendar' => [
                'options' => [
                    'showsTime' => (int) $this->getHelper()->getConfigValue('deliverydate/deliverydate_general/deliverytime_enable_time'),
                    'buttonImage' => $this->_assetRepo->getUrl("Biztech_Deliverydate::images/datepicker.png"),
                    'buttonText' => __('Select Date'),
                    'interval' => (int) $this->getHelper()->getConfigValue('deliverydate/deliverydate_general/datepicker_time_mininterval'),
                    'buttonText' => __('Select Date'),
                    'showAnim' => $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/datepicker_enable_animation'),
                    'showButtonPanel' => (boolean) $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/datepicker_buttom_bar'),
                    'isRTL' => (boolean) $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/datepicker_rtl'),
                    'maxDate' => (int) $this->getHelper()->getConfigValue('deliverydate/deliverydate_front_config/datepicker_delivery_intervals'),
                    'dateDisplayMode' => $this->getHelper()->getDateDisplayMode(),
                    'getavailableDays' => $this->getHelper()->getavailableDays(),
                    'enableTimeSlotForCalendar' => $enableTimeSlotForCalendar,
                    'timeDisplayMode' => $this->getHelper()->getTimeDisplayMode(),
                    'deliverydateSlotLabel' => $this->getHelper()->getDeliverydatetimeslotLabel(),
                    'imageUrl' => $this->_assetRepo->getUrl("Biztech_Deliverydate::images"),
                ]
            ],
            'timeslot' => [
                'enabled_timeslots' => $timeslot,
                'timeslotTableLabel' => __($timeslotTableLabel),
            ]
        ];

        if ($deliverydateProEnable == true) {
            $enableForShipping = $this->getHelper()->getConfigValue('deliverydate/deliverydate_configuration/enable_shipping');
            if ($enableForShipping == 1) {
                $config['general']['enableShippingMethods'] = $this->getHelper()->getConfigValue('deliverydate/deliverydate_configuration/shipping_method_option');
            }
            if ($this->getHelper()->objectManager()->get('\Biztech\Deliverydatepro\Helper\Data')->excludeHolidays() == 1) {
                $holiday = $this->getHelper()->objectManager()->get('\Biztech\Deliverydatepro\Helper\Data')->getEnableHolidays();
                if (isset($holiday['annualRepeatHoliday'])) {
                    $holidayArray = [];
                    foreach ($holiday['annualRepeatHoliday'] as $holiday) {
                        $date = strtotime($holiday);
                        //n means month without leading zero in two digits, j means date without leading zero (did as in js we get value without leading zero)
                        $holidayArray[] = date("j", $date) . '-' . date("n", $date);
                    }
                    $config['calendar']['options']['annualRepeatHoliday'] = $holidayArray;
                }
            }
            $config['general']['restrictCartAmount'] = $this->getHelper()->getConfigValue('deliverydate/deliverydate_configuration/disable_date_on_cart');
            $config['general']['cartAmount'] = $this->getHelper()->getConfigValue('deliverydate/deliverydate_configuration/cart_amount');
            $config['general']['per_week_day_charge'] = $this->getHelper()->objectManager()->get('\Biztech\Deliverydatepro\Helper\Data')->getDayCharges();
            $config['general']['enable_same_day_charge'] = $this->getHelper()->objectManager()->get('\Biztech\Deliverydatepro\Helper\Data')->dayWiseCharge();
        }
        $this->response[]['checkout_level'] = $config;
        $this->response[]['product_level'] = $this->proHelper->getProductLevelConfig();
        return $this->response;
    }

    private function getHelper() {
        return $this->helper;
    }

    public function enableExtension() {
        $display = false;
        $isCustomerApplicable = $this->proHelper->checkCurrentUserAllowed($this->customerSession->getCustomer()->getGroupId());
        if ($this->getHelper()->isEnable() && $this->getHelper()->getOnWhichPage() == 1 && $isCustomerApplicable) {
            $display = true;
        }
        return $display;
    }

}
