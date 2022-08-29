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

    /**
     * Tests that XML generation throws the expected exception when the
     * required group identifier is not set.
     */
    public function testExceptionIsThrownWhenUserIdentifierNotSet() {
        $accountApi = 'account';
        $userApi = 'user';
        $query = (new GetGroupQuery())
            ->setAccountApi($accountApi)
            ->setUserApi($userApi);
        
        self::expectException(MissingValueException::class);
        self::expectExceptionMessage(
            'Group identifier must be specified when creating a GetGroupQuery.'
        );
        $xml = $query->toXml($accountApi, $userApi);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the group is identified by its name.
     */
    public function testXMLGeneratedAsExpectedForGroupName() {
        $accountApi = 'account';
        $userApi = 'user';
        $name = 'My Group';
        $query = (new GetGroupQuery())
            ->setName($name);

        $xml = $query->toXml($accountApi, $userApi);
        self::assertIsString($xml);
        $xmlAsElement = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xmlAsElement->getName());
        self::assertCount(4, $xmlAsElement);
        $elements = [];
        foreach ($xmlAsElement->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xmlAsElement->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xmlAsElement->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getGroup', $xmlAsElement->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xmlAsElement->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);

        $groupTag = [];
        foreach ($xmlAsElement->Parameters->Group->children() as $group) {
            $groupTag[] = $group->getName();
        }
        self::assertCount(1, $groupTag);
        self::assertContains('Name', $groupTag);
        self::assertEquals($name, $xmlAsElement->Parameters->Group->Name);
    }

    /**
     * Tests that XML generation produces the expected result when all required
     * information is present and the group is identified by its ID.
     */
    public function testXMLGeneratedAsExpectedForGroupID() {
        $accountApi = 'account';
        $userApi = 'user';
        $groupId = '1';
        $query = (new GetGroupQuery())
            ->setGroupId($groupId);

        $xml = $query->toXml($accountApi, $userApi);
        self::assertIsString($xml);
        $xmlAsElement = simplexml_load_string($xml);
        self::assertEquals('SmarterU', $xmlAsElement->getName());
        self::assertCount(4, $xmlAsElement);
        $elements = [];
        foreach ($xmlAsElement->children() as $element) {
            $elements[] = $element->getName();
        }
        self::assertContains('AccountAPI', $elements);
        self::assertEquals($accountApi, $xmlAsElement->AccountAPI);
        self::assertContains('UserAPI', $elements);
        self::assertEquals($userApi, $xmlAsElement->UserAPI);
        self::assertContains('Method', $elements);
        self::assertEquals('getGroup', $xmlAsElement->Method);
        self::assertContains('Parameters', $elements);
        $parameters = [];
        foreach ($xmlAsElement->Parameters->children() as $parameter) {
            $parameters[] = $parameter->getName();
        }
        self::assertCount(1, $parameters);
        self::assertContains('Group', $parameters);

        $groupTag = [];
        foreach ($xmlAsElement->Parameters->Group->children() as $group) {
            $groupTag[] = $group->getName();
        }
        self::assertCount(1, $groupTag);
        self::assertContains('GroupID', $groupTag);
        self::assertEquals($groupId, $xmlAsElement->Parameters->Group->GroupID);
    }
}