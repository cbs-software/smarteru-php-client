<?php

/**
 * Contains Tests\CBS\SmarterU\DataTypes\SubscriptionVariantTest.
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/03
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\DataTypes;

use CBS\SmarterU\DataTypes\SubscriptionVariant;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\DataTypes\SubscriptionVariant.
 */
class SubscriptionVariantTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $id = '1';
        $requiresCredits = true;
        $action = 'Add';

        $subscriptionVariant = (new SubscriptionVariant())
            ->setId($id)
            ->setRequiresCredits($requiresCredits)
            ->setAction($action);

        self::assertEquals($id, $subscriptionVariant->getId());
        self::assertEquals(
            $requiresCredits,
            $subscriptionVariant->getRequiresCredits()
        );
        self::assertEquals($action, $subscriptionVariant->getAction());
    }
}
