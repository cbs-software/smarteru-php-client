<?php

/**
 * Contains Tests\SmarterU\Queries\Tags\MatchTagTest
 *
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 */

declare(strict_types=1);

namespace Tests\SmarterU\Queries\Tags;

use CBS\SmarterU\Queries\Tags\MatchTag;
use PHPUnit\Framework\TestCase;

/**
 * Tests SmarterU\Queries\Tags\MatchTag;
 */
class MatchTagTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $matchType = 'EXACT';
        $value = 'match';
        $matchTag = (new MatchTag())
            ->setMatchType($matchType)
            ->setValue($value);

        self::assertEquals($matchType, $matchTag->getMatchType());
        self::assertEquals($value, $matchTag->getValue());
    }
}
