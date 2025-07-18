<?php
/**
 * Copyright (c) 2018 MageModule, LLC: All rights reserved
 *
 * LICENSE: This source file is subject to our standard End User License
 * Agreeement (EULA) that is available through the world-wide-web at the
 * following URI: https://www.magemodule.com/end-user-license-agreement/.
 *
 *  If you did not receive a copy of the EULA and are unable to obtain it through
 *  the web, please send a note to admin@magemodule.com so that we can mail
 *  you a copy immediately.
 *
 *  @author        MageModule admin@magemodule.com
 *  @copyright    2018 MageModule, LLC
 *  @license        https://www.magemodule.com/end-user-license-agreement/
 */

namespace MageModule\Core\Api;

/**
 * Interface AttributeRepositoryInterface
 *
 * @package MageModule\Core\Api
 */
interface AttributeRepositoryInterface
{
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $attributeCode
     *
     * @return \MageModule\Core\Api\Data\AttributeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($attributeCode);

    /**
     * @param \MageModule\Core\Api\Data\AttributeInterface $attribute
     *
     * @return \MageModule\Core\Api\Data\AttributeInterface
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(\MageModule\Core\Api\Data\AttributeInterface $attribute);

    /**
     * @param \MageModule\Core\Api\Data\AttributeInterface $attribute
     *
     * @return bool
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(\MageModule\Core\Api\Data\AttributeInterface $attribute);

    /**
     * @param string $attributeCode
     *
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($attributeCode);
}
