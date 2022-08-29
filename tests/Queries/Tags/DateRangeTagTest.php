<?php

/**
 * Contains Tests\SmarterU\Queries\Tags\DateRangeTagTest
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/07/22
 */

declare(strict_types=1);

namespace Tests\SmarterU\Queries\Tags;

use CBS\SmarterU\Queries\Tags\DateRangeTag;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\Queries\Tags\DateRangeTag;
 */
class DateRangeTagTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $dateFrom = new DateTime('now');
        $dateTo = new DateTime('9999-12-31');
        $dateRangeTag = (new DateRangeTag())
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo);

        self::assertEquals($dateFrom, $dateRangeTag->getDateFrom());
        self::assertEquals($dateTo, $dateRangeTag->getDateTo());
    }
}
