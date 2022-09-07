<?php

/**
 * Contains Tests\CBS\SmarterU\Queries\GetGroupQueryTest
 *
 * @author      Will Santanen <will.santanen@thecoresolution.com>
 * @copyright   $year$ Core Business Solutions
 * @license     MIT
 * @version     $version$
 * @since       2022/08/05
 */

declare(strict_types=1);

namespace Tests\CBS\SmarterU\Queries;

use CBS\SmarterU\Exceptions\MissingValueException;
use CBS\SmarterU\Queries\GetGroupQuery;
use PHPUnit\Framework\TestCase;

/**
 * Tests CBS\SmarterU\Queries\GetGroupQuery;
 */
class GetGroupQueryTest extends TestCase {
    /**
     * Tests agreement between getters and setters.
     */
    public function testAgreement() {
        $accountApi = 'account';
        $userApi = 'user';
        $name = 'My Group';
        $groupId = '1';
        $query = (new GetGroupQuery())
            ->setAccountApi($accountApi)
            ->setUserApi($userApi)
            ->setName($name);

        self::assertEquals($accountApi, $query->getAccountApi());
        self::assertEquals($userApi, $query->getUserApi());
        self::assertEquals($name, $query->getName());
        self::assertNull($query->getGroupId());

        /**
         * The two group identifiers are mutually exclusive, so calling the
         * setter for one should set the other to null.
         */
        $query->setGroupId($groupId);
        self::assertEquals($groupId, $query->getGroupId());
        self::assertNull($query->getName());

        $query->setName($name);
        self::assertEquals($name, $query->getName());
        self::assertNull($query->getGroupId());
    }
}
