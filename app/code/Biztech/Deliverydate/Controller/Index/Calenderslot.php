<?php

namespace Biztech\Deliverydate\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Calenderslot extends Action {

	/**
	 * Store constructor.
	 * @param Context $context
	 * @param JsonFactory $resultJsonFactory
	 * @param Biztech\Deliverydate\Helper\Data $dataHelper
	 * @param Magento\Framework\Pricing\Helper\Data $priceHelper
	 * @param Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
	 * @param Magento\Framework\Stdlib\DateTime\DateTime $date
	 */
	public function __construct(
		Context $context, JsonFactory $resultJsonFactory, \Biztech\Deliverydate\Helper\Data $dataHelper, \Magento\Framework\Pricing\Helper\Data $priceHelper, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone, \Magento\Framework\Stdlib\DateTime\DateTime $date
	) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_dataHelper = $dataHelper;
		$this->_priceHelper = $priceHelper;
		$this->_timezone = $timezone;
		$this->_date = $date;
		parent::__construct($context);
	}

	/**
	 * @return mixed
	 */
	public function execute() {
		$post = $this->getRequest()->getPostValue();
		$selectedDate = str_replace("/", "-", $post['selectedDate']);
		$time = strtotime($selectedDate);
		$selected_date = date('d-m-Y', $time);
		$result = [];
		$timeslotforCalender = $this->_dataHelper->getTimeSlots();
		$total_days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$selectedDay = date('D', $time);

		$disable_slot = $this->_dataHelper->getFormattedDisableSlots();
		$disable_slot_date = $this->_dataHelper->getFormattedDisableSlotsDate();

		// print_r($disable_slot_date);
		// exit("===");
		/* Disable slot on disabled day */
		$day_disable = array();
		foreach ($disable_slot as $dslot) {
			$day = $total_days[$dslot['day']];
			if ($day == $selectedDay) {
				foreach ($dslot['time_slot'] as $value) {
					$day_disable[] = $value;
				}
			}
		}
		/* Disable slot on disabled date */
		$date_disable = array();
		foreach ($disable_slot_date as $dslot) {
			if ($dslot['date'] == $selected_date) {
				foreach ($dslot['time_slot'] as $value) {
					$date_disable[] = $value;
				}
			}
		}
		/* Disable slot for the today's overtime with time interval */
		$currenttimeobj = new \DateTime($this->_timezone->date()->format('Y-m-d h:i A'));
		$currentTime = $this->_date->date('Y-m-d h:i A', $currenttimeobj);
		if (!empty($this->_dataHelper->getTimeDiff())) {
			$time_diff = ceil($this->_dataHelper->getTimeDiff());
		} else {
			$time_diff = 1;
		}
		$delivery_start_time = $this->_date->date('Y-m-d h:i A', strtotime($currentTime) + 60 * 60 * $time_diff);

		if (count($timeslotforCalender) > 0) {
			foreach ($timeslotforCalender as $key => $value) {
				$_sTime = date('Y-m-d', $time) . ' ' . date('h:i A', strtotime($value['start_time']));
				if ((strtotime($_sTime) < strtotime($delivery_start_time))) {
					unset($timeslotforCalender[$key]);
					continue;
				}
				if (!empty($day_disable)) {
					$currentSlot = $value['start_time'] . ' - ' . $value['end_time'];
					if (in_array($currentSlot, $day_disable)) {
						$timeslotforCalender[$key]['disable_value'] = true;
						continue;
					} else {
						$timeslotforCalender[$key]['disable_value'] = false;
					}
				}
				if (!empty($date_disable)) {
					$currentSlot = $value['start_time'] . ' - ' . $value['end_time'];
					if (in_array($currentSlot, $date_disable)) {
						$timeslotforCalender[$key]['disable_value'] = true;
						continue;
					} else {
						$timeslotforCalender[$key]['disable_value'] = false;
					}
				}
				if ($value['price']) {
					if (!is_null($value['price']) && $value['price'] != 0) {
						$timeslotforCalender[$key]['price'] = $this->_priceHelper->currency($value['price'], true, false);
					}
				}
			}
			$result['timeslot'] = $timeslotforCalender;
		}
		if (empty($timeslotforCalender)) {
			$result['timeslot'] = 'empty';
		}

		$resultJson = $this->resultJsonFactory->create();
		return $resultJson->setData($result);
	}

}
