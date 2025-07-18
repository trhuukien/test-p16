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

namespace MageModule\Core\Test\Unit\Model\Data\Sanitizer;

class DateTimeTest extends \Magento\Framework\TestFramework\Unit\BaseTestCase
{
    /**
     * @var \MageModule\Core\Model\Data\Sanitizer\DateTime
     */
    private $sanitizer;

    public function setUp()
    {
        parent::setUp();

        $this->sanitizer = $this->objectManager->getObject(
            \MageModule\Core\Model\Data\Sanitizer\DateTime::class,
            ['dateTime' => $this->objectManager->getObject(\Magento\Framework\Stdlib\DateTime::class)]
        );
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [
                '2/24/18 17:10',
                '2018-02-24 17:10:00'
            ],
            [
                '2/24/18 17:10:05',
                '2018-02-24 17:10:05'
            ],
            [
                '2/24/18 5:10PM',
                '2018-02-24 17:10:00'
            ],
            [
                '2/24/18 5:10AM',
                '2018-02-24 05:10:00'
            ],
            [
                '2-24-18 5:10PM',
                '2018-02-24 17:10:00'
            ],
            [
                '02/24/2018 17:10',
                '2018-02-24 17:10:00'
            ],
            [
                '2018/02/24 17:10',
                '2018-02-24 17:10:00'
            ],
            [
                '2018/24/02 17:10',
                null
            ],
            [
                '02/24/2018',
                '2018-02-24 00:00:00'
            ],
            [
                '2018-12-11',
                '2018-12-11 00:00:00'
            ]
        ];
    }

    /**
     * @param string                     $value
     * @param string|int|float|bool|null $expected
     *
     * @dataProvider  dataProvider
     */
    public function testSanitize($value, $expected)
    {
        $result = $this->sanitizer->sanitize($value);
        if ($expected === null) {
            $this->assertNull($result);
        } else {
            $this->assertEquals($expected, $result);
        }
    }
}
